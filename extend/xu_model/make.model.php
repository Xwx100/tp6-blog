<?php
// tp5 模块 路径 = tp6 应用路径
$module = 'admin';

return [
    'databases' => [
        'xu_admin'
    ],
    // 可以配置 %namespace=命名空间 %name=类名 %modelPos=模型类空间名 %prop=属性数组
    'replace'      => [
        // 输出空间
        '%namespace' => "app\\$module\\model",
        '%modelPos'      => '\\' . XuModel::class
    ],
    // 会自动 匹配 过滤掉 数据库带有的前缀
    'database_pre' => env('database.prefix') ?: app()->config->get('database.prefix'),
    // 是否删除 以前的
    'is_delete_before' => true,
    'input_tpl_file'    => __DIR__ . DIRECTORY_SEPARATOR . './model.un_format.tpl',
    // 输出目录
    'out_tpl_dir'      => implode(DIRECTORY_SEPARATOR, [rtrim(app()->getBasePath(), DIRECTORY_SEPARATOR), $module, 'model']),
];