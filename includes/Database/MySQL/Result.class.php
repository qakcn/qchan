<?php

/**
 * Result for MySQL
 *
 * Use for return MySQL result
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

class Result implements \Database\iResult {

    private $instance;
    private $num_rows;
    private $insert_id;

    public function __construct($result, $driver) {
        if($result === true) {
            $this->num_rows = $driver->affected_rows;
            $this->instance = '';
        }else if($result instanceof \mysqli_result) {
            $this->instance = $result;
            $this->num_rows = $result->num_rows;
        }
        $this->insert_id = $driver->insert_id;
    }

    public function __get($name) {
        switch($name) {
            case 'num_rows':
                return $this->num_rows;
            case 'insert_id':
                return $this->insert_id;
        }
    }

    public function fetch($assoc = TRUE) {
        if($this->instance instanceof \mysqli_result) {
            if($assoc) {
                return $this->instance->fetch_assoc();
            }else {
                return $this->instance->fetch_row();
            }
            return $return;
        }else {
            throw new MySQLException('RESULT_ERROR', 'Fetch only SELECT, SHOW, DESCRIB or EXPLAIN result');
        }
    }

    public function fetchAll($assoc = TRUE) {
        if($this->instance instanceof \mysqli_result) {
            $return = array();
            $fetch = $assoc ? 'fetch_assoc' : 'fetch_row';
            while($onerow = ($this->instance->$fetch())) {
                array_push($return, $onerow);
            }
            return $return;
        }else {
            throw new MySQLException('RESULT_ERROR', 'Fetch only SELECT, SHOW, DESCRIB or EXPLAIN result');
        }

    }
}
