@extends('layouts.innernavigate')
@section('title', 'Job')
@section('content')
<div class="col-sm-9">  		
		<h2>{{ trans('label.job_new') }}</h2>
		<div class="row text-center">
			@if(Session::has('flash_message'))
			    <div class="btn btn-success">{{ Session::get('flash_message') }} <br> <span style='color:red;'>Your job is published after approved by Admin.</span></div>
			@endif
			@if(Session::has('duplicate_message'))
				<div class="btn btn-warning">{{ Session::get('duplicate_message') }}</div>
			@endif	
		</div>
		<div class="row">
			@php $url = Session::get('lang').'/job/' @endphp 
    		{{ Form::open(array('url' => $url,'route'=>'job.store','role'=>'form')) }}	
    			{{ csrf_field() }}	
    			<div class="col-sm-12">
					<div class="form-group">	
						{{ Form::label('name', trans('label.company')) }} 
				        {{ Form::select('company_id', $company , Input::old('id'),array('id'=>'company_name','class' => 'form-control','onChange'=>'getText(this)' )) }}
				   </div>    
	            </div>		            
	            <div class="col-sm-6">
					<div class="form-group">	
						{{ Form::label('name', trans('label.jobtype')) }} 
				        {{ Form::select('job_nature', $job_nature , Input::old('id'),array('class' => 'form-control')) }}
				    </div>    
	            </div>	
	            <div class="col-sm-6">
					<div class="form-group">	
						{{ Form::label('name',  trans('label.jobcategory')) }}
				        {{ Form::select('job_category', $job_category , Input::old('id'),array('class' => 'form-control')) }}
				    </div>    
	            </div>	
	            <div class="col-sm-6">
					<div class="form-group">	
						{{ Form::label('name', trans('label.title')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
				        {{ Form::text('job_title', Input::old('job_title'), array('class' => 'form-control','required'=>'required')) }}
				    </div>    
	            </div>	
	            <div class="col-sm-6">
					<div class="form-group">	
						<?php $salary = ['0'=>'Negotiate', '1'=>'less than 100000 Ks', '2' =>'100000 ~ 300000 Ks', '3'=>'300000 ~ 500000', '4' => '500000 ~ 1000000 Ks','5'=>'greate than 100000 Ks'];?>
				        {{ Form::label('name', trans('label.pay_salary')) }} <span style='color:red; font-size: 20px; font-weight: bold;'></span>
				        {{ Form::select('salary_range', $salary, '2',array('class' => 'form-control'))}} 
				    </div>    
	            </div>	
	            <div class="col-sm-12">
					<div class="form-group">	
						{{ Form::label('name', trans('label.job_description')) }} 
				        {{ Form::textarea('description', Input::old('description'), array('class' => 'form-control','size' => '100x3')) }}
				   </div>    
	            </div>	            
	            <div class="col-sm-12">
					<div class="form-group">	
						{{ Form::label('name', trans('label.qualification')) }}
				        {{ Form::textarea('qualification', Input::old('qualification'), array('class' => 'form-control','size' => '100x3')) }}
				    </div>    
	            </div>	
	            <div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', trans('label.male')) }}
						{{ Form::text('male', Input::old('male'), array('class' => 'form-control')) }}
			        </div>    
	            </div>	
	            <div class="col-sm-4">
		            <div class="form-group">	
		           	 	{{ Form::label('name', trans('label.female')) }}
		            	{{ Form::text('female', Input::old('famale'), array('class' => 'form-control')) }}
            		</div>
            	</div>
				<div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', trans('label.both')) }}
						{{ Form::text('unisex', Input::old('unisex'), array('class' => 'form-control')) }}
					</div>
				</div>				    
	            <div class="col-sm-4">
					<div class="form-group">						
						{{ Form::checkbox('single', '1',false) }} {{ trans('label.single') }}
				    </div>    
	            </div>
	           <!-- <div class="col-sm-4">
					<div class="form-group">						
						{{ Form::checkbox('graduate', '1',false) }} {{ trans('label.graduate') }}
				    </div>    
	            </div> -->
	            <div class="col-sm-4">
					<div class="form-group">						
						{{ Form::checkbox('accomodation', '1',false) }} {{ trans('label.accomodatin') }}
				    </div>    
	            </div>
	            <div class="col-sm-4">
					<div class="form-group">						
						{{ Form::checkbox('food', '1',false) }} {{ trans('label.food') }}
				    </div>    
	            </div>
	            <div class="col-sm-6">
					<div class="form-group">						
						{{ Form::checkbox('transportation', '1',false) }} {{ trans('label.transportation') }}		       
				    </div>    
	            </div>
	            <div class="col-sm-12">
					<div class="form-group">	
						{{ Form::label('name',  trans('label.age_limit') ) }}<br>
				        <div class="col-sm-6">{{ Form::text('min_age', Input::old('min_age'), array('id'=>'min_age','class' => 'form-control','placeholder'=>'Minimun Age')) }}</div>
				        <div class="col-sm-6">{{ Form::text('max_age', Input::old('max_age'), array('id'=>'max_age','class' => 'form-control','placeholder'=>'Maximum Age')) }}</div>
				    </div>     
	            </div>
	            <div class="col-sm-12">
					<div class="form-group">	<br>
						{{ Form::label('name', trans('label.requirement') ) }} 
				        {{ Form::textarea('requirement', Input::old('requirement'), array('class' => 'form-control','size' => '100x3')) }}
				    </div>    
	            </div>
	           <!-- <div class="col-sm-12" style=' margin:5px 0 10px 0; border-radius:5px;'>
					<div class="form-group">	
						{{ Form::checkbox('same_loc', '1',array('id'=>'same_loc'))}} {{  trans('label.require_location') }}<br>
						<div class="col-sm-6">						
						{{ Form::label('name', trans('label.city')) }}
				        {{ Form::select('city', $city , 1 , array('class' => 'form-control','id'=>'ddlCompanyCity'))}}
			        	</div>
			        	<div class="col-sm-6">
			        	{{ Form::label('name', trans('label.township')) }} 
				        <div id='tsp_div'></div>	
						</div>
				        <div class="col-sm-12">{{ Form::text('contact_info', Input::old('contact_info'), array('class' => 'form-control','placeholder'=>'Address & Phone Number')) }}</div>
				    </div>    
	            </div>	 -->	            
	            <div class="col-sm-6">
				<div class="form-group">	
				Set Open Date
				      {{ Form::date('open_date', Input::old('open_date'), array('class' => 'form-control','id'=>'open_date')) }}				        
				</div>    
	            </div>	
	            <div class="col-sm-6">
					<div class="form-group">
					Set Close Date
				        {{ Form::date('close_date', Input::old('open_date'), array('class' => 'form-control','id'=>'close_date'))}} 
				    </div>    
	            </div>	
	           
	            <div class="col-sm-12">
					<div class="form-group">	
						<center>{{ Form::submit('Create', array('class' => 'btn btn-primary')) }}</center>
					</div>    
	            </div>
	            {{ Form::close() }}				
		</div>		
	</div>
@endsection
@extends('layouts.footer')