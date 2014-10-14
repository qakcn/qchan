<?php

/* Deny direct visit */
if(!defined('INDEX_RUN')) {
	header('HTTP/1.1 403 Forbidden');
	exit('This file must be loaded in flow.');
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1,minimum-scale=1,user-scalable=no"
	
	<meta name="Keywords" content="<?=SITE_KEYWORDS ?>">
	<meta name="Description" content="<?=SITE_DESCRIPTION ?>">
	
	<title><?=SITE_TITLE . ' - ' . __('Qchan Image Hosting') ?></title>
	
	<link rel="stylesheet" type="text/css" href="<?=get_url().theme_path() ?>style.css">
	<link rel="icon" type="image/png" href="<?=get_url() ?>site-img/favicon.png">
	
	<script type="application/javascript">
		ui_msg = <?=format_message() ?>;
		// Some parameters
		prop = {
			size_limit: <?=get_size_limit() ?>,
			upload_count: <?=get_upload_count() ?>,
			error_image: '<?=theme_path() ?>/images/error.png'
		};
	</script>
	
	<!--[if lt IE 9]> 
	<script>
		(function() {
		if (! 
		/*@cc_on!@*/
		0) return;
		var e = "abbr, article, aside, audio, canvas, datalist, details, dialog, eventsource, figure, footer, header, hgroup, mark, menu, meter, nav, output, progress, section, time, video".split(', ');
		var i= e.length;
		while (i=i-1){
			document.createElement(e[i])
		} 
		})();
	</script>
	<![endif]-->
	
	<style>
		#result_zone {background-image: url('<?=theme_path() ?>images/tip.<?=get_locale(true) ?>.png');}
	</style>
</head>

<body>
<header id="main_header">
	<div id="logo"></div>
	<?php if(!is_page()) { ?>
	<div id="upload_button_wrap">
		<button class="affirmative" id="upload_button" title="<?=__('Upload files') ?>"><span class="hide_mobile">&nbsp;<?=__('Upload') ?></span></button>
	</div>
	<?php } ?>
	<div id="lang_sel">
		<span class="hide_mobile"><?=__('Language') ?></span>
		<ul><?=get_langlist() ?></ul>
	</div>
</header>