<?php
define('ALL_PRE_NAME', 'xu');

if (!function_exists('xu_add_name_pre')) {
    function xu_add_name_pre(string $name) {
        return implode('_', [ALL_PRE_NAME, $name]);
    }
}
if (!function_exists('xu_add_re_format')) {
    function xu_add_re_format(array $data, string $message, int $code) {
        return [
            'data' => $data,
            'msg'  => $message,
            'code' => $code
        ];
    }
}
if (!function_exists('xu_add_app_uuid')) {
    function xu_add_app_uuid() {
        $make = [];

        if (function_exists('posix_getpid')) {
            $make[] = posix_getpid();
        }

        $make[] = substr(md5(uniqid(md5(microtime(true)), true)),0, 12);

        return implode('.', $make);
    }
}


define('STORE_ADD_REDIS', 'redis');

define('CACHE_STORE', 'file');

define('SESSION_NAME', xu_add_name_pre('session_id'));
define('SESSION_STORE', 'cache');
define('SESSION_STORE_TYPE', 'redis');
define('SESSION_USER_INFO', xu_add_name_pre('user_info'));

define('APP_LOGIN_URL', '/admin/index/login');
define('APP_UUID', xu_add_app_uuid());

if (PHP_SAPI === 'cli') {
    $_GET['s'] = $_SERVER['argv'][1];
}
