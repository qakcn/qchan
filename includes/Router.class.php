<?php

class Router {
    static public function route() {
        $scriptpath = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));

        $path = $_SERVER['REQUEST_URI'];
        $query = $_SERVER['QUERY_STRING'];

        if(strlen($query)!=0) {
            $path = substr($path, 0, -strlen($query)-1);
        }
        $path = explode('/', trim($path, '/?'));
        $query = explode('/', $query);

        do{
            $s = array_shift($scriptpath);
            $p = array_shift($path);
        }while(!is_null($s) && !is_null($p) && $s == $p);
        if(!is_null($p)) {
            array_unshift($path, $p);
        }

        switch(true) {
            case isset($_GET['action']) && $_GET['action'] == 'api':
                $path = explode('/', trim($_GET['path'], '/?'));
                self::routeAPI($path);
                break;
            case count($path)==0 && array_shift($query) == 'api':
                $path = $query;
                self::routeAPI($path);
                break;
            case array_shift($path) == 'api':
                self::routeAPI($path);
                break;
            default:
                self::routeHome();
        }
    }

    static private function routeAPI($path) {
        echo 'API action:';
        var_dump($path);
    }

    static private function routeHome() {
        if(isset($_GET['action']) && $_GET['action'] == 'legacy') {
            
        }
    }
}
