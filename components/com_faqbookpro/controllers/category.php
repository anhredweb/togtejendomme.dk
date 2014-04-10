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

if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
}

// import Joomla controller library
jimport('joomla.application.component.controller');
 
class FaqBookProControllerCategory extends JControllerLegacy 
{
  
	function __construct() 
	{		
		parent::__construct();	
	 }
			
}