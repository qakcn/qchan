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
            case 'CONSTRUCT_ERROR':
                $errno = 101;
                break;
            case 'CONFIG_ITEM_NOT_FOUND':
                $errno = 102;
                break;
            case 'OBJECT_OVERWRITE_WITH_SCALAR_OR_ARRAY':
                $errno = 103;
                break;
            case 'VALUE_TYPE_NOT_SUPPORT':
                $errno = 104;
                break;
            case 'FILE_NOT_FOUND':
                $errno = 105;
                break;
            case 'NO_CONFIG_TO_SAVE':
                $errno = 106;
                break;
            case 'SAVE_TO_FILE_FAILED':
                $errno = 107;
                break;
        }
        parent::__construct($errno);
    }
}
