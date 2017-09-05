@extends('layouts.navigate')
@section('title', 'Job')
<style>
tbody {font-size:14px;}
.select-style {
    padding: 0;
    margin: 0px;
    border: 1px solid #ccc;
    width: 220px;
    border-radius: 3px;
    overflow: hidden;
    background-color: #fff;
    background: #fff url("http://www.scottgood.com/jsg/blog.nsf/images/arrowdown.gif") no-repeat 90% 50%;
}
.select-style select {
    padding: 5px 8px;
    width: 130%;
    border: none;
    box-shadow: none;
    background-color: transparent;
    background-image: none;
    -webkit-appearance: none;
       -moz-appearance: none;
            appearance: none;
}
.select-style select:focus {
    outline: none;
}
#title{
	font-size:28px; color:blue; border-bottom:2px solid #c5c5c5;
}
#company{
	font-size:18px;  color:#DBC9C1; 
}
#open_date{
	font-size:14px;  color:#8e8e8e; 
}
#summary{
	font-size:14px; font-weight:bold;  margin:10px;
}
#description{
	font-size:14px;  margin:20px 10px;
}
#requirement{
	font-size:14px; margin:20px 10px;
}
#apply{
	font-size:12px; margin:20px 10px; border-top:2px solid #959595; padding-top:10px;
}
</style>
@section('content')    
    <div class="col-xs-12">
		<div class="box box-primary">
	    <div class="box-header with-border">
	     	<h3 class="box-title"></h3>
      	</div> 
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
        <div class="box-body" style='width:60%; background-color:#ffffff;padding:20px;'>
        {{ Form::open(array('url' => 'application','route'=>'application.store','role'=>'form')) }}
    	{{ Form::hidden('job_id', $job[0]->id) }} 
    	<div id='title'>{!! $job[0]->job_title !!}<br><span id="company">{!! $job[0]->company_name !!} </span><span id='open_date'>- posted on {{ $job[0]->open_date }}</span></div>
        <div id='summary'>{!! $job[0]->summary !!}</div>
        @if($job[0]->requirement != '')
        <div id='requirement'>
        	<span style="color:blue; font-size:16px;">Requirements: </span><br>
        	<span style="font-size:14px;">{!! $job[0]->requirement !!}</span>
        </div>
        @endif
        @if($job[0]->description != '')
        <div id='description'>
        	<span style="color:blue; font-size:16px;">Responsibility: </span><br>
        	<span style="font-size:14px;">{!! $job[0]->description !!}</span>
        </div>
        @endif
        <div id='apply'>{!! $job[0]->contact_info !!}</div>
        {{ Form::submit('Apply!', array('class' => 'btn btn-primary')) }}
        </div>  
    </div>
</div>
@endsection


