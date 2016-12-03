

function start()
{
	$(document).ready(function() {
        var curRecID = "";
        var curStudentUIN = "";
        var parentTR ="";
        var admUpdatedTution = 0;
        var admUpdatedCredits = 0;
        var admUpdatedSalary = 0;
        var admUpdatedFT = 0;
        var studentEmail = "";
      
	   	 $('#adminValModal').on('show.bs.modal', function (e) {	 		
	   		if( (isNaN(admUpdatedTution) && isNaN(admUpdatedCredits)) || admUpdatedTution <=0 || admUpdatedCredits <=0 ){
				 $("#adminValModal").modal("hide");					
			}
		 });	 
        
        
        
		//code for admin credentails goes here 
		$(document).on("click",".releaseOffButtAdm",function(event){
			$(".generateATag").attr("href","#");
			parentTR = $(this).parents("tr");		
			if(adminType == 1){
				admUpdatedTution = parseInt($.trim(parentTR.find(".stuTWaive input").val()));
				admUpdatedCredits = parseInt($.trim(parentTR.find(".stuNoOfCredits input").val()));
				admUpdatedSalary = parseInt($.trim(parentTR.find(".stuSal input").val()));
				admUpdatedFT = parseInt($.trim(parentTR.find(".stuFundingType .adminUpdatedFT").val()));
				studentEmail = parentTR.find(".stuName").attr("emailid");
			
				if((isNaN(admUpdatedTution) && isNaN(admUpdatedCredits) && isNaN(admUpdatedSalary)) || admUpdatedTution <=0 || admUpdatedCredits <=0 || admUpdatedSalary <= 0){				
					errorPopUp("Please fill out the Tution and Credits fields properly.");				
					return false;				
				}
			}
			
			if(adminType == 1){
				$(".fundsValidateCB").prop("disabled",false);
				
				if($(this).parents("td").attr("isfinanceverified") != '1'){
					 $(".fundsValidateCB").prop("checked",false);
				}								
				$(".tutionCost").val( (currTutionPSem * admUpdatedTution * admUpdatedCredits)/100 );
				$(".studentsCost").val(parentTR.find(".stuSal input").val());
				$(".formGradAdmin").hide();
				$(".formFinancialAdmin").show();
			}else{
				$(".formGradAdmin").show();			
				
				var aHRefForPDFGen = "/"+SERVERHOST+"_StudentAppointmentSystem/generateOfferPDF.php?recid="+parentTR.attr("id");
				//var aHRefForPDFGen = "/Prod_StudentAppointmentSystem/generateOfferPDF.php?recid="+parentTR.attr("id");
				$(".generateATag").attr("href",aHRefForPDFGen);				
				$(".formFinancialAdmin").hide();
			}
			$('#adminValModal').modal("show");
			curRecID = parentTR.attr("id");
			curStudentUIN = $.trim(parentTR.find(".stuUIN").text());
			$(".studentsI9Expiry").val($.trim(parentTR.find(".stuUIN").attr("i9expiry")));
			$(".studentDetADM").html(parentTR.find(".stuName").text()+" ("+parentTR.find(".stuUIN").text()+") ");
		
			//alert("clicked on release Button");
		});
		
		
		$(document).on("click",".fundsValidateCB",function(event){	
			if($(this).prop("checked")){
				$("#fundsvalidateButt_LPFA").show()
			}else{
				$("#fundsvalidateButt_LPFA").hide()
			}
			
		});
		
		
		
		// for financial admin, on click of validate funds checkbox
		$(document).on("click","#fundsvalidateButt_LPFA",function(event){		
			var parentTrUnderVer = $("#RecruitedStuTable tbody").find("#"+curRecID);
			
			if(!$(".fundsValidateCB").prop("checked")){
				errorPopUp("Please check the Funds Availablity, before clicking on validate.");				
				return false;	
			}
			// here there is one feature to be accomplished that, the financial admin must be able to update the credits and the %T - have to revisit
	        
			var data = {};       	
       	 	data["recId"] = curRecID;
       		data["admUpdateTution"] = admUpdatedTution;
       		data["admUpdatedSal"] = admUpdatedSalary;
       		data["admUpdatedFT"] = admUpdatedFT;
       		data["admUpdatedCredits"] = admUpdatedCredits;
			$('#cover').show();
			
			$.post('/'+SERVERHOST+'_StudentAppointmentSystem/updateDetByAdmin1.php',data,function (data){
			//$.post('/Prod_StudentAppointmentSystem/updateDetByAdmin1.php',data,function (data){

				 $('#cover').hide();
				    if($.trim(data) == "success"){
				    	parentTrUnderVer.find(".releaseOffAdmin").attr("isfinanceverified","1");
				    	//parentTrUnderVer.find(".stuTWaive input").attr("disabled",true);
				    	//parentTrUnderVer.find(".stuNoOfCredits input").attr("disabled",true);
				    	parentTrUnderVer.find(".releaseOffAdmin button").attr("disabled",true);
				    	parentTrUnderVer.find(".releaseOffAdmin button").attr("title","Funds Availability Verified Success already");
				    	$("#fundsvalidateButt_LPFA").hide();
				    	$(".fundsValidateCB").prop("disabled",true);
	    				successPopUp("Funds Availability verification is updated successfully");
	    			 }else{
	    					//alert("error: some problem while updating the I9 Expiry");
	    					errorPopUp("some problem while updating the Funds Availability verification.");
	    			 }
			});		
		});
		
		
		
		
	    
	    var today = new Date().toISOString().split('T')[0];
	    $(".studentsI9Expiry").attr('min', today);
	    
	    // registring the trigger on I9 expiry date change
	    $(document).on("change",".studentsI9Expiry",function(){	    
	    	$(".i9ExpiryUpdateButt").show();	    	
	    });
	    
		$(document).on("click",".i9ExpiryUpdateButt",function(){	 
			    	
			    	var currI9Expiry = $.trim($(".studentsI9Expiry").val());
			    	if( currI9Expiry == ""){
			    		//alert("Error: Give a proper Expiry Date.");
			    		errorPopUp("Please give a proper Expiry Date.");	
			    		return false;
			    	}
			    	
			    	$.ajax({
				          type: "GET",
				          url: "/"+SERVERHOST+"_StudentAppointmentSystem/updateSettingsAdmin.php?action=3&currI9Expiry="+currI9Expiry+"&studentUIN="+curStudentUIN,
				         // url: "/Prod_StudentAppointmentSystem/updateSettingsAdmin.php?action=3&currI9Expiry="+currI9Expiry+"&studentUIN="+curStudentUIN,
				          dataType: "text",
				          success: function( data, textStatus, jqXHR) {		
			    			 $('#cover').hide();
			    			 if($.trim(data) == "success"){
			    				 //alert("I9Expiry is updated successfully");
			    				 successPopUp("I9Expiry is updated successfully");
			    				 parentTR.find(".stuUIN").attr("i9expiry",$(".studentsI9Expiry").val());
			    				  $(".i9ExpiryUpdateButt").hide();
			    			 }else{
			    					//alert("error: some problem while updating the I9 Expiry");
			    					errorPopUp("some problem while updating the I9 Expiry.");
			    			 }

						    // window.location.href = "http://qav2.cs.odu.edu/Maheedhar/StudentRecruitmentTS/home.php";
				          },
			  			  error: function( data, textStatus, jqXHR) {
				        	  $('#cover').hide();
			  		      	  //alert("error: some problem while updating the I9 Expiry");
				        	  errorPopUp("some problem while updating the I9 Expiry.");
			  		      }
			    	}); 
			});
		
		
		
		
        // to upload Offer Letter and email to student from Admin CC Professor, Student
        $('#offerUpload_Email_LPA').click(function() {
        	var isValidated = true;
        	 $(".formGradAdmin").find("input:checkbox").each(function(){
				 if(!$(this).is(":checked")){
					 //alert("please verify the validation Of Student "+$(this).attr("purp")+" and Check the box");
					 errorPopUp("please verify the validation Of Student "+$(this).attr("purp")+" and Check the box");
					 isValidated = false;
					 return false;
				 }
			 });
        	 
        	 if(!isValidated){
        		 return false;
        	 }
        	 
	         if( $("#uploadOfferFile_LPA").val() == ""){
	        	//alert("please choose the Appointment Letter to Email Student");
				errorPopUp("please choose the Appointment Letter to Email Student");
				return false;
	         }
	         $('#cover').show();	  
	         $.ajax({
		          type: "GET",
		         //url: "/Prod_StudentAppointmentSystem/updateSettingsAdmin.php?action=5&recid="+curRecID,
		          url: "/"+SERVERHOST+"_StudentAppointmentSystem/updateSettingsAdmin.php?action=5&recid="+curRecID,
		          dataType: "text",
		          success: function( data, textStatus, jqXHR) {		
	    			 $('#cover').hide();
	    			 if($.trim(data).split("-")[0] == "Success"){
	    				 if($.trim(data).split("-")[1] == "1"){
	    					 var formData = new FormData();
	    		              formData.append('filetoUpload', $('#uploadOfferFile_LPA')[0].files[0]);
	    		              formData.append('recruitmentID',curRecID);
	    		               /*formData.append('admUpdatedTution',admUpdatedTution);
	    		               formData.append('admUpdatedCredits',admUpdatedCredits);
	    		               formData.append('admUpdatedSalary',admUpdatedSalary);
	    		               formData.append('admUpdatedFT',admUpdatedFT);*/
	    		              $('#cover').show();
	    		              $.ajax({
	    		                  url: 'emailStudent.php',
	    		                  type: 'POST',
	    		                  data: formData,
	    		                  processData: false,
	    		                  contentType: false,
	    		                  success: function(data) {
	    		            		 $('#cover').hide();
	    			                	if($.trim(data) == "success"){	     
	    			                		 //alert("Email sent successfully with the attached Offer to the Student!");
	    			     				     //window.location.href = "http://qav2.cs.odu.edu/Maheedhar/StudentRecruitmentTS/home.php"
	    			     				    successPopUpWithRD("Email sent successfully with the attached Offer to the Student!");
	    			                         //$('#success_upload').show();	                       
	    			                  		//$('body').append('<div id="over" style="position: absolute;top:0;left:0;width: 100%;height:100%;z-index:2;opacity:0.4;filter: alpha(opacity = 50)"></div>');
	    								}
	    								else{
	    									//alert("error: some problem while sending an email!");
	    									errorPopUp("error: some problem while sending an email!");
	    								}
	    		                  },
	    		                  error: function(xhr,error){    
	    		                	  $('#cover').hide();
	    		  	    		    //alert('Holy errors, testFileUpload!');
	    		  	    		    errorPopUp("error: some problem while sending an email!");
	    		                  }
	    		              });
	    				 }else{
	    					 errorPopUp("Funds Availabilty check isn't performed yet, So please contact the Financial Admin");
	    					 return false;
	    				 }    				 
	    				 
	    			 }else{
	    					//alert("error: some problem while updating the I9 Expiry");
	    					errorPopUp("some problem while trying to get the FundsAvailability Check.");
	    					return false;
	    			 }

				    // window.location.href = "http://qav2.cs.odu.edu/Maheedhar/StudentRecruitmentTS/home.php";
		          },
	  			  error: function( data, textStatus, jqXHR) {
		        	  $('#cover').hide();
	  		      	  //alert("error: some problem while updating the I9 Expiry");
		        	  errorPopUp("some problem while trying to get the FundsAvailability Check.");
	  		      }
	    	});
	         
	         
	         
	         
              
          });
        
        
        // Student to Accept or Deny the offer, which intimates the Admin on accept and intimates the professors on Deny -- Change Request
        $('#acceptOfferButt_LPS1').click(function() {  
        	$('#cover').show();
              $.ajax({
                  url: 'acceptAppointment1.php',
                  type: 'GET',                
                  processData: false,
                  contentType: false,
                  success: function(data) {
            		 $('#cover').hide();
	                	if($.trim(data) == "success"){	 
	                		$(".descriptionDivLPS").css("display","none");
	                		//alert("Email sent successfully with the attachment");
	                		successPopUp("Email notification sent successfully, that you have accepted the appointment.");
	                       // $('#success_upload_Accept').show();	
	                		$('body').append('<div id="over" style="position: absolute;top:0;left:0;width: 100%;height:100%;z-index:2;opacity:0.4;filter: alpha(opacity = 50)"></div>');

						}
						else{
							//alert("error: some problem while sending an email!");
							errorPopUp("error: some problem while sending an email!");
						}
                  },
                  error: function(xhr,error){   
                	$('#cover').hide();
  	    		    //alert('Holy errors, testFileUpload!');
                	errorPopUp('Holy errors, testFileUpload!');
                  }
              });
          });
        
        
        // Student to Accept & upload signed Offer Letter and email it back to Admin and CC Professor, Student
        $('#offerUpload_Email_LPS').click(function() {
        	
        	if( $("#uploadOfferFile_LPS").val() == ""){
       		 	//alert("please choose the signed Appointment Letter to Accept the Offer.");
       		 	errorPopUp("please choose the signed Appointment Letter to Accept the Offer.");
				return false;
        	}        	        	
              var formData = new FormData();
              formData.append('filetoUpload', $('#uploadOfferFile_LPS')[0].files[0]);
         	 $('#cover').show();
              $.ajax({
                  url: 'acceptAppointment.php',
                  type: 'POST',
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function(data) {
            		 $('#cover').hide();
	                	if($.trim(data) == "success"){	 
	                		$(".descriptionDivLPS").css("display","none");
	                		//alert("Email sent successfully with the attachment");
	                		successPopUp("Email sent successfully with the attachment");
	                       // $('#success_upload_Accept').show();	
	                		$('body').append('<div id="over" style="position: absolute;top:0;left:0;width: 100%;height:100%;z-index:2;opacity:0.4;filter: alpha(opacity = 50)"></div>');

						}
						else{
							//alert("error: some problem while sending an email!");
							errorPopUp("error: some problem while sending an email!");
						}
                  },
                  error: function(xhr,error){   
                	$('#cover').hide();
  	    		    //alert('Holy errors, testFileUpload!');
                	errorPopUp('Holy errors, testFileUpload!');
                  }
              });
          });
        
        $('#delineOfferButt_LPS').click(function() {
            $(".descriptionDivLPS").css("display","block");
        });	
            
        // Student to Accept & upload signed Offer Letter and email it back to Admin and CC Professor, Student
        $('#delineOffer_Email_LPS').click(function() {
        	var declineDesc= $("#declineDescription").val().trim();
        	if(declineDesc == ""){
        		//alert("please explain the reason for Declining the offer before submitting.");
        		errorPopUp("please explain the reason for Declining the offer before submitting.");
				return false;
        	}        	
        	$(".descriptionDivLPS").css("display","none");
          	var data = {};       	
        	 data["declineDescription"] = declineDesc;
        	 $('#cover').show();
	          $.post('/'+SERVERHOST+'_StudentAppointmentSystem/declineAppointment.php',data,function (data){
        	 //$.post('/Prod_StudentAppointmentSystem/declineAppointment.php',data,function (data){
        			 $('#cover').hide();
        			if(data.trim()== "success"){
        				
                		//alert("Email sent successfully with the Decline message");
                		successPopUp("Email sent successfully with the Decline message");
        				   //$('#success_upload_Decline').show();	
                		$('body').append('<div id="over" style="position: absolute;top:0;left:0;width: 100%;height:100%;z-index:2;opacity:0.4;filter: alpha(opacity = 50)"></div>');

        			}else{
        				//alert("error: some problem while sending an email!");
        				errorPopUp("error: some problem while sending an email!");
        			}
        		});
        });
        
        // to send an email remainder to Student.       
        $('.emailNotifStudentSend').click(function() {
        		
        	var data = {};       	      	 
       		data["studentemailid"] = studentEmail;
       		data["emailsub"] = $("#emailNotifSub").val();
       		data["emailbody"] = $("#emailNotifBody").val();
       		data["sendingfaculty"] = $(".loggedinusername").text();
			$('#cover').show();
			
			$.post('/'+SERVERHOST+'_StudentAppointmentSystem/SendManEmailNotification.php',data,function (data){
			//$.post('/Prod_StudentAppointmentSystem/SendManEmailNotification.php',data,function (data){
				 	$('#cover').hide();
				    if($.trim(data) == "success"){
	    				successPopUp("Email notification sent successfully");
	    			 }else{
	    					//alert("error: some problem while updating the I9 Expiry");
	    					errorPopUp("some problem while sending the email notification.");
	    			 }
				});
        });
        
        
        
        // for admin setting of the start and end date of the semester
        $('.semseladmsett, .yearseladmsett').change(function() {
        	console.log("in the change of sem selection for start date and end date");
        	
        	var curSelAccYear = $(".yearseladmsett").val();
        	var curSelAccSem = $(".semseladmsett").val();
        	$("#startDateAdmSett").removeAttr("max");
    		$("#startDateAdmSett").removeAttr("min"); 			     
     		$("#endDateAdmSett").removeAttr("max");
     		$("#endDateAdmSett").removeAttr("min");
     		
        	var defaultStartDate="";
        	var defaultEndDate="";
        	if(curSelAccSem == "Fall"){
        		defaultStartDate= curSelAccYear.split("-")[0]+"-08-02";
        		defaultEndDate= curSelAccYear.split("-")[0]+"-12-15";
        		$("#startDateAdmSett").attr("min",curSelAccYear.split("-")[0]+"-08-01");
        		$("#startDateAdmSett").attr("max",curSelAccYear.split("-")[0]+"-12-30");    
         		$("#endDateAdmSett").attr("min",curSelAccYear.split("-")[0]+"-08-01");
         		$("#endDateAdmSett").attr("max",curSelAccYear.split("-")[0]+"-12-30"); 
         		
        	}else if(curSelAccSem == "Spring"){
        		defaultStartDate=  curSelAccYear.split("-")[1]+"-01-02";
        		defaultEndDate= curSelAccYear.split("-")[1]+"-04-15";
        		$("#startDateAdmSett").attr("min",curSelAccYear.split("-")[1]+"-01-01");
        		$("#startDateAdmSett").attr("max",curSelAccYear.split("-")[1]+"-04-30");    
         		$("#endDateAdmSett").attr("min",curSelAccYear.split("-")[1]+"-01-01");
         		$("#endDateAdmSett").attr("max",curSelAccYear.split("-")[1]+"-04-30"); 
        	}else{
        		defaultStartDate= curSelAccYear.split("-")[1]+"-05-02";
        		defaultEndDate= curSelAccYear.split("-")[1]+"-07-15";
        		$("#startDateAdmSett").attr("min",curSelAccYear.split("-")[1]+"-05-01");
        		$("#startDateAdmSett").attr("max",curSelAccYear.split("-")[1]+"-07-30");    
         		$("#endDateAdmSett").attr("min",curSelAccYear.split("-")[1]+"-05-01");
         		$("#endDateAdmSett").attr("max",curSelAccYear.split("-")[1]+"-07-30"); 
        	}
        		
	     		
        	$.ajax({
  	          type: "GET",
  	          url: "/"+SERVERHOST+"_StudentAppointmentSystem/updateSettingsAdmin.php?action=6&sem="+curSelAccSem+"&accyear="+curSelAccYear+"&defStartDate="+defaultStartDate+"&defEndDate="+defaultEndDate,
  	          dataType: "text",
  	          success: function( data, textStatus, jqXHR) {			  			
  			 	if($.trim(data).split("|")[0] == "Success"){
  			 		if($.trim(data).split("|")[1] == "definsert"){
  			     		$("#startDateAdmSett").val(defaultStartDate);     			     		
  			     		$("#endDateAdmSett").val(defaultEndDate);
  			 		}else{
  			 			$("#startDateAdmSett").val($.trim(data).split("|")[1]);
  			 			//defaultStartDate = $.trim(data).split("|")[1];
  			 			$("#endDateAdmSett").val($.trim(data).split("|")[2]);	
  			 			//defaultEndDate = $.trim(data).split("|")[2];
  			 		}
  			 		
  			 	}else{
  			 		var errMsg = "some problem while getting the start and end dates of the semester details";
  		         	errorPopUp(errMsg);
  			 	}
  	          },
  			  error: function( data, textStatus, jqXHR) {
  	        	$('#cover').hide();
  		      	//alert("error: some problem while getting the project details");
  	         	var errMsg = "some problem while getting the start and end dates of the semester details";
  	         	errorPopUp(errMsg);	 
  		      }
        	}); 
    	
        });
        
       /* $(".yearseladmsett").change(function(){
        	console.log("in the change of Year selection for start date and end date");
        	
        	
        	
        });*/
        
        
        $("#startDateAdmSett").change(function(){
        	$("#endDateAdmSett").attr("min", $(this).val());
        	$(".updateSemDatesButt").show();
        });
        
        $("#endDateAdmSett").change(function(){
        	$("#startDateAdmSett").attr("max", $(this).val());
        	$(".updateSemDatesButt").show();
        });
        
        $(document).on("click",".updateSemDatesButt",function(){	 
        	var currStartDate = $.trim($("#startDateAdmSett").val());
	    	var currEndDate = $.trim($("#endDateAdmSett").val());
	    	if( currStartDate == "" || currEndDate== ""){
	    		//alert("Error: Update the Current Tution with a proper value.");
	    		//return false;
	    		var errMsg = "Update the Semester Start and End Dates properly.";
	    		errorPopUp(errMsg);
	    		return false;
	    	}
	    	
	    	$.ajax({
		          type: "GET",
		          url: "/"+SERVERHOST+"_StudentAppointmentSystem/updateSettingsAdmin.php?action=7&curStartDate="+currStartDate +"&curEndDate="+currEndDate+"&sem="+$(".semseladmsett").val()+"&accyear="+$(".yearseladmsett").val(),
		          dataType: "text",
		          success: function( data, textStatus, jqXHR) {		
	    			 $('#cover').hide();
	    			if( $.trim(data) == "success" || $.trim(data)== "insert"){
	    				 var msg = "Semester dates are updates succefully";
		    			 successPopUpWithRD(msg);    				
	    			}else{
	    				 var errMsg = "some problem while updating Semester dates";
			        	  errorPopUp(errMsg);
	    			}
	    			
		          },
	  			  error: function( data, textStatus, jqXHR) {
		        	  $('#cover').hide();
		        	  var errMsg = "some problem while updating Semester dates";
		        	  errorPopUp(errMsg);
	  		      }
	    		}); 
	    });
        
        $(document).on("change", "#startDateIP",function(){
        	$("#endDateIP").attr("min", $(this).val());
       
        });
        
        $(document).on("change","#endDateIP",function(){
        	$("#startDateIP").attr("max", $(this).val());
        	
        });
        
        var curDate = new Date();
        var yearSelOptionsAdmin = "";
        if(curDate.getMonth() < 5){
        	yearSelOptionsAdmin += "<option>"+ (curDate.getFullYear()-1)+"-"+curDate.getFullYear()+"</option>";
        }
        for(var i=0;i<5;i++){
        	yearSelOptionsAdmin += "<option>"+ (curDate.getFullYear()+i) +"-"+(curDate.getFullYear()+i+1) +"</option>";
        }

        $(".yearseladmsett").html(yearSelOptionsAdmin);
        
        $(document).on("change",".yearseladmsett",function(){       	
        	if(parseInt($(this).val().split("-")[0]) == curDate.getFullYear()){
        		// to implement the correct validations 
        		if((curDate.getMonth()+1) <=3 || (curDate.getMonth()+1) >=11  ){
        			$(".semseladmsett option[value='Fall']").attr("disabled",true);
        		}else if((curDate.getMonth()+1) >=7 && (curDate.getMonth()+1) <=10){
        			
        		}else{
        			$(".semseladmsett option[value='Fall']").attr("disabled",true);
        			$(".semseladmsett option[value='Spring']").attr("disabled",true);
        		}      		
        		$($(".semseladmsett option[disabled !='disabled']")[0]).attr("selected","selected");      		
        	}else{
        		$(".semseladmsett option").removeAttr("disabled");
        	}
        });

	});
}
start();


















