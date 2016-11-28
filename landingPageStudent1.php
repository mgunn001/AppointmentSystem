<?php
	include ('connect.php');
	$recruitmentId = @trim(stripslashes($_REQUEST["recid"]));
	session_start();
	$_SESSION["recruitmentId"] = $recruitmentId;
	
	
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$textToDisplay ="";
	$studentName = "";
	$recSelQuery = $conn->prepare('SELECT R.student_id AS stu_id, Stu.firstname as stu_fn,
 	Stu.lastname AS stu_ln,Stu.email AS stu_email,R.currentpost,R.semester,R.year,R.salarypaid,R.offerstatus,R.hours,Sta.faculty_id as sta_id, Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email,
 	R.isreappointed,R.project_id As proj_id FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id and R.id = ?');
	$recSelQuery->bind_param('s', $recruitmentId);
	$recSelQuery->execute();
	$recSelResult = $recSelQuery->get_result();
	while($row = $recSelResult->fetch_assoc()) {  	
		$studentName = $row["stu_fn"]." ".$row["stu_ln"]."(".$row["stu_id"].")"; 	
 	  	$textToDisplay.="Professor ".$row["sta_fn"]." ".$row["sta_ln"]." has initiated an offer for you to be a ".$row["currentpost"]." in ".$row["semester"]." ".$row["year"]." for ".$row["hours"]." hours a week";
 	  	$textToDisplay.= " and offer you a Salary of ".$row["salarypaid"].".";
	}

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
	          <a class="navbar-brand" href="#">Student Appointment Tracker</a>
	        </div>	       
	      </div>
	    </nav>
	   	<div id="cover" style="display:none;"></div>  
 		<div class="container" style="margin-top:5%">		
 			<h3>Hello <?php echo $studentName ?>!!</h3>
 			<well style="color: blue;font-size: 21px;"><p><?php echo $textToDisplay ?></p></well>
		   	<h4> Please click on the Accept Button to Accept the Offer or Decline Button to Decline the offer with the reason.</h4>
			<form action="#" class="form-horizontal formLPS"  Method="POST" id="offerUploadForm_LPS1"  role="form">
				   <div class="form-group">
				      		<div class="col-sm-1">
				        		<input type="button" class="btn btn-default btn-success" id="acceptOfferButt_LPS1" value="Accept" />
				        		<span id="success_upload_Accept" style="display:none;color:#80c9f1"> Email Sent Successfully.</span>
				      		</div>
				      		<div class="col-sm-1">
				        		<input class="btn btn-default btn-warning" type="button" id="delineOfferButt_LPS" value="Decline" />
				        		<span id="success_upload_Decline" style="display:none;color:#80c9f1"> Email Sent Successfully with Decline Msg.</span>
				      		</div>
				      		<div class="well col-sm-5 descriptionDivLPS" style="display:none;">				      					      			
				        		<label for="comment">Description:</label>
  								<textarea class="form-control " rows="5" id="declineDescription"></textarea>			
  								<input  type="button" class="btn btn-default btn-warning pull-right" id="delineOffer_Email_LPS" value="Submit" >  								
				      		</div>
			     </div>
			 </form>
		</div>
    </body>
 </html>


