<?php
/**
 * 基础权限表
 * Class Make
 */
namespace app\admin\sqls;

class Make {

    public $tableNamePre = ALL_PRE_NAME;
    public $tableNameGlue = '_';
    public $databaseIsExist = 'create database if not exists %s';

    // 用户(user) 用户组(user_group) 组(group && type = user|menu) 菜单组(menu_group) 菜单(menu)
    public static $tables = [
        'user'       => "
create table if not exists `%s` (
  `user_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_name_en` varchar(50) NOT NULL,
  `user_name_zh` varchar(50) NOT NULL default '',
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`),
  key (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户';
",
        'menu'       => "
create table if not exists `%s` (
  `menu_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_name_en` varchar(50) NOT NULL,
  `menu_name_zh` varchar(50) NOT NULL default '',
  `path` varchar(255) NOT NULL,
  `level` smallint unsigned NOT NULL default 0,
  `role_id` bigint unsigned NOT NULL default 0,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`menu_id`),
  key (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='菜单';
",
        'role' => "
create table if not exists `%s` (
  `role_id` bigint unsigned NOT NULL,
  `role_name_en` varchar(50) NOT NULL COMMENT '组名',
  `role_name_zh` varchar(50) NOT NULL default '' COMMENT '组名',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  primary key (`role_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='角色';
",
        'group'      => "
create table if not exists `%s` (
  `group_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group_name_en` varchar(50) NOT NULL COMMENT '组名',
  `group_name_zh` varchar(50) NOT NULL default '' COMMENT '组名',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`group_id`),
  key (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户组';
",
        'user_group' => "
create table if not exists `%s` (
  `user_id` bigint unsigned NOT NULL,
  `group_id` bigint unsigned NOT NULL,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  unique KEY `role_group_id`(`user_id`,`group_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='用户-用户组';
",
        'user_role' => "
create table if not exists `%s` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  unique KEY `role_group_id`(`user_id`,`role_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='用户-角色';",
    ];

    public function start() {
        array_walk(self::$tables, function (&$v, $k) {
            $v = sprintf((string)$v, implode($this->tableNameGlue, [$this->tableNamePre, $k]));
        }, ARRAY_FILTER_USE_BOTH);
        return implode("\n", array_values(self::$tables));
    }
}