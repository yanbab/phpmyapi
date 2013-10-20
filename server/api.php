<?php

// http://www.gen-x-design.com/archives/create-a-rest-api-with-php/
// https://github.com/gilbitron/Arrest-MySQL/blob/master/lib/arrest-mysql.php

/**
 * db controller (PDO wrapper)
 */

class db  {

    public static $pdo;
    private $base;
    private $table;
    public static $dbo;

    public static $options = array(
        // Database
        'db_type' => 'mysql',
        'db_host' => '127.0.0.1',
        'db_name' => 'prestastrap_demo',
        'db_user' => 'root',
        'db_pass' => 'admin',
    );


    public function __construct() {
        // Database connection
        $dsn = self::$options['db_type'] . ':host=' . self::$options['db_host'];
        try {
            self::$pdo = new PDO($dsn, self::$options['db_user'], self::$options['db_pass']);
        } catch (PDOException $e) {    
            return false;
        } 
    }

    public function get_index($base = null, $table = null, $id = null) {
        if($base) {
            self::$pdo->query("USE $base");
            if($table) {
                return $this->_fetch($base, $table, $id);
            } else {
                return $this->_tables($base);
            }
        } else {
            return $this->_bases();
        }
    }

    private function _fetch($base, $table, $id = null) {
        
        // fields
        $fields = isset($_GET['fields']) ? $fields = $_GET['fields'] : $fields = '*';
        
        if($id) {
            $sql .= " WHERE id = '$id'";
        } 

        // limit
        // isset($params['per_page']) ? $params = min($_GET['per_page'], $this->per_page_max) : $per_page = $this->per_page;
        // isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
        // $limit_start = ( $page - 1 ) * $per_page;
        // $limit = "LIMIT $limit_start, $per_page";

        // order
        $order = '';

        $sql = "SELECT $fields FROM $table  $order";
        
        $query = self::$pdo->query("USE $base");
        $query = self::$pdo->query($sql);
        $records = $query->fetchAll(PDO::FETCH_ASSOC);
        return $records;
    }

    private function _tables($base) {
        $query = self::$pdo->query("SHOW TABLES FROM $base");
        $tables = $query->fetchAll(PDO::FETCH_COLUMN);
        return $tables;
    }

    private function _bases() {
        $query = self::$pdo->query('SHOW DATABASES');
        $bases = $query->fetchAll(PDO::FETCH_COLUMN);
        return $bases;
    }

}

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

    // public function exception($e) {
    //     self::response(
    //         array(
    //             'status' => 'error',
    //             'error' => $e->message()
    //         ),
    //         false
    //     );
    // }

    public function run() {     
        //set_exception_handler('api::exception');
        //set_error_handler('api::exception');
        $request = self::request();
        $results = self::execute($request);
        self::response($results);
    }

}

