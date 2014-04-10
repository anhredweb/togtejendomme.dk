<?php
/**
* @title			FAQ Book Pro
* @version   		3.x
* @copyright   		Copyright (C) 2011-2013 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @author email   	info@minitek.gr
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die;

class FaqBookProCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__faqbookpro_content';
		$options['extension'] = 'com_faqbookpro';

		parent::__construct($options);
	}
}
