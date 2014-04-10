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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

$app = JFactory::getApplication();
$user = JFactory::getUser();
$input = $app->input;

// Get current url
$uri = & JFactory::getURI();
$current = $uri->toString();
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'article.cancel' || document.formvalidator.isValid(document.id('item-form')))
		{
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
</script>

<div class="fbpContent_ask well">

<h1><?php echo JText::_('COM_FAQBOOKPRO_ASK_A_QUESTION'); ?></h1>

<form action="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=article&layout=edit&Itemid='.$this->Itemid); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

	<div class="row-fluid">
		<!-- Begin Content -->
		<div class="span12 form-horizontal">

					<fieldset class="adminform">
                    
						<div class="control-group form-inline">
                            <div class="control-label">
							<?php echo $this->form->getLabel('title'); ?> 
                            </div>
                            <div class="clearfix"> </div>
                            <div class="controls">
							<?php echo $this->form->getInput('title'); ?> 
							</div>
                        </div>
                        
                        <?php 
							$cat_class = '';
							if (!$this->params->get('allow_select_category')) { 
								$cat_class = ' category';
							}
						?>
                        <div class="control-group form-inline<?php echo $cat_class; ?>">
                            <div class="control-label">
							<?php echo $this->form->getLabel('catid'); ?> 
                            </div>
                            <div class="clearfix"> </div>
                            <div class="controls">
							<?php echo $this->form->getInput('catid'); ?>
                            </div>
                        </div>

					<div class="control-group">
                            <div class="control-label">
							<?php echo $this->form->getLabel('language'); ?> 
                            </div>
                            <div class="clearfix"> </div>
                            <div class="controls">
							<?php echo $this->form->getInput('language'); ?>
                            </div>
                        </div>
                        
                        <?php if ( ($this->params->get('askform_captcha')==1 && !$user->id) || ($this->params->get('askform_captcha')==2)) { ?>
                        <div class="control-group form-inline">
                            <div class="control-label">
							<?php echo $this->form->getLabel('captcha'); ?> 
                            </div>
                            <div class="clearfix"> </div>
                            <div class="controls">
							<?php 
							echo $this->form->getInput('captcha'); 
							?>
                            </div>
                        </div>
						<?php } ?>
                        
						<div class="control-group">
                            <div class="control-label">
                            </div>
                            <div class="controls">
                            	<br />
                                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('article.save')">
                                    <i class="icon-new"></i> <?php echo JText::_('COM_FAQBOOKPRO_BUTTON_SAVE_AND_CLOSE') ?>
                                </button>
                            </div>
                        </div>
                        
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="return" value="<?php echo $current;?>" />
			<?php echo JHtml::_('form.token'); ?>
            
            </fieldset>
		</div>
		
	</div>
</form>
</div>