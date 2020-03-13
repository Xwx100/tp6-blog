<?php
define('ALL_PRE_NAME', 'xu');

if (!function_exists('add_name_pre')) {
    function add_name_pre(string $name) {
        return implode('_', [ALL_PRE_NAME, $name]);
    }
}
if (!function_exists('add_re_format')) {
    function add_re_format(array $data, string $message, int $code) {
        return [
            'data' => $data,
            'msg'  => $message,
            'code' => $code
        ];
    }
}
if (!function_exists('add_app_uuid')) {
    function add_app_uuid() {
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

define('SESSION_NAME', add_name_pre('session_id'));
define('SESSION_STORE', 'cache');
define('SESSION_STORE_TYPE', 'redis');
define('SESSION_USER_INFO', add_name_pre('user_info'));

define('APP_LOGIN_URL', '/admin/index/login');
define('APP_UUID', add_app_uuid());
