<?php
function format_results($results) {
	$id = 0;
	$format = <<<FORMAT
<li id="n%d" %s draggable="true" style="width: %dpx; height: %dpx;"><div class="img" style="%sbackground-image: url(&quot;%s&quot;);"><div class="name"><p>%s</p></div><div class="infotag"><span class="longtag" title="%s">LONG</span><span class="tinytag" title="%s">TINY</span></div><div class="select"><p>\xee\x98\x81</p></div></div></li>
FORMAT;
	$output='';
	$tinytag = __('This image is tiny and enlarged');
	$longtag = __('This image is long and will be auto scrolled when mouse over');
	foreach($results as $result) {
		$exclass = '';
		$imgstyle = '';
		if(isset($result['width'])) {
			$width = $result['width'];
			$height = $result['height'];
			$extiny = (isset($result['extiny']) && $result['extiny'] == 'tiny');
			$exlong = (isset($result['exlong']) && $result['exlong'] == 'long');
			$ratio = $width/$height;
			if($extiny && $exlong) {
				if($ratio < 1) {
					$width = 67;
					$height = 200;
					$exclass='data-direction="ttb" ';
					$imgstyle = 'background-size: 100% auto;';
				}else {
					$height = 67;
					$width = 200;
					$exclass='data-direction="ltr" ';
					$imgstyle = 'background-size: auto 100%;';
				}
				$exclass .= 'class="tiny long"';
			}else if($extiny && !$exlong) {
				if($ratio < 1) {
					$width = 67;
					$height = $width/$ratio;
				}else {
					$height = 67;
					$width = $height * $ratio;
				}
				$exclass = 'class="tiny"';
			}else if(!$extiny && $exlong) {
				if($ratio < 1) {
					$height = 200;
					$exclass='data-direction="ttb" ';
					$imgstyle = 'background-size: 100% auto;';
				}else {
					$width = 200;
					$exclass='data-direction="ltr" ';
					$imgstyle = 'background-size: auto 100%;';
				}
				$exclass .= 'class="long"';
			}
		}else {
			$width = 200;
			$height = 200;
		}
		$preview = ($result['thumb']!='none')?$result['thumb']:$result['path'];
		$output .= sprintf($format, $id++, $exclass, $width, $height, $imgstyle, $preview, $result['name'], $longtag, $tinytag);
	}
	return $output;
}

function format_script($results) {
	$id = 0;
	$format = <<<FORMAT
$('#n%d').on('click', toggleinfo).on('contextmenu', toggleinfo).prop('work', %s).on('mouseenter', movelongstart).on('mouseleave', movelongend);
FORMAT;
	$output = '';
	foreach($results as $result) {
		$work = json_encode(array('name'=>$result['name'], 'path'=>$result['path'], 'thumb'=>$result['thumb'], 'status'=>'success', 'qid'=>'n'.$id));
		$output .= sprintf($format, $id, $work);
		$id++;
	}
	return $output;
}

function format_message() {
	return json_encode(array(
		'err' => array(
			'illegal_url' => __(' is not an acceptable URL.'),
			'fail_load' => __(' cannot load preview now. Waiting server response.'),
			'wrong_type' => __(' is unsupported file type.'),
			'size_limit' => __(' reaches the size limitation.'),
			'no_file' => __(' cannot be retrieved by server now. Check if URL is invalid.'),
			'write_prohibited' => __(' cannot write to server disk.'),
			'fail_duplicate' => __(' cannot perform duplicate check.'),
			'php_upload_size_limit' => __(' reaches size limit set in php.ini.'),
			'part_upload' => __(' only part of were uploaded.'),
			'no_tmp' => __(' there is no temporary directory on server.'),
			'fail_retry' => __(' tried several times and all failed.')
		),
		
		'err_detail' => array(
			'no_file' => __('File cannot be retrieved by server now. Perhaps remote server is unreachable, or file does not exist any more, or you just make a little mistake.'),
			'size_limit' => __('Reaches file size limitation.'),
			'fail_load' => __('The file preview cannot be loaded, Perhaps file is no more exist, or remote server is un reachable, or you just make a little mistake.'),
			'write_prohibited' => __('File cannot write to upload directory on server. Ask webmaster to check permissions.'),
			'wrong_type' => __('File type not support now. Communicate with author for more help.'),
			'fail_duplicate' => __('Duplicate check is failed.'),
			'php_upload_size_limit' => __('Reaches file size limitation in php.ini.'),
			'part_upload' => __('Only parts of the file were uploaded. You can retry it.'),
			'no_tmp' => __('Temporary directory on the server does not exist. Ask webmaster to check.'),
			'fail_retry' => __('Try to upload several times and all of those were failed.')
		),
		
		'status' =>array(
			'prepare' => __('Preparing for uploading'),
			'waiting' => __('Waiting for uploading'),
			'uploading' => __('Uploading'),
			'success' => __('Uploaded successfully'),
			'error' => __('Something wrong'),
			'failed' => __('Failed to upload'),
			'all_success' => __('All selected files were uploaded successfully'),
			'part_success' => __('Not all selected files were uploaded successfully, only uploaded ones showed below'),
			'all_failed' => __('All selected files were failed to upload'),
		),
		
		'info' => array(
			'files_selected' => __(' Files Selected'),
			'orig' => __('Original File'),
			'html' => __('HTML Code'),
			'html_with_thumb' => __('HTML Code with thumbnail'),
			'bbcode' => __('BBCode'),
			'bbcode_with_thumb' => __('BBCode with thumbnail'),
			'thumb_tips' => __('Click to view large version'),
			'undrop' => __('Your browser doesn\'t support Drag and drop upload'),
			'tinytag' => __('This image is tiny and enlarged'),
			'longtag' => __('This image is long and will be auto scrolled when mouse over')
		),
	));
}

function format_main_site() {
	if(MAIN_SITE) {
		if(MAIN_SITE_LOGO!==''){
			return '<p id="main_site"><a target="_blank" href="'.MAIN_SITE_URL.'"><img src="'.MAIN_SITE_LOGO.'" alt="Main Site Logo" title="'.MAIN_SITE_NAME.'"></a></p>';
		}else {
			return '<p id="main_site"><a target="_blank" href="'.MAIN_SITE_URL.'" title="'.MAIN_SITE_NAME.'">'.MAIN_SITE_NAME.'</a></p>';
		}
	}else {
		return '';
	}
}