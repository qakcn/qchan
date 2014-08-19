<?php

function __($message) {
	global $lang;
	if ($lang && isset($lang[$message])) {
		return $lang[$message];
	}else {
		return $message;
	}
}

function get_locale() {
	if(isset($_GET['lang'])) {
		setcookie('UI_LANG',$_GET['lang'],time()+60*60*24*365);
		return $_GET['lang'];
	}else if(isset($_COOKIE['UI_LANG'])) {
		return $_COOKIE['UI_LANG'];
	}else {
		return defined('UI_LANG') ? UI_LANG : 'en';
	}
}

function get_available_langs() {
	$langfiles = scandir(ABSPATH.'/lang');
	$out = array();
	foreach ($langfiles as $key => $langfile) {
		if(preg_match('/.+\.json$/', $langfile)) {
			array_push($out, substr($langfile,0,-5));
		}
	}
	return $out;
}

function get_langlist() {
	$langlist = '';
	$langs = get_available_langs();
	foreach($langs as $lang) {
		$langlist .= sprintf('<li><a href="?lang=%s">%s</a></li>', $lang, get_lang_name($lang)) . "\n";
	}
	return $langlist;
}

function load_lang() {
	$locale = get_locale();
	$langfiles = get_available_langs();
	while($langfile = array_pop($langfiles)) {
		if($langfile==$locale || $langfile==substr($locale,0,2)) {
			return json_decode(file_get_contents(ABSPATH.'/lang/' . $langfile . '.json'),true);
		}else if(substr_compare($langfile,$locale,0,2)==0) {
			$remember = $langfile;
		}
	}
	return isset($remember) ? json_decode(file_get_contents(ABSPATH.'/lang/' . $remember . '.json'),true) : false;
}

function get_lang_name($locale) {
	$locales = json_decode(file_get_contents(ABSPATH.'/lang/lang.list'),true);
	return $locales[$locale];
}

