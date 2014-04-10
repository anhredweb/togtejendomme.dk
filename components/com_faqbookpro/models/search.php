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
 
class FaqBookProModelSearch extends JModelItem
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
	
	public static function getIndexCatsString($indexRoot) 
	{
		$items = FaqBookProHelperUtilities::getCategoriesRoot($indexRoot);
		
		$output = '';
		
		foreach ($items as $item) 
		{
			$categoryString = FaqBookProHelperUtilities::getCategoriesTreeString($item);
			$output .= $categoryString;
		}	
		
		$output = rtrim($output, ',');	
		return $output;
		
	}
	
	public static function getSearchResults($search_val, $catsString) 
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
	  if ($catsString) {
	    $parent_con = ' AND content.'. $db->quoteName('catid').' IN ('.$catsString.')';
	  } else {
		$parent_con = '';
	  }
	  	  
	  // Arrays for content
	  $content = array();
	  $faqs_amount = 0;
		
      // Defining the user access
	  $user = JFactory::getUser();
	  $groups = implode(',', $user->getAuthorisedViewLevels());
	  $access_con = 'AND content.'. $db->quoteName('access').' IN ('.$groups.')';
	  	
	  // Condition for Featured articles
	  $frontpage_con = '';
		
	  // Ordering string		
	  if ($params->get('search_ordering') == 'random') 
	  {
		$order_options = ' RAND() '; 
	  }
	  else if ($params->get('search_ordering') == 'title_desc') 
	  { 
		$order_options = ' content.'. $db->quoteName('title').' DESC ';
	  }
	  else if ($params->get('search_ordering') == 'created_desc') 
	  {
		$order_options = ' content.'. $db->quoteName('created').' DESC ';
	  } else {
		$order_options = ' content.'. $db->quoteName($params->get('search_ordering')).' ';  
	  }
	  
	  // Clean-up search term
	  if (!$search_val) {
		  return;
	  } else {
		  $search_val = self::cleanSearchTerm($search_val);
		  $search_terms = explode(' ', $search_val);
	  }
	  
	  foreach ($search_terms as $key=>$search_term) 
	  {
		  if ($key == '0') 
		  {
		  	$search_options = ' AND ( '; 
		  }
		  if (($key > '0') && ($key < count($search_terms))) 
		  {
		  	$search_options .= ' OR '; 
		  }
		  $search_options .= ' (content.'. $db->quoteName('title').' LIKE "%'.$search_term.'%"';
		  $search_options .= ' OR content.'. $db->quoteName('introtext').' LIKE "%'.$search_term.'%"';
		  $search_options .= ' OR content.'. $db->quoteName('fulltext').' LIKE "%'.$search_term.'%"';
		  $search_options .= ' )';
		  if ($key == count($search_terms) - 1) 
		  {
		  	$search_options .= ' ) '; 
		  }
	  }
	  
	  // Language filters
	  $lang_filter = '';
	  if (JFactory::getApplication()->getLanguageFilter()) 
	  {
		$lang_filter = ' AND content.'. $db->quoteName('language').' in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').') ';
	  }
		
	  // SQL query			
	  $query_faqs = 'SELECT 
		content.'. $db->quoteName('id').' AS '. $db->quoteName('iid').',
		content.'. $db->quoteName('title').' AS '. $db->quoteName('title').', 
		content.'. $db->quoteName('alias').' AS '. $db->quoteName('alias').', 
		content.'. $db->quoteName('introtext').' AS '. $db->quoteName('introtext').', 
		content.'. $db->quoteName('fulltext').' AS '. $db->quoteName('text').', 
		content.'. $db->quoteName('catid').' AS '. $db->quoteName('parent').', 
		content.'. $db->quoteName('created_by').' AS '. $db->quoteName('author').', 
		content.'. $db->quoteName('created').' AS '. $db->quoteName('date').', 
		content.'. $db->quoteName('publish_up').' AS '. $db->quoteName('date_publish').',
		content.'. $db->quoteName('hits').' AS '. $db->quoteName('hits').',
		content.'. $db->quoteName('featured').' AS '. $db->quoteName('frontpage').'				
	  FROM 
		' . $db->quoteName('#__faqbookpro_content') . ' AS content 
	  WHERE 
		content.'. $db->quoteName('state').' = '. $db->Quote('1').'
        '. $access_con .'   
	  AND ( content.'. $db->quoteName('publish_up').' = '.$db->Quote($nullDate).' OR content.'. $db->quoteName('publish_up').' <= '.$db->Quote($now).' )
	  AND ( content.'. $db->quoteName('publish_down').' = '.$db->Quote($nullDate).' OR content.'. $db->quoteName('publish_down').' >= '.$db->Quote($now).' )
		'.$parent_con.'
		'.$sql_where.'
		'.$lang_filter.'
		'.$frontpage_con.' 
		'.$search_options.' 
	  ORDER BY 
			'.$order_options.'
	  LIMIT '.$params->get('search_limit').'
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
		  content.'. $db->quoteName('id').' AS '. $db->quoteName('iid').',
		  content.'. $db->quoteName('access').' AS '. $db->quoteName('access').',
		  categories.'. $db->quoteName('title').' AS '. $db->quoteName('catname').', 
		  users.'. $db->quoteName('email').' AS '. $db->quoteName('author_email').',
		  users.'. $db->quoteName('name').' AS '. $db->quoteName('author_name').',
		CASE WHEN CHAR_LENGTH(content.'. $db->quoteName('alias').') 
	      THEN CONCAT_WS(":", content.'. $db->quoteName('id').', content.'. $db->quoteName('alias').') 
		    ELSE content.'. $db->quoteName('id').' END as id, 
	    CASE WHEN CHAR_LENGTH(categories.'. $db->quoteName('alias').') 
		  THEN CONCAT_WS(":", categories.'. $db->quoteName('id').', categories.'. $db->quoteName('alias').') 
			ELSE categories.'. $db->quoteName('id').' END as cid			
		FROM 
		  ' . $db->quoteName('#__faqbookpro_content') . ' AS content 
		LEFT JOIN 
		  ' . $db->quoteName('#__categories') . ' AS categories 
		ON categories.' . $db->quoteName('id') . ' = content.' . $db->quoteName('catid') . ' 
		LEFT JOIN 
		  ' . $db->quoteName('#__users') . ' AS users 
		ON users.' . $db->quoteName('id') . ' = content.' . $db->quoteName('created_by') . ' 			
		/*LEFT JOIN 
		  ' . $db->quoteName('#__content_rating') . ' AS content_rating 
		ON content_rating.' . $db->quoteName('content_id') . ' = content.' . $db->quoteName('id') . '*/
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
			$access = JComponentHelper::getParams('com_faqbookpro')->get('show_noauth');
			
			// Merge the new data to the array of items data
			$content[$pos] = array_merge($content[$pos], (array) $item);
		  }
		}
		
		// Get the content array
		return $content; 
		
	  } // end if count($content)
	}
	
	public static function cleanSearchTerm($search_val) 
	{
		// Search word conditions
	  	$search_val = str_replace(',', ' ', $search_val);
	  	$search_val = preg_replace('/( )+/', ' ', $search_val); // remove excessive whitespace
		$search_val = trim($search_val);
		
		return $search_val;
	}
	
	public static function highlightWords($text, $words) 
	{
		preg_match_all('~\w+~', $words, $m);
		if(!$m)
			return $text;
		$re = '~\\b(' . implode('|', $m[0]) . ')\\b~i';
		return preg_replace($re, '<b>$0</b>', $text);
	}
	
}