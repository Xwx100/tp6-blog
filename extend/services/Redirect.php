<?php
/**
 * Created by PhpStorm.
 * User: Xu
 * Date: 2020/3/12
 * Time: 17:52
 */

namespace services;

use think\facade\Log;
use think\Request;

/**
 * 重定向
 *
 * Class Redirect
 *
 * @package services
 */

class Redirect {

    /**
     * 中间件 重定向
     *
     * @param Request     $request
     * @param string|null $url
     */
    public function start(Request $request, string $url = null) {
        // 不是json 重定向  前端首页
        if (!$this->isJson($request)) {
            redirect($url ?? FRONT_LOGIN_URL, 303);
        } else {
            // 是json 重定向 后端登录接口
            json(xu_add_re_format(['url' => $url ?? APP_LOGIN_URL], '重定向url', 0));
        }
    }

    public function isJson(Request $request) {
        $ok = $request->method() === 'POST' || $request->isJson() || $request->isAjax()  || $request->isPjax();
        return $ok;
    }
}
