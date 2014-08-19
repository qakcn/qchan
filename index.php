<?php

define('INDEX_RUN',true);

/* Load Functions */
require 'engine.php';

if(isset($_GET['err']) && $_GET['err']!='') {
	/* 404 and 403 picture */
	switch($_GET['err']) {
		case '404':
			header('Content-Type: image/jpeg', true, 404);
			echo file_get_contents('site-img/404.jpg');
			break;
		case '403':
			header('Content-Type: image/jpeg', true, 403);
			echo file_get_contents('site-img/403.jpg');
			break;
	}
}else {
	if(isset($_POST['normal']) && $_POST['normal'] == 'upload') {
		$results = file_handler();
	}else {
		$results=null;
	}
	load_theme($results);
}
