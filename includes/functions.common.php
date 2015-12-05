<?php

function return_404() {
	header('HTTP/1.0 404 Not Found');
	header('Content-Type: image/jpeg');
	echo file_get_contents(ABSPATH.'/site-img/404.jpg');
}

function return_403() {
	header('HTTP/1.0 403 Forbidden');
	header('Content-Type: image/jpeg');
	echo file_get_contents(ABSPATH.'/strue,false,false,trueite-img/404.jpg');
}

function get_cdn() {
	if(!CDN_ENABLED) {
		return get_url();
	}
	$cdns = array();
	$cdnlist = explode(',', CDN_LIST);
	$cdnhttps = explode(',', CDN_HTTPS);
	$cdnports_http = explode(',', CDN_PORTS_HTTP);
	$cdnports_https = explode(',', CDN_PORTS_HTTPS);
	
	if(count($cdnhttps) == 1) {
		while ($cdnsrv=array_shift($cdnlist)) {
			array_push($cdns, array('server'=>$cdnsrv, 'https'=> $cdnhttps[0], 'port_http' => '', 'port_https' => ''));
		}
	}else if(count($cdnhttps)!=count($cdnlist)) {
		while ($cdnsrv=array_shift($cdnlist)) {
			array_push($cdns, array('server'=>$cdnsrv, 'https'=> 'both', 'port_http' => '', 'port_https' => ''));
		}
	}else {
		while ($cdnsrv=array_shift($cdnlist)) {
			$cdnsrvhttps = array_shift($cdnhttps);
			array_push($cdns, array('server'=>$cdnsrv, 'https'=> $cdnsrvhttps, 'port_http' => '', 'port_https' => ''));
		}
	}
	
	if(count($cdnports_http) == 1) {
		for($i = 0; $i < count($cdns); $i++) {
			if($cdnports_http[0] == '' || ($cdns[$i]['https']=='both' ||  $cdns[$i]['https']=='forceoff' ||  $cdns[$i]['https']=='off') && $cdnports_http[0] == '80') {
				continue;
			}else {
				$cdns[$i]['port_http'] = ':' . $cdnsrvport;
			}
		}
	}else if(count($cdnports_http)==count($cdns)) {
		for($i = 0; $i < count($cdns); $i++) {
			$cdnsrvport = array_shift($cdnports_http);
			if($cdnsrvport == '' || ($cdns[$i]['https']=='both' ||  $cdns[$i]['https']=='forceoff' ||  $cdns[$i]['https']=='off') && $cdnsrvport == '80') {
				continue;
			}else {
				$cdns[$i]['port_http'] = ':' . $cdnsrvport;
			}
		}
	}
	
	if(count($cdnports_https) == 1) {
		for($i = 0; $i < count($cdns); $i++) {
			if($cdnports_https[0] == '' || ($cdns[$i]['https']=='both' ||  $cdns[$i]['https']=='forceon' ||  $cdns[$i]['https']=='on') && $cdnports_https[0] == '443') {
				continue;
			}else {
				$cdns[$i]['port_https'] = ':' . $cdnsrvport;
			}
		}
	}else if(count($cdnports_https)==count($cdns)) {
		for($i = 0; $i < count($cdns); $i++) {
			$cdnsrvport = array_shift($cdnports_https);
			if($cdnsrvport == '' || ($cdns[$i]['https']=='both' ||  $cdns[$i]['https']=='forceon' ||  $cdns[$i]['https']=='on') && $cdnsrvport == '443') {
				continue;
			}else {
				$cdns[$i]['port_https'] = ':' . $cdnsrvport;
			}
		}
	}
	
	while(true) {
		$rand_key = array_rand($cdns);
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
			switch($cdns[$rand_key]['https']) {
				case 'both':
				case 'on':
				case 'forceon':
					$schema = 'https';
					$port = $cdns[$rand_key]['port_https'];
					break 2;
				case 'forceoff':
					$schema = 'http';
					$port = $cdns[$rand_key]['port_http'];
					break 2;
			}
		}else {
			switch($cdns[$rand_key]['https']) {
				case 'both':
				case 'off':
				case 'forceoff':
					$schema = 'http';
					$port = $cdns[$rand_key]['port_http'];
					break 2;
				case 'forceon':
					$schema = 'https';
					$port = $cdns[$rand_key]['port_https'];
					break 2;
			}
		}
	}

	return $schema.'://' . $cdns[$rand_key]['server'] . $port . '/';
}

