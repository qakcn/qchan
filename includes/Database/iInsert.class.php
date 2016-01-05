<?php

/**
 * iInsert Interface
 *
 * The class that use for database insert must implement this
 *
 * @package     qchan
 * @subpackage  Database
 * @author      qakcn <qakcnyn@gmail.com>
 * @copyright   2015 Quadra Work
 * @version     0.1
 * @license     http://mozilla.org/MPL/2.0/
 * @link        https://github.com/qakcn/qchan
 */

namespace Database;

interface iInsert {

    /**
     * The table's name which will be inserted
     * @param  string  $table  Table name
     * @return iInsert  The instance of iInsert object itself
     */
    function into($table);

    /**
     * The table's name which will be inserted
     * @param  string  $table  Table name
     * @return iInsert  The instance of iInsert object itself
     */
    function values(array $value);

    /**
     * Do the INSERT
     * @return iResult  A Result object
     */
    function query();
}
