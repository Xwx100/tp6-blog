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

    protected $infos = array(
        'user' => 'app\admin\model\User',
        'user_role' => 'app\admin\model\UserRole',
        'role' => 'app\admin\model\Role',
        'menu_role' => 'app\admin\model\MenuRole',
        'menu' => 'app\admin\model\Menu'
    );

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
    `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (`menu_id`),
    key (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='菜单';
",
        'role' => "
create table if not exists `%s` (
    `role_id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `role_name_en` varchar(50) NOT NULL COMMENT '组名',
    `role_name_zh` varchar(50) NOT NULL default '' COMMENT '组名',
    `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    primary key (`role_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='角色';
",
        'user_role'      => "
create table if not exists `%s` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  unique KEY `role_group_id`(`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户-角色';
",
        'menu_role' => "
create table if not exists `%s` (
    `menu_id` bigint unsigned NOT NULL,
    `role_id` bigint unsigned NOT NULL,
    `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    unique KEY `role_group_id`(`menu_id`,`role_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='菜单-角色';
",
        'order' => "
create table if not exists `%s` (
    `order_id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `order_name_en` varchar(50) NOT NULL COMMENT '排序英文名',
    `order_name_zh` varchar(50) NOT NULL default '' COMMENT '排序中文名',
    `content` varchar(300) not null default '[]' comment 'role_ids',
    `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    primary key (`order_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='角色排序表';",
    ];

    /**
     * 生成 创建表语句
     *
     * @return bool
     */
    public function start(): bool {
        array_walk(self::$tables, function (&$v, $k) {
            $v = sprintf((string)$v, implode($this->tableNameGlue, [$this->tableNamePre, $k]));
        }, ARRAY_FILTER_USE_BOTH);
        $sql = implode("\n", array_values(self::$tables));
        return file_put_contents(EXTEND_FIELD_ATTR_PATH . DIRECTORY_SEPARATOR . 'admin.sql', $sql);
    }

    /**
     * 生成 表属性（必须要先建model）
     */
    public function genFieldAttr() {
        $funcName = 'get_service';
        if (!function_exists($funcName)) {
            $funcName = '';
        }
        $fails = array_map(function ($i) use ($funcName) {
            return $funcName('mysql_utils')->descToAttr(new $i);
        }, $this->infos);

        var_export($fails);
    }
}