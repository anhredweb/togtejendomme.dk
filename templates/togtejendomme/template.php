<?php
/**
 * @package    RedCOMPONENT.Template.Framework.
 *
 * @copyright  redCOMPONENT 2013 All Rights Reserved.
 *
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

?>
<!DOCTYPE html>
	<html>
	<head>
		<!--
            ##########################################
            ## redComponent ApS                     ##
            ## Blangstedgaardsvej 1                 ##
            ## 5220 Odense SØ                       ##
            ## Danmark                              ##
            ## support@redcomponent.com             ##
            ## http://www.redcomponent.com          ##
            ## Dato: 2013-04-23                     ##
            ##########################################
        -->
		<w:head />

		<link href='http://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>

	</head>
	<body <?php if ($bodyclass != "") : ?> class = "<?php echo $bodyclass ?>" <?php endif; ?>>	
	<?php if ($this->countModules('toolbar')) : ?>
		<!-- menu -->
		<w:nav containerClass="<?php echo $containerClass ?>" rowClass="<?php echo $gridMode; ?>" wrapClass="navbar-fixed-top navbar-inverse" type="toolbar" name="toolbar" />
	<?php endif; ?>

<div id="top-wrapper">
	<?php if ($this->countModules('featured')) : ?>
		<script>jQuery("#top-wrapper").css("height","470px");</script>
		<!-- featured -->
		<div id="featured">
			<w:module type="none" name="featured" chrome="xhtml" />
		</div>
	<?php endif; ?>
	
		<!-- header -->
		<header id="header">
			<div class="<?php echo $containerClass ?>">
				<div class="<?php echo $gridMode; ?> clearfix">
                	<w:logo name="top"   />
					<div class="clear"></div>
				</div>
				<?php if ($this->countModules('menu')) : ?>
					<!-- menu -->
					<w:nav name="menu" />
				<?php endif; ?>
			</div>
		</header>
</div>
	<div class="<?php echo $containerClass ?>">

		<?php if ($this->countModules('grid-top')) : ?>
			<!-- grid-top -->
			<div id="grid-top">
				<w:module type="<?php echo $gridMode; ?>" name="grid-top" chrome="popover" extra="bottom"  />
			</div>
		<?php endif; ?>
		<?php if ($this->countModules('grid-top2')) : ?>
			<!-- grid-top2 -->
			<div id="grid-top2">
				<w:module type="<?php echo $gridMode; ?>" name="grid-top2" chrome="wrightflexgrid" />
			</div>
		<?php endif; ?>

		<div id="main-content" class="<?php echo $gridMode; ?>">
			<!-- sidebar1 -->
			<aside id="sidebar1">
				<w:module name="sidebar1" chrome="accordion" />
			</aside>
			<!-- main -->
			<?php if ($this->countModules('above-content')) : ?>
				<!-- above-content -->
				<div id="above-content" class="span9">
					<w:module type="none" name="above-content" chrome="xhtml" />
				</div>
			<?php endif; ?>			


			<?php if ($this->countModules('sidebar3')) : ?>
				<div id="sidebar3" class="span3">
					<jdoc:include type="modules" name="sidebar3" />				
				</div>
			<?php endif; ?>

			<section id="main">
				<?php if ($this->countModules('breadcrumbs')) : ?>
					<!-- breadcrumbs -->
					<div id="breadcrumbs">
						<w:module type="single" name="breadcrumbs" chrome="none" />
					</div>
				<?php endif; ?>
				<!-- component -->
				<w:content />
				<?php if ($this->countModules('below-content')) : ?>
					<!-- below-content -->
					<div id="below-content">
						<w:module type="none" name="below-content" chrome="xhtml" />
					</div>
				<?php endif; ?>
			</section>		
			<?php if ($this->countModules('above-content-1') || $this->countModules('above-content-2')) : ?>
				<!-- above-content -->
			<div id="above-content">
				<div id="above-content-1" class="span9">
					<w:module type="none" name="above-content-1" chrome="xhtml" />
				</div>
				<div id="above-content-2" class="span3">
					<w:module type="none" name="above-content-2" chrome="xhtml" />
				</div>
			</div>
		<?php endif; ?>	
			<!-- sidebar2 -->
			<aside id="sidebar2">
				<w:module name="sidebar2" chrome="xhtml" />
			</aside>
		</div>
		<?php if ($this->countModules('grid-bottom')) : ?>
			<!-- grid-bottom -->
			<div id="grid-bottom">
				<w:module type="<?php echo $gridMode; ?>" name="grid-bottom" chrome="wrightflexgrid" />
			</div>
		<?php endif; ?>
		<?php if ($this->countModules('grid-bottom2')) : ?>
			<!-- grid-bottom2 -->
			<div id="grid-bottom2">
				<w:module type="<?php echo $gridMode; ?>" name="grid-bottom2" chrome="wrightflexgrid" />
			</div>
		<?php endif; ?>
		<?php if ($this->countModules('bottom-menu')) : ?>
			<!-- bottom-menu -->
			<w:nav containerClass="<?php echo $containerClass ?>" rowClass="<?php echo $gridMode; ?>" name="bottom-menu" />
		<?php endif; ?>
	</div>

	<!-- footer -->
	<div class="wrapper-footer">
		<footer id="footer" <?php if ($this->params->get('stickyFooter', 1)) : ?> class="sticky"<?php endif;?>>
			<img src="images/floatingbackground-half.png" />
			<div class="<?php echo $containerClass ?>">
				<?php if ($this->countModules('footer')) : ?>
					<w:module type="<?php echo $gridMode; ?>" name="footer" chrome="wrightflexgrid" />
				<?php endif; ?>
			</div>
		</footer>
	</div>

    <w:footer />
    <script type="text/javascript">
    	if (window.jQuery())
    	{
    		// Get the width of the current Bootstrap container and strip of last "px"
    		var containerWidth = parseInt((jQuery(".container").css('width')).substring(0,(jQuery(".container").css('width')).length - 2));
    		// Calculate the new left margin for caption description (minus the original margin-left)
    		var captionMargin = ((parseInt(document.documentElement.clientWidth) - containerWidth)/2)-90;
    		//console.log(captionMargin);

    		jQuery(".caption-description").each(function(){
    			jQuery(this).css('margin-left',captionMargin);
    		});

    		jQuery("div[class*='custom_bottommodule-']").each(function(){
				var tmp = jQuery(this).find('a').attr('href');
				if (tmp !== undefined){
					jQuery(this).attr('onclick','window.location.href="'+tmp+'";');
					jQuery(this).css('cursor','pointer');
				}
			});

    		if(jQuery("#plan").html() == "")
    		{
    			jQuery("a[href='#plan']").hide();
    		}

    		if (jQuery("#fieldline_2").length)
    		{
    			var val = jQuery(".lejemaalid").attr('title');
    			jQuery("#field2").attr('value',val);
    			console.log(jQuery("#field2").attr('value'));

    		}
    		jQuery("#field1").keyup( function(){
    			var pregMatch = /^[0-9]{8}$/;
    			console.log(jQuery("#field1").val());
    			if (jQuery("#field1").val().match(pregMatch))
    			{
    				//console.log("succses");
    			}
    			else
    			{
    				//console.log("failure");
    			}
    		});

    		///////////////////////////////////////////////////////
    		////////////////// Change contact box /////////////////
    		var dateOb = new Date();
    		var currentTime = {};
    		currentTime['month'] = dateOb.getMonth();
    		currentTime['day'] = dateOb.getDay();
    		currentTime['hour'] = dateOb.getHours();

    		//console.log(currentTime['month']+ " " + currentTime['day'] + " " + currentTime['hour']);

    		var changeContactBox = false;

    		if (currentTime['hour'] > 11 || currentTime['hour'] < 9)
    		{
    			//console.log("Det er senere end kl. 12 og tidligere end kl. 9.00\n");
    			changeContactBox = true;
    		}
    		if (currentTime['day'] > 5)
    		{
    			//console.log("Det er en lørdag eller søndag\n");
    			changeContactBox = true;
    		}
    		// Insert non-working days

    		if (changeContactBox)
    		{
    			jQuery("#above-content-2 .custom_kontaktmodul > h3").html('Akuthjælp');
    			jQuery("#above-content-2 .custom_kontaktmodul").css('background-image','url(../images/akutimg.jpg)');
    			jQuery("#above-content-2 .custom_kontaktmodul").css('background-size','contain');
				jQuery("#above-content-2 .custom_kontaktmodul > p:first").html('Er skaden sket? Så kan du kontakte os uden for normal telefontid');
				jQuery("#above-content-2 .custom_kontaktmodul > h4").html('RING: 70 70 13 39');
    		}
    		///////////////////////////////////////////////////////
    		///////////////////////////////////////////////////////
    	}
    </script>
	</body>
	</html>
