<?php

function __($message) {
	global $lang;
	if ($lang && isset($lang[$message])) {
		if(func_num_args()==1) {
			return $lang[$message];
		}else{
			$args=func_get_args();
			array_shift($args);
			return vsprintf($lang[$message],$args);
		}
	}else {
		if(func_num_args()==1) {
			return $message;
		}else{
			$args=func_get_args();
			array_shift($args);
			return vsprintf($message,$args);
		}
	}
}

function get_locale($nocookie=false) {
	if(isset($_GET['lang'])) {
		if(!$nocookie) {
			setcookie('UI_LANG',$_GET['lang'],time()+60*60*24*365);
		}
		return $_GET['lang'];
	}else if(isset($_COOKIE['UI_LANG'])) {
		return $_COOKIE['UI_LANG'];
	}else {
		return defined('UI_LANG') ? UI_LANG : 'en';
	}
}

function get_available_langs($manage=false) {
	$langfiles = scandir(ABSPATH. '/' . ($manage?'manage/':theme_path()) .'lang');
	$out = array();
	foreach ($langfiles as $key => $langfile) {
		if(preg_match('/.+\.json$/', $langfile)) {
			array_push($out, substr($langfile,0,-5));
		}
	}
	return $out;
}

function get_langlist($manage=false) {
	$langlist = '';
	$langs = get_available_langs($manage=false);
	foreach($langs as $lang) {
		$langlist .= sprintf('<li><a href="?lang=%s">%s</a></li>', $lang, get_lang_name($lang)) . "\n";
	}
	return $langlist;
}

function load_lang($manage=false) {
	$locale = get_locale();
	$langfiles = get_available_langs($manage);
	while($langfile = array_pop($langfiles)) {
		if($langfile==$locale || $langfile==substr($locale,0,2)) {
			return json_decode(file_get_contents(ABSPATH . '/' . ($manage?'manage/':theme_path()).'lang/' . $langfile . '.json'),true);
		}else if(substr_compare($langfile,$locale,0,2)==0) {
			$remember = $langfile;
		}
	}
	return isset($remember) ? json_decode(file_get_contents(ABSPATH . '/' . ($manage?'manage/':theme_path()).'lang/' . $remember . '.json'),true) : false;
}

function get_lang_name($locale) {
	$locales = json_decode(file_get_contents(ABSPATH.'/lang/lang.list'),true);
	return $locales[$locale];
}

