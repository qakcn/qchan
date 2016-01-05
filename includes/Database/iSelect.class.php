<?php

/**
 * iSelect Interface
 *
 * The class that use for database select must implement this
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

interface iSelect {

    /**
     * Add a select result column
     * @param  string/array  $tables  A table name or an array contains these
     * @return iSelect  The instance of iSelect object itself
     */
    function from($tables);

    /**
     * Add a select result column
     * @param  string/array  $exprs  A column name or an expression or an array contains these
     * @return iSelect  The instance of iSelect object itself
     */
    function column($exprs);

    /**
     * Add a WHERE clause
     * @param  string  $condition  A where condition expression
     * @return iSelect  The instance of iSelect object itself
     */
    function where($condition);

    /**
     * Add a JION clause
     * @param  string/array  $tables  Table names which will be join
     * @param  string  $condition  A condition expression that will ON
     * @param  string  $type  To be 'inner', 'left', 'right' or 'full', that is the question.
     * @return iSelect  The instance of iSelect object itself
     */
    function join($tables, $condition, $type = 'inner');

    /**
     * Change a limit number of LIMIT clause, should be 1000 even if not called
     * @param  int  $num  The limit number
     * @return iSelect  The instance of iSelect object itself
     */
    function limit($num);

    /**
     * Change a offset number of LIMIT clause, should be 0 even if not called
     * @param  int  $num  The offset number
     * @return iSelect  The instance of iSelect object itself
     */
    function offset($num);

    /**
     * Add ORDERBY clause
     * @param  string  $expr  Some like $exprs in column() method, but cannot be an array
     * @param  bool  $asc  Ascending for TRUE (default) or descending for FALSE
     * @return iSelect  The instance of iSelect object itself
     */
    function orderby($expr, $asc = TRUE);

    /**
     * Do the SELECT
     * @return iResult  A Result object
     */
    function query();
}
