<?php
	 session_start();
	include ('connect.php');
  	if($_SESSION['user']){
  		 $user_id = $_SESSION['user_id'];
    }
    else{ 
       header("location:home.php");
    }
    $action = intval(@trim(stripslashes($_REQUEST["action"])));
    
    //echo $action;
   $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
			OR die ('Could not connect to MySQL: '.mysql_error());			
				
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}   
    
    if($action == 1){
    	
    	$Query ="Select * FROM AdminSettings WHERE 1";
    	$tutionRecRes = mysqli_query($conn, $Query);
	    if (mysqli_num_rows($tutionRecRes) > 0) {
	    	while($row = $tutionRecRes->fetch_assoc()) {   		
	 			echo "success-".$row["currenttution"];	 			
	 		}
	    	
	    }else{
	    	echo "fail";
	    }
    }
   else if($action == 2){
		    $currTution = floatval($_REQUEST["currTution"]);
		    if($currTution > 0){
		    	$Query = "UPDATE AdminSettings SET currenttution=".$currTution." WHERE 1";
				$insertQueryRes = mysqli_query($conn,$Query); //Inserts the value to table Student				
				if (!$insertQueryRes) {				
					echo "fail";				
				}			
				echo "success";
		    }else{
		    	echo "fail";			
		    }		
			
    }else if($action == 3){
    	if($_REQUEST["currI9Expiry"] != "" && $_REQUEST["studentUIN"]!= ""){   		
	    	$currI9Expiry = date('Y-m-d', strtotime($_REQUEST["currI9Expiry"]));
	    	$currStudentUIN = $_REQUEST["studentUIN"];
	    	//echo $_REQUEST["currI9Expiry"];
	    	//echo $currI9Expiry;
	    	//echo $currStudentUIN; 
    		$Query = "UPDATE Student SET i9expiry='".$currI9Expiry."' WHERE uin='".$currStudentUIN."'";
			//echo $Query;
			$insertQueryRes = mysqli_query($conn,$Query); //Inserts the value to table Student				
			if (!$insertQueryRes) {				
				echo "fail";				
			}			
			echo "success";
    	}else{
    		echo "fail";
    	}
    	
    }else if($action == 4){ // to update the financial verification
    	if($_REQUEST["recid"]!= ""){   		   		
    		$currRecruitmentId = intval(@trim(stripslashes($_REQUEST["recid"])));
    		$Query = "UPDATE Recruitments SET isfinanceverified='1'  WHERE id='".$currRecruitmentId."'";
			//echo $Query;
			$insertQueryRes = mysqli_query($conn,$Query); //Inserts the value to table Student				
			if (!$insertQueryRes) {				
				echo "fail";				
			}			
			echo "success";
    	}else{
    		echo "fail";
    	}
    }else if($action == 5){ // to get whether financial verification is done or not
	    if($_REQUEST["recid"]!= ""){   		   		
	    	$currRecruitmentId = intval(@trim(stripslashes($_REQUEST["recid"])));
	    	$Query = "Select isfinanceverified from Recruitments WHERE id='".$currRecruitmentId."'";
	    	//echo $Query;
	    	$result = mysqli_query($conn, $Query);
			
			while ($row = mysqli_fetch_assoc($result)) {	  
				//echo  $row["isfinanceverified"]; 		
				if($row["isfinanceverified"] == "1"){
					echo "Success-1";
					return;
				}else{
					echo "Success-0";
					return;
				}
	    	}
	    	
	    }
	    echo "Fail";
    }else if($action == 6){ // this one is to get the semester start date  & end date
    	
    	if($_REQUEST["sem"]!= "" && $_REQUEST["accyear"]!=""){   

    		$curSem = @trim(stripslashes($_REQUEST["sem"]));
    		$accYear = @trim(stripslashes($_REQUEST["accyear"]));
    		$defStartDate = date('Y-m-d', strtotime($_REQUEST["defStartDate"]));
    		$defEndDate = date('Y-m-d', strtotime($_REQUEST["defEndDate"]));
	    	$QueryGet = "Select startdate,enddate from AdminSemDateSettings WHERE semester='".$curSem."' and year='".$accYear."'";
	    	//echo $Query;
	    	$result = mysqli_query($conn, $QueryGet);
			$num_rows = mysql_num_rows($result);

				while ($row = mysqli_fetch_assoc($result)) {	  
					echo "Success|".$row["startdate"]."|".$row["enddate"];
					return;
		    	}
				$QueryInsert = "INSERT INTO AdminSemDateSettings(semester, startdate, enddate, year, updatedby) VALUES ('$curSem','$defStartDate','$defEndDate','$accYear',$user_id)";
				if(mysqli_query($conn,$QueryInsert)){
			 			echo "Success|definsert";
			 			return ;		  		
				}
			    else
			    {
			    	echo "Fail";	   
			    } 
				

	
    	}
    	echo "Fail";
    }else if($action == 7){ // this one is to update the dates of the semester
    	
    	if($_REQUEST["curStartDate"]!= "" && $_REQUEST["curEndDate"]!=""){   

			$curSem = @trim(stripslashes($_REQUEST["sem"]));
    		$accYear = @trim(stripslashes($_REQUEST["accyear"]));
    		$defStartDate = date('Y-m-d', strtotime($_REQUEST["curStartDate"]));
    		$defEndDate = date('Y-m-d', strtotime($_REQUEST["curEndDate"]));
    		
    		$QueryUpdate = "UPDATE AdminSemDateSettings SET startdate='".$defStartDate."' , enddate='".$defEndDate ."' where semester='".$curSem."' and year='".$accYear."'";
			//echo $QueryUpdate;
    		$QueryRes = mysqli_query($conn,$QueryUpdate); //Inserts the value to table Student				
				if (!$QueryRes) {	
								
					echo "Fail";	
					return;			
				}			
				echo "success";
				return;		
    	}
    	echo "Fail";
    }

	mysqli_close($con);
?>








