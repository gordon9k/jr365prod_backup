@extends('layouts.innernavigate')
@section('title', 'Company')
@section('content')
	<div class="col-sm-9"> 
	<h2>{{ trans('label.company_new') }}</h2>
		@if(Session::has('flash_message'))
	    	<div class="alert alert-success">
	        	{{ Session::get('flash_message') }}
	    	</div>
		@endif
		@if(Session::has('duplicate_message'))
	    	<div class="alert alert-danger">
	        	{{ Session::get('duplicate_message') }}
	    	</div>
		@endif
		@php $url = Session::get('lang').'/company' @endphp 
		{{ Form::open(array('url' => $url,'route'=>'company.store','role'=>'form','files'=>'true')) }}		
		{{ csrf_field() }}
		<div class="col-sm-12">
			<div class="form-group">						
			    	@php $businessType = ['1'=>'Company', '2'=>'Non-Company'] @endphp
				{{ Form::label('name', 'Type') }}
			        {{ Form::select('ddlbusinessType ', $businessType ,  Input::old('type'),array('class' => 'form-control', 'id'=>'ddlbusinessType'))}}
			</div>
		</div>
		<div class="col-sm-6">						
			<div class="form-group">	
				{{ Form::label('name', trans('label.name')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			    	{{ Form::text('company_name', Input::old('company_name'), array('class' => 'form-control','required' => 'required')) }}
			</div>    
    		</div>
            	<div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', 'Business Type') }} <span style='color:red; font-size: 20px; font-weight: bold;'></span>
         			{{ Form::select('job_industry', $job_industry , Input::old('id'),array('class' => 'form-control','id'=>'ddlJobIndustry'))}}
         		</div>    
            </div>            
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', trans('label.mobile_no')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::text('mobile_no', Input::old('mobile_no'), array('class' => 'form-control','required' => 'required')) }}
			    </div>    
            </div>
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', trans('label.email')) }} <span style='color:red; font-size: 20px; font-weight: bold;'></span>
			        {{ Form::email('email', Input::old('email'), array('class' => 'form-control')) }}
			    </div>    
            </div>              
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', trans('label.city')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::select('city', $city , 1 , array('class' => 'form-control','id'=>'ddlCompanyCity'))}}
		        </div>    
            </div> 
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', trans('label.township')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        <div id='tsp_div'></div>
			    </div>    
            </div>          
            <div class="col-sm-12">
				<div class="form-group">	
					{{ Form::label('name', trans('label.address')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::text('address', Input::old('address'), array('class' => 'form-control','required' => 'required')) }}
			    </div>    
            </div> 
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', 'Primary Contact Person') }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::text('primary_contact_person', Input::old('primary_contact_person'), array('class' => 'form-control','required' => 'required')) }}
			    </div>    
            </div> 
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', trans('label.mobile_no')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::text('primary_mobile', Input::old('primary_mobile'), array('class' => 'form-control','required' => 'required')) }}
			    </div>    
            </div>
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', 'Secondary Contact Person') }} 
			        {{ Form::text('secondary_contact_person', Input::old('secondary_contact_person'), array('class' => 'form-control')) }}
			    </div>    
            </div> 
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', trans('label.mobile_no')) }} 
			        {{ Form::text('secondary_mobile', Input::old('secondary_mobile'), array('class' => 'form-control')) }}
			    </div>    
            </div>
            <!-- <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', 'Postal Code') }}
				        {{ Form::text('postal_code', Input::old('postal_code'), array('class' => 'form-control')) }}
				     </div>    
            </div>  -->           
            <div class="col-sm-12">
				<div class="form-group">	
					{{ Form::label('name', trans('label.description')) }} <span style='color:red; font-size: 20px; font-weight: bold;'></span>
			        {{ Form::textarea('description', Input::old('description'),array('class' => 'form-control')) }}
			     </div>    
            </div>
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', 'Website') }}
			        {{ Form::text('website', Input::old('website'), array('class' => 'form-control')) }}
			    </div>    
            </div>
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', 'Logo') }}
         			{{ Form::file('logo', array('class' => 'form-control')) }}
         		</div>    
            </div>   
            <div class="col-sm-12">
				<div class="form-group">	
					<center>{{ Form::submit('Create!', array('class' => 'btn btn-primary')) }}</center>
					{{ Form::close() }}
				</div>    
            </div>
    </div>
@endsection
@extends('layouts.footer')
