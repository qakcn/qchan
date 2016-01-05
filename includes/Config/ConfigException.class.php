<?php

/**
 * Config Classes
 *
 * Classes to handle configurations
 *
 * @package     qchan
 * @subpackage  Config
 * @author      qakcn <qakcnyn@gmail.com>
 * @copyright   2015 Quadra Studio
 * @version     0.1
 * @license     http://mozilla.org/MPL/2.0/
 * @link        https://github.com/qakcn/qchan
 */

namespace Config;

class ConfigException extends \QchanException {

    public function __construct($type) {
        switch($type) {
            case 'CARRIER_CAN_NOT_SET_VALUE':
                $errno = 101;
                break;
            case 'CARRIER_CAN_NOT_OVERWRITE_BY_ENTRY':
                $errno = 102;
                break;
            case 'NO_SUCH_A_CONFIG_ENTRY':
                $errno = 103;
                break;
        }
        parent::__construct($errno);
    }
}
