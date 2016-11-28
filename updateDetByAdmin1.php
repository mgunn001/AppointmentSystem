<?php
	 session_start();
	include ('connect.php');
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
 	
	if($_SERVER['REQUEST_METHOD'] == "POST")
    {
	   $rectuitmentid = @trim(stripslashes($_POST['recId']));
       $admUpdatedTution= @trim(stripslashes($_POST['admUpdateTution']));       
       $admUpdatedSal = @trim(stripslashes($_POST['admUpdatedSal']));
       $admUpdatedFT = @trim(stripslashes($_POST['admUpdatedFT']));       
		$admUpdatedCredits = @trim(stripslashes($_POST['admUpdatedCredits']));     
    	$Query = "UPDATE Recruitments SET isfinanceverified='1',tutionwaive=".$admUpdatedTution." ,salarypaid=".$admUpdatedSal." ,credithours='".$admUpdatedCredits."' ,fundingtype='".$admUpdatedFT ."'  WHERE id='".$rectuitmentid."'";
		$insertQueryRes = mysqli_query($conn,$Query); //Inserts the value to table Student				
		if (!$insertQueryRes) {				
			echo "fail";				
		}else{
			echo "success";
		}		
		
    }else{
    	echo "fail";
    }
    mysqli_close($con);
 	
?>