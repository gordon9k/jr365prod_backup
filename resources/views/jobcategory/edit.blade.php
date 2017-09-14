@extends('layouts.navigate')
@section('title', 'Job Category')
@section('content')
<div class="col-xs-12">
    <div class='row' style='margin:10px;'>
    <div class="col s9" >
    <div class="panel panel-default">	
    <div class="panel-body ">
    <div style="margin-bottom: 30px;">
    	<h5 class="amber-text text-darken-2"><b>{{ trans('label.category_edit') }}</b></h5>
		@php $url = Session::get('lang').'/jobcategory' @endphp 
		<a href="{{ url($url) }}" style="float:right"><i class="material-icons">list</i></a>
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
    <div class="box-body">    
	@php $url = Session::get('lang').'/jobcategory/'.$job_category->id @endphp 
    {{ Form::open(array('url' => $url,'route'=>array('jobcategory',$job_category->id),'role'=>'form', 'method' => 'PUT')) }}
    @if($errors->any())
      <div class="form-group has-error">
          {{ Form::label('name', 'Name') }}
          {{ Form::text('name', null, array('class' => 'form-control')) }}
      </div>
      @else
      <div class="form-group">
          {{ Form::label('name', 'Name') }}
          {{ Form::text('name', $job_category->category, array('class' => 'form-control')) }}
      </div>
    @endif
      {{ Form::submit('Update!', array('class' => 'btn btn-primary green')) }}

  {{ Form::close() }}
  </div></div>
</div>
</div>
<div class="col s3" ></div>
</div>
</div>
@endsection
@extends('layouts.footer')