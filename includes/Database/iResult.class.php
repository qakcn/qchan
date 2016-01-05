<?php

namespace Database;

interface iResult {

    /**
     * This cannot be declare in an interface, but you should implement it
     * Get the number of rows or the insret id
     * @param  string  $name  'num_rows' or 'insert_id';
     * @return mixed  The value
     */
    function __get($name);

    /**
     * Fetch one row
     * @param  boolean  $assoc  Get as associative (TRUE, default) or enumerated (FALSE) array
     * @return array  An array contains one row
     */
    function fetch($assoc = TRUE);

    /**
     * Fetch all rows
     * @param  boolean  $assoc  Get each row as associative (TRUE, default) or enumerated (FALSE) array
     * @return array  An array contains all rows
     */
    function fetchAll($assoc = TRUE);
}
