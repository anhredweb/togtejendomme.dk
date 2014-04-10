<?php
/**
 * @package     RedITEM
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
jimport('redcore.bootstrap');

require_once JPATH_ADMINISTRATOR . '/components/com_reditem/helpers/helper.php';
require_once JPATH_ADMINISTRATOR . '/components/com_reditemcategoryfields/helpers/helper.php';

/**
 * Plugins RedITEM Category Fields
 *
 * @since  2.0
 */
class PlgReditem_CategoriesCategory_Fields extends JPlugin
{
	/**
	 * Constructor - note in Joomla 2.5 PHP4.x is no longer supported so we can use this.
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An array that holds the plugin configuration
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Event before edit an category
	 *
	 * @param   object  $category  Category object
	 *
	 * @return  boolean/array
	 */
	public function prepareCategoryEdit($category)
	{
		$typeId = $this->params->get('typeID', 0);

		if (($typeId) && ($category->type_id == $typeId))
		{
			$categoryFieldsModel = RModel::getAdminInstance('CategoryFields', array('ignore_request' => true), 'com_reditemcategoryfields');
			$categoryFieldsModel->setState('filter.published', 1);
			$rows = $categoryFieldsModel->getItems();

			$categoryData = ReditemCategoryFieldsHelper::getFieldsData($category->id);
			$categoryData = $categoryData[0];
			$categoryData = (array) $categoryData;

			$fields = array();

			foreach ($rows AS $row)
			{
				$field = ReditemHelperHelper::getCustomField($row->type);
				$field->bind($row);

				if (($categoryData) && isset($categoryData[$row->fieldcode]))
				{
					$field->value = $categoryData[$row->fieldcode];
				}

				$renderHTML = '<div class="control-group">';
				$renderHTML .= '<div class="control-label">';
				$renderHTML .= $field->getLabel();
				$renderHTML .= '</div>';
				$renderHTML .= '<div class="controls">';

				if (($field->type == 'image') || ($field->type == 'gallery'))
				{
					$renderHTML .= $field->render('', 'categoryfield/' . $category->id);
				}
				else
				{
					$renderHTML .= $field->render();
				}

				$renderHTML .= '</div>';
				$renderHTML .= '</div>';

				$fields[] = $renderHTML;
			}

			return $fields;
		}

		return false;
	}

