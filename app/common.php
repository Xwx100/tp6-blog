<?php
use services\MysqlUtils;
use services\Admin;
use services\Redirect;
use think\Container;

// 应用公共文件
if (!function_exists('get_service')) {
    /**
     * @param       $name
     * @param array $args
     * @param bool  $newInstance
     *
     * @return object|\think\App|MysqlUtils|Admin|Redirect
     */
    function get_service($name, array $args = [], bool $newInstance = false) {
        return \app(add_name_pre($name), $args, $newInstance);
    }
}

// 应用公共文件
if (!function_exists('json_send')) {
    /**
     * @param array $data
     * @param int   $code
     * @param array $header
     * @param array $options
     */
    function json_send($data = [], $code = 200, $header = [], $options = []) {
        json($data, $code, $header, $options)->send();
    }
}

if (!function_exists('app_delete')) {
    function app_delete($name) {
        Container::getInstance()->delete(add_name_pre($name));
    }
}
