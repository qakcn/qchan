<?php
 /**
  * This file handle the configurations and functions.
  */

// Load configurations
require './config.php';

// Load language file
require './lang/' . LANG . '.php';

// Some system settings
define('SUPPORT_TYPE', 'jpg|jpeg|jpe|jifi|jif|gif|png|webp|svg');
define('QCHAN_VER', '0.6');
if(function_exists('date_default_timezone_set'))
	date_default_timezone_set(TIMEZONE);

// Output the site title
function the_title($full=false) {
	if ($full)
		return SITE_TITLE . ' - ' . INFO_NAME;
	else
		return SITE_TITLE;
}

// Output the time
function the_time($format='M j, Y') {
	return date($format);
}

// Output site url
function the_url(){
	return preg_replace('/(.*\/).*?\.php$/', '\1', 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
}

// Output the file size limit
function the_size_limit(){
	return (SIZE_LIMIT * 1048576);
}

function remote_filesize($url){  
 $url = parse_url($url); 
 if($fp = @fsockopen($url['host'],empty($url['port'])?80:$url['port'],$error)){
  fputs($fp,"GET ".(empty($url['path'])?'/':$url['path'])." HTTP/1.1\r\n");
  fputs($fp,"Host:$url[host]\r\n\r\n");
  while(!feof($fp)){
   $tmp = fgets($fp);
   if(trim($tmp) == ''){
    break;
   }else if(preg_match('/Content-Length:(.*)/si',$tmp,$arr)){
    return trim($arr[1]);
   }
  }
  return null;
 }else{
  return null;
 }
}

// Output the uoload errors
function the_upload_error($code){
	switch($code){
		case UPLOAD_ERR_INI_SIZE:
			return ERR_UPLOAD_INI_SIZE;
			break;
		case UPLOAD_ERR_FORM_SIZE:
			return ERR_UPLOAD_FORM_SIZE;
			break;
		case UPLOAD_ERR_PARTIAL:
			return ERR_UPLOAD_PARTIAL;
			break;
		case UPLOAD_ERR_NO_FILE:
			return ERR_UPLOAD_NO_FILE;
			break;
		case UPLOAD_ERR_NO_TMP_DIR:
			return ERR_UPLOAD_NO_TMP_DIR;
			break;
		case UPLOAD_ERR_CANT_WRITE:
			return ERR_UPLOAD_CANT_WRITE;
			break;
		case UPLOAD_ERR_EXTENSION:
			return ERR_UPLOAD_EXTENSION;
			break;
		case 9:
			return ERR_UPLOAD_FAIL_SAVE;
			break;
		case 10:
			return ERR_UPLOAD_WRONG_TYPE;
			break;
	}
}

function make_thumb($uploads_dir, $thumbs_dir, $name, $size){
	if($_POST['is_thumb']=='no') return false;
	switch(true){
		case $size<THUMB_MIN: $size=THUMB_MIN; break;
		case $size>THUMB_MAX: $size=THUMB_MAX; break;
	}
	$width = $height = (int)$size;
	$imgInfo=getimagesize("$uploads_dir/$name");
	
	// Stop making thumbnail if file not jpeg, gif or png
	switch($imgInfo[2]){
		case IMAGETYPE_GIF:$readf="imagecreatefromgif";$writef="imagegif";$makethumb=true;break;
		case IMAGETYPE_JPEG:$readf="imagecreatefromjpeg";$writef="imagejpeg";$makethumb=true;break;
		case IMAGETYPE_PNG:$readf="imagecreatefrompng";$writef="imagepng";$makethumb=true;break;
		case IMAGETYPE_PNG:$readf="imagecreatefrompng";$writef="imagepng";$makethumb=true;break;
		default:$makethumb=false;
	}
				
	list($width_orig, $height_orig) = $imgInfo;
				
	// Stop making thumbnail if image size is less than thumbnail size
	if($width_orig <= $width and $height_orig <= $height)
		$makethumb=false;
				
	// Make the thumbnail
	if($makethumb){
		$ratio_orig = $width_orig/$height_orig;
		if ($width/$height > $ratio_orig)
			$width = $height*$ratio_orig;
		else
			$height = $width/$ratio_orig;
		$image_p = imagecreatetruecolor($width, $height);
		$image = $readf("$uploads_dir/$name");
		
		// Create alpha channel for gif and png
		if($imgInfo[2] == IMAGETYPE_GIF OR $imgInfo[2] == IMAGETYPE_PNG) {
			imagealphablending($image_p, false);
			imagesavealpha($image_p,true);
			$transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
			imagefilledrectangle($image_p, 0, 0, $width, $height, $transparent);
		}
					
		// Resize image
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		$writef($image_p, "$thumbs_dir/$name");
		imagedestroy($image);imagedestroy($image_p);
		$namec=rawurlencode($name);
		return "$thumbs_dir/$namec";
	}else {
		return false;
	}
}

function setup_dir() {
	$year=date('Y');$month=date('m');
	if(!file_exists(UPLOAD_DIR))
		mkdir(UPLOAD_DIR);
	if(!file_exists(UPLOAD_DIR . '/' . $year))
		mkdir(UPLOAD_DIR . '/' . $year);
	if(!file_exists(UPLOAD_DIR . '/' . $year .'/'. $month))
		mkdir(UPLOAD_DIR . '/' . $year .'/'. $month);
	$uploads_dir=UPLOAD_DIR . '/' . $year .'/'. $month;
	if(!file_exists(THUMB_DIR))
		mkdir(THUMB_DIR);
	if(!file_exists(THUMB_DIR . '/' . $year))
		mkdir(THUMB_DIR . '/' . $year);
	if(!file_exists(THUMB_DIR . '/' . $year .'/'. $month))
		mkdir(THUMB_DIR . '/' . $year .'/'. $month);
	$thumbs_dir=THUMB_DIR . '/' . $year .'/'. $month;
	return array($uploads_dir,$thumbs_dir);
}

// Save the uploaded files
function save_upload_files(){
	list($uploads_dir,$thumbs_dir) = setup_dir();
	$err=array();
	
	// Loop every uploaded file
	foreach ($_FILES['files']['error'] as $key => $error) {
		$name = str_replace(array('#','?','=','&','/','\\'), '_', $_FILES['files']['name'][$key]);
		if($error==UPLOAD_ERR_OK) {
			
			// Refuse to save unsupport filetype
			if(!preg_match('/\.(' . SUPPORT_TYPE . ')$/i', $name)) {
				$err[$key]['name']=htmlspecialchars($name);
				$err[$key]['error']=10;	
				continue;
			}
			$tmp_name = $_FILES['files']['tmp_name'][$key];
			
			if(filesize($tmp_name)>the_size_limit()) {
				$err[$key]['name']=htmlspecialchars($name);
				$err[$key]['error']=UPLOAD_ERR_FORM_SIZE;	
				continue;
			}
			// Rename file if exists
			$num = 1;
			while(file_exists("$uploads_dir/$name")){
					$name = preg_replace('/(\(\d*\))?\.(' . SUPPORT_TYPE . ')$/i', '(' .$num . ').\2', $name);
					$num++;
			}
			
			// Save file
			if(!move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
				$err[$key]['name']=htmlspecialchars($name);
				$err[$key]['error']=9;
			}else{
				$namec=rawurlencode($name);
				$err[$key]['error']=UPLOAD_ERR_OK;
				if(is_null($_POST['thumb_size']) || !is_numeric($_POST['thumb_size'])) $size=THUMB_DEFAULT;
				else $size=$_POST['thumb_size'];
				$err[$key]['thumb']=make_thumb($uploads_dir,$thumbs_dir,$name,$size);
				$err[$key]['name']=htmlspecialchars($name);
				$err[$key]['path']="$uploads_dir/$namec";
			}
		}else {
			// Return error code
			$err[$key]['name']=htmlspecialchars($name);
			$err[$key]['error']=$error;
		}
	}
	return $err;
}

function result_format($files) {
	$thumbfmt=<<<THUMBFMT
<div class="preview">
<a title="%s" href="%s" target="_blank"><img src="%s" alt="%s"></a>
</div>
<div class="links">
<p><label>%s</label><input class="url" type="text" readonly value="%s"></p>
<p><label>%s</label><input class="url" type="text" readonly value="[IMG]%s[/IMG]"></p>
<p><label>%s</label><input class="url" type="text" readonly value="[URL=%s][IMG]%s[/IMG][/URL]"></p>
<p><label>%s</label><input class="url" type="text" readonly value="&lt;img src=&quot;%s&quot; alt=&quot;%s&quot; title=&quot;%s&quot; /&gt;"></p>
<p><label>%s</label><input class="url" type="text" readonly value="&lt;a href=&quot;%s&quot; title=&quot;%s&quot;&gt;&lt;img src=&quot;%s&quot; alt=&quot;%s&quot; /&gt;&lt/a&gt;"></p>
</div>
THUMBFMT;
	$nothumbfmt=<<<NOTHUMBFMT
<div class="preview">
<img src="%s" title="%s" alt="%s">
</div>
<div class="links">
<p><label>%s</label><input class="url" type="text" readonly value="%s"></p>
<p><label>%s</label><input class="url" type="text" readonly value="[IMG]%s[/IMG]"></p>
<p><label>%s</label><input class="url" type="text" readonly value="&lt;img src=&quot;%s&quot; alt=&quot;%s&quot; title=&quot;%s&quot; /&gt;"></p>
</div>
NOTHUMBFMT;
	foreach($files as $file) {
		if($file['error']==UPLOAD_ERR_OK)	{
			$name=$file['name'];
			$path=the_url() . $file['path'];
			$thumb=$file['thumb'];
			echo '<li class="imgok normalresult">';
			if($thumb) {
				$thumb=the_url() . $thumb;
				printf($thumbfmt, $name, $path, $thumb, $name, UI_RESULT_ORIG, $path, UI_RESULT_ORIGBB, $path, UI_RESULT_THBB, $path, $thumb, UI_RESULT_ORIGHTML, $path, $name, $name, UI_RESULT_THHTML, $path, $name, $thumb, $name);
			}else {
				printf($nothumbfmt, $path, $name, $name, UI_RESULT_ORIG, $path, UI_RESULT_ORIGBB, $path, UI_RESULT_ORIGHTML, $path, $name, $name);
			}
		}else {
			$theerror=the_upload_error($file['error']);
			echo '<li class="imgfail">';
			$name=$file['name'];
			echo '<div class="errortitle">'.UI_ERROR_TITLE.'</div>';
			echo '<div class="errorname">'.UI_ERROR_NAME.$name.'</div>';
			echo '<div class="errormsg">'.$theerror.'</div>';
		}
		echo "</li>";
	}
}

?>