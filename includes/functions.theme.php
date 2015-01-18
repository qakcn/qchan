<?php

function theme_path() {
	return 'themes/'.UI_THEME.'/';
}

function load_theme($results=null) {
	require_once ABSPATH.'/'.theme_path().'functions.php';
	$page=is_page();
	if ($page) {
		if(file_exists(ABSPATH . '/' . theme_path().'page-'.$page.'.php')) {
			require_once ABSPATH . '/' . theme_path().'page-'.$page.'.php';
		}else {
			return_404();
		}
	}else {
		require_once ABSPATH . '/' . theme_path().'main.php';
	}
}

function is_page() {
	return isset($_GET['page'])?$_GET['page']:false;
}

function load_header() {
	require_once ABSPATH.'/'.theme_path().'header.php';
}

function load_footer() {
	require_once ABSPATH.'/'.theme_path().'footer.php';
}

function embed_script() {
	return '<script src="'.get_url().'js/upload.js" type="application/javascript"></script>';
}
