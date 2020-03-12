<?php
define('NAME_PRE', 'xu');

if (!function_exists('add_name_pre')) {
    function add_name_pre(string $name) {
        return implode('_', [NAME_PRE, $name]);
    }
}


define('STORE_ADD_REDIS', 'redis');

define('CACHE_STORE', 'file');

define('SESSION_NAME', add_name_pre('session_id'));
define('SESSION_STORE', 'cache');
define('SESSION_STORE_TYPE', 'redis');