function get_url() {
	if($_SERVER['SERVER_NAME'] == 'localhost') {
		$_SERVER['SERVER_NAME'] = $_SERVER['SERVER_ADDR'];
	}
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
		return preg_replace('/(.*\/)(.*?\.php|.*?\?.+)$/', '\1', 'https://'.$_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT']==443 ? '' : ':'.$_SERVER['SERVER_PORT']).$_SERVER['REQUEST_URI']);
	}else {
		return preg_replace('/(.*\/)(.*?\.php|.*?\?.+)$/', '\1', 'http://'.$_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT']==80 ? '' : ':'.$_SERVER['SERVER_PORT']).$_SERVER['REQUEST_URI']);
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
	$siteset = return_bytes(SIZE_LIMIT);
	
	return min($postsize, $filesize, $siteset);
}

function get_upload_count(){
	return defined('UPLOAD_COUNT') ? UPLOAD_COUNT : 3;
}

// Escape special character
function escape_special_char($name) {
	return str_replace(array('#', '?', '=', '&', '/', '\\', ';', '<', '>', '[', ']', '%', '@', '-', '`', '(', ')'), '_', $name);
}

// Check if config.php is correct 
function check_config() {
	if (!(
		defined('UI_LANG') &&
		is_string(UI_LANG) &&
		preg_match('/[a-z]{2,3}(-[A-Z]{2})?|zh-Han[ts]/', UI_LANG)
	)) {
		exit('UI_LANG set incorrectly.');
	}
	if (!(
		defined('UI_THEME') &&
		is_string(UI_THEME) &&
		file_exists(ABSPATH . '/themes/' . UI_THEME)
	)) {
		exit('UI_THEME set incorrectly.');
	}
	if(!(
		defined('SITE_TITLE') &&
		is_string(SITE_TITLE)
	)) {
		exit('SITE_TITLE set incorrectly.');
	}
	if(!(
		defined('SITE_DESCRIPTION') &&
		is_string(SITE_DESCRIPTION)
	)) {
		exit('SITE_DESCRIPTION set incorrectly.');
	}
	if(!(
		defined('SITE_KEYWORDS') &&
		is_string(SITE_KEYWORDS)
	)) {
		exit('SITE_KEYWORDS set incorrectly.');
	}
	if(!(
		defined('ADMIN_EMAIL') &&
		is_string(ADMIN_EMAIL) &&
		preg_match('/(\w+\.)*\w+@(\w+\.)+[A-Za-z]+/', ADMIN_EMAIL)
	)) {
		exit('ADMIN_EMAIL set incorrectly.');
	}
	if(!(
		defined('MAIN_SITE') &&
		is_bool(MAIN_SITE)
	)) {
		exit('MAIN_SITE set incorrectly.');
	}else if(MAIN_SITE) {
		if(!(
			defined('MAIN_SITE_NAME') &&
			is_string(MAIN_SITE_NAME)
		)) {
			exit('MAIN_SITE_NAME set incorrectly.');
		}
		if(!(
			defined('MAIN_SITE_LOGO') &&
			is_string(MAIN_SITE_LOGO)
		)) {
			exit('MAIN_SITE_LOGO set incorrectly.');
		}
		if(!(
			defined('MAIN_SITE_URL') &&
			is_string(MAIN_SITE_URL) &&
			preg_match('/^https?:\/\/[a-zA-Z0-9_\-.]+(\/.*)?$/', MAIN_SITE_URL)
		)) {
			exit('MAIN_SITE_URL set incorrectly.');
		}
	}
	if(!(
		defined('SIZE_LIMIT') &&
		is_string(SIZE_LIMIT) &&
		preg_match('/[1-9]+\d*[TtGgMmKk]/', SIZE_LIMIT)
	)) {
		exit('SIZE_LIMIT set incorrectly.');
	}
	if(!(
		defined('UPLOAD_DIR') &&
		is_string(UPLOAD_DIR) &&
		(file_exists(ABSPATH . '/' . UPLOAD_DIR) ? (is_dir(ABSPATH . '/' . UPLOAD_DIR . '/') && is_writable(ABSPATH . '/' . UPLOAD_DIR . '/')) : is_writable(ABSPATH . '/'))
	)) {
		exit('UPLOAD_DIR set incorrectly.');
	}
	if(!(
		defined('THUMB_DIR') &&
		is_string(THUMB_DIR) &&
		(file_exists(ABSPATH . '/' . THUMB_DIR) ? (is_dir(ABSPATH . '/' . THUMB_DIR . '/') && is_writable(ABSPATH . '/' . THUMB_DIR . '/')) : is_writable(ABSPATH . '/'))
	)) {
		exit('THUMB_DIR set incorrectly.');
	}
	if(!(
		defined('DUPLICATE_FILE_CHECK') &&
		is_bool(DUPLICATE_FILE_CHECK)
	)) {
		exit('DUPLICATE_FILE_CHECK set incorrectly.');
	}
	if(!(
		defined('MANAGE_NAME') &&
		is_string(MANAGE_NAME)
	)) {
		exit('MANAGE_NAME set incorrectly.');
	}
	if(!(
		defined('MANAGE_PASSWORD') &&
		is_string(MANAGE_PASSWORD)
	)) {
		exit('MANAGE_PASSWORD set incorrectly.');
	}
	if(!(
		defined('CDN_ENABLED') &&
		is_bool(CDN_ENABLED)
	)) {
		exit('CDN_ENABLED set incorrectly.');
	}else if(CDN_ENABLED) {
		if(!(
			defined('CDN_LIST') &&
			is_string(CDN_LIST) &&
			preg_match('/^([-\w.]+[-\w]+,)*[-\w.]+[-\w]+$/', CDN_LIST)
		)) {
			exit('CDN_LIST set incorrectly.');
		}
		if(!(
			defined('CDN_HTTPS') &&
			is_string(CDN_HTTPS) &&
			preg_match('/^((forceon|forceoff|on|off|both)?,)*(forceon|forceoff|on|off|both)$/', CDN_HTTPS)
		)) {
			exit('CDN_HTTPS set incorrectly.');
		}
		if(!(
			defined('CDN_PORTS_HTTP') &&
			is_string(CDN_PORTS_HTTP) &&
			(CDN_PORTS_HTTP == '' || preg_match('/^((6553[0-5]|655[0-2]\d|65[0-4]\d{2}|6[0-4]\d{3}|[1-5]\d{4}|[1-9]\d{1,3}|[1-9])?,)*(6553[0-5]|655[0-2]\d|65[0-4]\d{2}|6[0-4]\d{3}|[1-5]\d{4}|[1-9]\d{1,3}|[1-9])$/', CDN_PORTS_HTTP))
		)) {
			exit('CDN_PORTS_HTTP set incorrectly.');
		}
		if(!(
			defined('CDN_PORTS_HTTPS') &&
			is_string(CDN_PORTS_HTTPS) &&
			(CDN_PORTS_HTTP == '' || preg_match('/^((6553[0-5]|655[0-2]\d|65[0-4]\d{2}|6[0-4]\d{3}|[1-5]\d{4}|[1-9]\d{1,3}|[1-9])?,)*(6553[0-5]|655[0-2]\d|65[0-4]\d{2}|6[0-4]\d{3}|[1-5]\d{4}|[1-9]\d{1,3}|[1-9])$/', CDN_PORTS_HTTPS))
		)) {
			exit('CDN_PORTS_HTTPS set incorrectly.');
		}
	}
	if(!(
		defined('COPYRIGHT') &&
		is_string(COPYRIGHT)
	)) {
		exit('COPYRIGHT set incorrectly.');
	}
	if(!(
		defined('WATERMARK') &&
		is_bool(WATERMARK) &&
		file_exists(ABSPATH . '/site-img/watermark.png')
	)) {
		exit('WATERMARK set incorrectly.');
	}else if(WATERMARK) {
		if(!(
			defined('WATERMARK_MIN_SIZE') &&
			is_string(WATERMARK_MIN_SIZE) &&
			preg_match('/([1-9]+\d*|0)x([1-9]+\d*|0)/', WATERMARK_MIN_SIZE)
		)) {
			exit('WATERMARK_MIN_SIZE set incorrectly.');
		}
		if(!(
			defined('WATERMARK_POS') &&
			is_string(WATERMARK_POS) &&
			preg_match('/(-?[1-9]+\d*|0)x(-?[1-9]+\d*|0)/', WATERMARK_MIN_SIZE)
		)) {
			exit('WATERMARK_POS set incorrectly.');
		}
	}
}
