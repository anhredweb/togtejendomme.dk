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

// Highlight each search word
$search_val = $this->getModel()->cleanSearchTerm($this->search_val);
$search_terms = explode(' ', $search_val);  
?>

<div class="fbpContent_search">		
	<?php if ($this->search_val && $this->faqs_count) { ?>
        <h1><?php echo JText::_('COM_FAQBOOKPRO_SEARCH_RESULTS_FOR').' "'.htmlspecialchars($this->search_val).'"'; ?></h1>
    <?php } ?>
                
    <h3><?php echo $this->faqs_count.' '.JText::_('COM_FAQBOOKPRO_SEARCH_FAQS_FOUND'); ?></h3>
    
    <?php if ($this->search_val == '' || $this->faqs_count == '0' ) { ?>
    	<div class="empty_s_r">
        	<img class="lfloat" width="" height="" alt="" src="<?php echo JURI::base().'components/com_faqbookpro/images/search2.png'; ?>">
            <div class="empty_s_r_list">
                <div>
                    <span><?php echo JText::_('COM_FAQBOOKPRO_DID_NOT_FIND_RESULTS_FOR'); ?> <b><?php echo htmlspecialchars($this->search_val); ?></b></span>
                    <div>
                        <ul>
                            <li><?php echo JText::_('COM_FAQBOOKPRO_TRY_ALTERNATE_SPELLINGS'); ?></li>
                            <li><?php echo JText::_('COM_FAQBOOKPRO_TRY_FEWER_WORDS'); ?></li>
                            <li><?php echo JText::_('COM_FAQBOOKPRO_TRY_DIFFERENT_KEYWORDS'); ?></li>
                            <li><?php echo JText::_('COM_FAQBOOKPRO_TRY_A_MORE_GENERAL_SEARCH'); ?></li>
                        </ul>
                    </div>
                </div>
        	</div>
        </div>
    <?php } else if ($this->search_val && $this->faqs_count) { ?>
    <ul>
        <?php foreach ($this->faqs as $faq) { 
			// Compute faq slug
			$faq['slug'] = $faq['alias'] ? $faq['id'] : $faq['id'];
		?>
            <li>
            	<div class="s_r_f">
                    <a href="<?php echo JRoute::_(FaqBookProHelperRoute::getArticleRoute($faq['slug'], $faq['parent'])); ?>">
						<?php 
						foreach ($search_terms as $search_term) 
						{
							$faq['title'] = $this->getModel()->highlightWords($faq['title'], $search_term);
						}
						echo $faq['title']; 
						?>
                    </a>		
                    <?php if ($faq['frontpage']) { ?>
                    	<span class="category_faqFeatured"><?php echo JText::_('COM_FAQBOOKPRO_FEATURED_FAQ'); ?></span>
					<?php } ?>
                </div>
                <?php if ($this->params->get('search_category')) { ?>
                <div class="s_r_c">
                	<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=category&id='.$faq['parent'].'&Itemid='.$this->Itemid); ?>"><?php echo $faq['catname']; ?></a>	
                </div>
                <?php } ?>
                <?php if ($this->params->get('search_introtext')) { ?>
                <div class="s_r_t">
                	<?php 
					// FAQ Text
					$faq_introtext = $faq['introtext'];
					$faq_fulltext = $faq['introtext'].' '.$faq['text'];				
					if ($this->params->get('search_introtext_limit')) {
						$faq_pretext = strip_tags(FaqBookProHelperUtilities::getWordLimit($faq_fulltext, $this->params->get('search_introtext_limit')));
					} else {
						$faq_pretext = strip_tags(htmlspecialchars($faq_fulltext));
					} 
					
				    foreach ($search_terms as $search_term) 
				    {
						$faq_pretext = $this->getModel()->highlightWords($faq_pretext, $search_term);
					}
					echo $faq_pretext; 
					?>				
                </div>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
    <?php } ?>
</div>
