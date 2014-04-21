<?php

function __($message) {
	global $lang;
	if ($lang && isset($lang[$message])) {
		return $lang[$message];
	}else {
		return $message;
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
	$langfiles = scandir(ABSPATH.'/lang');
	$out = array();
	foreach ($langfiles as $key => $langfile) {
		if(preg_match('/.+\.json$/', $langfile)) {
			array_push($out, substr($langfile,0,-5));
		}
	}
	return $out;
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
			return json_decode(file_get_contents(ABSPATH.'/lang/' . $langfile . '.json'),true);
		}else if(substr_compare($langfile,$locale,0,2)==0) {
			$remember = $langfile;
		}
	}
	return isset($remember) ? json_decode(file_get_contents(ABSPATH.'/lang/' . $remember . '.json'),true) : false;
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

?>