<?php

// http://www.gen-x-design.com/archives/create-a-rest-api-with-php/
// https://github.com/gilbitron/Arrest-MySQL/blob/master/lib/arrest-mysql.php



class api {

    public static function request() {
        // Returns HTTP request information
        return array (
            'verb'     => strtolower($_SERVER['REQUEST_METHOD']),
            'path'     => isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '/',
            'segments' => isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : array(),
            'params'   => isset($_GET) ? $_GET : null
        );
    }

    public static function execute($request) {
 
        $method_default = 'index';
        $segments = $request['segments'];

        // Determines controller
        $ctrl = 'db';
        if(isset($segments[0]) && class_exists($segments[0])) {
            $ctrl = $segments[0];
            $segments = array_slice($segments, 1);
        }

        // Determines controller method and adjust params 
        $method_name = $request['verb'] . "_index";
        if(isset($segments[0]) && method_exists($ctrl, $request['verb'] . '_' . $segments[0])) {
            // custom method
            $method_name = $request['verb'] . '_' . $segments[0];
        }

        // Call controller method with request params
        $ctrl = new $ctrl;
        $result = call_user_func_array(array($ctrl, $method_name), $segments);
        return $result;

    }

    public static function response($data, $success = true) {
        if(!$success) {
            header("HTTP/1.0 500 Internal Server Error");
            echo json_encode($data); 
        } else { 
            header('Content-Type: application/json');
            echo json_encode($data); 
        }
    }

    public function run() {     
        //set_exception_handler('api::exception');
        //set_error_handler('api::exception');
        $request = self::request();
        $results = self::execute($request);
        self::response($results);
    }

}

