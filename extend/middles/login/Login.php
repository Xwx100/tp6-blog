<?php
/**
 * Created by PhpStorm.
 * User: Xu
 * Date: 2020/3/10
 * Time: 10:45
 */
namespace middles\login;

use middles\interfaces\Middle;
use services\Redirect;
use think\Request;
use think\Response;

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
            // 区分
            if (false === stripos($request->url(), 'admin/index/login')) {
                $this->getRedirect()->start($request);
            } else {
                $params = $request->param();
            }
        }

        return $next($request);
    }

    public function end(Response $res) {
        \think\facade\Log::write('Login');
    }

    /**
     * @return Redirect
     */
    public function getRedirect() {
        return app(add_name_pre('redirect'));
    }
}
