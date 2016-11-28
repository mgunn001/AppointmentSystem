<?php

	require("class.phpmailer.php");
	session_start();
	//echo $_SESSION['user'];
	if($_SESSION['user']){
  		//echo "Session exist";
    }
    else{ 
      header("location:home.php");
      //echo "else Part";
 	}

		
	  $studentemailid = @trim(stripslashes($_POST['studentemailid']));
       $emailSub= @trim(stripslashes($_POST['emailsub']));       
       $emailBody = @trim(stripslashes($_POST['emailbody']));
        $facultyName = @trim(stripslashes($_POST['sendingfaculty']));	
       $emailBody.="<br /><br />This mail is institated by ".$facultyName."<br /> Thank you.";
	 
/*		echo "hello";
	   	echo $studentemailid;
		echo $emailSub;
		echo $emailBody;
		echo $facultyName;
		echo " "*/
	 	$mail = new PHPMailer();
		$mail->SMTPDebug = false;                               // Enable verbose debug output
		$mail->Port = '587';
		$mail->IsSMTP();     // set mailer to use SMTP
		$mail->Host = gethostbyname('smtp.gmail.com');  // specify main and backup server
		$mail->SMTPAuth = true;     // turn on SMTP authentication
		
		$mail->Username = "studentrecruitment.csodu@gmail.com";  // SMTP username
		$mail->Password = "Srts@123"; // SMTP password
		$mail->SMTPSecure= 'tls';
		$mail->IsHTML(true);           // set email format to HTML
		$mail->setFrom("studentrecruitment.csodu@gmail.com","StudentRecruitment_CSODU"); // name is optional
		
		    $mail->AddAddress($studentemailid);// name is optional
			 //echo $to["email"];			
			$mail->Subject= $emailSub;
			//echo "subject:".$to["emailSubject"]."<br />";
			$mail->Body= $emailBody;
			//echo "body:".$to["emailBody"]."<br />";
			$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
			if(!$mail->Send())
			{
			  echo "fail";
			}else{
				echo "success";
			}
			$mail->ClearAddresses();  // to clear the previous emails address

?>
