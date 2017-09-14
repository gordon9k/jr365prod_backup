@extends('layouts.app')
@extends('layouts.navigate')
@section('title', 'Job Seeker')
@section('content')
<div class="col-xs-12">
<div class='row' style='margin:10px;'>
    <div class="col s9" >
    <div class="panel panel-default">	
    <div class="panel-body ">
    <div style="margin-bottom: 30px;">
    	<h5 class="amber-text text-darken-2"><b>{{ trans('label.applicant_new') }}</b></h5>
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
    <div id="tabs">
	<ul class='tabs'>
		<li class='tab'><a class='green-text text-darken-2' href="#tabs-1">Personal Info</a></li>
		<li class='tab'><a class='green-text text-darken-2' href="#tabs-3">Education</a></li>
		<li class='tab'><a class='green-text text-darken-2' href="#tabs-4">Qualification</a></li>
		<li class='tab'><a class='green-text text-darken-2' href="#tabs-5">Experience</a></li>
		<li class='tab'><a class='green-text text-darken-2' href="#tabs-6">Refrees</a></li>
	</ul>
	@php $url = Session::get('lang').'/applicant' @endphp  
	{{ Form::open(array('url' => $url,'route'=>'applicant.store','role'=>'form','files'=>'true')) }}
		@if($errors->any())
			<?php $frm_class = "form-group has-error";?>
		@else
			<?php $frm_class = "form-group";?>
		@endif	
	<div id="tabs-1">
		<table class='table responsive-table'>	
			<tr>
				<td>
				 	<div class="{{ $frm_class }}">	
				    	{{ Form::radio('sex', 'male', $male, array('class'=>'form-control with-gap')) }} <label for="sex">Mr.</label>
						{{ Form::radio('sex', 'female', $female, array('class'=>'form-control with-gap')) }} <label for="sex">Ms.</label>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'First Name') }} *
				        {{ Form::text('first_name', Input::old('first_name'), array('class' => 'form-control')) }}
				    </div>
				</td>
				<td>
				    <div class="{{ $frm_class }}">
				        {{ Form::label('name', 'Last Name') }} 
				        {{ Form::text('last_name', Input::old('last_name'), array('class' => 'form-control')) }}
				    </div>
			    </td>
		    </tr>
		    <tr>
		    	<td>
				    <div class="{{ $frm_class }}">
						<?php $status = ['single' => 'Single', 'married' => 'Married'];?>
				        {{ Form::label('name', 'Marital Status') }}
				        {{ Form::select('marital_status', $status, '1',array('class' => 'form-control'))}} 
				    </div>				   
				</td>
				<td>
				    <div class="{{ $frm_class }}">
				     	{{ Form::label('name', 'Date of Birth') }} *
						{{ Form::text('dob',Input::old('dob'), array('id'=>'datepicker','class' => 'form-control')) }}
					</div>				   
				</td>
			</tr>
			<tr>
				<td>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'Mobile No') }} *
				        {{ Form::text('mobile_no', Input::old('mobile_no'), array('class' => 'form-control')) }}
				    </div>
				</td>
				<td>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'Address') }} *
				        {{ Form::textarea('address', Input::old('address'), array('class' => 'form-control','size' => '25x4')) }}
				    </div>
				</td>
			</tr>
			<tr>				
				<td>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'Township') }} *
				        {{ Form::text('township', Input::old('township'), array('class' => 'form-control')) }}
			    	</div>
				</td>
				<td>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'Postal Code') }}
				        {{ Form::text('postal_code', Input::old('postal_code'), array('class' => 'form-control')) }}
				    
				    </div>
				</td>
			</tr>		
			<tr>
				<td>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'Country') }}
				        {{ Form::select('country', $country , Input::old('id'),array('class' => 'form-control')) }}
				    </div>
				</td>
				<td>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'City') }}
				        {{ Form::select('city', $city , Input::old('id'),array('class' => 'form-control'))}}
			        </div>
				</td>
			<tr>
				<td colspan='2'>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'Upload CV') }}
				        {{ Form::file('cv_attach', array('class' => 'form-control')) }}
			        </div>
				</td>
			</tr>
		</table>
		</div>
		<div id="tabs-3">
		Education <input class='btn btn-primary green' type="button" value="Add" id="btnAddEdu" style="width:80px; padding: 5px; float:right;"/>
		<table class='table table-responsive'>
			<tr>
				<td>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'University') }}
				        {{ Form::text('university', Input::old('university'),array('id'=>'university','class' => 'form-control')) }}
				    </div>
				</td>
				<td>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'Degree') }}
				        {{ Form::text('degree', Input::old('degree'),array('id'=>'degree','class' => 'form-control'))}}
			        </div>
				</td>
				<td>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'Year') }}
				        {{ Form::text('year',Input::old('year'), array('id'=>'year','class' => 'form-control')) }}
				    </div>
				</td>		
			</tr>
			<tr>
				<td colspan='3'><input type="hidden" value="" name="hdnEdu" id="hdnEdu"/>
					<table id="tblEdu" class="table table-striped table-hover table-responsive" name='tblEdu'>
		                <thead>
		                <tr>
		                  <th>University</th>
		                  <th>Degree</th>
		                  <th>Year</th>
		                  <th></th>
		                </tr>
		                </thead>
		            </table>
				</td>
			</tr>
			</table>			
		</div>
		<div id="tabs-4">
			Qualification <input type="button" value="Add" id="btnAddSkill" style="width:80px; padding: 5px; float:right;"/>
			<table class='table table-responsive'>
			<tr>
				<td width='50%'>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'Name') }}
				        {{ Form::text('type', Input::old('type'),array('id'=>'type','class' => 'form-control')) }}
				    </div>
				</td>
				<td>
					<div class="{{ $frm_class }}">
				    	<?php $level = ['1' => 'good', '2' => 'fair', '3' => 'bad'];?>
				        {{ Form::label('name', 'level') }}
				        {{ Form::select('level', $level, '2',array('id'=>'level','class' => 'form-control'))}} 
				   
				    </div>
				</td>			
			</tr>	
			<tr>
				<td  colspan='2'><input type="hidden" value="" name="hdnSkill" id="hdnSkill"/>
					<table id="tblSkill" class="table table-striped table-hover table-responsive" name='tblSkill'>
		                <thead>
		                <tr>
		                  <th>Qualification</th>
		                  <th>level</th>
		                  <th></th>
		                </tr>
		                </thead>
		            </table>
				</td>
			</tr>
			</table>
		</div>
		<div id="tabs-5">	
			Work Experience <input type="button" value="Add" id="btnAddExp" style="width:80px; padding: 5px; float:right;"/>
			<table class='table table-responsive'>
			<tr>
				<td>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'Company') }}
				        {{ Form::text('organization', Input::old('organization'),array('id'=>'organization','class' => 'form-control')) }}
				    </div>
				</td>
				<td>
					<div class="{{ $frm_class }}">
				    	{{ Form::label('name', 'Rank') }}
				        {{ Form::text('rank', Input::old('rank'),array('id'=>'rank','class' => 'form-control')) }}
				    </div>
				</td>	
				<td>
					<div class="{{ $frm_class }}">
				    	{{ Form::label('name', 'Start Date') }}
				     	{{ Form::text('start_date',Input::old('start_date'), array('id'=>'sdate','class' => 'form-control')) }}
					</div>
				</td>	
				<td>
					<div class="{{ $frm_class }}">
				    	{{ Form::label('name', 'End Date') }}
				        {{ Form::text('end_date', Input::old('end_date'),array('id'=>'edate','class' => 'form-control')) }}
				    </div>
				</td>			
			</tr>
			<tr>
				<td colspan='4'><input type="hidden" value="" name="hdnExp" id="hdnExp"/>
					<table id="tblExp" class="table table-striped table-hover table-responsive" name='tblExp'>
		                <thead>
		                <tr>
		                  <th>Company</th>
		                  <th>Position</th>
		                  <th>From</th>
		                  <th>To</th>
		                  <th></th>
		                </tr>
		                </thead>
		            </table>
				</td>
			</tr>	
			</table>
		</div>
		<div id="tabs-6">
			Refrees <input type="button" value="Add" id="btnAddRefree" style="width:80px; padding: 5px; float:right;"/>
			<table class='table table-responsive'>
			<tr>
				<td>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'First Name') }}
				        {{ Form::text('refree_first_name', Input::old('refree_first_name'),array('id'=>'refree_first_name','class' => 'form-control')) }}
				    </div>
				</td>
				<td>
					<div class="{{ $frm_class }}">
				        {{ Form::label('name', 'Last Name') }}
				        {{ Form::text('refree_last_name', Input::old('refree_last_name'),array('id'=>'refree_last_name','class' => 'form-control')) }}
				    </div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="{{ $frm_class }}">
				    	{{ Form::label('name', 'Company') }}
				        {{ Form::text('refree_company', Input::old('refree_company'),array('id'=>'refree_company','class' => 'form-control')) }}
				    </div>
				</td>
				<td>
					<div class="{{ $frm_class }}">
				    	{{ Form::label('name', 'Rank') }}
				        {{ Form::text('refree_rank', Input::old('refree_rank'),array('id'=>'refree_rank','class' => 'form-control')) }}
				    </div>
				</td>	
			</tr>
			<tr>
				<td>
					<div class="{{ $frm_class }}">
				    	{{ Form::label('name', 'Email') }}
				     	{{ Form::text('refree_email',Input::old('refree_email'), array('id'=>'refree_email','class' => 'form-control')) }}
					</div>
				</td>	
				<td>
					<div class="{{ $frm_class }}">
				    	{{ Form::label('name', 'Mobile No.') }}
				        {{ Form::text('refree_mobile_no', Input::old('refree_mobile_no'),array('id'=>'refree_mobile_no','class' => 'form-control')) }}
				    </div>
				</td>			
			</tr>
			<tr>
				<td colspan='4'><input type="hidden" value="" name="hdnRefree" id="hdnRefree"/>
					<table id="tblRefree" class="table table-striped table-hover table-responsive" name='tblRefree'>
		                <thead>
		                <tr>
		                  <th>Name</th>
		                  <th>Company</th>
		                  <th>Position</th>
		                  <th>Email</th>
		                  <th>Mobile No</th>
		                  <th></th>
		                </tr>
		                </thead>
		            </table>
				</td>
			</tr>	
			</table>
		</div>
	    <center>{{ Form::submit('Create!', array('class' => 'btn btn-primary green')) }}</center>
	{{ Form::close() }}
	</div>
</div>
</div>
</div>
<div class="col s3" ></div>
</div>
</div>
@endsection