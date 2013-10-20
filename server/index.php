<?php

include('api.php');

// Example of a custom API, you can delete this class :
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
