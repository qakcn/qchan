<?php

/**
 * Database Class
 *
 * Register database handler and return it.
 *
 * @package     qchan
 * @subpackage  Database
 * @author      qakcn <qakcnyn@gmail.com>
 * @copyright   2015 Quadra Work
 * @version     0.1
 * @license     http://mozilla.org/MPL/2.0/
 * @link        https://github.com/qakcn/qchan
 */

namespace Database;

class Database {

    static private $handlers = array();
    static private $handler_instances = array();

    static public function registerHandler($type, $handler) {
        self::$handlers[$type] = $handler;
    }


    static public function linkStart($domain = 'main', $type = null, $params = null) {
        if(!isset(self::$handler_instances[$domain])) {
            if(is_null($type) || !isset(self::$handlers[$type])) {
                throw new Exception('DATABASE_TYPE_ERROR');
            }
            if(is_null($params)) {
                throw new Exception('DATABASE_PARAMS_EMPTY');
            }
            self::$handler_instances[$domain] = new self::$handlers[$type]($params);
        }
        return self::$handler_instances[$domain];
    }
}
