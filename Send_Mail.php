<?php
function Send_Mail($toList,$ccList,$subject,$body,$attachmentPath)
{
	require("class.phpmailer.php");
	
	$mail = new PHPMailer();
	
	$mail->SMTPDebug = false;                               // Enable verbose debug output
	$mail->Port = '587';
	$mail->IsSMTP();     // set mailer to use SMTP
	$mail->Host = gethostbyname('smtp.gmail.com');  // specify main and backup server
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	
	$mail->Username = "studentrecruitment.csodu@gmail.com";  // SMTP username
	$mail->Password = "Srts@123"; // SMTP password
	$mail->SMTPSecure= 'tls';
		
	$mail->setFrom("studentrecruitment.csodu@gmail.com","StudentRecruitment_CSODU"); // name is optional
	
	foreach ($toList as $to) {
	    $mail->AddAddress($to["email"]);// name is optional
	  // echo $to["email"];
	}
	
	foreach ($ccList as $cc) {
	    $mail->AddCC($cc["email"]);// name is optional
	  //  echo $cc["email"];
	}
	
	if($attachmentPath !=""){
		$mail->AddAttachment($attachmentPath); // there can be optional name
	}
	   
	$mail->IsHTML(true);           // set email format to HTML
	
	$mail->Subject = $subject;
	//echo "subject:".$subject."<br />";
	$mail->Body    = $body;
	//echo "body:".$body."<br />";
	$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
	
	if(!$mail->Send())
	{
	   //echo "Message could not be sent. <p>";
	  // echo "Mailer Error: " . $mail->ErrorInfo;
	   //echo "error";
	  return false;
	}
	else{
		//echo "Message has been sent";
		return true;
	}
	
	

}
?>

