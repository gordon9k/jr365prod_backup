@extends('layouts.innernavigate')
@section('title', 'Education')
@section('content') 
<div class="col-sm-9"> 
<h2>{{ trans('label.education') }}</h2>		
<div class="row text-center">
	@if(Session::has('flash_message'))
	    <div class="success_message">{{ Session::get('flash_message') }}</div>
	@endif
	@if(Session::has('duplicate_message'))
		<div class="warning_message">{{ Session::get('duplicate_message') }}</div>
	@endif	
</div>	
<div class="row">	
	@php $url = Session::get('lang').'/education' @endphp 
	{{ Form::open(array('url' => $url,'route'=>'education.store','role'=>'form')) }}
	<div class="col-sm-6">
		<div class="form-group">	
			{{ Form::label('name',  trans('label.university')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
		    {{ Form::text('university',Input::old('university'),array('id'=>'university','class' => 'form-control','required'=>'required')) }}
		</div>  
	</div>
	<div class="col-sm-6">
	    <div class="form-group">
	        {{ Form::label('name',  trans('label.degree')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
	        {{ Form::text('degree',Input::old('degree'), array('id'=>'degree','class' => 'form-control','required'=>'required'))}}
        </div>
  	</div>
	<div class="col-sm-6">       
		<div class="form-group">
			{{ Form::label('name', trans('label.sdate')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
        	<!-- <input id="year" type="text" class="form-control" name="year" value="{{ old('year') }}" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>  -->
			{{ Form::date('sdate',Input::old('start_date'), array('class' => 'form-control datepicker')) }}
		</div> 
	</div>
	<div class="col-sm-6">       
		<div class="form-group">
			{{ Form::label('name', trans('label.edate')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
        	<!-- <input id="year" type="text" class="form-control" name="year" value="{{ old('year') }}" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>  -->
			{{ Form::date('edate',Input::old('start_date'), array('class' => 'form-control datepicker')) }}
		</div> 
	</div>
	<div class="col-sm-12 text-center">
		<div class="form-group">
			{{ Form::submit('Save', array('class' => 'btn btn-primary green')) }}{{ Form::close() }}
        </div>
	</div>	 
</div>
<table id="tblEdu" class="tblList table table-responsive"  style='border-top:2px solid #82e563;'> 
	<thead>
   		<tr>
        	<th></th>
        	<th>{{ trans('label.university') }}</th>
      		<th>{{ trans('label.degree') }}</th>
      		<th>{{ trans('label.sdate')}}</th>   
      		<th>{{ trans('label.edate')}}</th>                  
     	</tr>
    </thead>
    <tbody>
		@if(sizeof($education) > 0)
			@foreach ($education as $edu)      
		    	<tr>
		    		<td>
		            	{{ Form::open(['method' => 'DELETE', 
								    'route' => ['education.destroy', encrypt($edu->id)], 
					    			'id' => 'form-delete-record-' . $edu->id,
					    			'style'=>'margin:0px;']) }}						    
							        <a href=""  class="deleteRecord" data-toggle="modal"
	                        		data-id="{{$edu->id}}" data-product_token="{{ csrf_token() }}"
	                        		data-product_name="{{ $edu->degree }}" data-product_destroy_route="{{ route('education.destroy', encrypt($edu->id)) }}">
	                    			<i class='fa fa-remove'></i></a>			    
				        {{ Form::close() }} 					
              		</td>
	              	<td>{{ $edu->university }}</td>
	              	<td>{{ $edu->degree }}</td>
	              	<td>{{ $edu->start_date }}</td>
	              	<td>{{ $edu->end_date }}</td>
	            </tr>  
	            @endforeach
            @endif
    </tbody>
 </table>
</div>
@endsection
@extends('layouts.footer')