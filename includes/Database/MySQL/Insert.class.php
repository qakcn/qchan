<?php

/**
 * Insert for MySQL
 *
 * Use for MySQL insert
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

class Insert implements \Database\iInsert {

    private $driver;
    private $table;
    private $columns = array();
    private $values = array();

    public function __construct(Driver $driver) {
        $this->driver = $driver;
    }

    public function into($table) {
        $this->table = $table;
        return $this;
    }

    public function values(array $value) {
        if(func_num_args()>1) {
            $values = func_get_args();
            foreach($values as $value) {
                $this->values($value);
            }
        }else {
            if(empty($this->columns)) {
                $this->columns = array_keys($value);
                asort($this->columns);
            }else {
                if(!empty(array_diff_key(array_flip($this->columns),$value))) {
                    throw new \Exception('COLUMNS_NOT_MATCH');
                }else {
                    $value = array_diff_key($value, array_diff_key($value, array_flip($this->columns)));
                    ksort($value);
                }
            }
            array_push($this->values, $value);
        }
        return $this;
    }

    public function query() {
        if(empty($this->columns) || empty($this->values) || !isset($this->table)) {
            throw new \Exception('TOO_FEW_PARAMS');
        }
        $sql = 'INSERT INTO %s(%s) VALUES%s';
        $val = '';
        foreach($this->values as $value) {
            $val .= '(' . implode(',', $value) . '),';
        }
        $val = substr($val, 0, -1);

        $sql = sprintf($sql,
            $this->table,
            implode(',', $this->columns),
            $val);

        return $this->driver->query($sql);
    }
}
