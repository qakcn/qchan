<?php

define('MANAGE_RUN',true);

/* Load Functions */
require '../engine.php';

require ABSPATH.'/manage/functions.php';

$logerror=-1;
if($logged=is_login()) {
	set_login();
	$filem=get_files();
}else if(isset($_POST['submit']) && $logged=check_login($logerror)) {
	set_login();
	$filem=get_files();
}else {
	$filem=false;
}

/* Management UI */
?>
<!DOCTYPE HTML>
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
				fail_load: '<?=__(' cannot load preview now. Waiting server response.') ?>',
				wrong_type: '<?=__(' is unsupported file type.') ?>',
				size_limit: '<?=__(' reaches the size limitation.') ?>',
				no_file: '<?=__(' cannot be retrieved by server now. Check if URL is invalid.') ?>',
				write_prohibited: '<?=__(' cannot write to server disk.') ?>',
				fail_duplicate: '<?=__(' cannot perform duplicate check.') ?>',
				php_upload_size_limit: '<?=__(' reaches size limit set in php.ini.') ?>',
				part_upload: '<?=__(' only part of were uploaded.') ?>',
				no_tmp: '<?=__(' there is no temporary directory on server.') ?>',
				fail_retry: '<?=__(' tried several times and all failed.') ?>'
			},
			err_detail: {
				no_file: '<?=__('File cannot be retrieved by server now. Perhaps remote server is unreachable, or file does not exist any more, or you just make a little mistake.') ?>',
				size_limit: '<?=__('Reaches file size limitation.') ?>',
				fail_load: '<?=__('The file preview cannot be loaded, Perhaps file is no more exist, or remote server is un reachable, or you just make a little mistake.') ?>',
				write_prohibited: '<?=__('File cannot write to upload directory on server. Ask webmaster to check permissions.') ?>',
				wrong_type: '<?=__('File type not support now. Communicate with author for more help.') ?>',
				fail_duplicate: '<?=__('Duplicate check is failed.') ?>',
				php_upload_size_limit: '<?=__('Reaches file size limitation in php.ini.') ?>',
				part_upload: '<?=__('Only parts of the file were uploaded. You can retry it.') ?>',
				no_tmp: '<?=__('Temporary directory on the server does not exist. Ask webmaster to check.') ?>',
				fail_retry: '<?=__('Try to upload several times and all of those were failed.') ?>'
			},
			status: {
				prepare: '<?=__('Preparing for uploading') ?>',
				waiting: '<?=__('Waiting for uploading') ?>',
				uploading: '<?=__('Uploading') ?>',
				success: '<?=__('Uploaded successfully') ?>',
				error: '<?=__('Something wrong') ?>',
				failed: '<?=__('Failed to upload') ?>',
				all_success: '<?=__('All selected files were uploaded successfully') ?>',
				part_success: '<?=__('Not all selected files were uploaded successfully, only uploaded ones showed below') ?>',
				all_failed: '<?=__('All selected files were failed to upload') ?>',
			},
			info: {
				selected: '<?=__('Selected') ?>',
				files_selected: '<?=__(' File(s) Selected') ?>',
				orig: '<?=__('Original File') ?>',
				html: '<?=__('HTML Code') ?>',
				html_with_thumb: '<?=__('HTML Code with thumbnail') ?>',
				bbcode: '<?=__('BBCode') ?>',
				bbcode_with_thumb: '<?=__('BBCode with thumbnail') ?>',
				thumb_tips: '<?=__('Click to view large version') ?>'
			}
		};
		prop = {
			size_limit: <?=get_size_limit() ?>,
			upload_count: <?=get_upload_count() ?>
		}
	</script>
	
	<!--[if lt IE9]> 
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

	
	</style>
</head>

<body>
<!-- Header -->
<header id="main_header">
	<ul id="header_wrap">
		<!-- Logo -->
		<li id="logo"><a href="<?=get_url() ?>../" title="<?=SITE_TITLE ?>"><img src="../site-img/logo.png" alt="Logo"></a></li>
		<!-- Click to upload -->
		<li class="menu"><?=__('Management') ?><ul></ul></li>
		<!-- Language select -->
		<li class="menu" id="lang_set" title="<?=__('Select display language') ?>"><img src="images/WorldMap.svg" width="36" height="18">&nbsp;<?=__('Language') ?><ul><?=get_langlist() ?><div class="clear"></div></ul></li>
	</ul>
</header>

<section id="main">
<?php
if($logged) {
?>
	<ul id="dirlist"><?php list_dir(); ?></ul>
	<ul id="result_zone">
		<?=format_filelist($filem) ?>
		<div class="clear"></div>
	</ul>
	
	<ul id="message_zone">
	</ul>
<?php
}else {
?>
	<div id="first_load">
		<form id="login_form" method="post">
			<p><?=__('Please Login') ?></p>
			<p><label for="login" <?=$logerror%2==1 ? 'style="color:red;"' : '' ?>><?=__('Admin Name') ?></label><input type="text" id="login" name="login" <?=$logerror%2!=1&&isset($_POST['login']) ? ('value="'.$_POST['login'].'"') : '' ?> <?=$logerror%2==1 ? 'style="border-color:red;"' : '' ?>></p>
			<p><label for="password" <?=$logerror%2==0 ? 'style="color:red;"' : '' ?>><?=__('Password') ?></label><input type="password" id="password" name="password" <?=$logerror%2==0 ? 'style="border-color:red;"' : '' ?>></p>
<?php
switch($logerror) {
	case 1:
		echo '<p style="color:red;font-size: 20px;">' . __('Admin Name is empty!') . '</p>';
		break;
	case 2:
		echo '<p style="color:red;font-size: 20px;">' . __('Password is empty!') . '</p>';
		break;
	case 3:
		echo '<p style="color:red;font-size: 20px;">' . __('Admin Name is incorrect!') . '</p>';
		break;
	case 4:
		echo '<p style="color:red;font-size: 20px;">' . __('Password is incorrect!') . '</p>';
		break;
}
?>
			<p><input type="submit" name="submit" value="<?=__('Login') ?>"></p>
		</form>
	</div>
<?php } ?>
</section>

<!-- File info section -->
<aside id="info_zone" class="hide">
	<h1 id="namep"></h1>
	<p id="buttonp"><button id="delete_image"><?=__('Delete') ?></button><button id="view_image"><?=__('View') ?></button></p>
</aside>

<script type="application/javascript" src="ui.js"></script>
<script type="application/javascript">
<?=format_script($filem) ?>
</script>

<!-- Footer -->
<footer id="main_footer">
<p><?=__('This site is powered by Qchan, a light-weight image hosting program. Version: ') . QCHAN_VER ?>, <a target="_blank" href="http://github.com/qakcn/qchan">http://github.com/qakcn/qchan</a></p>
</footer>

</body>
</html>