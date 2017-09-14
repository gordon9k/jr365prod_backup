@extends('layouts.innernavigate')
@section('title', 'Job Seeker')
@section('content')
	<div class="col-sm-9">	
	<!-- <div role="tabpanel"> -->	
	@php $url = Session::get('lang').'/applicant/'.encrypt($applicant[0]->id) @endphp  
	{{ Form::open(array('url' => $url,'route'=>array('applicant',$applicant[0]->id),'role'=>'form','files'=>'true', 'method' => 'PUT')) }}	
		{{ csrf_field() }}
		<h2>{{ trans('label.applicant_edit') }}</h2>
		<div class="row text-center">
			@if(Session::has('flash_message'))
			    <div class="success_message">{{ Session::get('flash_message') }}</div>
			@endif
			@if(Session::has('duplicate_message'))
				<div class="warning_message">{{ Session::get('duplicate_message') }}</div>
			@endif	
		</div>	
		<div class="row" style='margin-top:10px;'>						
			<div class="col-sm-6">
				<div class="form-group">	
					@php $male=false; $female=false; 
					if($applicant[0]->gender == 'male') $male=true; else $female=true @endphp
	                {{ Form::radio('gender', 'male', $male, array('class'=>'with-gap')) }} <label>Mr.</label>
					{{ Form::radio('gender', 'female', $female, array('class'=>'with-gap')) }} <label>Mrs.</label>
            	</div>    
            </div>
            <div class="col-sm-6">			
		        <div class="form-group">	        	
		        	{{ Form::label('name', 'Photo') }}  (jpg/jpeg/png)
         			{{ Form::file('resume-photo') }}					        		
				</div>		
            </div>
			<div class="col-sm-6">
				<div class="form-group">
                	{{ Form::label('name',  trans('label.name') ) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::text('name', $applicant[0]->name, array('class' => 'form-control','required'=>'required')) }}
            	</div>
			</div>
            <div class="col-sm-6">
            	<div class="form-group">
                	{{ Form::label('name', trans('label.father_name') ) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::text('father_name', $applicant[0]->father_name, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                	{{ Form::label('name', trans('label.dob')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span> [mm-dd-yyyy]
					{{ Form::date('dob',$applicant[0]->date_of_birth, array('class' => 'form-control datepicker', 'required'=>'required')) }}
                </div>
            </div>
            <div class="col-sm-6">
				<div class="form-group">
	                {{ Form::label('name', trans('label.nrc')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::text('nrc', $applicant[0]->nrc_no,array('class' => 'form-control','required'=>'required'))}}
	        	</div>
           	</div>	           	
           	<div class="col-sm-6">
            	<div class="form-group">
              		{{ Form::label('name', trans('label.mobile_no')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::text('mobile_no', $applicant[0]->mobile_no, array('class' => 'form-control','required'=>'required')) }}
		        </div>
            </div>
            <div class="col-sm-6">
            	<div class="form-group">
              		{{ Form::label('name', trans('label.email')) }} <span style='color:red; font-size: 20px; font-weight: bold;'></span>
			        {{ Form::email('email', $applicant[0]->email, array('class' => 'form-control')) }}
		        </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                	{{ Form::label('name', trans('label.city')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::select('city', $city , $applicant[0]->city_id,array('class' => 'form-control','id'=>'ddlEmployeeCity'))}}
                </div>
            </div>   
			<div class="col-sm-6">
				<div class="form-group">
	               	{{ Form::label('name',trans('label.township')) }}  <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
	               	{{ Form::hidden('hd_employee_tsp_id',$applicant[0]->township_id,array('id'=>'hd_employee_tsp_id')) }} 
	              	<div id='employee_tsp_div'></div>
		        </div>
	        </div> 
	        <div class="col-sm-12">
               	<div class="form-group">
                	{{ Form::label('name', trans('label.address')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::text('address', $applicant[0]->address, array('class' => 'form-control','required'=>'required')) }}
               	</div>
           	</div>           						
           	<div class="col-sm-6">
               	<div class="form-group">
    	           	{{ Form::label('name', trans('label.nationality')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::text('nationality', $applicant[0]->nationality, array('class' => 'form-control','required'=>'required')) }}
               	</div>
           	</div>            	
           	<div class="col-sm-6">
               	<div class="form-group">
    	           	{{ Form::label('name', trans('label.religion')) }} <span style='color:red; font-size: 20px; font-weight: bold;'></span>
			        {{ Form::text('religion', $applicant[0]->religion, array('class' => 'form-control')) }}
               	</div>
           	</div> 
           	<div class="col-sm-6">
				<div class="form-group">
	                {{ Form::label('name', trans('label.birthplace')) }}  <span style='color:red; font-size: 20px; font-weight: bold;'></span>
			        {{ Form::text('pob', $applicant[0]->place_of_birth,array('class' => 'form-control'))}}
	        	</div>
           	</div>	           	
           	<div class="col-sm-6">
				<div class="form-group">
	                @php $status = ['Single' => 'Single', 'Married' => 'Married'] @endphp
			        {{ Form::label('name', trans('label.marital_status')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::select('marital_status', $status, $applicant[0]->marital_status,array('class' => 'form-control','required'=>'required'))}}
	        	</div>
           	</div>
           	<div class="col-sm-6">
				<div class="form-group">
	                {{ Form::label('name', trans('label.current_position')) }} <span style='color:red; font-size: 20px; font-weight: bold;'></span>
			        {{ Form::text('current_position', $applicant[0]->current_position,array('class' => 'form-control'))}}
	        	</div>
           	</div>	
           	<div class="col-sm-6">
				<div class="form-group">
	                {{ Form::label('name', trans('label.desire_position')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
	                {{ Form::hidden('category_id','',array('class' => 'form-control','id'=>'category_id')) }}
			        {{ Form::text('desired_position', $applicant[0]->desired_position,array('class' => 'form-control','id'=>'search_text','required'=>'required'))}}
	        	</div>
           	</div>	
            <div class="col-sm-6">
            	<div class="form-group">
              		{{ Form::label('name', trans('label.salary')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::text('expected_salary', $applicant[0]->expected_salary, array('class' => 'form-control','required'=>'required')) }}
		        </div>
            </div>
            <div class="col-sm-6">					
		        <div class="form-group">		        	
		        	{{ Form::label('name', 'Attach CV') }}  (doc/docx/pdf) 
         			{{ Form::file('attach_cv') }}         					        		
				</div>						
            </div><br>
            <div class="col-sm-6">            	
                <div class="form-group">
                	{{ Form::checkbox('chkDriving',1,$applicant[0]->driving_license) }} Driving License
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                	@if($applicant[0]->cv_views == null || $applicant[0]->cv_views=='')
                		@php $show = 0 @endphp
                	@else
                		@php $show = !$applicant[0]->cv_views @endphp
                	@endif				
                	{{ Form::checkbox('cv_view',1, $show) }} Hide CV from Employer
                </div>
            </div>            
			<div class="col-sm-12 text-center">
				<div class="form-group">
			        {{ Form::submit('Update Profile', array('class' => 'btn btn-primary')) }}
					{{ Form::close() }}
			    </div>
			</div>	        	
        </div>
        </div>
	</div>
	
@endsection
@extends('layouts.footer')