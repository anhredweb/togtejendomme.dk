<?php
/**
 * @version     1.0
 * @package     Joomla
 * @subpackage  com_redslider
 * @author      redWEB Aps <khai@redweb.dk>
 * @copyright   com_redslider (C) 2008 - 2012 redCOMPONENT.com
 * @license     GNU/GPL, see LICENSE.php
 * com_redslider can be downloaded from www.redcomponent.com
 * com_redslider is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 * com_redslider is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with com_redslider; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 **/

defined('_JEXEC') or die();

class FOFFormFieldGallerypicker extends FOFFormFieldList
{
	protected function getOptions()
	{
		// Initialise variables.
		$options = array();

		$db	= JFactory::getDbo();
		$query = $db->getQuery(true)
					->select('g.redslider_gallery_id AS value, g.title AS text')
					->from('#__redslider_galleries AS g')
					->where('g.enabled = 1');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		return $options;
	}
}