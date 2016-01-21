<?php

require_once __DIR__."/../database.php";
require_once __DIR__.'/../vendor/autoload.php';


$app = new Silex\Application();

$app->get('/hello', function () 
{
    return 'Hello!';
});

$app->get('/users', function () 
{
	/*header('Content-Type: application/json');*/
	$stmt = $db->prepare("SELECT ID, firstName, lastName, playTime FROM users ORDER BY ID;");
	$stmt->execute();
	$results=$stmt->fetchAll(PDO::FETCH_ASSOC);
	$json=json_encode(array('Reply' => $results));
	/*echo $json; */
	
    /*return $json;*/
	return $results;
});

$app->run();
