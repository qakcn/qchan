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

function format_filelist($filem,$page=1) {
	if(!$filem) return '';
	$perpage=100;
	$year=$_GET['year'];
	$month=$_GET['month'];
	$format = <<<FORMAT
<li id="n%d" draggable="true" style="width: %dpx; height: %dpx; margin-top: %dpx;" data-path="%s" data-thumb="%s"><div class="img" style="background-image: url(&quot;%s&quot;); background-size: %dpx %dpx; width: %dpx; height: %dpx;"><div><div class="select" style="padding-top: %dpx;"><p>Selected</p></div></div></div></li>
FORMAT;
	$output='';
	for($i=0+($page-1)*$perpage;$i<$page*$perpage && $i<count($filem);$i++) {
		$filepath=UPLOAD_DIR.'/'.$year.'/'.$month.'/'.$filem[$i];
		$thumbpath=THUMB_DIR.'/'.$year.'/'.$month.'/'.$filem[$i];
		if(file_exists(ABSPATH.'/'.$thumbpath)) {
			list($width, $height,,) = getimagesize(ABSPATH.'/'.$thumbpath);
		}else {
			list($width, $height,,) = getimagesize(ABSPATH.'/'.$filepath);
		}

		$width=$width>1000 ? 1000 : $width;
		$height=$height>200 ? 200 : $height;
		$output.=sprintf($format, $i, $width, $height, (200-$height)/2, '../'.$filepath, '../'.$thumbpath, '../'.$filepath, $width, $height, $width, $height, $height-30);
	}
	return $output;
}

function format_script($filem,$page=1) {
	if(!$filem) return '';
	$perpage=100;
	$year=$_GET['year'];
	$month=$_GET['month'];
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
	qid: 'n%d'
};

FORMAT;
	$output = '';
	for($i=0+($page-1)*$perpage;$i<$page*$perpage && $i<count($filem);$i++) {
		$filepath=UPLOAD_DIR.'/'.$year.'/'.$month.'/'.$filem[$i];
		$thumbpath=THUMB_DIR.'/'.$year.'/'.$month.'/'.$filem[$i];
		$output .= sprintf($format, $i, $i, $i, $i, $i, $i, $filem[$i], '../'.$filepath, '../'.$thumbpath, $i);
	}
	return $output;
}

?>