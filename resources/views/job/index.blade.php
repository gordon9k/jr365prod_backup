@extends('layouts.innernavigate')
@section('title', 'Job')
@section('content') 
<div class="col-sm-9"> 	
	<h2>{{ trans('label.job_list') }}</h2>	
	@php $url = Session::get('lang').'/jobs/create' @endphp 
	<a href="{{ url($url) }}" style="float:right"><i class="fa fa-plus-square"> Post Job</i></a><br>
	<div role="tabpanel">
		<ul class="nav nav-tabs nav-justified" role="tablist">
			<li role="presentation" class="active"><a href="#activejob" aria-controls="activejob" role="tab" data-toggle="tab">Active Jobs</a></li>
			<li role="presentation"><a href="#pendingjob" aria-controls="pendingjob" role="tab" data-toggle="tab">Pending Jobs</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="activejob">
				<table id="tblList" class="tblList table responsive-table">
					<thead>
						<tr><th></th><th></th><th></th><th></th></tr>
					</thead>
					<tbody>
						@foreach ($jobs as $job)   
						@if($job->is_active == 1)           
						<tr>
							<div class='row'>
								<td width="80px" style='border-bottom:1px solid #19fb0d; padding:10px;'>
									@php    
									switch($job->job_nature_id){
										case('2'):	echo "<label class='badge part-time'> <center>Part Time</center> </label>" ;
										break;
										case('3'):  echo "<label class='badge freelance'> <center>Freelance</center></label>" ;
										break;
										case('4'):	echo "<label class='badge internship'> <center>Contract</center> </label>" ;
										break;
										case('5'):	echo "<label class='badge temporary'> <center>Temporary</center> </label>" ;
										break;
										default:	echo "<label class='badge full-time'> <center>Full Time</center> </label>" ;
									}
									@endphp
								</td>
								<td style='border-bottom:1px solid #19fb0d; padding:10px;'><a href="{{ URL::to(Session::get('lang').'/application/' . encrypt($job->id) ) }}">
									<h5>{{ $job->job_title }}</h5>
									{{ $job->company_name }}<br>
									<i class='fa fa-calendar'></i> {{ $job->open_date }}</a>
								</td>         		
								<td width="80px" style='border-bottom:1px solid #19fb0d; padding:10px;'>
									<a href="{{ URL::to(Session::get('lang').'/job/' . encrypt($job->id) . '/edit') }}" ><i class='fa fa-edit'></i> Edit</a>
								</td>
								<td width="80px"  style='border-bottom:1px solid #19fb0d; padding:10px;'>

									{{ Form::open(['method' => 'DELETE', 
										'route' => ['job.destroy', encrypt($job->id)], 
										'id' => 'form-delete-record-' . $job->id,
										'style'=>'margin:0px;']) }}					    
										<a href=""  class="deleteRecord" data-toggle="modal"
										data-id="{{$job->id}}" data-product_token="{{ csrf_token() }}" data-product_name="{{ $job->job_title }}"
										data-product_destroy_route="{{ route('job.destroy', encrypt($job->id))}}">
										<i class='fa fa-remove'></i> Delete</a>		    
										{{ Form::close() }}

									</td>
								</div>
							</tr>  
							@endif
							@endforeach
						</tbody>
					</table>
				</div>
				<div role="tabpanel" class="tab-pane" id="pendingjob">
					<table id="tblList" class="tblList table responsive-table">
						<thead>
							@if ( Auth::check() && Auth::user()->user_role == 1)
							<tr><th></th><th></th><th></th><th></th></tr>
							@else 
							<tr><th></th><th></th><th></th></tr>
							@endif
						</thead>
						<tbody>
							@foreach ($jobs as $job)   
								@if($job->is_active == 0)           
								<tr>
									<div class='row'>
										<td width="80px" style='border-bottom:1px solid #19fb0d; padding:10px;'>
											@php    
											switch($job->job_nature_id){
												case('2'):	echo "<label class='badge part-time'> <center>Part Time</center> </label>" ;
												break;
												case('3'):  echo "<label class='badge freelance'> <center>Freelance</center></label>" ;
												break;
												case('4'):	echo "<label class='badge internship'> <center>Contract</center> </label>" ;
												break;
												case('5'):	echo "<label class='badge temporary'> <center>Temporary</center> </label>" ;
												break;
												default:	echo "<label class='badge full-time'> <center>Full Time</center> </label>" ;
											}
											@endphp
										</td>
										<td style='border-bottom:1px solid #19fb0d; padding:10px;'><a href="{{ URL::to(Session::get('lang').'/application/' . encrypt($job->id) ) }}">
											{{ $job->job_title }}<br>
											{{ $job->company_name }}<br>
											<i class='fa fa-calendar'></i> {{ $job->open_date }}</a>
										</td>

										@if ( Auth::check() && Auth::user()->user_role == 1)									
											<td width="80px"  style='border-bottom:1px solid #19fb0d; padding:10px;'>
												<a href="{{ URL::to(Session::get('lang').'/job/' . encrypt($job->id) . '/edit') }}" ><i class='fa fa-edit'></i> Edit</a>
											</td>
										@endif

										<td width="80px"  style='border-bottom:1px solid #19fb0d; padding:10px;'>

											{{ Form::open(['method' => 'DELETE', 
												'route' => ['job.destroy', encrypt($job->id)], 
												'id' => 'form-delete-record-' . $job->id,
												'style'=>'margin:0px;']) }}

												<a href=""  class="deleteRecord" data-toggle="modal"
												data-id="{{$job->id}}" data-product_token="{{ csrf_token() }}" data-product_name="{{ $job->job_title }}"
												data-product_destroy_route="{{ route('job.destroy', encrypt($job->id))}}">
												<i class='fa fa-remove'></i> Delete</a>		    
												{{ Form::close() }}

											</td>
										</div>
									</tr>   
									@endif
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		@endsection
		@extends('layouts.footer')