<?php

define('MANAGE_RUN',true);

/* Load Functions */
require 'engine.php';

/* Management Functions */
function is_login() {
	return (isset($_COOKIE['login_name']) && $_COOKIE['login_name']==hash('sha256',MANAGE_NAME));
}

function check_login(&$error) {
	if(!isset($_POST['login'])) {
		$error=1; //No Login Name
		return false;
	}else if(!isset($_POST['password'])) {
		$error=2; //No Password
		return false;
	}else if($_POST['login']!=MANAGE_NAME) {
		$error=3; // Login Name Incorrect
		return false;
	}else if($_POST['password']!=MANAGE_PASSWORD) {
		$error=4; //Password Incorrect
		return false;
	}else {
		return true;
	}
}

function set_login() {
	setcookie('login_name', hash('sha256',MANAGE_NAME), time()+300);
}

function list_dir(){
	$years=scandir(UPLOAD_DIR);

	foreach($years as $year) {
		if($year != '.' && $year != '..' && $year != 'working' && $year != 'hash') {
			if(isset($_GET['year']) && $_GET['year']==$year) {
				echo '<li class="selected">' . $year . '<ul>';
			}else {
				echo '<li>' . $year . '<ul>';
			}
			$months=scandir(UPLOAD_DIR.'/'.$year);
			foreach($months as $month) {
				if($month != '.' && $month != '..') {
					if(isset($_GET['year']) && isset($_GET['month']) && $_GET['year']==$year && $_GET['month']==$month) {
						echo '<li class="selected"><a href="manage.php?year=' . $year . '&month=' . $month . '">' . $month . '</a></li>';
					}else {
						echo '<li><a href="manage.php?year=' . $year . '&month=' . $month . '">' . $month . '</a></li>';
					}
				}
			}
			echo '</ul>';
		}
	}
}

function get_files() {
	if(isset($_POST['month']) && isset($_POST['month'])) {
		if(file_exists($path=UPLOAD_DIR.'/'.$year.'/'.$month)) {
			$hash = md5($path);
			if(isset($_SESSION[$hash])) {
				return $_SESSION[$hash];
			}else {
				$files = array();
				$dh = opendir($path);
				while (($file = readdir($dh)) !== false) {
					if($file != '.' && $file != '..') {
						$filepath = "$path/$file";
					$files[$file] = filemtime($filepath);
					}
				}
				closedir($dh);
				arsort($files);
				$filem=array();$i=0;
				foreach($files as $key => $value) {
					$filem[$i++] = $key;
				}
				$_SESSION[$hash]=$filem;
				return $filem;
			}
		}else {
			return false;
		}
	}else {
		return false;
	}
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
$logerror=-1;
if($logged=is_login()) {
	set_login();
	$result=get_files();
}else if(isset($_POST['submit']) && $logged=check_login($logerror)) {
	set_login();
	$result=get_files();
}else {
	$result=false;
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
		article,aside,dialog,footer,header,section,footer,nav,figure,menu {display:block;}
		#dirlist,#dirlist ul {list-style:none; font-size: 16px;}
		#dirlist {float: left; overflow: auto; top: 10px; bottom: 10px; position: absolute; width: 150px;}
		#dirlist > li {width: 100px; text-align: center; background-color: darkgray; padding: 10px; margin: 2px; line-height: 150%;}
		#dirlist > li:hover,#dirlist > li.selected {background-color: palevioletred;}
		#dirlist > li ul li a {text-decoration: none; color: black; display: block; padding: 5px; margin: 2px; background-color: lightgrey;}
		#dirlist > li ul li.selected a,#dirlist > li ul li a:hover{background-color:dodgerblue;}
		#result_zone {margin-left:150px; }
		#first_load {margin-top: 50px;}
		#login_form p {margin-top: 5px; color: #666;}
		#login_form label {display: inline-block; width: 150px; text-align: right; font-size: 20px; color: #aaa;}
		#login_form input[type=password],#login_form input[type=text] {border: none; border-bottom: 2px solid #aaa; background-color: transparent; width: 250px;padding: 2px; font-size: 20px; color: #666;}
		#login_form input[type=submit] {padding: 10px; width: 200px; background-color: cornflowerblue; border: none; color: white; font-size: 20px;}
		#login_form input[type=submit]:hover {background-color: royalblue;}
	</style>
</head>

<body>
<!-- Header -->
<header id="main_header">
	<ul id="header_wrap">
		<!-- Logo -->
		<li id="logo"><a href="<?=get_url() ?>" title="<?=SITE_TITLE ?>"><img src="site-img/logo.png" alt="Logo"></a></li>
		<!-- Click to upload -->
		<li class="menu"><?=__('Management') ?><ul></ul></li>
		<!-- Language select -->
		<li class="menu" id="lang_set" title="<?=__('Select display language') ?>"><img src="site-img/WorldMap.svg" width="36" height="18">&nbsp;<?=__('Language') ?><ul><?=get_langlist() ?><div class="clear"></div></ul></li>
	</ul>
</header>

<section id="main">
<?php
if($logged) {
?>
	<ul id="dirlist"><?=list_dir() ?></ul>
	<ul id="result_zone">
		<?//=$results ? format_results($results) : '' ?>
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
</aside>

<!-- Footer -->
<footer>
</footer>

</body>
</html>