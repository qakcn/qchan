<?php
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
	setcookie('login_name', hash('sha256',MANAGE_NAME), time()+3000);
}

function list_dir(){
	$years=scandir(ABSPATH.'/'.UPLOAD_DIR);

	foreach($years as $year) {
		if($year != '.' && $year != '..' && $year != 'working' && $year != 'hash') {
			if(isset($_GET['year']) && $_GET['year']==$year) {
				echo '<li class="chosen">' . $year . '<ul>';
			}else {
				echo '<li>' . $year . '<ul>';
			}
			$months=scandir(ABSPATH.'/'.UPLOAD_DIR.'/'.$year);
			foreach($months as $month) {
				if($month != '.' && $month != '..') {
					if(isset($_GET['year']) && isset($_GET['month']) && $_GET['year']==$year && $_GET['month']==$month) {
						echo '<li class="chosen"><a href="?year=' . $year . '&month=' . $month . '">' . $month . '</a></li>';
					}else {
						echo '<li><a href="?year=' . $year . '&month=' . $month . '">' . $month . '</a></li>';
					}
				}
			}
			echo '</ul>';
		}
	}
}

function get_files() {
	if(isset($_GET['year']) && isset($_GET['month'])) {
		$year=$_GET['year'];
		$month=$_GET['month'];
		if(file_exists($path=ABSPATH.'/'.UPLOAD_DIR.'/'.$year.'/'.$month)) {
			$hash = md5($path);
			if(isset($_SESSION[$hash])) {
				return $_SESSION[$hash];
			}else {
				$files = array();
				$dh = opendir($path);
				while (($file = readdir($dh)) !== false) {
					if($file != '.' && $file != '..') {
						$files[$file] = filemtime("$path/$file");
					}
				}
				closedir($dh);
				arsort($files);
				$filem=array();
				$i=0;
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

function get_image_size($file){
	if(file_mime_type($file)=='image/svg+xml') {
		$svg=file_get_contents($file);
		if(preg_match('/<svg.+width="(\d+\.?\d*)(em|ex|px|in|cm|mm|pt|pc|%)?".+height="(\d+\.?\d*)(em|ex|px|in|cm|mm|pt|pc|%)?"/',$svg,$matches)) {
			$width=$matches[1];
			$height=$matches[3];
		}else if(preg_match('/<svg.+height="(\d+\.?\d*)(em|ex|px|in|cm|mm|pt|pc|%)?".+width="(\d+\.?\d*)(em|ex|px|in|cm|mm|pt|pc|%)?"/',$svg,$matches)) {
			$width=$matches[3];
			$height=$matches[1];
		}else {
			$width=200;
			$height=200;
		}
	}else {
		list($width, $height,,) = getimagesize($file);
	}
	$ratio=$width/$height;
	if($height>200) {
		$height=200;
		$width=$ratio*200;
	}
	if($width>1000) {
		$height=1000/$ratio;
		$width=1000;
	}
	return array($width, $height);
}

function format_filelist($filem,$page=1) {
	if(!$filem) return '';
	$perpage=200;
	$year=$_GET['year'];
	$month=$_GET['month'];
	$format = <<<FORMAT
<li class="scroll-load" id="n%d" draggable="true" style="width: %dpx; height: %dpx; margin-top: %dpx;" data-path="%s" data-thumb="%s"><div class="img" style="background-image: url(&quot;images/none.svg&quot;); background-size: %dpx %dpx; width: %dpx; height: %dpx;"><div><div class="select" style="padding-top: %dpx;"><p>%s</p></div></div></div></li>
FORMAT;
	$output='';
	for($i=0+($page-1)*$perpage;$i<$page*$perpage && $i<count($filem);$i++) {
		$filepath=UPLOAD_DIR.'/'.$year.'/'.$month.'/'.$filem[$i];
		$thumbpath=THUMB_DIR.'/'.$year.'/'.$month.'/'.$filem[$i];
		$status='select';
		if(!file_exists(ABSPATH.'/'.$filepath)) {
			continue;
		}else if(file_exists(ABSPATH.'/'.$thumbpath)) {
			list($width, $height) = get_image_size(ABSPATH.'/'.$thumbpath);
		}else {
			list($width, $height) = get_image_size(ABSPATH.'/'.$filepath);
			$thumbpath = $filepath;
		}
		$select=__('Selected');
		$output.=sprintf($format, $i, $width, $height, (205-$height), '../'.$filepath, '../'.$thumbpath, $width, $height, $width, $height, $height-30, $select);
	}
	return $output;
}

function format_script($filem,$page=1) {
	if(!$filem) return '';
	$perpage=200;
	$year=$_GET['year'];
	$month=$_GET['month'];
	$format = <<<FORMAT
if(!n%d) {
	n%d = document.getElementById('n%d');
}
n%d.onclick = toggleinfo();
n%d.ondblclick = openimage;
n%d.oncontextmenu = toggleinfo();
n%d.work = {
	name: '%s',
	path: '%s',
	thumb: '%s',
	qid: 'n%d'
};

FORMAT;
	$output = '';
	for($i=0+($page-1)*$perpage;$i<$page*$perpage && $i<count($filem);$i++) {
		$filepath=UPLOAD_DIR.'/'.$year.'/'.$month.'/'.$filem[$i];
		$thumbpath=THUMB_DIR.'/'.$year.'/'.$month.'/'.$filem[$i];
		if(!file_exists(ABSPATH.'/'.$filepath)) {
			continue;
		}
		$output .= sprintf($format, $i, $i, $i, $i, $i, $i, $i, $filem[$i], '../'.$filepath, '../'.$thumbpath, $i);
	}
	return $output;
}

?>