<?php
    session_start();
    include ('connect.php');
    if($_SESSION['user']){
    }
    else{ 
       header("location:home.php");
    }
	 $user_id = $_SESSION['user_id'];
	 $isAdmin = False;
	   if($_SESSION['isAdmin'] == "True"){
	   		$isAdmin = True;
	   }
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
       $studentid = @trim(stripslashes($_POST['uinIP']));       
       $facultyid = @trim(stripslashes($_POST['staffIP']));
       $extAppImpBy = @trim(stripslashes($_POST['extAppImpBy']));
       $startdate =date('Y-m-d', strtotime(@trim(stripslashes($_POST['startDateIP']))));
       $enddate =date('Y-m-d', strtotime(@trim(stripslashes($_POST['endDateIP']))));
       //echo $startdate,$enddate;
       $semester = @trim(stripslashes($_POST['semesterIP']));
       $currentpost = @trim(stripslashes($_POST['postIP']));
       $year = @trim(stripslashes($_POST['yearIP']));
       
       // for the additional requirement of tution and no of credits
       $tution = @trim(stripslashes($_POST['tutionWaiveIP']));
       $noofcredits = @trim(stripslashes($_POST['noOfCreditsIP']));
       //$tution $noofcredits
       $salarypaid = @trim(stripslashes($_POST['salaryIP'])); 
       $projid = @trim(stripslashes($_POST['projID']));  //it can be a string if it we are adding a new project on a fly
       $projip = @trim(stripslashes($_POST['projIP']));
       $hoursip = @trim(stripslashes($_POST['hoursIP']));
       $fundingip = @trim(stripslashes($_POST['fundingIP']));
       $isreappointed = 0;

	 	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
		OR die ('Could not connect to MySQL: '.mysql_error());
		
	    if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// code to check whether a student is already been allocated with with 20Hours of work from another professor
		$stuSelQuery ="Select * FROM Recruitments WHERE student_id='".$studentid."' and semester='".$semester."' and year='".$year."'";
		//echo $stuSelQuery;
    	$exiStuRecRes = mysqli_query($conn, $stuSelQuery);
	    if (mysqli_num_rows($exiStuRecRes) > 0) {
	    	// do nothing
	   
	    	$exiHoursSum = 0;
	    	$facultyId ="";
	    	while($row = $exiStuRecRes->fetch_assoc()) {
    		
	 			$exiHoursSum +=  intval($row["hours"]);
	 			$facultyId = $row["faculty_id"];
	 			
	 		}
	 		//echo $exiHoursSum;
	 		//echo $facultyId;
	    	$totalHours = $exiHoursSum+intval($hoursip);
	    	//echo $totalHours;
	 		if($exiHoursSum >= 20){
	 			//echo "1";
	 			$facNameQuery ="Select firstname,lastname FROM Staff WHERE faculty_id='".$facultyId."'";
	 			//echo $facNameQuery;
	 			$proffName = mysqli_query($conn,$facNameQuery);
		 		while($row = $proffName->fetch_assoc()) {		    			    		
		 			echo "fail-This Student is already been appointed by proff ".$row['lastname']." ".$row['firstname']." so you cannot appoint.";
	 				exit();
		 		}	 			
	 		}
			//echo $totalHours;
		 	  if($totalHours > 20){		
		 			$facNameQuery ="Select firstname,lastname FROM Staff WHERE faculty_id='".$facultyId."'";
			 			//echo $facNameQuery;
			 		$proffName = mysqli_query($conn,$facNameQuery);
			 		while($row = $proffName->fetch_assoc()) {		    			    		
			 			echo "fail-This Student is already been appointed by proff ".$row['lastname']." ".$row['firstname']." for ".$exiHoursSum." hours, Hence you cannot appoint since the student will be overwhelemed";
			 				exit();
			 		}	
	 		   }
		  }
	

		$Query1 ="Select * from Projects WHERE name='$projip'";
		$result = mysqli_query($conn, $Query1);
	    if (mysqli_num_rows($result) > 0) {
	    	// do nothing
	    	while($row = $result->fetch_assoc()) {
	 			$projid = $row["id"];
	 		}
	    	
		} else {
		   if($projid != "0" && $projip!=="NONE"){
		   		$isSGRAProj = 0;
		   		if($currentpost == "SGRA" || $currentpost == "PHD_SGRA"){
		   			$isSGRAProj = 1;
		   		}
		   	
		    	$projInsQuery = "INSERT INTO Projects(name,issgraproj,faculty_id,status) VALUES ('$projip','$isSGRAProj','$facultyid','1')"; //SQL insert into project query	 
				if(mysqli_query($conn,$projInsQuery)){
			 		$insertedID = mysqli_insert_id($conn);  
			 		$projid = $insertedID;    				  		
				}
			    else
			    {
			    	exit();		   
			    } 
			}else{
				$projid="";
			}		  	
		}
		
    	$tutionQuery ="Select * FROM AdminSettings WHERE 1";	
    	$tutionRecRes = mysqli_query($conn, $tutionQuery);
    	$currentTution= 0.0;
	    if (mysqli_num_rows($tutionRecRes) > 0) {
	    	while($row = $tutionRecRes->fetch_assoc()) {   		
	 				$currentTution = floatval($row["currenttution"]);	 			
	 		}	    	
	    }
	    
		//echo $currentTution;
		if($projid == ""){
       		$Query2 ="INSERT INTO Recruitments(student_id, faculty_id, semester, currentpost,year,tutionwaive,credithours,currenttution,salarypaid,hours,isreappointed,offerstatus,startdate,enddate,fundingtype,existingAppImportedBy) VALUES ('$studentid','$facultyid','$semester','$currentpost','$year',$tution,$noofcredits,$currentTution,$salarypaid,$hoursip,$isreappointed,'4','$startdate' ,'$enddate',$fundingip,$extAppImpBy)"; //SQL query
		}else{
			$Query2 ="INSERT INTO Recruitments(student_id, faculty_id, semester, currentpost,year,tutionwaive,credithours,currenttution,salarypaid,hours,project_id,isreappointed,offerstatus,startdate,enddate,fundingtype,existingAppImportedBy) VALUES ('$studentid','$facultyid','$semester','$currentpost','$year',$tution,$noofcredits,$currentTution,$salarypaid,$hoursip,$projid,$isreappointed,'4','$startdate' ,'$enddate',$fundingip,$extAppImpBy)"; //SQL query			
		}
		
     	//echo $Query2;
		if(mysqli_query($conn,$Query2)){
	 		$insertedID = mysqli_insert_id($conn);       		
	  		//echo $studentid.",".$facultyid.",".$semester.",".$facultyid.",".$currentpost.",".$year.",".$salarypaid.",".$isreappointed;
	 		//header("location:home.php");
    	 	echo "success-".$insertedID;
		}
	    else
	    {
	    	echo "fail";
	     // header("location:home.php");
	    }
    }
    else
    {
    	echo "fail";
     // header("location:home.php");
    }
    mysqli_close($con);
?>