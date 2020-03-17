<?php
declare (strict_types = 1);

namespace app;

use think\Service;
use services\Redirect;
use services\MysqlUtils;
use services\Admin;

/**
 * 应用服务类
 */
class AppService extends Service
{
    public function register()
    {
        // 服务注册
        $this->app->bind(xu_add_name_pre('redirect'), Redirect::class);
        $this->app->bind(xu_add_name_pre('mysql_utils'), MysqlUtils::class);
        $this->app->bind(xu_add_name_pre('admin'), Admin::class);
    }

    public function boot()
    {
        // 服务启动
    }
}
