CREATE TABLE IF NOT EXISTS `#__reditem_category_fields`
(
    `id`                    int(10)             NOT NULL AUTO_INCREMENT,
    `type`                  varchar(255)        NOT NULL,
    `ordering`              int(11)             NOT NULL DEFAULT '0',
    `published`             tinyint(1)          NOT NULL,
    `name`                  varchar(255)        NOT NULL,
    `required`              tinyint(1)          NOT NULL DEFAULT '0',
    `options`               text                NOT NULL DEFAULT '',
    `fieldcode`             varchar(255)        NOT NULL,
    `checked_out`           int(11)             NOT NULL,
    `checked_out_time`      datetime            NOT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__reditem_category_fields_value`
(
    `cat_id`                    int(10)             NOT NULL,
    PRIMARY KEY (`cat_id`)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    AUTO_INCREMENT=1;