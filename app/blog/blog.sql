CREATE TABLE IF NOT EXISTS `xu_site_conf`
(
    sc_id         bigint      not null auto_increment,
    sc_name_en    varchar(30) not null comment '配置名',
    sc_name_zh    varchar(50) not null comment '配置名',
    sc_val_normal varchar(50) not null default '' comment '配置值',
    sc_val_json   text        not null comment '配置json=[]',
    sc_type       varchar(20) not null default 'common' comment '配置类型',
    is_deleted    tinyint     not null default 0,
    primary key (sc_id),
    key (sc_type, sc_name_en)
) engine = innodb
  default charset = 'utf-8' comment '网站配置';

CREATE TABLE if not exists `xu_articles`
(
    article_id            bigint     NOT NULL AUTO_INCREMENT,
    user_id               bigint(20) NOT NULL,
    article_title         text       NOT NULL,
    article_content       longtext   NOT NULL,
    article_views         bigint(20) NOT NULL,
    article_comment_count bigint(20) NOT NULL,
    article_date          datetime   NOT NULL,
    article_like_count    bigint(20) NOT NULL,
    PRIMARY KEY (article_id),
    KEY user_id (user_id)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8;