<?php
	session_start();
	$recruitmentId =  @trim(stripslashes($_POST['appointmentID']));
	$uploaddir = 'Assets/Uploads/';
	$uploadfile = $uploaddir . basename($_FILES['filetoUpload']['name']);
	$uploaddir = 'Assets/Uploads/';
	$uploadfile = $uploaddir . basename($_FILES['filetoUpload']['name']);
	$uploadfile_newname = $uploaddir."offersigned_".$recruitmentId.".pdf";
	$uploadSucess = move_uploaded_file($_FILES['filetoUpload']['tmp_name'], $uploadfile_newname);
	if($uploadSucess){
		echo "success";
	}else{
		echo "fail";
	}
?>