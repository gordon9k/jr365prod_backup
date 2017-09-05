@extends('layouts.innernavigate')
@section('title', 'Job')   
@section('content') 
<div class="col-sm-9">
		<div role="tabpanel">
		<h2>{{ trans('label.job_dashboard') }}</h2>
			<ul class="nav nav-tabs nav-justified" role="tablist">
				<li role="presentation" class="active"><a href="#apply" aria-controls="apply" role="tab" data-toggle="tab">{{ trans('label.user_job_list') }}</a></li>
				<li role="presentation"><a href="#interview" aria-controls="interview" role="tab" data-toggle="tab">{{ trans('label.job_offer_list') }}</a></li>
				<li role="presentation"><a href="#expire" aria-controls="expire" role="tab" data-toggle="tab">{{ trans('label.expire') }}</a></li>
			</ul>
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="apply">
					<table id=tblList class="tblList table table-responsive">
	        			<thead><tr><th>Job Title</th><th>Company</th><th>Date Apply</th><th>Close Date</th></tr></thead>
		        		<tbody>
	        				@if(sizeof($history_list) > 0)
	                			@foreach ($history_list as $lst)
	                			@if($lst->close_date >= date("Y-m-d")) 
					    	    <tr>
					              	<td><a href="/application/{{ encrypt($lst->job_id) }}">{{ $lst->job_title }}</a> </td>
		              				<td>{{ $lst->company_name }}</td> 
		              				<td width="15%">{{ $output = date('Y-m-d', strtotime($lst->date_apply)) }} </td>
		              				<td width="20%">{{ $lst->close_date}} <?php  //	print_r($lst);     
								      	switch($lst->job_nature_id){
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
									?></td>
			            			</tr>
			            			@endif
			            			@endforeach
			            		@endif
	        				</tbody> 
		    	  		</table>	
				</div>
				<div role="tabpanel" class="tab-pane" id="interview">
					<table id="tblList" class="tblList table table-responsive">
	        			<thead><th></th></thead>
		        		<tbody>
		        			@if(sizeof($job_offer) > 0)
	        				@foreach($job_offer as $job)
					        <tr>
						        <td>{{ $job->description }}
						        <div class="job-location">{{ $job->contact_info }}</div></td>
					        </tr>
					        @endforeach
					        @endif
        				</tbody> 
    	  			</table>
				</div>
				<div role="tabpanel" class="tab-pane" id="expire">
					<table id=tblList class="tblList table table-responsive">
        				<thead><tr><th>Job Title</th><th>Company</th><th>Date Apply</th><th>Close Date</th></tr></thead>
		        		<tbody>
	        				@if(sizeof($history_list) > 0)
	                			@foreach ($history_list as $lst) 
	                			@if($lst->close_date < date("Y-m-d"))
					    	    <tr>
					              	<td>{{ $lst->job_title }} </td>
		              				<td>{{ $lst->company_name }}</td> 
		              				<td>{{ $lst->date_apply}} </td>
		              				<td>{{ $lst->close_date}} <?php  //	print_r($lst);     
								      	switch($lst->job_nature_id){
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
									?></td>
			            			</tr>
			            			@endif
			            			@endforeach
			            		@endif
	        				</tbody> 
		    	  		</table>	
				</div>
			</div>
		</div>
@endsection   
@extends('layouts.footer')