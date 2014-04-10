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
defined('_JEXEC') or die;

$user  = JFactory::getUser();
$token = JSession::getFormToken();
?>

<table width="100%" border="0">
	<tr>
		<td width="55%" valign="top">

<div id="cpanel" style="float:left;">

<div style="float:left;">
    	<div class="icon">
	    	<a class="thumbnail" href="index.php?option=com_categories&extension=com_faqbookpro">
                <img alt="Categories" src="components/com_faqbookpro/assets/images/dashboard/icon-48-category.png">
                <br>
                <span><?php echo JText::_('COM_FAQBOOKPRO_SUBMENU_CATEGORIES'); ?></span>
            </a>
    	</div>
  	</div>
    
    <div style="float:left;">
    	<div class="icon">
	    	<a class="thumbnail" href="index.php?option=com_faqbookpro&view=articles">
                <img alt="Articles" src="components/com_faqbookpro/assets/images/dashboard/icon-48-article.png">
                <br>
                <span><?php echo JText::_('COM_FAQBOOKPRO_SUBMENU_ARTICLES'); ?></span>
            </a>
    	</div>
  	</div>
    <div style="float:left;">
    	<div class="icon">
	    	<a class="thumbnail" href="index.php?option=com_faqbookpro&view=votes">
                <img alt="Votes" src="components/com_faqbookpro/assets/images/dashboard/icon-48-votes.png">
                <br>
                <span><?php echo JText::_('COM_FAQBOOKPRO_SUBMENU_VOTES'); ?></span>
            </a>
    	</div>
  	</div>
    <div style="float:left;">
    	<div class="icon">
	    	<a class="thumbnail" href="index.php?option=com_faqbookpro&view=about">
                <img alt="About" src="components/com_faqbookpro/assets/images/dashboard/icon-48-about.png">
                <br>
                <span><?php echo JText::_('COM_FAQBOOKPRO_SUBMENU_ABOUT'); ?></span>
            </a>
    	</div>
  	</div>
    <div style="float:left;">
    	<div class="icon">
	    	<a class="thumbnail" href="http://www.minitek.gr/support/documentation/joomla-extensions/faq-book-pro" target="_blank">
                <img alt="Files" src="components/com_faqbookpro/assets/images/dashboard/icon-48-help.png">
                <br>
                <span><?php echo JText::_('COM_FAQBOOKPRO_SUBMENU_HELP'); ?></span>
            </a>
    	</div>
  	</div>
     
  
	<div class="clr"></div>
  
</div>
<div class="clr"></div>
    
</td>
		<td width="45%" valign="top">
			<?php
				echo $this->pane->startPane( 'stat-pane' );
				echo $this->pane->startPanel( JText::_('COM_FAQBOOKPRO_QUICK_ICONS') , 'welcome' );
			?>
			<table class="adminlist">
				<tr>
					<td>
            
                <?php if ($user->authorise('core.create', 'com_faqbookpro')) { ?>
            	<div class="row-fluid">
                    <div class="quick-link span12">
                    	<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&task=article.add'); ?>">
                    	<span class="icon-newarticle"><?php echo JText::_('COM_FAQBOOKPRO_QUICKICONS_ADD_NEW_ARTICLE'); ?></span>
                    	</a>
                    </div>
                </div>
                <?php } ?>
                <div class="row-fluid">
                    <div class="quick-link span12">
                    	<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=articles'); ?>">
                    	<span class="icon-article"><?php echo JText::_('COM_FAQBOOKPRO_QUICKICONS_ARTICLE_MANAGER'); ?></span>
                    	</a>
                    </div>
                </div>
                <?php if ($user->authorise('core.create', 'com_faqbookpro')) { ?>
                <div class="row-fluid">
                    <div class="quick-link span12">
                    	<a href="<?php echo JRoute::_('index.php?option=com_categories&view=category&layout=edit&extension=com_faqbookpro'); ?>">
                    	<span class="icon-newcategory"><?php echo JText::_('COM_FAQBOOKPRO_QUICKICONS_ADD_NEW_CATEGORY'); ?></span>
                    	</a>
                    </div>
                </div>
                <?php } ?>
                <div class="row-fluid">
                    <div class="quick-link span12">
                    	<a href="<?php echo JRoute::_('index.php?option=com_categories&extension=com_faqbookpro'); ?>">
                    	<span class="icon-category"><?php echo JText::_('COM_FAQBOOKPRO_QUICKICONS_CATEGORY_MANAGER'); ?></span>
                    	</a>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="quick-link span12">
                    	<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=votes'); ?>">
                    	<span class="icon-featured"><?php echo JText::_('COM_FAQBOOKPRO_QUICKICONS_VOTES'); ?></span>
                    	</a>
                    </div>
                </div>
                <?php if ($user->authorise('core.manage', 'com_faqbookpro')) { ?> 
                <div class="row-fluid">
                    <div class="quick-link span12">
                    	<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&task=purgeImages&'.$token.'=1'); ?>">
                    	<span class="icon-trash"><?php echo JText::_('COM_FAQBOOKPRO_QUICKICONS_PURGE_IMAGE_CACHE'); ?></span>
                    	</a>
                    </div>
                </div>
                <?php } ?>
                <div class="row-fluid">
                    <div class="quick-link span12">
                    	<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=about'); ?>">
                    	<span class="icon-info"><?php echo JText::_('COM_FAQBOOKPRO_QUICKICONS_ABOUT'); ?></span>
                    	</a>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="quick-link span12">
                    	<a href="http://www.minitek.gr/support/documentation/joomla-extensions/faq-book-pro" target="_blank">
                    	<span class="icon-help"><?php echo JText::_('COM_FAQBOOKPRO_QUICKICONS_HELP'); ?></span>
                    	</a>
                    </div>
                </div>
                <?php if ($user->authorise('core.admin', 'com_faqbookpro')) { ?> 
                <div class="row-fluid">
                    <div class="quick-link span12">
                    	<a href="<?php echo JRoute::_('index.php?option=com_config&view=component&component=com_faqbookpro'); ?>">
                    	<span class="icon-maintenance"><?php echo JText::_('COM_FAQBOOKPRO_QUICKICONS_CONFIGURATION'); ?></span>
                    	</a>
                    </div>
                </div>
                <?php } ?>
                <div class="row-fluid">
                    <div class="quick-link span12">
                    	<a href="http://extensions.joomla.org/extensions/directory-a-documentation/faq/19917" target="_blank">
                    	<span class="icon-featured"><?php echo JText::_('COM_FAQBOOKPRO_QUICKICONS_REVIEW'); ?></span>
                    	</a>
                    </div>
                </div>
                
            <!--</div>
    	</div>
    </div>-->

					</td>
				</tr>
			</table>
			<?php
				echo $this->pane->endPanel();
            echo $this->pane->startPanel( JText::_('COM_FAQBOOKPRO_VERSION') , 'welcome' );
			?>    
            <table class="adminlist">
				<tr>
					<td>
                <div class="quick-link version">            
                    <p class="alert-heading"><?php echo JText::_('COM_FAQBOOKPRO_VERSION_MSG'); ?></p>
                    <a class="btn" title="Check for new version" href="index.php?option=com_installer&view=update">Check for new version</a>
                    <br />
                </div>
                </td>
				</tr>
			</table>
    		<?php		
			echo $this->pane->endPanel();
				echo $this->pane->endPane();
			?>
		</td>
	</tr>
</table>

<div class="clr"></div>



    
    

	
