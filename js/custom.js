$(function(){		
	showLatestJob('tblLatestJobs');
	getJobType(); 
	getJobCategory(); 
	getCity();	
	$('.modal-trigger').leanModal();
	$(".dropdown-button").dropdown();
});

$('#btnHpSearch').on( 'click', function () {
	showLatestJob('tblLatestJobs');		    
});

function showLatestJob(tbl_name){
	var type = $("#jobType").val()
	var category = $("#category").val();		
	var city =$("#city").val();
	var township =$("#township").val();
	
	$('#'+tbl_name).DataTable().destroy();
    $('#'+tbl_name).DataTable({
    	"jQueryUI":true,  
    	"scrollY":"300px", 
    	"paging": true,
		"lengthChange": false,		
  		"pageLength": 10,
		"searching": false,
		"ordering": false,
		"autoWidth": true,	
		processing: true,
        serverSide: true,
        ajax: {
    		method: 'GET', // Type of response and matches what we said in the route
    	    url: 'getLatestJob', // This is the url we gave in the route
    	    data: {'type' : type, 'category' : category,'city' : city, 'township':township }, // a JSON object to send back
    	    },
        columns: [
        	  { data: 'open_date', name: 'open_date' },
                  { data: 'job_title', name: 'job_title'},
                  { data: 'company_name', name: 'company_name'},                 
                  { data: 'location', name: 'location' },                 
                  ]
      });	
}

function getCity(){
	$.ajax({
	        method: "GET",
	        url: "city"
	    })
	    .success(function(data) {    		
	    	 select = '<select name="city" class="browser-default transparent blue-text text-darken-4"  id="city"  onChange="javascript:getTownship(this);">';
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
	    	 select = '<select name="township" class="browser-default transparent blue-text text-darken-4" id="township" >';
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
	    	 select = '<select name="jobType" class="browser-default transparent blue-text text-darken-4"  id="jobType" >';
	    	 select +='<option value="0"   selected>Select Job Type</option>';
	      $.each(data, function(key, value) {
	     	 select +='<option value="'+key+'">'+value+'</option>';
	       });
	      select += '</select>';
	      $("#type_div").html(select);
	});
} 

function getJobCategory(){
	$.ajax({
	        method: "GET",
	        url: "category"	
	    })
	    .success(function(data) {
	    	 select = '<select name="category" id="category" class="browser-default transparent blue-text text-darken-4">';
	    	 select +='<option value="0"  selected>Select Category</option>';
	      $.each(data, function(key, value) {
	     	 select +='<option value="'+key+'">'+value+'</option>';
	       });
	      select += '</select>';
	      $("#category_div").html(select);
	});
} 


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
$("#search_text").autocomplete({
   source: function(request, response) {
       $.ajax({
           url: 'category_autocomplete',
           dataType: "json",
           data: {
               term : request.term
           },
           success: function(data) {
        	     
               response(data);
           }
       });
   },
   min_length: 3,   
   select: function (event, ui) {alert(ui.item.id);
       $('#category_id').val(ui.item.id);
   }
});*/


/*
$('#btnSearch').on( 'click', function () {
	showLatestJob('tblUserJobList');		    
});
*/