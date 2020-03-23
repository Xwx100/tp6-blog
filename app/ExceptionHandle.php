<?php
namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\facade\Log;
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
        $re = $this->convertExceptionToArray($e);
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

    protected function convertExceptionToArray(Throwable $exception): array
    {
        if ($this->app->isDebug()) {
            // 调试模式，获取详细的错误信息
            $traces = [];
            $nextException = $exception;
            do {
                $traces[] = [
                    'name'    => get_class($nextException),
                    'file'    => $nextException->getFile(),
                    'line'    => $nextException->getLine(),
                    'code'    => $this->getCode($nextException),
                    'msg' => $this->getMessage($nextException),
//                    'trace'   => $nextException->getTrace(),
                    'source'  => $this->getSourceCode($nextException),
                ];
            } while ($nextException = $nextException->getPrevious());
            $data = [
                'code'    => $this->getCode($exception),
                'msg' => $this->getMessage($exception),
                'traces'  => $traces,
                'datas'   => $this->getExtendData($exception),
                'tables'  => [
                    'GET Data'              => $this->app->request->get(),
                    'POST Data'             => $this->app->request->post(),
                    'Files'                 => $this->app->request->file(),
                    'Cookies'               => $this->app->request->cookie(),
                    'Session'               => $this->app->session->all(),
                    'Server/Request Data'   => $this->app->request->server(),
                    'Environment Variables' => $this->app->request->env(),
                    'ThinkPHP Constants'    => $this->getConst(),
                ],
            ];
        } else {
            // 部署模式仅显示 Code 和 msg
            $data = [
                'code'    => $this->getCode($exception),
                'msg' => $this->getMessage($exception),
            ];

            if (!$this->app->config->get('app.show_error_msg')) {
                // 不显示详细错误信息
                $data['msg'] = $this->app->config->get('app.error_message');
            }
        }

        return $data;
    }
}
