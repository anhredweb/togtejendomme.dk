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

<!-- Start Layout -->
<div id="fbpExtended" class="fbpItemLayout">
  
  	<!-- Top Navigation -->
  	<?php echo $this->topnavigation; ?>
	
	  <?php if ($this->leftnav) { ?>
    	
			<!-- Left Navigation -->
    	<?php echo $this->leftnavigation; ?>
			
			<?php $class = 'fbpContent_core'; ?>
			
		<?php } else { ?>
		
		  <?php $class = 'fbpContent_core noleftnav'; ?>
			
		<?php } ?>
	  
  	<!-- Main Content -->
  	<div id="fbpcontent" class="<?php echo $class; ?>">
  	  <div class="fbpContent_root">
  	    <?php include JPATH_SITE.'/components/com_faqbookpro/views/item/tmpl/itemcontent.php'; ?>
  	  </div>
    </div>
	
</div>
<!-- End Layout -->
<div class="clearfix"> </div>
