
<?php
	include ('connect.php');
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$uin = $_POST['uin'];
		
		$firstname = ucwords(strtolower($_POST['firstname']));
		$lastname = ucwords(strtolower($_POST['lastname']));
		$email = $_POST['email'];
		$gradlevel = $_POST['gradlevel'];
	    $bool = true;
	    
	   // echo "username entered is:".$username."<br/>";
	   // echo "password entered is:".$password;
	 
		mysql_connect(DB_HOST, DB_USER,DB_PASSWORD) or die('Could not connect to MySQL: '.mysql_error()); //Connect to server
		mysql_select_db(DB_NAME) or die("Cannot connect to database"); //Connect to database
		
		if($bool) // checks if bool is true
		{
			$Query = "INSERT INTO Student(uin,firstname,lastname,email,gradlevel) VALUES ('$uin','$firstname','$lastname','$email','$gradlevel')";
			$insertQueryRes = mysql_query($Query); //Inserts the value to table Student
			
			if (!$insertQueryRes) {
				
				echo "fail";
				//echo $uin.",".$firstname.",".$lastname.",".$email;											
		   		//die('<br />something went wrong with Insertion: ' . mysql_error());
			}
			/*Print '<script>alert("Successfully Added the student !");</script>'; // Prompts the user
			Print '<script>window.location.assign("index.php");</script>'; // redirects to index.php*/
			echo "success";
			
		}

	}
	//mysqli_close($con);
?>