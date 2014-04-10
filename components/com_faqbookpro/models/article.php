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

// import Joomla modelitem library
jimport('joomla.application.component.modeladmin');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/faqbookpro.php';

class FAQBookProModelArticle extends JModelAdmin
{
	protected $text_prefix = 'COM_FAQBOOKPRO';

	protected function prepareTable($table)
	{
		// Set the publish date to now
		$db = $this->getDbo();
		if ($table->state == 1 && (int) $table->publish_up == 0)
		{
			$table->publish_up = JFactory::getDate()->toSql();
		}

		if ($table->state == 1 && intval($table->publish_down) == 0)
		{
			$table->publish_down = $db->getNullDate();
		}

		// Increment the content version number.
		$table->version++;

		// Reorder the articles within the category so the new article is first
		if (empty($table->id))
		{
			$table->reorder('catid = ' . (int) $table->catid . ' AND state >= 0');
		}
	}

	public function getTable($type = 'Content', $prefix = 'FAQBookProTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			// Convert the params field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->attribs);
			$item->attribs = $registry->toArray();

			// Convert the metadata field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->metadata);
			$item->metadata = $registry->toArray();

			// Convert the images field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->images);
			$item->images = $registry->toArray();

			// Convert the urls field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->urls);
			$item->urls = $registry->toArray();

			$item->articletext = trim($item->fulltext) != '' ? $item->introtext . "<hr id=\"system-readmore\" />" . $item->fulltext : $item->introtext;

			if (!empty($item->id))
			{
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_faqbookpro.article');
			}
		}

		// Load associated content items
		$app = JFactory::getApplication();
		$assoc = isset($app->item_associations) ? $app->item_associations : 0;

		if ($assoc)
		{
			$item->associations = array();

			if ($item->id != null)
			{
				$associations = JLanguageAssociations::getAssociations('com_faqbookpro', '#__faqbookpro_content', 'com_faqbookpro.item', $item->id);

				foreach ($associations as $tag => $association)
				{
					$item->associations[$tag] = $association->id;
				}
			}
		}

