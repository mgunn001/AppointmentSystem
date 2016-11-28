<?php
	include ('connect.php');
	session_start();
	if($_SESSION['user']){
    }
    else{ 
       header("location:home.php");
    }
    
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	if (isset($_REQUEST['searchVal'])) {
		$sql = "SELECT * FROM Student where lastname LIKE '%".strtoupper($_REQUEST['searchVal'])."%' OR firstname LIKE '%".strtoupper($_REQUEST['searchVal'])."%'";
    	//echo $sql;
    	$result = mysqli_query($conn, $sql);
		$array = array();		
		while ($row = mysqli_fetch_assoc($result)) {
			$array[] = $row['firstname'].",".$row['lastname']."-".$row['uin']."-".$row['gradlevel'];
		}	
		echo json_encode($array); //Return the JSON Array
	}
	mysqli_close($con);
?>