$(document).ready(function() {

	$('.tblList').DataTable({ //table except home page
		"jQueryUI":true,  
    		"paging": true,
		"lengthChange": false,		
  		"pageLength": 10,
		"searching": false,
		"ordering": false,
		"autoWidth": true,
		//"stripeClasses": [ 'odd-row', 'even-row' ]
	});	
	
	$('.tblList1').DataTable({ //table except home page
		"jQueryUI":true,  
    		//"scrollY":"600px", 
    		"paging": true,
		"lengthChange": true,		
  		"pageLength": 10,
		"searching": true,
		"ordering": false,
		"autoWidth": true,
	});	

	$('.tblList2').DataTable({ //table except home page
		"jQueryUI":true,  
    		//"scrollY":"600px", 
    		"paging": true,
		"lengthChange": true,		
  		"pageLength": 10,
		//"searching": true,
		"ordering": false,
		"autoWidth": true,
	});	
	
	$('#btnBrowseCV').on( 'click', function () {
		browseCV('tblBrowseCVList');		    
	});

	function browseCV(tbl_name){

  	var keyword = $("#txtbrowsecv").val();	
	
	//alert(salary_range+'/'+type+'/'+category+'/'+township+'/'+company);
	$('#'+tbl_name).DataTable().destroy();
	$('#'+tbl_name).DataTable({
    		"jQueryUI":true,  
    		//"scrollY":"600px", 
    		"paging": true,
    		// "pagingType": "first_last_numbers",
		  	"lengthChange": false,		
  			"pageLength": 10,
		  	"searching": false,
		  	"ordering": false,
		  	"autoWidth": true,	
		  	processing: true,
      		serverSide: true,
	      	ajax: {
	  			method: 'GET', // Type of response and matches what we said in the route
	    	    url: 'en/get_browse_cv_list', // This is the url we gave in the route
		    	data: {'keyword':keyword}, // a JSON object to send back
	    	   },
	          columns:[{data:'result', name:'result'}]        
	      });	
}
		
	function split( val ) {
        return val.split( /,\s*/ );
    }
    
    function extractLast( term ) {
        return split( term ).pop();
    }
    
	$("#search_text").autocomplete({		
	   source: function(request, response) {
	       $.ajax({
	           url: '../../category_autocomplete',
	           dataType: "json",
	           data: {
	               term : extractLast(request.term)
	           },
	           success: function(data) {
	               response(data);
	           }
	       });
	   },
	   min_length: 3, 
	   autoFocus: true,	     
	   select: function (event, ui) {
		   var terms = split(this.value);
		   console.log("terms "+terms);
	   // remove the current input
	   terms.pop();
	   // add the selected item
	   terms.push( ui.item.value );
	   // add placeholder to get the comma-and-space at the end
	   terms.push( "" );
	   this.value = terms.join( ", " );
	   console.log("this.value "+this.value);
	   
	   var id = [$('#category_id').val()];
	   if(id == '')	id.pop();
	   id.push( ui.item.id);
	   $('#category_id').val(id);
	   console.log("category_id "+$('#category_id').val());
	   
	   return false;
	       
	   }
	});
/////////////////////////// employer dashboard /////////////////////////   

    $("#lnkEdit").on('click', function () { 	
    	//window.location.href = "/company/"+document.getElementById("ddlCompany").value+"/edit";    	
    	window.open("/company/"+document.getElementById("ddlCompany").value+"/edit",'_blank');
    });

    $("#lnkView").on('click', function () { 	
    	window.open("/company/"+document.getElementById("ddlCompany").value,'_blank');
    });    
    
    $("#lnkDelete").on('click', function (e) { 
    	e.preventDefault();
		var id = document.getElementById("ddlCompany").value; //"e1e768766a9e411e9522e3e5293c7e3a";
		var self = $(this);
		var productRoute = "http://10.127.127.1/en/company/"+document.getElementById("ddlCompany").value;//alert(productRoute);
		var productName = id;
		var productToken = document.getElementById("hdnCompany").value;
		swal({
			allowOutsideClick: true,
			title: "Are you sure to delete?",
			text: "Record cannot be recovered after delete!",
			textClass:"red-text text-accent-4",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-warning",
			cancelButtonClass: "btn-info",
			confirmButtonText: "Yes",
			cancelButtonText: 'No',
			closeOnConfirm: true,			
		},
		function(){
			url = "/en/company/"+document.getElementById("ddlCompany").value;			
			$.ajax({
			    url: url,
			    type: 'post',
			    data: {_method:'delete', _token: document.getElementById("hdnCompany").value},
			    success: function(result) {
			        // Do something with the result
			    	console.log('success '+result);
			    },
			    error: function(result) {
			        // Do something with the result
			    	console.log('error '+result);
			    }
			}); 
		});	
    });
    
    $("#ddlCompany").on('change', function () { 	   
    	showJobbyCandidate();
    	showShortlistedbyCandidate();
    });    
    
    function showJobbyCandidate(){
	  	var company_id = $("#ddlCompany").val();  	
	  	//alert(company_id);
		$('#tblJobbyCandidate').DataTable().destroy();
		if(company_id != null){
			$('#tblJobbyCandidate').DataTable({
		    		"jQueryUI":true,  
		    		"paging": true,
				  	"lengthChange": false,		
		  			"pageLength": 15,
				  	"searching": false,
				  	"ordering": false,
				  	"autoWidth": true,	
				  	processing: true,
		      		serverSide: true,
			      	ajax: {
			  			method: 'GET', // Type of response and matches what we said in the route
			    	    url: '/en/job_by_candidate', // This is the url we gave in the route
				    	data: {'company_id':company_id},
			    	   },
			          columns:[{data:'result', name:'result'}] 
			              
			      });	
		}
	}
    
    function showShortlistedbyCandidate(){
	  	var company_id = $("#ddlCompany").val();  
		$('#tblJobbyShortlisted').DataTable().destroy();
		if(company_id != null){
			$('#tblJobbyShortlisted').DataTable({
		    		"jQueryUI":true,  
		    		"paging": true,
				  	"lengthChange": false,		
		  			"pageLength": 15,
				  	"searching": false,
				  	"ordering": false,
				  	"autoWidth": true,	
				  	processing: true,
		      		serverSide: true,
			      	ajax: {
			  			method: 'GET', // Type of response and matches what we said in the route
			    	    url: '/en/shortlisted_by_candidate', // This is the url we gave in the route
				    	data: {'company_id':company_id},
			    	   },
			          columns:[{data:'result', name:'result'}] 
			              
			      });	
		}
	}
/////////////////////////// end employer dashboard /////////////////////////  
    
/////////////////////////// city, township /////////////////////////  
    var com_cid = $('#ddlCompanyCity').val();
    var com_tid = $('#hd_company_tsp_id').val();	
	getTownship(com_cid,com_tid,'tsp_div');	
	
	$("#ddlCompanyCity").on('change', function () {
    	var city_id = document.getElementById("ddlCompanyCity").value;
    	getTownship(city_id,0,'tsp_div');
    })
    
    var emp_cid = $('#ddlEmployerCity').val();
	var emp_tid = $('#hd_employer_tsp_id').val();
	getTownship(emp_cid,emp_tid,'emp_tsp_div');		
    
    $("#ddlEmployerCity").on('change', function () {
    	var city_id = document.getElementById("ddlEmployerCity").value;
    	getTownship(city_id,0,'emp_tsp_div');
    })
    
    var jobseeker_cid = $('#ddlEmployeeCity').val();
	var jobseeker_tid = $('#hd_employee_tsp_id').val();
	getTownship(jobseeker_cid,jobseeker_tid,'employee_tsp_div');		
    
    $("#ddlEmployeeCity").on('change', function () {
    	var city_id = document.getElementById("ddlEmployeeCity").value;
    	getTownship(city_id,0,'employee_tsp_div');
    })
    
    function getTownship(city_id,tsp_id,div_id){        	
		if(city_id == '') url_tsp = "/townshipByCity";
		else url_tsp = "/townshipByCity/"+city_id ;
		$.ajax({
            method: "GET",
            url: url_tsp
        })                 
        .success(function(data) {
	        	var list = new Array(); 	
	        	// insert all the properties and their values into an array of objects
	        	for(var propName in data) {
	        	    list.push({ "id": propName, "value":data[propName] });
	        	}        	
	        	// sort the array using the value instead of the id
	        	list.sort(function compare(a,b) {
	        	              if (a.value < b.value)
	        	                 return -1;
	        	              else
	        	                 return 1; // we consider that if they have the same name, the first one goes first
	        	            });
	        	
	            select = '<div class="list-area"><ul class="list-unstyled ">';
	            select += '<select name="township" id="ddlTsp" class="form-control list-unstyled">';
	            $.each(list, function(key, value) {
	            	if(tsp_id == value.id){               
	                	select +='<option value="'+value.id+'" selected>'+value.value+'</option>';
	            	}
	            	else
	            		{
	            		console.log('<option value="'+value.id+'">'+value.value+'</option>');                
	                	select +='<option value="'+value.id+'">'+value.value+'</option>';
	            		}
	            });
	            select += '</select></ul></div>'; 
	            //console.log(select);           
	            $("#"+div_id).html(select);          
	    });
	}    
	
    	
	$("#ddlbusinessType").on('change', function () {		
    		var type_id = document.getElementById("ddlbusinessType").value;    		
    		getBusinessType(type_id);
    })
    
    function getBusinessType(type_id){    
    	
	if(type_id == '') url_tsp = "/job_industry";
	else url_tsp = "/job_industry/"+type_id ;
	$.ajax({
            method: "GET",
            url: url_tsp
        })                 
        .success(function(data) {
	        	console.log(data);
	        	$("#ddlJobIndustry").empty()
	        	for(var item of data){
	        		//businessType +='<option value="'+item.id+'" selected>'+item.job_industry+'</option>';  
	        		$('#ddlJobIndustry').append('<option value="'+item.id+'" selected>'+item.job_industry+'</option>'); 
			} 			        
	    });
	}
    
/////////////////////////// end city, township /////////////////////////   
    
	$(document).on('click', '.deleteRecord', function(e) {
		e.preventDefault();
		var id = $(this).data('id'); //alert(id);
		var self = $(this);
		var productRoute = $(this).attr("data_destroy_route");	 //alert(productRoute);	
		var productName = $(this).attr("data_name");			//alert(productName);
		var productToken = $(this).attr("data_token");
		swal({
			allowOutsideClick: true,
			title: "Are you sure to delete?",
			text: "Record cannot be recovered after delete!",
			textClass:"red-text text-accent-4",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-warning",
			cancelButtonClass: "btn-info",
			confirmButtonText: "Yes",
			cancelButtonText: 'No',
			closeOnConfirm: true,
			
		},
		function(){
			$('#form-delete-record-' + id).submit();
			swal("Good job!", "You clicked the button!", "success");
		});	
	});		


	if ( $('[type="date"]').prop('type') != 'date' ) {
		$( '[type="date"]').datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0", // last hundred years
			dateFormat: "yy-mm-dd"
		});
	}
	
	 $('[type="date"]').prop('max', function(){
        	return new Date();
    	});
	
	
	
	//category entry popup
	$(".link-cat").click(function () { 
		$("#cat").fadeIn(300);
		$("body").addClass("no-scroll");
	});
	$("#cat .close").click(function () {
		$("#cat").fadeOut(300);
		$("body").removeClass("no-scroll");
	});
	
	$(".link-township").click(function () {
		$("#township").fadeIn(300);
		$("body").addClass("no-scroll");
	});

	$("#township .close").click(function () {
		$("#township").fadeOut(300);
		$("body").removeClass("no-scroll");
	});
	
	$(".link-industry").click(function () { 
		$("#industry").fadeIn(300);
		$("body").addClass("no-scroll");
	});
	$("#industry .close").click(function () {
		$("#industry").fadeOut(300);
		$("body").removeClass("no-scroll");
	});
	
	$(".link-send").click(function () {
		$("#send").fadeIn(300);
		$("body").addClass("no-scroll");
	});
	$("#send .close").click(function () {
		$("#send").fadeOut(300);
		$("body").removeClass("no-scroll");
	});	
	
	$(".link-call").click(function () {
		$("#mobile_number").fadeIn(300);
		$("body").addClass("no-scroll");
	});
	$("#mobile_number .close").click(function () {
		$("#mobile_number").fadeOut(300);
		$("body").removeClass("no-scroll");
	});	
	
	$(".link-upgrade_pkg").click(function () { 
		$("#upgrade_pkg").fadeIn(300);
		$("body").addClass("no-scroll");
	});
	$("#upgrade_pkg .close").click(function () {
		$("#upgrade_pkg").fadeOut(300);
		$("body").removeClass("no-scroll");
	});
	/*
	$("#otoday").on('change', function () { 
			document.getElementById("open_date").disabled = document.getElementById("otoday").checked ? 0 : 1;
    	})
    
    	$("#ctoday").on('change', function () { 
			document.getElementById("close_date").disabled = document.getElementById("ctoday").checked ? 0 : 1;
    	})*/
    /*    
    $("#ddlCity").on('change', function () { 	
    	var city_id = document.getElementById("ddlCity").value;
    	//alert(value);
    	getTownship(city_id,0,'tsp_div1');
    })*/
   /* $("#category,#township").on('change', function () { 
    	showJobSeekerList();	
    })
    */
    function showJobSeekerList(){
		var cat = $("#category").val();	
	  	var tsp =$("#township").val();
	  	$.ajax({
	  	    url: "getAllApplicant",
	  	    data: { 
	  	        "category": cat, 
	  	        "township": tsp
	  	        },
	  	    cache: false,
	  	    type: "get",
	  	    success: function(response) {
	  	    	$.each(data, function(index, obj){
	  	          var tr = $("<tr></tr>");

	  	          tr.append("<td>"+ obj.id +"</td>");
	  	          tr.append("<td>"+ obj.first_name +"</td>");
	  	          tr.append("<td>"+ obj.last_name +"</td>");
	  	          tr.append("<td>"+ obj.gender +"</td>");
	  	          tr.append("<td>"+ obj.created_at +"</td>");

	  	          $("#my-containing-data").append(tr);
	  	      });
	  	    	
	  	    },
	  	    error: function(xhr) {
	  	    	console.log(error);
	  	    }
	  	});
	}
    
     $("#btnSendMsg").click(function () {
    	var description = $("#contact_info").val()+", "+$("#txtdescription").val();  
    	var mobile_no = document.getElementById("mobile_no").options[document.getElementById("mobile_no").selectedIndex].text;
    	var sms_info = "sms:"+mobile_no+"?body="+description+"";
    	window.open(sms_info, '_system');
	});
    
    $(".link-calls").click(function () {
    	save_candidate(); 	
	});
    
    $(".link-call1").click(function () {
    	save_candidate();	
	});
    
    function save_candidate(){
    	var heid = $("#heid").val();    	
	  	var haid =$("#haid").val();
	  	$.ajax({
	  	    url: "../../api/candidate",
	  	    data: { 
	  	        "heid": heid, 
	  	        "haid": haid,
	  	        "contact_info": "",
	  	        "description": ""
	  	        },	
	  	    type: "post",
	  	    beforeSend: function (xhr) {
	          // Function needed from Laravel because of the CSRF Middleware
	          var token = $('meta[name="csrf_token"]').attr('content');

	          if (token) {
	              return xhr.setRequestHeader('X-CSRF-TOKEN', token);
	          }
	  	    },
	  	    success: function(response) {
	  	    	console.log(response);
	  	    },	 
	  	    error: function(xhr,error) {
	  	    	console.log(error);
	  	    }
	  	});
    }
	showJobbyCandidate();
	showShortlistedbyCandidate();
	
	
	
    /*
	
	$('#ddlCompany').on( 'selectedindexchange', function () {
		showJobbyCandidate();	    
	});	*/	
	
	
	
