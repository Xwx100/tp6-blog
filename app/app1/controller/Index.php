<?php
namespace app\app1\controller;

use app\app2\commons\TestFacade;
use app\app2\commons\TestFacadeInstance;
use app\BaseController;
use think\facade\Log;

class Index extends BaseController
{
    public function index()
    {
        Log::write(1111111);
        session('test0', 1111);
        var_dump($this->request->session());
//        TestFacade::haha();
//        print_r(Container::getInstance()->get(TestFacadeInstance::class));
        return json(['app1' => 1]);
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
