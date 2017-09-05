@extends('layouts.innernavigate')
@section('title', 'Browse CV')
@section('content')  
<div class="col-sm-9">	
			<h2>{{ trans('label.browse_cv') }}</h2>		
			<div class='row'>
					<div class="col-sm-2" ></div>
					<div class="col-sm-6" >
					        {{ Form::text('search', Input::old('search'), array('id'=>'txtbrowsecv', 'placeholder'=>'Type Here To Search', 'class'=>'form-control')) }}
					</div>
					
					<div class="col-sm-2" style='margin-top:5px;' >
						<a class="btn btn-primary" id="btnBrowseCV">
						<span class="more">{{ trans('label.search') }}</span>
						</a>
					</div>
					
				</div>	
			<div class='row'>	
  		  	<table id="tblBrowseCVList" class="tblList2 table table-responsive">
    		<thead>
        		<tr><th></th><th></th></tr>
        	</thead>
        	<tbody> 
        	@if (count($applicant)>0)         
                @foreach ($applicant as $js)
	    	    <tr>
	    	     <div class='row'>
	            	<td style='border-bottom:1px solid #19fb0d; padding:10px;'>
	            		@if(Auth::user()->user_role == 1)
			            	<a target="blank" href="{{ URL::to(Session::get('lang').'/applicant/' . encrypt($js->user_id) . '/edit') }}">
		              	@else
		            		<a target="blank" href="{{ URL::to(Session::get('lang').'/applicant/' . encrypt($js->id) ) }}">
		              	@endif  
		              	<div id='result'></div>
	              		<div class='row'><div class='col-md-2'>
		            	  	@if($js->photo != '')
					 	@php $img = URL::to('/')."/uploads/resume-photo/". $js->photo; @endphp						 		
 					@else
 						@php $img = URL::to('/')."/uploads/resume-photo/person.jpg" @endphp
				 	@endif
				 	<img src="{{ $img }}" style='width:80px; height:80px; padding:0px 2px 5px 0px; border-radius:10%;'>
			 		</div>
			 		<div class='col-md-10'>
	                		<h5><span style='font-weight:bold;'>{{ $js->name }}</span></h5>
	                		<span style='margin-top:5px; color:#04991d;'>{{ $js->desired_position}}</span>              	
		              		<div class='job-location'>Location: {{ $js->township }} - {{ $js->city }} </div>
		              		<i class="fa fa-phone-square"></i> {{ $js->mobile_no }} 
		              		</div>
		              		</div>
	            		</a>
              		</td>	
              		<td style='border-bottom:1px solid #19fb0d; padding:10px;'>@if(Auth::user()->user_role == 1) Registered with {{ $js->telephone_no }} @endif</td>  
              		</div>            		
	            </tr> 
	            @endforeach
	            @endif
                </tbody>
            </table>
            </div>
		</div>
@endsection
@extends('layouts.footer')