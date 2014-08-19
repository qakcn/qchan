<?php

function theme_path() {
	return 'themes/'.UI_THEME.'/';
}

function load_theme($results=null) {
	require_once ABSPATH.'/'.theme_path().'functions.php';
	if (isset($_GET['page'])) {
		if(file_exists(ABSPATH . '/' . theme_path().'page-'.$_GET['page'].'.php')) {
			require_once ABSPATH . '/' . theme_path().'page-'.$_GET['page'].'.php';
		}else {
			return_404();
		}
	}else {
		require_once ABSPATH . '/' . theme_path().'main.php';
	}
}

function load_header() {
	require_once ABSPATH.'/'.theme_path().'header.php';
}

function load_footer() {
	require_once ABSPATH.'/'.theme_path().'footer.php';
}

