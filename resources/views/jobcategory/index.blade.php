@extends('layouts.innernavigate')
@section('title', 'Basic Settings')
@section('content')    
<div class="col-sm-9">
	<div role="tabpanel">
	<h2>{{ trans('label.basic') }}</h2>
	<ul class="nav nav-tabs nav-justified" role="tablist">
		<li role="presentation" class="active"><a href="#category_tab" aria-controls="category_tab" role="tab" data-toggle="tab">{{ trans('label.jobcategory') }}</a></li>
		<li role="presentation"><a href="#township_tab" aria-controls="township_tab" role="tab" data-toggle="tab">{{ trans('label.township') }}</a></li>
		<li role="presentation"><a href="#industry_tab" aria-controls="industry_tab" role="tab" data-toggle="tab">{{ trans('label.jobindustry') }}</a></li>
	</ul>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="category_tab">
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
			<a style="float:right" class="link-cat"><i class="fa fa-plus-square"> New Category</i></a>
			<table id="tblList" class="tblList table table-responsive">
		    	<thead>
		        	<tr>
		            	<th>#</th>
		              	<th>Job Category</th>
		                <th></th>
		            </tr>
		        </thead>
		        <tbody>
		        	@php $j = 1 @endphp
		        	@if(sizeof($job_category) > 0)
		            @foreach ($job_category as $jobCat)      
			    	    <tr>
			            	<td>{{ $j }}</td>
			              	<td>{{ $jobCat->category }}</td>			              	
			              	<td>
			                	{{ Form::open(['method' => 'DELETE', 
											    'route' => ['jobcategory.destroy', encrypt($jobCat->id)], 
								    			'id' => 'form-delete-record-' . $jobCat->id,
								    			'style'=>'margin:0px;']) }}						    
								        <a href=""  class="deleteRecord" data-toggle="modal"
		                        		data-id="{{$jobCat->id}}" data-product_token="{{ csrf_token() }}"
		                        		data-product_name="{{ $jobCat->category }}" data-product_destroy_route="{{ route('jobcategory.destroy', encrypt($jobCat->id)) }}">
		                    		<i class="fa fa-remove"></i> Delete</a>	
								{{ Form::close() }} 					
		              		</td>
		              		<!-- <td><a style="float:right" class="link-categorydel"><i class="fa fa-remove"> Delete </i></a></td> -->
			            </tr>
			            @php $j++ @endphp
			            @endforeach
			            @endif
		        </tbody>
			</table>
		
			<div class="popup" id="cat">
				<div class="popup-form">
					<div class="popup-header">
						<a class="close"><i class="fa fa-remove fa-lg"></i></a>
						<h2>New Category</h2>
					</div>
					
					@php $url = Session::get('lang').'/jobcategory' @endphp  
					{{ Form::open(array('url' => $url,'route'=>'jobcategory.store','role'=>'form')) }}
						<div class="form-group">
							{{ Form::label('name', 'Name') }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
				        	{{ Form::text('name', Input::old('category'), array('class' => 'form-control','required' => 'required')) }}
						</div>
						<center>{{ Form::submit('Save', array('class' => 'btn btn-primary')) }}</center>
					{{ Form::close() }}
				</div>
			</div>
		 </div>
		 
		<div role="tabpanel" class="tab-pane" id="industry_tab">
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
			<a style="float:right" class="link-industry"><i class="fa fa-plus-square"> New Industry</i></a>
			<table id="tblList" class="tblList table table-responsive">
		    	<thead>
		        	<tr>
		            	<th>#</th>
		              	<th>Job Industry</th>
		                <th></th>
		            </tr>
		        </thead>
		        <tbody>
		        	@php $j = 1 @endphp
		        	@if(sizeof($job_industry) > 0)
		            @foreach ($job_industry as $industry)   
		            @if($industry->type == 1) @php $type = 'Company' @endphp 
		            @else @php $type = 'Non-Company' @endphp 
		            @endif    
			    	    <tr>
			            	<td>{{ $j }}</td>
			              	<td>{{ $type }} - {{ $industry->job_industry }}</td>			              	
			              	<td>
			                	{{ Form::open(['method' => 'DELETE', 
											    'route' => ['jobindustry.destroy', encrypt($industry->id)], 
								    			'id' => 'form-delete-record-' . $industry->id,
								    			'style'=>'margin:0px;']) }}						    
								        <a href=""  class="deleteRecord" data-toggle="modal"
		                        		data-id="{{$industry->id}}" data-product_token="{{ csrf_token() }}"
		                        		data-product_name="{{ $industry->job_industry }}" data-product_destroy_route="{{ route('jobindustry.destroy', encrypt($industry->id)) }}">
		                    		<i class="fa fa-remove"></i> Delete</a>	
								{{ Form::close() }} 					
		              		</td>
	              		</tr>
			            @php $j++ @endphp
			            @endforeach
			            @endif
		        </tbody>
			</table>
		
			<div class="popup" id='industry'>
				<div class="popup-form">
					<div class="popup-header">
						<a class="close"><i class="fa fa-remove fa-lg"></i></a>
						<h2>New Job Industry</h2>
					</div>					
					@php $url = Session::get('lang').'/jobindustry' @endphp  
					{{ Form::open(array('url' => $url,'route'=>'jobindustry.store','role'=>'form')) }}
						<div class="form-group">
							@php $businessType = ['1'=>'Company', '2'=>'Non-Company'] @endphp
							{{ Form::label('name', 'Type') }}
				        		{{ Form::select('ddlBType', $businessType ,  Input::old('type'),array('class' => 'form-control'))}}
			       		</div>
						<div class="form-group">
							{{ Form::label('name', 'Name') }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
				        	{{ Form::text('name', Input::old('job_industry'), array('class' => 'form-control','required' => 'required')) }}
						</div>
						<center>{{ Form::submit('Save', array('class' => 'btn btn-primary')) }}</center>
					{{ Form::close() }}
				</div>
			</div>
		 </div>
		  
	    <div role="tabpanel" class="tab-pane" id="township_tab">
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
			<a style="float:right" class="link-township"><i class="fa fa-plus-square"> New Townships</i></a>
			<table id="tblList" class="tblList table table-responsive">
		    	<thead>
		        	<tr>
		            	<th>#</th>
		              	<th>Township</th>
		              	<th>City</th>
		                <th></th>
		            </tr>
		        </thead>
		        <tbody>
		        	@php $j = 1 @endphp
		        	@if(sizeof($township) > 0)
		            @foreach ($township as $tsp)      
			    	    <tr>
			            	<td>{{ $j }}</td>
			              	<td>{{ $tsp->township }}</td>
			              	<td>{{ $tsp->city }}</td>			              	
			              	<td>
			                	{{ Form::open(['method' => 'DELETE', 
											    'route' => ['township.destroy', encrypt($tsp->id)], 
								    			'id' => 'form-delete-record-' . $tsp->id,
								    			'style'=>'margin:0px;']) }}						    
								        <a href=""  class="deleteRecord" data-toggle="modal"
		                        		data-id="{{$tsp->id}}" data-product_token="{{ csrf_token() }}"
		                        		data-product_name="{{ $tsp->township }}" data-product_destroy_route="{{ route('township.destroy', encrypt($tsp->id)) }}">
		                    		<i class="fa fa-remove"></i> Delete</a>	
								{{ Form::close() }} 					
		              		</td>
	              		</tr>
			            @php $j++ @endphp
			            @endforeach
			            @endif
		        </tbody>
			</table>
		
			<div class="popup" id="township">
				<div class="popup-form">
					<div class="popup-header">
						<a class="close"><i class="fa fa-remove fa-lg"></i></a>
						<h2>New Township</h2>
					</div>					
					@php $url = Session::get('lang').'/township' @endphp  
					{{ Form::open(array('url' => $url,'route'=>'township.store','role'=>'form')) }}
						<div class="form-group">
							{{ Form::label('name', 'City') }}
				        	{{ Form::select('ddlCity', $city ,  Input::old('id'),array('class' => 'form-control'))}}
			       		</div>
			       		<div class="form-group">
							{{ Form::label('name', 'Name') }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
				        	{{ Form::text('txtTownship', '', array('class' => 'form-control','required' => 'required')) }}
						</div>
						<center>{{ Form::submit('Save', array('class' => 'btn btn-primary')) }}</center>
					{{ Form::close() }}
				</div>
			</div>
		 </div>
 	</div>
 </div>
 </div>
@endsection
@extends('layouts.footer')