<?php

/*require_once __DIR__."/../database.php";*/
require_once __DIR__.'/../vendor/autoload.php';


$app = new Silex\Application();

$app->get('/hello', function () 
{
    return 'Hello!';
});

$app->get('/users', function () 
{
	try
	{
	$conn_string = "mysql:host=mysql.hostinger.fi;dbname=u176618033_po16";
	$db_Username = "u176618033_admin";
	$db_Password = "q9uUHTUKU2";
	$db = new PDO ($conn_string, $db_Username, $db_Password);
	$db->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	print ("Connected\n");
	}
	catch (PDOException $e)
	{
	 print ("Cannot connect to server\n");
	 print ("Error code: " . $e->getCode () . "\n");
	 print ("Error message: " . $e->getMessage () . "\n");
	}
	
	
	/*header('Content-Type: application/json');*/
	$stmt = $db->prepare("SELECT ID, firstName, lastName, playTime FROM users ORDER BY ID;");
	$stmt->execute();
	$results=$stmt->fetchAll(PDO::FETCH_ASSOC);
	var_dump ($results);
	/*$json=json_encode(array('Reply' => $results));*/
	/*echo $json; */
	
    /*return $json;*/
	return $results;
});

$app->run();
