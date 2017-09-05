@extends('layouts.innernavigate')
@section('title', 'Company')
@section('content')
<div class="col-sm-9"> 
	<h2>{{ trans('label.company_edit') }}</h2>
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
    	@php $url = Session::get('lang').'/company/'.encrypt($company[0]->id) @endphp 
		{{ Form::open(array('url' => $url,'route'=>array('company',$company[0]->id),'role'=>'form','files'=>'true','method' => 'PUT')) }}		
		{{ csrf_field() }}
		<div class="col-sm-6">
			<div class="form-group">	
				{{ Form::label('name', trans('label.name')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			    {{ Form::text('company_name', $company[0]->company_name, array('class' => 'form-control','required' => 'required')) }}
			</div>    
        </div>
        <div class="col-sm-6">
			<div class="form-group">	
				{{ Form::label('name', 'Business Type') }} <span style='color:red; font-size: 20px; font-weight: bold;'></span>
         		{{ Form::select('job_industry',$job_industry, $company[0]->job_industry_id ,array('class' => 'form-control','id'=>'ddlJobIndustry'))}}
         	</div>    
       </div>  
       <div class="col-sm-6">
			<div class="form-group">	
				{{ Form::label('name', trans('label.mobile_no')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
		        {{ Form::text('mobile_no', $company[0]->mobile_no, array('class' => 'form-control','required' => 'required')) }}
		    </div>    
      </div>
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', 'Company Email') }} <span style='color:red; font-size: 20px; font-weight: bold;'></span>
			        {{ Form::email('email', $company[0]->email, array('class' => 'form-control')) }}
			    </div>    
            </div> 
             
        <div class="col-sm-6">
                <div class="form-group">
                	{{ Form::label('name', trans('label.city')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::select('city', $city , $company[0]->city_id,array('class' => 'form-control','id'=>'ddlCompanyCity'))}}
                </div>
            </div>   
			<div class="col-sm-6">
				<div class="form-group">
	               	{{ Form::label('name',trans('label.township')) }}  <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
	               	{{ Form::hidden('hd_company_tsp_id',$company[0]->township_id,array('id'=>'hd_company_tsp_id')) }} 
	              	<div id='tsp_div'></div>
		        </div>
	        </div>         
        <div class="col-sm-12">
			<div class="form-group">	
				{{ Form::label('name', trans('label.address')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
		        {{ Form::text('address',  $company[0]->address, array('class' => 'form-control','required' => 'required')) }}
		    </div>    
        </div> 
        <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', 'Primary Contact Person') }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::text('primary_contact_person', $company[0]->primary_contact_person, array('class' => 'form-control','required' => 'required')) }}
			    </div>    
            </div> 
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', trans('label.mobile_no')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
			        {{ Form::text('primary_mobile', $company[0]->primary_mobile, array('class' => 'form-control','required' => 'required')) }}
			    </div>    
            </div>
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', 'Secondary Contact Person') }} 
			        {{ Form::text('secondary_contact_person', $company[0]->secondary_contact_person, array('class' => 'form-control')) }}
			    </div>    
            </div> 
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', trans('label.mobile_no')) }} 
			        {{ Form::text('secondary_mobile', $company[0]->secondary_mobile, array('class' => 'form-control')) }}
			    </div>    
            </div>
         
            <div class="col-sm-6">
			<div class="form-group">	
				{{ Form::label('name', 'Logo') }}
        		{{ Form::file('logo', array('class' => 'form-control')) }}
        	</div>    
        	</div> 
        
            <div class="col-sm-6">
				<div class="form-group">	
					{{ Form::label('name', 'Website') }}
			        {{ Form::text('website', $company[0]->website, array('class' => 'form-control')) }}
			    </div>    
            </div>
            
            <div class="col-sm-12">
				<div class="form-group">	
					{{ Form::label('name', 'Description') }}
			        {{ Form::textarea('description', $company[0]->description, array('class' => 'form-control')) }}
			     </div>    
            </div>
            
            <div class="col-sm-12">
				<div class="form-group">	
					<center>{{ Form::submit('Update', array('class' => 'btn btn-primary')) }}</center>
					{{ Form::close() }}
				</div>    
            </div>
</div>
@endsection
@extends('layouts.footer')