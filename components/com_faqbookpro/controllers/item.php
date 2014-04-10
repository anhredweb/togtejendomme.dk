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
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
class FaqBookProControllerItem extends JControllerLegacy {

 		function __construct() 
		{			
			parent::__construct();	
			$this->registerTask('faqThumbsUp', 'ajaxFaqThumbUp');
		  	$this->registerTask('faqThumbsDown', 'ajaxFaqThumbDown');	
			$this->registerTask('faqVoteReason', 'ajaxFaqVoteReason');	
			$this->registerTask('addHit', 'ajaxAddHit');	
	  }
		
		function ajaxFaqThumbUp()
		{
		  $this->ajaxFaqRating(1);
		}
		
		function ajaxFaqThumbDown()
		{
		  $this->ajaxFaqRating(0);
	  	}
		
		function ajaxFaqVoteReason()
		{
		  $this->ajaxFaqReason();
	  	}
		
		private function ajaxFaqRating($type)
		{
		  $user = &JFactory::getUser();
		  JSession::checkToken();			
		  $id = JRequest::getVar('id',0,'','INT');
			
			if ($id)
			{
				$model = &$this->getModel('item');
				$data = $model->FaqVoting($id, $type);
				if ($data)
				{
					//echo json_encode(array('rating'=>$data));
					echo $data;
				} else {
					$error = $model->getError();		
			  }
		  }
			  			
  		jexit();
  		}
		
		private function ajaxFaqReason()
		{
		  $user = &JFactory::getUser();
		  JSession::checkToken();			
		  $rid = JRequest::getVar('rid',0,'','INT');
		  $fid = JRequest::getVar('fid',0,'','INT');
			
			if ($rid && $fid)
			{
				$model = &$this->getModel('item');
				$data = $model->FaqVotingReason($rid, $fid);
				if ($data)
				{
					//echo json_encode(array('rating'=>$data));
					echo $data;
				} else {
					$error = $model->getError();		
			  }
		  }
			  			
  		jexit();
  	}
	
	function ajaxAddHit()
	{
		JSession::checkToken();			
		$id = JRequest::getVar('id',0,'','INT');
			
		if ($id)
		{
			$model = &$this->getModel('item');
			$data = $model->addHit($id);
			if ($data)
			{
				//echo json_encode(array('rating'=>$data));
				echo $data;
			} else {
				$error = $model->getError();		
			 }
		}
			  			
  		jexit();
  	}
	
}