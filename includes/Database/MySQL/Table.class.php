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


