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

//Get A SINGLE CUSTOMER 
//****All customers terms changed to customer, except in SQL statement
$app->get('/api/customer/{id}', function(Request $request, Response $response){
	$id = $request->getAttribute('id');

	$sql = "SELECT * FROM customers WHERE id = $id"; //$id comes from the URL 

		try{
			// Get DB object 
			$db = new db();
			// connect 
			$db = $db->connect(); 


			$stmt = $db->query($sql);
			$customer = $stmt->fetchAll(PDO::FETCH_OBJ); // changed to singular
			$db = null;
			echo json_encode($customer);  // changed to singular


		} catch(PDOException $e){
			//e for errors exception handling
       		 echo '{"error": {"text": '.$e->getMessage().'}';
    	}
});


// Add Customer
//we need to get the parameters, usually these would be thru a form, instead of get attribute which is the url
	// if now we want to get param
$app->post('/api/customer/add', function(Request $request, Response $response){
    $first_name = $request->getParam('first_name');// GETS PARAMETERS FROM USER INPUT
    $last_name = $request->getParam('last_name');
    $phone = $request->getParam('phone');
    $email = $request->getParam('email');
    $address = $request->getParam('address');
    $city = $request->getParam('city');
    $district = $request->getParam('district');
    $sql = "INSERT INTO customers (first_name,last_name,phone,email,address,city,district) VALUES
    (:first_name,:last_name,:phone,:email,:address,:city,:district)";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);  // BIND EACH PARAMATER
        $stmt->bindParam(':last_name',  $last_name);
        $stmt->bindParam(':phone',      $phone);
        $stmt->bindParam(':email',      $email);
        $stmt->bindParam(':address',    $address);
        $stmt->bindParam(':city',       $city);
        $stmt->bindParam(':district',      $district);
        $stmt->execute();
        echo '{"notice": {"text": "Customer Added"}';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
// Update Customer
//CHANGE POST TO PUT ------WE NEED THE ID
// NOW WE ARE GETTING BOTH URL ATTRIBUTE (TO FIND ID) AND PARAMS
$app->put('/api/customer/update/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $first_name = $request->getParam('first_name'); // GETTING PARAMS
    $last_name = $request->getParam('last_name');
    $phone = $request->getParam('phone');
    $email = $request->getParam('email');
    $address = $request->getParam('address');
    $city = $request->getParam('city');
    $district = $request->getParam('district');
    $sql = "UPDATE customers SET
				first_name 	= :first_name, 
				last_name 	= :last_name,
                phone		= :phone,
                email		= :email,
                address 	= :address,
                city 		= :city,
                district		= :district
			WHERE id = $id";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name',  $last_name);
        $stmt->bindParam(':phone',      $phone);
        $stmt->bindParam(':email',      $email);
        $stmt->bindParam(':address',    $address);
        $stmt->bindParam(':city',       $city);
        $stmt->bindParam(':district',      $district);
        $stmt->execute();
        echo '{"notice": {"text": "Customer Updated"}'; /// message changed to confirm
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});


// Delete Customer
$app->delete('/api/customer/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM customers WHERE id = $id";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "Customer Deleted"}';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});




