<?php 
 session_start();
require 'Send_Mail.php';
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
$recruitmentId =  @trim(stripslashes($_POST['recruitmentID']));
$uploaddir = 'Assets/Uploads/';
$uploadfile = $uploaddir . basename($_FILES['filetoUpload']['name']);
$uploadfile_newname= $uploaddir."offerunsigned_".$recruitmentId.".pdf";
//echo $new_file_name;

$result = move_uploaded_file($_FILES['filetoUpload']['tmp_name'], $uploadfile_newname); // for this to work the destination folder must have edit permissions 
//echo $uploadfile;
if ($result) {   
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	//$path =  $uploadfile;
	$path = $uploadfile_newname;
	//echo "File is valid, and was successfully uploaded.".$path;
	
	/*$admUpdatedTution =  @trim(stripslashes($_POST['admUpdatedTution']));
	$admUpdatedCredits =  @trim(stripslashes($_POST['admUpdatedCredits']));
	$admUpdatedSalary =  @trim(stripslashes($_POST['admUpdatedSalary']));
	$admUpdatedFT =  @trim(stripslashes($_POST['admUpdatedFT']));*/
	//echo $recruitmentId;
	
	$toList = array();
	$ccList = array();

	$recSelQuery = $conn->prepare('SELECT R.student_id AS stu_id, Stu.firstname as stu_fn,
 	Stu.lastname AS stu_ln,Stu.email AS stu_email,R.currentpost,R.semester,R.year,R.salarypaid,R.offerstatus,R.hours,Sta.faculty_id as sta_id, Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email,
 	R.isreappointed,R.project_id As proj_id FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id and R.id = ?');

	$recSelQuery->bind_param('s', $recruitmentId);
	$recSelQuery->execute();
	$recSelResult = $recSelQuery->get_result();
	while($row = $recSelResult->fetch_assoc()) {  	  	
	  	$stuObj = array();
    	$stuObj["name"] =  $row["stu_fn"]."-".$row["stu_ln"];
      	$stuObj["email"] = $row["stu_email"];     
    	array_push($toList,$stuObj);    	
		$emailSubject ="offer letter for ".$row["semester"]."|".$row["year"];
		
		$emailBody = "Hi ".$row["stu_fn"]." ".$row["stu_ln"].", <br />Proffesor ".$row["sta_fn"]." ".$row["sta_ln"]." has released an offer. Please find the attached offer, download to read and sign. <br /><br />";
		$emailBody.= "click on the below link to upload and accept the offer. <br /><br />";		
		// this is the link that student clicks to upload his offer letter and clicks on accept
		$emailBody .= "http://qav2.cs.odu.edu/".SERVERHOST."_StudentAppointmentSystem/landingPageStudent.php?recid=".$recruitmentId;			
		//$emailBody .= "http://qav2.cs.odu.edu/Prod_StudentAppointmentSystem/landingPageStudent.php?recid=".$recruitmentId;			
		
		$emailBody.="<br /> <br />Thank you";
		
		if(Send_Mail($toList,$ccList,$emailSubject,$emailBody,$path)){		
			// a recent change to incorporate the change that admin must be able to edit the credits and tutuion fee stuff	
			//$updatOfferStatQuery= "UPDATE Recruitments SET offerstatus='1',tutionwaive='".$admUpdatedTution."',salarypaid='".$admUpdatedSalary ."',credithours='".$admUpdatedCredits."' ,fundingtype='".$admUpdatedFT ."' WHERE id=".$recruitmentId;				
			$updatOfferStatQuery= "UPDATE Recruitments SET offerstatus='1' WHERE id=".$recruitmentId;				
			
			//echo $updatOfferStatQuery;
			if(mysqli_query($conn, $updatOfferStatQuery)){
					echo "success";
		    }			
	    }else{
	    	echo "error";
	    }
	 }
} else {
    echo "Possible file upload attack!\n";
}


?>