	/**
	 * Event before edit an category
	 *
	 * @param   object  $category  Category object
	 * @param   object  $input     An input oject JInput
	 *
	 * @return  void
	 */
	public function onAfterCategorySave($category, $input)
	{
		$typeId = $this->params->get('typeID', 0);

		if (($typeId) && ($category->type_id == $typeId))
		{
			$db = JFactory::getDBO();
			$cform = $input->get('cform', null, 'array');
			$jform = $input->get('jform', null, 'array');
			$imageFilesCustomField = $input->files->get('cform');
			$imageFolder = JPATH_ROOT . '/components/com_reditem/assets/images/categoryfield/' . $category->id . '/';
			$cfDataTable = $db->quoteName('#__reditem_category_fields_value');

			// Create folder if not exists
			if (!JFolder::exists($imageFolder))
			{
				JFolder::create($imageFolder);
			}

			/*
			 * Remove [image] custom field - Checked
			 */
			if (isset($jform['customfield_image_rm']))
			{
				foreach ($jform['customfield_image_rm'] as $customFieldImageRemove)
				{
					$tmpImageName = json_decode($cform[$customFieldImageRemove], true);
					$tmpImageName = $tmpImageName[0];
					$tmpImagePath = $imageFolder . $tmpImageName;

					if (JFile::exists($tmpImagePath))
					{
						JFile::delete($tmpImagePath);

						// Remove this image from values array
						$cform[$customFieldImageRemove] = '';
					}
				}
			}

			/*
			 * Remove [gallery] custom field - Checked
			 */
			if (isset($jform['customfield_gallery_rm']))
			{
				foreach ($jform['customfield_gallery_rm'] as $cfGallery => $cfImagesRemove)
				{
					$tmpImages = $cform[$cfGallery];

					if ($cfImagesRemove)
					{
						foreach ($cfImagesRemove as $cfImage)
						{
							if ($cfImage)
							{
								$tmpImagePath = $imageFolder . $cfImage;

								// Remove this image from values array
								$key = array_search($cfImage, $cform[$cfGallery]);
								unset($cform[$cfGallery][$key]);

								if (JFile::exists($tmpImagePath))
								{
									JFile::delete($tmpImagePath);
								}
							}
						}
					}
				}
			}

			// Upload / Save custom fields image
			if (is_array($imageFilesCustomField))
			{
				foreach ($imageFilesCustomField AS $imageField => $imageData)
				{
					$tmpVarName = substr($imageField, 0, -4);

					if (isset($imageData['name']))
					{
						// Single image upload for "Image" field

						if ($imageData['name'] != '')
						{
							$imageCustomFileds[] = $tmpVarName;

							$imageData['name'] = $tmpVarName . '_' . time() . '_' . ReditemHelperHelper::replaceSpecial($imageData['name']);
							$cform[$tmpVarName] = array($imageData['name']);
							JFile::upload($imageData['tmp_name'], $imageFolder . $imageData['name']);
						}
					}
					else
					{
						// Multiple upload. For "Gallery" field

						$imageCustomFileds[] = $tmpVarName;

						foreach ($imageData AS $image)
						{
							if ($image['name'] != '')
							{
								$image['name'] = $tmpVarName . '_' . time() . '_' . ReditemHelperHelper::replaceSpecial($image['name']);
								$cform[$tmpVarName][] = $image['name'];
								JFile::upload($image['tmp_name'], $imageFolder . $image['name']);
							}
						}
					}
				}

				foreach ($imageCustomFileds as $k)
				{
					$cform[$k] = json_encode((array) array_filter($cform[$k]));
				}
			}

			// Remove current custom field value of this category
			$query = $db->getQuery(true);
			$query->delete($cfDataTable)
				->where($db->quoteName('cat_id') . ' = ' . $db->quote($category->id));
			$db->setQuery($query);
			$db->query();

			// Insert new data row for this category
			$query = $db->getQuery(true);
			$query->insert($cfDataTable);
			$query->columns($db->quoteName('cat_id'));
			$query->values($db->quote($category->id));
			$db->setQuery($query);
			$db->query();

			// Insert custom field value
			if (count($cform))
			{
				$query = $db->getQuery(true);

				$query->update($cfDataTable);

				foreach ($cform AS $col => $val)
				{
					$val = (is_array($val)) ? json_encode($val) : $val;
					$query->set($db->quoteName($col) . ' = ' . $db->quote($val));
				}

				$query->where($db->quoteName('cat_id') . ' = ' . $db->quote($category->id));

				$db->setQuery($query);

				if ($db->query() == 1)
				{
					$result = true;
				}
				else
				{
					$result = false;
				}
			}
		}
	}

	/**
	 * Event before edit an category, add extra tags.
	 *
	 * @param   array   &$categoryTags  Category tags list
	 * @param   object  $template       Template object
	 * @param   string  $prefix         Prefix of tags
	 *
	 * @return  void
	 */
	public function prepareCategoryTemplateTag(&$categoryTags, $template, $prefix = '')
	{
		$typeId = $this->params->get('typeID', 0);

		if (($typeId) && ($template->type_id == $typeId))
		{
			$categoryFieldsModel = RModel::getAdminInstance('CategoryFields', array('ignore_request' => true), 'com_reditemcategoryfields');
			$categoryFieldsModel->setState('filter.published', 1);
			$fields = $categoryFieldsModel->getItems();

			if ($fields)
			{
				foreach ($fields as $field)
				{
					$tag = '{' . $prefix . 'category_extra_' . $field->fieldcode . '_text}';
					$tagDesc = JText::sprintf('COM_REDITEM_TEMPLATE_TAG_FIELD_' . strtoupper($field->type) . '_TITLE', $field->name);
					$categoryTags[$tag] = $tagDesc;

					$tagParams = JText::_('COM_REDITEM_TEMPLATE_TAG_FIELD_' . strtoupper($field->type) . '_PARAMS');
					$tag = '{' . $prefix . 'category_extra_' . $field->fieldcode . '_value' . $tagParams . '}';
					$tagDesc = JText::sprintf('COM_REDITEM_TEMPLATE_TAG_FIELD_' . strtoupper($field->type) . '_VALUE', $field->name);
					$categoryTags[$tag] = $tagDesc;
				}
			}
		}
	}

