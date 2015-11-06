<?php

namespace Database\Driver\PostgreSQL;

class DriverException extends \QchanException {
    public function __construct($error, $errno) {
        parent::__construct($desc, $errno);
    }
}