/*
	$( "#datepicker, #sdate, #edate").datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+0", // last hundred years
		dateFormat: "yy-mm-dd"
	});

	$( "#tabs" ).tabs();	
*/
            
});


/*function getCity(){
	$.ajax({
	        method: "GET",
	        url: "city"
	    })
	    .success(function(data) {    		
	    	 select = '<select name="city" style="width:150px;" class="browser-default white gery-text text-lighten-1"  id="city"  onChange="javascript:getTownship(this);">';
	    	 select +='<option value="0"   selected>State / Division</option>';
             $.each(data, function(key, value) {
            	 select +='<option value="'+key+'">'+value+'</option>';
              });
             select += '</select>';
             $("#city_div").html(select);
     });
}

function getTownship(city){
	//alert(city_id);
	var city_id = city.options[city.selectedIndex].value;
	
	$.ajax({
	        method: "GET",
	        url: "township",
        	data: {'city' : city_id}	
	    })
	    .success(function(data) {    	
	    	 select = '<select name="township" style="width:150px;" class="browser-default white gery-text text-lighten-1" id="township" >';
	    	 select +='<option value="0"   selected>Select Township</option>';
             $.each(data, function(key, value) {
            	 select +='<option value="'+data[key]['id']+'">'+data[key]['township']+'</option>';
              });
             select += '</select>';
             $("#township_div").html(select);
     });
}

function getJobType(){
	$.ajax({
	        method: "GET",
	        url: "type"
	    })
	    .success(function(data) {
	    	 select = '<select name="jobType" style="width:150px;" class="browser-default white gery-text text-lighten-1"  id="jobType" >';
	    	 select +='<option value="0"   selected>Select Job Type</option>';
	      $.each(data, function(key, value) {
	     	 select +='<option value="'+key+'">'+value+'</option>';
	       });
	      select += '</select>';
	      $("#type_div").html(select);
	});
} 
*/
 


