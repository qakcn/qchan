<?php

define('MANAGE_RUN',true);

session_start();

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

	$allpage = ceil(count($filem)/200);
	if(isset($_GET['page']) && is_numeric($_GET['page'])) {
		$page = floor($_GET['page']);
	}else {
		$page = 1;
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
	
	<title><?=__('Management') ?> - <?=SITE_TITLE ?> - <?=__('Qchan Image Hosting') ?></title>
	
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" type="image/png" href="<?=get_url() ?>../site-img/favicon.png">
	
	<script type="application/javascript">
		/* Message for UI */
		files_selected = '<?=__(' Files Selected') ?>';
	</script>
	
	<!--[if lt IE　9]> 
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
</head>

<body>
<!-- Header -->
<header id="main_header">
	<div id="logo"></div>
	<div id="lang_sel">
		<span class="hide_mobile"><?=__('Language') ?></span>
		<ul><?=get_langlist() ?></ul>
	</div>
</header>

<section id="main">
<?php
if($logged) {
?>
	<ul id="dirlist"><?php list_dir(); ?></ul>
	<ul id="result_zone">
		<?=format_filelist($filem,$page) ?>
	</ul>
	
<?php
}else {
?>
	<div id="first_load">
		<form id="login_form" method="post">
			<p><?=__('Please Login') ?></p>
			<p><input placeholder="<?=__('Admin Name') ?>" type="text" id="login" name="login" <?=$logerror%2!=1&&isset($_POST['login']) ? ('value="'.$_POST['login'].'"') : '' ?> <?=$logerror%2==1 ? 'style="border-color:red;"' : '' ?>></p>
			<p><input placeholder="<?=__('Password') ?>" type="password" id="password" name="password" <?=$logerror%2==0 ? 'style="border-color:red;"' : '' ?>></p>
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
			<p><button type="submit" name="submit" class="affirmative"><?=__('Login') ?></button></p>
		</form>
	</div>
<?php } ?>
</section>
<?php if(isset($_GET['year']) && isset($_GET['month']) && $logged) { ?>
<aside id="page_zone">
	<button id="prev_page" <?=$page==1?'disabled':'' ?>><?=__('Previous') ?></button>
	<?=__('Page: ') ?>
	<select id="page_select" data-url="?year=<?=$_GET['year'] ?>&month=<?=$_GET['month'] ?>&page=">
	<?php
		for ($i=1;$i<=$allpage;$i++) {
			if($page == $i) {
				echo '<option value="'.$i.'" selected>'.$i.'</option>';
			}else {
				echo '<option value="'.$i.'">'.$i.'</option>';
			}
		}
	?>
	</select>
	<button id="next_page" <?=$page==$allpage?'disabled':'' ?>><?=__('Next') ?></button>
</aside>
<?php } ?>

<!-- File info section -->
<aside id="info_zone" class="hide">
	<h1 id="namep"></h1>
	<p id="buttonp"><button id="delete" class="negative"><?=__('Delete') ?></button><button id="view" class="affirmative"><?=__('View') ?></button></p>
</aside>

<script type="application/javascript" src="js/zepto.min.js"></script>
<script type="application/javascript" src="js/ui.js"></script>
<script type="application/javascript">
<?=format_script($filem,$page) ?>
</script>

<!-- Footer -->
<footer id="main_footer">
<p><?=__('This site is powered by <a target="_blank" href="http://github.com/qakcn/qchan">Qchan %s</a>', QCHAN_VER) ?></p>
</footer>

</body>
</html>