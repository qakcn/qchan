<?php

namespace DatabaseDriver;

interface DatabaseDriver {

    function query($dbquery);

    function close();

    function escape($str);
}
