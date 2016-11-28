<?php 
 session_start();
require 'Send_Mail1.php';
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
 
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//echo "File is valid, and was successfully uploaded.".$path;
	$recruitmentId =  @trim(stripslashes($_REQUEST["recid"]));
	//echo $recruitmentId;
	
	$toList = array();
	$ccList = array();
	$proffName="";
	$academicSem = "";
	$studentDet ="";
     
	//echo "first echo";
	
	$recSelQuery = $conn->prepare('SELECT R.student_id AS stu_id, Stu.firstname as stu_fn,
 	Stu.lastname AS stu_ln,Stu.email AS stu_email,R.currentpost,R.semester,R.year,R.startdate,R.enddate,R.salarypaid,R.offerstatus,R.hours,Sta.faculty_id as sta_id, Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email,
 	R.isreappointed,R.project_id As proj_id FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id and R.id = ?');
	$recSelQuery->bind_param('s', $recruitmentId);
	$recSelQuery->execute();
	$recSelResult = $recSelQuery->get_result();
	while($row = $recSelResult->fetch_assoc()) {  	  	
	  	
		$stuObj = array();
		$studentDet = $row["stu_fn"]." ".$row["stu_ln"]."(".$row["stu_id"].")";
    	$stuObj["name"] =  $row["stu_fn"]."-".$row["stu_ln"];
      	$stuObj["email"] = $row["stu_email"];     
    	//$stuObj["accdet"]= $row["semester"]."-".$row["year"]."-";
    	$academicSem = $row["semester"]."-".$row["year"];
  	   	$proffName = $row["sta_fn"]." ".$row["sta_ln"];
    	// no need to send a CC to Offer who initiated offer	
		$emailSubject ="Offer Initiated for ".$academicSem; // this it got to be dymamic, right now it is hardcoded
		$emailBody = "Dear ".$row["stu_fn"]." ".$row["stu_ln"].", <br /><br /> You have been appointed by Proffesor ".$proffName." as a ".$row["currentpost"]." begining ".$row["startdate"]." until ".$row["enddate"]." for ".$row["salarypaid"].".If you accept, an offer letter along with the appointment's requirements will be sent to you with in the next few days.<br />";
		$emailBody.= "please click on the below link to accept or decline the offer. <br /><br />";		
		// this is the link that student clicks to upload his offer letter and clicks on accept
		$emailBody .= "http://qav2.cs.odu.edu/".SERVERHOST."_StudentAppointmentSystem/landingPageStudent1.php?recid=".$recruitmentId;	
		//$emailBody .= "http://qav2.cs.odu.edu/Prod_StudentAppointmentSystem/landingPageStudent1.php?recid=".$recruitmentId;			
		
		$emailBody.="<br /> <br />Sincerely,<br/>".$proffName;
		
		$stuObj["emailSubject"] = $emailSubject;
		$stuObj["emailBody"] = $emailBody;		
      	array_push($toList,$stuObj);      	  	
	 }

	 
	 
	 //recent changes 
	$adminSelQuery = "SELECT * FROM Staff WHERE isadmin = 1 or isadmin = 2";
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
	 
	 
	    if(Send_Mail1($toList,$ccList)){		
			// a recent change to incorporate the change that admin must be able to edit the credits and tutuion fee stuff	
			/*$updatOfferStatQuery= "UPDATE Recruitments SET offeraccepted='1',tutionwaive='".$admUpdatedTution."',credithours='".$admUpdatedCredits."' WHERE id=".$recruitmentId;				
		   	if(mysqli_query($conn, $updatOfferStatQuery)){
					echo "success";
		    }	*/
			echo "success";			
	    }else{
	    	echo "error";
	    }
?>