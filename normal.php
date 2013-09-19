<?php

/* Deny direct visit */
if(!defined('INDEX_RUN')) {
	exit('This file must be loaded in flow.');
}

function format_results($results) {
	$id = 0;
	$format = <<<FORMAT
<li id="n%d" draggable="true" style="width: %dpx; height: %dpx; margin-top: %dpx;" data-path="%s" data-thumb="%s"><div class="img" style="background-image: url(&quot;%s&quot;); background-size: %dpx %dpx; width: %dpx; height: %dpx;"><div class="progress" style="width: %dpx; height: %dpx; background-position: %dpx center;"><div class="select" style="padding-top: %dpx;"><p>Selected</p></div></div></div></li>
FORMAT;
	$output='';
	foreach($results as $result) {
		if(isset($result['width'])) {
			$width = $result['width'];
			$height = $result['height'];
		}else {
			$width = 200;
			$height = 200;
		}
		$output .= sprintf($format, $id++, $width, $height, (200-$height)/2, $result['path'], $result['thumb'], $result['path'], $width, $height, $width, $height, $width, $height, $width, $height-30);
	}
$output.=<<<SCRIPT
<script type="application/javascript">
var first_load = document.createElement('div'),
add = document.createElement('a');
</script>
SCRIPT;
	return $output;
}

function format_script($results) {
	$id = 0;
	$format = <<<FORMAT
if(!n%d) {
	n%d = document.getElementById('n%d');
}
n%d.onclick = toggleinfo();
n%d.oncontextmenu = toggleinfo();
n%d.work = {
	name: '%s',
	path: '%s',
	thumb: '%s',
	status: 'success',
	qid: 'n%d'
};
FORMAT;
	$output = '';
	foreach($results as $result) {
		$output .= sprintf($format, $id, $id, $id, $id, $id, $id, $result['name'], $result['path'], $result['thumb'], $id++);
	}
	return $output;
}

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
				fail_load: '<?=__(' cannot be loaded now. Waiting server response.') ?>',
				wrong_type: '<?=__(' is unsupported file type.') ?>',
				size_limit: '<?=__(' reaches the size limitation.') ?>',
				no_file: '<?=__(' cannot be retrieved by server now. Check if URL is invalid.') ?>',
				write_prohibited: '<?=__(' cannot write to server disk.') ?>',
				fail_duplicate: '<?=__(' cannot perform duplicate check ') ?>',
				php_upload_size_limit: '<?=__(' reaches size limit set in php.ini.') ?>',
				part_upload: '<?=__(' only part of were uploaded.') ?>',
				no_tmp: '<?=__(' there is no temporary directory.') ?>',
				fail_retry: '<?=__(' tried several times and all failed.') ?>'
			},
			err_detail: {
				no_file: '<?=__('File cannot be retrieved by server now. Perhaps remote server is unreachable, or file does not exist any more, or you just make a little mistake.') ?>',
				size_limit: '<?=__('Reaches file size limitation. Ask webmaster to check "post_max_size" and "upload_max_filesize" in php.ini, and "SIZE_LIMIT" in Qchan config.php.') ?>',
				fail_load: '<?=__('The file thumbnail cannot be loaded, Perhaps file is no more exist, or remote server is un reachable, or you just make a little mistake.') ?>',
				write_prohibited: '<?=__('File cannot write to upload directory. Ask webmaster to check permissions.') ?>',
				wrong_type: '<?=__('File type not support now. Communicate with author for more help.') ?>',
				fail_duplicate: '<?=__('Duplicate check is failed.') ?>',
				php_upload_size_limit: '<?=__('') ?>',
				part_upload: '<?=__('Only parts of the file were uploaded. You can etry it.') ?>',
				no_tmp: '<?=__('Temporary directory on the server does not exist. Ask webmaster to check.') ?>',
				fail_retry: '<?=__('Try to upload several times and all of those were failed.') ?>'
			},
			status: {
				prepare: '<?=__('Preparing for uploading') ?>',
				waiting: '<?=__('Waiting for uploading') ?>',
				uploading: '<?=__('Uploading') ?>',
				success: '<?=__('Uploaded successfully') ?>',
				error: '<?=__('Something wrong') ?>',
				failed: '<?=__('Failed to upload') ?>'
			},
			info: {
				selected: '<?=__('Selected') ?>',
				files_selected: '<?=__(' files selected.') ?>',
				orig: '<?=__('Original File') ?>',
				html: '<?=__('HTML Code') ?>',
				html_with_thumb: '<?=__('HTML Code with thumbnail') ?>',
				bbcode: '<?=__('BBCode') ?>',
				bbcode_with_thumb: '<?=__('BBCode with thumbnail') ?>'
			}
		};
		prop = {
			size_limit: <?=get_size_limit() ?>,
			upload_count: <?=get_upload_count() ?>
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
		<?=$results ? format_results($results) : '' ?>
		<div class="clear"></div>
	</ul>

	
	<ul id="message_zone">
	</ul>
<?php
if($results) {
}else {
?>
	<!-- Button and message in center -->
	<div id="first_load">
		<a id="add" title="<?=__('Upload files') ?>"></a>
		<p><?=__('Upload files') ?></p>
		<p><?=__('Drag and drop files here') ?></p>
	</div>
<?php } ?>

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
						<input type="hidden" name="MAX_FILE_SIZE" value="<?=get_size_limit() ?>" >
						<input type="hidden" name="normal" value="upload" >
					</form>
					<div id="file_review">
					</div>
				</div>
				<!-- URL upload zone -->
				<div id="url_zone" class="zone">
					<textarea id="url_list" placeholder="<?=__('Please put URLs here, one URL per line, with leading \'http://\'') ?>"></textarea>
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
<script type="application/javascript">
<?=$results ? format_script($results) : '' ?>
</script>
</body>
</html>