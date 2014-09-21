<?php

/* Deny direct visit */
if(!defined('INDEX_RUN')) {
    header('HTTP/1.1 403 Forbidden');
    exit('This file must be loaded in flow.');
}

?>

<?php load_header(); ?>

<section id="main">
	<ul id="result_zone">
		<?=$results!=null ? format_results($results) : '' ?>
	</ul>

<?php
if(!$results) {
?>
	<!-- Button and message in center -->
	<div id="first_load">
		<a id="add" title="<?=__('Upload files') ?>"></a>
		<p><?=__('Upload files') ?></p>
		<p class="hide_mobile"><?=__('Drag and drop files here') ?></p>
		<p class="compatible hide_mobile"><?=__('Only works with IE 10+ and other mordern browser') ?></p>
	</div>
<?php } ?>

	<div id="upload_popup">
		<div id="pop_window">
			<header><?=__('Upload') ?></header>
			<section>
				<!-- Tabs -->
				<div id="method_change"><a id="normal"><?=__('Normal') ?></a><a id="url" class="noact"><?=__('URL') ?></a></div>
				<!-- Normal upload zone -->
				<div id="normal_zone" class="zone">
					<form id="normal_form" method="post" enctype="multipart/form-data">
						<button id="file_select" class="affirmative" type="button"><?=__('Select Files') ?></button>
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
					<button id="closepop" class="negative hide_mobile" type="button"><?=__('Close') ?></button>
					<button id="submit" class="affirmative" type="button"><?=__('Start Upload') ?></button>
				</div>
			</section>
		</div>
	</div>

</section>

<!-- File info section -->
<aside id="info_zone" class="hide">
</aside>

<script type="application/javascript" src="<?=get_url().theme_path() ?>js/zepto.min.js"></script>
<script type="application/javascript" src="<?=get_url().theme_path() ?>js/fx.js"></script>
<script type="application/javascript" src="<?=get_url().theme_path() ?>js/ui.js"></script>
<script type="application/javascript" src="<?=get_url().theme_path() ?>js/upload.js"></script>
<script type="application/javascript">
<?=$results!=null ? format_script($results) : '' ?>
</script>

<?php load_footer(); ?>