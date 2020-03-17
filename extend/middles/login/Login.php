<?php
/**
 * Created by PhpStorm.
 * User: Xu
 * Date: 2020/3/10
 * Time: 10:45
 */

namespace middles\login;

use middles\interfaces\Middle;
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
        $userInfo = session(SESSION_USER_INFO);
        if (!isset($userInfo)) {
            // session 无用户信息 跳转
            if (false === stripos($request->url(), 'admin/index/login')) {
                get_service('redirect')->start($request);
            } else {
                $params = $request->param(['user_name_en', 'password']);
                // 增加参数
                $params = array_merge($params, ['is_deleted' => 0]);
                // 查看用户菜单
                $params = array_merge($params, [
                    'field' => ['user_id', 'menu_ids', 'role_ids'],
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
                $userRoleMenus = get_service('admin', ['params' => $params])->lists();
                if ($userRoleMenus->isEmpty()) {
                    json_send(add_re_format([], '账号不存在 | 密码错误', 1));
                }
                if (empty($userRoleMenus[0]['menu_ids'])) {
                    json_send(add_re_format($userRoleMenus[0], '该用户 还未 分配权限', 1));
                }

                Session::set(SESSION_USER_INFO, $userRoleMenus);
            }
        }

        return $next($request);
    }

    public function end(Response $res) {
        \think\facade\Log::write('Login');
    }
}
