<?php

/**
 * Driver for MySQL
 *
 * Hold mysqli instance
 *
 * @package     qchan
 * @subpackage  Database\MySQL
 * @author      qakcn <qakcnyn@gmail.com>
 * @copyright   2015 Quadra Work
 * @version     0.1
 * @license     http://mozilla.org/MPL/2.0/
 * @link        https://github.com/qakcn/qchan
 */

namespace Database\MySQL;

class Driver {
    private $instance;

    public function __construct($params) {
        if(!(isset($params['host']) && isset($params['username']) && isset($params['password']) && isset($params['dbname']))) {
            throw new Exception('PARAM_ERROR');
        }

        $port = isset($params['port']) ? isset($params['port']) : 3306;
        $this->instance = new \mysqli($params['host'], $params['username'], $params['password'], $params['dbname'], $port);
        if($this->instance->connect_errno) {
            throw new Exception('CONNECT_FAIL', $this->instance->connect_error, $this->instance->connect_errno);
        }
    }


    public function query($dbquery) {
        $result = $this->instance->query($dbquery);
        if($result) {
            return new Result($result, $this->instance);
        }else {
            throw new Exception('QUERY_FAIL', $this->instance->error, $this->instance->errno);
        }
    }

    public function escape($str) {
        return $this->instance->real_escape_string($str);
    }

    public function close() {
        return $this->instance->close();
    }
}


