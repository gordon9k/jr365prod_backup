@extends('layouts.innernavigate')
@section('title', 'Shortlisted Information')
@section('content')    
    <div class="col-xs-9">
		<div class="box box-primary">
	    <div class="box-header with-border">
	     	<h3 class="box-title">Shortlisted</h3>
      	</div> 
        <div class="box-body" style='margin-left:0%; width:100%; background-color:#ffffff; padding:10px;'>
        <table id="tblList" class="tblList table responsive-table">
    		<thead>
				<tr><th>Name</th><th>Contact</th><th>Description</th></tr>
			</thead>
			<tbody>
				@if(count($shortlisted) > 0)
				@foreach ($shortlisted as $selected)
				<tr><td width="25%">
				@if($selected->photo != '' || $selected->photo != null)
				@php $img = URL::to('/')."/uploads/resume-photo/".$selected->photo; @endphp   
				<img src="{{$img}}" width="60px" height="60px">
				@endif		
				<span style='margin-left:5px; font-weight:bold;'>{{ $selected->name }}</span>
				</td>
				<td width="25%">{{ $selected->mobile_no }}<br>{{ $selected->email }}</td>
				<td>{{ $selected->description }}</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
       </div>  
    </div>
</div>
@endsection



