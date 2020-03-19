<?php
namespace app\test\controller;

use app\BaseController;
use app\test\event\Event4;
use app\test\provider\Provider1;
use app\admin\sqls\Make;

class Index extends BaseController
{
    public function index()
    {

    }

    /**
     * php public/index.php /test/index/get_admin_sql
     */
    public function get_admin_sql() {
        (new Make())->start();
    }

    /*
     * php public/index.php /test/index/get_table_attr
     */
    public function get_table_attr() {
        (new Make())->genFieldAttr();
    }

    /**
     * php public/index.php /test/index/test_event
     */
    public function test_event() {
        var_dump('事件订阅 发布 listener');
        $this->app->event->subscribe(Event4::class);

        var_dump('通过事件订阅 自动发布 事件监听');
        $this->app->event->trigger('UserLogin');

        var_dump('通过事件配置 手动发布 事件监听');
        $this->app->event->trigger('test');

        var_dump('事件绑定 重命名 Test1 = test');
        $this->app->event->trigger('Test1');
    }

    /**
     * php public/index.php /test/index/test_server
     */
    public function test_server() {
        var_dump('不支持应用级别 服务');
        var_dump(xu_get_service('provider'));
    }

    /**
     * php public/index.php /test/index/test_common
     */
    public function test_common() {
        xu_test_app_common();
    }

    /**
     * php public/index.php /test/index/test_middleware var_dum=1
     */
    public function test_middleware() {

    }

    /**
     * php public/index.php /test/index/test_provide
     */
    public function test_provide() {
//        xu_get_service('provider1');
        xu_get_service('provider2');
    }
}
