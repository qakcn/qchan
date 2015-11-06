<?php

/**
 * Config Classes
 *
 * Classes to handle configurations
 *
 * @package     qchan
 * @subpackage  Config
 * @author      qakcn <qakcnyn@gmail.com>
 * @copyright   2015 Quadra Studio
 * @version     0.1
 * @license     http://mozilla.org/MPL/2.0/
 * @link        https://github.com/qakcn/qchan
 */

namespace Config;

class ConfigItem {

    // Store child item
    private $children = array();

    // Reference to parent item
    private $parent = null;

    // Name of current item
    private $cname = null;

    static protected function is_assoc($var) {
        return is_array($var) && array_diff_key($var,array_keys(array_keys($var)));
    }

    static protected function packItemValue($value, $parent = null, $name = null) {
        if(self::is_assoc($value)) {
            $output = new ConfigItem($value, $parent, $name);
        }else if(is_array($value)) {
            $output = array();
            foreach($value as $v) {
                $output[] = self::packItemValue($v, $parent, $name);
            }
        }else if(is_scalar($value)) {
            $output = $value;
        }else if($value instanceof ConfigItem) {
            $output = clone $value;
        }else {
            throw new ConfigException('VALUE_TYPE_NOT_SUPPORT');
        }
        return $output;
    }

    static protected function unpackItemValue($value) {
        if($value instanceof ConfigItem) {
            $output = $value->toArray();
        }else if(is_array($value)) {
            $output = array();
            foreach($value as $v) {
                $output[] = self::unpackItemValue($v);
            }
        }else {
            $output = $value;
        }
        return $output;
    }

    public function __construct(array $config = array(), $parent = null, $cname = null) {
        $this->parent = $parent instanceof ConfigItem ? $parent : null;
        $this->cname = is_string($cname) ? $cname : null;
        if(self::is_assoc($config)) {
            foreach($config as $name => $value) {
                $this->__set($name, $value);
            }
        }else {
            throw new ConfigException('CONSTRUCT_ERROR');
        }
    }

    public function __get($name) {
        if(isset($this->children[$name])) {
            return $this->children[$name];
        }else {
            throw new ConfigException('CONFIG_ITEM_NOT_FOUND');
            return null;
        }
    }

    public function __isset($name) {
        return isset($this->children[$name]);
    }

    public function __clone() {
        foreach($this->children as $name => $value) {
            if($value instanceof ConfigItem) {
                $this->children[$name] = clone $this->children[$name];
            }
        }
    }

    public function __set($name, $value) {
        $value = self::packItemValue($value, $this, $name);
        if(!isset($this->children[$name])) {
            $this->children[$name] = $value;
        }else if(isset($this->children[$name]) && $this->children[$name] instanceof ConfigItem) {
            if(is_scalar($value) || is_array($value)) {
                throw new ConfigException('OBJECT_OVERWRITE_WITH_SCALAR_OR_ARRAY');
            }else if($value instanceof ConfigItem) {
                foreach($value->children() as $n => $v) {
                    $this->children[$name]->$n = $v;
                }
            }
        }else {
            $this->children[$name] = $value;
        }
    }

    public function toArray() {
        $output = array();
        foreach($this->children as $name => $value) {
            $output[$name] = self::unpackItemValue($value);
        }
        return $output;
    }

    static protected function formatArray($array, $php = false, $tab = '') {
        $output = ($php ? 'array(' : '[') . PHP_EOL;
        $i = 0;
        foreach($array as $name => $value) {
            $output .= $tab . '    ';
            if($php) {
                if(self::is_assoc($array)) {
                    $output .= '"' . addcslashes($name, "\n\r\t\v\e\f\\\$\"") . '" => ';
                }
            }else {
                $key = self::is_assoc($array) ? $name : $i++;
                $output .= $key . ' : ';
                if(!is_array($value) || !self::is_assoc($value)) {
                    $output .= gettype($value) . ' : ';
                }
            }
            switch(true) {
                case is_string($value):
                    $output .= '"' . addcslashes($value, "\n\r\t\v\e\f\\\$\"") . '"';
                    break;
                case is_bool($value):
                    $output .= $value?'true':'false';
                    break;
                case is_integer($value):
                case is_float($value):
                    $output .= $value;
                    break;
                case is_null($value):
                    $output .= 'null';
                    break;
                case is_array($value):
                    $output .= self::formatArray($value, $php, $tab.'    ');
            }
            $output .= ','.PHP_EOL;
        }
        $output = substr($output, 0, strlen($output)-2).PHP_EOL. $tab . ($php ? ')' : ']');
        return $output;
    }

    public function __toString() {
        return self::formatArray($this->toArray());
    }

    public function formatPHP() {
        return self::formatArray($this->toArray(), true);
    }

    public function formatJSON() {
        return json_encode($this->toArray());
    }


    public function parent() {
        if(is_null($this->parent)) {
            return false;
        }else {
            return $this->parent;
        }
    }

    public function children() {
        return $this->children;
    }

    public function name() {
        return $this->cname;
    }

    public function next() {
        $children = $this->parent()->children();
        for($search = reset($children); $search->name() != $this->cname; $search = next($children));
        return next($children);
    }

    public function prev() {
        $children = $this->parent()->children();
        for($search = end($children); $search->name() != $this->cname; $search = prev($children));
        return prev($children);
    }

    public function merge(ConfigItem $another) {
        $that = clone $this;
        foreach($another->children() as $name => $value) {
            $that->__set($name, $value);
        }
        return $that;
    }
}
