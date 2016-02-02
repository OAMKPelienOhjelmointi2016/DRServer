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

$app->get('/kills', function () 
{
	$ID = $_GET['ID'];
	$db = db_connect();
	header('Content-Type: application/json');
	$stmt = $db->prepare("SELECT kills, killed, hits FROM kills WHERE parentID = :ID;");
	$stmt->bindParam(':ID', $ID);
	$stmt -> execute();
	$result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
		
	$json=json_encode(array('Reply' => $result));
	
	return $json;
}
);

$app->post('/kills', function () 
{
	try
	{
		$ID = $_POST['ID'];
		
		$kills = $_POST['kills'];
		if (!isset ($kills))
		{
			$kills = 0;
		}
		$killed = $_POST['killed'];
		if (!isset ($killed))
		{
			$killed = 0;
		}
		$hits = $_POST['hits'];
		if (!isset ($hits))
		{
			$hits = 0;
		}
		
		
		$db = db_connect();
		
		$stmt = $db->prepare("SELECT ID FROM kills WHERE parentID = :ID;");
		$stmt->bindParam(':ID', $ID);
		$stmt->execute();
		$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if ($result==NULL)
		{
			$stmt = $db->prepare("INSERT INTO kills (parentID, kills, killed, hits) VALUES (:ID, :kills, :killed, :hits);");
			$stmt->bindParam(':ID', $ID);
			$stmt->bindParam(':kills', $kills);
			$stmt->bindParam(':killed', $killed);
			$stmt->bindParam(':hits', $hits);
			$stmt->execute();
			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			if ($kills > 0)
			{
				$stmt = $db->prepare("UPDATE kills SET kills = kills + :kills WHERE parentID = :ID;");
				$stmt->bindParam(':ID', $ID);
				$stmt->bindParam(':kills', $kills);
				$stmt->execute();
			}
			if ($killed > 0)
			{
				$stmt = $db->prepare("UPDATE kills SET killed = killed + :killed WHERE parentID = :ID;");
				$stmt->bindParam(':ID', $ID);
				$stmt->bindParam(':killed', $killed);
				$stmt->execute();
			}
			if ($hits > 0)
			{
				$stmt = $db->prepare("UPDATE kills SET hits = hits + :hits WHERE parentID = :ID;");
				$stmt->bindParam(':ID', $ID);
				$stmt->bindParam(':hits', $hits);
				$stmt->execute();
			}
						
			/*$result=$stmt->fetchAll(PDO::FETCH_ASSOC);*/
			return "kills/killed/hits created.";
		}		
	}	
	catch(PDOException $e)
    {
		return $sql . "<br>" . $e->getMessage();	
    }
	
}
);

$app->get('/top10', function () 
{
	
	$db = db_connect();
	header('Content-Type: application/json');
	$stmt = $db->prepare("SELECT b.ID, b.nickName, a.parentID, a.kills, a.killed, a.hits
						  FROM kills AS a LEFT JOIN users AS b ON a.ParentID = b.ID
						  ORDER BY a.kills DESC 
						  LIMIT 10;");
	$stmt -> execute();
	$result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
		
	$json=json_encode(array('Reply' => $result));
	
	return $json;
}
);


$app->run();
