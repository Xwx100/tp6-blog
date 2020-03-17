<?php
use services\MysqlUtils;
use services\Admin;
use services\Redirect;
use think\Container;

// 应用公共文件
if (!function_exists('xu_get_service')) {
    /**
     * @param       $name
     * @param array $args
     * @param bool  $newInstance
     *
     * @return object|\think\App|MysqlUtils|Admin|Redirect
     */
    function xu_get_service($name, array $args = [], bool $newInstance = false) {
        return \app(xu_add_name_pre($name), $args, $newInstance);
    }
}

if (!function_exists('xu_json_send')) {
    /**
     * @param array $data
     * @param int   $code
     * @param array $header
     * @param array $options
     */
    function xu_json_send($data = [], $code = 200, $header = [], $options = []) {
        json($data, $code, $header, $options)->send();
        exit();
    }
}

if (!function_exists('xu_app_delete')) {
    function xu_app_delete($name) {
        Container::getInstance()->delete(xu_add_name_pre($name));
    }
}

if (!function_exists('xu_str_f')) {
    /**
     * @param       $format
     * @param array ...$args
     *
     * @return string
     */
    function xu_str_f($format, ...$args) {
        foreach ($args as &$arg) {
            if (is_array($arg)) {
                $arg = json_encode($arg, JSON_UNESCAPED_UNICODE);
            } elseif (is_object($arg)) {
                if (method_exists($arg, '__toString')) {
                    $arg = $arg->__toString();
                } else {
                    $arg = json_encode($arg, JSON_UNESCAPED_UNICODE);
                }
            }
        }
        unset($arg);
        return sprintf($format, ...$args);
    }
}