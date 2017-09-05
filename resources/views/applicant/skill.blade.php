@extends('layouts.innernavigate')
@section('title', 'Skill')
@section('content')
<div class="col-sm-9">
			<h2>{{ trans('label.skill') }}</h2>	
				<div class="row">
					@php $url = Session::get('lang').'/skill' @endphp 	
					{{ Form::open(array('url' => $url,'route'=>'skill.store','role'=>'form')) }}
					<div class="col-sm-4">
						<div class="form-group">	
							{{ Form::label('name', trans('label.language')) }} <span style='color:red; font-size: 20px; font-weight: bold;'>*</span>
	        				{{ Form::text('language','',array('id'=>'language','class' => 'form-control','required'=>'required')) }}
				    	 </div>  
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							{{ Form::label('name', trans('label.spoken')) }}<br>
							<label style="margin-left:10px;">{{ Form::radio('slevel', 'Excellent', 1, array('class'=>'with-gap')) }}Excellent </label>
							<br><label style="margin-left:10px;">{{ Form::radio('slevel', 'Good', 2, array('class'=>'with-gap')) }} Good</label>
							<br><label style="margin-left:10px;">{{ Form::radio('slevel', 'Fair', 3, array('class'=>'with-gap')) }} Fair</label>
					    </div>
					</div>
				  	<div class="col-sm-4">
						<div class="form-group">
							{{ Form::label('name', trans('label.write')) }}<br>
							<label style="margin-left:10px;">{{ Form::radio('wlevel', 'Excellent', 1, array('class'=>'with-gap')) }}Excellent </label>
							<br><label style="margin-left:10px;">{{ Form::radio('wlevel', 'Good', 2, array('class'=>'with-gap')) }} Good</label>
							<br><label style="margin-left:10px;">{{ Form::radio('wlevel', 'Fair', 3, array('class'=>'with-gap')) }} Fair</label>
					    </div> 
					</div>
				    <div class="col-sm-12 text-center">
						<div class="form-group">
							{{ Form::submit('Save', array('class' => 'btn btn-primary green')) }}{{ Form::close() }}
			            </div>
				    </div>	 
	            </div>
				<table id="tblSkill" class="tblList table table-responsive" style='border-top:2px solid #82e563;'> 
			    	<thead>
			       		<tr>
			       			<th></th>
			                <th>{{ trans('label.language') }}</th>
			                <th>{{ trans('label.spoken') }}</th>
			                <th>{{ trans('label.write') }}</th>              
			        	</tr>
			        </thead>
			        <tbody>
			            @if(sizeof($skill) > 0)
			                @foreach ($skill as $sk)      
				    	    <tr>
				    	    	<td>
				                	{{ Form::open(['method' => 'DELETE', 
												    'route' => ['skill.destroy', encrypt($sk->id)], 
									    			'id' => 'form-delete-record-' . $sk->id,
									    			'style'=>'margin:0px;']) }}						    
									        <a href=""  class="deleteRecord" data-toggle="modal"
			                        		data-id="{{$sk->id}}" data-product_token="{{ csrf_token() }}"
			                        		data-product_name="{{ $sk->type }}" data-product_destroy_route="{{ route('skill.destroy', encrypt($sk->id)) }}">
			                    		<i class='fa fa-remove'></i></a>				    
									        {{ Form::close() }} 					
			              		</td>
				              	<td>{{ $sk->language }}</td>
				              	<td>{{ $sk->spoken_level }}</rd>	
				              	<td>{{ $sk->written_level }}</rd>				              	
				            </tr>  
				            @endforeach
				            @endif
						</tbody>
					</table>
			</div>
@endsection
@extends('layouts.footer')