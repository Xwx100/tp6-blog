<?php


namespace app\test\provider;


class Provider1 {

    public function __construct() {
        var_dump('支持 应用容器提供' . __CLASS__);
    }
}