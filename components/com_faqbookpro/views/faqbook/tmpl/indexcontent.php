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
?>

		  <div>
          		<!-- Index Pre-text -->
				<?php if ($this->index_pre_text) 
				{
			    echo '<div class="index_pre_text">'.$this->index_pre_text.'</div>';
				} ?>
                
			  	<!-- Featured Categories -->
				<?php if ($this->index_featured) 
				{
			    echo $this->featured;
				} ?>
				
				<!-- Popular Questions -->
				
				<!-- Top Answers -->
				
				<!-- Ask a question -->
				
				<!-- Favourite faq's -->
                
                <!-- Index Post-text -->
				<?php if ($this->index_post_text) 
				{
			    echo '<div class="index_post_text">'.$this->index_post_text.'</div>';
				} ?>
				
			</div>
