<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
?>
<!DOCTYPE html>
<html>
	<head>
		<jdoc:include type="head" />
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/style.css" type="text/css" />
	</head>
	<body class="contentpane">
		<jdoc:include type="message" />
		<jdoc:include type="component" />
	</body>
</html>