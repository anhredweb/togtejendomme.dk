<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

/**
 * The types controller
 *
 * @package     RedITEM.Backend
 * @subpackage  Controller.Types
 * @since       2.0
 */
class ReditemControllerTypes extends RControllerAdmin
{
	/**
	 * constructor (registers additional tasks to methods)
	 */
	public function __construct()
	{
		parent::__construct();

		// Write this to make two tasks use the same method (in this example the add method uses the edit method)
		$this->registerTask('add', 'edit');
	}

	/**
	 * display the add and the edit form
	 *
	 * @return void
	 */
	public function edit()
	{
		JRequest::setVar('view', 'type');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}
}
