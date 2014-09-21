<?php

// Setup path for upload
function setup_dir() {
	$year=date('Y');$month=date('m');
	if(!file_exists(ABSPATH.'/'.UPLOAD_DIR))
		mkdir(ABSPATH.'/'.UPLOAD_DIR) || exit('Cannot write to upload directory.');
	if(!file_exists(UPLOAD_DIR . '/' . $year))
		mkdir(ABSPATH.'/'.UPLOAD_DIR . '/' . $year);
	if(!file_exists(ABSPATH.'/'.UPLOAD_DIR . '/' . $year .'/'. $month))
		mkdir(ABSPATH.'/'.UPLOAD_DIR . '/' . $year .'/'. $month);
	$uploads_dir=UPLOAD_DIR . '/' . $year .'/'. $month;
	if(!file_exists(ABSPATH.'/'.UPLOAD_DIR . '/working'))
		mkdir(ABSPATH.'/'.UPLOAD_DIR . '/working');
	if(!file_exists(ABSPATH.'/'.UPLOAD_DIR . '/hash'))
		mkdir(ABSPATH.'/'.UPLOAD_DIR . '/hash');
	if(!file_exists(ABSPATH.'/'.THUMB_DIR))
		mkdir(ABSPATH.'/'.THUMB_DIR) || exit('Cannot write to thumbnail directory.');
	if(!file_exists(ABSPATH.'/'.THUMB_DIR . '/' . $year))
		mkdir(ABSPATH.'/'.THUMB_DIR . '/' . $year);
	if(!file_exists(ABSPATH.'/'.THUMB_DIR . '/' . $year .'/'. $month))
		mkdir(ABSPATH.'/'.THUMB_DIR . '/' . $year .'/'. $month);
	$thumbs_dir=THUMB_DIR . '/' . $year .'/'. $month;
	
	return array($uploads_dir,$thumbs_dir);
}

//Handle the URL uploading
function url_handler() {
	list($uploads_dir,$thumbs_dir) = setup_dir();
	
	$url = $_POST['url'];
	$name = escape_special_char(basename($url));
	
	$result = array('qid'=>$_POST['qid']);
	$host = get_cdn();
	
	$purl=parse_url($url);
	if($purl['host']==$_SERVER['SERVER_NAME'] || CDN_ENABLED && in_array($purl['host'], explode(',', CDN_LIST))) {
		$result['status']='success';
		$result['name']='duplicate';
		$result['path']=$url;
		$result['thumb']=$url;
		return $result;
	}
	
	if(remote_filesize($url) > get_size_limit()) {
		$result['status'] = 'failed';
		$result['err'] = 'size_limit';
	}else if($content=@file_get_contents($url)) {
		$temp = ABSPATH.'/'.UPLOAD_DIR . '/working/' . $name . time();
		if(!file_put_contents($temp, $content)) {
			$result['status'] = 'failed';
			$result['err'] = 'write_prohibited';
			return $result;
		}else if($duplicate = is_duplicate($temp)) {
			$result['status'] = 'success';
			$result['thumb'] = ($duplicate['thumb']=='none' ? '' : $host) . $duplicate['thumb'];
			$result['path'] = $host . $duplicate['path'];
			$result['name'] = $duplicate['name'];
			unlink($temp);
		}else if(filesize($temp) > get_size_limit()) {
			$result['status'] = 'failed';
			$result['err'] = 'size_limit';
			unlink($temp);
		}else {
			$mime=file_mime_type($temp);
			switch($mime) {
				case 'image/jpeg':	
					if(!preg_match('/\.(jpg|jpeg|jpe|jfif|jfi|jif)$/i', $name)) {
						$name.='.jpg';
					}
					break;
				case 'image/png':
					if(!preg_match('/\.(png)$/i', $name)) {
						$name.='.png';
					}
					break;
				case 'image/gif':
					if(!preg_match('/\.(gif)$/i', $name)) {
					$name.='.gif';
					}
					break;
				case 'image/svg+xml':
					if(!preg_match('/\.(svg)$/i', $name)) {
						$name.='.svg';
					}
					break;
				default:
					$result['status'] = 'failed';
					$result['err'] = 'wrong_type';
			}
			if(!isset($result['status']) || !$result['status'] == 'failed') {
				$name = rename_if_exists($name, $uploads_dir);
				$path = "$uploads_dir/$name";
				if(!copy($temp, ABSPATH.'/'.$path)) {
					$result['status'] = 'failed';
					$result['err'] = 'write_prohibited';
				}else {
					watermark($path);
					$thumb = make_thumb($name, $path, $thumbs_dir);
					if($duplicate = duplicate_hash($name, $path, $thumb)) {
						$result['status'] = 'success';
						$result['thumb'] = $thumb['generated'] ? $host . $thumb['path'] : 'none';
						$result['path'] = $host . $path;
						$result['name'] = $name;
						if(isset($thumb['width'])) {
							$result['width'] = $thumb['width'];
							$result['height'] = $thumb['height'];
						}
					}else {
						$result['status'] = 'error';
						$result['err'] = 'fail_duplicate';
						$result['path'] = $host . $path;
						$result['name'] = $name;
						$result['thumb'] = $thumb['generated'] ? $host . $thumb['path'] : 'none';
						if(isset($thumb['width'])) {
							$result['width'] = $thumb['width'];
							$result['height'] = $thumb['height'];
						}
					}
				}
			}
			unlink($temp);
		}
	}else {
		$result['status'] = 'failed';
		$result['err'] = 'no_file';
	}
	return $result;
}

