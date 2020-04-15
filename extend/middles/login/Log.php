<?php


namespace middles\login;


use middles\interfaces\Middle;
use think\facade\Config;
use think\facade\Env;
use think\Request;
use think\Response;

/**
 * 日志 增加uuid字段 用于标明一个请求
 *
 * Class Log
 *
 * @package middles\login
 */
class Log implements Middle {

    /**
     * @param Request  $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next) {
        // 忽略 警告
        error_reporting(E_ALL ^ E_NOTICE);

        $params = $request->param();
        \think\facade\Log::params(array_merge($params, ['method' => $request->method()]));
        return $next($request);
    }

    public function end(Response $res) {
        \think\facade\Log::write('Log');
        if (is_array($resParams = $res->getData())) {
            array_merge($resParams, [
                'total_time' => app()->getBeginTime() - time()
            ]);
            \think\facade\Log::params($resParams);
        }
    }
}
