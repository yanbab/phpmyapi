<?php

/**
 * db controller (PDO wrapper)
 */

class db  {

    public static $pdo;
    private $base;
    private $table;

    public static $options = array(
        // Database
        'type' => 'mysql',
        'host' => '127.0.0.1',
        //'name' => 'prestastrap_demo',
        'user' => 'root',
        'pass' => '',
    );

    public function __construct() {
        // Database connection
        $dsn = self::$options['type'] . ':host=' . self::$options['host'];
        try {
            self::$pdo = new PDO($dsn, self::$options['user'], self::$options['pass']);
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