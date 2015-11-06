<?php

namespace Database\MySQL;

class MysqlException extends \QchanException {
    public function __construct($type, $error, $mysql_errno=null) {
        switch($type) {
            case 'CONNECT_FAIL':
                $errno = 201;
                break;
            case 'QUERY_FAIL':
                $errno = 202;
                break;
            case 'RESULT_ERROR':
                $errno = 203;
                break;
            case 'COLUMN_ERROR':
                $errno = 204
                break;
        }
        if(!is_null($mysql_errno)) {
            $error .= ' (MySQL Error #'.$mysql_errno.')';
        }
        parent::__construct($errno);
    }
}
