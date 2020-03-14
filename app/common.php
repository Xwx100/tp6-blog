<?php
use services\MysqlUtils;

// 应用公共文件
if (!function_exists('get_service')) {
    /**
     * @param $name
     *
     * @return object|\think\App|MysqlUtils
     */
    function get_service($name) {
        return \app(add_name_pre($name));
    }
}
