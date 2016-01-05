<?php

/**
 * Select for MySQL
 *
 * Use for MySQL select
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

class Select implements \Database\iSelect {
    private $driver;
    private $tables = array();
    private $columns = array();
    private $where = null;
    private $join = array();
    private $limit = 1000;
    private $offset = 0;
    private $orderby = array();

    function __construct(Driver $driver) {
        $this->driver = $driver;
    }

    function from($tables) {
        if(is_string($tables)) {
            $tables = array($tables);
        }else if(!is_array($tables)) {
            throw new Exception('PARAM_TYPE_ERROR');
        }
        $this->tables = array_merge($this->tables, $tables);
        return $this;
    }

    function column($exprs) {
        if(is_string($exprs)) {
            $exprs = array($exprs);
        }else if(!is_array($exprs)) {
            throw new Exception('PARAM_TYPE_ERROR');
        }
        $this->columns = array_merge($this->columns, $exprs);
        return $this;
    }

    function where($condition) {
        $this->where = $condition;
        return $this;
    }

    function join($tables, $condition, $type = 'INNER') {
        $type = strtoupper($type);
        if(is_string($table)) {
            $tables = array($tables);
        }else if(!is_array($exprs)) {
            throw new Exception('PARAM_TYPE_ERROR');
        }
        if(!in_array(strtoupper($type), array('INNER', 'LEFT', 'RIGHT', 'FULL'))) {
            throw new \Database\Exception('JOIN_TYPE_ERROR');
        }
        array_push($this->join, array('tables' => $tables, 'type' => $type, 'on' => $condition));
        return $this;
    }

    function limit($num) {
        $this->limit = (int)$num;
        return $this;
    }

    function offset($num) {
        $this->offset = (int)$num;
        return $this;
    }

    function orderby($expr, $asc = true) {
        array_push($this->orderby, array('expr' => $expr, 'asc' => $asc));
        return $this;
    }

    function query() {
        $sql = 'SELECT %s FROM %s%s%s%s LIMIT %d,%d';
        $joinsql = '';
        $wheresql = '';
        $orderbysql = '';
        if(!empty($this->join)) {
            foreach($this->join as $join) {
                $joinsql .= sprintf(' %s JOIN %s ON %s', $join['type'], implode(',', $join['tables']), $join['on']);
            }
        }
        if(!empty($this->where)) {
            $wheresql = ' WHERE ' . $this->where;
        }
        if(!empty($this->orderby)) {
            $orderbysql = ' ORDER BY ';
            foreach($this->orderby as $orderby) {
                $orderbysql .= $orderby['expr'] . ($orderby['asc'] ? ' ASC,' : ' DESC,');
            }
            $orderbysql = substr($orderbysql, 0, -1);
        }

        $sql = sprintf($sql,
            empty($this->columns) ? '*' : implode(',', $this->columns),
            implode(',', $this->tables),
            $joinsql, $wheresql, $orderbysql,
            $this->offset, $this->limit);
        return $this->driver->query($sql);
    }

}
