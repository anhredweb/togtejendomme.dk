<?php
/**
 * @package    RedCOMPONENT.Template.Framework.
 *
 * @copyright  redCOMPONENT 2013 All Rights Reserved.
 *
 */

// Restrict Access to within Joomla
defined('_JEXEC') or die('Restricted access');

// Get the bootstrap row mode ( row / row-fluid )
$gridMode = $this->params->get('bs_rowmode', 'row-fluid');
$containerClass = 'container';

if ($gridMode == 'row-fluid')
{
	$containerClass = 'container-fluid';
}

$bodyclass = "";

if ($this->countModules('toolbar'))
{
	$bodyclass = "toolbarpadding";
}
