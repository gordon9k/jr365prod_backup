@extends('layouts.innernavigate')
@section('title', 'Applicant Information')
@section('content')
<style>			
@media (max-width: 991px){
	.mobile_contact {
		display:block;
	}
	.web_contact {
		display:none;
	}
	#btnSendMsg{
		display:block;
	}
	#btnSendMsg1{
		display:none;
	}
}	
		
@media (min-width: 991px){
	.mobile_contact {
		display:none;
	}
	.web_contact {
		display:block;
	}
	#btnSendMsg{
		display:none;
	}
	#btnSendMsg1{
		display:block;
	}
}
</style>
<div class="col-sm-9">
	<section id="title">
		<div class="container">
			<div class="row">
				<div class="col-sm-9">		
					
					@if($applicant[0]->photo != '')
			 		@php $img = URL::to('/')."/uploads/resume-photo/".$applicant[0]->photo; @endphp
			 		
			 		<img src="{{ $img }}" class='img-responsive' style='width:100px; height:100px; border: 2px solid #CFE8D4; border-radius:10px;'>
			 		<div style='margin-left:120px; margin-top:-90px; color:#000; font-weight:bold; font-size:18px;'>	
					<h5>{!! $applicant[0]->name !!}</h5></div>
					<div style='margin-left:120px; margin-top:-0px; color:green; font-weight:bold; font-size:14px;'>	
					{!! $applicant[0]->mobile_no !!}  <br> {!! $applicant[0]->address !!}, {!! $applicant[0]->township !!}, {!! $applicant[0]->city !!} <br> {!! $applicant[0]->email !!} </div>
					@else
					<div style='margin-left:0px; margin-top:0px; color:#000; font-weight:bold; font-size:18px;'>	
					<h5>{!! $applicant[0]->name !!}</h5></div>
					<div style='margin-left:0px; margin-top:0px; color:green; font-weight:bold; font-size:14px;'>	
					{!! $applicant[0]->mobile_no !!} <br> {!! $applicant[0]->address !!}, {!! $applicant[0]->township !!}, {!! $applicant[0]->city !!} <br> {!! $applicant[0]->email !!} </div>
		 			@endif			 			
					
				</div>
			</div>
		</div>
	</section>
	<br>
	<section id="applicant_show">
		<div class="container">
			<div class="row">
				<div class="col-sm-9">
					<div id="widget-contact">
						<!-- <img src="images/resume.jpg" alt="" class="pull-left" /> -->
						<h5>Personal Information</h5>
						<table>
							<tr><td style='width:120px;'>Father Name</td><td>{!! $applicant[0]->father_name !!}</td></tr>
							<tr><td>N.R.C Number</td><td>{!! $applicant[0]->nrc_no !!}</td></tr>
							<tr><td>Date of Birth</td><td>{!! $applicant[0]->date_of_birth !!}</td></tr>
							<tr><td>Nationality</td><td>{!! $applicant[0]->nationality !!}</td></tr>
							<tr><td>Religion</td><td>{!! $applicant[0]->religion !!}</td></tr>
							<tr><td>Marital Status</td><td>{!! $applicant[0]->marital_status !!}</td></tr>
							<tr><td>Current Position</td><td>{!! $applicant[0]->current_position !!}</td></tr>
							<tr><td>Desire Position</td><td>{!! $applicant[0]->desired_position !!}</td></tr>
							<tr><td>Driving License</td><td><?php echo ($applicant[0]->driving_license==1)? 'Yes':'No '?></td></tr>							
							<tr><td>Expected Salary</td><td>{!! $applicant[0]->expected_salary !!}</td></tr>
						</table>
						<hr style='margin-top:10px;'>
						<div class='row' style='margin-top:-40px;'>
							<div class='col-sm-12'><h5>Education</h5></div>
							<div class='col-sm-12'>
							<ul>
							@foreach ($education as $edu)
								<li><div class="col-sm-4">{{ $edu->university }}</div>						
								<div class="col-sm-4">{{ $edu->degree }} </div><div class="col-sm-4"> {{ $edu->start_date }} ~ {{ $edu->end_date }}</div></li>
			                @endforeach
		                	</ul>
		                	</div>
						</div>
						<hr style='margin-top:10px;'>
						<div class='row' style='margin-top:-40px;'>
							<div class='col-sm-12'><h5>Other Qualification/Certificates</h5></div>							
							<div class='col-sm-12'>
							<ul>
							@foreach ($skill as $sk)	
								<li><div class="col-sm-4">{{ $sk->language }}</div>
								<div class="col-sm-4">Spoken - {{ $sk->spoken_level }}</div>
								<div class="col-sm-4">Written - {{ $sk->written_level }}</div></li>			
							@endforeach	
		                	</ul>
		                	</div>
						</div>
						<hr style='margin-top:10px;'>
						<div class='row' style='margin-top:-40px;'>
							<div class='col-sm-12'><h5>Working Experience</h5></div>
							<div class="col-sm-12">
							<ul>
							@foreach ($experience as $exp)               
								<li><div class="col-sm-4">{{ $exp->organization }} </div>
								<div class="col-sm-4"> {{ $exp->rank }}</div>
								<div class="col-sm-"><span style='color:green;'>{{ $exp->start_date }} ~~ {{ $exp->end_date }}</span></div></li>					
						 	@endforeach
						 	</ul>	
						 	</div>
						</div>
						
						<div class='row' style='margin-top:20px;'>
						@if($applicant[0]->attach_cv != '')
							<div class="col-sm-8"><i class="fa fa-download"></i> <a href = "{{ URL::to( '/uploads/cv/' . $applicant[0]->attach_cv)  }}" download="{{$applicant[0]->attach_cv}}" target="_blank" >Download CV Here </a></div>
							<div class="col-sm-4"><i class="fa fa-file-text"></i> <a href = "{{ URL::to( '/uploads/cv/' . $applicant[0]->attach_cv)  }}" target="_blank" >View CV Here </a></div>
							@endif
				       		</div>
				       	</div>			
				       	<div class='row mobile_contact' style='margin-top:20px;'>
				       		@if(Auth::user()->user_type == 1)
				       		<div class="row" style='height:50px;'>							
					       		<a class="link-send btn btn-primary" ><i class="fa fa-send"></i> Send Message</a>
				       		</div>
				       		<div class="row" style='height:50px;'>
					       		@php  $call_no =  $applicant[0]->mobile_no; @endphp
				       			@if($call_no != "")
				       				@php 	$mobile_no = explode(",",$call_no);		@endphp
				       				@if(count($mobile_no) == 1)				       			
			       						@php $call_no = $mobile_no[0]; 	@endphp
			       						<a class="link-call1 btn btn-primary" href="tel:{{ $call_no }}"><i class="fa fa-phone"></i> Call for Interview</a>	
					       			@else
			       						<div class="popup" id="mobile_number">
											<div class="popup-form"  style='padding:3px; border:2px solid #fff; border-radius:3px; background-color:#fff;'>
												<div style='margin:5px; height:20px;'><a class="close"><i class="fa fa-remove fa-lg" style='color:green;'></i></a></div>
												<div>
													@foreach($mobile_no as $mb)				       						
			       										<div class="form-group">
			       											<a class="link-calls btn btn-primary"  href="tel:{{ $mb }}"><i class="fa fa-phone"></i> {{ $mb }} </a>
			       										</div>
			       									@endforeach
												</div>	
											</div>
										</div>
										<a class="link-call btn btn-primary"><i class="fa fa-phone"></i> Call for Interview</a>
					       			@endif				       				
				       			@endif					       		
					       		</div>
							@endif
				       	</div>	
				       <div class='row web_contact' style='margin-top:20px;'>
				       		@if(Auth::user()->user_type == 1)
							<div class="row col-sm-8">							
					       	<a class="link-send btn btn-primary" ><i class="fa fa-send"></i> Send Message</a></div>
					       	</div>
					       	@endif
				       	</div>		 					
					</article>
				</div>				
				</div>
			</div>
		</div>
	</section>
	<!-- Modal Structure -->
