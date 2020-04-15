# mysql 执行 sql脚本文件  mysql -uroot -p < admin.format.sql
create database if not exists `xu_admin`;
use `xu_admin`;

# 用户-用户组-角色
create table if not exists `xu_user` (
     `user_id` bigint unsigned NOT NULL AUTO_INCREMENT,
     `user_en` varchar(50) NOT NULL,
     `user_zh` varchar(50) NOT NULL default '',
     `password` varchar(255) NOT NULL,
     `email` varchar(255) DEFAULT NULL,
     `phone` varchar(255) NOT NULL,
     `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
     `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     `is_deleted` tinyint NOT NULL DEFAULT 0,
     PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户';

create table if not exists `xu_user_group` (
    `user_id` bigint unsigned NOT NULL,
    `group_id` bigint unsigned NOT NULL,
    unique key (`user_id`, `group_id`)
) ENGINE=Innodb DEFAULT CHARSET=utf8 COMMENT='用户-组' ;

create table if not exists `xu_group` (
    `group_id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `group_en` varchar(50) NOT NULL COMMENT '组名',
    `group_zh` varchar(50) NOT NULL default '' COMMENT '组名',
    `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    primary key (`group_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='组';

create table if not exists `xu_group_role` (
    `group_id` bigint unsigned NOT NULL,
    `role_id` bigint unsigned NOT NULL,
    unique key (`group_id`, `role_id`)
) ENGINE=Innodb DEFAULT CHARSET=utf8 COMMENT='组-角色' ;

create table if not exists `xu_role` (
    `role_id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `role_en` varchar(50) NOT NULL COMMENT '角色英文名',
    `role_zh` varchar(50) NOT NULL default '' COMMENT '角色中文名',
    `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    primary key (`role_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='角色';

create table if not exists `xu_role_menu` (
    `role_id` bigint unsigned NOT NULL,
    `menu_id` bigint unsigned NOT NULL,
    unique key (`role_id`, `menu_id`)
) ENGINE=Innodb DEFAULT CHARSET=utf8 COMMENT='角色-菜单' ;

# /admin/index/login
create table if not exists `xu_menu` (
   `menu_id` bigint unsigned NOT NULL AUTO_INCREMENT,
   `menu_path` varchar(10) not null default '菜单路径',
   `menu_level` tinyint not null default 1 comment '菜单等级',
   `menu_pid` bigint unsigned not null default 0,
   `is_deleted` tinyint not null default 0,
   primary key (`menu_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='菜单';