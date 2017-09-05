@extends('layouts.app')
@section('innernavigate')

@php $lang = Session::get('lang') @endphp
@if(Auth::check())
	@if(Auth::user()->user_role == 1)
		<div class="panel-group" id="admin">
			<div class="panel panel-default">				
				<div class="panel-heading">
					<a href="{{ url($lang.'/jobcategory') }}"><b>Basic Settings</b></a>
				</div>
				<div class="panel-heading">
					<a href="{{ url($lang.'/company') }}"><b>{{ trans('label.company_list') }}</b></a>
				</div>
				<!-- <div class="panel-heading">
					<a href="{{ url($lang.'/employer') }}"><b>{{ trans('label.employer_list') }}</b></a>
				</div> -->
				<div class="panel-heading">
					<a href="{{ url($lang.'/applicant') }}"><b>{{ trans('label.applicant_list') }}</b></a>
				</div>
				<div class="panel-heading">
					<a href="{{ url($lang.'/job') }}"><b>{{ trans('label.job_list') }}</b></a>
				</div>
			</div>
		</div>
	@elseif(Auth::user()->user_role == '2')
		<div class="panel-group" id="admin">
			<div class="panel panel-default">
				<div class="panel-heading">
					<a href="{{ url($lang.'/dashboard') }}"><b>Generate Key</b></a>
				</div>
				<div class="panel-heading">
					<a href="{{ url($lang.'/company') }}"><b>{{ trans('label.company_list') }}</b></a>
				</div>
				<div class="panel-heading">
					<a href="{{ url($lang.'/job') }}"><b>{{ trans('label.job_list') }}</b></a>
				</div>
			</div>
		</div>
	@elseif(Auth::user()->user_type == '1')
		<div class="panel-group" id="admin">
			<div class="panel panel-default">
				<div class="panel-heading">
					<a href="{{ url($lang.'/employer/'.encrypt(Auth::user()->id).'/edit') }}"><b>{{ trans('label.employer_edit') }}</b></a>
				</div>
				<div class="panel-heading">
					<a href="{{ url($lang.'/dashboard') }}"><b>{{ trans('label.employer_dashboard') }}</b></a>
				</div> 
				<div class="panel-heading">
					<a href="{{ url($lang.'/changepassword') }}"><b>{{ trans('label.change_password') }}</b></a>
				</div>
				<div class="panel-heading">
					<a href="{{ url($lang.'/jobs/create') }}"><b>{{ trans('label.post_job') }}</b></a>
				</div>
				<div class="panel-heading">
					<a href="{{ url($lang.'/browse_cv') }}"><b>{{ trans('label.browse_cv')}}</b></a>
				</div>
			</div>
		</div>	
    @else
    	<div class="panel-group" id="accordion">
    	<div class="panel panel-default">
			<div class="panel-heading">				
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
					<b>Profile</b>
					<i class="indicator glyphicon glyphicon-chevron-up pull-right"></i>
				</a>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in">
				<div class="panel-body">
				<ul class="nav">
					<li><a href="{{ url($lang.'/applicant/'.encrypt(Auth::user()->id).'/edit') }}"><b style='color:#333;' >{{ trans('label.applicant_edit') }}</b></a></li>
					<li><a href="{{ url($lang.'/education') }}"><b style='color:#888;' >{{ trans('label.education') }}</b></a></li>
					<li><a href="{{ url($lang.'/qualification') }}"><b style='color:#888;' >{{ trans('label.qualification') }}</b></a></li>
					<li><a href="{{ url($lang.'/skill') }}"><b style='color:#888;' >{{ trans('label.skill') }}</b></a></li>
					<li><a href="{{ url($lang.'/experience') }}"><b style='color:#888;' >{{ trans('label.experience') }}</b></a></li>
					<!--<li><a href="{{ url($lang.'/refree') }}"><b>{{ trans('label.refree') }}</b></a></li>-->
				</ul>
				</div>
			</div>
			
			<div class="panel-heading">
				<a href="{{ url($lang.'/application') }}"><b>{{ trans('label.employee_dashboard') }}</b></a>
			</div> 
			<div class="panel-heading">				
				<a href="{{ url($lang.'/changepassword') }}"><b>{{ trans('label.change_password') }}</b></a>
			</div>
			<div class="panel-heading">				
				<a href="{{ url($lang.'/relatedjob') }}"><b>{{ trans('label.related_job') }}</b></a>				
			</div>
			
			</div>
			</div>   	
   	@endif 
   	
@endif  
<!--
			      <div style='padding-top:0px;margin-left:300px;'>
			      <a href="{{ url('language/en') }}"><img id='en' src="../../../engFlag.png" style='margin-left:10px;' width='20px' height='15px'></a>
			      <a href="{{ url('language/mm') }}"><img id='mm' src="../../../mmFlag.png" style='margin-left:10px;' width='20px' height='15px'></a>
			      </div> -->
			      <!-- 
			      <div style='padding-top:0px;margin-left:300px;'>js
			      <a onclick="changetoen();"><img id='en' src="../../../engFlag.png" style='margin-left:10px;' width='20px' height='15px' ></a>
			      <a onclick="changetomm();"><img id='mm' src="../../../mmFlag.png" style='margin-left:10px;' width='20px' height='15px' ></a>
			      </div>  
               </div> 
            </nav>
</div>-->
@endsection