/*var edutable = $('#tblEdu').DataTable({
"lengthChange": true,
"searching": false,
"autoWidth": true,
"sDom": 'lfrtip',
"paging":   false,
"ordering": false,
"info":     false
}); 
function getEducation(){
$.ajax({
        method: "GET",
        url: "getEducation"
    })
    .success(function(edu) {
    	edutable.clear();edutable.draw();
        var temp = "<input type='button' class='btnEduRemove' value='Remove'/>";
        for (var i = 0;i < edu.length; i++) {
            edutable.row.add([edu[i]['university'],edu[i]['degree'],edu[i]['year'],temp]);
            edutable.draw();
        }
    })
}
var edu = [] ;
$('#btnAddEdu').on( 'click', function () {
if($('#university').val() != ''){    
    var jsonData = {};
    jsonData["university"] = $('#university').val();
    jsonData["degree"] = $('#degree').val();
    jsonData["year"] = $('#year').val();

    edu.push(jsonData);
    console.log(edu);
    document.getElementById("hdnEdu").value=JSON.stringify(edu);    
    
    edutable.clear();edutable.draw();
    var temp = "<input type='button' class='btnEduRemove' value='Remove'/>";
    for (var i = 0;i < edu.length; i++) {
        edutable.row.add([edu[i]['university'],edu[i]['degree'],edu[i]['year'],temp]);
        edutable.draw();
    }
}
});  
$('#tblEdu tbody').on( 'click', 'tr', function () {
edu.splice(edutable.row( this ).index(), 1);
document.getElementById('hdnEdu').value=JSON.stringify(edu); 
var temp = "<input type='button' class='btnEduRemove' value='Remove'/>";
    edutable.clear();edutable.draw();
    for (var i = 0;i < edu.length; i++) {
        edutable.row.add([edu[i]['university'],edu[i]['degree'],edu[i]['year'],temp]);
        edutable.draw();
    }
});  */
//applicant page -- Skill
/*var skilltable = $('#tblSkill').DataTable({
"lengthChange": true,
"searching": false,
"autoWidth": true,
"sDom": 'lfrtip',
"paging":   false,
"ordering": false,
"info":     false
});     
var skill = [] ;          
$('#btnAddSkill').on( 'click', function () {

if($('#type').val() != ''){
    var jsonData = {};
    jsonData["type"] = $('#type').val();
    jsonData["level"] = $('#level').val();

    skill.push(jsonData);
    console.log(skill);
    document.getElementById("hdnSkill").value=JSON.stringify(skill);    

    skilltable.clear();skilltable.draw();
    var temp = "<input type='button' class='btnSkillRemove' value='Remove'/>";
    for (var i = 0;i < skill.length; i++) {
        skilltable.row.add([skill[i]['type'],skill[i]['level'],temp]);
        skilltable.draw();
    }
}
});  
$('#tblSkill tbody').on( 'click', 'tr', function () {
skill.splice(skilltable.row( this ).index(), 1);
document.getElementById('hdnSkill').value=JSON.stringify(skill); 
var temp = "<input type='button' class='btnSkillRemove' value='Remove'/>";
    skilltable.clear();skilltable.draw();
    for (var i = 0;i < skill.length; i++) {
        skilltable.row.add([skill[i]['type'],skill[i]['level'],temp]);
        skilltable.draw();
    }
});*/
//applicant page -- Education End

