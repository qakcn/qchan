<?php

/**
 * Utilities Functions
 *
 * Functions that are utility.
 *
 * @package     qchan
 * @subpackage  utils.function
 * @author      qakcn <qakcnyn@gmail.com>
 * @copyright   2015 Quadra Studio
 * @version     0.1
 * @license     http://mozilla.org/MPL/2.0/
 * @link        https://github.com/qakcn/qchan
 */

/**
 * Get MIME type of a file or binary string
 *
 * @param string $data string a file path or a binary string
 * @param boolean $notfile set TRUE if $data is not a file path
 * @return string MIME type of $data, or FALSE if an error occurred
 */
function get_mimetype($data, $notfile = false) {
    $fi = finfo_open(FILEINFO_MIME_TYPE);
    if($notfile) {
        $mime = finfo_buffer($fi, $data);
    }else {
        $mime = finfo_file($fi, $data);
    }
    finfo_close($fi);
    return $mime;
}

/**
 * Get Configuration
 * @param string $name configuration name
 * @return mixed configuration content
 */
function C($name) {
    return Config::get($name);
}
