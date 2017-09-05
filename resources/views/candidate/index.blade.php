@extends('layouts.innernavigate')
@section('title', 'Candidate List')
@section('content')  
<div class="col-sm-9">
	
	<h2>{{ $title[0] }}</h2>
	
	@if($company[0]->company_logo != '')
 		@php $img = URL::to('/')."/uploads/company_logo/".$company[0]->company_logo @endphp
 	@else
 		@php $img = URL::to('/')."/uploads/company_logo/logo.jpg" @endphp
 	@endif
 	<img src="{{ $img }}" class='img-responsive' style='width:100px; height:100px; border: 2px solid #CFE8D4 ; border-radius:10px;'>
	<div style='color:green; font-size:14px; margin-top:-80px; margin-left: 120px; font-weight:bold;'>{{ $company[0]->company_name }}</div>	
	<div style='color:#000; font-size:16px; margin-top:0px; margin-left: 120px;'><h5>{{ $company[0]->job_title }}</h5></div>	
	@if(sizeof($candidate) > 0)
	
	<table id="tblList" class="tblList table table-responsive" style='margin-top: 100px;'>
		<thead><tr><th></th><th></th></tr></thead>
		<tbody>			
	    	@foreach($candidate as $cv)			    	   	
			<tr>
				<td style='border-bottom:1px solid #19fb0d; padding:10px;'>				
				@if($cv->photo != '')
		 			@php $img = URL::to('/')."/uploads/resume-photo/".$cv->photo; @endphp
		 		@else
		 			@php $img = URL::to('/')."/uploads/resume-photo/person.jpg" @endphp
		 		@endif
		 		<div style='margin-top:0px; margin-left:0px; float:left;'>
		 		<img src="{{ $img }}" class='img-responsive' style='width:80px; height:80px; border: 2px solid #CFE8D4 ; border-radius:10px;'>		 		
		 		</div>
		 		<div style='margin-top:5px; margin-left:30px; float:left;'>
				{{ $cv->name }} <br> {{ $cv->mobile_no }} <br> {{ $cv->email }} 
				</div>
				</td>
		        	<td style='border-bottom:1px solid #19fb0d; padding:10px;'><div style='margin-top:20px;'><a href="{{ URL::to('view_cv/' . encrypt($cv->user_id) .'/'. encrypt($cv->jid) ) }}"> Detail </a></div></td>
	        </tr>
	        @endforeach	        
   		</tbody> 
	</table>
	@else
	<table  class="table table-responsive" style='font-size:16px; margin-top:100px;'>
		<tr><td><center><h5 style='color:red;'>There is no candidate for that position.</h5></center></td></tr>
	</table>
	@endif
</div>
@endsection
@extends('layouts.footer')