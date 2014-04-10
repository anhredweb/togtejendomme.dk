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
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class FaqBookProModelCategory extends JModelItem
{ 
	
	function __construct() 
	{
		parent::__construct();
		
	}
	
	public static function getParentMenuParams()
	{
	  $activeMenu = JFactory::getApplication()->getMenu()->getActive();
		
		$parentMenuParams = $activeMenu->params;
		
		return $parentMenuParams;
		
	}
	
	public static function getCategoryContent($category, $params)
	{
	  $token = JSession::getFormToken();
	  $output = '';
		
	  // Get articles
	  $articles = self::getArticles($category->id);
	  
	  // FAQ Block
	  if (count($articles))
	  {
	    foreach ($articles as $faq) 
	    {
		  $output .= self::constructFaqs($faq, $category->id, $params);		
	    }
	  }
	  
	  return $output;
		
	}
	
	public static function constructFaqs($faq, $catid, $params)
	{		
		$Itemid = JRequest::getVar('Itemid',0,'','INT');
		$user = &JFactory::getUser();
		
		// Compute faq slug
		$faq['slug'] = $faq['alias'] ? $faq['id'] : $faq['id'];
		
		$output = '';
	  
	  	// FAQ Text
			$faq_introtext = $faq['introtext'];
			$faq_fulltext = $faq['introtext'].' '.$faq['text'];
			if ($params->get('faq_text')) {
			  if ($params->get('faq_text_limit')) {
			    $faq_pretext = strip_tags(FaqBookProHelperUtilities::getWordLimit($faq_fulltext, $params->get('faq_text_limit_num')));
				} else {
				  $faq_pretext = strip_tags($faq_fulltext);
				}
			}
			// FAQ Date
			if ($params->get('faq_date')) {
			  if ($params->get('faq_date_format') == '1') {
  			  $faq_date = JHTML::_('date', $faq['date'], JText::_('d/m/Y'));
  			} else if ($params->get('faq_date_format') == '2') {
  			  $faq_date = JHTML::_('date', $faq['date'], JText::_('m/d/Y'));
  			} else {
  			  $faq_date = JHTML::_('date', $faq['date'], JText::_('M d, Y'));
  			}
			}
			// FAQ Author
			if ($params->get('faq_author')) {
			  $faq_author_id = $faq['author'];
			  $faq_author = $faq['author_name'];
				if ($params->get('faq_author_link')) {
					// Load Jomsocial library
					$jomsocial = JComponentHelper::getComponent('com_community'); 	  
          if ($jomsocial) {
            $pathtojs = JPATH_ROOT.DS.'components'.DS.'com_community';				
            if (file_exists($pathtojs.DS.'libraries'.DS.'core.php')) {	
              include_once($pathtojs.DS.'libraries'.DS.'core.php');		
						}
          }
  				$faq_author_link = CRoute::_('index.php?option=com_community&view=profile&userid='.$faq_author_id);
  			}
			}
			
		  $output .= '<div id="faq_'.$faq['iid'].'" class="category_faqBlock">';  	
		    $output .= '<div class="category_faqPresentation">';
		      $output .= '<a href="#" id="faqLink_'.$faq['iid'].'" class="category_faqToggleLink" onclick="return false;">';
			    $output .= '<span class="category_faqToggleQuestion">';
			      $output .=   $faq['title'];
			    $output .= '</span>';
			    $output .= '<span class="category_faqExpanderIcon"></span>';
					if ($faq['frontpage']) {
					  $output .= '<span class="category_faqFeatured">'.JText::_('COM_FAQBOOKPRO_FEATURED_FAQ').'</span>';
					}
					// FAQ Preview
					if ($params->get('faq_text')) {
  					$output .= '<span class="category_faqAnswerWrapper_preview">';
  		      $output .= '<span>'.$faq_pretext.'</span>';
  					$output .= '</span>';
					}
			  $output .= '</a>';
		    $output .= '</div>';
		    $output .= '<div id="a_w_'.$faq['iid'].'" class="category_faqAnswerWrapper">';
				  
				  // FAQ Content
				  $output .= '<div class="category_faqAnswerWrapper_inner">';
		      $output .= $faq_fulltext;
					// FAQ Extra
					if ($params->get('faq_date') || $params->get('faq_author')) {
  					$output .= '<div class="faq_extra">';
						  if ($params->get('faq_date')) {
  					    $output .= '<span class="faq_date">'.JText::_('COM_FAQBOOKPRO_ON').' '.$faq_date.'</span>';
							}
							if ($params->get('faq_author')) {
							  if ($params->get('faq_author_link')) {
								  $output .= ' <span class="faq_author">'.JText::_('COM_FAQBOOKPRO_BY').' <a href="'.$faq_author_link.'" rel="nofollow">'.$faq_author.'</a></span>';
								} else {
								  $output .= ' <span class="faq_author">'.JText::_('COM_FAQBOOKPRO_BY').' '.$faq_author.'</span>';
								}
							}
  					$output .= '</div>'; 
					}
					// FAQ Tools
					if (($params->get('faq_voting') &&( ($user->guest && $params->get('faq_guest_voting')) || ($user->id > 0) ) ) || $params->get('faq_permalink')) {
					$output .= '<div class="faq_tools">';
  					// FAQ Voting
						if ($params->get('faq_voting') &&( ($user->guest && $params->get('faq_guest_voting')) || ($user->id > 0) ) ) {
    					$output .= '<div class="faq_voting">'; 
          			$output .= '<span class="faq_votingQuestion">'.JText::_('COM_FAQBOOKPRO_WAS_THIS_HELPFUL').'</span>'; 	
  							$output .= '<div class="vote_box">'; 
								  // Vote up
								  $output .= '<div class="thumb-box">'; 
									$output .= '<a href="#" id="thumbs_up_'.$faq['iid'].'" class="thumbs_up" onclick="return false;" rel="nofollow"><i></i><span>'.FAQBookProModelCategory::getFaqVotes($faq['iid'], $type = 'vote_up').'</span></a>';
									$output .= '</div>';
									// Vote down
									$output .= '<div class="thumb-box">'; 
									$output .= '<a href="#" id="thumbs_down_'.$faq['iid'].'" class="thumbs_down" onclick="return false;" rel="nofollow"><i></i><span>'.FAQBookProModelCategory::getFaqVotes($faq['iid'], $type = 'vote_down').'</span></a>';
									$output .= '</div>';
								$output .= '</div>';
        		  $output .= '</div>';
						}
  					// FAQ Permalink
						if ($params->get('faq_permalink')) {
    					$output .= '<div class="faq_links">'; 
				  $output .= '<a id="faqPermalink_'.$faq['iid'].'" href="'.JRoute::_(FaqBookProHelperRoute::getArticleRoute($faq['slug'], $faq['parent'])).'">'.JText::_('COM_FAQBOOKPRO_PERMALINK').'</a>'; 
        		  $output .= '</div>'; 
						}
						//$output .= '<div class="clearfix"> </div>';
					$output .= '</div>';
				  }
					$output .= '</div>';
		    $output .= '</div>';
		  $output .= '</div>';
	  
	  	return $output;
		
	}
	
	public static function getCategoryTools($category, $params, $Itemid)
	{		
		$output = '';
	  
		  $output .= '<div class="category_voting">'; 
			$output .= '<span class="category_votingQuestion">'.JText::_('COM_FAQBOOKPRO_WAS_THIS_HELPFUL').'</span>'; 	
		  $output .= '</div>';
			  
	  return $output;
		
	}
	
	public static function getSubcategoryContent($category, $params)
	{
	  $Itemid = JRequest::getVar('Itemid',0,'','INT');
		
	  $output = '';
		if ($params->get('subcategories_title'))
	  {
	    $output .= '<h2 class="subCategory_sectionTitle"><a id="catPermalink_'.$category->id.'" href="'.JRoute::_('index.php?option=com_faqbookpro&view=category&id='.$category->id.'&Itemid='.$Itemid).'">'.$category->title.'</a></h2>';
	  }
		if ($params->get('subcategories_description'))
	  {
	    $output .= '<span class="subCategory_sectionDescription">'.$category->description.'</span>';
	  }
		if ($params->get('subcategories_image'))
	  {
		  $imgsize = $params->get('subcategories_imageSize');
		  $imgheight = $params->get('subcategories_imageHeight');
		  $path = $category->getParams()->get('image');
			$img = FaqBookProHelperUtilities::resizeImage($imgsize, $imgheight, $path, $category->title);
		  
			$output .= '<div class="fbpContent_catImage">';
	      $output .= '<img src="'.$img.'" alt="">';
			$output .= '</div>';
	  }
	
	  if ($params->get('subcategories_faqs')) {
		  // Get articles
		  $articles = self::getArticles($category->id);
		  
		  // FAQ Block
		  if (count($articles))
		  {
			foreach ($articles as $faq) 
			{
			  $output .= self::constructFaqs($faq, $category->id, $params);
			}
		  }
	  }
	  
	  return $output;
		
	}
	
	static function getArticles($catid) 
	{	
	  // Initializing standard Joomla classes and SQL necessary variables
	  $db = JFactory::getDBO();
	  $date = JFactory::getDate("now");
	  $now  = $date->toSql(true);
	  $nullDate = $db->getNullDate();
		
		// Get params
	  $params = FaqBookProHelperUtilities::getParams('com_faqbookpro');	
	  
	  $sql_where = '';
	  
	  // Defining the parent category
	  $parent_con = ' AND content.catid = '.$catid;
	  	  
	  // Arrays for content
	  $content = array();
	  $faqs_amount = 0;
		
      // Defining the user access
	  $user = JFactory::getUser();
	  $groups = implode(',', $user->getAuthorisedViewLevels());
	  $access_con = 'AND content.access IN ('.$groups.')';
	  	
	  // Condition for Featured articles
	  $frontpage_con = '';
		
	  // Ordering string
	  $order_options = '';
		
	  // When sort value is random
	  if ($params->get('faqs_ordering') == 'random') 
	  {
		$order_options = ' RAND() '; 
	  }
	  else
	  { // when sort value is different than random
		$order_options = ' content.'.$params->get('faqs_ordering').' ';
	  }
		
	  // Language filters
	  $lang_filter = '';
	  if (JFactory::getApplication()->getLanguageFilter()) 
	  {
		$lang_filter = ' AND content.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').') ';
	  }
		
	  // SQL query			
	  $query_faqs = 'SELECT 
		content.id AS iid,
		content.title AS title, 
		content.alias AS alias,
		content.introtext AS introtext, 
		content.fulltext AS text, 
		content.catid AS parent, 
		content.created_by AS author, 
		content.created AS date, 
		content.publish_up AS date_publish,
		content.hits AS hits,
		content.images AS images,
		content.featured AS frontpage				
	  FROM 
		#__faqbookpro_content AS content 
	  WHERE 
		content.state = 1
        '. $access_con .'   
	  AND ( content.publish_up = '.$db->Quote($nullDate).' OR content.publish_up <= '.$db->Quote($now).' )
	  AND ( content.publish_down = '.$db->Quote($nullDate).' OR content.publish_down >= '.$db->Quote($now).' )
		'.$parent_con.'
		'.$sql_where.'
		'.$lang_filter.'
		'.$frontpage_con.' 
	  ORDER BY 
			'.$order_options.'
		';
		
	  // Run SQL query
	  $db->setQuery($query_faqs);
		
	  // Get results
	  if ($faqs = $db->loadAssocList()) 
	  {			
		// Generating tables of articles
		foreach ($faqs as $item) 
		{	
		  $content[] = $item; // store item in array
		  $faqs_amount++;	// faqs amount
		}
	  }
		
	  if (count($content))
	  {	
	    // Generate SQL WHERE condition
		$second_sql_where = '';
		for ($i = 0; $i < count($content); $i++) 
		{
		  $second_sql_where .= (($i != 0) ? ' OR ' : '') . ' content.id = ' . $content[$i]['iid'];
		}
		
		// Second SQL query to get rest of the data and avoid the DISTINCT
		$second_query_faqs = '
		SELECT
		  content.id AS iid,
		  content.access AS access,
		  categories.title AS catname, 
		  users.email AS author_email,
			users.name AS author_name,
		  content_rating.rating_sum AS rating_sum,
		  content_rating.rating_count AS rating_count,
		CASE WHEN CHAR_LENGTH(content.alias) 
	      THEN CONCAT_WS(":", content.id, content.alias) 
		    ELSE content.id END as id, 
	    CASE WHEN CHAR_LENGTH(categories.alias) 
		  THEN CONCAT_WS(":", categories.id, categories.alias) 
			ELSE categories.id END as cid			
		FROM 
		  #__faqbookpro_content AS content 
		LEFT JOIN 
		  #__categories AS categories 
		ON categories.id = content.catid 
		LEFT JOIN 
		  #__users AS users 
		ON users.id = content.created_by 			
		LEFT JOIN 
		  #__content_rating AS content_rating 
		ON content_rating.content_id = content.id
		WHERE 
			'.$second_sql_where.'
		ORDER BY 
			'.$order_options.'
		';
		
		// Run the query
		$db->setQuery($second_query_faqs);
		
		// Get results
		if ($faqs2 = $db->loadAssocList()) 
		{
		  // Create the iid array
		  $content_iid = array();
		  
		  // Create the content IDs array
		  foreach ($content as $item) 
		  {
			array_push($content_iid, $item['iid']);
		  }
		  
		  // Generate tables of faqs data
		  foreach ($faqs2 as $item) 
		  {						
			$pos = array_search($item['iid'], $content_iid);
				
			// Check the access restrictions
			$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
			$access = JComponentHelper::getParams('com_content')->get('show_noauth');
			
			// Merge the new data to the array of items data
			$content[$pos] = array_merge($content[$pos], (array) $item);
		  }
		}
		
		// Get the content array
		return $content; 
		
	  } // end if count($content)
	  
	}
	
	static function getFaqVotes($faq_id, $type) 
	{	
	  $db = JFactory::getDBO();
	  $query = "SELECT COUNT(*) FROM "
            .$db->quoteName("#__faqbookpro_voting")
            ." WHERE " . $db->quoteName("faq_id") . "=" . $db->Quote($faq_id)
            ." AND " . $db->quoteName($type) . "=" . $db->Quote('1')
            ." AND " . $db->quoteName("published") . "=" . $db->Quote('1');
  	$db->setQuery($query);
  	$vote_sum = $db->loadResult();
		
		return $vote_sum;
	}
		
}