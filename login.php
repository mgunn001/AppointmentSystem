<html>
    <head>
        <title>Student Recruitment System - Login</title>
        <!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- Latest compiled JavaScript -->
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="Assets/Script/loginScript.js"></script>
		<link rel="stylesheet" href="Assets/CSS/loginStyle.css">
    </head>
    <!--<body>
        <h2>Login Page</h2>
        <a href="index.php">Click here to go back </a><br/><br/>
        <form action="checklogin.php" method="POST">
           Enter Username: <input type="text" name="username" required="required" /> <br/>
           Enter password: <input type="password" name="password" required="required" /> <br/>
           <input type="submit" value="Login"/>
        </form>
    </body>-->
    <body>
		<div class="login-page">
		  <div class="loginRegform">
		        		
			   <form action="addFaculyRegistration.php" method="POST" class="defalutForms register-form">
			      <input type="text" name="username" placeholder="username" required="required" />
			      <input type="password" name="password" placeholder="password" />
			      <input type="text" name="firstname" placeholder="First Name" required="required" /> <br/>
		          <input type="text" name="lastname" placeholder="Last Name" required="required" /> <br/>
			      <input type="email" name="email" required="required" placeholder="email address"/>
			       <div class="form-group">
				        <label for="isAdminCB" class="control-label">Register as Admin</label>
				        <div>
				            <input type="checkbox" id="isAdminCB" name="isAdminCB" >
				        </div>
				    </div>
					<button>create</button>
			      <p class="message">Already registered? <a href="#">Sign In</a></p>
			    </form>
			    
			    <form  action="checklogin.php" method="POST" class="defalutForms login-form">
			      <input type="text" name="username" required="required" placeholder="username" />
			      <input type="password" name="password" required="required" placeholder="password"/>
			      <button>login</button>
			      <p class="message">Not registered? <a href="#">Register here</a></p>
			    </form>
		  </div>
		</div>
	</body>
    
</html>

	