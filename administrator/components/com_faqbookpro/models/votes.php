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

jimport('joomla.application.component.modellist');

class FAQBookProModelVotes extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'faq_id', 'a.faq_id',
				'title', 'c.title', 'article_title',
				'user_id', 'a.user_id',
				'user_ip', 'a.user_ip',
				'vote_up', 'a.vote_up',
				'vote_down', 'a.vote_down',
				'reason', 'a.reason',
				'published', 'a.published',
				'creation_date', 'a.creation_date',
				'name', 'ua.name', 'author_name',
				'catid', 'c.catid',
				'title', 'cc.title', 'category_title',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down'
			);

			/*$app = JFactory::getApplication();
			$assoc = isset($app->item_associations) ? $app->item_associations : 0;
			if ($assoc)
			{
				$config['filter_fields'][] = 'association';
			}*/
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$authorId = $app->getUserStateFromRequest($this->context . '.filter.author_id', 'filter_author_id');
		$this->setState('filter.author_id', $authorId);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		//$level = $this->getUserStateFromRequest($this->context . '.filter.level', 'filter_level', 0, 'int');
		//$this->setState('filter.level', $level);

		//$language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
		//$this->setState('filter.language', $language);

		// force a language
		/*$forcedLanguage = $app->input->get('forcedLanguage');
		if (!empty($forcedLanguage))
		{
			$this->setState('filter.language', $forcedLanguage);
			$this->setState('filter.forcedLanguage', $forcedLanguage);
		}

		$tag = $this->getUserStateFromRequest($this->context . '.filter.tag', 'filter_tag', '');
		$this->setState('filter.tag', $tag);*/

		// List state information.
		parent::populateState('a.id', 'desc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		//$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.category_id');
		$id .= ':' . $this->getState('filter.author_id');
		//$id .= ':' . $this->getState('filter.language');

		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id,
				a.faq_id,
				c.title,
				cc.title,
				c.catid,
				a.user_id,
				ua.name,
				a.user_ip,
				a.vote_up,
				a.vote_down,
				a.reason,
				a.published,
				a.creation_date,
				a.publish_up, 
				a.publish_down'
			)
		);
		$query->from('#__faqbookpro_voting AS a');

		// Join over the language
		//$query->select('l.title AS language_title')
		//	->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		//$query->select('uc.name AS editor')
		//	->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		//$query->select('ag.title AS access_level')
		//	->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		
		// Join over the articles.
		$query->select('c.title AS article_title, c.catid')
			->join('LEFT', '#__faqbookpro_content AS c ON c.id = a.faq_id');

		// Join over the users for the author.
		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.user_id');
			
		// Join over the categories.
		$query->select('cc.title AS category_title')
			->join('LEFT', '#__categories AS cc ON cc.id = c.catid');

		// Implement View Level Access
		/*if (!$user->authorise('core.admin'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}*/

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published = 0 OR a.published = 1)');
		}

		// Filter by a single or group of categories.
		$baselevel = 1;
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId))
		{
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryId);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$baselevel = (int) $cat_tbl->level;
			$query->where('cc.lft >= ' . (int) $lft)
				->where('cc.rgt <= ' . (int) $rgt);
		}
		elseif (is_array($categoryId))
		{
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('c.catid IN (' . $categoryId . ')');
		}

		// Filter by author
		$authorId = $this->getState('filter.author_id');
		if (is_numeric($authorId))
		{
			$type = $this->getState('filter.author_id.include', true) ? '= ' : '<>';
			$query->where('a.user_id ' . $type . (int) $authorId);
		}

		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(c.title LIKE ' . $search . ')');
		}

		// Filter by a single tag.
		/*$tagId = $this->getState('filter.tag');
		if (is_numeric($tagId))
		{
			$query->where($db->quoteName('tagmap.tag_id') . ' = ' . (int) $tagId)
				->join(
					'LEFT', $db->quoteName('#__contentitem_tag_map', 'tagmap')
					. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
					. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote('com_content.article')
				);
		}*/

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'c.title');
		$orderDirn = $this->state->get('list.direction', 'asc');
		if ($orderCol == 'article_title')
		{
			$orderCol = 'c.title ' . $orderDirn . ', a.id';
		}
		//sqlsrv change
		/*if ($orderCol == 'language')
		{
			$orderCol = 'l.title';
		}
		if ($orderCol == 'access_level')
		{
			$orderCol = 'ag.title';
		}*/
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		// echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}

	public function getAuthors()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Construct the query
		$query->select('u.id AS value, u.name AS text')
			->from('#__users AS u')
			->join('INNER', '#__faqbookpro_voting AS a ON a.user_id = u.id')
			->group('u.id, u.name')
			->order('u.name');

		// Setup the query
		$db->setQuery($query);

		// Return the result
		return $db->loadObjectList();
	}

	public function getItems()
	{
		$items = parent::getItems();
		$app = JFactory::getApplication();
		if ($app->isSite())
		{
			$user = JFactory::getUser();
			$groups = $user->getAuthorisedViewLevels();

			for ($x = 0, $count = count($items); $x < $count; $x++)
			{
				//Check the access level. Remove articles the user shouldn't see
				if (!in_array($items[$x]->access, $groups))
				{
					unset($items[$x]);
				}
			}
		}
		return $items;
	}
}
