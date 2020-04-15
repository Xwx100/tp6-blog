<?php
/**
 * app 定义常量
 */
define('APP_NAME_LOW', 'xu_');
define('APP_NAME_UP', 'XU_');
define('XU_IS_CLI', PHP_SAPI === 'cli');

/**
 * tp6 自带常量
 */
// tp6.cache
define('XU_CACHE_DRIVER_DEFAULT', 'redis');
define('XU_CACHE_DRIVER_CONF', [
    XU_CACHE_DRIVER_DEFAULT => [
        'type'       => XU_CACHE_DRIVER_DEFAULT,
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'password'   => '',
        'select'     => 0,
        'timeout'    => 0,
        'expire'     => 0,
        'persistent' => false,
        'prefix'     => '',
        'tag_prefix' => 'tag:',
        'serialize'  => [],
    ]
]);
// tp6.session
define('XU_SESSION_NAME', xu_add_name_pre('cookie_id'));
define('XU_SESSION_STORE', 'cache');
define('XU_SESSION_STORE_TYPE', 'redis');
define('XU_SESSION_CHECK_KEY', xu_add_name_pre('user_info'));

define('APP_LOGIN_URL', '/admin/index/login');
define('FRONT_LOGIN_URL', '/index.html');
define('APP_UUID', xu_add_app_uuid());

/**
 * 增加 app 前缀
 *
 * @param string $name
 *
 * @return string
 */
function xu_add_name_pre(string $name) {
    return APP_NAME_LOW . $name;
}
/**
 * 增加格式化
 *
 * @param array  $data
 * @param string $message
 * @param int    $code
 *
 * @return array
 */
function xu_add_re_format(array $data, string $message, int $code = 0) {
    return [
        'data' => $data,
        'msg'  => $message,
        'code' => $code
    ];
}

/**
 * uuid
 *
 * @return string
 */
function xu_add_app_uuid() {
    $make = [];

    if (function_exists('posix_getpid')) {
        $make[] = posix_getpid();
    }

    $make[] = substr(md5(uniqid(md5(microtime(true)), true)), 0, 12);

    return implode('.', $make);
}

/**
 * tp6 兼容 cli
 */
XU_IS_CLI && $_GET['s'] = $_REQUEST['s'] = $_SERVER['argv'][1];
