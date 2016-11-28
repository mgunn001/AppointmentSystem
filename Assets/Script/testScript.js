function start()
{
	$(document).ready(function() { 

		// testing the email sending code 
		    $("#testEmail").on("click",function(){	    
		    	$.ajax({
		    		  url: '/Maheedhar/StudentRecruitmentTS/base_email.php',
		    		  // It's a good idea to explicitly declare the return type
		    		  dataType: 'json',
		    		  //dataType: 'html',
		    		  success: function(json) {
		    		   alert(json)
		    		  },
		    		  error: function(xhr, error) {
		    		    alert('Holy errors, testEmail!');
		    		  }
		    		});
		    });
		    
		    
	        // for testdummy testing
	  	    $(".testPHPOutputTD").on("click",function(){    	
	  	    		var ipValue= $(".testIPTD").val();
	  	    		$.ajax({
	  		          type: "GET",
	  		          url: "/Maheedhar/StudentRecruitmentTS/autoComplete.php?query="+ipValue,
	  		          dataType: "text",
	  		          success: function( data, textStatus, jqXHR) {			      				       				     
	  				    console.log(data);	   
	  		          },
		  			  error: function( data, textStatus, jqXHR) {
		  		      	alert("error: some problem!");
		  		      }
	  	    		});   		  	    		 	
	  	    });
		    	    
		    
		    
		    // to upload file and email
	          $('#testFileUpload_Email').click(function() {
	                var formData = new FormData();
	                formData.append('filetoUpload', $('#uploadTestFile')[0].files[0]);
	                $.ajax({
	                    url: 'base_upload_email.php',
	                    type: 'POST',
	                    data: formData,
	                    processData: false,
	                    contentType: false,
	                    success: function(data) {
	                        //console.log(data);
	                        //alert(data);
	                        $('#uploadTestFile').val('');
	                        $('#success_upload').show();
	                    },
	                    error: function(xhr,error){
	                    	$('#uploadTestFile').val('');
	    	    		    alert('Holy errors, testFileUpload!');
	                    }
	                });
	            });
		});
}
start();
	