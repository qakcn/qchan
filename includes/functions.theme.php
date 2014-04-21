<?php

function theme_path() {
	return 'themes/'.UI_THEME.'/';
}

function load_theme($results=null) {
	require_once ABSPATH.'/'.theme_path().'functions.php';
	switch($_GET['page']) {
		case 'main':
			require_once ABSPATH.'/'.theme_path().'main.php';
			break;
		case 'privacy':
			require_once ABSPATH.'/'.theme_path().'privacy.php';
			break;
		case 'agreement':
			require_once ABSPATH.'/'.theme_path().'agreement.php';
			break;
	}
}

function load_header() {
	require_once ABSPATH.'/'.theme_path().'header.php';
}

function load_footer() {
	require_once ABSPATH.'/'.theme_path().'footer.php';
}

?>