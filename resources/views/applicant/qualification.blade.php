@extends('layouts.innernavigate')
@section('title', 'Qualification')
@section('content') 
<div class="col-sm-9"> 
<h2>{{ trans('label.qualification') }}</h2>	
<div class="row text-center">
	@if(Session::has('flash_message'))
	    <div class="success_message">{{ Session::get('flash_message') }}</div>
	@endif
	@if(Session::has('duplicate_message'))
		<div class="warning_message">{{ Session::get('duplicate_message') }}</div>
	@endif	
</div>		
<div class="row">	
	@php $url = Session::get('lang').'/qualification' @endphp 
	{{ Form::open(array('url' => $url,'route'=>'qualification.store','role'=>'form')) }}
	<div class="col-sm-6">
		<div class="form-group">	
			{{ Form::label('name',  trans('label.center_name')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
		    {{ Form::text('center_name',Input::old('center_name'),array('id'=>'center_name','class' => 'form-control','required'=>'required')) }}
		</div>  
	</div>
	<div class="col-sm-6">
	    <div class="form-group">
	        {{ Form::label('name',  trans('label.course')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
	        {{ Form::text('course',Input::old('course'), array('id'=>'course','class' => 'form-control','required'=>'required'))}}
        </div>
  	</div>
	<div class="col-sm-6">       
		<div class="form-group">
			{{ Form::label('name', trans('label.sdate')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
        	<!-- <input id="year" type="text" class="form-control" name="year" value="{{ old('year') }}" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>  -->
			{{ Form::date('sdate',Input::old('sdate'), array('class' => 'form-control datepicker')) }}
		</div> 
	</div>
	<div class="col-sm-6">       
		<div class="form-group">
			{{ Form::label('name', trans('label.edate')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
        	<!-- <input id="year" type="text" class="form-control" name="year" value="{{ old('year') }}" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>  -->
			{{ Form::date('edate',Input::old('edate'), array('class' => 'form-control datepicker')) }}
		</div> 
	</div>
	<div class="col-sm-12 text-center">
		<div class="form-group">
			{{ Form::submit('Save', array('class' => 'btn btn-primary green')) }}{{ Form::close() }}
        </div>
	</div>	 
</div>
<table id="tblQua" class="tblList table table-responsive"  style='border-top:2px solid #82e563;'> 
	<thead>
   		<tr>
        	<th></th>
        	<th>{{ trans('label.center_name') }}</th>
      		<th>{{ trans('label.course') }}</th>
      		<th>{{ trans('label.sdate')}}</th>   
      		<th>{{ trans('label.edate')}}</th>                  
     	</tr>
    </thead>
    <tbody>
		@if(sizeof($qualification) > 0)
			@foreach ($qualification as $qua)      
		    	<tr>
		    		<td>
		            	{{ Form::open(['method' => 'DELETE', 
								    'route' => ['qualification.destroy', encrypt($qua->id)], 
					    			'id' => 'form-delete-record-' . $qua->id,
					    			'style'=>'margin:0px;']) }}						    
							        <a href=""  class="deleteRecord" data-toggle="modal"
	                        		data-id="{{$qua->id}}" data-product_token="{{ csrf_token() }}"
	                        		data-product_name="{{ $qua->degree }}" data-product_destroy_route="{{ route('qualification.destroy', encrypt($qua->id)) }}">
	                    			<i class='fa fa-remove'></i></a>			    
				        {{ Form::close() }} 					
              		</td>
	              	<td>{{ $qua->center_name }}</td>
	              	<td>{{ $qua->course }}</td>
	              	<td>{{ $qua->start_date }}</td>
	              	<td>{{ $qua->end_date }}</td>
	            </tr>  
	            @endforeach
            @endif
    </tbody>
 </table>
</div>
@endsection
@extends('layouts.footer')