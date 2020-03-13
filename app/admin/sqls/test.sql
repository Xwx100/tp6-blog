CREATE TABLE `user` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户'

CREATE TABLE `group` (
  `group_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group_name_en` varchar(50) NOT NULL COMMENT '组名',
  `group_name_zh` varchar(50) NOT NULL default '' COMMENT '组名',
  `type` varchar(10) not null COMMENT 'user|menu',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`group_id`),
  key (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='组'

create table `role` (
  `role_id` bigint unsigned NOT NULL,
  `group_id` bigint unsigned NOT NULL,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  UNION KEY `role_group_id`(`role_id`,`group_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='用户组'

create table `user_role` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  UNION KEY `role_group_id`(`role_id`,`group_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='用户组'

CREATE TABLE `menu` (
  `menu_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_name_en` varchar(50) NOT NULL,
  `menu_name_zh` varchar(50) NOT NULL default '',
  `path` varchar(255) NOT NULL,
  `group_id` bigint unsigned NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`),
  key (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='菜单'



create table `sort` (
  `sort_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sort` varchar(1000) default '[]',
)























user join user_role using(user_id) join role using(role_id) join group using(group_id) join menu using(menu_id)


