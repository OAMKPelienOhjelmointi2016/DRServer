<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__."/../database.php";


$app = new Silex\Application();

$app->get('/hello', function () 
{
    return 'Hello!';
});

$app->get('/users', function () 
{
	$db = db_connect();
	header('Content-Type: application/json');
	$stmt = $db->prepare("SELECT ID, firstName, lastName, playTime FROM users ORDER BY ID;");
	$stmt->execute();
	$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		
	$json=json_encode(array('Reply' => $result));
	
	return $json;
	
});

$app->run();
