<?php

require 'engine.php';

list($uploads_dir,$thumbs_dir) = setup_dir();

// URL Upload Handler
if($_POST['type']=='url') {
	// URL upload handler
	$thumbsize=$_POST['thumb_size'];
	$isthumb=$_POST['is_thumb'];
	$url=trim($_POST['addr']);
	$specurl=htmlspecialchars($url);
	$name=basename($url);
	$name=escape_special_char($name);
	$wrongtype=false;
	
	// Check if size is over limit
	if(remote_filesize($url)>the_size_limit()) {
		echo '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname"><em>'.$specurl.'</em></div><div class="errormsg">'.ERR_UPLOAD_FORM_SIZE.'(01)</div>';
	}elseif($content=@file_get_contents($url)) {
		$temp = UPLOAD_DIR . '/working/' . $name . time();
		if(!file_put_contents($temp, $content)) {
			echo '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname"><em>'.$specurl.'</em></div><div class="errormsg">'.ERR_UPLOAD_CANT_WRITE.'(01)</div>';
		}elseif(filesize($temp)>the_size_limit()) {
			unlink($temp);
			echo '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname"><em>'.$specurl.'</em></div><div class="errormsg">'.ERR_UPLOAD_FORM_SIZE.'(02)</div>';
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
				$wrongtype=true;
			}
			if($wrongtype) {
				unlink($temp);
				echo '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname"><em>'.$specurl.'</em></div><div class="errormsg">'.ERR_UPLOAD_WRONG_TYPE.'(01)</div>';
			}else {
				$name=rename_if_exists($name, $uploads_dir);
				$path="$uploads_dir/$name";
				if(!copy($temp, $path)) {
					unlink($temp);
					echo '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname"><em>'.$specurl.'</em></div><div class="errormsg">'.ERR_UPLOAD_CANT_WRITE.'(02)</div>';
				}elseif(!$wrongtype) {
					unlink($temp);
					$thumb=make_thumb($uploads_dir,$thumbs_dir,$name,$thumbsize, $isthumb);
					result_format(array(array('error'=>UPLOAD_ERR_OK,'thumb'=>$thumb,'path'=>$uploads_dir,'name'=>$name)),'urlresult');
				}
			}
		}
	}else {
		$output = '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname"><em>'.$specurl.'</em></div><div class="errormsg">'.ERR_UPLOAD_NO_FILE.'</div>';
	}
}elseif($_POST['type']=='grab') {
	$url=trim($_POST['addr']);
	$specurl=htmlspecialchars($url);
	if($content=@file_get_contents($url)) {
		$temp = UPLOAD_DIR . '/working/' . time();
		if(!file_put_contents($temp, $content)) {
			echo '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname"><em>'.$specurl.'</em></div><div class="errormsg">'.ERR_UPLOAD_CANT_WRITE.'(01)</div>';
		}else {
			$mime=file_mime_type($temp);
			unlink($temp);
			if(preg_match('/image\/(jpeg|png|gif|svg|bmp)/', $mime)) {
				$content=base64_encode($content);
				echo '<li class="grabimg urlresult"><img src="data:'.$mime.';base64,'.$content.'" alt="'.$specurl.'"></li>';
			}else {
				echo '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname"><em>'.$specurl.'</em></div><div class="errormsg">'.ERR_UPLOAD_WRONG_TYPE.'</div>';
			}
		}
	}else {
		$output = '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname"><em>'.$specurl.'</em></div><div class="errormsg">'.ERR_UPLOAD_NO_FILE.'</div>';
	}
}elseif ($_POST['type']=='drop') {
	// Drag & Drop upload handler
	$thumbsize=$_POST['thumb_size'];
	$isthumb=$_POST['is_thumb'];
	$name=escape_special_char($_POST['name']);
	$namec=htmlspecialchars($name);
	$wrongtype=false;
	$content=base64_decode(preg_replace('/data:image\/(jpeg|png|gif|svg\+xml);base64,/','',$_POST['file']));
	
	$temp = UPLOAD_DIR . '/working/' . $name . time();
	if(!file_put_contents($temp, $content)) {
		echo '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname">'.UI_ERROR_NAME.'<em>'.$namec.'</em></div><div class="errormsg">'.ERR_UPLOAD_CANT_WRITE.'(01)</div>';
	}elseif(filesize($temp)>the_size_limit()) {
		unlink($temp);
		echo '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname">'.UI_ERROR_NAME.'<em>'.$namec.'</em></div><div class="errormsg">'.ERR_UPLOAD_FORM_SIZE.'(02)</div>';
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
			$wrongtype=true;
		}
		if($wrongtype) {
			unlink($temp);
			echo '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname">'.UI_ERROR_NAME.'<em>'.$namec.'</em></div><div class="errormsg">'.ERR_UPLOAD_WRONG_TYPE.'(01)</div>';
		}else {
			$name=rename_if_exists($name, $uploads_dir);
			$path="$uploads_dir/$name";
			if(!copy($temp, $path)) {
				unlink($temp);
				echo '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname">'.UI_ERROR_NAME.'<em>'.$namec.'</em></div><div class="errormsg">'.ERR_UPLOAD_CANT_WRITE.'(02)</div>';
			}elseif(!$wrongtype) {
				unlink($temp);
				$thumb=make_thumb($uploads_dir,$thumbs_dir,$name,$thumbsize, $isthumb);
				result_format(array(array('error'=>UPLOAD_ERR_OK,'thumb'=>$thumb,'path'=>$uploads_dir,'name'=>$name)),'dropresult');
			}
		}
	}
}