//applicant page -- Work Experience
/*var exptable = $('#tblExp').DataTable({
"lengthChange": true,
"searching": false,
"autoWidth": true,
"sDom": 'lfrtip',
"paging":   false,
"ordering": false,
"info":     false
});               
var exp = [];
$('#btnAddExp').on( 'click', function () {

if($('#organization').val() != ''){
    var jsonData = {};
    jsonData["organization"] = $('#organization').val();        
    jsonData["rank"] = $('#rank').val();
    jsonData["start_date"] = $('#sdate').val();
    jsonData["end_date"] = $('#edate').val();

    exp.push(jsonData);
    console.log(exp);
    document.getElementById("hdnExp").value=JSON.stringify(exp);    

    exptable.clear();exptable.draw();
    var temp = "<input type='button' class='btnExpRemove' value='Remove'/>";
    for (var i = 0;i < exp.length; i++) {
        exptable.row.add([exp[i]['organization'],exp[i]['rank'],exp[i]['start_date'],exp[i]['end_date'],temp]);
        exptable.draw();
    }
}
});  
$('#tblExp tbody').on( 'click', 'tr', function () {
exp.splice(exptable.row( this ).index(), 1);
document.getElementById('hdnExp').value=JSON.stringify(exp); 
var temp = "<input type='button' class='btnExpRemove' value='Remove'/>";
    exptable.clear();exptable.draw();
    for (var i = 0;i < exp.length; i++) {
        exptable.row.add([exp[i]['organization'],exp[i]['rank'],exp[i]['start_date'],exp[i]['end_date'],temp]);
        exptable.draw();
    }
});*/
//applicant page -- Education End

