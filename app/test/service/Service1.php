<?php


namespace app\test\service;


use think\App;
use think\Service;

class Service1 extends Service {

    public function register() {
        $this->app->bind(xu_add_name_pre('provider'), provider::class);
    }
}