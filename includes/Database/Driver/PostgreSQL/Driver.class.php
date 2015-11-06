<?php

namespace Database\Driver\PostgreSQL;

class Driver {
    private $conn;

    public function __construct($host, $username, $passwd, $dbname, $port = 5432, $persistent = false) {
        $connstr = printf('host=%s port=%d user=%s password=%s dbname=%s', $host, $port, $user, $passwd, $dbname);
        if($persistent) {
            $this->conn = pg_pconnect($connstr);
        }else {
            $this->conn = pg_connect($connstr);
        }
        if($this->conn === false) {
            throw new DriverException('Connection failed.', 1);
        }
    }

    public function query($dbquery) {
        $result = pg_query($this->conn, $dbquery);
        if($result) {
            return new Result($result);
        }else {
            throw new DriverException(pg_last_error($this->conn), 2);
        }
    }

    public function escape($str) {
        return pg_escape_string($str);
    }
}