//Handle the file uploading
function file_handler() {
	list($uploads_dir,$thumbs_dir) = setup_dir();
	$files = $_FILES['files'];
	$results=array();

	foreach($files['error'] as $key => $error) {
		$result = isset($_POST['qid']) ? array('qid'=>$_POST['qid']) : array();
		$name =  escape_special_char($files['name'][$key]);
		$host = get_cdn();

		if($error==UPLOAD_ERR_OK) {
			if($files['size'][$key] > get_size_limit()) {
				$result['status'] = 'failed';
				$result['err'] = 'size_limit';
			}else {
				$temp = $files['tmp_name'][$key];
				if($duplicate = is_duplicate($temp)) {
					$result['status'] = 'success';
					$result['thumb'] = ($duplicate['thumb']=='none' ? '' : $host) . $duplicate['thumb'];
					$result['path'] = $host . $duplicate['path'];
					$result['name'] = $duplicate['name'];
					$result['width'] = $duplicate['width'];
					$result['height'] = $duplicate['height'];
				}else {
					$mime=file_mime_type($temp);
					switch($mime) {
						case 'image/jpeg':	
							if(!preg_match('/\.(jpg|jpeg|jpe|jfif|jfi|jif)$/i', $name)) {
								$name.='.jpg';
							}
							break;
						case 'image/png':
							if(!preg_match('/\.(png)$/i', $name)) {
								$name.='.png';
							}
							break;
						case 'image/gif':
							if(!preg_match('/\.(gif)$/i', $name)) {
								$name.='.gif';
							}
							break;
						case 'image/svg+xml':
							if(!preg_match('/\.(svg)$/i', $name)) {
								$name.='.svg';
							}
							break;
						default:
							$result['status'] = 'failed';
							$result['err'] = 'wrong_type';
					}
					
					if(!isset($result['status']) || !$result['status'] == 'failed') {
						$name = rename_if_exists($name, $uploads_dir);
						$path = "$uploads_dir/$name";
						if(!move_uploaded_file($temp, ABSPATH.'/'.$path)) {
							$result['status'] = 'failed';
							$result['err'] = 'write_prohibited';
						}else {
							watermark($path);
							$thumb = make_thumb($name, $path, $thumbs_dir);
							if($duplicate = duplicate_hash($name, $path, $thumb)) {
								$result['status'] = 'success';
								$result['thumb'] = $thumb['generated'] ? $host . $thumb['path'] : 'none';
								$result['path'] = $host . $path;
								$result['name'] = $name;
								if(isset($thumb['width'])) {
									$result['width'] = $thumb['width'];
									$result['height'] = $thumb['height'];
								}
							}else {
								$result['status'] = 'error';
								$result['err'] = 'fail_duplicate';
								$result['path'] = $host . $path;
								$result['name'] = $name;
								$result['thumb'] = $thumb['generated'] ? $host . $thumb['path'] : 'none';
								if(isset($thumb['width'])) {
									$result['width'] = $thumb['width'];
									$result['height'] = $thumb['height'];
								}
							}
						}
					}
				}
			}
		}else {
			switch($error) {
				case UPLOAD_ERR_INI_SIZE:
					$result['status'] = 'failed';
					$result['err'] = 'php_upload_size_limit';
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$result['status'] = 'failed';
					$result['err'] = 'size_limit';
					break;
				case UPLOAD_ERR_PARTIAL:
					$result['status'] = 'failed';
					$result['err'] = 'part_upload';
					break;
				case UPLOAD_ERR_NO_FILE:
					$result['status'] = 'failed';
					$result['err'] = 'no_file';
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$result['status'] = 'failed';
					$result['err'] = 'no_tmp';
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$result['status'] = 'failed';
					$result['err'] = 'write_prohibited';
					break;
			}
		}

		array_push($results, $result);
	}
	return $results;
}

// Rename file if exists
function rename_if_exists($name, $dir) {
	$num = 1;
	while(file_exists(ABSPATH."/$dir/$name")){
		$name = preg_replace('/(\(\d*\))?\.(' . SUPPORT_TYPE . ')$/i', '(' .$num . ').\2', $name);
		$num++;
	}
	return $name;
}

//Get remote file size
function remote_filesize($url){  
	$url = parse_url($url); 
	if($fp = @fsockopen($url['host'],empty($url['port'])?80:$url['port'],$error)){
		fputs($fp,"GET ".(empty($url['path'])?'/':$url['path'])." HTTP/1.1\r\n");
		fputs($fp,"Host:$url[host]\r\n\r\n");
	while(!feof($fp)) {
		$tmp = fgets($fp);
			if(trim($tmp) == '') {
				break;
			}elseif(preg_match('/Content-Length:(.*)/si',$tmp,$arr)) {
				return trim($arr[1]);
			}
		}
		return null;
	}else{
		return null;
	}
}

