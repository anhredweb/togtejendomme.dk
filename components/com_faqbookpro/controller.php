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
 
/**
 * FAQ Book Pro Component Controller
 */
class FaqBookProController extends JControllerLegacy {

	function display() 
	{
        // Make sure we have a default view
        if( !JRequest::getVar( 'view' )) {
            JRequest::setVar('view', 'faqbook' );
        }
        parent::display();
    }

}