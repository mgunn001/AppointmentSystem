<?php
require 'Send_Mail.php';

$to = "mkuku002@odu.edu";

$name = "dummy"; 

$subject = "testing email sending"; 


//$body = "dummy inside a body, just for testing <br /> click on the following link <br /> http://qav2.cs.odu.edu/Prod_StudentAppointmentSystem/testDummy.php";

$body = "dummy inside a body, just for testing <br /> click on the following link <br /> http://qav2.cs.odu.edu/".SERVERHOST."_StudentAppointmentSystem/testDummy.php";

Send_Mail($to,$subject,$body,"Assets/Uploads/Color-Bars.jpg");
//echo "hello how r u ?";
?>