	/**
	 * Front-site. Event run when replace tag of template
	 *
	 * @param   string  &$content  Content template
	 * @param   array   $category  Category data array
	 * @param   string  $prefix    Prefix of tags
	 *
	 * @return  void
	 */
	public function onReplaceCategoryTag(&$content, $category, $prefix = '')
	{
		$typeId = $this->params->get('typeID', 0);

		if (($typeId) && ($category->type_id == $typeId))
		{
			$doc = JFactory::getDocument();
			$imageGenerator = new ImageGenerator;
			$categoryFieldsModel = RModel::getAdminInstance('CategoryFields', array('ignore_request' => true), 'com_reditemcategoryfields');
			$categoryFieldsModel->setState('filter.published', 1);
			$fields = $categoryFieldsModel->getItems();

			$customFieldValues = ReditemCategoryFieldsHelper::getFieldsData($category->id);

			if (isset($customFieldValues[0]))
			{
				$customFieldValues = (array) $customFieldValues[0];
			}

			if ($fields)
			{
				foreach ($fields as $tag)
				{
					/*
					 * Replace the title tag
					 */
					$tagstr = '{' . $prefix . 'category_extra_' . $tag->fieldcode . '_text}';
					$content = str_replace($tagstr, $tag->name, $content);

					/**
					 * Replace the value tag
					 */
					switch ($tag->type)
					{
						case 'image':
							$preg = '/{' . $prefix . 'category_extra_' . $tag->fieldcode . '_value[^}]*}/i';
							$value = $customFieldValues[$tag->fieldcode];
							$matches = array();

							if (preg_match_all($preg, $content, $matches) > 0)
							{
								$matches = $matches[0];

								foreach ($matches as $match)
								{
									$_tmp = explode('|', $match);

									if (isset($_tmp[1]))
									{
										$width = (int) $_tmp[1] . 'px';
									}
									else
									{
										$width = '';
									}

									if (isset($_tmp[2]))
									{
										$height = (int) $_tmp[2] . 'px';
									}
									else
									{
										$height = '';
									}

									$imgs = json_decode($value, true);

									$js = '';

									if (count($imgs) > 0 && !empty($imgs[0]))
									{
										$img = $imgs[0];

										if (($width) || ($height))
										{
											// Auto create thumbnail file
											$tmp = explode('/', $img);
											$fileName = array_pop($tmp);
											$class = 'class="modal"';
											$imagePath = $imageGenerator->getImageLink($category->id, 'categoryfield', 0, $fileName, 'thumbnail', $width, $height, false, $class);
											$replaceText = $imagePath;
										}
										else
										{
											$imagePath = JURI::base() . 'components/com_reditem/assets/images/categoryfield/' . $category->id . '/' . $img;

											$replaceText = '<img class="modal" src="' . $imagePath . '"';

											if ($width)
											{
												$replaceText .= ' width="' . $width . '"';
											}

											if ($height)
											{
												$replaceText .= ' height="' . $height . '"';
											}

											$replaceText .= ' />';
										}

										$content = str_replace($match, $replaceText, $content);
									}
									else
									{
										$content = str_replace($match, '', $content);
									}
								}
							}
							break;

						case 'gallery':
							$preg = '/{' . $prefix . 'category_extra_' . $tag->fieldcode . '_value[^}]*}/i';
							$value = '';

							if (isset($customFieldValues[$tag->fieldcode]))
							{
								$value = $customFieldValues[$tag->fieldcode];
							}

							$matches = array();

							if (preg_match_all($preg, $content, $matches) > 0)
							{
								$matches = $matches[0];
								$i = 0;

								foreach ($matches as $match)
								{
									$tmpMatch = str_replace('{', '', $match);
									$tmpMatch = str_replace('}', '', $tmpMatch);

									$params = explode('|', $tmpMatch);

									if (isset($params[1]))
									{
										$width = (int) $params[1];
									}
									else
									{
										$width = '';
									}

									if (isset($params[2]))
									{
										$height = (int) $params[2];
									}
									else
									{
										$height = '';
									}

									if (isset($params[3]))
									{
										$displayType = $params[3];
									}
									else
									{
										$displayType = '';
									}

									$imgs = json_decode($value, true);
									$js = '';

									if (count($imgs) > 0)
									{
										if ($displayType == 'slider')
										{
											$strData = '<div class="flexslider" id="flexslider_' . $category->id . '_' . $i . '">';
											$strData .= '<ul class="slides">';

											foreach ($imgs as $img)
											{
												if (!empty($img))
												{
													if (($width) || ($height))
													{
														$tmp = explode('/', $img);
														$fileName = array_pop($tmp);
														$imagePath = $imageGenerator->getImageLink($category->id, 'categoryfield', 0, $fileName, 'thumbnail', $width, $height, false);
													}
													else
													{
														$imagePath = JURI::base() . 'components/com_reditem/assets/images/categoryfield/' . $category->id . '/' . $img;
														$imagePath = '<img src="' . $imagePath . '" />';
													}

													$strData .= '<li>' . $imagePath . '</li>';
												}
											}

											$strData .= '</ul>';
											$strData .= '</div>';

											$content = str_replace($match, $strData, $content);

											$sliderParams = array('animation' => 'slide', 'smoothHeight' => false);

											JHtml::_('rjquery.flexslider', '#flexslider_' . $category->id . '_' . $i, $sliderParams);
										}
										else
										{
											$_str = '';
											$first = true;

											foreach ($imgs as $img)
											{
												if (!empty($img))
												{
													$_src = JURI::base() . 'components/com_reditem/assets/images/categoryfield/' . $category->id . '/' . $img;

													if ($first)
													{
														// Create thumbnail file for first image
														if (($width) || ($height))
														{
															$tmp = explode('/', $img);
															$fileName = array_pop($tmp);
															$imagePath = $imageGenerator->getImageLink($category->id, 'categoryfield', 0, $fileName, 'thumbnail', $width, $height, false);
														}
														else
														{
															$imagePath = JURI::base() . 'components/com_reditem/assets/images/categoryfield/' . $category->id . '/' . $img;
															$imagePath = '<img src="' . $imagePath . '" width="' . $width . '" height="' . $height . '" />';
														}

														$_str .= '<a class="colorbox_group_' . $category->id . '_' . $i . '" href="' . $_src . '" />';
														$_str .= $imagePath;
														$_str .= '</a>';

														$first = false;
													}
													else
													{
														$_str .= '<a class="colorbox_group_' . $category->id . '_' . $i . ' hidden" href="' . $_src . '" />';
														$_str .= '</a>';
													}
												}
											}

											$content = str_replace($match, $_str, $content);

											$js .= 'jQuery(document).ready(function(){';
											$js .= 'jQuery(".colorbox_group_' . $category->id . '_' . $i . '").colorbox({rel:"colorbox_group_' . $category->id . '_' . $i . '"});';
											$js .= '});';
										}
									}
									else
									{
										// Replace tag by empty string
										$content = str_replace($match, '', $content);
									}

									$doc->addScriptDeclaration($js);

									$i++;
								}

								// Load color box
								$doc->addScript(JURI::base() . '/media/com_reditem/colorbox/jquery.colorbox-min.js');
								$doc->addStyleSheet(JURI::base() . '/media/com_reditem/colorbox/colorbox.css');
							}
							break;

						case 'youtube':
							JHTML::_('behavior.modal');

							$preg = '/{' . $prefix . 'category_extra_' . $tag->fieldcode . '_value[^}]*}/i';
							$value = $customFieldValues[$tag->fieldcode];

							$matches = array();

							if (preg_match_all($preg, $content, $matches) > 0)
							{
								$matches = $matches[0];
								$i = 0;

								foreach ($matches as $match)
								{
									$_tmp = explode('|', $match);

									if (isset($_tmp[1]))
									{
										$width = (int) $_tmp[1];
									}
									else
									{
										$width = '400';
									}

									if (isset($_tmp[2]))
									{
										$height = (int) $_tmp[2];
									}
									else
									{
										$height = '250';
									}

									if ($value)
									{
										$string = '<a id="youtube_vid_' . $i . '" class="youtube modal" href="//www.youtube.com/embed/' . $value . '"';
										$string .= 'style="width:' . $width . 'px;height:' . $height . 'px;" rel="{handler: \'iframe\', size: {x: 640, y: 360}}"></a>';
									}
									else
									{
										$string = '';
									}

									$content = str_replace($match, $string, $content);
									$i++;
								}
							}
							break;

						case 'number':
							$typeModel = RModel::getAdminInstance('Type', array('ignore_request' => true), 'com_reditem');
							$type = $typeModel->getItem($tag->type_id);

							$typeParams = new JRegistry($type->params);

							$decimalSepatator = $typeParams->get('customfield_number_decimal_sepatator', '.');
							$thousandSeparator = $typeParams->get('customfield_number_thousand_separator', ',');
							$numberDecimals = $typeParams->get('customfield_number_number_decimals', 2);

							$value = $customFieldValues[$tag->fieldcode];

							$string = number_format(floatval($value), $numberDecimals, $decimalSepatator, $thousandSeparator);
							$match = '{' . $prefix . 'category_extra_' . $tag->fieldcode . '_value}';
							$content = str_replace($match, $string, $content);
							break;

						case 'googlemaps':
							$strData = '';
							$doc = JFactory::getDocument();

							$preg = '/{' . $prefix . 'category_extra_' . $tag->fieldcode . '_value}/i';

							$value = '';

							if (isset($customFieldValues[$tag->fieldcode]))
							{
								$value = $customFieldValues[$tag->fieldcode];
							}

							$matches = array();

							if (preg_match_all($preg, $content, $matches) > 0)
							{
								$matches = $matches[0];

								foreach ($matches as $match)
								{
									$js = '(function($){
										$(document).ready(function($){
											reditem_extra_category_googlemaps_init();
										});
									})(jQuery);';

									$doc->addScriptDeclaration($js);

									$strData = '<div class="reditem_extra_category_googlemaps">';
									$strData .= '<div id="reditem_extra_category_googlemaps_' . $category->id . '_canvas"
										class="reditem_extra_category_googlemaps_canvas"></div>';
									$strData .= '<input type="hidden" id="map_extra_id" value="reditem_extra_category_googlemaps_' . $category->id . '_canvas" />';
									$strData .= '<input type="hidden" id="map_extra_latlng" value="' . $value . '" />';
									$strData .= '<input type="hidden" id="map_extra_infor" value="<h3>' . $category->title . '</h3>" />';
									$strData .= '</div>';

									$content = str_replace($match, $strData, $content);
								}

								// Add Google Maps script
								$doc->addScript('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
							}
							break;

						default:
							$tagstr = '{' . $prefix . 'category_extra_' . $tag->fieldcode . '_value}';
							$string = '';

							if (isset($customFieldValues[$tag->fieldcode]))
							{
								$string = $customFieldValues[$tag->fieldcode];
							}

							$content = str_replace($tagstr, $string, $content);
							break;
					}
				}
			}
		}
	}

	/**
	 * Method for generate filter tag base on extra fields of categories
	 * 
	 * @param   array   &$filterTags  Array of tags
	 * @param   object  $object       Categories / Template object
	 * 
	 * @return void
	 */
	public function prepareCategoryFilterExtraTag(&$filterTags, $object)
	{
		$typeId = $this->params->get('typeID', 0);

		if (($typeId) && ($object->type_id == $typeId))
		{
			$filterExtrasTags = array(
				'{filter_catextrafield|<em>cfId</em>|<em>cfType</em>}' => JText::_('COM_REDITEM_TEMPLATE_TAG_FILTER_BY_CATEGORYEXTRAFIELD_DATA')
			);

			$filterTags = array_merge($filterTags, $filterExtrasTags);
		}
	}

	/**
	 * Front-side. Event run when replace filter tag of template
	 * 
	 * @param   array   &$content  Array of tags
	 * @param   object  $category  Categories / Template object
	 * 
	 * @return void
	 */
	public function onReplaceCategoryFilterExtrasFieldTag(&$content, $category)
	{
		$typeId = $this->params->get('typeID', 0);

		if (($typeId) && ($category->type_id == $typeId))
		{
			// {filter_catextrafield|<extraFieldId>|<generatedFilterHTML>}
			$preg = '/{filter_catextrafield[^}]*}/i';

			if (preg_match_all($preg, $content, $matches) > 0)
			{
				$matches = $matches[0];

				foreach ($matches as $match)
				{
					$extraFieldId = 0;
					$generatedFilterType = "select";
					$extraField = null;
					$extraFieldDatas = array();

					$params = explode('|', $match);
					$params = str_replace('{', '', $params);
					$params = str_replace('}', '', $params);

					// Get extra field
					if (isset($params[1]))
					{
						$extraFieldId = (int) $params[1];
					}

					// Get generated HTML filter type
					if (isset($params[2]))
					{
						$generatedFilterType = $params[2];
					}

					$extraFieldModel = RModel::getAdminInstance('CategoryField', array('ignore_request' => true), 'com_reditemcategoryfields');
					$extraField = $extraFieldModel->getItem($extraFieldId);

					if ($extraField)
					{
						$categoriesModel = RModel::getAdminInstance('Categories', array('ignore_request' => true), 'com_reditem');
						$categoriesModel->setState('list.select', 'c.id');
						$categoriesModel->setState('filter.parentid', $category->id);
						$categoriesModel->setState('filter.published', 1);

						$subCategories = $categoriesModel->getItems();
						$subCategoriesIds = array();

						foreach ($subCategories As $subCategory)
						{
							$subCategoriesIds[] = $subCategory->id;
						}

						$extraFieldDatas = ReditemCategoryFieldsHelper::getAllFieldsData($extraFieldId, $subCategoriesIds);

						switch ($generatedFilterType)
						{
							case 'value':

								break;

							case 'select':
							default:
								$options = array();
								$options[] = JHTML::_('select.option', '', JText::_('JALL') . ' ' . $extraField->name);

								foreach ($extraFieldDatas as $extraFieldValue)
								{
									$options[] = JHTML::_('select.option', $extraFieldValue, $extraFieldValue);
								}

								$attribs = ' class="chosen" onChange="javascript:reditemCatExtraFilterAjax();"';

								$filters = JFactory::getApplication()->input->get('filter_catextrafield', null, 'array');

								$value = '';

								if ($filters)
								{
									if (isset($filters[$extraField->id]))
									{
										$value = $filters[$extraField->id];
									}
								}

								$selectHTML = JHTML::_('select.genericlist', $options, 'filter_catextrafield[' . $extraField->id . ']', $attribs, 'value', 'text', $value);

								$content = str_replace($match, $selectHTML, $content);
								break;
						}
					}
				}
			}
		}
	}

	/**
	 * Event on filter category ids
	 * 
	 * @return array  List of categories Id
	 */
	public function onFilterCategoryIds()
	{
		$input = JFactory::getApplication()->input;
		$db = JFactory::getDBO();

		/*
		 * Filter by extra values
		 */
		$filters = $input->get('filter_catextrafield', null, 'array');

		if ($filters)
		{
			$extraFieldModel = RModel::getAdminInstance('CategoryField', array('ignore_request' => false), 'com_reditemcategoryfields');
			$results = array();
			$first = true;
			$i = 0;
			$query = $db->getQuery(true);

			foreach ($filters as $extraFieldId => $extraFieldValue)
			{
				if ($extraFieldValue)
				{
					$extraField = $extraFieldModel->getItem($extraFieldId);

					if ($first)
					{
						$first = false;

						$query->select('DISTINCT (' . $db->quoteName('v.cat_id') . ')')
							->from($db->quoteName('#__reditem_category_fields_value', 'v'))
							->where($db->quoteName('v.' . $extraField->fieldcode) . ' = ' . $db->quote($extraFieldValue));
					}
					else
					{
						$tableName = $db->quoteName('#__reditem_category_fields_value', 'v' . $i);

						$query->innerjoin($tableName . ' ON ' . $db->quoteName('v.cat_id') . ' = ' . $db->quoteName('v' . $i . '.cat_id'))
							->where($db->quoteName('v' . $i . '.' . $extraField->fieldcode) . ' = ' . $db->quote($extraFieldValue));
					}

					$i++;
				}
			}

			if (!$first)
			{
				$db->setQuery($query);

				$results = $db->loadResultArray();

				if ($results)
				{
					return $results;
				}

				return array(-1);
			}
		}

		return false;
	}
}
