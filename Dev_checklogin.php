<?php
    session_start();
    $username = $_POST['username'];
    $password = $_POST['password'];
    //$username = $_REQUEST['username'];
    //$password =$_REQUEST['password'];
    $bool = true;
	include ('connect.php');
	mysql_connect(DB_HOST, DB_USER,DB_PASSWORD) or die('Could not connect to MySQL: '.mysql_error()); //Connect to server
	mysql_select_db(DB_NAME) or die("Cannot connect to database"); //Connect to database
	$query = mysql_query("Select * from Staff WHERE username='$username'"); // Query the users table
    $exists = mysql_num_rows($query); //Checks if username exists
    $table_users = "";
    $table_password = "";
    $login_userId = "";
    if($exists > 0) //IF there are no returning rows or no existing username
    {
       while($row = mysql_fetch_assoc($query)) // display all rows from query
       {
          $table_users = $row['username']; // the first username row is passed on to $table_users, and so on until the query is finished
          $table_password = $row['password']; // the first password row is passed on to $table_password, and so on until the query is finished
       	  $login_userId = $row['faculty_id'];
       	  $loginUser_FullName = $row['firstname'].",".$row['lastname'];
 			//echo '<script>alert("result:'.$loginUser_FullName .'");</script>';	            
       	  
       	  $isAdmin = False;
       	  $adminType="";
       	  if($row['isadmin'] == "1" || $row['isadmin'] == "2"){ // isAdmin - 1->Financial, isAdmin -2 -> Graduate Admin  
       	  	 $isAdmin = True;
       	  	 $adminType=$row['isadmin'];
       	  }
       	  $login_userEmaild = $row['email'];
       }
       if(($username == $table_users) && ($password == $table_password))// checks if there are any matching fields
       {
          if($password == $table_password)
          {
             $_SESSION['user'] = $username;//set the username in a session. This serves as a global variable
             $_SESSION['loginUser_FullName'] = $loginUser_FullName;
             $_SESSION['user_id'] = $login_userId;
             $_SESSION['user_email'] = $login_userEmaild;
             $_SESSION['isAdmin'] = "False";
             if($isAdmin){
             	 $_SESSION['isAdmin'] = "True";
             	 $_SESSION['adminType'] = $adminType;
             }
             //header("location: home.php"); // redirects the user to the authenticated home page
          }
       }
       else
       {
         echo("Try Again! Invalid Password.");
       }
    }
    else
    {
      echo("Try Again! Invalid credentails.");
    }
?>