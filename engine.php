<?php

/* Deny direct visit */
if (!defined('INDEX_RUN') && !defined('API_RUN') &&  !defined('MANAGE_RUN')) {
	header('HTTP/1.1 403 Forbidden');
	exit('This file must be loaded in flow.');
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
			preg_match('/^https?:\/\/[a-zA-Z_\-.]+(\/.*)?$/', MAIN_SITE_URL)
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

define('QCHAN_VER', '1.0pre build 20140921');
define('QCHAN_URL', 'http://github.com/qakcn/qchan');
date_default_timezone_set('UTC');
define('ABSPATH', __DIR__);

if(!is_writable(ABSPATH . '/')) {
	exit(ABSPATH.' is not writable.');
}

/* Load Configurations or start installation */
if(file_exists( ABSPATH.'/config.php')) {
	require_once  ABSPATH.'/config.php';
}else {
	header("Location: install.php");
	exit();
}

$config_timestamp = 0;
$config_timestamp_now = filemtime(ABSPATH.'/config.php');
if(file_exists(ABSPATH.'/config.timestamp')) {
	$config_timestamp = file_get_contents(ABSPATH.'/config.timestamp');
}
if($config_timestamp != $config_timestamp_now) {
	check_config();
	file_put_contents(ABSPATH.'/config.timestamp', $config_timestamp_now);
}

define('SUPPORT_TYPE', 'jpg|jpeg|jpe|jfif|jfi|jif|gif|png|svg');
header("\x58\x2D\x51\x63\x68\x61\x6E\x2D\x49\x6E\x66\x6F\x3A\x20\x51\x63\x68\x61\x6E\x20\x69\x73\x20\x61\x6E\x20\x69\x6D\x61\x67\x65\x20\x68\x6F\x73\x74\x69\x6E\x67\x20\x66\x72\x65\x65\x77\x61\x72\x65\x2C\x20\x69\x73\x20\x64\x65\x76\x65\x6C\x6F\x70\x65\x64\x20\x62\x79\x20\x51\x75\x61\x64\x72\x61\x20\x53\x74\x75\x64\x69\x6F\x2C\x20\x61\x6E\x64\x20\x70\x75\x62\x6C\x69\x73\x68\x65\x64\x20\x75\x6E\x64\x65\x72\x20\x47\x50\x4C\x76\x33\x2E");

/* Load functions */
require_once ABSPATH.'/includes/functions.common.php';
require_once ABSPATH.'/includes/functions.language.php';
require_once ABSPATH.'/includes/functions.theme.php';
require_once ABSPATH.'/includes/functions.upload.php';
$lang = load_lang(defined('MANAGE_RUN'));

