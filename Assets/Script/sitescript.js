var loginUID ="";
var loggedIn ="0";
var isAdmin = false;
var adminType= 0;
var inBTWAddition = false;
var curIPunderSugg="";
var currTutionPSem = 0;
var projOptionStr ='<option value="None-0">None</option><option value="createNew-">CreateNew</option>';
var nextSemYear ="";
var colUnderSearch="";
var existingSemYear ="";
var newSemYear = "";
var curSearchStr="";
var curColNum="";
var curAccYear = "";
var SERVERHOST ="Dev";
var curSemEndDate="";
var curSemStartDate="";
var popUpTimeOut = 3000;
function errorPopUpWithRD(errMsg){
	$('#errorModal .modal-body').html("<p>"+errMsg+"</p>");
	 $('#errorModal').on('hidden.bs.modal', function (e) {
				$('#errorModal').off();
					window.location.href = "/"+SERVERHOST+"_StudentAppointmentSystem/home.php";
					//window.location.href = "/Prod_StudentAppointmentSystem/home.php";
		});
	 
	 $('#errorModal').on('shown.bs.modal', function (e) {
		
			$("#errorModal").fadeOut( popUpTimeOut, function() {
				  $("#errorModal").modal("hide"); 
			});
	 });	 
	 
	$("#errorModal").modal("show");
	 $("#errorModal").css("z-index","1100");
}

function errorPopUp(errMsg){
	$('#errorModal .modal-body').html("<p>"+errMsg+"</p>");
	 $('#errorModal').on('hidden.bs.modal', function (e) {
				$('#errorModal').off();
		});
	 
	 $('#errorModal').on('shown.bs.modal', function (e) {
		
			$("#errorModal").fadeOut( popUpTimeOut, function() {
				  $("#errorModal").modal("hide");				 
				});
	 });	 
	 
	$("#errorModal").modal("show");
	 $("#errorModal").css("z-index","1100");
}

function successPopUpWithRD(msg){
	$('#successModal .modal-body').html("<p>"+msg+"</p>");
	 $('#successModal').on('hidden.bs.modal', function (e) {
				$('#successModal').off();
					window.location.href = "/"+SERVERHOST+"_StudentAppointmentSystem/home.php";
					//window.location.href = "/Prod_StudentAppointmentSystem/home.php";
			});
	 
	 $('#successModal').on('shown.bs.modal', function (e) {
		
			$("#successModal").fadeOut( popUpTimeOut, function() {
				  $("#successModal").modal("hide");
				 
				});
	 });	 
	 
	$("#successModal").modal("show");
	 $("#successModal").css("z-index","1100");
}

function successPopUp(msg){
	$('#successModal .modal-body').html("<p>"+msg+"</p>");
	 $('#successModal').on('hidden.bs.modal', function (e) {
				$('#successModal').off();					
			});
	 
	 $('#successModal').on('shown.bs.modal', function (e) {
		
			$("#successModal").fadeOut( popUpTimeOut, function() {
				  $("#successModal").modal("hide");
				 
				});
	 });	 
	 
	$("#successModal").modal("show");
	 $("#successModal").css("z-index","1100");
}


