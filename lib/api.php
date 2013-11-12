<?php

// http://www.gen-x-design.com/archives/create-a-rest-api-with-php/
// https://github.com/gilbitron/Arrest-MySQL/blob/master/lib/arrest-mysql.php



class api {

    // $http_codes = array(
    //     100 => 'Continue',
    //     101 => 'Switching Protocols',
    //     102 => 'Processing',
    //     200 => 'OK',
    //     201 => 'Created',
    //     202 => 'Accepted',
    //     203 => 'Non-Authoritative Information',
    //     204 => 'No Content',
    //     205 => 'Reset Content',
    //     206 => 'Partial Content',
    //     207 => 'Multi-Status',
    //     300 => 'Multiple Choices',
    //     301 => 'Moved Permanently',
    //     302 => 'Found',
    //     303 => 'See Other',
    //     304 => 'Not Modified',
    //     305 => 'Use Proxy',
    //     306 => 'Switch Proxy',
    //     307 => 'Temporary Redirect',
    //     400 => 'Bad Request',
    //     401 => 'Unauthorized',
    //     402 => 'Payment Required',
    //     403 => 'Forbidden',
    //     404 => 'Not Found',
    //     405 => 'Method Not Allowed',
    //     406 => 'Not Acceptable',
    //     407 => 'Proxy Authentication Required',
    //     408 => 'Request Timeout',
    //     409 => 'Conflict',
    //     410 => 'Gone',
    //     411 => 'Length Required',
    //     412 => 'Precondition Failed',
    //     413 => 'Request Entity Too Large',
    //     414 => 'Request-URI Too Long',
    //     415 => 'Unsupported Media Type',
    //     416 => 'Requested Range Not Satisfiable',
    //     417 => 'Expectation Failed',
    //     418 => 'I\'m a teapot',
    //     422 => 'Unprocessable Entity',
    //     423 => 'Locked',
    //     424 => 'Failed Dependency',
    //     425 => 'Unordered Collection',
    //     426 => 'Upgrade Required',
    //     449 => 'Retry With',
    //     450 => 'Blocked by Windows Parental Controls',
    //     500 => 'Internal Server Error',
    //     501 => 'Not Implemented',
    //     502 => 'Bad Gateway',
    //     503 => 'Service Unavailable',
    //     504 => 'Gateway Timeout',
    //     505 => 'HTTP Version Not Supported',
    //     506 => 'Variant Also Negotiates',
    //     507 => 'Insufficient Storage',
    //     509 => 'Bandwidth Limit Exceeded',
    //     510 => 'Not Extended'
    // );

    public static function request() {
        // Returns HTTP request information
        return array (
            'verb'     => strtolower($_SERVER['REQUEST_METHOD']),
            'path'     => isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '/',
            'segments' => isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : array(),
            'params'   => isset($_GET) ? $_GET : null
        );
    }

    public static function execute($request, $default) {
 
        $method_default = 'index';
        $segments = $request['segments'];

        // Determines controller
        $ctrl = $default;
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
            header("HTTP/1.0 400 Bad Request");
            echo json_encode($data); 

        } else { 
            header('Content-Type: application/json');
            echo json_encode($data); 
        }
    }

    public static function error($data) {
        self::response($data, false);
        
    }

    public function input() {
        return json_decode(file_get_contents('php://input'),true);
    }

    public function run($default = 'db') {     
        $request = self::request();
        $results = self::execute($request, $default);
        self::response($results);
    }



}

