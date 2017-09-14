@extends('layouts.navigate')
@section('title', 'Employer Information')
@section('content')    
    <div class="col-xs-12">
		<div class="box box-primary">
	    <div class="box-header with-border">
	     	<h3 class="box-title"></h3>
      	</div> 
        <div class="box-body" style='margin-left:20%; width:60%; background-color:#ffffff; padding:10px;'>
        <table class="table  table-responsive company">
        	<tr><td width='30%'>Company</td><td>
        	<?php $img = URL::to('/')."/uploads/company_logo/".$employer[0]->company_logo;?>
        	<img width=120 height=120 style="display: block;" src="<?php echo $img;?>"/>
        	{!! $employer[0]->company_name !!} 
        	</td></tr>
        	@if($employer[0]->description)<tr><td></td><td>{!! $employer[0]->description !!}</td></tr>@endif
        	<tr><td>Contact Person</td><td>{!! $employer[0]->name !!}</td></tr>
        	<tr><td>Contact No.</td><td>{!! $employer[0]->mobile_no !!}</td></tr>
        	<tr><td>Company Contact</td><td>{!! $employer[0]->company_contact !!}</td></tr>
        	<tr><td>Address</td><td>{!! $employer[0]->address !!} {!! $employer[0]->township !!} {!! $employer[0]->city !!},{!! $employer[0]->postal_code !!}{!! $employer[0]->country !!}</td></tr>
        	<tr><td>Company Email</td><td>{!! $employer[0]->company_email !!}</td></tr>
       	</table>
       	<a href="{{ url(Session::get('lang').'/employer') }}"><i class="material-icons">reply</i>Back</a>
        </div>  
    </div>
</div>
@endsection



