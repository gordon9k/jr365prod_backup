@extends('layouts.innernavigate')
@section('title', 'Recommendation')
@section('content')
<div class="col-sm-9">
			<h2>{{ trans('label.refree') }}</h2>
				<div class="row">
    				@php $url = Session::get('lang').'/refree' @endphp 	
					{{ Form::open(array('url' => $url,'route'=>'refree.store','role'=>'form')) }}
					<div class="col-sm-6">
						<div class="form-group">	
							{{ Form::label('name', trans('label.name')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
		        			{{ Form::text('first_name',Input::old('first_name'),array('id'=>'refree_first_name','class' => 'form-control','required'=>'required')) }}
					    </div>  
					</div>
					<div class="col-sm-6">
						<div class="form-group">	
							{{ Form::label('name', trans('label.company_list')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
		        			{{ Form::text('organization', Input::old('organization'),array('id'=>'refree_company','class' => 'form-control','required'=>'required')) }}
					    </div>  
					</div>
					<div class="col-sm-6">
						<div class="form-group">	
							{{ Form::label('name', trans('label.position')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
		        			{{ Form::text('rank', Input::old('rank'),array('id'=>'refree_rank','class' => 'form-control','required'=>'required')) }}
					    </div>  
					</div>
					<div class="col-sm-6">
						<div class="form-group">	
							{{ Form::label('name', 'Email') }} <span style='color:red; font-size: 20px; font-weight: bold;'></span>
		     				{{ Form::email('email',Input::old('email'), array('id'=>'refree_email','class' => 'form-control')) }}
					    </div>  
					</div>
					<div class="col-sm-6">
						<div class="form-group">	
							{{ Form::label('name', trans('label.mobile_no')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
		        			{{ Form::text('mobile_no',Input::old('mobile_no'),array('id'=>'refree_mobile_no','class' => 'form-control','required'=>'required')) }}
					    </div>  
					</div>
					<div class="col-sm-12 text-center">
						<div class="form-group">
							{{ Form::submit('Save', array('class' => 'btn btn-primary green')) }}{{ Form::close() }}
			            </div>
				    </div>
		    	</div>
				<table id="tblRefree" class="tblList table table-responsive" style='border-top:2px solid #82e563;'> 
				<thead>
	                <tr>
	                  <th></th>
	                  <th>{{ trans('label.name') }}</th>
	                  <th>{{ trans('label.company_list') }}</th>
	                  <th>{{ trans('label.position') }}</th>
	                  <th>Email</th>
	                  <th>{{ trans('label.mobile_no') }}</th>		                  
	                </tr>
                </thead>
                <tbody>		                
	                @if(sizeof($refree) > 0)
	                @foreach ($refree as $ref)      
		    	    <tr>
		    	    	<td>
		                	{{ Form::open(['method' => 'DELETE', 
								    'route' => ['refree.destroy', encrypt($ref->id)], 
					    			'id' => 'form-delete-record-' . $ref->id,
					    			'style'=>'margin:0px;']) }}						    
					        <a href=""  class="deleteRecord" data-toggle="modal"
                        		data-id="{{$ref->id}}" data-product_token="{{ csrf_token() }}"
                        		data-product_name="{{ $ref->organization }}" data-product_destroy_route="{{ route('refree.destroy', encrypt($ref->id)) }}">
                    			<i class='fa fa-remove'></i></a>				    
					        {{ Form::close() }} 					
	              		</td>
		              	<td>{{ $ref->first_name }}</td>
		              	<td>{{ $ref->organization }}</td>
		              	<td>{{ $ref->rank }}</td>	
		              	<td>{{ $ref->mobile_no }}</td>     
		              	<td>{{ $ref->email }}</td>  
		            </tr>  
		            @endforeach
		            @endif
              	</tbody>
            	</table>
		</div>
@endsection
@extends('layouts.footer')