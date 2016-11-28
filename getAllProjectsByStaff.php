<?php
	include ('connect.php');
	session_start();
	if($_SESSION['user']){
	  $user = $_SESSION['user']; //assigns user value
		   $isLoggedIn = true;
		   $user_id = $_SESSION['user_id'];
		   $loginUser_FullName = $_SESSION['loginUser_FullName'];
		   $isAdmin = False;
		   if($_SESSION['isAdmin'] == "True"){
		   		$isAdmin = True;
		   }
    }
    else{ 
       header("location:home.php");
    }

	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	if (isset($_REQUEST['staffId'])) {
		$sql = "SELECT * FROM Projects where faculty_id ='".$_REQUEST['staffId']."'";
    	//echo $sql;
    	$result = mysqli_query($conn, $sql);
		$array = array();		
		while ($row = mysqli_fetch_assoc($result)) {
			if($row['status'] == "1"){
				$array[] = $row['name']."-".$row['id']."-".$row['issgraproj'];
			}
		//echo $row['name']."-".$row['id'];
		}	
		echo json_encode($array); //Return the JSON Array
	}
	mysqli_close($con);
?>