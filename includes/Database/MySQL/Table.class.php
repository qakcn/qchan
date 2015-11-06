<?php

namespace Database\MySQL;

class Table {

    private $name;
    private $columns = array();
    private $primary_key = array();
    private $index = array();
    private $engine = 'InnoDB';
    private $comment = null;

    public function __construct($name) {
        $this->name = $name;
    }

    public function newColumn($name) {
        $column = new Column($name);
        $this->addColumn($column);
        return $column;
    }

    public function addColumn(Column $column) {
        $columns[$column->name] = $column;
        return $this;
    }

    public function primaryKey(array $names) {
        foreach ($names as $name) {
            if(array_key_exists($name, $this->columns) {
                $this->primary_key[] = $name;
            }
        }
        return $this;
    }

    public function index($index, array $names) {
        $this->index[$index] = array();
        foreach ($names as $name) {
            if(array_key_exists($name, $this->columns) {
                $this->index[$index][] = $name;
            }
        }
        return $this;
    }

    public function engine($engine) {
        switch($engine) {
            case 'temp':
                $this->engine = 'MEMORY';
                break;
        }
        return $this;
    }

    public function comment($comment) {
        $this->comment = $comment;
        return $this;
    }

    public function __get($name) {
        if(in_array($name, array('name','columns','primary_key','index','engine','comment'))) {
            return $this->$name;
        }else {
            throw new MysqlException('COLUMN_ERROR', 'No such a property');
        }
    }
}

class Column {

    private $name;
    private $type;
    private $unsigned = false;
    private $notnull = false;
    private $unique = false;
    private $autoincrement = false;
    private $default = null;
    private $comment = null;

    public function __construct($name) {
        $this->name = $name;
    }

    public function __get($name) {
        if(in_array($name, array('name','type','unsigned','notnull','unique','autoincrement','default','comment'))) {
            return $this->$name;
        }else {
            throw new MysqlException('COLUMN_ERROR', 'No such a property');
        }
    }

    public function type($type) {
        switch($type) {
            case 'int8':
                $this->type = 'TINYINT';
                break;
            case 'int16':
                $this->type = 'SMALLINT';
                break;
            case 'int32':
                $this->type = 'INT';
                break;
            case 'int64':
                $this->type = 'BIGINT';
                break;
            case 'float32':
                $this->type = 'FLOAT';
                break;
            case 'float64':
                $this->type = 'DOUBLE';
                break;
            case 'bool':
                $this->type = 'BIT';
                break;
            case 'string':
                $this->type = 'VARCHAR';
                break;
            case 'text':
                $this->type = 'LONGTEXT';
                break;
            case 'binary':
                $this->type = 'VARBINARY';
                break;
            case 'blob':
                $this->type = 'LONGBLOB';
                break;
        }
        return $this;
    }

    public function unsigned($yes = true) {
        $this->unsigned = $yes;
        return $this;
    }

    public function notNull($yes = true) {
        $this->notnull = $yes;
        return $this;
    }

    public function unique($yes = true) {
        $this->unique = $yes;
        return $this;
    }

    public function autoIncrement($yes = true) {
        $this->autoincrement = $yes;
        return $this;
    }

    public function default($default) {
        $this->default = $default;
        return $this;
    }

    public function comment($comment) {
        $this->comment = $comment;
        return $this;
    }


}
