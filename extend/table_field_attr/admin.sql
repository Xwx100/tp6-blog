create table if not exists `xu_user` (
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

create table if not exists `xu_user_role` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  unique KEY `role_group_id`(`user_id`,`role_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='用户-角色';

create table if not exists `xu_role` (
    `role_id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `role_name_en` varchar(50) NOT NULL COMMENT '组名',
    `role_name_zh` varchar(50) NOT NULL default '' COMMENT '组名',
    `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    primary key (`role_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='角色';


create table if not exists `xu_menu_role` (
    `menu_id` bigint unsigned NOT NULL,
    `role_id` bigint unsigned NOT NULL,
    `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    unique KEY `role_group_id`(`menu_id`,`role_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='菜单-角色';

create table if not exists `xu_menu` (
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


create table if not exists `xu_order` (
    `order_id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `order_name_en` varchar(50) NOT NULL COMMENT '排序英文名',
    `order_name_zh` varchar(50) NOT NULL default '' COMMENT '排序中文名',
    `content` varchar(300) not null default '[]' comment 'role_ids',
    `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    primary key (`order_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='角色排序表';
