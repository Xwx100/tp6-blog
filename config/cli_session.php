<?php
define('EXTEND_FIELD_ATTR_PATH', implode('/', [app()->getRootPath(), 'extend', 'table_field_attr']));

return [
    // admin权限 总开关
    'open_session' => true,
    'session_value' => [
        'user_name_en' => 'cli',
    ],
    // 白名单： 是否 检测有菜单
    'white_list' => [1],
    // 黑名单：直接 不检测
    'black_list' => [2],
];