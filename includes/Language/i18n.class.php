<?php

class i18n {
    static private $lang = array();
    static private $locale = array();
    static private $orig_locale = array();
    static private $valid_lang = array();

    static private $available = array(
        'af' => 'Afrikaans',
        'af-ZA' => 'Afrikaans (Suid-Afrika)',
        'ar' => 'العربية',
        'ar-AE' => 'العربية (الإمارات العربية المتحدة)',
        'ar-BH' => 'العربية (البحرين)',
        'ar-DZ' => 'العربية (الجزائر)',
        'ar-EG' => 'العربية (مصر)',
        'ar-IQ' => 'العربية (العراق)',
        'ar-JO' => 'العربية (الأردن)',
        'ar-KW' => 'العربية (الكويت)',
        'ar-LB' => 'العربية (لبنان)',
        'ar-LY' => 'العربية (ليبيا)',
        'ar-MA' => 'العربية (المغرب)',
        'ar-OM' => 'العربية (عُمان)',
        'ar-QA' => 'العربية (قطر)',
        'ar-SA' => 'العربية (المملكة العربية السعودية)',
        'ar-SY' => 'العربية (سوريا)',
        'ar-TN' => 'العربية (تونس)',
        'ar-YE' => 'العربية (اليمن)',
        'az' => 'azərbaycan',
        'az-AZ' => 'azərbaycan (Azərbaycan)',
        'be' => 'беларуская',
        'be-BY' => 'беларуская (Беларусь)',
        'bg' => 'български',
        'bg-BG' => 'български (България)',
        'bs-BA' => 'bosanski (Bosna i Hercegovina)',
        'ca' => 'català',
        'ca-ES' => 'català (Espanya)',
        'cs' => 'čeština',
        'cs-CZ' => 'čeština (Česká republika)',
        'cy' => 'Cymraeg',
        'cy-GB' => 'Cymraeg (Y Deyrnas Unedig)',
        'da' => 'dansk',
        'da-DK' => 'dansk (Danmark)',
        'de' => 'Deutsch',
        'de-AT' => 'Deutsch (Österreich)',
        'de-CH' => 'Deutsch (Schweiz)',
        'de-DE' => 'Deutsch (Deutschland)',
        'de-LI' => 'Deutsch (Liechtenstein)',
        'de-LU' => 'Deutsch (Luxemburg)',
        'el' => 'Ελληνικά',
        'el-GR' => 'Ελληνικά (Ελλάδα)',
        'en' => 'English',
        'en-AU' => 'English (Australia)',
        'en-BZ' => 'English (Belize)',
        'en-CA' => 'English (Canada)',
        'en-CB' => 'English (CB)',
        'en-GB' => 'English (United Kingdom)',
        'en-IE' => 'English (Ireland)',
        'en-JM' => 'English (Jamaica)',
        'en-NZ' => 'English (New Zealand)',
        'en-PH' => 'English (Philippines)',
        'en-TT' => 'English (Trinidad and Tobago)',
        'en-US' => 'English (United States)',
        'en-ZA' => 'English (South Africa)',
        'en-ZW' => 'English (Zimbabwe)',
        'eo' => 'esperanto',
        'es' => 'español',
        'es-AR' => 'español (Argentina)',
        'es-BO' => 'español (Bolivia)',
        'es-CL' => 'español (Chile)',
        'es-CO' => 'español (Colombia)',
        'es-CR' => 'español (Costa Rica)',
        'es-DO' => 'español (República Dominicana)',
        'es-EC' => 'español (Ecuador)',
        'es-ES' => 'español (España)',
        'es-GT' => 'español (Guatemala)',
        'es-HN' => 'español (Honduras)',
        'es-MX' => 'español (México)',
        'es-NI' => 'español (Nicaragua)',
        'es-PA' => 'español (Panamá)',
        'es-PE' => 'español (Perú)',
        'es-PR' => 'español (Puerto Rico)',
        'es-PY' => 'español (Paraguay)',
        'es-SV' => 'español (El Salvador)',
        'es-UY' => 'español (Uruguay)',
        'es-VE' => 'español (Venezuela)',
        'et' => 'eesti',
        'et-EE' => 'eesti (Eesti)',
        'eu' => 'euskara',
        'eu-ES' => 'euskara (Espainia)',
        'fa' => 'فارسی',
        'fa-IR' => 'فارسی (ایران)',
        'fi' => 'suomi',
        'fi-FI' => 'suomi (Suomi)',
        'fo' => 'føroyskt',
        'fo-FO' => 'føroyskt (Føroyar)',
        'fr' => 'français',
        'fr-BE' => 'français (Belgique)',
        'fr-CA' => 'français (Canada)',
        'fr-CH' => 'français (Suisse)',
        'fr-FR' => 'français (France)',
        'fr-LU' => 'français (Luxembourg)',
        'fr-MC' => 'français (Monaco)',
        'gl' => 'galego',
        'gl-ES' => 'galego (España)',
        'gu' => 'ગુજરાતી',
        'gu-IN' => 'ગુજરાતી (ભારત)',
        'he' => 'עברית',
        'he-IL' => 'עברית (ישראל)',
        'hi' => 'हिंदी',
        'hi-IN' => 'हिंदी (भारत)',
        'hr' => 'hrvatski',
        'hr-BA' => 'hrvatski (Bosna i Hercegovina)',
        'hr-HR' => 'hrvatski (Hrvatska)',
        'hu' => 'magyar',
        'hu-HU' => 'magyar (Magyarország)',
        'hy' => 'հայերեն',
        'hy-AM' => 'հայերեն (Հայաստան)',
        'id' => 'Bahasa Indonesia',
        'id-ID' => 'Bahasa Indonesia (Indonesia)',
        'is' => 'íslenska',
        'is-IS' => 'íslenska (Ísland)',
        'it' => 'italiano',
        'it-CH' => 'italiano (Svizzera)',
        'it-IT' => 'italiano (Italia)',
        'ja' => '日本語',
        'ja-JP' => '日本語 (日本)',
        'ka' => 'ქართული',
        'ka-GE' => 'ქართული (საქართველო)',
        'kk' => 'қазақ тілі',
        'kk-KZ' => 'қазақ тілі (Қазақстан)',
        'kn' => 'ಕನ್ನಡ',
        'kn-IN' => 'ಕನ್ನಡ (ಭಾರತ)',
        'ko' => '한국어',
        'ko-KR' => '한국어(대한민국)',
        'kok' => 'कोंकणी',
        'kok-IN' => 'कोंकणी (भारत)',
        'lt' => 'lietuvių',
        'lt-LT' => 'lietuvių (Lietuva)',
        'lv' => 'latviešu',
        'lv-LV' => 'latviešu (Latvija)',
        'mk' => 'македонски',
        'mk-MK' => 'македонски (Македонија)',
        'mr' => 'मराठी',
        'mr-IN' => 'मराठी (भारत)',
        'ms' => 'Bahasa Melayu',
        'ms-BN' => 'Bahasa Melayu (Brunei)',
        'ms-MY' => 'Bahasa Melayu (Malaysia)',
        'mt' => 'Malti',
        'mt-MT' => 'Malti (Malta)',
        'nb' => 'norsk bokmål',
        'nb-NO' => 'norsk bokmål (Norge)',
        'nl' => 'Nederlands',
        'nl-BE' => 'Nederlands (België)',
        'nl-NL' => 'Nederlands (Nederland)',
        'nn-NO' => 'nynorsk (Noreg)',
        'pa' => 'ਪੰਜਾਬੀ',
        'pa-IN' => 'ਪੰਜਾਬੀ (ਭਾਰਤ)',
        'pl' => 'polski',
        'pl-PL' => 'polski (Polska)',
        'pt' => 'português',
        'pt-BR' => 'português (Brasil)',
        'pt-PT' => 'português (Portugal)',
        'ro' => 'română',
        'ro-RO' => 'română (România)',
        'ru' => 'русский',
        'ru-RU' => 'русский (Россия)',
        'sk' => 'slovenčina',
        'sk-SK' => 'slovenčina (Slovensko)',
        'sl' => 'slovenščina',
        'sl-SI' => 'slovenščina (Slovenija)',
        'sq' => 'Shqip',
        'sq-AL' => 'Shqip (Shqipëri)',
        'sr-BA' => 'Српски (Босна и Херцеговина)',
        'sr-SP' => 'Српски (SP)',
        'sv' => 'svenska',
        'sv-FI' => 'svenska (Finland)',
        'sv-SE' => 'svenska (Sverige)',
        'sw' => 'Kiswahili',
        'sw-KE' => 'Kiswahili (Kenya)',
        'ta' => 'தமிழ்',
        'ta-IN' => 'தமிழ் (இந்தியா)',
        'te' => 'తెలుగు',
        'te-IN' => 'తెలుగు (భారత దేశం)',
        'th' => 'ไทย',
        'th-TH' => 'ไทย (ไทย)',
        'tr' => 'Türkçe',
        'tr-TR' => 'Türkçe (Türkiye)',
        'uk' => 'українська',
        'uk-UA' => 'українська (Україна)',
        'ur' => 'اردو',
        'ur-PK' => 'اردو (پاکستان)',
        'uz' => 'oʻzbekcha',
        'uz-UZ' => 'oʻzbekcha (Oʻzbekiston)',
        'vi' => 'Tiếng Việt',
        'vi-VN' => 'Tiếng Việt (Việt Nam)',
        'zh' => '中文',
        'zh-CN' => '中文（中国）',
        'zh-HK' => '中文（中華人民共和國香港特別行政區）',
        'zh-MO' => '中文（中華人民共和國澳門特別行政區）',
        'zh-SG' => '中文（新加坡）',
        'zh-TW' => '中文（台灣）',
        'zh-Hans' => '中文（简体中文）',
        'zh-Hant' => '中文（繁體字）',
        'zu' => 'isiZulu',
        'zu-ZA' => 'isiZulu (i-South Africa)',
    );

