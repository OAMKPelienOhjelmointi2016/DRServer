<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__."/../database.php";

$app = new Silex\Application();

$app->get('/hello', function () 
{
    return 'Hello!';
});

$app->get('/login', function () 
{
	$nName = $_GET['nname'];
	$db = db_connect();
	header('Content-Type: application/json');
	
	$stmt = $db->prepare("SELECT ID FROM users WHERE nickName = :nName;");
	$stmt->bindParam(':nName', $nName);	
	$stmt -> execute();
	$result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
		
	$json=json_encode(array('Reply' => $result));
	
	return $json;
}
);


$app->get('/users', function () 
{
	$nName = $_GET['nname'];
	
	$db = db_connect();
	header('Content-Type: application/json');
	if ($nName==NULL)
	{
		$stmt = $db->prepare("SELECT ID, firstName, lastName, nickName, playTime FROM users ORDER BY ID;");
	}
	else
	{
		$stmt = $db->prepare("SELECT ID, firstName, lastName, nickName, playTime FROM users WHERE nickName = :nName;");
		$stmt->bindParam(':nName', $nName);
	}
	$stmt -> execute();
	$result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
		
	$json=json_encode(array('Reply' => $result));
	
	return $json;
}
);

$app->post('/users', function () 
{
	try
	{
		$fName = $_POST['fname'];
		$lName = $_POST['lname'];
		$nName = $_POST['nname'];
		
		$db = db_connect();
		$stmt = $db->prepare("INSERT INTO users (firstName, lastName, nickName, playTime) VALUES (:fName, :lName, :nName, 0);");
		$stmt->bindParam(':fName', $fName);
		$stmt->bindParam(':lName', $lName);
		$stmt->bindParam(':nName', $nName);
		$stmt->execute();
		$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		
		return "User created.";	
	}	
	catch(PDOException $e)
    {
		return $sql . "<br>" . $e->getMessage();	
    }	
}
);

$app->get('/shots', function () 
{
	$ID = $_GET['ID'];
	$db = db_connect();
	header('Content-Type: application/json');
	$stmt = $db->prepare("SELECT fired FROM shots WHERE parentID = :ID;");
	$stmt->bindParam(':ID', $ID);
	$stmt -> execute();
	$result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
		
	$json=json_encode(array('Reply' => $result));
	
	return $json;
}
);

$app->post('/shots', function () 
{
	try
	{
		$ID = $_POST['ID'];
		
		$shots = $_POST['shots'];
		if (!isset ($shots))
		{
			$shots = 1;
		}
		
		$db = db_connect();
		
		$stmt = $db->prepare("SELECT ID FROM shots WHERE parentID = :ID;");
		$stmt->bindParam(':ID', $ID);
		$stmt->execute();
		$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if ($result==NULL)
		{
			$stmt = $db->prepare("INSERT INTO shots (parentID, fired) VALUES (:ID, :shots);");
			$stmt->bindParam(':ID', $ID);
			$stmt->bindParam(':shots', $shots);
			$stmt->execute();
			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			
			$stmt = $db->prepare("UPDATE shots SET fired = fired + :shots WHERE parentID = :ID;");
			$stmt->bindParam(':ID', $ID);
			$stmt->bindParam(':shots', $shots);
			$stmt->execute();
			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return "Shots created.";
		}		
	}	
	catch(PDOException $e)
    {
		return $sql . "<br>" . $e->getMessage();
		
    }
	
}
);


$app->run();
