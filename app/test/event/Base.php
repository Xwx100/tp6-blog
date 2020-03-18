<?php


namespace app\test\event;


class Base {

    public function __construct() {
        var_dump(get_called_class());
    }

    public function handle() {
        return true;
    }
}