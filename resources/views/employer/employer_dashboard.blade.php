@extends('layouts.innernavigate')
@section('title', 'Employer Dashboard')
@section('content') 


<div class="col-sm-9">
	<h2>{{ Auth::user()->login_name }} Dashboard</h2>	
	<input type='hidden' id='expiredate' value='<?php echo $employer_info[0]->expired_date; ?>'>
	<div class="col-sm-12">		
		<div class='row'>
			<div class="col-sm-12">	
			@if(Session::has('flash_message'))
			    <span style='color:#11a009; font-size:16px; font-weight:bold;'>{{ Session::get('flash_message') }}</span>
			@endif
			@if(Session::has('duplicate_message'))
				<span style='color:red; font-size:16px;  font-weight:bold;'>{{ Session::get('duplicate_message') }}</span>
			@endif	
			</div>
		</div>	
		<div class='row' style='margin-top:10px;'>		
			<div class="col-sm-4">	<span style='padding:10px;  border:2px solid #13ab15; color:#333; border-radius:7px; font-weight:bold; font-size:16px;'>{{ $employer_info[0]->package_type }} Package </span></div>
			<div class="col-sm-4">	<div style='padding-top:10px; border-radius:7px; font-weight:bold; font-size:16px;'><a class='link-upgrade_pkg' style='color:#444;'><i class='fa fa-gift'></i> {{ trans('label.buy_package') }}</a></div>
			</div>
			<div class="col-sm-4">	@if($employer_info[0]->package_type != 'Basic')(<div id="expire" style='color:red; font-weight:bold; font-size:16px; padding-top:10px;'></div> @endif</div>
		</div>
	</div>
	<div class="col-sm-12" style='margin-top:10px;'>
		<span style='color:#13ab15; font-weight:bold; font-size:16px;'>{{ trans('label.business_name') }}</span>
		
		<hr style='display: block; height: 1px; border: 0; border-top: 1px solid #34b80c; margin: 1em 0; padding: 0; '>
			<div class='row'>
				<div class="col-sm-12">		
					<div class="form-group">
					    {{ Form::select('company', $company_list, 0, array('class' => 'form-control', 'id'=>'ddlCompany'))}}
				    </div> 
			    	</div>
			    <div class="col-sm-12">
					<div class="form-group" style="margin-top: 15px;">
					@php $url = Session::get('lang').'/company/create' @endphp 
				<a href="{{ url($url) }}" target='blank'><i class="fa fa-plus"> New Company </i></a> 
						<span style='margin-left:10px;'><a id='lnkView' target='blank'><i class="fa fa-list"> {{ trans('label.view') }}</i></a></span>  
					<!-- 	<a id='lnkEdit' target='blank'><i class="fa fa-edit"> Edit </i></a> 	 --> 
						<span style='margin-left:10px;'><a id='lnkDelete' style='color:red; target='blank'><i class="fa fa-remove"> {{ trans('label.delete') }}</i></a></span>										
                  		{{ Form::hidden('hdnCompany', csrf_token(), array('id'=>'hdnCompany')) }}
                  		
                  	</div>
				</div>		
			</div> 
		<hr style="display: block; height: 1px; border: 0; border-top: 1px solid #34b80c; margin: 1em 0; padding: 0;">
	</div>		
	<div class="col-sm-12" >
		<h4>Jobs & Candidates</h4> 
		<table id="tblJobbyCandidate" class="tblList table table-responsive" style='min-height:200px;'>
        	<thead><tr><th></th></tr></thead>
    		</table>			
    	</div>
    	<hr style="display: block; height: 1px; border: 0; border-top: 1px solid #34b80c; margin: 1em 0; padding: 0;">
    	<div class="col-sm-12">
    		<h4>Jobs & Shortlisted</h4>
		<table id="tblJobbyShortlisted" class="tblList table table-responsive"  style='min-height:200px;'>
        	<thead><tr><th></th></tr></thead>
    		</table>			
    	</div>
    
	</div>
	
	<div class="popup" id="upgrade_pkg">
		<div class="popup-form">
			<div class="popup-header">
				<a class="close"><i class="fa fa-remove fa-lg"></i></a>
				<h2 style='font-size: 16px;'>Buy Package</h2>
			</div>			
			@php $url = Session::get('lang').'/upgrade_package' @endphp  
			{{ Form::open(array('url' => $url,'route'=>'employer.store','role'=>'form')) }}
				<div class="form-group">
					{{ Form::select('pkg', ['Gold' => 'Gold Package','Platinum' => 'Platinum Package'],0,array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('name', 'Enter User Key') }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
	        			{{ Form::text('key_code', '', array('class' => 'form-control','required' => 'required')) }}
					{{ Form::hidden('hidden_id',  $employer_info[0]->id , array('class' => 'form-control','required' => 'required')) }}
				</div>
				<center>{{ Form::submit('Upgrade', array('class' => 'btn btn-primary')) }}</center>
			{{ Form::close() }}
		</div>
	</div>
 </div>	
</div>

<script>
// Set the date we're counting down to

	var countDownDate = new Date(document.getElementById("expiredate").value).getTime();	
	console.log(countDownDate);
	
	// Update the count down every 1 second
	var x = setInterval(function() {
	
	  	// Get todays date and time
	  	var now = new Date().getTime();
	
	  	// Find the distance between now an the count down date
	  	var distance = countDownDate - now;
	
	  	// Time calculations for days, hours, minutes and seconds
	  	var days = Math.floor(distance / (1000 * 60 * 60 * 24));
	  	var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	  	var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	 	 var seconds = Math.floor((distance % (1000 * 60)) / 1000);
	
	  	// Display the result in the element with id="demo"
	  	document.getElementById("expire").innerHTML ="Time Left: " + days + "d " + hours + "h "
	  	+ minutes + "m " + seconds + "s";
	
	  	// If the count down is finished, write some text 
	  	if (distance < 0) {
	    	clearInterval(x);
	    	document.getElementById("expire").innerHTML = "EXPIRED";
	  	}
	}, 1000);
</script>
@endsection

@extends('layouts.footer')