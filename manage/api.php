<?php

define('MANAGE_RUN',true);

/* Load Functions */
require '../engine.php';

require ABSPATH.'/manage/functions.php';

$logerror=-1;
if($logged=is_login()) {
	set_login();
	$filem=get_files();
}else if(isset($_POST['submit']) && $logged=check_login($logerror)) {
	set_login();
	$filem=get_files();
}else {
	$filem=false;
}



?>