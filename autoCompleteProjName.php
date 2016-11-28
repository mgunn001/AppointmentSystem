<?php
	include ('connect.php');
	session_start();
	if($_SESSION['user']){
    }
    else{ 
       header("location:home.php");
    }
   	$searchVal =  $_REQUEST['searchVal'];
   	//echo $searchVal;
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	if (isset($_REQUEST['searchVal'])) {
		$sql = "SELECT * FROM Projects where name LIKE '%".strtoupper($_REQUEST['searchVal'])."%'";
    	//echo $sql;
    	$result = mysqli_query($conn, $sql);
		$array = array();		
		while ($row = mysqli_fetch_assoc($result)) {
			$array[] = $row['name']."-".$row['id']."-".$row['issgraproj'];
			//echo $row['name']."-".$row['id'];
		}	
		echo json_encode($array); //Return the JSON Array
	}
	mysqli_close($con);
?>