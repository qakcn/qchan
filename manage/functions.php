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
				echo '<li class="year chosen">' . $year . '<ul>';
			}else {
				echo '<li class="year">' . $year . '<ul>';
			}
			$months=scandir(ABSPATH.'/'.UPLOAD_DIR.'/'.$year);
			foreach($months as $month) {
				if($month != '.' && $month != '..') {
					if(isset($_GET['year']) && isset($_GET['month']) && $_GET['year']==$year && $_GET['month']==$month) {
						echo '<li class="month chosen"><a href="?year=' . $year . '&month=' . $month . '">' . $month . '</a></li>';
					}else {
						echo '<li class="month"><a href="?year=' . $year . '&month=' . $month . '">' . $month . '</a></li>';
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
    $geterror=false;
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
		$ratio=$width/$height;
		if($ratio < 0.33 || $ratio >= 1 && $ratio <= 3) {
			$width = 200;
			$height = $width/$ratio;
		}else if ($ratio >= 0.33 && $ratio < 1 || $ratio > 3) {
			$height = 200;
			$width = $height*$ratio;
		}
	}else {
	    if(!$imgInfo = @getimagesize($file)) {
	        list($width, $height) = array(200,200);
	        $geterror=true;
	    }else {
		    list($width, $height,,) = $imgInfo;
		}
	}
	
	return array($width, $height, $geterror);
}

function format_filelist($filem,$page=1) {
	if(!$filem) return '';
	$perpage=200;
	$year=$_GET['year'];
	$month=$_GET['month'];
	$format = <<<FORMAT
<li id="n%d" %s draggable="true" style="width: %dpx; height: %dpx;" data-thumb="%s"><div class="img" style="%s"><div class="name"><p>%s</p></div><div class="infotag"><span class="longtag" title="%s">LONG</span><span class="tinytag" title="%s">TINY</span></div><div class="select"><p>\xee\x98\x81</p></div></div></li>
FORMAT;
	$output='';
	$tinytag = __('This image is tiny and enlarged');
	$longtag = __('This image is long and will be auto scrolled when mouse over');
	for($i=($page-1)*$perpage;$i<$page*$perpage && $i<count($filem);$i++) {
		$filepath=UPLOAD_DIR.'/'.$year.'/'.$month.'/'.$filem[$i];
		$thumbpath=THUMB_DIR.'/'.$year.'/'.$month.'/'.$filem[$i];
		$status='select';
		$exclass = 'class="scroll-load"';
		$imgstyle = '';
		if(!file_exists(ABSPATH.'/'.$filepath)) {
			continue;
		}else if(file_exists(ABSPATH.'/'.$thumbpath)) {
			list($width, $height, $geterror) = get_image_size(ABSPATH.'/'.$thumbpath);
		}else {
			list($width, $height, $geterror) = get_image_size(ABSPATH.'/'.$filepath);
			$thumbpath = $filepath;
		}
		$thumbpath = '/'.$thumbpath;
		if($geterror) {
			$thumbpath = 'images/error.png';
			$width = $height = 200;
		}else {
			$ratio = $width/$height;
			$extiny = ($width < 67 || $height < 67);
			$exlong = ($ratio > 3 || $ratio < 0.33 );
			if($extiny && $exlong) {
				if($ratio < 1) {
					$width = 67;
					$height = 200;
					$exclass='data-direction="ttb" ';
					$imgstyle = 'background-size: 100% auto;';
				}else {
					$height = 67;
					$width = 200;
					$exclass='data-direction="ltr" ';
					$imgstyle = 'background-size: auto 100%;';
				}
				$exclass .= 'class="tiny long scroll-load"';
			}else if($extiny && !$exlong) {
				if($ratio < 1) {
					$width = 67;
					$height = $width/$ratio;
				}else {
					$height = 67;
					$width = $height * $ratio;
				}
				$exclass = 'class="tiny scroll-load"';
			}else if(!$extiny && $exlong) {
				if($ratio < 1) {
					$height = 200;
					$exclass='data-direction="ttb" ';
					$imgstyle = 'background-size: 100% auto;';
				}else {
					$width = 200;
					$exclass='data-direction="ltr" ';
					$imgstyle = 'background-size: auto 100%;';
				}
				$exclass .= 'class="long scroll-load"';
			}
		}
		$output .= sprintf($format, $i, $exclass, $width, $height, htmlspecialchars($thumbpath), $imgstyle, $filem[$i], $longtag, $tinytag);
	}
	return $output;
}

function format_script($filem,$page=1) {
	if(!$filem) return '';
	$perpage=200;
	$year=$_GET['year'];
	$month=$_GET['month'];
	$format = <<<FORMAT
$('#n%d').on('click', toggleinfo).on('contextmenu', toggleinfo).prop('work', %s).on('mouseenter', movelongstart).on('mouseleave', movelongend);
FORMAT;
	$output = '';
	for($i=($page-1)*$perpage;$i<$page*$perpage && $i<count($filem);$i++) {
		$filepath=UPLOAD_DIR.'/'.$year.'/'.$month.'/'.$filem[$i];
		$thumbpath=THUMB_DIR.'/'.$year.'/'.$month.'/'.$filem[$i];
		if(!file_exists(ABSPATH.'/'.$filepath)) {
			continue;
		}
		$work = json_encode(array('name'=>$filem[$i], 'path'=>'../'.$filepath, 'thumb' => '../'.$thumbpath, 'qid' => 'n'.$i));
		$output .= sprintf($format, $i, $work);
	}
	return $output;
}

function delete_files($works) {
	$result = array();
	foreach($works as $work) {
		$hash = hash_file('sha256', $work['path']);
		$hashfile = ABSPATH.'/'.UPLOAD_DIR .'/hash/'.$hash;
		$su = file_exists($work['path']) && unlink($work['path']);
		$st = file_exists($work['thumb']) && unlink($work['thumb']);
		$sh = file_exists($work['thumb']) && unlink($hashfile);
		if($su && $st) {
			$result[$work['qid']]='deleted';
		}else if($su && !$st) {
			$result[$work['qid']]='thumbdelfail';
		}else if(!$su && $st) {
			$result[$work['qid']]='origdelfail';
		}else {
			$result[$work['qid']]='failed';
		}
	}
	return $result;
}

