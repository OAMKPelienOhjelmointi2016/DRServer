<?php

	require_once __DIR__."/../database.php";
	$db = db_connect();
	$stmt = $db->prepare("SELECT b.ID, b.nickName, b.firstName, b.lastName, a.parentID, a.kills, a.killed, (a.kills/a.killed) AS killRatio 
						  FROM kills AS a LEFT JOIN users AS b ON a.ParentID = b.ID
						  ORDER BY 8 DESC 
						  LIMIT 10;");
	$stmt -> execute();							
	echo "<h1>DescentRemake statistics</h1>";
	echo "<h2>OAMK Pelien ohjelmointikurssi 2016</h2></br></br>";
	
	echo "<h2>TOP10 Players</h2>";
	$i = 1;
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
	{	
		$rowarray = array($i, $row['nickName'], $row['firstName'], $row['lastName'], $row['kills'], $row['killed'], $row['killRatio']);
		$top10[] = $rowarray;	
		$i++;
	}
	echo "<table border='1'><tr>";	
	echo "<th>Rank</th>";
	echo "<th>Nick</th>";
    echo "<th>First Name</th>";
    echo "<th>Last Name</th>";
	echo "<th>Kills</th>";
	echo "<th>Deaths</th>";
	echo "<th>Kill ratio</th></tr><tr>";
	foreach ($top10 as $arvot)
	{
		foreach ($arvot as $arvot1)
			echo "<td>$arvot1</td>";
		echo "</tr>";
	}
	echo "</table>";	

	$db = db_connect();
	$stmt = $db->prepare("SELECT b.ID, b.nickName, b.firstName, b.lastName, a.parentID, a.fired, c.hits, (c.hits/a.fired) * 100 AS hitRatio
							FROM shots AS a
							LEFT JOIN users AS b ON a.ParentID = b.ID LEFT JOIN kills AS c ON b.ID = c.parentID
							ORDER BY 8 DESC 
							LIMIT 10;");
	$stmt -> execute();							

	echo "<h2>TOP10 Shooters</h2>";
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
	{	
		$rowarray2 = array($row['ID'], $row['nickName'], $row['firstName'], $row['lastName'], $row['fired'], $row['hits'], $row['hitRatio']);
		$shooters[] = $rowarray2;	
	}
	echo "<table border='1'><tr>";	
	echo "<th>ID</th>";
	echo "<th>Nick</th>";
    echo "<th>First Name</th>";
    echo "<th>Last Name</th>";
	echo "<th>Shots fired</th>";
	echo "<th>Shots hit</th>";
	echo "<th>Hit ratio %</th></tr><tr>";
	foreach ($shooters as $arvos)
	{
		foreach ($arvos as $arvos1)
			echo "<td>$arvos1</td>";
		echo "</tr>";
	}
	echo "</table>";

	$db = db_connect();
	$stmt = $db->prepare("SELECT ID, nickName, firstName, lastName FROM users ORDER BY nickName;");
	$stmt -> execute();							

	echo "<h2>All Players</h2>";
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
	{	
		$rowarray3 = array($row['ID'], $row['nickName'], $row['firstName'], $row['lastName']);
		$users[] = $rowarray3;	
	}
	echo "<table border='1'><tr>";	
	echo "<th>ID</th>";
	echo "<th>Nick</th>";
    echo "<th>First Name</th>";
    echo "<th>Last Name</th>";
	echo "</tr><tr>";
	foreach ($users as $arvou)
	{
		foreach ($arvou as $arvou1)
			echo "<td>$arvou1</td>";
		echo "</tr>";
	}
	echo "</table>";
?>
