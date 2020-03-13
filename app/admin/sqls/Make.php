<?php
/**
 * 基础权限表
 * Class Make
 */

class Make {

    public $tableName = ALL_PRE_NAME;
    public $tableNameGlue = '_';

    // 用户(user) 用户组(user_group) 组(group && type = user|menu) 菜单组(menu_group) 菜单(menu)
    public static $tables = [
        'user'       => "create table %s (user_id bigint not null autoincrement comment '用户ID', user_name_en varchar(20) not null default '' comment '英文名', user_name_zh varchar(20) not null default '' comment '中文名',primary key `user_id`)",
        'menu'       => "create table %s (menu_id bigint not null autoincrement comment '菜单ID', menu_name_en varchar(20) not null default '' comment '英文名', menu_name_zh varchar(20) not null default '' comment '中文名',primary key `menu_id`)",
        'group'      => "create table %s (group_id bigint not null autoincrement comment '组ID', group_name_en varchar(20) not null default '' comment '英文名', group_name_zh varchar(20) not null default '' comment '中文名',primary key `group_id`)",
        'user_group' => "create table %s (user_id bigint not null comment '用户ID', group_id bigint not null comment '组ID', primary key `menu_id`)",
        'menu_group' => "create table %s (menu_id bigint not null autoincrement comment '用户ID', group_id bigint not null comment '组ID', primary key `menu_id`)",
    ];
}
