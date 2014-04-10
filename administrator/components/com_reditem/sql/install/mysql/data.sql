SET FOREIGN_KEY_CHECKS=0;

LOCK TABLES `#__reditem_categories` WRITE;
ALTER TABLE `#__reditem_categories` DISABLE KEYS;
INSERT INTO `#__reditem_categories` VALUES (1, 0, 0, 1, 0, 'ROOT', 'root', 0, '', 0, '', '', '', 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', '');
ALTER TABLE `#__reditem_categories` ENABLE KEYS;
UNLOCK TABLES;

LOCK TABLES `#__reditem_types` WRITE;
ALTER TABLE `#__reditem_types` DISABLE KEYS;
INSERT INTO `#__reditem_types` VALUES (1, 'RedITEM', 'Default types', 0, 'reditem', '{"default_itemdetail_template":"1","default_categorydetail_template":"2","default_itemimage_large_width":600,"default_itemimage_large_height":600,"default_itemimage_medium_width":300,"default_itemimage_medium_height":300,"default_itemimage_small_width":150,"default_itemimage_small_height":150,"default_categoryimage_large_width":600,"default_categoryimage_large_height":600,"default_categoryimage_medium_width":300,"default_categoryimage_medium_height":300,"default_categoryimage_small_width":150,"default_categoryimage_small_height":150}');
ALTER TABLE `#__reditem_types` ENABLE KEYS;
UNLOCK TABLES;

LOCK TABLES `#__reditem_templates` WRITE;
ALTER TABLE `#__reditem_templates` DISABLE KEYS;
INSERT INTO `#__reditem_templates` VALUES (1, 'Default Item Detail', 1, '<div class="toolbox">{print_icon}</div>\r\n<h1>{item_title}</h1>\r\n<p>{item_image}</p>\r\n<p>{item_introtext}</p>\r\n<p>{item_fulltext}</p>', 'Default Item Detail template', 'view_itemdetail', 1, 0, 0, '0000-00-00 00:00:00');
INSERT INTO `#__reditem_templates` VALUES (2, 'Default Category Detail', 1, '<div class="toolbox">{print_icon}</div>\n<div class="category">\n<h1><a href="{category_link}">{category_title}</a></h1>\n{category_image}\n<p>{category_introtext}</p>\n<p>{category_fulltext}</p>\n</div>\n<p>{items_loop_start}</p>\n<p><a href="{item_link}">{item_title}</a></p>\n<p>{item_image}</p>\n<p>{item_introtext}</p>\n<p>{item_fulltext}</p>\n<p>{items_loop_end}</p>\n<hr />\n<h3>Sub Category (Featured)</h3>\n<p>{sub_featured_category_start} <a href="{sub_category_link}">{sub_category_title}</a>{sub_category_image}</p>\n<p>{sub_category_introtext}</p>\n<p>{sub_category_fulltext}</p>\n<p>{sub_featured_category_end}</p>\n<hr />\n<h3>Sub Category</h3>\n<p>{sub_category_start}<a href="{sub_category_link}">{sub_category_title}</a>{sub_category_image}</p>\n<p>{sub_category_introtext}</p>\n<p>{sub_category_fulltext}</p>\n<p>{sub_category_end}</p>', 'Default Category detail template', 'view_categorydetail', 1, 0, 0, '0000-00-00 00:00:00');
ALTER TABLE `#__reditem_templates` ENABLE KEYS;
UNLOCK TABLES;

SET FOREIGN_KEY_CHECKS=1;