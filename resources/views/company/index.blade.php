@extends('layouts.innernavigate')
@section('title', 'Company')
@section('content') 
<div class="col-sm-9"> 
	<h2>{{ trans('label.company_list') }}</h2>
	<div class="row" style="margin:10px;"> 
	@if(Auth::user()->user_role == 1) 
	@php $url = Session::get('lang').'/company/create' @endphp 
	<a href="{{ url($url) }}" style="float:right"><i class="fa fa-plus-square"> New Company</i></a>
	@endif
	</div>
	<table id="tblCompanyList" class="tblList1 table responsive-table">
    	<thead>
        	<tr>
                  <th></th>
                  <th></th>
                  <th></th>
            </tr>
       	</thead>
        <tbody>
                @php $j = 1 @endphp
                @if(sizeof($company) > 0)
                	@foreach($company as $com)   
                	@if($com->company_logo != '')
            			@php $img_url = URL::to('/')."/uploads/company_logo/".$com->company_logo @endphp
	            	@else
	            		@php $img_url = URL::to('/')."/uploads/company_logo/logo.jpg" @endphp
	            	@endif  
	            	@if(Auth::user()->user_role == 1) @php $phone =  $com->telephone_no @endphp 
	            	@else @php $phone = Auth::user()->telephone_no @endphp
			@endif
					
		    	    <tr>
		    	    <div class='row'>
		    	    	<td style='border-bottom:1px solid #19fb0d; padding:10px;'>
		    	    	  	<div class='col-md-2 hidden-sm hidden-xs'>                    	
			                	<img src="{{ $img_url }}" width="80" height="80" style="border:2px solid #999999; border-radius:5px;">
			               	</div>
			                <div class='col-md-8 col-sm-12 col-xs-12'><h5>{{ $com->company_name }}
		                		<b style='color:green;'> - Registerd with  {{ $phone }}</b></h5>
		              			{{ $com->address }} {{ $com->township }} {{ $com->city }}
		              			<br><i class='fa fa-phone-square'></i> {{ $com->mobile_no }}
		              			@if($com->email != '') <br><i class='fa fa-envelope'></i> {{ $com->email }} @endif
		              			<br><i class='fa fa-user'></i> 	{{ $com->primary_contact_person }} - {{ $com->primary_mobile }}
			              	</div>
		                </td>	
					
		              	<td width="80px" style='border-bottom:1px solid #19fb0d; padding:10px;'>     
		              		@if(Auth::user()->user_role == 1)        		
		              		<a href="{{ URL::to(Session::get('lang').'/company/' . encrypt($com->id) .'/edit') }}"><i class="fa fa-edit"></i> Edit</a>
	              			@endif
	              		</td>
		              	<td width="80px" style='border-bottom:1px solid #19fb0d; padding:10px;'>  
		              		@if(Auth::user()->user_role == 1) 
		                	{{ Form::open(['method' => 'DELETE', 
										    'route' => ['company.destroy', encrypt($com->id)], 
							    			'id' => 'form-delete-record-' . $com->id,
							    			'style'=>'margin:0px;']) }}						    
							        <a href="" class="deleteRecord" data-toggle="modal" data-id="{{$com->id}}" data_token="{{ csrf_token() }}"
	                        		data_name="{{ $com->company_name }}" data_destroy_route="{{ route('company.destroy', encrypt($com->id)) }}">
	                  		<i class="fa fa-remove"></i> Delete</a>
	                  		{{ Form::close() }} 
	                  		@endif				
	              		</td>
	              		</div>
              		<!-- 
              		<td>
              			@php $url = Session::get('lang').'/company/'.encrypt($com->id) @endphp 
						{{ Form::open(array('url' => $url,'route'=>array('company',$com->id),'role'=>'form','method' => 'PUT')) }}		
				      	@if ($com->is_feature == 1)	
				      		{{ Form::hidden('hfeature', 0) }} 
				      		{{ Form::submit('Feature', array('class' => 'btn btn-primary')) }}
				      	@else
				      		{{ Form::hidden('hfeature', 1) }} 
				      		{{ Form::submit('Feature', array('class' => 'btn btn-danger')) }}
				      	@endif
					    {{ Form::close() }} 
              		</td><td></td>
              		 -->
	            	</tr>
	            @php $j++ @endphp 	  
	            @endforeach
	            @endif
           </tbody>
        </table>
</div>
@endsection
@extends('layouts.footer')