		return $item;
	}

	public function getForm($data = array(), $loadData = true)
	{
		$params  = JComponentHelper::getParams('com_faqbookpro');
		$user = JFactory::getUser();
		// Get the form.
		if ( ($params->get('askform_captcha')==1 && !$user->id) || ($params->get('askform_captcha')==2)) {
			$form = $this->loadForm('com_faqbookpro.article', 'article', array('control' => 'jform', 'load_data' => $loadData));
		} else {
			$form = $this->loadForm('com_faqbookpro.article_noc', 'article_noc', array('control' => 'jform', 'load_data' => $loadData));
		}
		
		if (empty($form))
		{
			return false;
		}
		$jinput = JFactory::getApplication()->input;

		// The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('a_id'))
		{
			$id = $jinput->get('a_id', 0);
		}
		// The back end uses id so we use that the rest of the time and set it to 0 by default.
		else
		{
			$id = $jinput->get('id', 0);
		}
		// Determine correct permissions to check.
		if ($this->getState('article.id'))
		{
			$id = $this->getState('article.id');
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
			// Existing record. Can only edit own articles in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit.own');
		}
		else
		{
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		$user = JFactory::getUser();

		// Check for existing article.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_faqbookpro.article.' . (int) $id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_faqbookpro'))
		)
		{
			// Disable fields for display.
			$form->setFieldAttribute('featured', 'disabled', 'true');
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an article you can edit.
			$form->setFieldAttribute('featured', 'filter', 'unset');
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		// Prevent messing with article language and category when editing existing article with associations
		$app = JFactory::getApplication();
		$assoc = isset($app->item_associations) ? $app->item_associations : 0;

		if ($app->isSite() && $assoc && $this->getState('article.id'))
		{
			$form->setFieldAttribute('language', 'readonly', 'true');
			$form->setFieldAttribute('catid', 'readonly', 'true');
			$form->setFieldAttribute('language', 'filter', 'unset');
			$form->setFieldAttribute('catid', 'filter', 'unset');
		}

		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_faqbookpro.edit.article.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('article.id') == 0)
			{
				$data->set('catid', $app->input->getInt('catid', $app->getUserState('com_faqbookpro.articles.filter.category_id')));
			}
		}

		//$this->preprocessData('com_faqbookpro.article', $data);

		return $data;
	}

	public function save($data)
	{
		$app = JFactory::getApplication();
		
		// Autopublish new frontent article
		$params = FaqBookProHelperUtilities::getParams('com_faqbookpro');
		if ($params->get('ask_autopublish')) 
		{
			$data['state'] = 1;
		}
		
		if (isset($data['images']) && is_array($data['images']))
		{
			$registry = new JRegistry;
			$registry->loadArray($data['images']);
			$data['images'] = (string) $registry;
			//die(var_dump($data['images']));
		}
		
		if (isset($data['urls']) && is_array($data['urls']))
		{

			foreach ($data['urls'] as $i => $url)
			{
				if ($url != false && ($i == 'urla' || $i == 'urlb' || $i = 'urlc'))
				{
					$data['urls'][$i] = JStringPunycode::urlToPunycode($url);
				}

			}
			$registry = new JRegistry;
			$registry->loadArray($data['urls']);
			$data['urls'] = (string) $registry;
		}

		// Alter the title for save as copy
		if ($app->input->get('task') == 'save2copy')
		{
			list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
			$data['title'] = $title;
			$data['alias'] = $alias;
			$data['state'] = 0;
		}

		if (parent::save($data))
		{

			if (isset($data['featured']))
			{
				$this->featured($this->getState($this->getName() . '.id'), $data['featured']);
			}

			$assoc = isset($app->item_associations) ? $app->item_associations : 0;
			if ($assoc)
			{
				$id = (int) $this->getState($this->getName() . '.id');
				$item = $this->getItem($id);

				// Adding self to the association
				$associations = $data['associations'];

				foreach ($associations as $tag => $id)
				{
					if (empty($id))
					{
						unset($associations[$tag]);
					}
				}

				// Detecting all item menus
				$all_language = $item->language == '*';

				if ($all_language && !empty($associations))
				{
					JError::raiseNotice(403, JText::_('COM_CONTENT_ERROR_ALL_LANGUAGE_ASSOCIATED'));
				}

				$associations[$item->language] = $item->id;

				// Deleting old association for these items
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->delete('#__associations')
					->where('context=' . $db->quote('com_faqbookpro.item'))
					->where('id IN (' . implode(',', $associations) . ')');
				$db->setQuery($query);
				$db->execute();

				if ($error = $db->getErrorMsg())
				{
					$this->setError($error);
					return false;
				}

				if (!$all_language && count($associations))
				{
					// Adding new association for these items
					$key = md5(json_encode($associations));
					$query->clear()
						->insert('#__associations');

					foreach ($associations as $id)
					{
						$query->values($id . ',' . $db->quote('com_faqbookpro.item') . ',' . $db->quote($key));
					}

					$db->setQuery($query);
					$db->execute();

					if ($error = $db->getErrorMsg())
					{
						$this->setError($error);
						return false;
					}
				}
			}

			return true;
		}

		return false;
	}

	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);

		if (empty($pks))
		{
			$this->setError(JText::_('COM_CONTENT_NO_ITEM_SELECTED'));
			return false;
		}

		try
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
						->update($db->quoteName('#__faqbookpro_content'))
						->set('featured = ' . (int) $value)
						->where('id IN (' . implode(',', $pks) . ')');
			$db->setQuery($query);
			$db->execute();

		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		//$table->reorder();

		$this->cleanCache();

		return true;
	}

	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'catid = ' . (int) $table->catid;
		return $condition;
	}

	protected function preprocessForm(JForm $form, $data, $group = 'faqbookpro')
	{
		// Association content items
		$app = JFactory::getApplication();
		$assoc = isset($app->item_associations) ? $app->item_associations : 0;
		if ($assoc)
		{
			$languages = JLanguageHelper::getLanguages('lang_code');

			// force to array (perhaps move to $this->loadFormData())
			$data = (array) $data;

			$addform = new SimpleXMLElement('<form />');
			$fields = $addform->addChild('fields');
			$fields->addAttribute('name', 'associations');
			$fieldset = $fields->addChild('fieldset');
			$fieldset->addAttribute('name', 'item_associations');
			$fieldset->addAttribute('description', 'COM_CONTENT_ITEM_ASSOCIATIONS_FIELDSET_DESC');
			$add = false;
			foreach ($languages as $tag => $language)
			{
				if (empty($data['language']) || $tag != $data['language'])
				{
					$add = true;
					$field = $fieldset->addChild('field');
					$field->addAttribute('name', $tag);
					$field->addAttribute('type', 'modal_article');
					$field->addAttribute('language', $tag);
					$field->addAttribute('label', $language->title);
					$field->addAttribute('translate_label', 'false');
					$field->addAttribute('edit', 'true');
					$field->addAttribute('clear', 'true');
				}
			}
			if ($add)
			{
				$form->load($addform, false);
			}
		}

		parent::preprocessForm($form, $data, $group);
	}

	protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache('com_faqbookpro');
		/*parent::cleanCache('mod_faqbookpro_items');*/
	}
}
