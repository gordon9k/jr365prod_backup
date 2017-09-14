@extends('layouts.innernavigate')
@section('title', 'Job Detail')
@section('content')  
<div class="col-sm-9">
	<section id="title">
		<div class="container">
			@if(sizeof($job) > 0)
			<div class="row">
				<div class="col-sm-12">					
					@if($job[0]->company_logo != '')
					@php $img = URL::to('/')."/uploads/company_logo/".$job[0]->company_logo; @endphp
					@else
					@php $img = URL::to('/')."/uploads/company_logo/logo.jpg"; @endphp
					@endif
					<img src="{{ $img }}" width="80px" height="80px" style='border: 2px solid #999; border-radius:6px;'>
					<div style='margin-left:100px; margin-top:-80px; color:#000; font-weight:bold; font-size:20px;'>{!! $job[0]->job_title !!}</div>
					<div style='margin-left:100px; margin-top:0px; color:green;'>{!! $job[0]->company_name !!}</div>						 	
				</div>
			</div>		
			<div class="row" style='margin-top:50px;'>
				<div class="col-sm-12 show_data" >{{ trans('label.jobtype') }} : {!!  $job[0]->type  !!}</div>
			</div>
			@if($job[0]->male != 0 && $job[0]->female != 0 && $job[0]->unisex != 0)
			<div class="row" style='margin-top:10px;'>
				<div class="col-sm-12" style='font-weight: bold;'>Gender : 			 			
					@if($job[0]->male != 0)		{{ $job[0]->male }} [M] @endif
					@if($job[0]->female != 0)	{{ $job[0]->female }} [F] @endif
					@if($job[0]->unisex != 0)	{{ $job[0]->unisex }} [M/F] @endif
				</div>	
			</div>
			@endif
			<div class="row" style='margin-top:10px;'>				
				@if ($job[0]->max_salary == 0)
				@php $salary = 'Negotiate' @endphp
				@elseif($job[0]->min_salary == 1000000)
				@php $salary = '100000 ++' @endphp
				@else
				@php $salary =  $job[0]->min_salary .' ~ '. $job[0]->max_salary @endphp
				@endif
				<div class="col-sm-12 show_data">{{ trans('label.pay_salary') }} : {{ $salary }} </div>
			</div>			
			@if($job[0]->single == 1)
			<div class="row" style='margin-top:10px;'>
				<div class="col-sm-12  show_data" style='font-weight: bold;'>Marital Status : Single</div>
			</div>
			@endif
			@if($job[0]->min_age > 0 || $job[0]->max_age > 0)
			<div class="row" style='margin-top:10px;'>
				<div class="col-sm-12 show_data">{{ trans('label.age_limit') }} : {!! $job[0]->min_age  !!} ~ {!! $job[0]->max_age  !!}</div>
			</div>
			@endif
			@if ($job[0]->accomodation == 1)
			<div class="row" style='margin-top:10px;'>
				<div class="col-sm-12 show_data">{{ trans('label.accomodatin') }} : Yes</div>
			</div>
			@endif
			@if ($job[0]->food_supply == 1)
			<div class="row" style='margin-top:10px;'>
				<div class="col-sm-12 show_data">{{ trans('label.food') }} : Yes</div>
			</div>
			@endif
			@if ($job[0]->ferry_supply == 1)
			<div class="row" style='margin-top:10px;'>
				<div class="col-sm-12 show_data">{{ trans('label.transportation') }} : Yes</div>
			</div>
			@endif
			@if($job[0]->description!= '')
			<div class="row" style='margin-top:10px;'>
				<div class="col-sm-12 show_data">			
					{{ trans('label.job_description') }}
				</div>	
				<div class="col-sm-12">	
					<div style='margin-left:25px; width:60%;'>
						{!! nl2br($job[0]->description) !!}</div>		
					</div>
				</div>
				@endif
				@if($job[0]->summary!= '')
				<div class="row" style='margin-top:10px;'>
					<div class="col-sm-12 show_data">			
						{{ trans('label.qualification') }}
					</div>	
					<div class="col-sm-12">	
						<div style='margin-left:25px; width:60%;'>
							{!! nl2br($job[0]->summary) !!}
						</div>		
					</div>
				</div>
				@endif
				@if($job[0]->requirement!= '')
				<div class="row" style='margin-top:10px;'>
					<div class="col-sm-12 show_data">			
						{{ trans('label.requirement') }} 
					</div>	
					<div class="col-sm-12">				 			
						<div style='margin-left:25px; width:60%;'>
							{!! nl2br($job[0]->requirement) !!}
						</div>
					</div>
				</div>
				@endif

				<div class="row" style='margin-top:10px;'>
					<div class="col-sm-12">
						@if(Session::has('flash_message'))
						<div class="success_message">
							{{ Session::get('flash_message') }}
						</div>
						@endif
						@if(Session::has('duplicate_message'))
						<div class="warning_message">
							{{ Session::get('duplicate_message') }}
						</div>
						@endif				

						<p class='show_data'>If you are interested in that position, please apply before {{ $job[0]->close_date }}.</p>

						@if (!Auth::guest())
						@if(Auth::user()->user_type == 2) 		
						@php $url = Session::get('lang').'/application' @endphp 
						{{ Form::open(array('url' => $url,'route'=>'application.store','role'=>'form','files'=>'true')) }}
						{{ Form::hidden('hid', $job[0]->id) }} 
						<center>{{ Form::submit('Apply Here', array('class' => 'btn btn-primary')) }}</center> 
						{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

						<script>
							function clicked() {
								if (confirm('Do you wanna to submit?')) {
									yourformelement.submit();
								} else {
									return false;
								}
							}

						</script>
						<input class="btn btn-primary" type="submit" onclick="clicked();" name="submit" value="Apply" />  --}}


						@elseif(Auth::user()->user_role == 1 || Auth::user()->user_role == 2) 	
						@if( $job[0]->is_active == 0)				        		
						@php $url = Session::get('lang').'/job_activate' @endphp 
						{{ Form::open(array('url' => $url,'route'=>'job.job_activate','role'=>'form')) }}
						{{ Form::hidden('hid', $job[0]->id) }} 
						<center>{{ Form::submit('Activate', array('class' => 'btn btn-primary')) }}</center> 
						@endif
						@elseif(Auth::user()->user_type == 1) 
						@php $edit_url = '/en/job/'.encrypt($job[0]->id).'/edit' @endphp
						<center><a href="{{ $edit_url }}" target='_blank'  class='btn btn-primary' ><i class='fa fa-edit'> Modify </i></a></center>		
						@endif	
						@else

						<label style="color:red;" class="show_data">{{ trans('label.job_apply_msg') }}</label>	        	
						@endif	
					</div>
				</div>

  <!-- Modal Structure 
  <div id="modal1" class="modal bottom-sheet">
    <div class="modal-content">
      <h5>Apply for {!! $job[0]->job_title !!}</h5>
    </div>
    <div class="modal-footer">
     @php $url = Session::get('lang').'/application' @endphp 
    	{{ Form::open(array('url' => $url,'route'=>'application.store','role'=>'form','files'=>'true')) }}
      	{{ Form::hidden('hid', $job[0]->id) }} 
      	{{ Form::label('name', 'Upload your CV here') }}
      	{{ Form::file('cv', array('class' => 'form-control')) }}
	  	{{ Form::submit('Apply!', array('class' => 'waves-effect waves-light btn btn-primary green')) }}  
    </div>
  </div>
-->
</div>
@else
<div class="row">
	<div class="col-sm-12">		
		<b>There is no record to show.</b>
	</div>
</div>
@endif
</section>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
@endsection
@extends('layouts.footer')