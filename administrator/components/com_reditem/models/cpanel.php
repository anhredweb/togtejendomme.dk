<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * Redshop configuration model
 *
 * @package     RedITEM.Backend
 * @subpackage  Model.Configuration
 * @since       2.0
 */
class RedItemModelCpanel extends RModelAdmin
{
	/**
	 * Get the current redITEM version
	 *
	 * @return  string  The redITEM version
	 *
	 * @since   0.9.1
	 */
	public function getVersion()
	{
		$xmlfile = JPATH_SITE . '/administrator/components/com_reditem/reditem.xml';
		$version = JText::_('COM_REDITEM_FILE_NOT_FOUND');

		if (file_exists($xmlfile))
		{
			$data = JApplicationHelper::parseXMLInstallFile($xmlfile);
			$version = $data['version'];
		}

		return $version;
	}

	/**
	 * Get the current redITEM version
	 *
	 * @return  string  The redITEM version
	 *
	 * @since   0.9.1
	 */
	public function getStats()
	{
		$stats = (object) array();

		// Get number of all published items

		$query = $this->_db->getQuery(true);
		$query->select('count(*)')
		->from('#__reditem_items')
		->where($this->_db->qn('published') . '=' . $this->_db->quote('1'));
		$this->_db->SetQuery($query);
		$stats->item_published = $this->_db->loadResult();

		// Get number of all unpublished items
		$query = $this->_db->getQuery(true);
		$query->select('count(*)')
		->from('#__reditem_items')
		->where($this->_db->qn('published') . '=' . $this->_db->quote('0'));
		$this->_db->SetQuery($query);
		$stats->item_unpublished = $this->_db->loadResult();

		$stats->item_total = $stats->item_unpublished + $stats->item_published;

		// Get number of all published categories
		$query = $this->_db->getQuery(true);
		$query->select('count(*)')
		->from('#__reditem_categories')
		->where($this->_db->qn('published') . '=' . $this->_db->quote('1'))
		->where($this->_db->qn('level') . '>' . $this->_db->quote('0'));
		$this->_db->SetQuery($query);
		$stats->category_published = $this->_db->loadResult();

		// Get number of all unpublished categories
		$query = $this->_db->getQuery(true);
		$query->select('count(*)')
		->from('#__reditem_categories')
		->where($this->_db->qn('published') . '=' . $this->_db->quote('0'))
		->where($this->_db->qn('level') . '>' . $this->_db->quote('0'));
		$this->_db->SetQuery($query);
		$stats->category_unpublished = $this->_db->loadResult();

		$stats->category_total = $stats->category_unpublished + $stats->category_published;

		return $stats;
	}
}
