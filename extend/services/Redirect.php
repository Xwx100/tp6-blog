<?php
/**
 * Created by PhpStorm.
 * User: Xu
 * Date: 2020/3/12
 * Time: 17:52
 */

namespace services;

use think\Request;

class Redirect {

    public function start(Request $request) {
        if (!$this->isJson($request)) {
            redirect(APP_LOGIN_URL, 303)->send();
        } else {
            json(add_re_format(['url' => APP_LOGIN_URL], '重定向url', 0));
        }
    }

    public function isJson(Request $request) {
        $ok = $request->isJson() || $request->isAjax()  || $request->isPjax();
        return $ok;
    }
}
