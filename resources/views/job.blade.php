@extends('layouts.innernavigate')
@section('title', "Company's Job")   
@section('content')
	<div class="col-sm-9">	
		<div class="row">
			<div class="col-sm-12 text-center">
				@php $img = URL::to('/')."/uploads/company_logo/".$com_job[0]->company_logo; @endphp
				<img src="{{ $img }}" class="img-responsive" alt="" />
			</div>
		</div>
		<article>
				<h2>About this company</h2>
				<p>{{ $com_job[0]->com_des}}</p><hr>					
				<h2>Jobs</h2>
				<div class="jobs">
				@foreach($com_job as $cj)
				@php $url = URL::to(Session::get('lang').'/application/' . encrypt($cj->id)); @endphp
					<a href="{{ $url }}">
						<div class="title">
							<h5>{{ $cj->job_title }}</h5>
						</div>
						<div class="data">
							<span class="type full-time"><i class="fa fa-clock-o"></i>{{ $cj->type }}</span>
							<span class="sallary"> Ks{{ $cj->salary_range }}++</span>
						</div>
					</a>
				@endforeach					
				</div>
			</article>
	</div>
@endsection   
@extends('layouts.footer')