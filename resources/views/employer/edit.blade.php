@extends('layouts.innernavigate')
@section('title', 'Employer')
@section('content')
	<div class="col-sm-9"> 
		<h2>{{ trans('label.employer_edit') }}</h2>
		<div class="row text-center">
			@if(Session::has('flash_message'))
			    <div class="btn btn-success">{{ Session::get('flash_message') }}</div>
			@endif
			@if(Session::has('duplicate_message'))
				<div class="btn btn-warning">{{ Session::get('duplicate_message') }}</div>
			@endif	
		</div>
		<div class="row">
			@php $url = Session::get('lang').'/employer/'.encrypt($employer[0]->id) @endphp 
    		{{ Form::open(array('url' => $url, 'route'=>array('employer',$employer[0]->id),'role'=>'form', 'method' => 'PUT')) }}
    			{{ csrf_field() }}						
				<div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', trans('label.name')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
				        {{ Form::text('name', $employer[0]->name, array('class' => 'form-control','required' => 'required')) }}
				    </div>    
	            </div>	            
	            <div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', trans('label.mobile_no')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
				        {{ Form::text('mobile_no', $employer[0]->mobile_no, array('class' => 'form-control','required' => 'required')) }}
				    </div>    
	            </div>
	            <div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', trans('label.email')) }} <span style='color:red; font-size: 20px; font-weight: bold;'></span>
				        {{ Form::email('email', $employer[0]->email, array('class' => 'form-control')) }}
				    </div>    
	            </div>
	            <div class="col-sm-6">
					<div class="form-group">	
						{{ Form::label('name', 'Facebook Account') }}
				        {{ Form::text('facebook', $employer[0]->facebook, array('class' => 'form-control')) }}
				    </div>    
	            </div>
	            <div class="col-sm-6">
					<div class="form-group">	
						{{ Form::label('name', 'Viber No') }}
				        {{ Form::text('viber', $employer[0]->viber, array('class' => 'form-control')) }}
				    </div>    
	            </div>	            
	            <div class="col-sm-6">
					<div class="form-group">	
						{{ Form::label('name', trans('label.city')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
				        {{ Form::select('city', $city, $employer[0]->city_id ,array('class' => 'form-control', 'id'=>'ddlEmployerCity'))}}
			        </div>    
	            </div>	 
	            <div class="col-sm-6">
					<div class="form-group">	
						{{ Form::label('name',trans('label.township')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
	               		{{ Form::hidden('hd_employer_tsp_id',$employer[0]->township_id,array('id'=>'hd_employer_tsp_id')) }} 
	              		<div id='emp_tsp_div'></div>
              		</div>    
	            </div>
	            <div class="col-sm-12">
					<div class="form-group">	
						{{ Form::label('name', trans('label.address')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
				        {{ Form::text('address', $employer[0]->address, array('class' => 'form-control','required' => 'required')) }}
				    </div>    
	            </div>
	            <!-- <div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', 'Postal Code') }}
				        {{ Form::text('postal_code', $employer[0]->postal_code, array('class' => 'form-control')) }}
				    </div>    
	            </div> -->
	                      
	            
	            <div class="col-sm-12">
					<div class="form-group">
						<center>{{ Form::submit('Update Profile!', array('class' => 'btn btn-primary green')) }}</center>
  						{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>
@endsection   
@extends('layouts.footer')