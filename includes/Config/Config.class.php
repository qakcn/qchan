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

class Config {

    static private $config = array();
    static private $default = array();


    static public function parse(array $config, array $default = null, $domain = 'main') {
        try {
            if(isset($config[$domain])) {
                self::$config[$domain]->merge(new ConfigItem($config));
            }else {
                self::$config[$domain] = new ConfigItem($config);
            }

            if(isset($default[$domain])) {
                 self::$default[$domain] = is_null($default) ? self::$default[$domain] : self::$default[$domain]->merge(new ConfigItem($default));
            }else {
                 self::$default[$domain] = is_null($default) ? null : new ConfigItem($default);
            }

            if(!is_null($default)) {
                self::$config[$domain] = self::$default[$domain]->merge(self::$config[$domain]);
            }

            return true;
        }catch(Exception $e) {
            throw $e;
            return false;
        }
    }

    static public function get($names = '', $default = false, $domain = 'main') {
        $direct = ($names=='');
        $names = explode('.', trim($names, ' \t\n\r\0\x0B.'));
        $v = $default ? self::$default[$domain] : self::$config[$domain];

        try {
            if(!is_null($v) && !$direct) {
                foreach($names as $name) {
                    $v = $v->$name;
                }
            }
            return $v;
        }catch(Exception $e) {
            throw $e;
            return false;
        }
    }

    static public function set($names, $value, $domain = 'main') {
        $names = explode('.', trim($names, ' \t\n\r\0\x0B.'));
        $lastname = array_pop($names);
        $names = implode('.',$names);

        try {
            $item = self::get($names);
            $item->$lastname = $value;
            return true;
        }catch(Exception $e) {
            throw $e;
            return false;
        }
    }

    static public function parseFile($configfile, $defaultfile = null, $domain = 'main') {
        try {
            if(file_exists($configfile) && (!is_null($defaultfile) && file_exists($defaultfile) || is_null($defaultfile))) {
                $config = require $configfile;
                $default = is_null($defaultfile) ? null : require $defaultfile;

                self::parse($config, $default, $domain);
                return true;
            }else {
                throw new ConfigException('FILE_NOT_FOUND');
            }
        }catch(Exception $e) {
            throw $e;
            return false;
        }
    }

    static public function saveFile($configfile, $domain = 'main') {
        try {
            if(!isset($config[$domain]) || !$config[$domain] instanceof ConfigItem) {
                throw new ConfigException('NO_CONFIG_TO_SAVE');
            }
            if(file_exists($configfile) && is_writable($configfile) || !file_exists($configfile) && is_writable(dirname($configfile))) {
                $content = '<?php' . PHP_EOL . 'require ' . $config[$domain]->formatPHP() . ';' . PHP_EOL;
                if(file_put_contents($configfile, $content) === false) {
                    throw new ConfigException('SAVE_TO_FILE_FAILED');
                }
                return true;
            }else {
                throw new ConfigException('FILE_NOT_WRITABLE');
            }
        }catch(Exception $e) {
            throw $e;
            return false;
        }
    }
}
