<?php

/**
 * iHandler Interface
 *
 * Database handler must implement this
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

interface iHandler {

    /**
     * List all tables in the database
     * @return <array> all table names
     */
    function listTables();

    function escape($string);

    function select();

    function insert();

    function update();

    function createTable();

    function deleteTable();


}
