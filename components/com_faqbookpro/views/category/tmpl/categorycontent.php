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
<?php if ($this->params_category_title) { ?>
  <h1><a id="catPermalink_<?php echo $this->categoryId; ?>" href="<?php echo JRoute::_(FaqBookProHelperRoute::getCategoryRoute($this->categoryId)); ?>">
  <?php echo $this->categoryTitle; ?></a></h1>
<?php } ?>

<?php if ($this->params_category_description && $this->categoryDesc) { ?>
  <p><?php echo $this->categoryDesc; ?></p>
<?php } ?>

<?php if ($this->params_category_image && $this->categoryImage) { ?>
  <div class="fbpContent_catImage">
    <img src="<?php echo $this->categoryImage; ?>" alt="<?php echo $this->categoryTitle; ?>">
	</div>
<?php } ?>

<?php if ($this->categoryContent) { ?>
  <div class="category_section">
    <?php echo $this->categoryContent; ?>
	</div>  
<?php } ?>

<?php if (count($this->subcategoryContents)) { ?>
  <?php foreach ($this->subcategoryContents as $subcategoryContent) { ?>
	  <div class="subCategory_section">
      <?php echo $subcategoryContent; ?>
		</div>  
  <?php } ?>
<?php } ?>