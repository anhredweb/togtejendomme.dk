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
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
class FaqBookProControllerFaqBook extends JControllerLegacy
{

  function __construct() 
	{		
		parent::__construct();	
		$this->registerTask('loadHome', 'loadHome');
	}
		
	function loadHome()
	{
		$this->loadHomeContent();
	}

	private function loadHomeContent()
	{
	  JSession::checkToken('request') or jexit('Invalid token');
			
		$itemsString = JRequest::getVar('items');
		$items = explode(",", $itemsString);
		$cols = JRequest::getVar('cols');
		$title = JRequest::getVar('title');
		$desc = JRequest::getVar('desc');
		$limit = JRequest::getVar('limit');
		$image = JRequest::getVar('image');
		$imgsize = JRequest::getVar('imgsize');
		$imgheight = JRequest::getVar('imgheight');
		$featured = JRequest::getVar('featured');
		$Itemid = JRequest::getVar('Itemid');
		
		if ($featured)
		{
		  if ($items || $cols || $title || $desc || $limit || $image || $imgsize)
	 	  {
			  $model = &$this->getModel('faqbook');
			  $data = $model->loadHomeContent($items, $cols, $title, $desc, $limit, $image, $imgsize, $imgheight, $Itemid);			
		  }
		}
			
	}

}