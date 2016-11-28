
<?php 
	//details from the GET request
	include ('connect.php');
	$searchVal = trim(stripslashes($_REQUEST['searchVal']));
		
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
	
	  $ldap_dn = "OU=Grad,OU=students,DC=cs,DC=odu,DC=edu";
	 
	  $attr = array("givenname","sn","extensionAttribute1", "employeeNumber", "mail","memberof");		
	 }
	 	 
	 // type = 1 -> UIN
	 // type = 2 -> STUDENT NAME	
	 

 	$uin = $searchVal;
 	$filterStr= "(employeeNumber=".$searchVal.")";
 
	 
  $filter = $filterStr;
  $result = ldap_search($ldap_connection, $ldap_dn, $filter, $attr) or exit("Unable to search LDAP server");
  //echo "After LDap Search";
  $entries = ldap_get_entries($ldap_connection, $result);
  
  $beingMemeber = $entries[0]['memberof'];  
	$memArr = array();
 	for ($x = 0; $x < $entries[0]['memberof']["count"]; $x++) {		
 		$keyStr = $entries[0]['memberof'][$x];
 		$zeroIndex = explode(",",$keyStr);		
		 $arr = explode("=", $zeroIndex[0]);	
 		array_push($memArr,$arr[1]);				 
	} 
	$gradlevel = "grad";
	// here undergrad is not taken care yet, have to take care of it
	if (in_array("phd", $memArr)){
		$gradlevel = "phd";
	}

  $firstname = $entries[0]['givenname'][0];
  $firstname = ucwords(strtolower($firstname));
  $lastname = $entries[0]['sn'][0];
  $lastname = ucwords(strtolower($lastname));
  $email = $entries[0]['extensionattribute1'][0];
  //$beingMemeber = $entries[0]['memberof'];
  
  //echo $entries[0]['memberof']["count"];
  
  //echo "<br />".$firstname." ".$lastname." ".$email;
  $array[] = $searchVal.",".$firstname.",".$lastname.",".$email;
  
  if($firstname !="" && $email != "" ){
  		$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
		OR die ('Could not connect to MySQL: '.mysql_error());
	  	if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
	
		$stuInsQuery = "INSERT INTO Student(uin,firstname,lastname,email,gradlevel) VALUES ('$uin','$firstname','$lastname','$email','$gradlevel')";
		if(mysqli_query($conn, $stuInsQuery)){

			echo "success- The student ". $firstname ." corresponding to UIN:".$uin." is found with LDAP Search and is added into Local DB.-".$firstname.",".$lastname."-".$gradlevel;
		}else{
			echo "fail-No Student Found with this UIN in and above the Grad Level.";
		}

	    mysqli_close($con);
	    ldap_unbind($ldap_connection);
  }else{
  	echo "fail-No Student Found with this UIN in and above the Grad Level.";
  }

  
  
  
 // echo json_encode($array); //Return the JSON Array

?>