    static public function getLocaleName($locale) {
        if(array_key_exists($locale, self::$available)) {
            return self::$available[$locale];
        }else {
            return false;
        }
    }

    static public function parseLang(array $lang, $locale, $domain = 'main') {
        if(!is_null(self::$lang[$domain]) && self::$lang[$doamin]->locale == $locale) {
            if(!self::$lang[$domain]->merge(new Language($lang, $locale))) {
                return false;
            }
        }else {
            if(!self::getLocaleName($locale)) {
                return false;
            }
            self::$lang[$domain] = new Language($lang, $locale);
        }
        return true;
    }

    static private function getAcceptLang() {
        $http_lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

        $lang = array();

        foreach($http_lang as $l) {
            $lang = explode(';', $l);
            array_push($lang, $l[0]);
        }

        return $lang;
    }

    static public function getUserLocale($getname = 'lang', $cookiename = 'ui.language') {
        $query_lang = isset($_GET[$getname]) ? $_GET[$getname] : null;
        $cookie_lang = isset($_COOKIE[$cookiename]) ? $_COOKIE[$cookiename] : null;
        $accept_lang = self::getAcceptLang();
        $config_lang = C('ui.language');

        $lang = array();
        if(!is_null($query_lang) && self::getLocaleName($query_lang)) {
            array_push($lang, $query_lang);
        }
        if(!is_null($cookie_lang) && self::getLocaleName($cookie_lang)) {
            array_push($lang, $cookie_lang);
        }
        foreach($accept_lang as $al) {
            if(self::getLocaleName($al)) {
                array_push($lang, $al);
            }
        }
        if(self::getLocaleName($config_lang)) {
            array_push($lang, $config_lang);
        }
        return $lang;
    }

