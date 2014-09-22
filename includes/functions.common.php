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
		return preg_replace('/(.*\/)(.*?\.php|.*?\?.+)$/', '\1', 'https://'.$_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT']==443 ? '' : $_SERVER['SERVER_PORT']).$_SERVER['REQUEST_URI']);
	}else {
		return preg_replace('/(.*\/)(.*?\.php|.*?\?.+)$/', '\1', 'http://'.$_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT']==80 ? '' : $_SERVER['SERVER_PORT']).$_SERVER['REQUEST_URI']);
	}
}

function check_apikey() {
	return true;
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
