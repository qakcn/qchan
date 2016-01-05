<?php

/**
 * Exception for MySQL
 *
 * Handle exceptions
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

class Exception extends \QchanException {
    public function __construct($type, $error = '', $mysql_errno=null) {
        switch($type) {
            case 'CONNECT_FAIL':
                $errno = 301;
                break;
            case 'QUERY_FAIL':
                $errno = 302;
                break;
            case 'RESULT_ERROR':
                $errno = 303;
                break;
        }
        if(!is_null($mysql_errno)) {
            $error .= ' (MySQL Error #'.$mysql_errno.')';
        }

        parent::__construct($errno);
    }
}
