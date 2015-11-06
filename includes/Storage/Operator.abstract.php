<?php

namespace Storage;

abstract class Operator {
    abstract public function save($tmpfile, $is_uploaded = true);
    abstract public function delete($file);

    const HEX2CHAR_LIST = 'abcdefghijkmnopqrstuvwxyzACDEFGHJKLMNPQRSTUVWXY_';

    protected function hex2char($hexstr) {

        $charset = self::HEX2CHAR_LIST;

        // Size to split hex string, avoid integer overflow
        $size = 6;

        // Split hex string into array
        $hash = array_filter(explode(',',chunk_split($hexstr,$size,',')));

        $output = '';

        // Convert from lower byte to higher byte
        while ($h = array_pop($hash)) {
            $h = hexdec($h);
            while($h>0) {
                $char = $h%48;
                $h = ($h-$char)/48;
                $output = $charset[$char] . $output;
            }
        }

        return $output;
    }

    protected function char2hex($str) {

        $charset = self::HEX2CHAR_LIST;

        $i=0;
        $num = 0;

        $output = '';

        while($char = substr($str, $i++, 1)) {
            $char = strpos($charset, $char);
            if($num*48 + $char > 16777215) {
                $output .= dechex($num);
                $num = 0;
            }
            $num = $num*48 + $char;
        }
        $output .= dechex($num);

        return $output;
    }
}
