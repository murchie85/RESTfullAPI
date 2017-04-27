<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//Get All Customers 

$app->get('/api/customers', function(Request $request, Response $response){
	$sql = "SELECT * FROM customers";

		try{
			// Get DB object 
			$db = new db();
			// connect 
			$db = $db->connect(); 

		}catch(PDOException $e){
			//e for errors exception handling
        echo '{"error": {"text": '.$e->getMessage().'}';
    	}
});
