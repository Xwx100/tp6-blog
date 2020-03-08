<?php
namespace app\app2\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        print_r(app('test_provider')->test());
        return json(['app2' => 1]);
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