function start()
{	
	$(document).ready(function() {
		loggedIn ="0";	
		
		$('.message a').click(function(){
			   $('.defalutForms').animate({height: "toggle", opacity: "toggle"}, "slow");
		});
		
		 $("#submitButtLogin").on("click",function(){    	
			 
			 $(".login-form").find("input").each(function(){
				 if($.trim($(this).val())==""){
					// alert("please fill in the "+$(this).attr("placeholder")+" field properly to login.");
					 errorPopUp("please fill in the "+$(this).attr("placeholder")+" field properly to login.");
					 return false;
				 }
			 });			 			 
	    		//var queryStr= $(".login-form").serialize();
	    		var formdata = $(".login-form").serializeArray();
	    		var data = {};
	    		$(formdata ).each(function(index, obj){
	    		    data[obj.name] = obj.value;
	    		});
	    		
	    		// this authentication is through my local DB
	    		//$.post('/Prod_StudentAppointmentSystem/checklogin.php',data,function (data){
	    		$.post('/'+SERVERHOST+'_StudentAppointmentSystem/'+SERVERHOST+'_checklogin.php',data,function (data){
	    			if($.trim(data) != ""){
	    				alert($.trim(data));	    				
	    				errorPopUp($.trim(data));	
	    				return false;
	    			}	   				    		
	    		});
	    		
	    		// this authentication is LDAP
	    		/*$.post('/Maheedhar/StudentRecruitmentTS/ldap_auth_test.php',data,function (data){
	    			if($.trim(data) != ""){
	    				alert($.trim(data));	    				
	    				errorPopUp($.trim(data));	
	    				return false;
	    			}	   				    		
	    		});*/
		  	    		 	
	    });
		
		 $("#submitButtRegister").on("click",function(){    			 
				$(".register-form").find("input[required='required']").each(function(){
					 if($.trim($(this).val())==""){
						 var errMsg = "please fill in the "+$(this).attr("placeholder")+" field properly to Register."
						 //alert("please fill in the "+$(this).attr("placeholder")+" field properly to Register.");
						 errorPopUp(errMsg);
						 return false;
					 }				 
				});			 			 
	    		var queryStr= $(".register-form").serialize();	    		
	    		$.ajax({
		          type: "GET",
		         //url: "/Prod_StudentAppointmentSystem/addFaculyRegistration.php?"+queryStr,
		          url: "/"+SERVERHOST+"_StudentAppointmentSystem/addFaculyRegistration.php?"+queryStr,
		          dataType: "text",
		          success: function( data, textStatus, jqXHR) {			      				       				     
	    			var msg= $.trim(data);
	    			// alert($.trim(data));
				    // window.location.href = "http://qav2.cs.odu.edu/Maheedhar/StudentRecruitmentTS/home.php";				     
				     successPopUpWithRD(msg);				    	 
		          },
	  			  error: function( data, textStatus, jqXHR) {
	  		      	var errMsg = "some problem while Registration!";
	  		      	errorPopUp(errMsg);	 
	  		      }
	    		});   		  	    		 	
	    }); 
		 
		
		 
		loginUID = $(".loggedInUID").text().split("-")[0];
		if(loginUID == ""){
			return false;
		}
		//// for the default Start and end date of the semester
		var today = new Date();
		 var currentMonth = today.getMonth()+1; //Default January is 0, so incrementing by 1!
		 var currentYear = today.getFullYear();
		 var minmaxDSD = "";
		 var minmaxDED = "";
		var cursemester ="";
		
		 var defaultStartDate=currentYear+"-08-01";
		 var defaultEndDate=currentYear+"-12-15";
		  // this code segemets are for the 8 week rule to populate the default semester 
		  if(currentMonth >=7 && currentMonth <=10 ){
			 minmaxDSD = currentYear+"-08-01";
			defaultStartDate = currentYear+"-08-02";
			minmaxDED = currentYear+"-12-30";
			defaultEndDate = currentYear+"-12-15";
			cursemester= "Fall";

		  }else if(currentMonth <=3 || currentMonth>=11 ){
			 minmaxDSD = currentYear+1+"-01-01";
			defaultStartDate = currentYear+1+"-01-02";
			defaultEndDate = currentYear+1+"-04-15";
			minmaxDED = currentYear+1+"-04-30";
			cursemester= "Spring";      	
			$(".semseladmsett option[value='Fall']").attr("disabled",true);
		  }else{
			 minmaxDSD = currentYear+1+"-05-01";
			defaultStartDate = currentYear+1+"-05-02";
			defaultEndDate = currentYear+1+"-07-15";
			minmaxDED = currentYear+1+"-07-30";
			cursemester= "Summer";
			$(".semseladmsett option[value='Fall']").attr("disabled",true);
			$(".semseladmsett option[value='Spring']").attr("disabled",true);
		  }
		  
    		if(cursemester.toUpperCase() =="SPRING"){	
    			if(currentMonth == 11 || currentMonth == 12){
    				curAccYear= currentYear+"-"+(currentYear+1);
    			}else{
    				curAccYear= (currentYear-1)+"-"+(currentYear);
    			}		
    			
    		}else if(cursemester.toUpperCase() == "SUMMER"){  	
    			curAccYear=(currentYear-1)+"-"+currentYear;
    		}else{		     			
    			curAccYear= currentYear+"-"+(currentYear+1);
    		}
		  
		  
		 $.ajax({
	          type: "GET",
	          url: "/"+SERVERHOST+"_StudentAppointmentSystem/updateSettingsAdmin.php?action=6&sem="+cursemester+"&accyear="+curAccYear+"&defStartDate="+defaultStartDate+"&defEndDate="+defaultEndDate,
	          dataType: "text",
	          success: function( data, textStatus, jqXHR) {			  			
			 	if($.trim(data).split("|")[0] == "Success"){
			 		if($.trim(data).split("|")[1] == "definsert"){
			 			curSemEndDate=defaultEndDate;
			 			 curSemStartDate=defaultStartDate;
			     		$("#startDateAdmSett").val(defaultStartDate);     			     		
			     		$("#endDateAdmSett").val(defaultEndDate);
			 		}else{
			 			$("#startDateAdmSett").val($.trim(data).split("|")[1]);    
			 			curSemStartDate=$.trim(data).split("|")[1];
			 			$("#endDateAdmSett").val($.trim(data).split("|")[2]);	
			 			curSemEndDate = $.trim(data).split("|")[2];
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

		$(".semseladmsett").val(cursemester);
		$("#startDateAdmSett").attr("max",minmaxDED);
		$("#startDateAdmSett").attr("min",minmaxDSD);
		$("#endDateAdmSett").attr("min",minmaxDSD);
		$("#endDateAdmSett").attr("max",minmaxDED);
		
		
		
		
		
		
		
		
		isAdmin = Boolean($(".loggedInUID").text().split("-")[1]);
		adminType = parseInt($(".loggedInUID").text().split("-")[2]);
		if(isAdmin){
			$.ajax({
		          type: "GET",
		         //url: "/Prod_StudentAppointmentSystem/updateSettingsAdmin.php?action=1",
		          url: "/"+SERVERHOST+"_StudentAppointmentSystem/updateSettingsAdmin.php?action=1",
		          dataType: "text",
		          success: function( data, textStatus, jqXHR) {			  			
					$("#currentTution").val($.trim(data).split("-")[1]);
					currTutionPSem = parseFloat($("#currentTution").val());
		          },
				  error: function( data, textStatus, jqXHR) {
		        	$('#cover').hide();
			      	//alert("error: some problem while getting the project details");
		         	var errMsg = "some problem while getting the project details";
		         	errorPopUp(errMsg);	 
			      }
	  		}); 	

		}else{
			getAllProjectsByStaff(loginUID);
		}
			
		
	    // Setup - add a text input to each footer cell
	    $('#RecruitedStuTable tfoot th').each( function () {
	        var placeHolder = $.trim($(this).text());
	        var title = $.trim($(this).attr("purp"));
	        // just to show the search icon in the ID field
	        if(title == "UIN"){
		        $(this).html('<div class="right-inner-addon"><i class="icon-search"></i><input class="form-control type="text" data-toggle="tooltip" data-placement="top" title="Search By '+title +'" placeholder="'+placeHolder+'" /></div>' );
	        }
	        else if( title == "SemStartDate" || title == "SemEndDate"){
		       //$(this).html('<div class="right-inner-addon"><input class="form-control" type="text" data-toggle="tooltip" data-placement="top" title="Search By '+title +'" placeholder="'+placeHolder+'" /></div>' );
	        	$(this).html('<div class="right-inner-addon"><input class="form-control" type="date" data-toggle="tooltip" data-placement="top" title="Search By '+title +'" placeholder="'+placeHolder+'" /></div>' );
	        }
	        else{
		        $(this).html('<div class="right-inner-addon"><input class="form-control" type="text" data-toggle="tooltip" data-placement="top" title="Search By '+title +'" placeholder="'+placeHolder+'" /></div>' );
	        }	         
	    });
	    $('[placeholder="Re-Appoint"]').parent().css("display","none");
	    $('[placeholder="Action"]').parent().css("display","none");
	    $('[placeholder="Offer Docs"]').parent().css("display","none");
	    //$('[data-toggle="tooltip"]').tooltip();
	   
	    // DataTable
	    var table = $('#RecruitedStuTable').on( 'order.dt',  function () {inBTWAddition=false; } )
        .on( 'search.dt', function () { 
        	inBTWAddition=false; 
        	//alert("insearch of the dataTable");
        }).on( 'page.dt',   function () {
        	inBTWAddition=false; 
        }).DataTable({
        	 lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        	pageLength: 50
        });
	    
	    table.order( [ 3, 'desc' ] ).draw();
	    	    
	    // Apply the search in DataTable
	    table.columns().every( function () {
	        var that = this;	 
	        $( 'input', this.footer() ).on( 'keyup change focusout', function () {	        	
	            if ( that.search() !== this.value ) {
	            	curColNum = that[0][0];
	            	// trying to incorporate the search date range functionality here
	            	colUnderSearch = "";
	            	if($(this).attr("placeholder")== "StartDate" || $(this).attr("placeholder")== "EndDate" ){	            		
	            		colUnderSearch =$(this).attr("placeholder");
	            		curSearchStr = this.value;         		
	            		 // this is the custom extension of the the datatable search
			           		 $.fn.dataTable.ext.search.push(
			           		    	    function( settings, data, dataIndex ) {		    	    				           		    	    	
			           		    	    		// implementing the search for reset of the stuff Except for the Dates			           		    	    				           			    	  
			           									if(colUnderSearch == "StartDate"){	 
			           						 	    		if($("input[placeHolder='StartDate']").val() == ""){
			           						 	    			return true;
			           						 	    		}
			           						 	    		
			           						 	    		var searchDateIP = $("input[placeHolder='StartDate']").val().split("-");
			           						 	    		$("input[placeHolder='EndDate']").attr("min",searchDateIP);
			           						 	    		var searchDate = new Date(searchDateIP[0],(parseInt(searchDateIP[1]-1)),searchDateIP[2] );
			           						 	    		var curRowDate = new Date(data[3]);
			           						 	    		if(  curRowDate >= searchDate ){
			           						 	    			return true;
			           						 	    		}
			           						 	    	} else if( colUnderSearch == "EndDate"){	
			           						 	    		if($("input[placeHolder='EndDate']").val() == ""){
			           						 	    			return true;
			           						 	    		}
			           						 	    		
			           						 	    		var searchDateIP = $("input[placeHolder='EndDate']").val().split("-");
			           						 	    		$("input[placeHolder='StartDate']").attr("max",searchDateIP)
			           						 	    		var searchDate = new Date(searchDateIP[0],(parseInt(searchDateIP[1]-1)),searchDateIP[2] );		           						 	    	
			           						 	    		var curRowDate = new Date(data[4]);
			           						 	    		if(  curRowDate <= searchDate ){
			           						 	    			return true;
			           						 	    		}	            			    	    		
			           						         	}			           			    	    		
			           									return false;	 	       
			           		    	    });
			           		 		table.draw();
			           		 		$.fn.dataTable.ext.search.pop();
	            	}
	            	else{
	            		// this is where the redrawing of the 
	            		that.search(  this.value ).draw();
	            	}
	            		            	
	                curSearchStr ="";
	                curColNum = "";
	                colUnderSearch = "";
	                if(this.value == ""){
	                	return;
	                }
	                var searchType = $(this).parents("th").attr("purp");
	                if( searchType== "Staff" || searchType == "Year" || searchType=="SemStartDate" ||searchType=="SemEndDate" || searchType == "Tution" || searchType == "Semester" ||  searchType=="Funding" || searchType == "Post" || searchType == "Project" || searchType=="Name" ||searchType == "UIN"){
	                	// here is where the logic that gets additional row containing the total of the fonds spent by the professor
	                	var totalTutionAmt = 0;
	                	var avgCredits = 0;
	                	var avgHrs = 0;
	                	var totalSalary = 0;
	                	var dataRowCount = $("#RecruitedStuTable").find("tbody tr.dataRow").length;
	                	$("#RecruitedStuTable").find("tbody tr.dataRow").each(function(){
	                		if(isAdmin && adminType==1){
	                			
	                			if($(this).find(".stuTWaive input").length == 1){
	                				totalTutionAmt+= ((parseFloat($(this).find(".stuTWaive").attr("currtutionfee")) * parseFloat($(this).find(".stuTWaive input").val())/ 100) * parseFloat($(this).find(".stuNoOfCredits input").val()) );
			                		avgCredits += parseInt($(this).find(".stuNoOfCredits input").val());
			                		totalSalary += parseFloat($(this).find(".stuSal input").val());
			                		avgHrs += parseInt($(this).find(".stuWHours .oSpan").text());
	                			}else{
	                				totalTutionAmt+= ((parseFloat($(this).find(".stuTWaive").attr("currtutionfee")) * parseFloat($(this).find(".stuTWaive .oSpan").text())/ 100) * parseFloat($(this).find(".stuNoOfCredits .oSpan").text()) );
			                		avgCredits += parseInt($(this).find(".stuNoOfCredits .oSpan").text());
			                		totalSalary += parseFloat($(this).find(".stuSal .oSpan").text());
			                		avgHrs += parseInt($(this).find(".stuWHours .oSpan").text());        
	                			}
	                		}else{
	                				                		
		                		totalTutionAmt+= ((parseFloat($(this).find(".stuTWaive").attr("currtutionfee")) * parseFloat($(this).find(".stuTWaive .oSpan").text())/ 100) * parseFloat($(this).find(".stuNoOfCredits .oSpan").text()) );
		                		avgCredits += parseInt($(this).find(".stuNoOfCredits .oSpan").text());
		                		totalSalary += parseFloat($(this).find(".stuSal .oSpan").text());
		                		avgHrs += parseInt($(this).find(".stuWHours .oSpan").text());          		
	                		}
	                	});
	                	
	                	if(dataRowCount > 0){
	                		var totalSummaryRow ="";
	                		
	                		if(isAdmin){
		                	 totalSummaryRow ='<tr style="text-align: center;background: #337AB7;color: white;"><td></td><td colspan="2"><b>Total Summary -</b></td><td></td><td></td><td title=" Total Tution Amount"> $'+totalTutionAmt+'</td><td title="Average Credits">'+parseFloat(avgCredits/dataRowCount).toFixed(2) +'</td><td title="Total Salary"> $'+totalSalary +'</td><td title="Average Hours">'+ parseFloat(avgHrs/dataRowCount).toFixed( 2 )+'</td><td colspan="5"></td></tr>';	    	      			    	      		
	                		}else{
		                		totalSummaryRow ='<tr style="text-align: center;background: #337AB7;color: white;"><td></td><td colspan="2"><b>Total Summary -</b></td><td></td><td></td><td title=" Total Tution Amount"> $'+totalTutionAmt+'</td><td title="Average Credits">'+parseFloat(avgCredits/dataRowCount).toFixed(2) +'</td><td title="Total Salary"> $'+totalSalary +'</td><td title="Average Hours">'+ parseFloat(avgHrs/dataRowCount).toFixed( 2 )+'</td><td colspan="4"></td></tr>';	    	      			    	      		
	                		}

		        	      	$("#RecruitedStuTable tbody").append(totalSummaryRow);	   
	                	}
	                }
	            }
	        });
	    });
	    

	    
	    
	    
	    // event registration to trigger on content change of input
	    $(document).on("input","#currentTution",function(){	    
	    	$(".updateTutionButt").show();
	    });
	    
	    $(document).on("click",".updateTutionButt",function(){	 
	    	
	    	var currTution = $.trim($("#currentTution").val());
	    	if( currTution == "" || currTution <= 0){
	    		//alert("Error: Update the Current Tution with a proper value.");
	    		//return false;
	    		var errMsg = "Update the Current Tution with a proper value.";
	    		errorPopUp(errMsg);
	    		return false;
	    	}
	    	
	    	$.ajax({
		          type: "GET",
		         // url: "/Prod_StudentAppointmentSystem/updateSettingsAdmin.php?action=2&currTution="+currTution,
		          url: "/"+SERVERHOST+"_StudentAppointmentSystem/updateSettingsAdmin.php?action=2&currTution="+currTution,

		          dataType: "text",
		          success: function( data, textStatus, jqXHR) {		
	    			 $('#cover').hide();
	    			 var msg = "Tution is updated successfully";
	    			 
				     //alert("Tution is updated successfully");
				     //window.location.href = "http://qav2.cs.odu.edu/Maheedhar/StudentRecruitmentTS/home.php";
	    			 successPopUpWithRD(msg);
		          },
	  			  error: function( data, textStatus, jqXHR) {
		        	  $('#cover').hide();
	  		      	//alert("error: some problem while updating the tution details");
		        	  var errMsg = "some problem while updating the tution details";
		        	  errorPopUp(errMsg);
	  		      }
	    		}); 
	    });

	    //to take care of setting Project to NONE on selecting GTA or Grader while re-appointing
	    $(document).on("change",".rePostIP",function(){	 
	    	var selectedPost = $(this).val();
	    	var parentRow = $(this).parents("tr");
	    	var preProjVal  = $(this).parents("tr").find(".reProjSel").val();
	    	if(selectedPost.split("_")[0] == "PHD"){
	    		if(selectedPost == "PHD_GRA"){
	    			parentRow.find(".reProjSel option[purp='SGRA']").hide();
	    		}else{
	    			parentRow.find(".reProjSel option[purp='SGRA']").show();
	    		}
	    		
	    		return false;	    		
	    	}
	    	if(selectedPost == "GTA" || selectedPost == "Grader"){	    		
	    		parentRow.find(".reProjSel").val("None-0");
	    		parentRow.find(".reProjSel").attr("disabled","disabled");
	    		
	    		 // for the additional requirements of accomadating tution and no of credits
	    		parentRow.find(".reTutionIP").val("50");
	    		parentRow.find(".reCreditsIP").val("9");
	    	}else{	    	
	    		
	    		parentRow.find(".reProjSel").removeAttr("disabled");
	    		parentRow.find(".reProjSel").val(preProjVal);
	    		  
	    		// for the additional requirements of accomadating tution and no of credits
	    		parentRow.find(".reTutionIP").val("75");
	    		parentRow.find(".reCreditsIP").val("6");	 
	    		
	    		if(selectedPost == "GRA"){
	    			parentRow.find(".reProjSel option[purp='SGRA']").hide();
	    		}else{
	    			parentRow.find(".reProjSel option[purp='SGRA']").show();
	    		}
	    	}	    
		});
	    
	    //to take care of setting Project to NONE on selecting GTA or Grader while cadding new
	    $(document).on("change",".postIP",function(){	 
	    	
	    	var selectedPost = $(this).val();
	    	var parentRow = $(this).parents("tr");
	    	if(selectedPost.split("_")[0] == "PHD"){
	    		if(selectedPost == "PHD_GRA"){
	    			parentRow.find(".projSel option[purp='SGRA']").hide();
	    		}else{
	    			parentRow.find(".projSel option[purp='SGRA']").show();
	    		}
	    		
	    		return false;
	    	}
	    	
	    	if(selectedPost == "GTA" || selectedPost == "Grader"){	
	    		 
	    		parentRow.find(".projSel").val("None-0");
	    		parentRow.find(".projSel").attr("disabled","disabled");
	    		 // for the additional requirements of accomadating tution and no of credits
	    		
	    		parentRow.find(".tutionWaiveIP").val("50");
	    		parentRow.find(".noOfCreditsIP").val("9");
	    		
	    	}else{	    		    		
	    		parentRow.find(".projSel").removeAttr("disabled");
	    		  // for the additional requirements of accomadating tution and no of credits
	    		parentRow.find(".tutionWaiveIP").val("75");
	    		parentRow.find(".noOfCreditsIP").val("6");
	    		
	    		if(selectedPost == "GRA"){
	    			parentRow.find(".projSel option[purp='SGRA']").hide();
	    		}else{
	    			parentRow.find(".projSel option[purp='SGRA']").show();
	    		}

	    	}
		});
	    
	    
	    // to handle the new project selection stuff
	    $(document).on("change",".reProjSel",function(){	    	
	    	if($(this).val().split("-")[0] == "createNew"){	    		
  	    		var reProjIPStr='<input type="text" name="reProjIP" class="reProjIP form-control width_100Per" placeholder="Proj Number" required />';
  	    		$(this).parent().append(reProjIPStr);
  	    		//$(this).hide();
	    	}else{
	    		$(this).parent().find(".reProjIP").remove();
	    	}
		});
	    

	    
	    $(document).on("change",".projSel",function(){	    	
	    	if($(this).val().split("-")[0] == "createNew"){	    		
  	    		var projIPStr='<input type="text" name="projIP" class="projIP form-control width_100Per" placeholder="Proj Number" required />';
  	    		$(this).parent().append(projIPStr);
  	    		//$(this).hide();
	    	}else{
	    		$(this).parent().find(".projIP").remove();
	    	}    
		});
	    
	    
	                   
          //modified-- actions to be done on checkbox click
  	    $(".reAppointCB").on("click",function(){
  	    	var ParentTR = $(this).parents("tr");
  	    	
  	    	if($(this).prop("checked")){ 	
  	    		// to make any number of appointments to be initiated at once by Faculty -- currently left unimplemented have to revisit this 
  	    		if(inBTWAddition){
  		  			//alert("Please add one Appointment at a time.");
  		  			var errMsg ="Please add one Appointment at a time.";
  		  			errorPopUp(errMsg);
  		  			
  		  			return false;
  		  		}
  	    		inBTWAddition = true;
  	    		// to revert to the state of being unchecked
  	    		var originalTrContent = $(ParentTR).html();
  	    		
  	    		var recId = $(this).parents("tr").attr("id");
  	    		ParentTR.find(".reAppointButt").css("display","block");  	
  	    		
  	    		// just to show the next sem and year - accomidating the changes suggested "next Sem|Year-year|StartDate|EndDate"
  	    		existingSemYear = ParentTR.find(".stuStartDate").attr("title");
	    		nextSemYear= getNextSemAndYear(existingSemYear).split("|");
	    		newSemYear = nextSemYear[0]+"|"+nextSemYear[1];
	    		
	    		
	    		var preStartDate = jQuery.trim(ParentTR.find(".stuStartDate .oSpan").text()); 	    
	    		var startDateIPStr='<span class="tSpan"><input type="date" name="reStartDateIP" class="reStartDateIP form-control width_100Per" placeholder="Semester StartDate" required disabled semyear="'+nextSemYear[0]+'|'+ nextSemYear[1]+'" value="'+ nextSemYear[2] +'"/></span>';
  	    		ParentTR.find(".stuStartDate .oSpan").hide();
  	    		ParentTR.find(".stuStartDate").append(startDateIPStr);
  	    		ParentTR.find(".stuStartDate").attr("title",newSemYear);
  	    		
	    		var preEndDate = jQuery.trim(ParentTR.find(".stuEndDate .oSpan").text()); 	    		
  	    		var endDateIPStr='<span class="tSpan"><input type="date" name="reEndDateIP" class="reEndDateIP form-control width_100Per" placeholder="semester EndDate" required disabled semyear="'+nextSemYear[0]+'|'+ nextSemYear[1]+'" value="'+ nextSemYear[3] +'"/></span>';
  	    		ParentTR.find(".stuEndDate .oSpan").hide();
  	    		ParentTR.find(".stuEndDate").append(endDateIPStr);
  	    		ParentTR.find(".stuEndDate").attr("title",newSemYear);
	    			
	    		/*var preSem = jQuery.trim(ParentTR.find(".stuSem .oSpan").text()); 	    		
  	    		var semIPStr='<span class="tSpan"><input type="text" name="reSemIP" class="reSemIP form-control width_100Per" placeholder="Semester" required disabled value="'+ nextSemYear[0] +'"/></span>';
  	    		ParentTR.find(".stuSem .oSpan").hide();
  	    		ParentTR.find(".stuSem").append(semIPStr);*/
  	    		
  	    		/*var preYear = jQuery.trim(ParentTR.find(".stuSal .oSpan").text()); 	    		
  	    		var yearIPStr='<span class="tSpan"><input type="number" name="reYearIP" class="reYearIP form-control width_100Per" placeholder="Year" required disabled value="'+ nextSemYear[1] +'"/></span>';
  	    		ParentTR.find(".stuYear .oSpan").hide();
  	    		ParentTR.find(".stuYear").append(yearIPStr);*/

  	    		var preSal = jQuery.trim(ParentTR.find(".stuSal .oSpan").text()); 	    		
  	    		var salIPStr='<span class="tSpan"><input type="number" name="reSalaryIP" class="reSalaryIP form-control width_100Per" placeholder="Salary" required value="'+ parseInt(preSal) +'"/></span>';
  	    		ParentTR.find(".stuSal .oSpan").hide();
  	    		ParentTR.find(".stuSal").append(salIPStr);
  	    		
  	    		var preHours = jQuery.trim(ParentTR.find(".stuWHours .oSpan").text()); 	    	
  	    		var hoursIPStr='<span class="tSpan"><input type="number" name="reHoursIP" class="reHoursIP form-control width_100Per" placeholder="Hours" required value="'+ parseInt(preHours) +'"/></span>';
  	    		ParentTR.find(".stuWHours .oSpan").hide();
  	    		ParentTR.find(".stuWHours").append(hoursIPStr);
  	    		
  	    		// these are for the additional requirements of tutuion and fee 
  	    		var preTution = jQuery.trim(ParentTR.find(".stuTWaive .oSpan").text()); 	    	
  	    		var tutionIPStr='<span class="tSpan"><input type="number" name="reTutionIP" disabled class="reTutionIP form-control width_100Per" placeholder="Tution Waive" required value="'+ parseInt(preTution) +'"/></span>';
  	    		ParentTR.find(".stuTWaive .oSpan").hide();
  	    		ParentTR.find(".stuTWaive").append(tutionIPStr);
  	    		
  	    		var preNoOfCredits = jQuery.trim(ParentTR.find(".stuNoOfCredits .oSpan").text()); 	    	
  	    		var creditsIPStr='<span class="tSpan"><input type="number" name="reCreditsIP" disabled class="reCreditsIP form-control width_100Per" placeholder="No.of Credits" required value="'+ parseInt(preNoOfCredits) +'"/></span>';
  	    		ParentTR.find(".stuNoOfCredits .oSpan").hide();
  	    		ParentTR.find(".stuNoOfCredits").append(creditsIPStr);
  	    		
  	    		
  			    var postSelectStr = '<span class="tSpan"> <select class="form-control rePostIP" name="rePostIP"><option value="0">select post</option><option value="GRA">GRA</option><option value="SGRA">SGRA</option><option value="GTA">GTA</option><option value="Grader">Grader</option></select></span>';
  	    		var prePost = jQuery.trim(ParentTR.find(".stuPost .oSpan").text());
  	    		ParentTR.find(".stuPost .oSpan").hide();
  	    		ParentTR.find(".stuPost").append(postSelectStr);
  	    		ParentTR.find(".rePostIP").val(prePost); 
  	    		
  	    		$staffId = loginUID;
  	    		
  	    		if(isAdmin){
  	    			// here the staffID of the selected staff has to be filled
  	    			$staffId = ParentTR.find(".staName").attr("staffid");
  	    		}    		
  	    		// to populate all the projects
  				  projSelectHelper(ParentTR,prePost); 
  				  
  				  var postSelectStr = '<span class="tSpan"> <select class="form-control reFundingIP" name="reFundingIP"><option value="1">ODU</option><option value="2">ODU & Research</option></select></span>';
    	    	  var preFundType = jQuery.trim(ParentTR.find(".stuFundingType .oSpan").text());
    	    	  ParentTR.find(".stuFundingType .oSpan").hide();
    	    	  ParentTR.find(".stuFundingType").append(postSelectStr);
    	    	  if(preFundType == "ODU"){
    	    		  ParentTR.find(".reFundingIP").val("1");
    	    	  }else{
    	    		  ParentTR.find(".reFundingIP").val("2");
    	    	  }
    	    	   

  	    	}else{
  	    		inBTWAddition = false;
  	    		ParentTR.find(".tSpan").remove();
  	    		ParentTR.find(".oSpan").show();
  	    		ParentTR.find(".reAppointButt").css("display","none"); 
  	    		ParentTR.find(".stuEndDate").attr("title",existingSemYear);
  	    		ParentTR.find(".stuStartDate").attr("title",existingSemYear);
			    // window.location.href = "/Maheedhar/StudentRecruitmentTS/home.php";
  	    	}  	  	    
  	    });
  	    
  	    //modified-- on click of Re-Appoint Button
  	  $(".reAppointButt").on("click",function(){
  		  		var ParentTR = $(this).parents("tr"); 		  		
	    		var recId = ParentTR.attr("id");		
	    		var postIP = $.trim(ParentTR.find(".rePostIP").val());	    			    		
	    		var reSal = $.trim(ParentTR.find(".reSalaryIP ").val());
	    		var reHours = $.trim(ParentTR.find(".reHoursIP ").val());
	    		var reTution = $.trim(ParentTR.find(".reTutionIP ").val());
	    		var reNoofCredits = $.trim(ParentTR.find(".reCreditsIP ").val());
	    		var reFundingType = $(".reFundingIP").val();
	    		if($.trim(postIP)== 0){
	    			//alert("Please fill in the Post input correctly");			
		  			errorPopUp("Please fill in the Post input correctly");
	    			return false;
	    		}else if(reTution=="" || reTution==0){
	    			//alert("Please fill in the Turion Waive field correctly");
	    			errorPopUp("Please fill in the Turion Waive field correctly");
	    			return false;
	    		}else if(reNoofCredits=="" || reNoofCredits == 0){
	    			//alert("Please fill in the No.of Credits field correctly");
	    			errorPopUp("Please fill in the No.of Credits field correctly");
	    			return false;
	    		}else if(reSal==""){
	    			//alert("Please fill in the Salary field correctly");
	    			errorPopUp("Please fill in the Salary field correctly");
	    			return false;
	    		}
	    			    		
	    		
	    		if(reHours== "" || parseInt(reHours)==0 || parseInt(reHours) > 20 || parseInt(reHours) < 0){
	    			//alert("Please fill in the Hours input correctly; Hours assigned must be less than 20 and greater than 0");
	    			errorPopUp("Please fill in the Hours input correctly: Hours assigned must be less than 20 and greater than 0");
	    			return false;
	    		}
	    		
	    		var reProjIp = "",reProjId="", projectConfirmStr = "";
		    		if( ParentTR.find(".reProjSel").val().split("-")[0]== "createNew"){
		    			
		    			reProjIp = ParentTR.find(".reProjIP").val();
		    			reProjId="";
		    			projectConfirmStr = " on a New Project "+reProjIp.toUpperCase();
		    			if($.trim(reProjIp) == ""){
			    			//alert("Please fill in the Project field correctly");
			    			errorPopUp("Please fill in the Project field correctly");
			    			return false;
			    		}
		    			
		    		}else{
		    			reProjIp = ParentTR.find(".reProjSel").val().split("-")[0];
		    
		    			reProjId= ParentTR.find(".reProjSel").val().split("-")[1];
		    			
		    			if( ParentTR.find(".reProjSel").val().split("-")[1]!= "0"){
		    				projectConfirmStr = " on a Project "+reProjIp.toUpperCase();
		    			}
		    			
		    		}
	    		
	    		//var nextSemYear= getNextSemAndYear(ParentTR.find(".stuSem input").val(),ParentTR.find(".stuYear input").val()).split("|");
		    	 var fundingDet = "";
		    	if(reFundingType == "1"){
		    		fundingDet = "funded by ODU";
		    	}else{
		    		fundingDet = "funded by both ODU & Research Fundation";
		    	}
	    		var confirmationStr = " Are you sure to Re Appoint the student "+ParentTR.find(".stuName").text() +"("+ParentTR.find(".stuUIN").text()+")";
	    		confirmationStr+= " as a "+ postIP + " for "+ ParentTR.find(".stuStartDate input").attr("semyear")+" "+ projectConfirmStr +" "+ fundingDet+" and pay him "+reSal+" ?";
	    		 
	    		var proceed = confirm(confirmationStr);

	    		 if(!proceed){
	    			 return false;
	    		 }
	    		    			    		
	    		var querySTR = "recid="+recId+"&reProj="+reProjIp.toUpperCase()+"-"+reProjId+"&reSalaryIP="+jQuery.trim(ParentTR.find(".reSalaryIP").val())+"&rePostIP="+ParentTR.find(".rePostIP").val()+
	    		"&reHourIP="+reHours+"&reTutionIP="+reTution+"-"+reNoofCredits;
	    		var data = {};
	    		data["recid"]= recId;
	    		data["reProj"]= reProjIp.toUpperCase()+"-"+reProjId;
	    		data["reSalaryIP"]= jQuery.trim(ParentTR.find(".reSalaryIP").val());
	    		data["rePostIP"]= ParentTR.find(".rePostIP").val();
	    		data["reHourIP"]= reHours;
	    		data["reTutionIP"]= reTution+"-"+reNoofCredits;
	    		data["semester"] = nextSemYear[0];
	    		data["year"]= nextSemYear[1];
	    				
	    		data["startdate"]= nextSemYear[2];
	    		data["enddate"]= nextSemYear[3];
	    		data["reFundingIP"]= reFundingType;
	    		console.log(querySTR);	    		
	    		 $('#cover').show();
	    		$.ajax({
		          type: "POST",
		         // url: "/Prod_StudentAppointmentSystem/addReAppointment.php",
		          url: "/"+SERVERHOST+"_StudentAppointmentSystem/addReAppointment.php",
		          dataType: "text",
		          data:data,
		          success: function( data, textStatus, jqXHR) {	
	    			 $('#cover').hide();
				     //var jObj = jQuery.parseJSON(data);
				    // console.log(jObj[0].stu_id); 
	    			if($.trim(data).split("-")[0]== "success"){
	    				 successPopUpWithRD("Student Appointment Process is initiated");
	    			 // alert("Student Appointment Process is initiated");
 				     //window.location.href = "http://qav2.cs.odu.edu/Maheedhar/StudentRecruitmentTS/home.php";
	    			}else if($.trim(data).split("-")[0]== "fail"){
	    				
	    				if($.trim(data).split("-")[1] != ""){
	    					errorPopUp($.trim(data).split("-")[1]);
	    					//alert($.trim(data).split("-")[1]);
	    				}else{
		    				//alert("error: some problem while initiating the Appointment!");
		    				errorPopUp("some problem while initiating the Appointment!");
	    				}
	    				
	    				
	    			}			   
		          },
	  			  error: function( data, textStatus, jqXHR) {
		        	$('#cover').hide();
	  		      	//alert("error: some problem while sending an email!");
	  		       	errorPopUp("some problem while sending an email!");
	  		      }
	    		});   		
	  	
	    });
  	  
   // this event for adding new appointment 
  	$(".addNewAppointmet").on("click",function(){
  		
  		// to make any number of appointments to be initiated at once by Faculty -- currently left unimplemented have to revisit this 
  		if(inBTWAddition){
	  		//alert("Please add one Appointment at a time.");
	  		errorPopUp("Please add one Appointment at a time.");
	  		return false;
	  	}
	  	inBTWAddition = true;
  		
  		
  		var formGroupDiv = "<div class='form-group'>";		    	 
  		var divEnding = "</div>";

  		  var today = new Date();
  		  var currentMonth = today.getMonth()+1; //Default January is 0, so incrementing by 1!
  		  var currentYear = today.getFullYear();
  		  var defaultSal = "3200";
  		  var defaultSemester="";
  		  var defaultStartDate=currentYear+"-08-01";
  		 var defaultEndDate=currentYear+"-12-15";
  		  var defaultTutionWaiver = 50;
  		  var defaultNoofCredits = 9;
  		  
  		  // this code segemets are for the 8 week rule to populate the default semester 
  		  if(currentMonth >=7 && currentMonth <=10 ){
  			defaultStartDate = currentYear+"-08-01";
  			defaultEndDate = currentYear+"-12-15";
  			defaultSemester= "Fall";
  		  }else if(currentMonth <=3 || currentMonth>=11 ){
  			defaultStartDate = currentYear+1+"-01-01";
  			defaultEndDate = currentYear+1+"-04-15";
  			defaultSemester= "Spring";
  		  }else{
  			defaultStartDate = currentYear+1+"-05-01";
  			defaultEndDate = currentYear+1+"-07-15";
  			defaultSemester= "Summer";
  		  }
  		  
		    var postSelectStr = '<select purpose="Post" class="form-control postIP" name="postIP"><option value="0">select post</option><option value="GRA">GRA</option><option value="SGRA">SGRA</option><option value="GTA">GTA</option><option value="Grader">Grader</option></select>';
  		    var semSelectStr = '<select purpose="Semester" class="form-control semesterIP" name="semesterIP"><option value="0">select semester</option><option value="Spring">Spring</option><option value="Summer">Summer</option><option value="Fall">Fall</option></select>';
  		    var fundingSelStr = '<select purpose="Funding" class="form-control fundingIP" name="fundingIP"><option value="1">ODU</option><option value="2">ODU&Research</option></select>';

	      	var editableRow ='<tr class="appointmentToAdd"><td>'+formGroupDiv+'<input type="text" name="uinIP" class="uinIP txt-auto form-control  width_100Per " placeholder="Uin"  required />'+divEnding+'</td>';
	      		editableRow+='<td>'+formGroupDiv +'<input type="text" name="nameIP" id="nameIP" class="nameIP txt-auto form-control width_100Per" placeholder="Name" required />'+divEnding+'</td>';	      		
	      		editableRow+='<td>'+formGroupDiv+postSelectStr+divEnding+'</td>';
	      		// the below one is from StartDate and EndDate	      	
	      		editableRow+='<td>'+formGroupDiv+'<input type="date" name="startDateIP" id="startDateIP" class="startDateIP width85p txt-auto form-control " placeholder="Semester Start Date" title="Semester Start Date"  required /> '+divEnding+'</td>'
	      		editableRow+='<td>'+formGroupDiv+'<input type="date" name="endDateIP" id="endDateIP" class="endDateIP width85p txt-auto form-control "  placeholder="Semester End date" title="Semester End Date" required /> '+divEnding+'</td>'	      	
	      		
	      		//editableRow+='<td>'+formGroupDiv+semSelectStr+divEnding+'</td>';	      			      		
	      		//editableRow+='<td>'+formGroupDiv +'<input type="number" name="yearIP" class="yearIP txt-auto form-control width_100Per" placeholder="Year" required value="'+ currentYear +'"/>'+divEnding+'</td>';
	      		
	      		//the following two are added for additional requirements of tutuion and no of credit hours
	      		editableRow+='<td>'+formGroupDiv +'<input type="number" name="tutionWaiveIP" disabled class="tutionWaiveIP txt-auto form-control width_100Per" placeholder="Tution Waive" required value="'+ defaultTutionWaiver +'"/>'+divEnding+'</td>';
	      		editableRow+='<td>'+formGroupDiv +'<input type="number" name="noOfCreditsIP" disabled class="noOfCreditsIP txt-auto form-control width_100Per" placeholder="No.of Credits" required value="'+ defaultNoofCredits +'"/>'+divEnding+'</td>';
	      		editableRow+='<td>'+formGroupDiv +'<input type="number" name="salaryIP" class="salaryIP form-control txt-auto width_100Per" placeholder="Salary" required value="'+ defaultSal +'"/>'+divEnding+'</td>';
	      		editableRow+='<td>'+formGroupDiv +'<input type="number" name="hoursIP" class="hoursIP form-control txt-auto width_100Per" placeholder="Hours" required value="'+ 20 +'"/>'+divEnding+'</td>';	    		
  	    		// to populate all the projects
	    	    var projSelectStr = '<select class="form-control projSel" name="projSel">'+ projOptionStr+'</select>';
	      		editableRow+='<td>'+formGroupDiv +projSelectStr+divEnding+'</td>';   
	      		editableRow+='<td>'+formGroupDiv +fundingSelStr+divEnding+'</td>';   
	      		
	      		if(isAdmin){
		      		editableRow+='<td>'+formGroupDiv +'<input type="text" name="staffIP" class="staffIP form-control txt-auto width_100Per" placeholder="Staff Name" required />'+divEnding+'</td>';
	      		}	      		
	      		editableRow+='<td>'+formGroupDiv +'<button type="button" " class="submitAppointment btn btn-success pull-right" onclick="addNewAppointment($(this))">Appoint</button>'+divEnding+'</td>';     	
	      		editableRow+='<td>'+formGroupDiv +'<button type="button" " class="closeAppmntRow btn btn-success pull-left" onclick="closeAppmntRow($(this))">close</button>'+divEnding+'</td></tr>';     	

	      		$("#RecruitedStuTable tbody").append(editableRow);
	    	    var today = new Date().toISOString().split('T')[0];
	    	    $(".appointmentToAdd .startDateIP").attr('min', today);
	    	    $(".appointmentToAdd .startDateIP").val(curSemStartDate);
	    	    
	    	    $(".appointmentToAdd .endDateIP").val(curSemEndDate);	    	    
	    	    $(".appointmentToAdd .endDateIP").attr('min', today);
	      		
	      		$staffId = loginUID;
	    		if(isAdmin){
	    			// here the staffID of the selected staff has to be filled
	    			$staffId = loginUID;
	    		}
   	
	      	$(".semesterIP").val(defaultSemester);
	      		      	
	      	// type a head of student UIN
	      	$( "input.uinIP" ).keyup(function() {
	      		$(".resSuggDiv").remove();
	      		 curIPunderSugg = $(this);
	      		 var curRowParent = $(this).parents("tr");
	      		var strVal = $(this).val().trim();
	      		curRowParent.find("#nameIP").val("");
	      		if(strVal.length >=2){	
	      			
	      			$.ajax({
		  		          type: "GET",
		  		          //url: "/Prod_StudentAppointmentSystem/autoCompleteStudentUIN.php?searchVal="+strVal,
		  		          url: "/"+SERVERHOST+"_StudentAppointmentSystem/autoCompleteStudentUIN.php?searchVal="+strVal,
		  		          dataType: "text",
		  		          success: function( data, textStatus, jqXHR) {			      				       				     
		  				   	var result = $.parseJSON(data);
		  				   	if(result.length == 0){
		  				   		if(strVal.length == 8){	
		  							 $('#cover').show();
		  							successPopUp("No Student found with this UIN in the Local DB, Initiating the LDAP Search...");
		  				   			//alert("No Student found with this UIN in the Local DB, Initiating the LDAP Search...");
			  				   		LDAPSearchByUIN(strVal);
		  				   		}		  				   			  				   		
		  				   	}else{		
		  				   		var listGroupDiv = $("<div class='resSuggDiv'><ul class='list-group'></ul></div>");				   	
		  				   		var liComp = "";
		  				   		$.each(result,function(index,value){
		  				   			liComp += '<li class="list-group-item stuUINSuggList" id="'+value.split("-")[0] +'" gradlevel="'+value.split("-")[2] +'">'+value.split("-")[1]+'</li>';		  				   		
		  				   		});
		  				   		listGroupDiv.find("ul").append(liComp);
		  				   		$("body").append(listGroupDiv);
		  				   			listGroupDiv.css( {position:'absolute',
		  				   			 top:curIPunderSugg.offset().top+31,
			  				   		  left:curIPunderSugg.offset().left
		  				   		});
		  				   		
		  				   		$(".stuUINSuggList").click(function(){		  
			  				   		if($(this).attr("gradLevel") == "phd"){
						   				var optStrPHD = '<option value="0">select Post</option><option value="PHD_GRA">PHD-GRA</option><option value="PHD_SGRA">PHD-SGRA</option><option value="PHD_GTA">PHD-GTA</option><option value="PHD_Grader">PHD-Grader</option>';
						   				curRowParent.find(".tutionWaiveIP").val("100");
						   				curRowParent.find(".noOfCreditsIP").val("6");
						   				curRowParent.find(".postIP").html(optStrPHD);
						   			}else{
						   				var optStrGrad = '<option value="0">select post</option><option value="GRA">GRA</option><option value="SGRA">SGRA</option><option value="GTA">GTA</option><option value="Grader">Grader</option>';
						   				curRowParent.find(".tutionWaiveIP").val("75");
						   				curRowParent.find(".noOfCreditsIP").val("6");
						   				curRowParent.find(".postIP").html(optStrGrad);
						   			}
		  				   			curIPunderSugg.val($(this).html());
		  				   			curRowParent.find("#nameIP").val($(this).attr("id"));
		  				   			$(".resSuggDiv").remove();
		  				   		});	
		  				   	}		  				   	
		  		          },
			  			  error: function( data, textStatus, jqXHR) {
			  		      	//alert("error: some problem!");
		  		        	  errorPopUp("some problem!");
			  		      }
		  	    		});    	
	      		}
	      	});
	      	
	      	
	      	// type a head of student UIN
	    	$( "input#nameIP" ).keyup(function() {
	      		 curIPunderSugg = $(this);
	      		 var curRowParent = $(this).parents("tr");
	      		$(".resSuggDiv").remove();
	      		var strVal = $(this).val().trim();
	      		curRowParent.find(".uinIP").val("");
	      		if(strVal.length >=2){	      		
	      			$.ajax({
		  		          type: "GET",
		  		          //url: "/Prod_StudentAppointmentSystem/autoCompleteStudentName.php?searchVal="+strVal,
		  		          url: "/"+SERVERHOST+"_StudentAppointmentSystem/autoCompleteStudentName.php?searchVal="+strVal,
		  		          dataType: "text",
		  		          success: function( data, textStatus, jqXHR) {			      				       				     
		  				   	var result = $.parseJSON(data);
		  				   	if(result.length == 0){
			  				   	if(strVal.length > 3){	
		  							$('#cover').show();
		  							successPopUp("No Student found with this name in the Local DB, Initiating the LDAP Search...");
		  				   			//alert("No Student found with this name in the Local DB, Initiating the LDAP Search...");
			  				   		LDAPSearchByName(strVal);
		  				   		}		
		  				   	}else{		  				   	 				   			   		
		  				   		var listGroupDiv = $("<div class='resSuggDiv'><ul class='list-group'></ul></div>");				   	
		  				   		var liComp = "";
		  				   		$.each(result,function(index,value){
		  				   			liComp += '<li class="list-group-item stuNameSuggList" id="'+value.split("-")[1] +'" gradlevel="'+value.split("-")[2] +'">'+value.split("-")[0]+'</li>';		  				   		
		  				   		});
		  				   		listGroupDiv.find("ul").append(liComp);
		  				   		$("body").append(listGroupDiv);
		  				   			listGroupDiv.css( {position:'absolute',
		  				   			 top:curIPunderSugg.offset().top+31,
			  				   		  left:curIPunderSugg.offset().left
		  				   		});
		  				   		
		  				   		$(".stuNameSuggList").click(function(){	
			  				   		if($(this).attr("gradLevel") == "phd"){
						   				var optStrPHD = '<option value="0">select post</option><option value="PHD_GRA">PHD-GRA</option><option value="PHD_SGRA">PHD-SGRA</option><option value="PHD_GTA">PHD-GTA</option><option value="PHD_Grader">PHD-Grader</option>';
						   				curRowParent.find(".tutionWaiveIP").val("100");
						   				curRowParent.find(".noOfCreditsIP").val("6");
						   				curRowParent.find(".postIP").html(optStrPHD);
						   			}else{
						   				var optStrGrad = '<option value="0">select post</option><option value="GRA">GRA</option><option value="SGRA">SGRA</option><option value="GTA">GTA</option><option value="Grader">Grader</option>';
						   				curRowParent.find(".tutionWaiveIP").val("75");
						   				curRowParent.find(".noOfCreditsIP").val("6");
						   				curRowParent.find(".postIP").html(optStrGrad);
						   			}
		  				   			curIPunderSugg.val($(this).html());
		  				   			curRowParent.find(".uinIP").val($(this).attr("id"));
		  				   			$(".resSuggDiv").remove();
		  				   		});	
		  				   	}		  				   	
		  		          },
			  			  error: function( data, textStatus, jqXHR) {
			  		      	//alert("error: some problem!");
			  		      	errorPopUp("some problem!");
			  		      }
		  	    		});    	
	      		}
	      	});
	      		      	
	   });  

	});
}


// return the String combination of "next Sem|(Accademic)Year-year|(Genuine Date)StartDate|EndDate"
function getNextSemAndYear(semyearStr){
	var curSem =  $.trim(semyearStr.split("|")[0]);
	var curAccYear = $.trim(semyearStr.split("|")[1]);
	var startYear= parseInt(curAccYear.split("-")[0]);
	var endYear = parseInt(curAccYear.split("-")[1]);
	var semAndYear = "";
	if(curSem.toUpperCase() =="SPRING"){
		
		semAndYear="Summer|"+curAccYear+"|"+endYear+"-05-01"+"|"+endYear+"-07-15";	
	}else if(curSem.toUpperCase() == "SUMMER"){
  // for instance -  Fall|2016-2017|2016-08-01|2016-12-15
		semAndYear="Fall|"+endYear+"-"+(endYear+1)+"|"+endYear+"-08-01"+"|"+endYear+"-12-15";
	}else{		
		
		semAndYear= "Spring|"+curAccYear+"|"+endYear+"-01-01|"+endYear+"-04-15";
	}
	return semAndYear;
}



function ajaxGetProjectsByStaffID(staffID){	
	$.ajax({
          type: "GET",
          //url: "/Prod_StudentAppointmentSystem/getAllProjectsByStaff.php?staffId="+staffID,
          url: "/"+SERVERHOST+"_StudentAppointmentSystem/getAllProjectsByStaff.php?staffId="+staffID,

          dataType: "text",
          success: function( data, textStatus, jqXHR) {			      				       				     
		     projDetArray = $.parseJSON(data);
		   return projDetArray;
		    
          },
          error: function( data, textStatus, jqXHR) {
        	  
		    //alert("error: some problem while getting the project details");
		    errorPopUp("some problem while getting the project details!");
		 }
	}); 
}

//construct the project select box on fly 
function projSelectHelper(ParentTR,prePost){ 
	    var projSelectStr = '<span class="tSpan"><select class="form-control reProjSel" name="reProjSel">'+projOptionStr +'</select></span>';		
		var preProjName = jQuery.trim(ParentTR.find(".stuProj .oSpan").text());
		var preProjId = jQuery.trim(ParentTR.find(".stuProj").attr("projId"));
		ParentTR.find(".stuProj .oSpan").hide();
		ParentTR.find(".stuProj").append(projSelectStr);
		ParentTR.find(".reProjSel").val(preProjName+"-"+preProjId); 
		if(prePost == "GTA" || prePost == "Grader"){
			ParentTR.find(".reProjSel").attr("disabled","disabled");
		}
}



function closeAppmntRow(buttonClicked){
	buttonClicked.parents("tr").remove();
	inBTWAddition = false;
}



// this is the method that get called on click of new appointment submit 
function addNewAppointment(buttonClicked){
	//alert("clicked Button");
	var isExit = false;
	var curRowParent= buttonClicked.parents("tr");
	
	curRowParent.find("input.form-control").each(function(inputEle){				
		if($(this).val() == ""){
			//alert("Fill out the field: "+ $(this).attr("placeholder") +" Properly before submitting");
			errorPopUp("Fill out the field: "+ $(this).attr("placeholder") +" Properly before submitting");
			isExit = true;
			return false
		}						
	});			
	if(isExit){
		return false;
	}
	
	
	if(parseInt(curRowParent.find(".hoursIP").val()) == 0 || parseInt(curRowParent.find(".hoursIP").val()) < 0 || parseInt(curRowParent.find(".hoursIP").val())>20  ){
		//alert("A Number of hours of work the student must be asigned is more than 0 and must be less than 20.");
		errorPopUp("A Number of hours of work the student must be asigned is more than 0 and must be less than 20.");
		return false;
	}
	
	
	var curPostVal = curRowParent.find(".postIP").val();	
	
	if(curPostVal == "GRA" ||curPostVal == "SGRA"){		
		if (curRowParent.find(".projSel").val() == "None-0"){
			//alert("There must be a project assigned to a sudent if he is appointed as GRA, Please choose a project");
			errorPopUp("There must be a project assigned to a sudent if he is appointed as GRA, Please choose a project");
			return false;
		}
	}


	curRowParent.find("select.form-control").each(function(inputEle){				
		if($(this).val() == "0"){
			//alert("Fill select an option from the filed: "+ $(this).attr("purpose") +" properly before submitting");
			errorPopUp("Fill select an option from the filed: "+ $(this).attr("purpose") +" properly before submitting");
			isExit = true;
			return false
		}						
	});
	
	if(isExit){
		return false;
	}	
	var formdata = curRowParent.find("input").serializeArray();
	var data = {};
	$(formdata).each(function(index, obj){
	    data[obj.name] = obj.value;
	});
	
	 data["firstNameIP"] = data["nameIP"].split(",")[0];
	 data["lastNameIP"] = (data["nameIP"].split(",")[1]).split("-")[0];
	 
	 if(isAdmin){
		 data["staffIP"]= curRowParent.find(".staffIP").attr("staffid");
	 }else{
		 data["staffIP"]= loginUID;
	 }

	 data["postIP"]= curRowParent.find(".postIP").val();
	 
	 data["fundingIP"]= curRowParent.find(".fundingIP").val();
	
	 
	 var selStartMonth = parseInt(data["startDateIP"].split("-")[1]);
	 	var selSemester="";
	 	if(selStartMonth >=7 && selStartMonth <=10 ){
	 		selSemester= "Fall";
		  }else if(selStartMonth <=3 || selStartMonth>=11 ){
			selSemester= "Spring";
		  }else{
			selSemester= "Summer";
		  }	 	 
	 	

	 data["semesterIP"]=selSemester; 
	 // to get the current academic year value
	 if(selSemester == "Spring"){
		 data["yearIP"] = (parseInt(data["startDateIP"].split("-")[0])-1)+"-"+(parseInt(data["startDateIP"].split("-")[0]));
	 }else if (selSemester == "Fall"){
		 data["yearIP"] = data["startDateIP"].split("-")[0]+"-"+(parseInt(data["startDateIP"].split("-")[0])+1);
	 }else{
		 data["yearIP"] = (parseInt(data["startDateIP"].split("-")[0])-1)+"-"+(parseInt(data["startDateIP"].split("-")[0]));
	 }
	 
	 var projIp = "",projId="", projectConfirmStr = "";
		if( curRowParent.find(".projSel").val().split("-")[0]== "createNew"){
			
			projIp = curRowParent.find(".projIP").val();
			projId="";
			projectConfirmStr = " on a New Project "+projIp.toUpperCase();
			if($.trim(projIp) == ""){
	 			//alert("Please fill in the Project field correctly");
				errorPopUp("Please fill in the Project field correctly");

	 			return false;
			}			
		}else{
			projIp = curRowParent.find(".projSel").val().split("-")[0];

			projId= curRowParent.find(".projSel").val().split("-")[1];
			
			if(  curRowParent.find(".projSel").val().split("-")[1]!= "0"){
				projectConfirmStr = " on a Project "+projIp.toUpperCase();
			}			
		}
	 data["projIP"]= projIp.toUpperCase();
	 data["projID"]= projId;
	 
	 data["tutionWaiveIP"]= parseInt(curRowParent.find(".tutionWaiveIP").val());
	 data["noOfCreditsIP"]= parseInt(curRowParent.find(".noOfCreditsIP").val());
	 if(projIp == "")
	 {
		 //alert("please give a proper name for the project");
		 errorPopUp("please give a proper name for the project");

		 return;
	 }
	 var fundingDet = "";
	 if(data["fundingIP"] == "1"){
		 fundingDet = "funded by ODU";
	 }else{
		 fundingDet = "funded by both ODU & Research Fundation";
	 }
	 
	 



	 
	 
	 
	 
	 
	 var confirmationStr = " Are you sure to Appoint the student "+ data["firstNameIP"]+" "+data["lastNameIP"]+"("+data["uinIP"]+")";
	 confirmationStr+= " as a "+data["postIP"]+ " for "+data["semesterIP"]+" "+data["yearIP"]+projectConfirmStr+" "+fundingDet+ " and pay him "+data["salaryIP"]+" ?";
	 
	var proceed = confirm(confirmationStr);
	 
	 if(!proceed){
		 return false;
	 }
	 $('#cover').show();
	//$.post('/Prod_StudentAppointmentSystem/addRecruitment.php',data,function (data){
	$.post('/'+SERVERHOST+'_StudentAppointmentSystem/addRecruitment.php',data,function (data){
		$('#cover').hide();
		if(data.split("-")[0]== "success"){
			
			var insertedRecID = data.split("-")[1];

			//going to be Changed due to workflow change - Proff(Initiated) -> Student(Accepted) -> Proff,Admins(Offer Released) -> Student(Signed&Sent-Employed) -> Admin
			// this code to initiate sending emails (from Proff To Admin) - Before Change Request
			/*$('#cover').show();
			$.ajax({
		          type: "GET",
		          url: "/Maheedhar/StudentRecruitmentTS/emailAdmin.php?recid="+insertedRecID,
		          dataType: "text",
		          success: function( data, textStatus, jqXHR) {	
					$('#cover').hide();
				     //var jObj = jQuery.parseJSON(data);
				    // console.log(jObj[0].stu_id); 
					if($.trim(data) == "success"){
						//alert("Student Appointment Initiated Successfully");
						
					     //window.location.href = "http://qav2.cs.odu.edu/Maheedhar/StudentRecruitmentTS/home.php";
					     successPopUpWithRD("Student Appointment Initiated Successfully");
					}
					else{
						//alert("error: some problem while sending an email!");
						errorPopUp("some problem while sending an email!");
					}					
		          },
	  			  error: function( data, textStatus, jqXHR) {
		        	$('#cover').hide();
	  		      	//alert("error: some problem while sending an email!");
	  		      	errorPopUp("some problem while sending an email!");
	  		      }
	    	});*/   				
			
			
			// code to send email from proff to Student - On Change Request
			$('#cover').show();
			$.ajax({
		          type: "GET",
		         // url: "/Prod_StudentAppointmentSystem/emailStudent1.php?recid="+insertedRecID,
		          url: "/"+SERVERHOST+"_StudentAppointmentSystem/emailStudent1.php?recid="+insertedRecID,
		          dataType: "text",
		          success: function( data, textStatus, jqXHR) {	
					$('#cover').hide();
				     //var jObj = jQuery.parseJSON(data);
				    // console.log(jObj[0].stu_id); 
					if($.trim(data) == "success"){
						//alert("Student Appointment Initiated Successfully");						
					     //window.location.href = "http://qav2.cs.odu.edu/Maheedhar/StudentRecruitmentTS/home.php";
					     successPopUpWithRD("Student Appointment Initiated Successfully");
					}
					else{
						//alert("error: some problem while sending an email!");
						errorPopUp("some problem while sending an email!");
					}					
		          },
	  			  error: function( data, textStatus, jqXHR) {
		        	$('#cover').hide();
	  		      	//alert("error: some problem while sending an email!");
	  		      	errorPopUp("some problem while sending an email!");
	  		      }
	    	});   
			
			
			
			
			
		}else if (data.split("-")[0]== "fail"){
			$('#cover').hide();
			if(data.split("-")[1] != "" && data.split("-")[1] != undefined){
				//alert(data.split("-")[1]);
				errorPopUp(data.split("-")[1]);
			}else{
				//alert("something went wrong");
				errorPopUp("something went wrong");
			}
		}		
	});
}



function LDAPSearchByUIN (strVal){
	$.ajax({
          type: "GET",
          //url: "/Prod_StudentAppointmentSystem/ldapSearchByUIN.php?searchVal="+strVal,
          url: "/"+SERVERHOST+"_StudentAppointmentSystem/ldapSearchByUIN.php?searchVal="+strVal,
          dataType: "text",
          success: function( data, textStatus, jqXHR) {		
				data = data.trim().split("-");
				if(data[0] == "success"){
					 $('#cover').hide();
					//alert(data[1]);
					successPopUp(data[1]);
					if(data[3] == "phd"){
		   				var optStrPHD = '<option value="0">select post</option><option value="PHD_GRA">PHD-GRA</option><option value="PHD_SGRA">PHD-SGRA</option><option value="PHD_GTA">PHD-GTA</option><option value="PHD_Grader">PHD-Grader</option>';
		   				
		   				$(".postIP").html(optStrPHD);
		   			}else{
		   				var optStrGrad = '<option value="0">select post</option><option value="GRA">GRA</option><option value="SGRA">SGRA</option><option value="GTA">GTA</option><option value="Grader">Grader</option>';
		   				
		   				$(".postIP").html(optStrGrad);
		   			}
					$(".nameIP").val(data[2]);			
				}else if(data[0] == ""){
					 $('#cover').hide();
					 errorPopUp("Couldn't find a student on this UIN with the LDAPSearch too..");
				}else{
					 $('#cover').hide();
					//alert(data[1]);
					errorPopUp(data[1]);
				}
          },
		  error: function( data, textStatus, jqXHR) {
        		 $('#cover').hide();
	      	//alert("error: some problem with LDAP Connection!");
	      	errorPopUp("some problem with LDAP Connection!");
	      }
		});    	
}

function LDAPSearchByName (strVal){
	$.ajax({
          type: "GET",
         // url: "/Prod_StudentAppointmentSystem/ldapSearchByName.php?searchVal="+strVal,
          url: "/"+SERVERHOST+"_StudentAppointmentSystem/ldapSearchByName.php?searchVal="+strVal,

          dataType: "text",
          success: function( data, textStatus, jqXHR) {		
				var isFound = true;
				studentdata = $.trim(data).split("--");
				if(studentdata[0] == "success"){
					 $('#cover').hide();
					var studentListObj = $.parseJSON(studentdata[1]);
					var studentListArr = $.map(studentListObj, function(el) { return el });
					var listGroupDiv = $("<div class='resSuggDiv'><ul class='list-group'></ul></div>");				   	
			   		var liComp = "";
			   		
					$.each(studentListArr, function( index, student ) {
						
						if(student == 0){
							isFound = false;
						}
						if( typeof student === 'object' && student.employeenumber != undefined && student.employeenumber[0].trim()!=""){
							
							console.log(student.givenname[0]+"-"+student.sn[0]+"-"+student.employeenumber[0]+"-"+student.extensionattribute1[0]);
							var gradLevel = gradLevelFinder(student)
							
								liComp += '<li class="list-group-item stuNameLDAPList" id="'+student.employeenumber[0]+'" mail="'+student.extensionattribute1[0]+'" gradlevel="'+gradLevel+'">'+student.givenname[0]+','+student.sn[0]+'</li>';							

						}
					});
					
						if(!isFound){
							errorPopUp("No Student Found on this name with the LDAPSearch too..");
							return false;
						}

				   		listGroupDiv.find("ul").append(liComp);
				   		$("body").append(listGroupDiv);
				   			listGroupDiv.css( {position:'absolute',
				   			 top:curIPunderSugg.offset().top+31,
  				   		  left:curIPunderSugg.offset().left
				   		});
				   		
				   		$(".stuNameLDAPList").click(function(){		  				   			
				   			var firstname = $(this).html().split(",")[0];
				   			var lastname = $(this).html().split(",")[1];
				   			var email = $(this).attr("mail");
				   			var uin = $(this).attr("id");
				   			var gradLevel = $(this).attr("gradlevel");
				   			var data={};
				   			data["firstname"]= firstname;
				   			data["lastname"]= lastname;
				   			data["email"]= email;
				   			data["uin"]= uin;	
				   			data["gradlevel"]= gradLevel;
				   			if(gradLevel == "phd"){
				   				var optStrPHD = '<option value="0">select post</option><option value="PHD_GRA">PHD-GRA</option><option value="PHD_SGRA">PHD-SGRA</option><option value="PHD_GTA">PHD-GTA</option><option value="PHD_Grader">PHD-Grader</option>';
				   				$(".postIP").html(optStrPHD);
				   			}else{
				   				var optStrGrad = '<option value="0">select post</option><option value="GRA">GRA</option><option value="SGRA">SGRA</option><option value="GTA">GTA</option><option value="Grader">Grader</option>';
				   				$(".postIP").html(optStrGrad);
				   			}
				   			$('#cover').show();
				   			//$.post('/Prod_StudentAppointmentSystem/addStudent.php',data,function (data){
					   		$.post('/'+SERVERHOST+'_StudentAppointmentSystem/addStudent.php',data,function (data){
				   				$('#cover').hide();
					   			if(data.trim().split("-")[0]== "success"){
					   				//alert("Student added successfuly into Local DB");
					   				successPopUp("Student added successfuly into Local DB");
						   			curIPunderSugg.val(firstname+","+lastname);
						   			$(".uinIP").val(uin);
						   			$(".resSuggDiv").remove();						   								   								
					   			}else{
					   				//alert("Error: Problem with adding student into Local DB");
					   				errorPopUp("Problem with adding student into Local DB");
					   			}		
					   		});
				   		});
															
				}else{
					 $('#cover').hide();
					//alert("Error: some problem occured with the LDAPSearch");
					errorPopUp("some problem occured with the LDAPSearch");
				}
          },
		  error: function( data, textStatus, jqXHR) {
        	$('#cover').hide();
	      	//alert("error: some problem with LDAP Connection!");
	      	errorPopUp("some problem with LDAP Connection!");
	      }
		});    	
}

// this method returns whether the student is of PHD level or masters level
function gradLevelFinder( student){
	var membersof = [];
	for(var i=0;i<student.memberof["count"]; i++ ){		
		membersof.push( student.memberof[i].split(",")[0].split("=")[1]);
	}
	if(membersof.indexOf("phd") >=0 || membersof.indexOf("phd".toLowerCase) >=0){
		return "phd";
	}else {
		var dnArr=student["dn"].split(",");
		if( dnArr.indexOf("OU="+"Undergrad".toLowerCase()) >= 0  || dnArr.indexOf("OU=Undergrad") >= 0 ){
			return "undergrad";
		}else if(dnArr.indexOf("OU="+"Grad".toLowerCase()) >= 0 || dnArr.indexOf("OU=Grad") >= 0){
			return "grad";
		}
	}
	
}

function getAllProjectsByStaff($staffId){
	$('#cover').show();

	// to populate all the projects
	$.ajax({
	  type: "GET",
	  //url: "/Prod_StudentAppointmentSystem/getAllProjectsByStaff.php?staffId="+$staffId,
	  url: "/"+SERVERHOST+"_StudentAppointmentSystem/getAllProjectsByStaff.php?staffId="+$staffId,
	  dataType: "text",
	  success: function( data, textStatus, jqXHR) {		
		 $('#cover').hide();
	    var projDetArray = $.parseJSON(data);	   
		$.each(projDetArray,function(index,value){
			var purp = "GRA";
			if(parseInt(value.split("-")[2])== 1){
				purp = "SGRA";
			}
			projOptionStr+='<option purp="'+purp +'" value="'+value.split("-")[0]+'-'+value.split("-")[1]+'">'+value.split("-")[0] +'</option>'; 	    		
		});		
	    
	  },
	 error: function( data, textStatus, jqXHR) {
		$('#cover').hide();
	    	//alert("error: some problem while getting the project details");
	      errorPopUp("some problem while getting the project details!");	    
	    }
	 }); 
	 return projOptionStr;
}

start();


































