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

class sample_api {

    function get_index($arg1) {
        return "You called GET /sample_api/$arg1";
    }

    function post_client() {
        return "You called POST /sample_api/client/";
    }

}

api::run();
