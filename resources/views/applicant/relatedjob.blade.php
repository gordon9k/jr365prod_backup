@extends('layouts.innernavigate')
@section('title', 'Job')   
@section('content') 

<div class="col-sm-9">
		<div role="tabpanel">
		<h2>Related Job</h2>		
			<table id=tblList class="tblList table table-responsive">
        		<thead><tr><th width="300">Job Title</th><th>Company</th><th>Close Date</th></tr></thead>
        		<tbody>
        				@if(sizeof($job_list) > 0)
                			@foreach ($job_list as $lst)
	                		<tr>
				              	<td><a href="/application/{{ encrypt($lst->id) }}">{{ $lst->job_title }}</a> </td>
	              				<td>{{ $lst->company_name }}</td> 
	              				<td>{{ $lst->close_date}} 
	              					<?php  //	print_r($lst);     
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
	            			@endforeach
	            		@endif
        			</tbody> 
    	  		</table>	
		</div>
</div>
@endsection   
@extends('layouts.footer')