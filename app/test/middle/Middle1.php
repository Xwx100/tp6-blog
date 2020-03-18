<?php


namespace app\test\middle;


use app\Request;

class Middle1 {

    public function handle(Request $req, \Closure $next) {
        if ($req->isCli() && $_SERVER['argv'][2]) {
            var_dump('支持应用级别 中间件');
            var_dump('应用名' . app()->http->getName());
        }

        return $next($req);
    }
}