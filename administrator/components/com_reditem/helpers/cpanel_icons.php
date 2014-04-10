<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class ReditemHelperCpanelIcons
 *
 * @since  2.0
 */
class ReditemHelperCpanelIcons extends JObject
{
	/**
	 * Protected! Use the getInstance
	 */
	protected function ReditemHelperCpanelIcons()
	{
		// Parent Helper Construction
		parent::__construct();
	}

	/**
	 * Some function which was in obscure reddesignhelper class.
	 *
	 * @return array
	 */
	public static function getIconArray()
	{
		$icon_array = array(
			"reditem" => array(
				"types" => array(
					"name"      => "types",
					"icon"   	=> "icon-list",
					"oldIcon"   => "reditem_items48.png",
					"title"     => "TYPES",
					'cpanelDisplay' => true,
				),
				"categories" => array(
					"name"      => "categories",
					"icon"      => "icon-folder-open",
					"oldIcon"   => "reditem_categories48.png",
					"title"     => "CATEGORIES",
					'cpanelDisplay' => true,
				),
				"items" => array(
					"name"      => "items",
					"icon"      => "icon-file",
					"oldIcon"   => "reditem_items48.png",
					"title"     => "ITEMS",
					'cpanelDisplay' => true,
				),
				"fields" => array(
					"name"      => "fields",
					"icon"      => "icon-bookmark",
					"oldIcon"   => "reditem_fields48.png",
					"title"     => "CUSTOMFIELDS",
					'cpanelDisplay' => true,
				),
				"templates" => array(
					"name"      => "templates",
					"icon"      => "icon-hdd",
					"oldIcon"   => "reditem_templates48.png",
					"title"     => "TEMPLATES",
					'cpanelDisplay' => true,
				)
				/*"keywords" => array(
					"name"      => "keywords",
					"icon"      => "icon-tags",
					"oldIcon"   => "reditem_keywords48.png",
					"title"     => "KEYWORDS",
					'cpanelDisplay' => false,
				)*/
			)
		);

		return $icon_array;
	}
}
