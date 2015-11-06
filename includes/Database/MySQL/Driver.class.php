<?php

namespace Database\MySQL;

class Driver {
    private $instance;

    public function __construct($host, $username, $passwd, $dbname, $port = 3306) {
        $this->instance = new mysqli($host, $username, $passwd, $dbname, $port);
        if($this->instance->connect_errno) {
            throw new MysqlException('connection', $this->instance->connect_error, $this->instance->connect_errno);
        }
    }

    public function query($dbquery) {
        $result = $this->instance->query($dbquery);
        if($result) {
            return new Result($result, $this->instance);
        }else {
            throw new MysqlException('query', $this->instance->error, $this->instance->errno);
        }
    }

    public function escape($str) {
        return $this->instance->real_escape_string($str);
    }
}


