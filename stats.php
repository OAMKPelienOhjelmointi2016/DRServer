<?php

	require_once __DIR__."/../database.php";
	$db = db_connect();
	$stmt = $db->prepare("SELECT b.ID, b.nickName, b.firstName, b.lastName, a.parentID, a.kills, a.killed, a.hits
						  FROM kills AS a LEFT JOIN users AS b ON a.ParentID = b.ID
						  ORDER BY a.kills DESC 
						  LIMIT 10;");
	$stmt -> execute();							

	echo "<h1>TOP 10 Players</h1>";
	$i = 1;
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
	{	
		$rowarray = array($i, $row['nickName'], $row['firstName'], $row['lastName'], $row['kills'], $row['killed'], $row['hits']);
		$top10[] = $rowarray;	
		$i++;
	}
	echo "<table border='1'><tr>";	
	echo "<th>Rank</th>";
	echo "<th>Nick</th>";
    echo "<th>First Name</th>";
    echo "<th>Last Name</th>";
	echo "<th>Kills</th>";
	echo "<th>Killed</th>";
	echo "<th>Hits</th></tr><tr>";
	foreach ($top10 as $arvo)
	{
		foreach ($arvo as $arvo1)
			echo "<td>$arvo1</td>";
		echo "</tr>";
	}
	echo "</table>";	

	$db = db_connect();
	$stmt = $db->prepare("SELECT ID, nickName, firstName, lastName, playTime FROM users;");
	$stmt -> execute();							

	echo "<h1>All Players</h1>";
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
	{	
		$rowarray = array($row['ID'], $row['nickName'], $row['firstName'], $row['lastName'], $row['playTime']);
		$users[] = $rowarray;	
	}
	echo "<table border='1'><tr>";	
	echo "<th>ID</th>";
	echo "<th>Nick</th>";
    echo "<th>First Name</th>";
    echo "<th>Last Name</th>";
	echo "<th>Play Time</th></tr><tr>";
	foreach ($users as $arvo)
	{
		foreach ($arvo as $arvo1)
			echo "<td>$arvo1</td>";
		echo "</tr>";
	}
	echo "</table>";

?>
