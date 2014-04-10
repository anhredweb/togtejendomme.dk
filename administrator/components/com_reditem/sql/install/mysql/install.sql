SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `#__reditem_categories`
(
    `id`                    int(11)             NOT NULL AUTO_INCREMENT,
    `parent_id`             int(10) unsigned    NOT NULL DEFAULT '0',
    `lft`                   int(11)             NOT NULL DEFAULT '0',
    `rgt`                   int(11)             NOT NULL DEFAULT '0',
    `level`                 int(10) unsigned    NOT NULL DEFAULT '0',
    `title`                 varchar(255)        NOT NULL,
    `alias`                 varchar(255)        NOT NULL DEFAULT '',
    `access`                tinyint(3) unsigned NOT NULL DEFAULT '0',
    `path`                  varchar(255)        NOT NULL DEFAULT '',
    `type_id`               int(11)             NOT NULL DEFAULT '0',
    `category_image`        varchar(255)        NOT NULL DEFAULT '',
    `introtext`             mediumtext          NOT NULL,
    `fulltext`              text                NOT NULL,
    `template_id`           int(11)             NOT NULL,
    `featured`              tinyint(1)          NOT NULL DEFAULT '0',
    `ordering`              int(11)             NOT NULL DEFAULT '0',
    `published`             tinyint(1)          NOT NULL,
    `checked_out`           int(10) unsigned    NOT NULL DEFAULT '0',
    `checked_out_time`      datetime            NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_user_id`       int(10) unsigned    NOT NULL DEFAULT '0',
    `created_time`          datetime            NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_user_id`      int(10) unsigned    NOT NULL DEFAULT '0',
    `modified_time`         datetime            NOT NULL DEFAULT '0000-00-00 00:00:00',
    `params`                varchar(2048)       NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY `idx_left_right` (`lft`,`rgt`)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__reditem_fields`
(
    `id`                    int(10)             NOT NULL AUTO_INCREMENT,
    `type_id`               int(11)             NOT NULL DEFAULT '0',
    `tag`                   varchar(100)        NOT NULL,
    `type`                  varchar(255)        NOT NULL,
    `ordering`              int(11)             NOT NULL DEFAULT '0',
    `published`             tinyint(1)          NOT NULL,
    `min`                   int(5)              NOT NULL,
    `max`                   int(5)              NOT NULL,
    `name`                  varchar(255)        NOT NULL,
    `tips`                  text                NOT NULL,
    `frontend_edit`         tinyint(1)          NOT NULL DEFAULT '0',
    `required`              tinyint(1)          NOT NULL DEFAULT '0',
    `searchable`            tinyint(1)          NOT NULL DEFAULT '1',
    `in_lists`              tinyint(1)          NOT NULL DEFAULT '0',
    `options`               text                NOT NULL DEFAULT '',
    `fieldcode`             varchar(255)        NOT NULL,
    `operator`              varchar(255)        NOT NULL,
    `compare_fieldcode`     varchar(255)        NOT NULL,
    `extra`                 tinyint(1)          NOT NULL DEFAULT '0',
    `filter`                tinyint(1)          NOT NULL DEFAULT '0',
    `infotext`              tinyint(1)          NOT NULL DEFAULT '0',
    `checked_out`           int(11)             NOT NULL,
    `checked_out_time`      datetime            NOT NULL,
    `params`                varchar(2048)       NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__reditem_items`
(
    `id`                    int(11)             NOT NULL AUTO_INCREMENT,
    `title`                 varchar(255)        NOT NULL,
    `alias`                 varchar(255)        NOT NULL DEFAULT '',
    `introtext`             text                NOT NULL,
    `fulltext`              text                NOT NULL,
    `item_image`            varchar(255)        NOT NULL,
    `extra_images`          mediumtext          NOT NULL,
    `ordering`              int(11)             NOT NULL DEFAULT '0',
    `access`                tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
    `published`             tinyint(1)          NOT NULL,
    `featured`              tinyint(4)          NOT NULL DEFAULT '0',
    `type_id`               int(11)             NOT NULL,
    `template_id`           int(11)             NOT NULL,
    `checked_out`           int(11)             NOT NULL DEFAULT '0',
    `checked_out_time`      datetime            NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_user_id`       int(10) unsigned    NOT NULL DEFAULT '0',
    `created_time`          datetime            NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_user_id`      int(10) unsigned    NOT NULL DEFAULT '0',
    `modified_time`         datetime            NOT NULL DEFAULT '0000-00-00 00:00:00',
    `params`                varchar(2048)       NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__reditem_item_category_xref`
(
    `item_id`               int(11)         NOT NULL,
    `category_id`           int(11)         NOT NULL,
    PRIMARY KEY (`item_id`,`category_id`)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__reditem_item_keyword_xref`
(
    `item_id`               int(11)         NOT NULL,
    `keyword_id`            int(11)         NOT NULL,
    PRIMARY KEY (`item_id`,`keyword_id`)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    COMMENT='Item Keyword Cross reference';

CREATE TABLE IF NOT EXISTS `#__reditem_keywords`
(
    `id`                    int(11)         NOT NULL AUTO_INCREMENT,
    `parent_id`             int(11)         NOT NULL,
    `input_id`              int(11)         NOT NULL,
    `name`                  varchar(255)    NOT NULL,
    `treelevel`             int(11)         NOT NULL DEFAULT '0',
    `treeorder`             int(11)         NOT NULL DEFAULT '0',
    `ordering`              int(11)         NOT NULL DEFAULT '0',
    `published`             tinyint(1)      NOT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__reditem_templates`
(
    `id`                    int(11)         NOT NULL AUTO_INCREMENT,
    `name`                  varchar(255)    NOT NULL,
    `type_id`               int(11)         NOT NULL DEFAULT '0',
    `content`               mediumtext      NOT NULL,
    `description`           varchar(255)    NOT NULL,
    `typecode`              varchar(255)    NOT NULL,
    `published`             tinyint(1)      NOT NULL,
    `ordering`              int(11)         NOT NULL DEFAULT '0',
    `checked_out`           int(11)         NOT NULL DEFAULT '0',
    `checked_out_time`      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__reditem_types`
(
    `id`                    int(11)         NOT NULL AUTO_INCREMENT,
    `title`                 varchar(255)    NOT NULL,
    `description`           text            NOT NULL DEFAULT '',
    `ordering`              int(11)         NOT NULL DEFAULT '0',
    `table_name`            varchar(200)    NOT NULL,
    `params`                varchar(2048)   NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__reditem_category_related`
(
    `related_id`           int(11)         NOT NULL,
    `parent_id`           int(11)         NOT NULL
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    COMMENT="redITEM Related Categories";

/*
 * Create table for default type 'reditem'
 */
CREATE TABLE IF NOT EXISTS `#__reditem_types_reditem`
(
    `id`                    int(11)         NOT NULL DEFAULT '0'
)
  ENGINE=InnoDB
  DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;