<?php
	
	$ccMailList = array();
	$ccMailList = $_REQUEST["ccmail"];
	//echo $ccMailList;
	//echo $ccMailList[0]."<br />";
	//echo $ccMailList[1]."<br />";
	$toMailList = array();
	$toMailList[0] = $_REQUEST["tomail"];
	//echo $toMailList;
	$recruitmentId = $_REQUEST["recid"];

	$stuDet = $_REQUEST["stuDet"];
	$stuDet = explode("-",$stuDet);
	//echo $toMailList[0]."<br />";;
	//$emailSubject ="offer letter for "."spring"." 2016"; // this it got to be dymamic, right now it is hardcoded
	//$emailBody = "Please find the attached offer, download to read and sign. <br />";
	//$emailBody.= "click on the below link to upload and accept the offer. <br/>";
	//$emailBody.="Thank you";
	session_start();
	$_SESSION["ccMailList"] =implode("|",$ccMailList);
	$_SESSION["toMailList"] = implode("|",$toMailList);
	$_SESSION["recruitmentId"] = $recruitmentId;
	
	//$_SESSION["emailSubject"] = $emailSubject;
	//$_SESSION["emailBody"] = $emailBody;
?>
<html>
    <head>
        <title>Student Recruitment TS - Page for Admin</title>
        <!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">		
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
		<!-- Latest compiled JavaScript -->
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="Assets/Script/landingPageScript.js"></script>
		<!--
		
		<script src="Assets/Script/sitescript.js"></script>
		-->
		<link rel="stylesheet" href="Assets/CSS/site.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">		
    </head>

 	<body>
	<nav class="navbar navbar-default navbar-inverse" role="navigation">
		<div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header" style="margin-left:6%;">
	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <a class="navbar-brand" href="#">Student Recruitment Tracker</a>
	        </div>	       
	      </div>
	  </nav>
 		<div class="container" style="margin-top:5%">
 			<div class="row">
 				<div class="row">
 					<div class="col-lg-12"> <h2> Please validate the student and prepare the Appointment Letter to email Student</h2></div><br />
 				</div>
 				
 				<div class="row">
	 				<div class="col-lg-12"> 
	 					<h5> Please validate the following Check List of the Student - <b> <?php echo $stuDet[0]." ".$stuDet[1]."(".$stuDet[2].") before Preparing Offer Letter."  ?> </b></h5>
	 				</div>						
 				</div>
 				
 				<div class="row">
	 				<div class="col-lg-5">					
						<form class="form-horizontal formLPA" action="#" Method="POST" id="offerUploadForm_LPA" name="offerUploadForm_LPA" enctype="multipart/form-data" role="form">						    						    
						   
							<div class="well col-sm-12">
							    <div class="form-group">
							      <div class="col-sm-offset-1 col-sm-9">
							        <div class="checkbox">
							         	<label><input type="checkbox" purp="I94"  value=""><b>Validate I94</b></label>
							        </div>
							      </div>
							    </div>
							    
							    <div class="form-group">
							      <div class="col-sm-offset-1 col-sm-9">
							        <div class="checkbox">
							         	<label><input type="checkbox" purp="Visa" value=""><b>Validate Visa Expiry</b></label>
							        </div>
							      </div>
							    </div>
							    
							    <div class="form-group">
							      <div class="col-sm-offset-1 col-sm-9">
							        <div class="checkbox">
							         	<label><input type="checkbox" purp="Passport" value=""><b>Validate Passport Expiry</b></label>
							        </div>
							      </div>
								</div>
								
							     <div class="form-group">
								      <div class="col-sm-offset-1 col-sm-9">
								         <input type="file" class="btn btn-primary" id="uploadOfferFile_LPA" name="filetoUpload">
								      </div>
							     </div>
							</div>
						     <div class="form-group">
						      	<div class="col-sm-offset-1 col-sm-8 ">
			        				<button type="button" class="btn btn-default btn-success" id="offerUpload_Email_LPA">Upload & Email</button>			        			
			      				</div>			      				
			    			 </div>
	   						
						</form>
	 				</div>
	 				<div class="col-lg-7">
	 				</div>

 				</div>	
			</div>
		</div>
    </body>
 </html>

