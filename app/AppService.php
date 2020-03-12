<?php
declare (strict_types = 1);

namespace app;

use think\Service;
use services\Redirect;

/**
 * 应用服务类
 */
class AppService extends Service
{
    public function register()
    {
        // 服务注册
        $this->app->bind(add_name_pre('redirect'), Redirect::class);
    }

    public function boot()
    {
        // 服务启动
    }
}
