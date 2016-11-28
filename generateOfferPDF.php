<?php

	define('FPDF_FONTPATH','font/');
	require('fpdf.php');
	include ('connect.php');
	include ('GlobalConstants.php');
	session_start();
	$user_id = $_SESSION['user_id'];
	if($_SESSION['user']){
    }
    else{ 
       header("location:login.php");
    }
    
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
    
    $recID =  $_REQUEST['recid'];
    $stmt = $conn->prepare('SELECT R.student_id AS stu_id, Stu.firstname as stu_fn,
 	Stu.lastname AS stu_ln,Stu.email AS stu_email,R.currentpost,R.tutionwaive,R.credithours,R.semester,R.year,R.startdate,R.enddate,R.salarypaid,R.fundingtype,Sta.faculty_id as sta_id, Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email,
 	R.isreappointed FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id and R.id = ?');

	$stmt->bind_param('s', $recID);
	$stmt->execute();
	$result = $stmt->get_result();
    $studentDet="";
    $professorName= "";
    $offerDet="";
    $semester="";
    $accYear="";
    $startDate="";
    $endDate = "";
    $curDate = date("m/d/Y");
   	$tutionWaive ="";
   	$creditHours = "";
   	$currentPost="";
    $salary="";
    $fundingtype="";
     while($row = $result->fetch_assoc()) {  
     	$currentPost=$row["currentpost"];
       	$studentDet = $row["stu_fn"]." ".$row["stu_ln"]." (".$row["stu_id"].")";
       	$professorName= $row["sta_fn"]." ".$row["sta_ln"];
    	$offerDet= $row["currentpost"]." for the Semester ".$row["semester"]." Academic Year ".$row["year"] ;
    	$semester = $row["semester"];
    	$accYear = $row["year"];
		$startDate = $row["startdate"];
		$endDate = $row["enddate"];
		$tutionWaive = $row["tutionwaive"];
		$creditHours = $row["credithours"];
		$salary= $row["salarypaid"];
		if($row["fundingtype"] == "1"){
			$fundingtype = "ODU";
		}else{
			$fundingtype = "ODURF";
		}
     }
     
    $line2 = "Dear ".$studentDet;
    
     $para1 = "I am pleased to offer you an assistantship for the semester ".$semester ." ".$accYear." i.e from ".$startDate." to ".$endDate;
     $para1.= " Please see below for information regarding your assistantship and financial award.";
     $para1.=" In addition, you will receive a ".$tutionWaive ."% tuition waiver for up to ". $creditHours."Credit Hours of graduate courses. ";
     
     
     $para2 = 'Old Dominion University (ODU) adheres to the “Resolution regarding Graduate Scholars, Fellows, Trainees and Assistants';
      $para2.=' of the Council of Graduate Schools of the United States. Please read the resolution at http://www.cgsnet.org/ckfinder/portals/0/pdf_CGS_Resolution.pdf to make';
     $para2.=' yourself aware of the responsibilities. Refer to the Graduate Assistantship Requirements document (http://www.cs.odu.edu/files/graduate-assistant-requirements.pdf) for information on course';
     $para2.=' registration and teaching assistant training requirements.';
     
     
     $para3 = "All registration must be completed prior to your hire paperwork being forwarded to the Payroll office for completion. Please contact Ariel Sturtevant at ";
     $para3 .="asturtev@odu.edu if you are unable to complete registration within 24 hours of signing this letter. ";
     $para3 .="Please note that, according to University policy,assistantship and/or fellowships (including tuition support) will be";
      $para3 .=" immediately terminated if you do not maintain a 3.00 grade point average.";
     
     
     $para4 = "This letter supersedes and replaces any earlier letter(s) for this period of appointment. Congratulations on receiving this appointment. I look forward to receiving your formal acceptance of the assistantship(s) and/or fellowship.";
     

    //$appointmentDet = "professor ".$professorName." has appointed a student ".$studentDet." for the post ";
    //$appointmentDet1 =$offerDet;
	class PDF extends FPDF
	{
		// Page header
		function Header()
		{
		    // Logo
		    $this->Image('Assets/Images/odulogo.JPG',75,6,40);
		    $this->Ln(20);
		}
		
		// Page footer
		function Footer()
		{
		    // Position at 1.5 cm from bottom
		    $this->SetY(-15);
		    // Arial italic 8
		    $this->SetFont('Arial','I',8);
		    // Page number
		    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		}
		
		
		function BasicTable($header, $data)
		{
		    // Header
		   // foreach($header as $col)
		    for($i=0;$i<count($header) ;$i++)
		    {
		    	if($i== 5){
		    		$this->Cell(48,7,$header[$i],1);
		    	}else{
		    		  $this->Cell(25,7,$header[$i],1);
		    	}
		    } 
		        
		    $this->Ln();
		    // Data
		  //  foreach($data as $col)
		    for($i=0;$i<count($data) ;$i++)
		    {
		       // foreach($row as $col)
		       if($i== 5){
		       	 $this->Cell(48,6,$data[$i],1);
		       }else{
		       	 $this->Cell(25,6,$data[$i],1);
		       }

		    }
		     $this->Ln();
		}
	}
	
	// Instanciation of inherited class
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Times','',12);
	
	/*$pdf->Cell(0,10,$appointmentDet,0,1);
	$pdf->Cell(0,10,$appointmentDet1,0,1);*/

	/*for($i=1;$i<=40;$i++)
	    $pdf->Cell(0,10,Offer_Text.$i,0,1);*/
	
	$pdf->Cell(0,10,$curDate,0,1);
	$pdf->Cell(0,10,$line2,0,1);

	$pdf->MultiCell(0,5,$para1);
	$pdf->Ln(4);
	$header = array('Postion', 'Award', 'ODU/RF', 'Report To',"Department","Date");
	$data = array($currentPost ,$salary,$fundingtype ,$professorName,"CS",$startDate." to ".$endDate);
	
	$pdf->BasicTable($header,$data);
	
	$pdf->Ln(4);
	$pdf->MultiCell(0,5,$para2);
	$pdf->Ln(2);
	$pdf->MultiCell(0,5,$para3);
	$pdf->Ln(2);
	$pdf->MultiCell(0,5,$para4);
	
	/*$pdf->Cell(0,10,$para2,0,1);

	$pdf->Cell(0,10,$para3,0,1);

	$pdf->Cell(0,10,$para4,0,1);*/

	   $lastlines = "Sincerely yours,
		Michele Weigle, PhD 
		Graduate Program Director
		Department of Computer Science
		UIN 
		Student’s Signature
		Date";
    $pdf->Ln();
	$pdf->Cell(0,10,"Sincerely yours,",0,1);
	$pdf->Ln();

	$pdf->Cell(0,10,"Michele Weigle, PhD,",0,1);
	$pdf->Cell(0,10,"Graduate Program Director",0,1);
	$pdf->Cell(0,10,"Department of Computer Science",0,1);
	$pdf->Ln(5);


	$pdf->Cell(0,10,"I understand and agree to the terms and requirements of this financial support award.",0,1);
	$pdf->Ln();
	$pdf->Cell(0,10,"UIN                                         Student’s Signature                                      Date",0,1);
	
	
	$pdf->Output();
?>

































