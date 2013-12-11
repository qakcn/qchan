<?php

function __($message) {
	global $lang;
	if ($lang && isset($lang[$message])) {
		return $lang[$message];
	}else {
		return $message;
	}
}

function get_user_setting($configname) {
	if(isset($_POST[$configname])) {
		return $_POST[$configname];
	}else {
		return defined($configname) ? constant($configname) : false;
	}
}

function get_locale() {
	if(isset($_GET['lang'])) {
		setcookie('UI_LANG',$_GET['lang'],time()+60*60*24*365);
		return $_GET['lang'];
	}else if(isset($_COOKIE['UI_LANG'])) {
		return $_COOKIE['UI_LANG'];
	}else {
		return defined('UI_LANG') ? UI_LANG : 'en';
	}
}

function get_available_langs() {
	$langfiles = scandir('lang');
	$out = array();
	foreach ($langfiles as $key => $langfile) {
		if(preg_match('/.+\.json$/', $langfile)) {
			array_push($out, substr($langfile,0,-5));
		}
	}
	return $out;
}

function get_lang_name($locale) {
	$locales = array(
		"af"=>"Afrikaans",
		"af-ZA"=>"Afrikaans (Suid-Afrika)",
		"ar"=>"العربية",
		"ar-AE"=>"العربية (الإمارات العربية المتحدة)",
		"ar-BH"=>"العربية (البحرين)",
		"ar-DZ"=>"العربية (الجزائر)",
		"ar-EG"=>"العربية (مصر)",
		"ar-IQ"=>"العربية (العراق)",
		"ar-JO"=>"العربية (الأردن)",
		"ar-KW"=>"العربية (الكويت)",
		"ar-LB"=>"العربية (لبنان)",
		"ar-LY"=>"العربية (ليبيا)",
		"ar-MA"=>"العربية (المغرب)",
		"ar-OM"=>"العربية (عُمان)",
		"ar-QA"=>"العربية (قطر)",
		"ar-SA"=>"العربية (المملكة العربية السعودية)",
		"ar-SY"=>"العربية (سوريا)",
		"ar-TN"=>"العربية (تونس)",
		"ar-YE"=>"العربية (اليمن)",
		"az"=>"azərbaycanca",
		"az-AZ"=>"azərbaycanca (Azərbaycan)",
		"az-AZ"=>"azərbaycanca (Azərbaycan)",
		"be"=>"беларуская",
		"be-BY"=>"беларуская (Беларусь)",
		"bg"=>"български",
		"bg-BG"=>"български (България)",
		"bs-BA"=>"bosanski (Bosna i Hercegovina)",
		"ca"=>"català",
		"ca-ES"=>"català (Espanya)",
		"cs"=>"čeština",
		"cs-CZ"=>"čeština (Česká republika)",
		"cy"=>"Cymraeg",
		"cy-GB"=>"Cymraeg (Prydain Fawr)",
		"da"=>"dansk",
		"da-DK"=>"dansk (Danmark)",
		"de"=>"Deutsch",
		"de-AT"=>"Deutsch (Österreich)",
		"de-CH"=>"Deutsch (Schweiz)",
		"de-DE"=>"Deutsch (Deutschland)",
		"de-LI"=>"Deutsch (Liechtenstein)",
		"de-LU"=>"Deutsch (Luxemburg)",
		"el"=>"Ελληνικά",
		"el-GR"=>"Ελληνικά (Ελλάδα)",
		"en"=>"English",
		"en-AU"=>"English (Australia)",
		"en-BZ"=>"English (Belize)",
		"en-CA"=>"English (Canada)",
		"en-CB"=>"English (CB)",
		"en-GB"=>"English (United Kingdom)",
		"en-IE"=>"English (Ireland)",
		"en-JM"=>"English (Jamaica)",
		"en-NZ"=>"English (New Zealand)",
		"en-PH"=>"English (Philippines)",
		"en-TT"=>"English (Trinidad and Tobago)",
		"en-US"=>"English (United States)",
		"en-ZA"=>"English (South Africa)",
		"en-ZW"=>"English (Zimbabwe)",
		"eo"=>"esperanto",
		"es"=>"español",
		"es-AR"=>"español (Argentina)",
		"es-BO"=>"español (Bolivia)",
		"es-CL"=>"español (Chile)",
		"es-CO"=>"español (Colombia)",
		"es-CR"=>"español (Costa Rica)",
		"es-DO"=>"español (República Dominicana)",
		"es-EC"=>"español (Ecuador)",
		"es-ES"=>"español (España)",
		"es-ES"=>"español (España)",
		"es-GT"=>"español (Guatemala)",
		"es-HN"=>"español (Honduras)",
		"es-MX"=>"español (México)",
		"es-NI"=>"español (Nicaragua)",
		"es-PA"=>"español (Panamá)",
		"es-PE"=>"español (Perú)",
		"es-PR"=>"español (Puerto Rico)",
		"es-PY"=>"español (Paraguay)",
		"es-SV"=>"español (El Salvador)",
		"es-UY"=>"español (Uruguay)",
		"es-VE"=>"español (Venezuela)",
		"et"=>"eesti",
		"et-EE"=>"eesti (Eesti)",
		"eu"=>"euskara",
		"eu-ES"=>"euskara (Espainia)",
		"fa"=>"فارسی",
		"fa-IR"=>"فارسی (ایران)",
		"fi"=>"suomi",
		"fi-FI"=>"suomi (Suomi)",
		"fo"=>"føroyskt",
		"fo-FO"=>"føroyskt (Føroyar)",
		"fr"=>"français",
		"fr-BE"=>"français (Belgique)",
		"fr-CA"=>"français (Canada)",
		"fr-CH"=>"français (Suisse)",
		"fr-FR"=>"français (France)",
		"fr-LU"=>"français (Luxembourg)",
		"fr-MC"=>"français (Monaco)",
		"gl"=>"Galego",
		"gl-ES"=>"Galego (España)",
		"gu"=>"ગુજરાતી",
		"gu-IN"=>"ગુજરાતી (ભારત)",
		"he"=>"עברית",
		"he-IL"=>"עברית (ישראל)",
		"hi"=>"हिन्दी",
		"hi-IN"=>"हिन्दी (भारत)",
		"hr"=>"hrvatski",
		"hr-BA"=>"hrvatski (Bosna i Hercegovina)",
		"hr-HR"=>"hrvatski (Hrvatska)",
		"hu"=>"magyar",
		"hu-HU"=>"magyar (Magyarország)",
		"hy"=>"Հայերէն",
		"hy-AM"=>"Հայերէն (Հայաստանի Հանրապետութիւն)",
		"id"=>"Bahasa Indonesia",
		"id-ID"=>"Bahasa Indonesia (Indonesia)",
		"is"=>"íslenska",
		"is-IS"=>"íslenska (Ísland)",
		"it"=>"italiano",
		"it-CH"=>"italiano (Svizzera)",
		"it-IT"=>"italiano (Italia)",
		"ja"=>"日本語",
		"ja-JP"=>"日本語(日本)",
		"ka"=>"ქართული",
		"ka-GE"=>"ქართული (საქართველო)",
		"kk"=>"қазақ тілі",
		"kk-KZ"=>"қазақ тілі (Қазақстан)",
		"kn"=>"ಕನ್ನಡ",
		"kn-IN"=>"ಕನ್ನಡ (ಭಾರತ)",
		"ko"=>"한국어",
		"ko-KR"=>"한국어(대한민국)",
		"kok"=>"कोंकणी",
		"kok-IN"=>"कोंकणी (भारत)",
		"lt"=>"lietuvių",
		"lt-LT"=>"lietuvių (Lietuva)",
		"lv"=>"latviešu",
		"lv-LV"=>"latviešu (Latvija)",
		"mk"=>"македонски",
		"mk-MK"=>"македонски (Македонија)",
		"mr"=>"मराठी",
		"mr-IN"=>"मराठी (भारत)",
		"ms"=>"Bahasa Melayu",
		"ms-BN"=>"Bahasa Melayu (Brunei)",
		"ms-MY"=>"Bahasa Melayu (Malaysia)",
		"mt"=>"Malti",
		"mt-MT"=>"Malti (Malta)",
		"nb"=>"norsk bokmål",
		"nb-NO"=>"norsk bokmål (Norge)",
		"nl"=>"Nederlands",
		"nl-BE"=>"Nederlands (België)",
		"nl-NL"=>"Nederlands (Nederland)",
		"nn-NO"=>"nynorsk (Noreg)",
		"pa"=>"ਪੰਜਾਬੀ",
		"pa-IN"=>"ਪੰਜਾਬੀ (ਭਾਰਤ)",
		"pl"=>"polski",
		"pl-PL"=>"polski (Polska)",
		"pt"=>"português",
		"pt-BR"=>"português (Brasil)",
		"pt-PT"=>"português (Portugal)",
		"ro"=>"română",
		"ro-RO"=>"română (România)",
		"ru"=>"русский",
		"ru-RU"=>"русский (Россия)",
		"sk"=>"slovenčina",
		"sk-SK"=>"slovenčina (Slovenská republika)",
		"sl"=>"slovenščina",
		"sl-SI"=>"slovenščina (Slovenija)",
		"sq"=>"shqipe",
		"sq-AL"=>"shqipe (Shqipëria)",
		"sr-BA"=>"Српски (Босна и Херцеговина)",
		"sr-BA"=>"Српски (Босна и Херцеговина)",
		"sr-SP"=>"Српски (SP)",
		"sr-SP"=>"Српски (SP)",
		"sv"=>"svenska",
		"sv-FI"=>"svenska (Finland)",
		"sv-SE"=>"svenska (Sverige)",
		"sw"=>"Kiswahili",
		"sw-KE"=>"Kiswahili (Kenya)",
		"ta"=>"தமிழ்",
		"ta-IN"=>"தமிழ் (இந்தியா)",
		"te"=>"తెలుగు",
		"te-IN"=>"తెలుగు (భారత దేశం)",
		"th"=>"ไทย",
		"th-TH"=>"ไทย (ไทย)",
		"tr"=>"Türkçe",
		"tr-TR"=>"Türkçe (Türkiye)",
		"uk"=>"українська",
		"uk-UA"=>"українська (Україна)",
		"ur"=>"اردو",
		"ur-PK"=>"اردو (پاکستان)",
		"uz"=>"Ўзбек",
		"uz-UZ"=>"Ўзбек (Ўзбекистон)",
		"uz-UZ"=>"Ўзбек (Ўзбекистон)",
		"vi"=>"Tiếng Việt",
		"vi-VN"=>"Tiếng Việt (Việt Nam)",
		"zh"=>"中文",
		"zh-CN"=>"中文（中国）",
		"zh-HK"=>"中文（中華人民共和國香港特別行政區）",
		"zh-MO"=>"中文（中華人民共和國澳門特別行政區）",
		"zh-SG"=>"中文（新加坡）",
		"zh-TW"=>"中文（台灣）",
		"zh-Hans"=>"中文（简体中文）",
		"zh-Hant"=>"中文（繁體中文）",
		"zu"=>"isiZulu",
		"zu-ZA"=>"isiZulu (iNingizimu Afrika)"
	);
	return $locales[$locale];
}

