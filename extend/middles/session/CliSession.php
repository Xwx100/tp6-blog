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
class CliSession extends SessionInit{

    public $isCli = null;

    public function __construct(App $app, \think\Session $session) {
        parent::__construct($app, $session);
        $this->setCli();
    }

    public function handle($request, Closure $next) {
        return $this->assign(func_get_args(), function (Request $request, Closure $next) {
            return $next($request);
        }, function (Request $request, Closure $next) {
            $res = parent::handle($request, $next);

            $userInfo = \app()->session->get(SESSION_USER_INFO);
            if (!isset($userInfo)) {
                // 不处于登录页 且 没有用户信息 跳转 登录页
                $curUrl = $request->url();
                $inLoginAddr = null;
                foreach([APP_LOGIN_URL, FRONT_LOGIN_URL] as $url) {
                    if (false !== stripos($curUrl, $url)) {
                        $inLoginAddr = true;
                        break;
                    }
                }
                if (empty($inLoginAddr)) {
                    xu_get_service('redirect')->start($request);
                } else  {
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
                    $params = array_merge($params, [
                        'field' => ['user_id', 'user_name_en','menu_ids', 'role_ids'],
                        'join' => [
                            ['no_pre_name' => 'user_role', 'type' => 'left'],
                            ['no_pre_name' => 'role', 'type' => 'left'],
                            ['no_pre_name' => 'menu_role', 'type' => 'left'],
                            ['no_pre_name' => 'menu', 'type' => 'left'],
                        ],
                        'group_by' => ['user_id'],
                        'order_by' => [
                            ['sort_field' => 'user_id', 'sort_type' => 'desc']
                        ],
                    ]);
                    $userRoleMenus = xu_get_service('admin', ['params' => $params])->lists();
                    if ($userRoleMenus->isEmpty()) {
                        xu_json_send(xu_add_re_format([], '账号不存在 | 密码错误', 1));
                    }
                    if (empty($userRoleMenus[0]['menu_ids'])) {
                        xu_json_send(xu_add_re_format($userRoleMenus[0], '该用户 还未 分配权限', 1));
                    }
                    $userRoleMenus = $userRoleMenus->toArray();

                    Session::set(SESSION_USER_INFO, $userRoleMenus);
                    xu_get_service('admin')->addLog($userRoleMenus);

                    xu_json_send(xu_add_re_format($userRoleMenus[0], '登录成功', 0));
                }
            }

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

    /**
     * 分配
     *
     * @param          $args
     * @param callable $isCli
     * @param callable $noCli
     *
     * @return mixed
     */
    public function assign($args , callable $isCli, callable $noCli) {
        return call_user_func_array($this->isCli ? $isCli : $noCli, $args);
    }

    /**
     * 判断 cli
     *
     * @return $this
     */
    public function setCli() {
        $this->isCli = $this->app->request->isCli();
        return $this;
    }
}