    static private function scanLang($dir, $default = 'en', $domain = 'main') {
        $lang = array($default);
        if(!$files = scandir($dir)) {
            return false;
        }
        foreach($files as $file) {
            if(preg_match('(/.*)\.lang\.php/',$file,$match)) {
                array_push($lang);
            }
        }
        $this->valid_lang[$domain] = $lang;
        return true;
    }

    static public function getValidLang($type = 'lang', $domain = 'main') {
        if($type == 'lang') {
            $lang = $this->valid_lang[$domain];
        }else if($type == 'name') {
            $lang = array();
            foreach($this->valid_lang[$domain] as $vl) {
                $lang[$vl] = self::getLocaleName($vl);
            }
        }
        return $lang;
    }

    static public function chooseLang($lang = null, $domain = 'main') {

    }

    static public function loadLang($dir, $domain = 'main', $orig_locale = 'en') {
        if(!self::getLocaleName($orig_locale)) {
            return false;
        }
        self::$orig_locale[$domain] = $orig_locale;
        if(self::scanLang($dir, $domain)) {
            $lang = self::chooseLang($domain);
        }
    }

    static public function translate($from, $params = array(), $domain = 'main') {
        $to = $lang[$domain]->get($from);

        foreach($params as $key => $value) {
            $to = str_replace('{'.$key.'}', $value, $to);
        }
        $to = str_replace(array('\{','\}'), array('{','}'), $to);
        return $to;
    }
}
