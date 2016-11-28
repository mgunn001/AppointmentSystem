<?php

	$ldap_password = 'n@g10$@d';
	$ldap_username = 'nagiosadmin@CS.ODU.EDU';
	$ldap_connection = ldap_connect('ad.cs.odu.edu') or die("Could not connect");
	echo $ldap_connection."hello";
	if (FALSE === $ldap_connection){
	    // Uh-oh, something is wrong...
	    echo "could connect";
	}else{
		echo "connection Established";
	}

	// We have to set this option for the version of Active Directory we are using.
	ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
	ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.
	
	 if (TRUE === ldap_bind($ldap_connection, $ldap_username, $ldap_password)){
	
	  $ldap_dn = "DC=cs,DC=odu,DC=edu";
	 
	  $attr = array("givenname","sn","extensionAttribute1", "employeeNumber", "mail");
		echo $ldap_dn;
	 }

?>
