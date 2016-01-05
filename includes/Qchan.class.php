<?php

/**
 * Utilities Functions
 *
 * Functions that are utility.
 *
 * @package     qchan
 * @subpackage  Qchan
 * @author      qakcn <qakcnyn@gmail.com>
 * @copyright   2015 Quadra Work
 * @version     0.1
 * @license     http://mozilla.org/MPL/2.0/
 * @link        https://github.com/qakcn/qchan
 */

class Qchan {

    static public function start() {
        try{
            //Register autoload function
            spl_autoload_register('Qchan::autoload');


            Database::registerHandler('MySQL', '\Database\MySQL\Handler');
            Database::linkStart('main', 'MySQL', array('host'=>'localhost', 'username'=>'root', 'password'=>'basic@)ismyLOVE', 'dbname'=>'test'));

            echo '<pre>';
            var_dump(Database::linkStart()->insert()->into('fuck')->values(array('name'=>'\'itfa\''))->query());



        }catch (Exception $e) {
            if(defined('QCHAN_DEBUG') && QCHAN_DEBUG) {
                echo '<pre>'.$e.'</pre>';
            }
        }
    }

    static public function autoload($classname) {
        $names = explode('\\',$classname);
        self::load_class(implode('/',$names));
    }

    static public function load_class($name, $dir='includes/') {
        require_once ABSPATH.'/'.$dir.$name.'.class.php';
    }

    static public function load_function($name, $dir='includes/') {
        require_once ABSPATH.'/'.$dir.$name.'.function.php';
    }



}
