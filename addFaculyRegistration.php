
<?php
	include ('connect.php');
		/*$username = $_POST['username'];
		$password = $_POST['password'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$isAdmin = $_POST['isAdminCB'];*/
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];
		$firstname = $_REQUEST['firstname'];
		$lastname = $_REQUEST['lastname'];
		$email = $_REQUEST['email'];
		
		if($_REQUEST['isAdminCB'] == "on"){
			$isadmin = 1;
		}else{
			$isadmin = 0;
		}
	    $bool = true;
	    
	   // echo "username entered is:".$username."<br/>";
	   // echo "password entered is:".$password;
	 
		mysql_connect(DB_HOST, DB_USER,DB_PASSWORD) or die('Could not connect to MySQL: '.mysql_error()); //Connect to server
		mysql_select_db(DB_NAME) or die("Cannot connect to database"); //Connect to database
		$query = mysql_query("Select * from Staff"); //Query the users table
		while($row = mysql_fetch_array($query)) //display all rows from query
		{
			$table_staff = $row['username']; // the first username row is passed on to $table_staff, and so on until the query is finished
			//echo $table_staff;
			if($username == $table_staff) // checks if there are any matching fields
			{
				$bool = false; // sets bool to false
				//echo "Username already exist! Please use an alternate one";
				//Print '<script>alert("Username already exist! Please use an alternate one");</script>'; //Prompts the user
				//Print '<script>window.location.assign("login.php");</script>'; // redirects to register.php
			}
		}
	
		if($bool) // checks if bool is true
		{
			mysql_query("INSERT INTO Staff (firstname,lastname,username, password,email,isadmin) VALUES ('$firstname','$lastname','$username','$password','$email','$isadmin')"); //Inserts the value to table users
			//echo $isadmin;
			echo "Successfully Registered!";
			//Print '<script>alert("Successfully Registered!");</script>'; // Prompts the user
			//Print '<script>window.location.assign("login.php");</script>'; // redirects to login.php
		}else{
				echo "Username already exist! Please use an alternate one!";
		}

?>