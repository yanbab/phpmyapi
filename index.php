<?php
//
// phpMyAPI
//
// Sample usage
//



include('lib/api.php');
include('lib/api.db.php');

// Specify options for api.db
db::$options['host'] = 'localhost';
db::$options['user'] = 'root';
db::$options['pass'] = 'admin';

// Example of a custom API
// (try /hello, /hello/mars, /hello/say, /hello/say/goodbye)

class hello {

    // GET /hello
    // RESPONSE hello !

    function get_index($w = 'world') {
        return "hello  $w ! :)";
    }

    // GET /hello/say
    //     RESPONSE "hello world !"
    // GET /hello/say/goodbye
    //     RESPONSE "hello goodbye !"
    function get_say($word = "world") {
        return "hello $word !";
    }

}

api::run();
