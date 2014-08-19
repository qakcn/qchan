<?php

define('MANAGE_RUN',true);

session_start();

/* Load Functions */
require '../engine.php';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<title><?=SITE_TITLE . ' - ' . __('Qchan Image Hosting') ?></title>


</head>
<?php
if(file_exists('manage/installed.lock')) {
	
}
switch ($_GET['step']) {
	case '1':
		
		break;
	case '2':
		
		break;
	case '3':
		
		break;
	default:
		
}
