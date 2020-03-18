<?php
/**
 * Created by PhpStorm.
 * User: Xu
 * Date: 2020/3/10
 * Time: 10:45
 */

namespace middles\login;

use middles\interfaces\Middle;
use think\facade\App;
use think\facade\Session;
use think\Request;
use think\Response;

/**
 * 验证登录
 *
 * Class Login
 *
 * @package middles\login
 */
class Login implements Middle {

    /**
     * @param Request  $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next) {
        if ($request->isCli()) {
            return $next($request);
        }

        $userInfo = \app()->session->get(SESSION_USER_INFO);
        if (!isset($userInfo)) {
            // session 无用户信息 跳转
            if (false === stripos($request->url(), APP_LOGIN_URL)) {
                xu_get_service('redirect')->start($request);
            } else  {
                if ($request->isGet()) {
//                    return view('');
                }
                $keys = ['user_name_en', 'password'];
                $params = \request()->param($keys);
                foreach ($keys as $key) {
                    if (empty($params[$key])) {
                        return xu_json_send(xu_add_re_format([], "用户名为空 | 密码为空", 1));
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

                xu_json_send(xu_add_re_format($userRoleMenus[0], '该用户 还未 分配权限', 1));
            }
        }

        return $next($request);
    }

    public function end(Response $res) {
        \think\facade\Log::write('Login');
    }
}
