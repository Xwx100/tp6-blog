<?php
/**
 * Created by PhpStorm.
 * User: Xu
 * Date: 2020/3/10
 * Time: 10:45
 */
namespace middles\login;

use middles\interfaces\Middle;

class Login implements Middle {

    public static $sessionConf = [
        'login_key' => 'user_info',
        'login_url' => 'admin/index/login'
    ];

    public function __construct() { }

    public function handle($request, \Closure $next) {
        $userInfo = session(self::$sessionConf['login_key']);
        if (!isset($userInfo)) {
            // 区分
            if (false === stripos($request->url(), 'admin/index/login')) {
//                $request
//                redirect()
//                app(implode())
            } else {

            }
        }

        return $next($request);
    }
}
