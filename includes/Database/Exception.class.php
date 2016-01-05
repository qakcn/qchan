<?php

namespace Database;

class Exception extends \QchanException {
    public function __construct($type, $error = '') {
        switch($type) {
            case 'COLUMN_NOT_EXISTS':
                $errno = 201;
                break;
            case 'PROPETY_NOT_EXISTS':
                $errno = 202;
                break;
            case 'DATABASE_TYPE_ERROR':
                $errno = 203;
                break;
            case 'DATABASE_PARAMS_EMPTY':
                $errno = 204;
                break;
            case 'JOIN_TYPE_ERROR':
                $errno = 205
        }
        parent::__construct($errno);
    }
}
