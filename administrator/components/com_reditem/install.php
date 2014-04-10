<?php
/**
 * @package    RedITEM.Installer
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
 * Script file of redITEM component
 *
 * @package  RedITEM.Installer
 *
 * @since    2.0
 */
class Com_RedItemInstallerScript extends Com_RedcoreInstallerScript
{
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
		JError::SetErrorHandling(E_ALL, 'callback', array('Com_RedItemInstallerScript', 'error_handling'));

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
		// Remove types table
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('table_name'))
			->from($db->quoteName('#__reditem_types'));
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		if ($rows)
		{
			foreach ($rows as $row)
			{
				$_tb = '#__reditem_types_' . $row->table_name;
				$_tb = $db->quoteName($_tb);
				$_q = 'DROP TABLE ' . $_tb;
				$db->setQuery($_q);
				$db->query();
			}
		}
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
	}

	/**
	 * Display install message
	 *
	 * @return void
	 */
	public function displayInstallMsg()
	{
		echo '<p><img src="components/com_reditem/assets/images/reditem_logo.jpg" alt="redITEM Logo" width="500"></p>';
		echo '<br /><br /><p>Remember to check for updates at:<br />';
		echo '<a href="http://www.redcomponent.com/" target="_new">';
		echo '<img src="components/com_reditem/assets/images/redcomponent_logo.jpg" alt="">';
		echo '</a></p>';

		// Install the sh404SEF router files
		JLoader::import('joomla.filesystem.file');
		JLoader::import('joomla.filesystem.folder');
		$sh404sefext   = JPATH_SITE . '/components/com_sh404sef/sef_ext';
		$sh404sefmeta  = JPATH_SITE . '/components/com_sh404sef/meta_ext';
		$sh404sefadmin = JPATH_SITE . '/administrator/components/com_sh404sef';
		$redadmin      = JPATH_SITE . '/administrator/components/com_reditem/extras';

		// Check if sh404SEF is installed
		if (JFolder::exists(JPATH_SITE . '/components/com_sh404sef'))
		{
			// Copy the plugin
			if (!JFile::copy($redadmin . '/sh404sef/sef_ext/com_reditem.php', $sh404sefext . '/com_reditem.php'))
			{
				echo JText::_('COM_REDSHOP_FAILED_TO_COPY_SH404SEF_EXTENSION_PLUGIN_FILE');
			}
		}
	}
}
