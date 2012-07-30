<?php
 /**
  * This file handle the configurations and functions.
  */
  
// Load configurations
if(file_exists('config.php')) {
	require 'config.php';
}else {
	die('No config.php found! Copy or rename config-template.php to config.php and edit it, then retry.');
}

// Load language file
require 'lang/' . LANG . '.php';

// Some system settings
define('SUPPORT_TYPE', 'jpg|jpeg|jpe|jfif|jfi|jif|gif|png|svg');
define('QCHAN_VER', '0.7');
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
	return preg_replace('/(.*\/)(.*?\.php|.*?\?.+)$/', '\1', 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
}

// Output the file size limit
function the_size_limit(){
	return (SIZE_LIMIT * 1048576);
}

//Get remote file size
function remote_filesize($url){  
 $url = parse_url($url); 
 if($fp = @fsockopen($url['host'],empty($url['port'])?80:$url['port'],$error)){
  fputs($fp,"GET ".(empty($url['path'])?'/':$url['path'])." HTTP/1.1\r\n");
  fputs($fp,"Host:$url[host]\r\n\r\n");
  while(!feof($fp)){
   $tmp = fgets($fp);
   if(trim($tmp) == ''){
    break;
   }elseif(preg_match('/Content-Length:(.*)/si',$tmp,$arr)){
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

// Generate Thumbnail image
function make_thumb($uploads_dir, $thumbs_dir, $name, $size, $isthumb){
	if(!$isthumb) return false;
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
		
		// Create alpha channel for png
		if($imgInfo[2] == IMAGETYPE_PNG) {
			imagealphablending($image_p, false);
			imagesavealpha($image_p,true);
			$transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
			imagefill($image_p, 0, 0, $transparent);
		}
					
		// Resize image
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		
		// Make transparent for gif
		if($imgInfo[2] == IMAGETYPE_GIF) {
			$bgcolor = ImageColorAllocate($image_p,0,0,0);   
			$bgcolor = ImageColorTransparent($image_p,$bgcolor) ;
		}
		
		$writef($image_p, "$thumbs_dir/$name");
		imagedestroy($image);imagedestroy($image_p);
		return $thumbs_dir;
	}else {
		return false;
	}
}

// Setup path for upload
function setup_dir() {
	$year=date('Y');$month=date('m');
	if(!file_exists(UPLOAD_DIR))
		mkdir(UPLOAD_DIR);
	if(!file_exists(UPLOAD_DIR . '/' . $year))
		mkdir(UPLOAD_DIR . '/' . $year);
	if(!file_exists(UPLOAD_DIR . '/' . $year .'/'. $month))
		mkdir(UPLOAD_DIR . '/' . $year .'/'. $month);
	$uploads_dir=UPLOAD_DIR . '/' . $year .'/'. $month;
	if(!file_exists(UPLOAD_DIR . '/working'))
		mkdir(UPLOAD_DIR . '/working');
	if(!file_exists(THUMB_DIR))
		mkdir(THUMB_DIR);
	if(!file_exists(THUMB_DIR . '/' . $year))
		mkdir(THUMB_DIR . '/' . $year);
	if(!file_exists(THUMB_DIR . '/' . $year .'/'. $month))
		mkdir(THUMB_DIR . '/' . $year .'/'. $month);
	$thumbs_dir=THUMB_DIR . '/' . $year .'/'. $month;
	
	return array($uploads_dir,$thumbs_dir);
}

// Rename file if exists
function rename_if_exists($name, $dir) {
	$num = 1;
	while(file_exists("$dir/$name")){
		$name = preg_replace('/(\(\d*\))?\.(' . SUPPORT_TYPE . ')$/i', '(' .$num . ').\2', $name);
		$num++;
	}
	return $name;
}

// Escape special character
function escape_special_char($name){
	return str_replace(array('#','?','=','&','/','\\'), '_', $name);
}

// Save the uploaded files
function save_upload_files($files, $thumb_size, $is_thumb){
	list($uploads_dir,$thumbs_dir) = setup_dir();
	$err=array();
	
	// Loop every uploaded file
	foreach ($files['files']['error'] as $key => $error) {
		$name =  escape_special_char($files['files']['name'][$key]);
		if($error==UPLOAD_ERR_OK) {
			
			// Refuse to save unsupport filetype
			if(!preg_match('/\.(' . SUPPORT_TYPE . ')$/i', $name)) {
				$err[$key]['name']=$name;
				$err[$key]['error']=10;	
				continue;
			}
			$tmp_name = $files['files']['tmp_name'][$key];
			
			if(filesize($tmp_name)>the_size_limit()) {
				$err[$key]['name']=$name;
				$err[$key]['error']=UPLOAD_ERR_FORM_SIZE;	
				continue;
			}
			$name = rename_if_exists($name, $uploads_dir);
			
			// Save file
			if(!move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
				$err[$key]['name']=$name;
				$err[$key]['error']=9;
			}else{
				$err[$key]['error']=UPLOAD_ERR_OK;
				$err[$key]['thumb']=make_thumb($uploads_dir,$thumbs_dir,$name,$thumb_size, $is_thumb);
				$err[$key]['name']=$name;
				$err[$key]['path']=$uploads_dir;
			}
		}else {
			// Return error code
			$err[$key]['name']=$name;
			$err[$key]['error']=$error;
		}
	}
	return $err;
}

function result_format($files,$type) {
	$thumbfmt=<<<THUMBFMT
<div class="preview">
<a title="%s" href="%s" target="_blank"><img src="%s" alt="%s"></a>
</div>
<div class="links">
<p class="linkp"><label>%s</label><input class="url" type="text" readonly value="%s"></p>
<p class="linkp"><label>%s</label><input class="url" type="text" readonly value="[IMG]%s[/IMG]"></p>
<p class="linkp"><label>%s</label><input class="url" type="text" readonly value="[URL=%s][IMG]%s[/IMG][/URL]"></p>
<p class="linkp"><label>%s</label><input class="url" type="text" readonly value="&lt;img src=&quot;%s&quot; alt=&quot;%s&quot; title=&quot;%s&quot; /&gt;"></p>
<p class="linkp"><label>%s</label><input class="url" type="text" readonly value="&lt;a href=&quot;%s&quot; title=&quot;%s&quot;&gt;&lt;img src=&quot;%s&quot; alt=&quot;%s&quot; /&gt;&lt/a&gt;"></p>
</div>
THUMBFMT;
	$nothumbfmt=<<<NOTHUMBFMT
<div class="preview">
<img src="%s" title="%s" alt="%s">
</div>
<div class="links">
<p class="linkp"><label>%s</label><input class="url" type="text" readonly value="%s"></p>
<p class="linkp"><label>%s</label><input class="url" type="text" readonly value="[IMG]%s[/IMG]"></p>
<p class="linkp"><label>%s</label><input class="url" type="text" readonly value="&lt;img src=&quot;%s&quot; alt=&quot;%s&quot; title=&quot;%s&quot; /&gt;"></p>
</div>
NOTHUMBFMT;
	foreach($files as $file) {
		if($file['error']==UPLOAD_ERR_OK)	{
			$name=htmlspecialchars($file['name']);
			$namec='/' . rawurlencode($file['name']);
			$path=the_url() . $file['path'] . $namec;
			$thumb=$file['thumb'];
			echo '<li class="'.$type.'">';
			if($thumb) {
				$thumb=the_url() . $thumb . $namec;
				printf($thumbfmt, $name, $path, $thumb, $name, UI_RESULT_ORIG, $path, UI_RESULT_ORIGBB, $path, UI_RESULT_THBB, $path, $thumb, UI_RESULT_ORIGHTML, $path, $name, $name, UI_RESULT_THHTML, $path, $name, $thumb, $name);
			}else {
				printf($nothumbfmt, $path, $name, $name, UI_RESULT_ORIG, $path, UI_RESULT_ORIGBB, $path, UI_RESULT_ORIGHTML, $path, $name, $name);
			}
		}else {
			$theerror=the_upload_error($file['error']);
			$name=$file['name'];
			echo '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname">'.UI_ERROR_NAME.'<em>'.$name.'</em></div><div class="errormsg">'.$theerror.'</div>';
		}
		echo "</li>";
	}
}

//Check file MIME type
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

// Check if mobile device
function is_mobile() {
	$useragent=$_SERVER['HTTP_USER_AGENT'];
	return preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
}

// URL upload handler
function url_handler($thumb_size, $is_thumb, $addr) {
	list($uploads_dir,$thumbs_dir) = setup_dir();

	$url=trim($addr);
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
					$thumb=make_thumb($uploads_dir,$thumbs_dir,$name,$thumb_size, $is_thumb);
					result_format(array(array('error'=>UPLOAD_ERR_OK,'thumb'=>$thumb,'path'=>$uploads_dir,'name'=>$name)),'urlresult');
				}
			}
		}
	}else {
		$output = '<li class="imgfail"><div class="errortitle">'.UI_ERROR_TITLE.'</div><div class="errorname"><em>'.$specurl.'</em></div><div class="errormsg">'.ERR_UPLOAD_NO_FILE.'</div>';
	}
}

function grab_handler($addr) {
	list($uploads_dir,$thumbs_dir) = setup_dir();

	$url=trim($addr);
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
}

// Drag & Drop upload handler
function dragdrop_handler($thumb_size, $is_thumb, $name, $file) {
	list($uploads_dir,$thumbs_dir) = setup_dir();

	$name=escape_special_char($name);
	$namec=htmlspecialchars($name);
	$wrongtype=false;
	$content=base64_decode(preg_replace('/data:image\/(jpeg|png|gif|svg\+xml);base64,/','',$file));
	
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
				$thumb=make_thumb($uploads_dir,$thumbs_dir,$name,$thumb_size, $is_thumb);
				result_format(array(array('error'=>UPLOAD_ERR_OK,'thumb'=>$thumb,'path'=>$uploads_dir,'name'=>$name)),'dropresult');
			}
		}
	}
}
?>