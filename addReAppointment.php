<?php
	include ('connect.php');
	require 'Send_Mail1.php';
	session_start();
	$user_id = $_SESSION['user_id'];
	if($_SESSION['user']){
    }
    else{ 
       header("location:login.php");
    }
	$exisitingrecruitmentId = @trim(stripslashes($_POST["recid"]));
	//echo $_REQUEST["reProj"];
	$newProjDet = explode("-",@trim(stripslashes($_POST["reProj"])));
	
	$newProjName = $newProjDet[0];
	//echo $newProjName;
	$newProjID = $newProjDet[1];
	//echo $newProjID;
	$newSal = @trim(stripslashes($_POST["reSalaryIP"]));
	$newPost = @trim(stripslashes($_POST["rePostIP"]));
	$newHours = @trim(stripslashes($_POST["reHourIP"]));
	$newTutionDet = explode("-",@trim(stripslashes($_POST["reTutionIP"])));
	$semester = @trim(stripslashes($_POST["semester"]));
	$year = @trim(stripslashes($_POST["year"]));
	$startdate = date('Y-m-d', strtotime(@trim(stripslashes($_POST["startdate"]))));
	$enddate = date('Y-m-d', strtotime(@trim(stripslashes($_POST["enddate"]))));
	$fundingType= @trim(stripslashes($_POST["reFundingIP"]));
	
	
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	//mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
	
	$projSelQuery ="Select * from Projects WHERE name='".$newProjName."'";
	//echo $projSelQuery;
	$projResult = mysqli_query($conn, $projSelQuery);
	 	if (mysqli_num_rows($projResult) > 0) {
	 		while($row = $projResult->fetch_assoc()) {
	 			$projid = $row["id"];
	 		}
	    	// do nothing
		} else {	    	
			if($newProjID != "0" && $newProjName!=="NONE"){
				$isSGRAProj = 0;
		   		if($newPost == "SGRA" || $newPost== "PHD_SGRA" ){
		   			$isSGRAProj = 1;
		   		}
				
				$projInsQuery = "INSERT INTO Projects(name,issgraproj,faculty_id,status) VALUES ('$newProjName','$isSGRAProj','$user_id','1')"; //SQL insert into project query	 
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
	//echo "Hello".$projid;
	//echo $exisitingrecruitmentId;
	//echo $_SESSION['user_id'];
	// this has to be changes to eg: christy or Ari's email, right now it is hardcoded need to get it dynamically
	$toList= array();
	$ccList = array();
	$stuObj = array();
	$proffName="";
	$academicSem = "";
	$studentDet ="";

     
	$existingRecSelQuery = $conn->prepare('SELECT R.student_id AS stu_id, Stu.firstname as stu_fn,
 	Stu.lastname AS stu_ln,Stu.email AS stu_email,R.currentpost,R.semester,R.year,R.salarypaid,R.offerstatus,R.hours,Sta.faculty_id as sta_id, Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email,
 	R.isreappointed,R.project_id As proj_id FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id and R.id = ?');

	$existingRecSelQuery->bind_param('s', $exisitingrecruitmentId);
	$existingRecSelQuery->execute();
	$recSelResult = $existingRecSelQuery->get_result();

  while($row = $recSelResult->fetch_assoc()) {  
    	

  		$studentDet = $row["stu_fn"]." ".$row["stu_ln"]."(".$row["stu_id"].")";
  		
    	$stuObj["name"] =  $row["stu_fn"]."-".$row["stu_ln"];
      	$stuObj["email"] = $row["stu_email"];     

      	$proffName = $row["sta_fn"]." ".$row["sta_ln"];
  	
    	// some businesslogiv to be written here yet
    	$studentid = $row['stu_id'];
    	//$semester= $row['semester'];
    	$facultyid= $_SESSION['user_id'];
    	$currentpost= $row['currentpost'];
    	//$year = $row['year'];
    	//$projid= $row['proj_id'];
    	$salarypaid = $row['salarypaid'];
    	$isreappointed = 0;
	    $semAndYear = "";

	

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
	    	$totalHours = $exiHoursSum+intval($newHours);
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
						
		//echo $studentid.",".$facultyid.",".$semester.",".$currentpost.",".$year.",".$salarypaid.",".$newHours.",".$projid.",".$isreappointed;   	
		$offerStatus =0;   	
      	$tutionQuery ="Select * FROM AdminSettings WHERE 1";	
    	$tutionRecRes = mysqli_query($conn, $tutionQuery);
    	$currentTution= 0.0;
	    if (mysqli_num_rows($tutionRecRes) > 0) {
	    	while($row = $tutionRecRes->fetch_assoc()) {   		
	 				$currentTution = floatval($row["currenttution"]);	 			
	 		}	    	
	    }
	    $academicSem = $semester."|".$year;
	    
    	if($projid == ""){
    		$Query1 ="INSERT INTO Recruitments(student_id, faculty_id, semester, currentpost,year,tutionwaive,credithours,currenttution,salarypaid,hours,isreappointed,offerstatus,startdate,enddate,fundingtype) VALUES ('$studentid','$facultyid','$semester','$newPost','$year',$newTutionDet[0],$newTutionDet[1],$currentTution,$newSal,$newHours,$isreappointed,$offerStatus,'$startdate' ,'$enddate',$fundingType)"; //SQL query 		
    	}else{
    		$Query1 ="INSERT INTO Recruitments(student_id, faculty_id, semester, currentpost,year,tutionwaive,credithours,currenttution,salarypaid,hours,project_id,isreappointed,offerstatus,startdate,enddate,fundingtype) VALUES ('$studentid','$facultyid','$semester','$newPost','$year',$newTutionDet[0],$newTutionDet[1],$currentTution,$newSal,$newHours,$projid,$isreappointed,$offerStatus,'$startdate' ,'$enddate',$fundingType)"; //SQL query   		
    	}
    	//echo $Query1;
		$Query2 = "UPDATE Recruitments SET isreappointed='1' WHERE id=".$exisitingrecruitmentId;		
    	    	
    	if(mysqli_query($conn,$Query1)){
			$newInsertedID = mysqli_insert_id($conn);
    		if(!mysqli_query($conn,$Query2)){
    			echo "something went wrong with insertion";
    			exit;
    		}	    
			//echo "newlyInserted:". $newInsertedID;
	    	// code to email added from right hre 
	    	//array_push($ccList, $_SESSION['user_email']);
	    	//array_push($ccList, $row['stu_email']);
		    $emailSubject ="Offer Initiated for ".$academicSem; // this it got to be dymamic, right now it is hardcoded   		   
			$emailBody = "Hi ".$studentDet.", <br />Proffesor ".$proffName." has initiated the Appointment.<br /><br />";
			$emailBody.= "please click on the below link to accept or decline the offer. <br /><br />";		
			
			// this is the link that student clicks to upload his offer letter and clicks on accept
			//$emailBody .= "http://qav2.cs.odu.edu/Prod_StudentAppointmentSystem/landingPageStudent1.php?recid=".$newInsertedID;		
							
			$emailBody .= "http://qav2.cs.odu.edu/".SERVERHOST."_StudentAppointmentSystem/landingPageStudent1.php?recid=".$newInsertedID;	
			
			$emailBody.="<br /> <br />Thank you";
			
			$stuObj["emailSubject"] = $emailSubject;
			$stuObj["emailBody"] = $emailBody;		
	      	array_push($toList,$stuObj);      	  			    		
	    	}
	    else{
	    	echo "fail";
	    	return false;
	    } 
    }
    
   	$adminSelQuery = "SELECT * FROM Staff WHERE isadmin <> 0";
	$adminResult = $conn->query($adminSelQuery);
    // output data of each row
	while($row = $adminResult->fetch_assoc()) {
	   	$admin = array();
	    $admin["name"] =  $row["firstname"]."-".$row["lastname"];
	    //echo $admin["name"] ;
	    $admin["email"] = $row["email"];
	     //echo $admin["email"] ;
	    
	    $emailSubject ="Offer Initiated for Student ".$studentDet."- ".$academicSem.".";
	    $emailBody = "Hi ".$row["firstname"]." ".$row["firstname"].", <br />Proffesor ".$proffName." has initiated the Appointment to the Student ".$studentDet."<br /><br />";	    	
	    //echo "adminType:".$row["isadmin"];
	    if(intval($row["isadmin"]) == 1){
	    	$emailBody.= "You may have to verify the Funds availbalility once the student accepts the Appointment. <br /><br />";			    	
	    }else{
	    	$emailBody.= "You may have to verify the Student's Academic Status and stuff once he accepts the Appointment. <br /><br />";		    	
	    }       	    
		//this is the link that student clicks to upload his offer letter and clicks on accept
		//$emailBody.= "Here is the link that you may go for: <br /><br /> http://qav2.cs.odu.edu/Prod_StudentAppointmentSystem/home.php";			    
	    $emailBody.= "Here is the link that you may go for: <br /><br /> http://qav2.cs.odu.edu/".SERVERHOST."_StudentAppointmentSystem/home.php";			
		$emailBody.="<br /> <br />Thank you";
			    
	    $admin["emailSubject"] = $emailSubject;
	    $admin["emailBody"] = $emailBody;   
	    //echo  $admin["emailSubject"];
	   // echo $admin["emailBody"];
	    array_push($ccList,$admin);
	} 
	//echo json_encode($toList);
	//echo json_encode($ccList);
     if(Send_Mail1($toList,$ccList)){
		 echo "success-".$newInsertedID;
	 }else{
		  echo "error";
	 }  	
    
   // mysqli_commit($con);
   mysqli_close($con);
//echo json_encode($array);
?>