function get_langlist() {
	$langlist = '';
	$langs = get_available_langs();
	foreach($langs as $lang) {
		$langlist .= sprintf('<li><a href="?lang=%s">%s</a></li>', $lang, get_lang_name($lang)) . "\n";
	}
	return $langlist;
}

function load_lang() {
	$locale = get_locale();
	$langfiles = get_available_langs();
	while($langfile = array_pop($langfiles)) {
		if($langfile==$locale || $langfile==substr($locale,0,2)) {
			return json_decode(file_get_contents('lang/' . $langfile . '.json'),true);
		}else if(substr_compare($langfile,$locale,0,2)==0) {
			$remember = $langfile;
		}
	}
	return isset($remember) ? json_decode(file_get_contents('lang/' . $remember),true) : false;
}

function is_mobile() {
	global $ismobile;
	if(!isset($_GET['mobile']) && $ismobile->isMobile() && !$ismobile->isTablet() || isset($_GET['mobile']) && $_GET['mobile']!='no') {
		return true;
	}else {
		return false;
	}
}

function load_mobile($type='phone') {
	if($type=='phone') {
		require 'phone.php';
	}
}

function load_normal($results=false) {
	require 'normal.php';
}

function get_url($cdn=false){
	if($cdn && defined('CDN_LIST')) {
		$cdnlist = explode(',',CDN_LIST);
		$rand_key = array_rand($cdnlist);
		return 'http://' . $cdnlist[$rand_key] . '/';
	}else {
		return preg_replace('/(.*\/)(.*?\.php|.*?\?.+)$/', '\1', 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	}
}

function check_apikey() {
	if(isset($_SERVER['HTTP_REFERER']) && preg_match('/'.str_replace('.', '\.', $_SERVER['SERVER_NAME']).'/', $_SERVER['HTTP_REFERER'])) {
		return true;
	}else if(isset($_GET['apikey']) && preg_match('/[0-9a-f]{64}/',$_GET['apikey'])) {
		if(file_exists('apikey/' . $_GET['apikey'] . '.php')) {
			require 'apikey/' . $_GET['apikey'] . '.php';
			if($apikey['type']=='web' && preg_match('/'.str_replace('.', '\.', $apikey['referer']).'/', $_SERVER['HTTP_REFERER'])) {
				return true;
			}else if($apikey['type']=='app') {
				return true;
			}else {
				return false;
			}
		}else {
			return false;
		}
	}else {
		return false;
	}
}

function return_bytes($val) {
	$val = trim($val);
	$last = strtolower($val[strlen($val)-1]);
	switch($last) {
		case 't':
			$val *= 1024;
		case 'g':
			$val *= 1024;
		case 'm':
			$val *= 1024;
		case 'k':
			$val *= 1024;
	}
	return $val;
}

function get_size_limit() {
	$postsize = return_bytes(ini_get('post_max_size'));
	$filesize = return_bytes(ini_get('upload_max_filesize'));
	$siteset = return_bytes(defined(SIZE_LIMIT) ? SIZE_LIMIT : '1T');
	
	return min($postsize, $filesize, $siteset);
}

function get_upload_count(){
	return defined('UPLOAD_COUNT') ? UPLOAD_COUNT : 3;
}

// Setup path for upload
function setup_dir() {
	$year=date('Y');$month=date('m');
	if(!file_exists(UPLOAD_DIR))
		mkdir(UPLOAD_DIR) || exit('Cannot write to upload directory.');
	if(!file_exists(UPLOAD_DIR . '/' . $year))
		mkdir(UPLOAD_DIR . '/' . $year);
	if(!file_exists(UPLOAD_DIR . '/' . $year .'/'. $month))
		mkdir(UPLOAD_DIR . '/' . $year .'/'. $month);
	$uploads_dir=UPLOAD_DIR . '/' . $year .'/'. $month;
	if(!file_exists(UPLOAD_DIR . '/working'))
		mkdir(UPLOAD_DIR . '/working');
	if(!file_exists(UPLOAD_DIR . '/hash'))
		mkdir(UPLOAD_DIR . '/hash');
	if(!file_exists(THUMB_DIR))
		mkdir(THUMB_DIR) || exit('Cannot write to thumbnail directory.');
	if(!file_exists(THUMB_DIR . '/' . $year))
		mkdir(THUMB_DIR . '/' . $year);
	if(!file_exists(THUMB_DIR . '/' . $year .'/'. $month))
		mkdir(THUMB_DIR . '/' . $year .'/'. $month);
	$thumbs_dir=THUMB_DIR . '/' . $year .'/'. $month;
	
	return array($uploads_dir,$thumbs_dir);
}

function url_handler() {
	list($uploads_dir,$thumbs_dir) = setup_dir();
	
	$url = $_POST['url'];
	$name = escape_special_char(basename($url));
	
	$result = array('qid'=>$_POST['qid']);
	$host = get_url(true);
	
	if(remote_filesize($url) > get_size_limit()) {
		$result['status'] = 'failed';
		$result['err'] = 'size_limit';
	}else if($content=@file_get_contents($url)) {
		$temp = UPLOAD_DIR . '/working/' . $name . time();
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
				if(!copy($temp, $path)) {
					$result['status'] = 'failed';
					$result['err'] = 'write_prohibited';
				}else {
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

function file_handler() {
	list($uploads_dir,$thumbs_dir) = setup_dir();
	$files = $_FILES['files'];
	$results=[];

	foreach($files['error'] as $key => $error) {
		$result = isset($_POST['qid']) ? array('qid'=>$_POST['qid']) : array();
		$name =  escape_special_char($files['name'][$key]);
		$host = get_url(true);

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
						if(!move_uploaded_file($temp, $path)) {
							$result['status'] = 'failed';
							$result['err'] = 'write_prohibited';
							
						}else {
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
	while(file_exists("$dir/$name")){
		$name = preg_replace('/(\(\d*\))?\.(' . SUPPORT_TYPE . ')$/i', '(' .$num . ').\2', $name);
		$num++;
	}
	return $name;
}

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

// Escape special character
function escape_special_char($name) {
	return str_replace(array('#','?','=','&','/','\\',';','<','>','[',']','%','@'), '_', $name);
}

function make_thumb($name, $path, $thumbs_dir) {
	$height = 200;
	$width = 1000;
	$return = array('generated'=>false);
	if(!$imgInfo=getimagesize($path)) {
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
		$image = $readf($path);
		
		// Create alpha channel for png
		if($type == IMAGETYPE_PNG) {
			if(!imagealphablending($image_p, false) || !imagesavealpha($image_p,true) || !($transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127)) || !imagefill($image_p, 0, 0, $transparent)) {
				return $return;
			}
		}

		// Resize image
		if(!imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig)) {
			return $return;
		}
		
		// Make transparent for gif
		if($type == IMAGETYPE_GIF) {
			if(!($bgcolor = imagecolorallocate($image_p,0,0,0) && $imagecolortransparent($image_p, $bgcolor))) {
				return $return;
			}
		}
		
		if(!$writef($image_p, "$thumbs_dir/$name")) {
			return $return;
		}
		imagedestroy($image);imagedestroy($image_p);
		$return['generated'] = true;
		$return['path'] = "$thumbs_dir/$name";
	}
	return $return;
}

// Check if file is duplicate
function is_duplicate($file) {
	if(defined('DUPLICATE_FILE_CHECK') && DUPLICATE_FILE_CHECK) {
		$hash = hash_file('sha256', $file);
		$wd = UPLOAD_DIR . '/hash';
		for($i=0;$i<5;$i++) {
			$wd .= '/' . substr($hash, $i*2+0, 2);
		}
		$wd .= '/' . substr($hash, 10, 2);
		if(file_exists($wd)) {
			$re = file_get_contents($wd);
			if(preg_match('/^'.$hash.';(.+?);(.+?);(.+?)$/',$re,$match)) {
				$name = $match[1];
				$path = $match[2];
				$thumb = $match[3];
				
				if($thumb!='none') {
					list($width, $height) = getimagesize($thumb);
				}else {
					$width = 200;
					$height = 1000;
					list($width_orig, $height_orig) = getimagesize($path);
					if($height_orig <= $height && $width_orig <= $width) {
						$width = $width_orig;
						$height = $height_orig;
					}else{
						$ratio_orig = $width_orig/$height_orig;
						if ($width/$height > $ratio_orig) {
							$width = $height*$ratio_orig;
						}else {
							$height = $width/$ratio_orig;
						}
					}
				}
				return array('name' => $name, 'path' => $path, 'thumb' => $thumb, 'width' => $width, 'height' => $height);
			}else {
				return false;
			}
		}else {
			return false;
		}
	}else {
		return false;
	}
}

// Generate hash file for duplicate check
function duplicate_hash($name, $path, $thumb) {
	if(defined('DUPLICATE_FILE_CHECK') && DUPLICATE_FILE_CHECK) {
		$hash = hash_file('sha256', $path);
		$wd = UPLOAD_DIR . '/hash';
		for($i=0;$i<5;$i++) {
			$wd .= '/' . substr($hash, $i*2+0, 2);
			if(!file_exists($wd)) {
				mkdir($wd);
			}
		}
		$wd .= '/' . substr($hash, 10, 2);
		if(file_put_contents($wd, $hash . ';' . $name . ';' . $path . ';' . ($thumb['generated'] ? $thumb['path'] : 'none') . "\n", FILE_APPEND | LOCK_EX) !== false) {
			return true;
		}else {
			return false;
		}
	}else {
		return true;
	}
}
?>