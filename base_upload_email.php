<?php 
require 'Send_Mail.php';

$uploaddir = 'Assets/Uploads/';
$uploadfile = $uploaddir . basename($_FILES['filetoUpload']['name']);

$result = move_uploaded_file($_FILES['filetoUpload']['tmp_name'], $uploadfile); // for this to work the destination folder must have edit permissions 
echo $uploadfile;
if ($result) {   
		
	$path =  $uploadfile;
	echo "File is valid, and was successfully uploaded.".$path;
	$to = "mgunn001@odu.edu";
	$subject = "Job Request";
	$body = "Here is my resume. Please find the below attachement.";
	Send_Mail($to,$subject,$body,$path);
	
} else {
    echo "Possible file upload attack!\n";
}


?>