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

jimport('joomla.application.component.controlleradmin');

class FAQBookProControllerVotes extends JControllerAdmin
{
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	public function getModel($name = 'Vote', $prefix = 'FAQBookProModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	protected function postDeleteHook(JModelLegacy $model, $ids = null)
	{
	}

}
