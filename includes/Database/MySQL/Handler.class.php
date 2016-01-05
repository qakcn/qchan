<?php

/**
 * Handler for MySQL
 *
 * Use for MySQL handle
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

class Handler implements \Database\iHandler {

    private $driver;

    public function __construct($params) {
        $this->driver = new Driver($params);
    }

    public function escape($string) {
        return $this->$driver->escape($string);
    }

    public function listTables() {
        return $this->driver->query('SHOW TABLES');
    }

    public function select() {
        return new Select($this->driver);
    }

    function insert(){
        return new Insert($this->driver);
    }

    function update(){}

    function createTable(){}

    function deleteTable(){}

}
