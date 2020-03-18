<?php


namespace app\test\provider;


class Provider2 {

    public function __construct() {
        var_dump('支持 应用容器提供' . __CLASS__);
    }
}