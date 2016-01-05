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

class ConfigEntryCarrier extends ConfigEntry implements \Iterator {
    /**
     * Magic methods
     */

    public function __construct() {
        $this->value = array();
    }

    public function __get($name) {
        if(isset($this->value[$name])) {
            return $this->value[$name];
        }
    }

    public function __set($name, ConfigEntry $entry) {
        $this->value[$name] = $entry;
    }

    public function __isset($name){
        return isset($this->value[$name]);
    }

    public function __clone() {
        foreach($this->value as $name => $entry) {
            $this->value[$name] = clone $entry;
        }
    }

    public function val(){
        return $this;
    }

    public function length() {
        return count($this->value);
    }

    public function merge(ConfigEntryCarrier $entries) {
        foreach($entries as $name => $entry) {
            if(isset($this->value[$name])) {
                if(($this->value[$name] instanceof ConfigEntryCarrier) xor !($entry instanceof ConfigEntryCarrier)) {
                        $this->value[$name]->merge($entry);
                }else {
                    throw new ConfigException('CARRIER_CAN_NOT_OVERWRITE_BY_ENTRY');
                }
            }else {
                $this->value[$name] = $entry;
            }
        }
    }

    /**
     * Iterator interface methods
     */

    public function current() {
        return current($this->value);
    }

    public function rewind() {
        return reset($this->value);
    }

    public function key() {
        return key($this->value);
    }

    public function next() {
        next($this->value);
    }

    public function valid() {
        if(key($this->value) === null) {
            return false;
        }else {
            return true;
        }
    }
}
