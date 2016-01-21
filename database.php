<?php	
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
	
?>