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
<div id="fbpExtended" class="fbpCategoryExtended">
  
  	<!-- Top Navigation -->
  	<?php echo $this->topnavigation; ?>
	
	  <?php if ($this->leftnav) { ?>
  	<!-- Left Navigation -->
  	<?php echo $this->leftnavigation; ?>
		<?php } ?>
	
	  <?php if ($this->leftnav) { ?>
  	<!-- Main Content -->
  	<div id="fbpcontent" class="fbpContent_core">
  	  <div class="fbpContent_root">
  	    <?php include JPATH_SITE.'/components/com_faqbookpro/views/category/tmpl/categorycontent.php'; ?>
  	  </div>
    </div>
		<?php } else { ?>
		<div id="fbpcontent" class="fbpContent_core noleftnav">
  	  <div class="fbpContent_root">
  	    <?php include JPATH_SITE.'/components/com_faqbookpro/views/category/tmpl/categorycontent.php'; ?>
  	  </div>
    </div>
		<?php } ?>
	
</div><!-- fbpCategoryExtended -->
<!-- End Layout -->
<div class="clearfix"> </div>
