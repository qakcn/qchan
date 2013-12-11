<?php

/* Deny direct visit */
if(!defined('INDEX_RUN')) {
	header('HTTP/1.1 403 Forbidden');
	exit('This file must be loaded in flow.');
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; minimum-scale=1.0; maximum-scale=1.0">

	<meta name="Keywords" content="<?=SITE_KEYWORDS ?>">
	<meta name="Description" content="<?=SITE_DESCRIPTION ?>">
	
	<title><?=SITE_TITLE . ' - ' . __('Qchan Image Hosting') ?></title>

	<style>
		
	
	</style>
</head>
<body>
	<header id="main_header">
		<a title="<?=SITE_TITLE ?>" href="<?=get_url() ?>"><img src="site-img/logo_m.png" alt="Logo"></a>
	</header>
	
	<section id="main">
		
	</section>
	<section id="langsel_panel">
		
	</section>
	
	<footer id="main_footer">
		<div id="langsel"><?=__('Language') ?>&gt;</div>
		<div id="statement">
			<p>Copyright&copy; <?=SITE_TITLE ?></p>
			<p>Powered by <a href="http://github.com/" target="_blank">Qchan</a>, Image Hosting.</p>
		</div>
	</footer>
</body>
</html>
