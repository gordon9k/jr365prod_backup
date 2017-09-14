@extends('layouts.innernavigate')
@section('title', 'Employer')
@section('content')
		<div class="col-sm-9"> 
			<h2>{{ trans('label.employer_new') }}</h2>
			<div class="row text-center">
				@if(Session::has('flash_message'))
				    <div class="btn btn-success">{{ Session::get('flash_message') }}</div>
				@endif
				@if(Session::has('duplicate_message'))
					<div class="btn btn-warning">{{ Session::get('duplicate_message') }}</div>
				@endif	
			</div>
			<div class="row">
    		@php $url = Session::get('lang').'/employer' @endphp 
			{{ Form::open(array('url' => $url,'route'=>'employer.store','role'=>'form')) }}		
				{{ csrf_field() }}	
				<div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', 'Employer Name') }}
				        {{ Form::text('name', Input::old('name'), array('class' => 'form-control','required' => 'required')) }}
				    </div>    
	            </div>
	            <div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', 'Mobile No') }}
				        {{ Form::text('mobile_no', Input::old('mobile_no'), array('class' => 'form-control','required' => 'required')) }}
				    </div>    
	            </div>
	            <div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', 'Email') }}
				        {{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
				    </div>    
	            </div>
	            <div class="col-sm-12">
					<div class="form-group">	
						{{ Form::label('name', 'Address') }}
				        {{ Form::text('address', Input::old('address'), array('class' => 'form-control','required' => 'required')) }}
				    </div>    
	            </div>
	            <div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', 'Township') }}
				        {{ Form::select('township', $township , Input::old('id'),array('class' => 'form-control','required' => 'required')) }}
				    </div>    
	            </div>
	            <!-- <div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', 'Postal Code') }}
				        {{ Form::text('postal_code', Input::old('postal_code'), array('class' => 'form-control')) }}
				    </div>    
	            </div> -->
	            <div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', 'Country') }}
				        {{ Form::select('country', $country , Input::old('id'),array('class' => 'form-control')) }}
				    </div>    
	            </div>
	            <div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', 'City') }}
				        {{ Form::select('city', $city , Input::old('id'),array('class' => 'form-control'))}}
			       </div>    
	            </div>
	            <div class="col-sm-12">
					<div class="form-group">
						<center>{{ Form::submit('Create!', array('class' => 'btn btn-primary green')) }}</center>
  						{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>   
@endsection   
@extends('layouts.footer')