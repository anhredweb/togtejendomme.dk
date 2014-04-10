<?php
/**
 * @package    RedITEMCategoryFields.Installer
 *
 * @copyright  Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Find redCORE installer to use it as base system
if (!class_exists('Com_RedcoreInstallerScript'))
{
	$searchPaths = array(
		// Install
		dirname(__FILE__) . '/redCORE',
		// Discover install
		JPATH_ADMINISTRATOR . '/components/com_redcore'
	);

	if ($redcoreInstaller = JPath::find($searchPaths, 'install.php'))
	{
		require_once $redcoreInstaller;
	}
}

/**
 * Script file of RedITEMCategoryFields component
 *
 * @package  RedITEMCategoryFields.Installer
 *
 * @since    2.0
 */
class Com_RedItemCategoryFieldsInstallerScript extends Com_RedcoreInstallerScript
{
	/**
	 * Method to install the component
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	/*public function install($parent)
	{
		return parent::install($parent);
	}*/

	/**
	 * Method to install the component
	 *
	 * @param   object  $parent  Class calling this method
	 *
	 * @return  boolean          True on success
	 */
	public function installOrUpdate($parent)
	{
		parent::installOrUpdate($parent);

		$this->com_install();

		return true;
	}

	/**
	 * method to update the component
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	/*public function update($parent)
	{
		parent::update($parent);

		return true;
	}*/

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @param   object  $type    type of change (install, update or discover_install)
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	/*public function preflight($type, $parent)
	{
		parent::preflight($type, $parent);
	}*/

	/**
	 * Method to run after an install/update/uninstall method
	 *
	 * @param   object  $type    type of change (install, update or discover_install)
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	/*public function postflight($type, $parent)
	{
		parent::postflight($type, $parent);
	}*/

	/**
	 * Main redITEM installer Events
	 *
	 * @return  void
	 */
	private function com_install()
	{
		// Diplay the installation message
		$this->displayInstallMsg();
	}

	/**
	 * method to uninstall the component
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	public function uninstall($parent)
	{
		// Error handling
		JError::SetErrorHandling(E_ALL, 'callback', array('Com_RedItemCategoryFieldsInstallerScript', 'error_handling'));

		// Uninstall extensions
		$this->com_uninstall();
	}

	/**
	 * Main redITEM uninstaller Events
	 *
	 * @return  void
	 */
	private function com_uninstall()
	{
	}

	/**
	 * Error handler
	 *
	 * @param   array  $e  Exception array
	 *
	 * @return  void
	 */
	public static function error_handling(Exception $e)
	{
		echo $e->getMessage();
	}

	/**
	 * Display install message
	 *
	 * @return void
	 */
	public function displayInstallMsg()
	{
		echo JText::_('COM_REDITEMCATEGORYFIELDS_INSTALL_SUCCESS');
	}
}
