<?php

namespace Language;

class Language {
    private $locale;

    private $lang;

    public function __construct(array $lang, $locale) {
        $this->lang = $lang;
        $this->locale = $locale;
    }

    public function __get($name) {
        if($name == 'locale') {
            return $this->locale;
        }else {
            return false;
        }
    }

    public function translate($from) {
        if(array_key_exists($from, $lang[$domain])) {
            $to = $lang[$domain][$from];
        }else {
            $to = $from;
        }
        return $to;
    }

    public function merge(Language $another) {
        if($another->locale == $this->locale) {
            $this->lang = array_merge($this->lang, $another->lang);
            return true;
        }else {
            return false;
        }
    }

}
