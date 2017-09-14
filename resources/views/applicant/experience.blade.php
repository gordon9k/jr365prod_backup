@extends('layouts.innernavigate')
@section('title', 'Experience')
@section('content')
<div class="col-sm-9">
			<h2>{{ trans('label.experience') }}</h2>
			<div class="row text-center">
	@if(Session::has('flash_message'))
	    <div class="success_message">{{ Session::get('flash_message') }}</div>
	@endif
	@if(Session::has('duplicate_message'))
		<div class="warning_message">{{ Session::get('duplicate_message') }}</div>
	@endif	
</div>
				<div class="row">
					@php $url = Session::get('lang').'/experience' @endphp 	
					{{ Form::open(array('url' => $url,'route'=>'experience.store','role'=>'form')) }}
					<div class="col-sm-12">
						<div class="form-group">	
							{{ Form::label('name', 'Company & Rank') }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
	        				{{ Form::text('organization', Input::old('organization'),array('id'=>'organization','class' => 'form-control','required'=>'required')) }}
					    </div>  
					</div>
					<div class="col-sm-12">
					    <div class="form-group">
					        {{ Form::label('name', 'Job Scope') }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
	        				{{ Form::textarea('rank', Input::old('rank'),array('id'=>'rank','class' => 'form-control','required'=>'required')) }}
				        </div>
				  	</div>
				  	<div class="col-sm-6">       
				        <div class="form-group">
				        	{{ Form::label('name', trans('label.sdate')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
	     					{{ Form::date('start_date',Input::old('start_date'), array('class' => 'form-control datepicker', 'required'=>'required')) }}
						</div> 
				    </div>
				    <div class="col-sm-6">       
				        <div class="form-group">
				        	{{ Form::label('name', trans('label.edate')) }} <span style='color:red; font-size: 20px; font-weight: bold;'></span>
					        {{ Form::date('end_date', Input::old('end_date'),array('class' => 'form-control datepicker')) }}
					    </div> 
				    </div>							    
				    <div class="col-sm-12 text-center">
						<div class="form-group">
							{{ Form::submit('Save', array('class' => 'btn btn-primary green')) }}{{ Form::close() }}
			            </div>
				    </div>	 
	            </div>
				<table id="tblExp" class="tblList table table-responsive"  style='border-top:2px solid #82e563;'> 
	                <thead>
		                <tr>
		                  <th></th>
		                  <th>{{ trans('label.company_list') }}  & {{ trans('label.position') }}</th>
		                  <th>{{ trans('label.sdate') }}</th>
		                  <th>{{ trans('label.edate') }}</th>		                  
		                </tr>
		                </thead>
		                <tbody>		                
		                @if(sizeof($experience) > 0)
		                @foreach ($experience as $exp)      
			    	    <tr>
			    	    	<td>
			                	{{ Form::open(['method' => 'DELETE', 
											    'route' => ['experience.destroy', encrypt($exp->id)], 
								    			'id' => 'form-delete-record-' . $exp->id,
								    			'style'=>'margin:0px;']) }}						    
								        <a href=""  class="deleteRecord" data-toggle="modal"
		                        		data-id="{{$exp->id}}" data-product_token="{{ csrf_token() }}"
		                        		data-product_name="{{ $exp->organization }}" data-product_destroy_route="{{ route('experience.destroy', encrypt($exp->id)) }}">
		                    			<i class='fa fa-remove'></i></a>			    
								        {{ Form::close() }} 					
		              		</td>
			              	<td>{{ $exp->organization }}</td>
			              	<td>{{ $exp->start_date }}</td>	
			              	<td>{{ $exp->end_date }}</td>   
			            </tr>  
			            @endforeach
			            @endif
		              </tbody>
	            </table>
		</div>
@endsection
@extends('layouts.footer')