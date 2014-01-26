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
	<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=no">

	<meta name="Keywords" content="<?=SITE_KEYWORDS ?>">
	<meta name="Description" content="<?=SITE_DESCRIPTION ?>">
	
	<title><?=SITE_TITLE . ' - ' . __('Qchan Image Hosting') ?></title>

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
		* {font-family: verdana, arial, helvetica, sans-serif; padding: 0; margin: 0;}
		body {background-color: #f8f8f8;}
		img {border: none;}
		article,aside,dialog,footer,header,section,footer,nav,figure,menu {display:block;}
		#main {text-align: center;}
		#file_select {font-size: 1.5em; background-color: cornflowerblue; padding: 0.5em; color: white; cursor: default;}
		#main_header {background-color: mediumseagreen; text-align: center;}
		#file_list {visibility: hidden; width: 0; height: 0;}
		#file_submit{display: none; font-size: 1.5em; background-color: palevioletred; padding: 0.5em; color: white; cursor: default;}
		#langsel {background-color: royalblue; text-align: center; font-size: 1.5em; color:white; padding: 0.5em; cursor: default;}
		#langlist {display: none;}
		#langlist li {list-style: none; background-color: dodgerblue;}
		#langlist li a{text-decoration: none; color: white; font-size: 1.2em; padding: 0.5em; display: block; text-align: center;}
		#statement {font-size: 0.8em; text-align: center; color: #666;}
		#statement a{color: mediumseagreen;}
	</style>
</head>
<body>
	<header id="main_header">
		<a title="<?=SITE_TITLE ?>" href="<?=get_url() ?>"><img src="site-img/logo_m.png" alt="Logo"></a>
	</header>
	
	<section id="main">
		<form id="file_form" method="post" enctype="multipart/form-data">
			<p id="file_select"><?=__('Select Files') ?>&gt;<input type="file" id="file_list" name="files[]" accept="image/*" capture="filesystem camera" multiple></p>
			<input type="hidden" name="MAX_FILE_SIZE" value="<?=get_size_limit() ?>" >
			<input type="hidden" name="normal" value="upload" >
			<p id="file_submit"><?=__('Start Upload') ?></p>
		</form>
	</section>
	
	<footer id="main_footer">
		<div id="langsel"><?=__('Language') ?>&gt;</div>
		<ul id="langlist"><?=get_langlist() ?></ul>
		<div id="statement">
			<p>Copyright&copy; <?=SITE_TITLE ?></p>
			<p>Powered by <a href="http://github.com/" target="_blank">Qchan</a>, Image Hosting.</p>
		</div>
	</footer>
	
	<script>
		if(!langsel) {
			langsel=document.getElementById("langsel");
			langlist=document.getElementById("langlist");
			file_select=document.getElementById("file_select");
			file_list=document.getElementById("file_list");
			file_submit=document.getElementById("file_submit");
			file_form=document.getElementById("file_form");
		}
		
		langsel.onclick = function(){
			if(langlist.style.display!="block") {
				langlist.style.display="block";
			}else {
				langlist.style.display="none";
			}
		};
		
		file_select.addEventListener('click', function(e) {
			file_list.click();
		});
		
		file_list.onchange=function(e){
			if(this.files.length>0) {
				file_submit.style.display="block";
			}else {
				file_submit.style.display="none";
			}
		};
		
		file_submit.onclick=function(){
			file_form.submit();
		};
	</script>
</body>
</html>
