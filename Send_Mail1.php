<?php
function Send_Mail1($toList,$ccList)
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
	$mail->IsHTML(true);           // set email format to HTML
	$mail->setFrom("studentrecruitment.csodu@gmail.com","StudentRecruitment_CSODU"); // name is optional
	
	foreach ($toList as $to) {
	    $mail->AddAddress($to["email"]);// name is optional
		 //echo $to["email"];			
		$mail->Subject= $to["emailSubject"];
		//echo "subject:".$to["emailSubject"]."<br />";
		$mail->Body= $to["emailBody"];
		//echo "body:".$to["emailBody"]."<br />";
		$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
		if(!$mail->Send())
		{
		//echo "Message could not be sent. <p>";
		//echo "Mailer Error: " . $mail->ErrorInfo;
		//echo "error";
		  return false;
		}
		$mail->ClearAddresses();  // to clear the previous emails address
	}

	foreach ($ccList as $cc) {
		
	 	$mail->AddAddress($cc["email"]);// name is optional
	  	//echo $cc["email"];			
		$mail->Subject = $cc["emailSubject"];
		//echo "subject:".$cc["emailSubject"]."<br />";
		$mail->Body = $cc["emailBody"];
		//echo "body:".$cc["emailBody"]."<br />";
		$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
		if(!$mail->Send())
		{
		//echo "Message could not be sent. <p>";
		//echo "Mailer Error: " . $mail->ErrorInfo;
		//echo "error";
		  return false;
		}
		$mail->ClearAddresses();  // to clear the previous emails address
	}
	
	return true;
}
?>

