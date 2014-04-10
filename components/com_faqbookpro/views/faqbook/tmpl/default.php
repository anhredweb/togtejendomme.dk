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

<?php if($this->params->get('show_page_title')): ?>
<!-- Page title -->
<h1 class="componentheading<?php echo $this->params->get('pageclass_sfx')?>">
  <?php echo $this->escape($this->params->get('page_title')); ?>
</h1>
<?php endif; ?>

<!-- Start Layout -->
<div id="fbpExtended" class="fbpIndexExtended">
  
	<!-- Top Navigation -->
  <?php echo $this->topnavigation; ?>
	
	<!-- Left Navigation -->
	<?php echo $this->leftnavigation; ?>
	
	<!-- Main Content -->
    <?php if ($this->leftnav) { ?>
  	<div id="fbpcontent" class="fbpContent_core">
  	  <div class="fbpContent_root">
  	    <?php include JPATH_SITE.'/components/com_faqbookpro/views/faqbook/tmpl/indexcontent.php'; ?>
  	  </div>
    </div>
		<?php } else { ?>
		<div id="fbpcontent" class="fbpContent_core noleftnav">
  	  <div class="fbpContent_root">
  	    <?php include JPATH_SITE.'/components/com_faqbookpro/views/faqbook/tmpl/indexcontent.php'; ?>
  	  </div>
    </div>
		<?php } ?>
	
</div><!-- End Layout -->
<div class="clearfix"> </div>