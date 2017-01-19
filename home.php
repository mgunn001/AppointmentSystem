<html>
    <head>
    	  <meta name="description" content="Students Appointment System CS ODU">
		  <meta name="keywords" content="Appointment System,CS,ODU,Norfolk,Students,Tracking,Appointment,Maheedhar,Handson">
		  <meta name="author" content="Maheedhar,Handson">
        <title>Student Recruitment TS</title>
        <!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">		
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="Assets/Script/sitescript.js"></script>
		<link rel="stylesheet" href="Assets/CSS/site.css">
		<link rel="stylesheet" href="Assets/CSS/loginStyle.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css">				
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
   		<script src="Assets/Script/landingPageScript.js"></script>
    </head>
   <?php
	 session_start(); //starts the session
	 $isLoggedIn = false;
	   if($_SESSION['user']){ // checks if the user is logged in  
		   $user = $_SESSION['user']; //assigns user value
		   $isLoggedIn = true;
		   $user_id = $_SESSION['user_id'];
		   $loginUser_FullName = $_SESSION['loginUser_FullName'];
		   $isAdmin = False;
			$adminType = 0;
		   if($_SESSION['isAdmin'] == "True"){
		   		$isAdmin = True;
		   		$adminType = intval($_SESSION['adminType']);
		   }
	   }else{
	   	$isLoggedIn = false;
	   }

   ?>
    <body> 
      <!--this Modal popup is for showing the success Msgs -->
		<div class="modal fade" id="successModal" role="dialog">
		    <div class="modal-dialog modal-sm">
		      <div class="modal-content">
			        <div class="modal-header">
			          <!--<button type="button" class="close" data-dismiss="modal">&times;</button>
			          -->
			          <h4 class="modal-title">Information!</h4>
			        </div>
			        <div class="modal-body">
			          <p>This is a Success modal.</p>
			        </div>			       
		      </div>
		    </div>
	  	</div>
	
		<!--this Modal popup is for showing the error Msg-->
		<div class="modal fade" id="errorModal" role="dialog">
		    <div class="modal-dialog modal-sm">
		      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">Error!!</h4>
			        </div>
			        <div class="modal-body">
			          <p>This is a error Modal.</p>
			        </div>			       
		      </div>
		    </div>
	  	</div>		
	  	
	  	<!--this Modal popup is for conformation-->
		<div class="modal fade" id="confirmationModal" role="dialog">
		    <div class="modal-dialog modal-lg">
		      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">Information!!</h4>
			        </div>
			        <div class="modal-body">
			          <p>This is a Confirmation Modal.</p>
			        </div>		
			         <div class="modal-footer">
			        	<button type="button" class="btn btn-warning notConfirmed" data-dismiss="modal">No</button>
			        	<button type="button" class="btn btn-success confirmed" data-dismiss="modal">Yes</button>
			        </div>	       
		      </div>
		    </div>
	  	</div>	
	  	
	  	
	  				
	<nav class="navbar navbar-default navbar-inverse" role="navigation">
		<div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header" style="margin-left:6%;">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		       <a class="navbar-brand" href="home.php">Student Appointments Tracker</a>
		    </div>
		
		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			  <?php 			  
				  if($isLoggedIn){
				    echo '<ul class="nav navbar-nav">
			            <li class="active"><a href="#">Home</a>
			            </li>	           
			          </ul>';
			       }?>
		          
		      <ul class="nav navbar-nav navbar-right">
		      <?php	            
	            	//echo '<script>alert("result:'.$loginUser_FullName .'");</script>';	
	           	if(!$isLoggedIn){ // checks if the user is logged in  	     				  	  		       			     
	           		echo '<li class="dropdown loginDD">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
						<ul id="login-dp" class="dropdown-menu">
							<li>
								 <div class="row">
										<div class="col-md-12">
											 	<div class="form">		        		
													   <form action="#" method="POST" class="defalutForms register-form">
													      <input type="text" name="username" placeholder="username" required="required" />
													      <input type="password" name="password" placeholder="password" required="required"/>
													      <input type="text" name="firstname" placeholder="First Name" required="required" /> <br/>
												          <input type="text" name="lastname" placeholder="Last Name" required="required" /> <br/>
													      <input type="email" name="email" required="required" placeholder="email address"/>
													       <div class="form-group">
														        <label for="isAdminCB" class="control-label">Register as Admin</label>
														        <div>
														            <input type="checkbox" id="isAdminCB" name="isAdminCB" >
														        </div>
														    </div>
															<button id="submitButtRegister">create</button>
													      <p class="message">Already registered? <a href="#">Sign In</a></p>
													    </form>
													    
													    <form  action="#" method="POST" class="defalutForms login-form">
													      <input type="text" name="username" required="required" placeholder="username" />
													      <input type="password" name="password" required="required" placeholder="password"/>
													      <button id="submitButtLogin">Login</button>
													      <!-- <input type="button" class="btn btn-success" id="submitButtLogin" value="Login" />
													    <p class="message">Not registered? <a href="#">Register here</a></p> -->
													    </form>
												  </div>
										</div>
										
								 </div>
							</li>
						</ul>
			        </li>';
		
				 }
		      	 else{
				   	$compName = explode(",", $loginUser_FullName);
	            	$userTitle = "";
	            	if($isAdmin){
	            		$userTitle = "Admin";
	            	}else{
	            		$userTitle = "Professor";	            		
	            	}
	           		echo '<li><a href="#"> Hello '.$userTitle.' <b class="loggedinusername">'.$compName[0]." ".$compName[1].'!</b><span class="loggedInUID hidden">'.$user_id."-".$isAdmin."-".$adminType.'<span></a></li>';            		
	           		if($isAdmin){	
						 echo '<li class="dropdown settingsDD">
				          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Settings</b> <span class="caret"></span></a>
							<ul id="settings-dp" class="dropdown-menu" style="width:300px;">
								<li>
									 <div class="row">
											<div class="col-md-12">        																 
														    <form  action="#" class="form-horizontal" style="margin-left:7%;"> <br />														    
														      <div class="form-group">
															      <label class="control-label col-sm-4" for="currentTution">Tution Per Credit:</label>
															      <div class="col-sm-4">
															        <input type="number" class="form-control" id="currentTution" placeholder="Enter Tution">
															      </div>
															      <div class="col-sm-3">
															      	<input type="button" class="btn btn-default updateTutionButt" value="Update" style="display:none" />
															      </div>
															   </div>
																<div class="form-group">
															     
															       <div class="col-sm-5">
															       		<select class="form-control semseladmsett" style="width:107%;"><option value="Fall">Fall</option><option value="Spring">Spring</option><option value="Summer">Summer</option></select>
															       </div>
															       <div class="col-sm-6">
															       		<select class="form-control yearseladmsett"></select>
															       </div>
															    </div>															       

															    <div class="admdatesetup">
																    <div class="form-group">
																      <label class="control-label  col-sm-4" for="currentTution">Start Date:</label>
																      <div class="col-sm-7">
																        <input type="date" class="form-control" id="startDateAdmSett">
																      </div>
																    </div>
																    <div class="form-group">
																      <label class="control-label col-sm-4" for="currentTution">End Date:</label>
																      <div class="col-sm-7">
																        <input type="date" class="form-control" id="endDateAdmSett">
																      </div>
																    </div>
																     <div class="form-group">
																	      <div class="col-sm-3" style="margin-left:2%;">
																	      	<input type="button" class="btn btn-default updateSemDatesButt" value="Update Dates" style="display:none" />
																	      </div													        
															   		 </div>
														    </form>
											</div>
											
									 </div>
								</li>
							</ul>
				        </li>';
	           		}
	           		echo '<li><a href="logout.php"><b>logout</b></a></li>';
				  }				            
	           	?>
		         
		         
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
	</nav>		
		
	   <?php
	    if(!$isLoggedIn){
		   echo '<div class="container" style="margin-top:15%;">
				    <div class="jumbotron">
					    <h1>Student Appointments Tracker</h1>
					    <p>This is an Application that can be used by the faculty to track their Student Job Appointments.</p>
					 </div>
		    	</div>';
		  }
	    ?>
	    
	    <div id="cover" style="display:none;"></div>
	    <div class="container" style="width:100%;">	  
	    <div class="col-lg-12 mainTableDiv" style="margin-top:5%;display:<?php if($isLoggedIn){echo "block;";}else{echo "none;";} ?>">
      		<div class="well well-sm" "><b style="margin-left: 40%;">Appointed Students List</b></div>
      		
      			<?php	            
			           if(!$isAdmin){
			            		echo '<button type="button" class="addNewAppointmet btn btn-primary pull-right" style="margin-bottom: 1%"><i class="icon-plus"></i> Add Appointment</button>';  
			           }else{
			           		echo '<button type="button" class="addNewAppointmet btn btn-primary pull-right" title="Add Existing Appointment" style="margin-bottom: 1%"><i class="icon-plus"></i> Add Existing Appt</button>';  	           	
			           }        					            	
	           	?>
      		
      		<!--<button type="button" class="addNewAppointmet btn btn-primary pull-right" style="margin-bottom: 1%">Add Appointment</button>

			-->
			<table id="RecruitedStuTable" class="table display" cellspacing="0">
	    		<thead>
		    		<tr> 
			    		<th title="Student UIN">UIN</th>
			    		<th>Student Name</th>
			    		<th>Post</th>
			    		<th title="Sem Start Date">StartDate</th>
			    		<th title="Sem End Date">EndDate</th>
			    		<!--<th title="Semester">Sem</th>
			    		<th>Year</th> 
			    		--><th title="Tution Waive%">%T</th>
			    		<th title="No Of Credits">#Cr</th>
			    		<th title="Salary Amount $">$Sal</th>
			    		<th title="No Of Hours">Hrs</th>
			    		<th>Project</th>
			    		<th>Funding</th>
			    		<?php	            
			            	if($isAdmin){
			            		echo '<th>Faculty</th>';  
			            	}        					            	
	           			?>
	           			<th>Offer Status</th> 
	           			<th>Offer Docs</th> 
	           			<?php	            
			            	if($isAdmin){
			            		echo '<th>Action</th>';  
			            	}else{
			            		echo '<th>Re-Appoint</th>';			    
			            	}   					            	
	           			?>

		    		</tr>
	    		</thead>
	    		<tfoot>
		           <tr> 
			    		<th purp='UIN'>UIN</th>
			    		<th purp='Name'>Name</th>
			    		<th purp='Post'>Post</th>
			    		<th purp='SemStartDate'>StartDate</th>
			    		<th purp='SemEndDate'>EndDate</th>
			    		<!--<th purp='Semester'>Sem</th>
			    		<th purp='Year'>Year</th>
			    		--><th purp='Tution'>%T</th>
			    		<th purp='NoOfCredits'>#Cr</th>
			    		<th purp='Salary'>$Sal</th>
			    		<th purp='Hours'>Hrs</th>
			    		<th purp='Project'>Project</th>
			    		<th purp='Funding'>Funding</th>
			    		<?php	            
			            	if($isAdmin){
			            		echo '<th purp="Staff">Faculty</th>';  
			            	}        					            	
	           			?>
	           			<th purp='Offer Status'>Status</th>
	           			<th purp='Docs'>Offer Docs</th> 
			    		<?php	            
			            	if($isAdmin){
			            		echo '<th purp="Action">Action</th>';  
			            	}else{
			            		echo '<th purp="RApp">Re-Appoint</th>';			    
			            	}   					            	
	           			?>
		    	   </tr>
	        	</tfoot>
	        	<tbody>
	    		<?php 
	    			include ('connect.php');
	    			mysql_connect(DB_HOST, DB_USER,DB_PASSWORD) or die('Could not connect to MySQL: '.mysql_error()); //Connect to server
					mysql_select_db(DB_NAME) or die("Cannot connect to database"); //Connect to database
					$recQueryStr="";
					if($isAdmin){
						$recQueryStr = "SELECT R.id as rec_id , R.student_id AS stu_id, Stu.firstname as stu_fn, Stu.lastname AS stu_ln,Stu.email as stu_email,Stu.i9expiry as stu_i9expiry,";
						$recQueryStr.="R.currentpost,R.semester,R.year,R.tutionwaive,R.credithours,R.salarypaid,R.currenttution,R.offerstatus,R.hours,R.startdate,R.enddate,R.isfinanceverified,R.fundingtype,R.existingAppImportedBy,Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.faculty_id as sta_id,R.isreappointed,R.project_id ";
						$recQueryStr.="FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin ";
						$recQueryStr.="JOIN Staff Sta ON R.faculty_id = Sta.faculty_id";										
					}else{
						$recQueryStr = "SELECT R.id as rec_id , R.student_id AS stu_id, Stu.firstname as stu_fn, Stu.lastname AS stu_ln,Stu.email as stu_email,Stu.i9expiry as stu_i9expiry,";
						$recQueryStr.="R.currentpost,R.semester,R.year,R.tutionwaive,R.credithours,R.salarypaid,R.currenttution,R.offerstatus,R.hours,R.startdate,R.enddate,R.isfinanceverified,R.fundingtype,R.existingAppImportedBy,Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.faculty_id as sta_id,R.isreappointed,R.project_id ";
						$recQueryStr.="FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id ";
						$recQueryStr.="and R.faculty_id=".$user_id;						
					}
					echo $queryStr;							
					$query = mysql_query($recQueryStr);
								
					//$query = mysql_query("Select * from Recruitments"); //Query the users table
					while($row = mysql_fetch_array($query)) //display all rows from query
					{
					 	$projID = $row['project_id'];
				
						echo"<tr class='text_center_overflow dataRow' id='".$row['rec_id']."'>";
						echo"<td class='stuUIN' i9expiry=".$row['stu_i9expiry']." >".$row['stu_id']."</td>";
						echo"<td class='stuName' emailid='".$row['stu_email'] ."'>".$row['stu_fn'].",".$row['stu_ln']."</td>";
						//echo"<td class='stuEmail'>".$row['stu_email']."</td>";
						echo"<td class='stuPost'><span class='oSpan'>".$row['currentpost']."</span></td>";
						
						//----------------------- this block one is for Displaying Start & End Dates (Change Request)-----------------
						//$startDate =  date('m/d/Y',strtotime($row['startdate']));
						//$endDate =  date('m/d/Y',strtotime($row['enddate']));
						
						$startDate =  date('Y/m/d',strtotime($row['startdate']));
						$endDate =  date('Y/m/d',strtotime($row['enddate']));
						echo"<td class='stuStartDate' title='".$row['semester']." | ".$row['year']."'><span class='oSpan'>".$startDate ."</span></td>";
						echo"<td class='stuEndDate' title='".$row['semester']." | ".$row['year']."'><span class='oSpan'>". $endDate."</span></td>";
						
						//------------------------------------------------------------------------------------------------------------
						
						
						//-------------- removed because the start date and end date has come into existance --------------
						//echo"<td class='stuSem'><span class='oSpan'>".$row['semester']."</span></td>";
						//echo"<td class='stuYear'><span class='oSpan'>".$row['year']."</span></td>";
						
						// the following two are for Tutionwaiver and No OF Credits, added for the additional requirements
						if(!$isAdmin){
							echo"<td class='stuTWaive' currtutionfee='".$row['currenttution'] ."'><span class='oSpan'>".$row['tutionwaive']."</span></td>";		
							echo"<td class='stuNoOfCredits'><span class='oSpan'>".$row['credithours']."</span></td>";
							echo"<td class='stuSal'><span class='oSpan'>".$row['salarypaid']."</span></td>";
							
						}else{
							
							if(((int)$row['isreappointed'] == 1 && $row['offerstatus'] == "2")){
								if($adminType == 1){
									echo"<td class='stuTWaive' currtutionfee='".$row['currenttution'] ."'><input type='number' name='adminUpdatedTution' class='adminUpdatedTutionIP form-control width_100Per' placeholder='Tution%' required value='".$row['tutionwaive']."' /></td>";		
									echo"<td class='stuNoOfCredits'><input type='number' name='adminUpdatedCredits' class='adminUpdatedCreditsIP form-control width_100Per' placeholder='No Of Credits' required value='".$row['credithours']."' /></td>";														
									echo"<td class='stuSal'><input type='number' name='adminUpdatedSalary' class='adminUpdatedSalaryIP form-control width_100Per' placeholder='Salary' required value='".$row['salarypaid']."' /></td>";
								}else{
									echo"<td class='stuTWaive' currtutionfee='".$row['currenttution'] ."'><span class='oSpan'>".$row['tutionwaive']."</span></td>";			
									echo"<td class='stuNoOfCredits'><span class='oSpan'>".$row['credithours']."</span></td>";
									echo"<td class='stuSal'><span class='oSpan'>".$row['salarypaid']."</span></td>";
								}
							}else if($row['offerstatus'] == "2"){
								if($adminType == 1){
									echo"<td class='stuTWaive' currtutionfee='".$row['currenttution'] ."'><input type='number' name='adminUpdatedTution' class='adminUpdatedTutionIP form-control width_100Per' placeholder='Tution%' required value='".$row['tutionwaive']."' /></td>";		
									echo"<td class='stuNoOfCredits'><input type='number' name='adminUpdatedCredits' class='adminUpdatedCreditsIP form-control width_100Per' placeholder='No Of Credits' required value='".$row['credithours']."' /></td>";													
									echo"<td class='stuSal'><input type='number' name='adminUpdatedSalary' class='adminUpdatedSalaryIP form-control width_100Per' placeholder='Salary' required value='".$row['salarypaid']."' /></td>";
								}else{
									echo"<td class='stuTWaive' currtutionfee='".$row['currenttution'] ."'><span class='oSpan'>".$row['tutionwaive']."</span></td>";			
									echo"<td class='stuNoOfCredits'><span class='oSpan'>".$row['credithours']."</span></td>";
									echo"<td class='stuSal'><span class='oSpan'>".$row['salarypaid']."</span></td>";
								}

							}else{					
								echo"<td class='stuTWaive' currtutionfee='".$row['currenttution'] ."'><span class='oSpan'>".$row['tutionwaive']."</span></td>";			
								echo"<td class='stuNoOfCredits'><span class='oSpan'>".$row['credithours']."</span></td>";
								echo"<td class='stuSal'><span class='oSpan'>".$row['salarypaid']."</span></td>";
							}
						}
						
						echo"<td class='stuWHours'><span class='oSpan'>".$row['hours']."</span></td>";
						//echo"<td class='stuProj' projId='".$row['proj_id']."'>".$row['proj_name']."</td>";
						if($projID == ""){
							echo"<td class='stuProj' projId='0'><span class='oSpan'>None</span></td>";
						}else{							
							$projQueryStr="Select * FROM Projects WHERE id=".$projID;		
							$projQuery = mysql_query($projQueryStr);
							while($projRow = mysql_fetch_array($projQuery)){
								echo "<td class='stuProj' projid='".$projRow['id']."'><span class='oSpan'>".$projRow['name']."</span></td>";
							}							
						}	
						if(!$isAdmin){							
	                        if($row['fundingtype'] == "1"){
	                        	echo"<td class='stuFundingType' title='Funded by ODU Alone'><span class='oSpan' style='color:blue;'>ODU</span></td>";
	                        }else{
	                        	echo"<td class='stuFundingType' title='Funded by Both ODU & Research Foundation'><span class='oSpan' style='color:green;'>Both</span></td>";                        	
	                        }
						}else{
							if($row['offerstatus'] == "2"){
								
								if($adminType == 1){
								
									if($row['fundingtype'] == "1"){
			                        	echo"<td class='stuFundingType'><select class='form-control adminUpdatedFT'><option value='1' selected>ODU</option><option value='2'>ODU&amp;Research</option></select></td>";
			                        }else if($row['fundingtype'] == "2"){
			                        	echo"<td class='stuFundingType'><select class='form-control adminUpdatedFT'><option value='1'>ODU</option><option value='2' selected>ODU&amp;Research</option></select></td>";                        	
			                        }
								}else{
									if($row['fundingtype'] == "1"){
			                        	echo"<td class='stuFundingType' title='Funded by ODU Alone'><span class='oSpan' style='color:blue;'>ODU</span></td>";
			                        }else{
			                        	echo"<td class='stuFundingType' title='Funded by Both ODU & Research Foundation'><span class='oSpan' style='color:green;'>Both</span></td>";                        	
			                        }
								}
		                        
							}else{
								if($row['fundingtype'] == "1"){
		                        	echo"<td class='stuFundingType' title='Funded by ODU Alone'><span class='oSpan' style='color:blue;'>ODU</span></td>";
		                        }else{
		                        	echo"<td class='stuFundingType' title='Funded by Both ODU & Research Foundation'><span class='oSpan' style='color:green;'>Both</span></td>";                        	
		                        }
							}
						}
						if($isAdmin){
							echo"<td class='staName' staffid='".$row['sta_id']."'>".$row['sta_fn'].",".$row['sta_ln']."</td>";
						}
						$offerStatus = "";
						$offerStatusTitle = "";
						$textColor = "";
						if($row['offerstatus'] == "0"){
							$offerStatus = "Initiated";
							$textColor = "orange";
							$offerStatusTitle = "Student yet to accept the Offer.";
						}elseif($row['offerstatus'] == "1"){
							$offerStatus = "Released";
							$textColor = "brown";
							$offerStatusTitle = "Student received the Appoitnment Letter, yet to Sign and Return it.";
						}else if ($row['offerstatus'] == "2"){
							$offerStatus = "Accepted";
							$textColor = "blue";
							$offerStatusTitle = "Student Accepted the Offer, Admin has to send the Appointment to Student yet to sign";
						}else if ($row['offerstatus'] == "3"){
							$offerStatus = "Declined";
							$textColor = "black";
							$offerStatusTitle = "Student Declined the Offer.";
						}else{
							$offerStatus = "Employed";
							$textColor = "green";
							$offerStatusTitle = "Student been employed and working.";
						}
						
						echo"<td class='stuRecStatus' title='".$offerStatusTitle ."' style='color:".$textColor .";'>".$offerStatus."</td>";
						
					// this td is for documents link
						
						if($row['offerstatus'] == "1"){
							echo"<td class='stuRecDocs'><a href='Assets/Uploads/offerunsigned_".$row['rec_id'].".pdf' target='_blank'><i class='fa fa-file-pdf-o' title='Document released to student' aria-hidden='true'></i></a> </td>";
						}else if($row['offerstatus'] == "4"){
							if(intval($row['existingAppImportedBy'])!= 0){
								echo"<td class='stuRecDocs'><a href='Assets/Uploads/offersigned_".$row['rec_id'].".pdf' target='_blank'><i class='fa fa-file-pdf-o' title='Document Student signed' aria-hidden='true'></i></a> </td>";															
							}else{
								echo"<td class='stuRecDocs'><a href='Assets/Uploads/offerunsigned_".$row['rec_id'].".pdf' target='_blank'><i class='fa fa-file-pdf-o' title='Document released to student' aria-hidden='true'></i></a> &nbsp; <a href='Assets/Uploads/offersigned_".$row['rec_id'].".pdf' target='_blank'><i class='fa fa-file-pdf-o' title='Document Student signed' aria-hidden='true'></i></a> </td>";						
							}
						
						} else{
							echo"<td class='stuRecDocs' title='No Docs avaliable'>None</td>";
						}
						
						
						if(!$isAdmin){
							if((int)$row['isreappointed'] == 1 ){ // when reappointed by Proffesor											
								echo"<td class='reAppCB'><input type='checkbox' checked disabled/> <button type='button' class='reAppointButt btn btn-warning pull-right' style='display:none'>Appoint</button></td>";											
							}
							else if($row['offerstatus'] == "0" || $row['offerstatus'] == "1" || $row['offerstatus']== "2"){ // check box has to be disabled except the offer is Declined or Employeed
															
								echo"<td class='reAppCB'><input type='checkbox' disabled/> <button type='button' class='reAppointButt btn btn-warning pull-right' style='display:none'>Appoint</button></td>";												
							}
							else{						
								echo"<td class='reAppCB'><input type='checkbox' class='reAppointCB' /> <button type='button' class='reAppointButt btn btn-warning pull-right' style='display:none'>Appoint</button></td>";
							}
						}else{
							if(((int)$row['isreappointed'] == 1 && $row['offerstatus'] == "2") ||  $row['offerstatus'] == "2"){
								
								if($adminType == 1){
									
									if($row['isfinanceverified'] == '1'){
										echo "<td class='releaseOffAdmin' isfinanceverified=". $row['isfinanceverified']."><button type='button'  disabled title='Funds Availability Verified Success already' class='releaseOffButtAdm btn btn-warning pull-right'>Verify</button></td>";																														
									}else{
										echo "<td class='releaseOffAdmin' isfinanceverified=". $row['isfinanceverified']."><button type='button'  title='click to verify funds availability' class='releaseOffButtAdm btn btn-warning pull-right'>Verify</button></td>";																														
									}																	
								}else{
									echo"<td class='releaseOffAdmin' isfinanceverified=". $row['isfinanceverified']."><button type='button'  title='click to verify Academic Status and Release Appointment Letter' class='releaseOffButtAdm btn btn-warning pull-right'>Release</button></td>";																													
								}							
							}
							/*else if($row['offerstatus'] == "2"){ // merged with the above if itself, revisit if something goes wrong
								echo"<td class='reLeaseOffAdmin'><button type='button' class='releaseOffButtAdm btn btn-warning pull-right'>Release</button></td>";											
																
							}*/
							else{
								echo"<td class='reLeaseOffAdmin'><button type='button' disabled class='releaseOffButtAdm btn btn-warning pull-right'>Release</button></td>";											
							}
						}
						
						
						
						
					}
	    		?>
	    		</tbody>
	    	</table> 
		</div>

		</div>
		<div id="adminValModal" class="modal  fade" role="dialog">
		  <div class="modal-dialog modal-lg">
		
		    <!-- Modal content-->
		    <div class="modal-content">
			    <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			        <h4 class="modal-title">Prepare Offer to Release</h4>
			    </div>
			      
			      <div class="modal-body">
			      	<br />				
			 			<div class="row">
				 			<div class="col-lg-12">
				 				<h5> Please validate the following Check List of the Student - <label class="studentDetADM"> </label> before Preparing Offer Letter.</h5>
				 			</div>					
			 			</div>
			 				
			 			<div class="row">
			 				<div class="col-lg-2">
				 			</div>
				 			<div class="col-lg-8">					
								<div class="well  col-sm-12" style="padding-left:5%;">
								
									<form class="form-inline formLPA formFinancialAdmin" action="#" Method="POST" name="offerUploadForm_LPA">						    						    								
										    <div class="CheckBox" style="margin-top:-5px;">									 
												<label><input type="checkbox"  purp="Funds Availability" value="" class="fundsValidateCB"><b style="margin-left:4px;">Funds Availability</b></label>
										    </div>
										     
										    <div class="form-group" style="margin-left:20%;">
										        - Tution Cost: $
												<input class="form-control tutionCost" type="number" disabled style="width:20%;"/>
												Student Cost: $
												<input class="form-control studentsCost" type="number" disabled style="width:20%;"/>
										    </div>
										    <div class="form-group fundsValidateButtFG">
												<div class="col-sm-8">
									        		<button type="button" class="btn btn-default btn-success" id="fundsvalidateButt_LPFA" title="Click to update the funds Availablity"  the style="display:none;">Validate</button>
									      		</div>	
								      		</div>
									</form>  
								
									<form class="form-inline formLPA formGradAdmin" action="#" Method="POST" name="offerUploadForm_LPA">						    						    								
	
										    <div class="CheckBox" style="margin-top:-5px;">									 
												<label><input type="checkbox" purp="I9"  value=""><b style="margin-left:4px;">Validate I9</b> </label>
										    </div>
										     
										    <div class="form-group">
										      -   Expiry Date: 
												<input class="form-control studentsI9Expiry" type="date" /> &nbsp; <input type="button" class="btn btn-default i9ExpiryUpdateButt" value="Update" style="display:none;"/>
										    </div>
									</form>  
	
									<form class="form-inline formLPA formGradAdmin" action="#" Method="POST" name="offerUploadForm_LPA">						    						    								
	
										    <div class="CheckBox" style="margin-top:-5px;">									 
												<label><input type="checkbox" purp="Academic Status"  value=""><b style="margin-left:4px;">Student's Academic Status</b> </label>
										    </div>									     
									</form>  
									
									<form class="form-inline formLPA formGradAdmin" action="#" Method="POST" name="offerUploadForm_LPA">						    						    									
										    <div class="CheckBox" style="margin-top:-5px;">									 
												<label><input type="checkbox" purp="No Of Credits Registered"  value=""><b style="margin-left:4px;">Credits Registered Check</b> </label>
										    </div>										   
									</form>  
									
									 <form class="form-horizontal formGradAdmin">
										<div class="form-group">
											<div class="col-sm-5">
												<input type="file" class="btn btn-primary" style="width: 107%;" accept="application/msword,application/vnd.ms-powerpoint,application/pdf"  id="uploadOfferFile_LPA" name="filetoUpload">
											</div>
											<div class="col-sm-5">
												<a href="#" class="sendEmailToStudent" data-toggle="collapse" data-target="#emailNotifDiv" title="Send an email remainder to student">Send an Email </a><br />											
												<div id="emailNotifDiv" class="collapse well">
												
													    <div class="form-group">
													      <label for="emailNotifSub">Email Subject:</label>
													      <input type="text" id="emailNotifSub" class="form-control "placeholder="Enter email Subject">
													    </div>
													    <div class="form-group">
													      <label for="emailNotifBody">Body:</label>
													      <textarea class="form-control" id="emailNotifBody" placeholder="Enter Email Body"></textarea>
													    </div>													    
													    <input type="button" class="btn btn-default emailNotifStudentSend" value="Send"></input>

												</div>
												<a href="#" class="generateATag" target="_blank">Generate Appointment Letter!</a>												
											</div>
										</div>
	
										<div class="form-group">
											<div class="col-sm-8">
								        		<button type="button" class="btn btn-default btn-success" id="offerUpload_Email_LPA">Upload & Email</button>
								      		</div>	
								      	</div>
									 </form>
	
								</div>
				 			</div>
				 			<div class="col-lg-2"></div>
						<div>
			      </div>
		      
		      	</div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      </div>
		      
		      
		    	</div>		
		  </div>
		</div>
		</div>
		

		
		
	</body>
 </html>