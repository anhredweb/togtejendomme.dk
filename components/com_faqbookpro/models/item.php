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
 
class FaqBookProModelItem extends JModelItem
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
	
	public static function getItemContent($id, $params)
	{
	  $db = JFactory::getDBO();
		$query		= 'SELECT a.* FROM ' . $db->quoteName('#__faqbookpro_content') . ' AS a '
				. ' WHERE a.'.$db->quoteName('id').'=' . $db->Quote($id);

		$db->setQuery( $query );
		$result = $db->loadObject();
	
		return $result;
		
	}
	
	public static function getUserName($userid)
	{
	  $db = JFactory::getDBO();
		$query		= 'SELECT a.name FROM ' . $db->quoteName('#__users') . ' AS a '
				. ' WHERE a.'.$db->quoteName('id').'=' . $db->Quote($userid);

		$db->setQuery( $query );
		$result = $db->loadObject();
	
		return $result;
		
	}
	
	public static function getItemExtra($id, $params, $Itemid)
	{		
	    $faq = self::getItemContent($id, $params);
		
		$output = '';
		
		// FAQ Date
			if ($params->get('faq_date')) {
			  if ($params->get('faq_date_format') == '1') {
  			  $faq_date = JHTML::_('date', $faq->created, JText::_('d/m/Y'));
  			} else if ($params->get('faq_date_format') == '2') {
  			  $faq_date = JHTML::_('date', $faq->created, JText::_('m/d/Y'));
  			} else {
  			  $faq_date = JHTML::_('date', $faq->created, JText::_('M d, Y'));
  			}
			}
			// FAQ Author
			if ($params->get('faq_author')) {
			  $faq_author_id = $faq->created_by;
			  $faq_author = self::getUserName($faq_author_id)->name;
			  
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
	  
	  return $output;
		
	}
	
	public static function getItemTools($id, $params, $Itemid)
	{		
	    $Itemid = JRequest::getVar('Itemid',0,'','INT');
		$user = &JFactory::getUser();
		
		$alias = self::getItemAlias($id);
		$parent = self::getItemParent($id);
		// Compute faq slug
		$slug = $alias ? $id : $id;
		
		$output = '';
		
		// FAQ Voting
		if ($params->get('faq_voting') &&( ($user->guest && $params->get('faq_guest_voting')) || ($user->id > 0) ) ) {
    					$output .= '<div class="faq_voting">'; 
          			$output .= '<span class="faq_votingQuestion">'.JText::_('COM_FAQBOOKPRO_WAS_THIS_HELPFUL').'</span>'; 	
  							$output .= '<div id="vote_box" class="vote_box">'; 
								  // Vote up
								  $output .= '<div class="thumb-box">'; 
									$output .= '<a href="#" id="thumbs_up_'.$id.'" class="thumbs_up" onclick="return false;" rel="nofollow"><i></i><span>'.self::getFaqVotes($id, $type = 'vote_up').'</span></a>';
									$output .= '</div>';
									// Vote down
									$output .= '<div class="thumb-box">'; 
									$output .= '<a href="#" id="thumbs_down_'.$id.'" class="thumbs_down" onclick="return false;" rel="nofollow"><i></i><span>'.self::getFaqVotes($id, $type = 'vote_down').'</span></a>';
									$output .= '</div>';
								$output .= '</div>';							
        		  $output .= '</div>';
		} 
	  
	  // FAQ Permalink
	 if ($params->get('faq_permalink')) {
    					$output .= '<div class="faq_links">'; 
				  $output .= '<a id="faqPermalink_'.$id.'" href="'.JRoute::_(FaqBookProHelperRoute::getArticleRoute($slug, $parent)).'">'.JText::_('COM_FAQBOOKPRO_PERMALINK').'</a>'; 	
        		  $output .= '</div>'; 
	 }
	  
	  return $output;
		
	}
	
	public static function FaqVoting($id, $type) 
	{
		$db = &JFactory::getDBO();
		$reason = JRequest::getVar('reason',0,'','INT');
		$user = JFactory::getUser();
    	$user_id = $user->id;
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$jnow = JFactory::getDate();
		$date = $jnow->toSql();
		
		$output = '';
		
		// Add controls for existing votes
		if ($user_id) 
		{
  		$query		= ' SELECT a.* FROM ' . $db->quoteName('#__faqbookpro_voting') . ' AS a '
  				      . ' WHERE a.'.$db->quoteName('faq_id').'=' . $db->Quote($id)
								. ' AND a.'.$db->quoteName('user_id').'=' . $db->Quote($user_id);
  		$db->setQuery( $query );
		  $user_vote_exists = $db->loadObject();
			
			if (!$user_vote_exists) 
			{
  
    		if ($type == 1) {
    		  $query = " INSERT INTO "
          			  .$db->quoteName("#__faqbookpro_voting")
          				." (faq_id, user_id, user_ip, vote_up, published, creation_date) "
                  ." VALUES ('".$id."','".$user_id."','".$user_ip."','1','1','".$date."') ";
    			$db->setQuery($query);
    			$db->query();	
					
					// Get sum
  			  $query = "SELECT COUNT(*) FROM "
            			.$db->quoteName("#__faqbookpro_voting")
            			." WHERE " . $db->quoteName("faq_id") . "=" . $db->Quote($id)
            			." AND " . $db->quoteName("vote_up") . "=" . $db->Quote('1')
            			." AND " . $db->quoteName("published") . "=" . $db->Quote('1');
  				$db->setQuery($query);
  				$vote_sum = $db->loadResult();
					
        }
    		if ($type == 0) {
    		  $query = " INSERT INTO `#__faqbookpro_voting` (faq_id, user_id, user_ip, vote_down, reason, published, creation_date) "
                  ." VALUES ('".$id."','".$user_id."','".$user_ip."','1','".$reason."','1','".$date."') ";
    			$db->setQuery($query);
    			$db->query();		
					
					// Get sum
  			  $query = "SELECT COUNT(*) FROM "
            			.$db->quoteName("#__faqbookpro_voting")
            			." WHERE " . $db->quoteName("faq_id") . "=" . $db->Quote($id)
            			." AND " . $db->quoteName("vote_down") . "=" . $db->Quote('1')
            			." AND " . $db->quoteName("published") . "=" . $db->Quote('1');
  				$db->setQuery($query);
  				$vote_sum = $db->loadResult();
					
        }
				
				$output = $vote_sum;
				
			}
		
		} else {
		
		  $query		= ' SELECT a.* FROM ' . $db->quoteName('#__faqbookpro_voting') . ' AS a '
  				      . ' WHERE a.'.$db->quoteName('faq_id').'=' . $db->Quote($id)
								. ' AND a.'.$db->quoteName('user_ip').'=' . $db->Quote($user_ip);
  		$db->setQuery( $query );
		  $ip_vote_exists = $db->loadObject();
			
			if (!$ip_vote_exists) 
			{
  
    		if ($type == 1) {
    		  $query = " INSERT INTO `#__faqbookpro_voting` (faq_id, user_id, user_ip, vote_up, published, creation_date) "
                  ." VALUES ('".$id."','".$user_id."','".$user_ip."','1','1','".$date."') ";
    			$db->setQuery($query);
    			$db->query();	
					
					// Get sum
  			  $query = "SELECT COUNT(*) FROM "
            			.$db->quoteName("#__faqbookpro_voting")
            			." WHERE " . $db->quoteName("faq_id") . "=" . $db->Quote($id)
            			." AND " . $db->quoteName("vote_up") . "=" . $db->Quote('1')
            			." AND " . $db->quoteName("published") . "=" . $db->Quote('1');
  				$db->setQuery($query);
  				$vote_sum = $db->loadResult();
					
        }
    		if ($type == 0) {
    		  $query = " INSERT INTO `#__faqbookpro_voting` (faq_id, user_id, user_ip, vote_down, reason, published, creation_date) "
                  ." VALUES ('".$id."','".$user_id."','".$user_ip."','1','".$reason."','1','".$date."') ";
    			$db->setQuery($query);
    			$db->query();		
					
					// Get sum
  			  $query = "SELECT COUNT(*) FROM "
            			.$db->quoteName("#__faqbookpro_voting")
            			." WHERE " . $db->quoteName("faq_id") . "=" . $db->Quote($id)
            			." AND " . $db->quoteName("vote_down") . "=" . $db->Quote('1')
            			." AND " . $db->quoteName("published") . "=" . $db->Quote('1');
  				$db->setQuery($query);
  				$vote_sum = $db->loadResult();
    			
        }
				
				$output = $vote_sum;
				
			}
		
		}
		
		// Error controls here
		// display error messages
		
		echo $output;
		
	}
	
	public static function FaqVotingReason($rid, $fid) 
	{
		$db = &JFactory::getDBO();
		$user = JFactory::getUser();
    	$user_id = $user->id;
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$jnow = JFactory::getDate();
		$date = $jnow->toSql();
		
		if ($user_id) 
		{ 		
						
    		$query = " UPDATE `#__faqbookpro_voting` "
			." SET reason = ".$db->Quote($rid)." "
			." WHERE faq_id = ".$db->Quote($fid)." "
			." AND user_id = ".$db->Quote($user_id)." ";
                  
    		$db->setQuery($query);
    		$db->query();	
			
			$output = $rid;
			
			// db error handling	
						
		} else {
		    		
    		$query = " UPDATE `#__faqbookpro_voting` "
			." SET reason = ".$db->Quote($rid)." "
			." WHERE faq_id = ".$db->Quote($fid)." "
			." AND user_ip = ".$db->Quote($user_ip)." ";
                  
    		$db->setQuery($query);
    		$db->query();		
							
			$output = $rid;
			
			// db error handling			
						
		}
		
		return $output;
		
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
	
	public static function addHit($id)
	{	
	    $db = JFactory::getDBO();
		$query = " UPDATE `#__faqbookpro_content` "
			." SET hits = hits + 1 "
			." WHERE id = ".$db->Quote($id)." ";
                  
    		$db->setQuery($query);
    		$db->query();			
	}
	
	static function getItemAlias($id) 
	{	
	  $db = JFactory::getDBO();
	  $query = "SELECT alias FROM "
            .$db->quoteName("#__faqbookpro_content")
            ." WHERE " . $db->quoteName("id") . "=" . $db->Quote($id);
  	$db->setQuery($query);
  	$alias = $db->loadResult();
		
		return $alias;
	}
	
	static function getItemParent($id) 
	{	
	  $db = JFactory::getDBO();
	  $query = "SELECT catid FROM "
            .$db->quoteName("#__faqbookpro_content")
            ." WHERE " . $db->quoteName("id") . "=" . $db->Quote($id);
  	$db->setQuery($query);
  	$parent = $db->loadResult();
		
		return $parent;
	}
		
}