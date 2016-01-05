<?php

/**
 * Config Entry Class
 *
 * Class to handle configturation entries
 *
 * @package qchan
 * @subpackage  Config
 * @author qakcn <qakcn@hotmail.com>
 * @copyright 2015 Quadra Studio
 * @version 0.1
 * @license http://mozilla.org/MPL/2.0/
 * @link https://github.com/qakcn/qchan
 */

namespace Config;

class ConfigEntry {
    protected $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function val() {
        return $this->value;
    }

    public function merge(ConfigEntry $entry) {
        $this->value = $entry->val();
    }
}
