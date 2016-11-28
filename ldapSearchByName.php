
<?php 
	//include('ldap_con.php');	
	//details from the GET request
	include ('connect.php');
	$searchPerson = trim(stripslashes($_REQUEST['searchVal']));
	
		
	$ldap_password = 'n@g10$@d';
	$ldap_username = 'nagiosadmin@CS.ODU.EDU';
	$ldap_connection = ldap_connect('ad.cs.odu.edu') or die("Could not connect");
	//echo $ldap_connection."hello";
	if (FALSE === $ldap_connection){
	    // Uh-oh, something is wrong...
	    echo "fail";
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


  $filter="(|(sn=$searchPerson*)(givenname=$searchPerson*))";
  $result = ldap_search($ldap_connection, $ldap_dn, $filter, $attr) or exit("Unable to search LDAP server");
  //echo "After LDap Search";
  $entries = ldap_get_entries($ldap_connection, $result);
  //echo "count-".count($entries);

	/*	foreach ($entries as $person) {
		if($person['givenname'][0] != ""){
			 echo "FirstName:".$person['givenname'][0]."-".$person['employeeNumber'][0] ."<br />\n";
		}
	}*/
	ldap_unbind($ldap_connection);
	echo "success--";
	echo json_encode($entries); //R

  
  
/*  if($firstname !="" && $email != "" ){
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

 // echo json_encode($array); //Return the JSON Array

?>