<div class="popup" id="send">
	<div class="popup-form">
		<div class="popup-header">
			<a class="close"><i class="fa fa-remove fa-lg"></i></a>
			<h2>Send Job Offer</h2>
		</div>	
		{{ Form::open(array('url' => Session::get('lang'). '/candidate','route'=>'candidate.store','role'=>'form')) }}
    	{{ Form::hidden('hmobile_no', $applicant[0]->mobile_no, array('id'=>'hmobile_no','class' => 'form-control')) }}
    	{{ Form::hidden('heid', Auth::user()->id, array('id'=>'heid','class' => 'form-control')) }} 
   	{{ Form::hidden('haid', $applicant[0]->user_id, array('id'=>'haid','class' => 'form-control')) }} 
   	{{ Form::hidden('hjid', $applicant[0]->jid, array('id'=>'hjid','class' => 'form-control')) }}	
    	<div class="form-group">
    	@if( $applicant[0]->mobile_no != "")
		@php 	$mobile_no = explode(",", $applicant[0]->mobile_no);		@endphp
		@endif
        {{ Form::label('name', 'Choose number') }}
        {{ Form::select('mobile_no', $mobile_no, '1',array('id'=>'mobile_no','class' => 'form-control'))}} 
		</div>		    
    	<div class="form-group">	
     	{{ Form::label('name', 'Contact Info') }} 
       	{{ Form::text('contact_info', Input::old('contact_no'), array('id'=>'contact_info','class' => 'form-control')) }}
       	</div>
       	<div class="form-group">
       	{{ Form::label('name', 'Job Description') }} 
       	{{ Form::textarea('description', Input::old('description'), array('id'=>'txtdescription','class' => 'form-control','size' => '100x3')) }}
       	</div>       	
		<center>
		<!-- <a id="sendsms" class="link-send btn btn-primary" href="sms:{{ $applicant[0]->mobile_no }}"><i class="fa fa-send"></i> Send Message</a></div> -->
		<div class='mobile_contact'>{{ Form::submit('Send', array('id'=>'btnSendMsg','class' => 'btn btn-primary')) }}</div>
		<div class='web_contact'>{{ Form::submit('Send', array('id'=>'btnSendMsg1','class' => 'btn btn-primary')) }}</div>
		</center>		          	
    	{{ Form::close() }}					
	</div>
</div> 
</div>
@endsection
@extends('layouts.footer')