@extends('layouts.innernavigate')
@section('title', 'Applicant')
@section('content')  
<div class="col-sm-9">
	<h2>Generate Key</h2>
	<div class="row text-center">
		@php $url = Session::get('lang').'/package_key' @endphp  
		{{ Form::open(array('url' => $url,'route'=>'package_key.store','role'=>'form','files'=>'true')) }}
			<div class='row'>
				<div class='col-sm-4'>
					{{ Form::radio('key', 'Platinum', ['class' => 'form-control']) }} Platinum Package
				</div>
				<div class='col-sm-4'>
					{{ Form::radio('key', 'Gold', ['class' => 'form-control']) }} Gold Package	
				</div>    
				<div class='col-sm-4'>
				{{ Form::submit('Generate Key', array('class' => 'btn btn-primary')) }}
				</div>
			</div>
		{{ Form::close() }}
		</div>
		@if(Session::has('flash_message'))
		    <span style='color:#11a009; font-size:16px; margin:0px 0 0 80px;  font-weight:bold;'>{{ Session::get('flash_message') }}</span>
		@endif
		@if(Session::has('duplicate_message'))
			<span style='color:red;'>{{ Session::get('duplicate_message') }}</span>
		@endif	
	
	</div>
@endsection
@extends('layouts.footer')