<?php
	require 'Send_Mail1.php';
	include ('connect.php');
	session_start();
	$recruitmentId = $_SESSION["recruitmentId"];
	//echo "In Decline Appointment";
	if($recruitmentId == ""){
		echo "error";
		return;
	}
	//echo $recruitmentId;
	$declineDesc = $_POST['declineDescription'];
	//echo $declineDesc;
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
		   
	$stmt = $conn->prepare('SELECT R.student_id AS stu_id, Stu.firstname as stu_fn,
 	Stu.lastname AS stu_ln,Stu.email AS stu_email,R.currentpost,R.semester,R.year,R.salarypaid,Sta.faculty_id as sta_id, Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email,
 	R.isreappointed FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id and R.id = ?');

	$stmt->bind_param('s', $recruitmentId);
	$stmt->execute();
	$result = $stmt->get_result();

    while($row = $result->fetch_assoc()) {  
    	    	
		$Query = "UPDATE Recruitments SET offerstatus='3' WHERE id=".$recruitmentId;		
    	if(mysqli_query($conn, $Query)){  

    		$studentDet = $row["stu_fn"]." ".$row["stu_ln"]."(".$row["stu_id"].")";
	    	$academicSem = $row["semester"]."-".$row["year"];
		    $proffName = $row["sta_fn"]." ".$row["sta_ln"];
	    	
	    	$staObj = array();
	    	$staObj["name"] =  $row["sta_fn"]." ".$row["sta_ln"];
	      	$staObj["email"] = $row["sta_email"];  
	      	
	        $emailSubject =  "Student ". $studentDet." has declined the Appointment."; 	    	
	    	$emailBody = "Hello ". $proffName.", <br /> Student ". $studentDet." has declined the Offer. <br /> <br />";  
	    	$emailBody .= "Here is the description that student provide: <br />";
			$emailBody .=$declineDesc." <br /> <br /> Thank you.";  
			$staObj["emailSubject"] = $emailSubject;
			$staObj["emailBody"]= $emailBody;
	    	array_push($toList,$staObj);
    	}
    }
      
    $stmt1 = "SELECT * FROM Staff WHERE isadmin=1 || isadmin=2";
   //echo $stmt1;
	$result1 = $conn->query($stmt1);
	    // output data of each row
	   while($row = $result1->fetch_assoc()) {
	   	 //echo "inside the Staff fetching while";
	   	 //echo $row["email"];
	   	  $admin = array();
	   	  $emailSubject =  "Student ".$studentDet ." has declined the Appointment."; 
	   	  $emailBody = "Hello ". $row["firstname"]." ".$row["lastname"].", <br /> Student ".$studentDet." has declined the Offer initiated by professor ".$proffName ." <br /> <br /> Thank you.";  
	      $admin["name"] =  $row["firstname"]." ".$row["lastname"];
	      $admin["email"] = $row["email"];     		
		  $admin["emailSubject"] = $emailSubject;
		  $admin["emailBody"] = $emailBody;   
	      array_push($ccList,$admin);
	   } 
 	//echo json_encode($ccList);
	 if(Send_Mail1($toList,$ccList)){
		echo "success";
	 }else{
	    echo "error";
	 } 
    mysqli_close($con);
   	unset($_SESSION["recruitmentId"]);
//echo json_encode($array);
?>