//applicant page -- Refree
/*var refreetable = $('#tblRefree').DataTable({
"lengthChange": true,
"searching": false,
"autoWidth": true,
"sDom": 'lfrtip',
"paging":   false,
"ordering": false,
"info":     false
});   
var refree = [];            
$('#btnAddRefree').on( 'click', function () {    
if($('#refree_company').val() != ''){
    var jsonData = {};
    jsonData["first_name"] = $('#refree_first_name').val();        
    jsonData["last_name"] = $('#refree_last_name').val();
    jsonData["company"] = $('#refree_company').val();
    jsonData["rank"] = $('#refree_rank').val();
    jsonData["email"] = $('#refree_email').val();
    jsonData["mobile_no"] = $('#refree_mobile_no').val();

    refree.push(jsonData);
    console.log(refree);
    document.getElementById("hdnRefree").value=JSON.stringify(refree);    

    refreetable.clear();refreetable.draw();
    var temp = "<input type='button' class='btnRefreeRemove' value='Remove'/>";
    for (var i = 0;i < refree.length; i++) {
        refreetable.row.add([refree[i]['first_name']+' '+refree[i]['last_name'],refree[i]['company'],refree[i]['rank'],refree[i]['email'],refree[i]['mobile_no'],temp]);
        refreetable.draw();
    }
}
});  

$('#tblRefree tbody').on( 'click', 'tr', function () {
refree.splice(refreetable.row( this ).index(), 1);
document.getElementById('hdnRefree').value=JSON.stringify(refree); 
var temp = "<input type='button' class='btnExpRemove' value='Remove'/>";
    refreetable.clear();refreetable.draw();
    for (var i = 0;i < refree.length; i++) {
        refreetable.row.add([refree[i]['first_name']+' '+refree[i]['last_name'],refree[i]['company'],refree[i]['rank'],refree[i]['email'],refree[i]['mobile_no'],temp]);
        refreetable.draw();
    }
});*/
//applicant page -- Education End




/*
$('#btnSearch').on( 'click', function () {
	showLatestJob('tblUserJobList');		    
});
*/