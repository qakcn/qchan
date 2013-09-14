<?php

/* Deny direct visit */
if (!defined('INDEX_RUN') && !defined('AJAX_RUN') && !defined('API_RUN')) {
	exit('This file must be loaded in flow.');
}

define('QCHAN_VER', '1.0');

/* Load Configurations or start installation */
if(file_exists('config.php')) {
	require 'config.php';
}else {
	header("Location: install.php");
}

require 'Mobile_Detect.php';
$ismobile = new Mobile_Detect;

/* Load functions */
require 'functions.php';
$lang = load_lang();


?>