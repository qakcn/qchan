<?php

namespace Database\MySQL;

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
