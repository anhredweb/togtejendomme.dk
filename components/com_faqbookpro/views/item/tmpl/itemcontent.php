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

<div id="a_w_<?php echo $this->id; ?>">
<div class="faq_faqAnswerWrapper_inner">

  <h1>
	  <?php echo $this->itemTitle; ?>
		<?php if ($this->itemFeatured) { ?>
		  <span class="category_faqFeatured"><?php echo JText::_('COM_FAQBOOKPRO_FEATURED_FAQ'); ?></span>
		<?php } ?>
  </h1>
    
  <!-- Item Image -->
  <?php if ($this->params->get('faq_image') && $this->itemImage) { ?>
  <div class="faq_image">
  	<img title="<?php echo htmlspecialchars($this->itemImageCaption); ?>" src="<?php echo htmlspecialchars($this->itemImage); ?>" alt="<?php echo htmlspecialchars($this->itemImageAlt); ?>"/>
  </div>
  <?php } ?>
	
  <?php echo $this->itemText; ?>
  
  <?php if ($this->params->get('faq_date') || $this->params->get('faq_author')) { ?>
  <div class="faq_extra">
    <?php echo $this->itemExtra; ?>
  </div>
  <?php } ?>
  
  <?php if (($this->params->get('faq_voting') &&( ($this->user->guest && $this->params->get('faq_guest_voting')) || ($this->user->id > 0) ) ) || $this->params->get('faq_permalink')) { ?>
  <div class="faq_tools">
    <?php echo $this->itemTools; ?>
  </div>
  <?php } ?>
  
</div>
</div>
