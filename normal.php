<?php

/* Deny direct visit */
if(!defined('INDEX_RUN')) {
	exit('This file must be loaded in flow.');
}
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	
	<meta name="Keywords" content="<?=SITE_KEYWORDS ?>">
	<meta name="Description" content="<?=SITE_DESCRIPTION ?>">
	
	<title><?=SITE_TITLE . ' - ' . __('Qchan Image Hosting') ?></title>
	
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="application/javascript">
		/* Message for UI */
		ui_msg = {
			err: {
				illegal_url: '<?=__(' is not an acceptable URL.') ?>',
				fail_load: '<?=__(' cannot load, maybe not a image or network error. Waiting server response.') ?>',
				wrong_type: '<?=__(' is unsupported file type.') ?>',
				size_limit: '<?=__(' reaches the size limitation.') ?>'
				},
			statu: {
				prepare: '<?=__('Preparing for uploading') ?>',
				waiting: '<?=__('Waiting for uploading') ?>',
				uploading: '<?=__('Uploading') ?>',
				success: '<?=__('Uploaded successfully') ?>',
				error: '<?=__('Something wrong') ?>',
				failed: '<?=__('Failed to upload') ?>'
			},
			info: {
				selected: '<?=__('Selected') ?>',
				files_selected: '<?=__(' files selected.') ?>'
			}
		};
		prop = {
			size_limit: <?=get_sizelimit() ?>
		}
	</script>
</head>

<body>
<!-- Header -->
<header id="main_header">
	<ul id="header_wrap">
		<!-- Logo -->
		<li id="logo"><a href="<?=get_url() ?>" title="<?=SITE_TITLE ?>"><img src="site-img/logo.png" alt="Logo"></a></li>
		<!-- Click to upload -->
		<li id="upload" title="<?=__('Upload files') ?>"><?=__('Upload') ?></li>
		<!-- Language select -->
		<li id="lang_set" title="<?=__('Select display language') ?>"><?=__('Language') ?><ul><?=get_langlist() ?><div class="clear"></div></ul></li>
	</ul>
</header>

<!-- Main section -->
<section id="main">
	<ul id="result_zone">
	<div class="clear"></div>
	</ul>

	
	<ul id="message_zone">
	</ul>

	<!-- Button and message in center -->
	<div id="first_load">
		<a id="add" title="<?=__('Upload files') ?>"></a>
		<p><?=__('Upload files') ?></p>
		<p><?=__('Drag and drop files here') ?></p>
	</div>
	
	<!-- Upload pop-up -->
	<div id="upload_popup">
		<div id="pop_window">
			<header><?=__('Upload') ?></header>
			<section>
				<!-- Tabs -->
				<div id="method_change"><a id="normal"><?=__('Normal') ?></a><a id="url" class="noact"><?=__('URL') ?></a></div>
				<!-- Normal upload zone -->
				<div id="normal_zone" class="zone">
					<form id="normal_form" method="post" enctype="multipart/form-data">
						<button id="file_select" type="button"><?=__('Select Files') ?></button>
						<input type="file" id="file_list" name="files[]" accept="image/*" capture="filesystem camera" multiple>
					</form>
				</div>
				<!-- URL upload zone -->
				<div id="url_zone" class="zone">
					<textarea id="url_list" placeholder="<?=__('Please put URLs here, one URL per line, with leading \'http://\'') ?>">
					</textarea>
				</div>
				<!-- Submit and Close buttons -->
				<div id="submit_zone">
					<button id="closepop" type="button"><?=__('Close') ?></button>
					<button id="submit" type="button"><?=__('Start Upload') ?></button>
				</div>
			</section>
		</div>
	</div>
</section>

<!-- File info section -->
<aside id="info_zone" class="hide">
</aside>

<!-- Footer -->
<footer>
</footer>

<script type="application/javascript" src="ui.js"></script>
<script type="application/javascript" src="upload.js"></script>
</body>
</html>