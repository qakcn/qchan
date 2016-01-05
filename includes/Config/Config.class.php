<?php

/**
 * Config Classes
 *
 * Classes to handle configurations
 *
 * @package qchan
 * @subpackage Config
 * @author qakcn <qakcnyn@gmail.com>
 * @copyright 2015 Quadra Studio
 * @version 0.1
 * @license http://mozilla.org/MPL/2.0/
 * @link https://github.com/qakcn/qchan
 */

namespace Config;

class Config {

    static private function is_assoc($var) {
        return is_array($var) && array_diff_key($var,array_keys(array_keys($var)));
    }

    static public function toEntry($value) {
        if(self::is_assoc($value)) {
            $e = new ConfigEntryCarrier();
            foreach($value as $k => $v) {
                $e->$k = self::toEntry($v);
            }
        }else {
            $e = new ConfigEntry($value);
        }
        return $e;
    }

    static public function getValue(ConfigEntry $entry, $path='') {
        if(empty($path)) {
            return $entry->val();
        }else {
            $path = explode('.', $path);
            if(isset($entry->{$path[0]})) {
                $entry = $entry->{$path[0]};
                array_shift($path);
                $path = implode('.', $path);
                return self::getValue($entry, $path);
            }
        }
        throw new ConfigException('NO_SUCH_A_CONFIG_ENTRY');
    }

}
