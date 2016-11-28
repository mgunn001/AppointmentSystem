<?php

	$recruitmentId = $_REQUEST["recid"];
	session_start();
	$_SESSION["recruitmentId"] = $recruitmentId;
?>
<html>
    <head>
        <title>Student Recruitment TS - Page for Student</title>
        <!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">		
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
		<!-- Latest compiled JavaScript -->
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="Assets/Script/sitescript.js"></script>
		<script src="Assets/Script/landingPageScript.js"></script>
		<link rel="stylesheet" href="Assets/CSS/site.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">		
    </head>

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
	   	<div id="cover" style="display:none;"></div>  
 		<div class="container" style="margin-top:5%">			
		   	<h2> Please Sign and Upload the Appointment letter, indicating the offer acceptance</h2>
			<form action="#" class="form-horizontal formLPS"  Method="POST" id="offerUploadForm_LPS" name="offerUploadForm_LPS" enctype="multipart/form-data" role="form">
			   
			   <div class="form-group">
					<div class="col-sm-3">
						    <input type="file" class="btn btn-primary" accept="application/msword,application/vnd.ms-powerpoint,application/pdf" id="uploadOfferFile_LPS" name="filetoUpload">
					</div>
			    </div>

				    <div class="form-group">
				      		<div class="col-sm-2">
				        		<input type="button" class="btn btn-default btn-success" id="offerUpload_Email_LPS" value="Submit" />		        	
				      		</div>
			     	</div>
			 </form>
		</div>
    </body>
 </html>

