<?php

define('MANAGE_RUN',true);

/* Load Functions */
require '../engine.php';

require ABSPATH.'/manage/functions.php';

$logerror=-1;
if($logged=is_login()) {
	set_login();
}else {
	$result = array('error'=>'not_login');
}

if(isset($_GET['action']) && $_GET['action']=='delete') {
	$works = json_decode(file_get_contents('php://input'),true);
	$result=delete_files($works);
}
echo json_encode($result);
