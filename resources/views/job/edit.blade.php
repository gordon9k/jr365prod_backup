@extends('layouts.innernavigate')
@section('title', 'Job Edit')
@section('content')
	<div class="col-sm-9"> 
		<h2>{{ trans('label.job_edit') }}</h2>
		<div class="row text-center">
			@if(Session::has('flash_message'))
			    <div class="btn btn-success">{{ Session::get('flash_message') }} <br><span style='color:red;'>Your job is published after approved by Admin.</span></div>
			@endif
			@if(Session::has('duplicate_message'))
				<div class="btn btn-warning">{{ Session::get('duplicate_message') }}</div>
			@endif	
		</div>
		<div class="row">
			@php $url = Session::get('lang').'/job/'.encrypt($job[0]->id) @endphp 
    		{{ Form::open(array('url' => $url, 'route'=>array('job',$job[0]->id),'role'=>'form', 'method' => 'PUT')) }}
    			{{ csrf_field() }}	
    			{{ Form::hidden('job_active',$job[0]->is_active) }} 
    			<div class="col-sm-12">
					<div class="form-group">	
						{{ Form::label('name', trans('label.company'))}} 
				        {{ Form::select('company_id', $company , $job[0]->company_id,array('class' => 'form-control','onChange'=>'getText(this)')) }}
				   </div>    
	            </div>		            
	            <div class="col-sm-6">
					<div class="form-group">	
						{{ Form::label('name', trans('label.jobtype')) }} 
				    	{{ Form::select('job_nature', $job_nature , $job[0]->job_nature_id, array('class' => 'form-control')) }}
				    </div>    
	            </div>	
	            <div class="col-sm-6">
					<div class="form-group">	
						{{ Form::label('name',  trans('label.jobcategory')) }}
				        {{ Form::select('job_category', $job_category , $job[0]->job_category_id,array('class' => 'form-control')) }}
				    </div>    
	            </div>	
	            <div class="col-sm-6">
					<div class="form-group">	
						{{ Form::label('name', trans('label.title')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
				        {{ Form::text('job_title', $job[0]->job_title, array('class' => 'form-control','required'=>'required')) }}
				    </div>    
	            </div>	
	            <div class="col-sm-6">
					<div class="form-group">	
						<?php $salary = ['0'=>'Negotiate', '1'=>'less than 100000 Ks', '2' =>'100000 ~ 300000 Ks', '3'=>'300000 ~ 500000', '4' => '500000 ~ 1000000 Ks','5'=>'greate than 100000 Ks'];
					    $selected = 0;
					    switch ($job[0]->min_salary){
					    	case '': $selected = 0;break;
					    	case 0: $selected = 1;break;
					    	case 100000: $selected = 2;break;
					    	case 300000: $selected = 3;break;
					    	case 500000: $selected = 4;break;
					    	case 1000000: $selected = 5;break;
					    }    	
					    ?>
				        {{ Form::label('name',  trans('label.pay_salary')) }} <span style='color:red; font-size: 20px; font-weight: bold;'></span>
				        {{ Form::select('salary_range', $salary, $selected, array('class' => 'form-control'))}} 
				    </div>    
	            </div>	
	            <div class="col-sm-12">
					<div class="form-group">	
						{{ Form::label('name', trans('label.job_description')) }} 
				        {{ Form::textarea('description', $job[0]->description, array('class' => 'form-control','size' => '100x3')) }}
				   </div>    
	            </div>	            
	            <div class="col-sm-12">
					<div class="form-group">	
						{{ Form::label('name', trans('label.qualification')) }}  
				        {{ Form::textarea('summary', $job[0]->summary, array('class' => 'form-control','size' => '100x3')) }}
				    </div>    
	            </div>	
	            <div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name',  trans('label.male')) }}
						{{ Form::text('male', $job[0]->male, array('class' => 'form-control')) }}
			        </div>    
	            </div>	
	            <div class="col-sm-4">
		            <div class="form-group">	
		           	 	{{ Form::label('name', trans('label.female')) }}
		            	{{ Form::text('female', $job[0]->female, array('class' => 'form-control')) }}
            		</div>
            	</div>
				<div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', trans('label.both')) }}
						{{ Form::text('unisex', $job[0]->unisex, array('class' => 'form-control')) }}
					</div>
				</div>	
				<div class="col-sm-4">
					<div class="form-group">			
						{{ Form::checkbox('single',1, $job[0]->single == 1 ? true : false) }} {{ trans('label.single') }}
				    </div>    
	            </div>
	            <!--
	            <div class="col-sm-4">
					<div class="form-group">						
						{{ Form::checkbox('graduate',1, $job[0]->graduate== 1 ?true:false) }} {{ trans('label.graduate') }}
				    </div>    
	            </div>-->
	            <div class="col-sm-4">
					<div class="form-group">						
						{{ Form::checkbox('accomodation',1, $job[0]->accomodation == 1 ?true:false) }} {{ trans('label.accomodatin') }}
				    </div>    
	            </div>
	            <div class="col-sm-4">
					<div class="form-group">						
						{{ Form::checkbox('food',1, $job[0]->food_supply == 1 ?true:false) }} {{ trans('label.food') }}
				    </div>    
	            </div>
	            <div class="col-sm-6">
					<div class="form-group">						
						{{ Form::checkbox('transportation', '1',$job[0]->ferry_supply == 1 ?true:false) }} {{ trans('label.transportation') }}		
				    </div>    
	            </div>
	            <div class="col-sm-12">
					<div class="form-group">	
						{{ Form::label('name',  trans('label.age_limit') ) }}<br>
				        <div class="col-sm-6">{{ Form::text('min_age', $job[0]->min_age, array('id'=>'min_age','class' => 'form-control','placeholder'=>'Minimun Age')) }}</div>
				        <div class="col-sm-6">{{ Form::text('max_age', $job[0]->max_age, array('id'=>'max_age','class' => 'form-control','placeholder'=>'Maximum Age')) }}</div>
				    </div>     
	            </div>
	            <div class="col-sm-12">
					<div class="form-group">	<br>
						{{ Form::label('name', trans('label.requirement')) }} 
				        {{ Form::textarea('requirement', $job[0]->requirement, array('class' => 'form-control','size' => '100x3')) }}
				    </div>    
	            </div>	
	            <!--
	            <div class="col-sm-12" style=' margin:5px 0 10px 0; border-radius:5px;'>
					<div class="form-group">	
						{{ Form::checkbox('same_loc', '0',false, array('id'=>'same_loc')) }} {{  trans('label.require_location') }}<br>
						<div class="col-sm-6">						
						{{ Form::label('name', trans('label.city')) }}
				        {{ Form::select('city', $city , $job[0]->city_id , array('class' => 'form-control','id'=>'ddlCompanyCity'))}}
			        	</div>
			        	<div class="col-sm-6">
			        	{{ Form::label('name', trans('label.township')) }} 
				        <div id='tsp_div'></div>	
						</div>
				        <div class="col-sm-12">{{ Form::text('contact_info', $job[0]->contact_info, array('class' => 'form-control','placeholder'=>'Address & Phone Number')) }}</div>
				    </div>    
	            </div>-->
	            <div class="col-sm-6">
					<div class="form-group">	
					Set Open Date
				        {{ Form::date('open_date', $job[0]->open_date, array('class' => 'form-control','id'=>'open_date')) }}				        
						
				    </div>    
	            </div>	
	            <div class="col-sm-6">
					<div class="form-group">
					Set Close Date
				        {{ Form::date('close_date', $job[0]->close_date, array('class' => 'form-control','id'=>'close_date'))}} 
				    </div>    
	            </div>	
	            
	            <!-- <div class="col-sm-4">
					<div class="form-group">	
						{{ Form::label('name', 'Country') }}
				        {{ Form::select('country', $country , $job[0]->country_id,array('class' => 'form-control')) }}
				    </div>    
	            </div>	 -->
	           	
	            <div class="col-sm-12">
					<div class="form-group">	
						@if(Auth::user()->user_type != 2) 
    						<center>{{ Form::submit('Update!', array('class' => 'btn btn-primary')) }}</center>
						@endif
					</div>    
	            </div>
	            {{ Form::close() }}
		</div>
</div>
@endsection
@extends('layouts.footer')