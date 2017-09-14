@extends('layouts.innernavigate')
@section('title', 'Employers')
@section('content') 
		<div class="col-sm-9">
		   <h2>{{ trans('label.employer_list') }}</h2>		   
		   <table id="tblList" class="tblList table table-responsive">
		    	<thead>
		        	<tr>
		            	<th>#</th>                  
		                <th>{{ trans('label.name') }}</th>
		                <th>{{ trans('label.address') }}</th>		                
		                <th></th>
					</tr>
				</thead>
		        <tbody>
		               @php $j = 1 @endphp
		               @foreach ($employer as $emp) 
			    	   <tr>
			            	<td>
			            	  <label>
		                        {{ $j }}
		                      </label>
		                	</td>
			              	<td>{{ $emp->name }}</td>              	
			              	<td>{{ $emp->address }}, {{ $emp->township }}, {{ $emp->city }}<br> <i class="fa fa-phone-square"></i> {{ $emp->mobile_no }} <br><i class="fa fa-envelope"></i> {{ $emp->email }}</td>	              	
			              	<td><a target="blank" href="{{ URL::to(Session::get('lang').'/employer/' . encrypt($emp->user_id) .'/edit') }}"><i class="fa fa-edit"></i> Edit</a></td>
			              	<!-- <td>
			              		@if(Auth::user()->is_admin == 1)
			              			<a href="{{ URL::to(Session::get('lang').'/employer/' . $emp->id ) }}"><i class="fa fa-edit"></i>View</a>
		              			@else
		              				<a href="{{ URL::to(Session::get('lang').'/employer/' . $emp->user_id .'/edit') }}"><i class="fa fa-view"></i>Edit</a>
		              			@endif		              		
			              	</td>
			              	<td width="100px">
			              	@if(Auth::user()->is_admin != 1) 
			                	{{ Form::open(['method' => 'DELETE', 
											    'route' => ['employer.destroy', $emp->id], 
								    			'id' => 'form-delete-record-' . $emp->id,
								    			'style'=>'margin:0px;']) }}						    
								        <a href="" class="deleteRecord" data-toggle="modal" data-id="{{$emp->id}}" data_token="{{ csrf_token() }}"
		                        		data_name="{{ $emp->name }}" data_destroy_route="{{ route('employer.destroy', $emp->id) }}">
		                  				<i class="fa fa-remove">delete</i>Delete</a>			    
								{{ Form::close() }} 
							@endif					
		              		</td> -->	
			            </tr>
			            @php $j++ @endphp	  
			            @endforeach
		                </tbody>
            	</table>
			</div>
@endsection
@extends('layouts.footer')