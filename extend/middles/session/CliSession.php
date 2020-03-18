<?php


namespace middles\session;

use Closure;
use middles\interfaces\Middle;
use think\App;
use think\middleware\SessionInit;
use think\Request;
use think\Response;

/**
 * 使session支持命令行 cli
 * Class Session
 *
 * @package middles\session
 */
class CliSession extends SessionInit {

    public $isCli = null;

    public function __construct(App $app, \think\Session $session) {
        parent::__construct($app, $session);
        $this->setCli();
    }

    public function handle($request, Closure $next) {
        return $this->assign(func_get_args(), function ($request, Closure $next) {
            return $next($request);
        }, function ($request, Closure $next) {
            return parent::handle($request, $next);
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