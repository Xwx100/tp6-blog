<?php
return [
    'admin_format' => [
        // sql 格式化
        'replace' => [
            '%db_name' => 'admin',
            '%pre' => 'xu_',
            '%user' => 'user',
            '%group' => 'group',
            '%role' => 'role',
            '%menu' => 'menu'
        ],
        // sql 格式化文件 输出 位置
        'input_filename' => __DIR__ . DIRECTORY_SEPARATOR . 'admin.un_format.sql',
        'out_filename' => __DIR__ . DIRECTORY_SEPARATOR . 'admin.format.sql'
    ]
];