CREATE TABLE IF NOT EXISTS `#__reditem_category_related`
(
    `related_id`           int(11)         NOT NULL,
    `parent_id`           int(11)         NOT NULL
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    COMMENT="redITEM Related Categories";