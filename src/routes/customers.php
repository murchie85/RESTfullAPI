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
			// when we are working with PDO we need to  create statements 
			// there are a few benefits to creating prepared statements
			// 1. the query only needs to be parsed once 
			// 2. can be executed multiple times with the same / or diff peramaters
			// 3. the parameters don't need to be quoted. 
			//stmt is standard 
			// passes in $sql var


			$stmt = $db->query($sql);
			$customers = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo json_encode($customers);


		} catch(PDOException $e){
			//e for errors exception handling
       		 echo '{"error": {"text": '.$e->getMessage().'}';
    	}
});
