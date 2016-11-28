<?php
require 'Send_Mail.php';
include ('connect.php');
session_start();
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
$recruitmentId=$_REQUEST["recid"];
$adminList= array();
$ccList = array();

$stmt1 = "SELECT firstname,lastname,email FROM Staff WHERE isAdmin=1";
$result1 = $conn->query($stmt1);
    // output data of each row
   while($row = $result1->fetch_assoc()) {
   	$admin = array();
      $admin["name"] =  $row["firstname"]."-".$row["lastname"];
      $admin["email"] = $row["email"];            
      array_push($adminList,$admin);
   } 



/*$query = mysql_query("SELECT R.id as rec_id , R.student_id AS stu_id, Stu.firstname as stu_fn,
 Stu.lastname AS stu_ln,R.currentpost,R.semester,R.year,R.salarypaid,Sta.firstname As sta_fn,Sta.lastname AS sta_ln,
 R.isreappointed FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id");*/

$stmt2 = $conn->prepare('SELECT R.student_id AS stu_id, Stu.firstname as stu_fn,
 Stu.lastname AS stu_ln,Stu.email AS stu_email,R.currentpost,R.semester,R.year,R.salarypaid,R.startdate,R.enddate,Sta.firstname As sta_fn,Sta.faculty_id as sta_id,Sta.lastname AS sta_ln,Sta.email AS sta_email,
 R.isreappointed FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id and R.id = ?');

$stmt2->bind_param('s', $recruitmentId);
$stmt2->execute();
$result2 = $stmt2->get_result();

    while($row = $result2->fetch_assoc()) {   
    	$stuObj = array();
    	$stuObj["name"] =  $row["stu_fn"]."-".$row["stu_ln"];
      	$stuObj["email"] = $row["stu_email"];     
    	array_push($ccList,$stuObj);
    	
    	$staObj = array();
    	$staObj["name"] =  $row["sta_fn"]."-".$row["sta_ln"];
      	$staObj["email"] = $row["sta_email"];     
    	array_push($ccList,$staObj);
   	    	
    	$emailSubject = "Student ". $row["stu_fn"]." ".$row["stu_ln"]."(".$row['stu_id'].") is appointed - Release Appointment Letter when student accepts";      
    	//$adminNames =  explode("@", $adminEmailList[0]);
    	$emailBody = "Dear ".str_replace("-"," ",$adminList[0]["name"]) .", <br /><br /> I have appointed Student ". str_replace("-"," ",$stuObj["name"])."(".$row['stu_id']."). as a ".$row['currentpost'] ." begining ". $row['startdate'] ." until ".$row['enddate'] ." for ".$row['salarypaid'] ." <br /><br />";
    	$emailBody .= "Click on the below link to attach and email the offer letter. <br /><br />";

    	// this following list is for admin to send -------------- come visit it important
    	$queryString = "ccmail[]=".$staObj["email"]."-".$staObj["name"]."&ccmail[]=".$adminList[0]["email"]."-".$adminList[0]["name"]."&tomail=".$stuObj["email"]."-".$stuObj["name"]."&recid=".$recruitmentId."&stuDet=".$stuObj["name"]."-".$row["stu_id"];
    	
    	//$emailBody .= "http://qav2.cs.odu.edu/Maheedhar/StudentRecruitmentTS/landingPageAdmin.php?".$queryString ;
    	//$emailBody .= "http://qav2.cs.odu.edu/Prod_StudentAppointmentSystem/home.php";
		$emailBody .= "http://qav2.cs.odu.edu/".SERVERHOST."_StudentAppointmentSystem/home.php";
		
		$emailBody.="<br/><br/> Sincerely,<br/>".$row["sta_fn"]." ".$row["sta_ln"];
    	//$array[]=array_map('utf8_encode',$row); 	
    	if(Send_Mail($adminList,$ccList,$emailSubject,$emailBody,"")){
    		echo "success";
    	}else{
    		echo "error";
    	}
    }
    
//echo json_encode($array);
mysqli_close($con);
?>