//Get file MIME type
function file_mime_type($file) {
	if(function_exists('mime_content_type')) {
		return mime_content_type($file);
	}elseif(function_exists('finfo_open') && ($finfo=finfo_open(FILEINFO_MIME_TYPE))) {
		return finfo_file($finfo, $file);
	}elseif(function_exists('fopen') && ($hl=fopen($file, 'r'))) {
		$bytes = fread($hl, 512);
		if(preg_match('/^\x89\x50\x4e\x47\x0d\x0a\x1a\x0a/',$bytes)) {
			return 'image/png';
		}elseif(preg_match('/^\xff\xd8/',$bytes)) {
			return 'image/jpeg';
		}elseif(preg_match('/^GIF8/',$bytes)) {
			return 'image/gif';
		}elseif(preg_match('/^BM....\x00\x00\x00\x00/',$bytes)) {
			return 'image/bmp';
		}elseif(preg_match('/^\s*<\?xml\C+<!DOCTYPE svg/',$bytes)) {
			return 'image/svg+xml';
		}else {
			return 'unknow';
		}
		fclose($hl);
	}
	return false;
}

//Generate thumbnail image
function make_thumb($name, $path, $thumbs_dir) {
	$height = 200;
	$width = 1000;
	$return = array('generated'=>false);
	if(!$imgInfo=getimagesize(ABSPATH.'/'.$path)) {
		return $return;
	}
	$notype = false;
	list($width_orig, $height_orig, $type) = $imgInfo;
	
	switch($type){
		case IMAGETYPE_GIF:
			$readf="imagecreatefromgif";
			$writef="imagegif";
			break;
		case IMAGETYPE_JPEG:
			$readf="imagecreatefromjpeg";
			$writef="imagejpeg";
			break;
		case IMAGETYPE_PNG:
			$readf="imagecreatefrompng";
			$writef="imagepng";
			break;
		default:
			$notype = true;
	}
	
	if($height_orig <= $height && $width_orig <= $width) {
		$return['width'] = $width_orig;
		$return['height'] = $height_orig;
	}else{
		$ratio_orig = $width_orig/$height_orig;
		if ($width/$height > $ratio_orig) {
			$width = $height*$ratio_orig;
		}else {
			$height = $width/$ratio_orig;
		}
		$return['width'] = $width;
		$return['height'] = $height;
		if($notype || !$image_p = imagecreatetruecolor($width, $height)) {
			return $return;
		}
		$image = $readf(ABSPATH.'/'.$path);
		
		// Create alpha channel for png
		if($type == IMAGETYPE_PNG) {
			if(!imagealphablending($image_p, false) || !imagesavealpha($image_p,true) || !($transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127)) || !imagefill($image_p, 0, 0, $transparent)) {
				return $return;
			}
		}

		// Make transparent for gif
		if($type == IMAGETYPE_GIF) {
			$transparent_index = imagecolortransparent($image);
			if($transparent_index != -1) {
				$bgcolor = imagecolorsforindex($image, $transparent_index);
			}else {
				$bgcolor = array('red' => 0, 'green' => 0, 'blue' => 0);
			}
			$bgcolor = imagecolorallocate($image_p, $bgcolor['red'], $bgcolor['green'], $bgcolor['blue']);
			$bgcolor_index = imagecolortransparent($image_p, $bgcolor);
			imagefill($image_p, 0, 0, $bgcolor_index);
		}

		// Resize image
		if(!imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig)) {
			return $return;
		}
		
		if(!$writef($image_p, ABSPATH."/$thumbs_dir/$name")) {
			return $return;
		}
		imagedestroy($image);imagedestroy($image_p);
		$return['generated'] = true;
		$return['path'] = "$thumbs_dir/$name";
	}
	return $return;
}

function watermark($file) {
	if(WATERMARK) {
		$imgInfo=getimagesize(ABSPATH.'/'.$file);
		list($width, $height, $type) = $imgInfo;
		if($type == IMAGETYPE_JPEG) {
			$readf="imagecreatefromjpeg";
			$writef="imagejpeg";
		}else if($type == IMAGETYPE_PNG) {
			$readf="imagecreatefrompng";
			$writef="imagepng";
		}
		
		list($x, $y) = explode(',', WATERMARK_POS);
		list($min_width, $min_height) = explode('x', WATERMARK_MIN_SIZE);
		
		if($width >= $min_width && $height >= $min_height) {
			if($x < 0) $x = $width + $x;
			if($y < 0) $y = $height + $y;
			$image = $readf(ABSPATH.'/'.$file);
			$mark = imagecreatefrompng(ABSPATH . '/site-img/watermark.png');
			imagecopy($image, $mark, $x, $y, 0, 0, imagesx($mark), imagesy($mark));
			$writef($image, ABSPATH.'/'.$file);
		}
	}
}