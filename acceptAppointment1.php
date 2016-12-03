<?php
	require 'Send_Mail1.php';
	include ('connect.php');
	session_start();
	$recruitmentId = $_SESSION["recruitmentId"];
	if($recruitmentId == ""){
		echo "error";
		return;
	}
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$toList= array();
	$ccList = array();
	$proffName="";
	$academicSem = "";
	$studentDet ="";
	$studentEmail = "";

	$stmt = $conn->prepare('SELECT R.student_id AS stu_id, Stu.firstname as stu_fn,
 	Stu.lastname AS stu_ln,Stu.email AS stu_email,R.currentpost,R.semester,R.year,R.salarypaid,Sta.faculty_id as sta_id, Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email,
 	R.isreappointed FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id and R.id = ?');

	$stmt->bind_param('s', $recruitmentId);
	$stmt->execute();
	$result = $stmt->get_result();

	    while($row = $result->fetch_assoc()) {  
	    	    	
	    	//$Query ="INSERT INTO Recruitments(student_id, faculty_id, semester, currentpost,year,salarypaid,isreappointed,offeraccepted) VALUES ('$studentid','$facultyid','$semester','$currentpost',$year,$salarypaid,$isreappointed,'1')"; //SQL query
			$Query = "UPDATE Recruitments SET offerstatus='2' WHERE id=".$recruitmentId;				
			/*	$insertQueryRes = mysql_query($Query); //SQL query
		       echo $insertQueryRes;*/
	    	if(mysqli_query($conn, $Query)){
	    		$studentEmail = $row["stu_email"];
		    	$studentDet = $row["stu_fn"]." ".$row["stu_ln"]."(".$row["stu_id"].")";
	    		$academicSem = $row["semester"]."-".$row["year"];
		    	$proffName = $row["sta_fn"]." ".$row["sta_ln"];
	    	}
	    }
    
        $stmt1 = "SELECT * FROM Staff WHERE isadmin=1 || isadmin=2";
		$result1 = $conn->query($stmt1);
	    // output data of each row
	   while($row = $result1->fetch_assoc()) {   	
	   	  $admin = array();
	   	  $emailSubject =  "Student ".$studentDet ." has accepted the Appointment."; 
	   	  $emailBody = "Dear ". $row["firstname"]." ".$row["lastname"].", <br /> Student ".$studentDet." has accepted the Offer initiated by professor ".$proffName ." <br /> <br />";  
	      $emailBody .= "Please see database for more information and to proceed with the hiring process, and release the appointment letter. <br /><br /> Thank you.";
	      $admin["name"] =  $row["firstname"]." ".$row["lastname"];
	      $admin["email"] = $row["email"];     		
		  $admin["emailSubject"] = $emailSubject;
		  $admin["emailBody"] = $emailBody;   
	      array_push($toList,$admin);
	   }
	   
	   // send a ack to student thanking for the offer acceptance and saying that he ill receive the offer letter sooner which he has to sign and submit
	    $stuMail = array();
	   	  $emailSubject =  "Thanks for accepting the Offer from ".$proffName; 
	   	  $emailBody = "Dear ". $studentDet." <br /> <br />";  
	      $emailBody .= "Thanks for accepting the offer initiated by professor ".$proffName ." <br /> <br />  Our Admins are working on the nessasary formalities, you will receive an  Appointment Letter shortly which you have to sign and submit. <br /><br />Thank you.";
	      $stuMail["name"] =  $studentDet;
	      $stuMail["email"] = $studentEmail;     		
		  $stuMail["emailSubject"] = $emailSubject;
		  $stuMail["emailBody"] = $emailBody;   
	      array_push($ccList,$stuMail);	   	       
		   if(Send_Mail1($toList,$ccList)){
			    echo "success";
		   }else{
		    	echo "error";
		   } 
	   
    mysqli_close($con);
   	unset($_SESSION["recruitmentId"]);
?>