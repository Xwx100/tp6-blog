<?php
define('MAKE_DATABASE_CONF', [
    'table_name_pre'  => 'xu',
    'table_name_glue' => '_'
]);
return [
    'user'       => "create table %s (user_id bigint not null autoincrement comment '用户ID', user_name_en varchar(20) not null default '' comment '英文名', user_name_zh varchar(20) not null default '' comment '中文名',primary key `user_id`)",
    'menu'       => "create table %s (menu_id bigint not null autoincrement comment '菜单ID', menu_name_en varchar(20) not null default '' comment '英文名', menu_name_zh varchar(20) not null default '' comment '中文名',primary key `menu_id`)",
    'user_group' => "create table %s (user_id bigint not null autoincrement comment '用户ID', group_id bigint not null autoincrement comment '菜单ID', primary key `menu_id`)",
    'group'      => "create table %s (group_id bigint not null autoincrement comment '用户ID', group_name_en varchar(20) not null default '' comment '英文名', group_name_zh varchar(20) not null default '' comment '中文名',primary key `group_id`)",
];