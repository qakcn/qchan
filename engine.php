<?php

use \DataType as DT;
use \Exception as E;

define('ABSPATH', dirname(__FILE__));
define('INCLUDE_PATH', ABSPATH . '/includes');

require_once INCLUDE_PATH.'/Qchan.class.php';
Qchan::start();
