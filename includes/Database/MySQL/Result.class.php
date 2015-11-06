<?php

namespace Database\MySQL;

class Result {

    private $instance;
    private $num_rows;
    private $insert_id;

    public function __construct($result, $driver) {
        if($result === true) {
            $this->num_rows = $driver->affected_rows;
            $this->instance = ''
        }else if($result instanceof mysqli_result) {
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

    public function fetch($name, $arguments) {
        if($this->instance instanceof mysqli_result) {
            return $this->instance->fetch_assoc();
        }else {
            throw new DriverException('result', 'Fetch only SELECT, SHOW, DESCRIB or EXPLAIN result', 0);
        }
    }
}
