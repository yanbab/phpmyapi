phpmyapi
========

phpMyAPI is a micro-framework to easyly create REST API.

**Usage**

	<?php
	require("lib/api.php");

	class sample_api {

	    function get_index($arg1) {
	        return "You called GET /sample_api/$arg1";
	    }

	    function post_client() {
	        return "You called POST /sample_api/client/";
	    }

	}

	api::run();

