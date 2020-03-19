<?php
namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\helper\Arr;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 支持 cli 命令行
        if (PHP_SAPI === 'cli') {
            var_dump($exception->getMessage());
            var_dump($exception->getTraceAsString());
            exit();
        }
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 添加自定义异常处理机制
        if (empty(xu_get_service('redirect')->isJson($request))) {
            // 其他错误交给系统处理
            return parent::render($request, $e);
        }
        // 不是get 请求 统一json
        $re = parent::convertExceptionToArray($e);
        $re['msg'] = Arr::pull($re, 'message');
        // 测试 统一全部
        if ($this->isDebug()) {
            return json($re);
        }
        // 线上 仅仅只有两个
        return json(Arr::only($re, ['code', 'msg']));
    }

    /**
     *
     * @return bool
     */
    public function isDebug() {
        return $this->app->isDebug();
    }
}
