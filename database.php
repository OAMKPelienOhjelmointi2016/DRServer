<?php	
	
	function db_connect() 
	{
		// Define connection as a static variable, to avoid connecting more than once 
		static $connection;
		
		// Try and connect to the database, if a connection has not been established yet
		if(!isset($connection)) 
		{
		try
			{
			$conn_string = "mysql:host=mysql.hostinger.fi;dbname=u176618033_po16";
			$db_Username = "u176618033_admin";
			$db_Password = "q9uUHTUKU2";
			$connection = new PDO ($conn_string, $db_Username, $db_Password);
			$connection->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			/*print ("Connected\n");*/
			}
			catch (PDOException $e)
			{
			 print ("Cannot connect to server\n");
			 print ("Error code: " . $e->getCode () . "\n");
			 print ("Error message: " . $e->getMessage () . "\n");
			}	
		}
		// Palautuarvon tarkistus
		// If connection was not successful, handle the error

    return $connection;
	}
	

	
	
?>