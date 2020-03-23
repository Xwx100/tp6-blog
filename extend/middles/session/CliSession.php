<?php


namespace middles\session;

use Closure;
use middles\interfaces\Middle;
use think\App;
use think\facade\Log;
use think\facade\Session;
use think\middleware\SessionInit;
use think\Request;
use think\Response;

/**
 * 使session支持命令行 cli
 * Class Session
 *
 * @package middles\session
 */
class CliSession extends SessionInit {

    public $isOpen = null;
    public $isLoginUrl = null;

    public function __construct(App $app, \think\Session $session) {
        parent::__construct($app, $session);
        $this->setOpen();
    }

    public function handle($request, Closure $next) {
        // 判断 当前url 是否是登录页
        $curUrl = $request->url();
        foreach([APP_LOGIN_URL, FRONT_LOGIN_URL] as $url) {
            if (false !== stripos($curUrl, $url)) {
                $this->isLoginUrl = true;
                break;
            }
        }

        return $this->assign(func_get_args(), function (Request $request, Closure $next) {
            // 不开启 权限限制 默认用户
            $this->app->session->set(SESSION_USER_INFO, config('xu_admin.session_value'));
            if (!empty($this->isLoginUrl)) {
                xu_json_send(xu_add_re_format($this->app->session->get(SESSION_USER_INFO), '已经登录'));
            }

            return $next($request);
        }, function (Request $request, Closure $next) {
            // 开启 session 权限限制
            $this->base($request);

            $res = $next($request);

            $res->setSession($this->session);
            $cookieName = config('session.name');
            if (!$request->cookie($cookieName)) {
                $this->app->cookie->set($cookieName, $this->session->getId());
            }

            $userInfo = \app()->session->get(SESSION_USER_INFO);

            // 处于登录页 且 有用户信息 已经登录成功
            if (isset($userInfo)) {
                return $res;
            }
            // 不处于登录页 且 没有用户信息 跳转 登录页
            if (empty($this->isLoginUrl)) {
                xu_get_service('redirect')->start($request);
                return $res;
            }
            $keys = ['user_name_en', 'password'];
            $params = \request()->param($keys);
            foreach ($keys as $key) {
                if (empty($params[$key])) {
                    xu_json_send(xu_add_re_format([], "用户名为空 | 密码为空", 1));
                }
            }
            // 增加参数
            $params = array_merge($params, ['is_deleted' => 0]);
            // 查看用户菜单
            $userRoleMenus = xu_get_service('admin')->initParams($params)->lists()[0];
            if ($userRoleMenus->isEmpty()) {
                xu_json_send(xu_add_re_format([], '账号不存在 | 密码错误', 1));
            }
            if ($userRoleMenus['role_ids']) {
                xu_json_send(xu_add_re_format([], '该用户 还未 分配角色', 1));
            }
            if (array_intersect(config('xu_admin.white_list'), $userRoleMenus['role_ids'])) {

            } elseif (array_intersect(config('xu_admin.black_list'), $userRoleMenus['role_ids'])) {
                xu_json_send(xu_add_re_format($userRoleMenus, '该用户 处于黑名单', 1));
            } elseif (empty($userRoleMenus['menu_ids'])) {
                xu_json_send(xu_add_re_format($userRoleMenus, '该用户 还未 分配权限', 1));
            }
            $userRoleMenus = $userRoleMenus->toArray();

            $this->app->session->set(SESSION_USER_INFO, $userRoleMenus);
            xu_get_service('admin')->addLog($userRoleMenus);

            xu_json_send(xu_add_re_format($userRoleMenus, '登录成功', 0));
            return $res;
        });
    }

    public function end(Response $response) {
        $this->assign(func_get_args(), function (Response $response) {
            exit();
        }, function (Response $response) {
            parent::end($response);
        });
    }

    public function base(Request $request) {
        // Session初始化
        $varSessionId = $this->app->config->get('session.var_session_id');
        $cookieName   = $this->session->getName();

        if ($varSessionId && $request->request($varSessionId)) {
            $sessionId = $request->request($varSessionId);
        } else {
            $sessionId = $request->cookie($cookieName);
        }

        if ($sessionId) {
            $this->session->setId($sessionId);
        }

        $this->session->init();

        $request->withSession($this->session);
    }

    /**
     * 分配
     *
     * @param          $args
     * @param callable $isOpen
     * @param callable $noOpen
     *
     * @return mixed
     */
    public function assign($args , callable $isOpen, callable $noOpen) {
        return call_user_func_array($this->isOpen ? $isOpen : $noOpen, $args);
    }

    /**
     * 是否 开启 session
     *
     * @return $this
     */
    public function setOpen() {
        $this->isOpen = $this->app->request->isCli() || empty(config('xu_admin.open_session'));
        return $this;
    }
}