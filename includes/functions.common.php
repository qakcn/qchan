<?php

function get_user_setting($configname) {
	if(isset($_POST[$configname])) {
		return $_POST[$configname];
	}else {
		return defined($configname) ? constant($configname) : false;
	}
}



function get_url($cdn=false){
	if($cdn && defined('CDN_LIST')) {
		$cdnlist = explode(',',CDN_LIST);
		$rand_key = array_rand($cdnlist);
		return 'http://' . $cdnlist[$rand_key] . '/';
	}else {
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
			return preg_replace('/(.*\/)(.*?\.php|.*?\?.+)$/', '\1', 'https://'.$_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT']==443 ? '' : $_SERVER['SERVER_PORT']).$_SERVER['REQUEST_URI']);
		}else {
			return preg_replace('/(.*\/)(.*?\.php|.*?\?.+)$/', '\1', 'http://'.$_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT']==80 ? '' : $_SERVER['SERVER_PORT']).$_SERVER['REQUEST_URI']);
		}
	}
}

function check_apikey() {
	if(isset($_SERVER['HTTP_REFERER']) && preg_match('/'.str_replace('.', '\.', $_SERVER['SERVER_NAME']).'/', $_SERVER['HTTP_REFERER'])) {
		return true;
	}else if(isset($_GET['apikey']) && preg_match('/[0-9a-f]{64}/',$_GET['apikey'])) {
		if(file_exists('apikey/' . $_GET['apikey'] . '.php')) {
			require 'apikey/' . $_GET['apikey'] . '.php';
			if($apikey['type']=='web' && preg_match('/'.str_replace('.', '\.', $apikey['referer']).'/', $_SERVER['HTTP_REFERER'])) {
				return true;
			}else if($apikey['type']=='app') {
				return true;
			}else {
				return false;
			}
		}else {
			return false;
		}
	}else {
		return false;
	}
}

function return_bytes($val) {
	$val = trim($val);
	$last = strtolower($val[strlen($val)-1]);
	switch($last) {
		case 't':
			$val *= 1024;
		case 'g':
			$val *= 1024;
		case 'm':
			$val *= 1024;
		case 'k':
			$val *= 1024;
	}
	return $val;
}

function get_size_limit() {
	$postsize = return_bytes(ini_get('post_max_size'));
	$filesize = return_bytes(ini_get('upload_max_filesize'));
	$siteset = return_bytes(defined(SIZE_LIMIT) ? SIZE_LIMIT : '1T');
	
	return min($postsize, $filesize, $siteset);
}

function get_upload_count(){
	return defined('UPLOAD_COUNT') ? UPLOAD_COUNT : 3;
}

// Escape special character
function escape_special_char($name) {
	return str_replace(array('#','?','=','&','/','\\',';','<','>','[',']','%','@','-'), '_', $name);
}



// Check if file is duplicate
function is_duplicate($file) {
	if(defined('DUPLICATE_FILE_CHECK') && DUPLICATE_FILE_CHECK && false) {
		$hash = hash_file('sha256', $file);
		$wd = ABSPATH.'/'.UPLOAD_DIR . '/hash';
		for($i=0;$i<5;$i++) {
			$wd .= '/' . substr($hash, $i*2+0, 2);
		}
		$wd .= '/' . substr($hash, 10, 2);
		if(file_exists($wd)) {
			$re = file_get_contents($wd);
			if(preg_match('/^'.$hash.';(.+?);(.+?);(.+?)$/',$re,$match)) {
				$name = $match[1];
				$path = $match[2];
				$thumb = $match[3];
				
				if($thumb!='none') {
					list($width, $height) = getimagesize($thumb);
				}else {
					$width = 200;
					$height = 1000;
					list($width_orig, $height_orig) = getimagesize($path);
					if($height_orig <= $height && $width_orig <= $width) {
						$width = $width_orig;
						$height = $height_orig;
					}else{
						$ratio_orig = $width_orig/$height_orig;
						if ($width/$height > $ratio_orig) {
							$width = $height*$ratio_orig;
						}else {
							$height = $width/$ratio_orig;
						}
					}
				}
				return array('name' => $name, 'path' => $path, 'thumb' => $thumb, 'width' => $width, 'height' => $height);
			}else {
				return false;
			}
		}else {
			return false;
		}
	}else {
		return false;
	}
}

// Generate hash file for duplicate check
function duplicate_hash($name, $path, $thumb) {
	if(defined('DUPLICATE_FILE_CHECK') && DUPLICATE_FILE_CHECK && false) {
		$hash = hash_file('sha256', ABSPATH.'/'.$path);
		$wd = ABSPATH.'/'.UPLOAD_DIR . '/hash';
		for($i=0;$i<5;$i++) {
			$wd .= '/' . substr($hash, $i*2+0, 2);
			if(!file_exists($wd)) {
				mkdir($wd);
			}
		}
		$wd .= '/' . substr($hash, 10, 2);
		if(file_put_contents($wd, $hash . ';' . $name . ';' . $path . ';' . ($thumb['generated'] ? $thumb['path'] : 'none') . "\n", FILE_APPEND | LOCK_EX) !== false) {
			return true;
		}else {
			return false;
		}
	}else {
		return true;
	}
}
?>