<html>
  <head>
	<link href="http://fonts.googleapis.com/css?family=Montserrat"
		rel="stylesheet" type="text/css">
	<link href="http://fonts.googleapis.com/css?family=Lato"
		rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">		
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	
	<script>
	$(document).ready(function() {
		tinymce.init({ 
			selector:'textarea',		 
			plugins : 'image',	
			 file_picker_callback: function(callback, value, meta) {
			    // Provide file and text for the link dialog
			    if (meta.filetype == 'file') {
			      callback('mypage.html', {text: 'My text'});
			    }

			    // Provide image and alt text for the image dialog
			    if (meta.filetype == 'image') {
			      callback('myimage.jpg', {alt: 'My alt text'});
			    }

			    // Provide alternative source and posted for the media dialog
			    if (meta.filetype == 'media') {
			      callback('movie.mp4', {source2: 'alt.ogg', poster: 'image.jpg'});
			    }
			  }
		
		});
	
	});
	
	</script>
	
 </head>
 
<body>
<br /> <br />
<textarea class="form-control pocTextArea"> </textarea>

<iframe id="form_target" name="form_target" style="display:none"></iframe>

<form id="my_form" action="/upload/" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
	<input name="image" type="file" onchange="$('#my_form').submit();this.value='';">
</form>

<?php 
	/*echo "Inside testDummy <br/>";
	$authusername = $_SERVER['PHP_AUTH_USER'];
	echo "Hello ".$authusername;*/
	include('ldap_con.php');	
	//details from the GET request
	include ('connect.php');
	$searchVal = trim(stripslashes($_REQUEST['searchVal']));
	$type = trim(stripslashes($_REQUEST['type']));	
		
	$ldap_password = 'n@g10$@d';
	$ldap_username = 'nagiosadmin@CS.ODU.EDU';
	$ldap_connection = ldap_connect('ad.cs.odu.edu') or die("Could not connect");
	//echo $ldap_connection."hello";
	if (FALSE === $ldap_connection){
	    // Uh-oh, something is wrong...
	    echo "could connect";
	}else{
		//echo "connection Established";
	}

	// We have to set this option for the version of Active Directory we are using.
	ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
	ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.
	
	 if (TRUE === ldap_bind($ldap_connection, $ldap_username, $ldap_password)){
	
	  $ldap_dn = "DC=cs,DC=odu,DC=edu";
	 
	  $attr = array("givenname","sn","extensionAttribute1", "employeeNumber", "mail","memberof");		
	 }
	 
	 // type = 1 -> UIN
	 // type = 2 -> STUDENT NAME	
	 
 	if($type == "1"){
 		$uin = $searchVal;
 		 $filterStr= "(employeeNumber=".$searchVal.")";
 		 
 	}elseif($type == "2"){

 	}
	
 	//$filter= "(employeeNumber=00896342)";
  //$filter = $filterStr;
 $person = "Sabbir";
 
  $filter="(|(sn=$person*)(givenname=$person*))";
  
  $result = ldap_search($ldap_connection, $ldap_dn, $filter, $attr) or exit("Unable to search LDAP server");
  //echo "After LDap Search";
  $entries = ldap_get_entries($ldap_connection, $result);
  
/*   $beingMemeber = $entries[0]['memberof'];  
	$memArr = array();
 	for ($x = 0; $x < $entries[0]['memberof']["count"]; $x++) {		
 		$keyStr = $entries[0]['memberof'][$x];
 		$zeroIndex = explode(",",$keyStr);		
		 $arr = explode("=", $zeroIndex[0]);	
 		array_push($memArr,$arr[1]);				 
	} 
	if (in_array("phd", $memArr)){
		
	}*/
	
 	
  
	//echo "count-".count($entries);
	
		
/*	foreach ($entries as $person) {
		if($person['givenname'][0] != ""){
			 echo "FirstName:".$person['givenname'][0]."-".$person['employeeNumber'][0] ."<br />\n";
		}
	}*/
	
	//echo json_encode($entries); //R
		
/*  $firstname = $entries[0]['givenname'][0];
  $lastname = $entries[0]['sn'][0];
  $email = $entries[0]['extensionattribute1'][0];
  echo "<br />".$firstname." ".$lastname." ".$email;
  $array[] = $searchVal.",".$firstname.",".$lastname.",".$email;*/
  
  
  
/* if($firstname !="" && $email != "" ){
  		$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
		OR die ('Could not connect to MySQL: '.mysql_error());
	  	if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
	
		$stuInsQuery = "INSERT INTO Student(uin,firstname,lastname,email) VALUES ('$uin','$firstname','$lastname','$email')";
		if(mysqli_query($conn, $stuInsQuery)){

			echo "success- The student ". $firstname ." corresponding to UIN:".$uin." is found with LDAP Search and is added into Local DB.-".$firstname.",".$lastname;
		}else{
			echo "fail-No Student Found with this UIN.";
		}

	    mysqli_close($con);
	    ldap_unbind($ldap_connection);
  }*/
/*echo '<a href="Assets/Uploads/offerunsigned_213.pdf" target="_blank">view file</a>';*/
echo json_encode($entries); //Return the JSON Array

?>

</body>
</html>





























