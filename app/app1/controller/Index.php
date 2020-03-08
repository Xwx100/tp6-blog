<?php
namespace app\app1\controller;

use app\app2\commons\TestFacade;
use app\app2\commons\TestFacadeInstance;
use app\BaseController;
use think\Container;

class Index extends BaseController
{
    public function index()
    {
        TestFacade::haha();
        print_r(Container::getInstance()->instances);
        print_r(Container::getInstance()->get(TestFacadeInstance::class));
        return json(['app1' => 1]);
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
