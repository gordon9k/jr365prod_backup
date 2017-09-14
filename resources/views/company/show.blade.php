@extends('layouts.innernavigate')
@section('title', 'Company Information')
@section('content')  
<div class="col-sm-9">
	<section id="title">
		<div class="container">
			<div class="row">			
				<div class="col-sm-12">			
					@if($company[0]->company_logo != '')
			 		@php $img = URL::to('/')."/uploads/company_logo/".$company[0]->company_logo; @endphp
			 		@else
			 		@php $img = URL::to('/')."/uploads/company_logo/logo.jpg"; @endphp
			 		@endif
			 		<img src="{{ $img }}" class='img-responsive' style='width:100px; height:100px; border: 2px solid #CFE8D4;'>
		 			<div style='margin-left:120px; margin-top:-80px; color:#000; font-weight:bold; font-size:18px;'>		 			
					<h5>{!! $company[0]->company_name !!}</h5></div>
					<div style='margin-left:120px; margin-top:0px; color:green;'>{!! $company[0]->mobile_no !!}</div>
					<div style='margin-left:120px; margin-top:0px; color:green;'>{!! $company[0]->email !!}</div>
					<div style='margin-left:120px; margin-top:0px; color:green;  width:50%;'>{!! $company[0]->address !!}, {!! $company[0]->township !!}, {!! $company[0]->city !!}, {!! $company[0]->postal_code !!} {!! $company[0]->country !!}</div>
		 			<!--
		 			<div style='margin-left:120px; margin-top:0px; color:#000; font-weight:bold; font-size:18px;'>		 			
					<h5>{!! $company[0]->company_name !!}</h5></div>
					<div style='margin-left:120px; margin-top:0px; color:green;'>{!! $company[0]->mobile_no !!}</div>
					<div style='margin-left:120px; margin-top:0px; color:green;'>{!! $company[0]->email !!}</div>
					<div style='margin-left:120px; margin-top:0px; color:green;   width:50%;'>{!! $company[0]->address !!}, {!! $company[0]->township !!}, {!! $company[0]->city !!}, {!! $company[0]->postal_code !!} {!! $company[0]->country !!}</div>-->	 			
				</div>
			</div>
			
			<div class="row" style='margin-top:30px;'>
				<div class="col-sm-12">	Business Type : {!! $company[0]->job_industry !!}</div>
			</div>
			
			<div class="row" style='margin-top:10px;'>
				<div class="col-sm-12">	Primary Contact Person : {!! $company[0]->primary_contact_person !!}</div>
			</div>
			
			<div class="row" style='margin-top:10px;'>
				<div class="col-sm-12"> Mobile : {!! $company[0]->primary_mobile !!}</div>
			</div>
			
			<div class="row" style='margin-top:10px;'>
				<div class="col-sm-12"> Secondary Contact Person : {!! $company[0]->secondary_contact_person !!}</div>
			</div>
			
			<div class="row" style='margin-top:10px;'>
				<div class="col-sm-12">	Mobile	: {!! $company[0]->secondary_mobile !!}</div>				
			</div>
			
			<div class="row" style='margin-top:30px; width:65%;'>
				<div class="col-sm-12" >			
					<h5>Company About</h5>	
		 		</div>			
		 		<div class="col-sm-12">				 			
					{!! $company[0]->description !!}
				</div>
			</div>
		</div>
		<!--<a href="{{ url(Session::get('lang').'/dashboard') }}"><i class='fa fa-backward'></i> Back</a>-->
		@if(Auth::user()->user_type == 1) <center><a href="{{ URL::to(Session::get('lang').'/company/' . encrypt($company[0]->id) .'/edit') }}" target='_blank'  class='btn btn-primary' ><i class='fa fa-edit'> Modify </i></a></center>@endif 
	</section>
	</div>
@endsection



