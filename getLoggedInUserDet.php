<?php
	include ('connect.php');
	session_start();
	$userName = $_SERVER['PHP_AUTH_USER'];
	if($userName){
    }
    else{ 
      // header("location:home.php");
      alert(" Not logged in successfully or no proper previleges.");
      return;
    }
  

	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	if (isset($_REQUEST['searchVal'])) {
		$sql = "SELECT * FROM Staff where username = '".$userName. "'";
    	//echo $sql;
    	$result = mysqli_query($conn, $sql);
		$staffDet = array();		
		while ($row = mysqli_fetch_assoc($result)) {
			$staffDet[] = $row['faculty_id']."-".$row['firstname']."-".$row['lastname']."-".$row['email']."-".$row['isadmin'];
			
			//echo $row['name']."-".$row['id'];
		}	
		echo json_encode($staffDet); //Return the JSON Array
	}
	mysqli_close($con);
?>