<?php


namespace middles\login;


use middles\interfaces\Middle;
use think\facade\Config;
use think\Request;
use think\Response;

class Log implements Middle {

    /**
     * @param Request  $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next) {
        // TODO: Implement handle() method.
        \think\facade\Log::params($request->param());
//        var_dump($request->getInput());
//        exit();
        return $next($request);
    }

    public function end(Response $res) {
        \think\facade\Log::write('Log');
        if (is_array($resParams = $res->getData())) {
            \think\facade\Log::params($resParams);
        }
    }
}
