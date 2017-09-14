<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{asset('/jobready365.png')}}">	
     
	<link rel="stylesheet" href="/bootstrap/css/sweetalert.css">
	
	<link href="/custom/css/style.css" rel="stylesheet">
	<link href="/custom/custom.css" rel="stylesheet">
	
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
    <style>
     body {
        font-family: 'Lato', serif;
        font-size: 14px;
      }
    </style>
</head>
<body id='home'>
@php Session::set('url',Request::url() )@endphp
	<div id="loader">
		<i class="fa fa-cog fa-4x fa-spin"></i>
	</div>
	<div class="fm-container">
		<!-- Menu -->
		<div class="menu">
			<div class="button-close text-right">
				<a class="fm-button"><i class="fa fa-close fa-2x"></i></a>				
			</div>
			@php $lang = Session::get('lang') @endphp   
			<ul class="nav">				
				@if (Auth::guest())
				<li style='float:left; list-style: none;'><a href="{{ url($lang.'/login') }}"><b>Login</b></a></li>
			     	<li style='float:left; list-style: none;'><a href="{{ url($lang.'/register') }}"><b>Register</b></a></li>
				@else
					<li style='float:left; list-style: none;'><a href="{{ url($lang.'/dashboard/') }}" role="button" aria-expanded="false"><b>{{ ucfirst(Auth::user()->telephone_no) }}</b></a></li>
			  	@endif 
			   	@if (!Auth::guest()) 
			    	<li><a href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><b>Logout</b></a>
			    	<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
			        	{{ csrf_field() }}
			        </form>
			   	</li> 
			   	@endif                     
			</ul>
		</div>
		<!-- header -->
		<header>
			<div id="header-background"></div>
			<div class="container">
				<div class="pull-left">
					<div id="logo"><a href="/"><img src="{{asset('/jobready365.png')}}" alt="Jobseek - Job Board Responsive HTML Template" width="50px" height="50px" />
						<span style='font-weight:bold; margin:50px 0 0 10px; color:#ffffff; font-size:16px;'>jobready365.com</span></a>
					</div>
				</div>			
				<div id="menu-open" class="pull-right">
					<a class="fm-button"><i class="fa fa-bars fa-lg"></i></a>
				</div>	
				<!-- <div style='margin-left:500px; font-weight:bold;'>Any Time Any Where</div> -->
				<div id="menu1" class="pull-right">					 
					@php $lang = Session::get('lang') @endphp   
					<ul class="nav_wide">
						@if (Auth::guest())
						<li style='float:left; list-style: none;'><a href="{{ url($lang.'/login') }}"><b>Login</b></a></li>
					    <li style='float:left; list-style: none;'><a href="{{ url($lang.'/register') }}"><b>Register</b></a></li>
						@elseif(Auth::user()->is_admin == 1)
							<li style='float:left; list-style: none;'><a href="{{ url($lang.'/dashboard/') }}"><b>{{ ucfirst(Auth::user()->telephone_no) }}</b></a></li>
				    	@elseif(Auth::user()->user_type == '1')
					    	<li style='float:left; list-style: none;'><a href="{{ url($lang.'/dashboard/') }}"><b>{{Auth::user()->telephone_no}}</b></a></li> 
					    @else
					    	<li style='float:left; list-style: none;'><a href="{{ url($lang.'/dashboard/')}}"><b>{{Auth::user()->telephone_no}}</b></a></li>
					   	@endif 
					   	@if (!Auth::guest()) 
					    <li style='float:left; list-style: none;'><a href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><b>Logout</b></a>
					    	<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
					        	{{ csrf_field() }}
					        </form>
					   	</li> 
					   	@endif                     
					</ul>
				</div>				
				<!-- <div id="searchbox" class="pull-right">					
					<form>
						<div class="form-group">
							<label class="sr-only" for="searchfield">Searchbox</label>
							<input type="text" class="form-control" id="searchfield" placeholder="Type keywords and press enter">
						</div>
					</form>
				</div> 
				<div id="search" class="pull-right">
					<a><i class="fa fa-search fa-lg"></i></a>
				</div>-->
				<div style='float:right; margin-right:20px;'>
				<a href="{{ url('language/en') }}">EN</a> | <a href="{{ url('language/mm') }}">ျမန္မာ</a></div>
			</div>
		</header>
	</div>
	<!-- ============ HEADER END ============ -->

	<!-- ============ SLIDES START ============ -->
	<div id="slider" class="sl-slider-wrapper">
		<div class="sl-slider">
			<div class="sl-slide" data-orientation="horizontal" data-slice1-rotation="-25" data-slice2-rotation="-25" data-slice1-scale="2" data-slice2-scale="2">
				<div class="sl-slide-inner">
					<div class="bg-img bg-img-1"></div>
					<div class="tint"></div>
					<div class="slide-content">
						<h2>Looking for a job?</h2>
						<h3>There's no better place to start</h3>
					<!--	<p><a href="jobs.html" class="btn btn-lg btn-default">Find a job</a></p>	-->
					</div>
				</div>
			</div>
			
			<div class="sl-slide" data-orientation="vertical" data-slice1-rotation="10" data-slice2-rotation="-15" data-slice1-scale="1.5" data-slice2-scale="1.5">
				<div class="sl-slide-inner">
					<div class="bg-img bg-img-2"></div>
					<div class="tint"></div>
					<div class="slide-content">
						<h2>Need an employee?</h2>
						<h3>We've got perfect candidates</h3>
					<!--	<p><a href="candidates.html" class="btn btn-lg btn-default">Post a job</a></p>	-->
					</div>
				</div>
			</div>
			
			<div class="sl-slide" data-orientation="horizontal" data-slice1-rotation="3" data-slice2-rotation="3" data-slice1-scale="2" data-slice2-scale="1">
				<div class="sl-slide-inner">
					<div class="bg-img bg-img-3"></div>
					<div class="tint"></div>
					<div class="slide-content">
						<h2>Evolving your career?</h2>
						<h3>Find new opportunities here</h3>
					<!--	<p><a href="jobs.html" class="btn btn-lg btn-default">Find a job</a></p>	-->
					</div>
				</div>
			</div>
			
			<div class="sl-slide" data-orientation="vertical" data-slice1-rotation="-5" data-slice2-rotation="25" data-slice1-scale="2" data-slice2-scale="1">
				<div class="sl-slide-inner">
					<div class="bg-img bg-img-4"></div>
					<div class="tint"></div>
					<div class="slide-content">
						<h2>Extending your team?</h2>
						<h3>Find a perfect match</h3>
					<!--	<p><a href="candidates.html" class="btn btn-lg btn-default">Find a cadidate</a></p>	-->
					</div>
				</div>
			</div>
			
		</div>

		<nav id="nav-arrows" class="nav-arrows">
			<span class="nav-arrow-prev">Previous</span>
			<span class="nav-arrow-next">Next</span>
		</nav>

		<nav id="nav-dots" class="nav-dots">
			<span class="nav-dot-current"></span>
			<span></span>
			<span></span>
			<span></span>
		</nav>
	</div> 
	<!-- ============ SLIDES END ============ -->
	<div id="app">  
		<div class="row">  
		<!-- <section id="companies" class="color1">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 dashboard">
				<h2>Featured Companies</h2>					
				<ul id="featured-companies" class="row">				
			        	@foreach($featured_company as $com) 
			        		@php 
			          			$img = URL::to('/')."/uploads/company_logo/".$com->company_logo; 
			          			$url = URL::to(Session::get('lang').'/company_job/' . encrypt($com->company_id));
			          		@endphp
							<li class="col-md-2">
								<a href="company.html">
									<a href="{{$url}}"><img src="{{ $img }}" class="img-responsive" style='border:1px solid #999; width:100px; height:100px;'>
									<span style='color: rgb(111, 102, 102); border-radius: 5px 20px 5px; background: #A6E919;; padding: 10px; margin-left: 10px; margin-top: 0px; border: 1px solid rgb(233, 208, 208); position: fixed;' class="badge">{{ $com->job_count}}</span></a>
								</a>
							</li>
	         			@endforeach	
				</ul>	
							
				<div class="carousel slide multi-item-carousel" id="theCarousel">
			        <div class="carousel-inner">
			        	<?php $i = 0;?>	
			        	@foreach($featured_company as $com) 
			        	@if($i == 0)
		            		<div class="item active">	
	            			@else
	            			<div class="item">
	            			@endif
			          	<div class="col-sm-2">
			          		@php 
			          			$img = URL::to('/')."/uploads/company_logo/".$com->company_logo; 
			          			$url = URL::to(Session::get('lang').'/company_job/' . encrypt($com->company_id));
			          		@endphp
			          		<a href="{{$url}}"><img src="{{ $img }}" class="img-responsive" style='border:1px solid #999; width:100px; height:100px;'><span style='color: rgb(111, 102, 102); border-radius: 5px 20px 5px; background: #A6E919;; padding: 10px; margin-left: 10px; margin-top: 0px; border: 1px solid rgb(233, 208, 208); position: fixed;'>{{ $com->job_count}}</span></a>
			 			</div>
			 			</div>
			 			<?php $i++; ?>
			         	@endforeach				        	
		          	</div>
		          	<a class="left carousel-control" href="#theCarousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
        			<a class="right carousel-control" href="#theCarousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
     	        </div>
	        </div>  
        </div>
    </div>
</section> -->
		<section id="recent_job">
			<div class="container">
				<div class="row dashboard">
				<div class="col-sm-3">
				<h2>{{ trans('label.search') }}</h2>
				<div class="block-section-sm side-right">
				<div class="result-filter" style='border:1px solid #34b80c; padding:10px; border-radius:5px;'>
					<h5 class="no-margin-top font-bold margin-b-20 " ><a href="#s_collapse_1" data-toggle="collapse" >Salary Estimate <i class="fa ic-arrow-toogle fa-angle-right pull-right"></i> </a></h5>
					<div class="collapse in" id="s_collapse_1" style='padding-top: 10px;'>
						<div class="list-area">
						
						<?php $salary = ['0'=>'Any','1'=>'Negotiate', '2'=>'less than 100000 Ks', '3' =>'100000 ~ 300000 Ks', '4'=>'300000 ~ 500000 Ks', '5' => '500000 ~ 1000000 Ks','6'=>'greate than 100000 Ks'];?>
					    {{ Form::select('salary_range', $salary, '0',array('id'=>'salary_range','class' => 'form-control list-unstyled'))}} 
						</div>
					</div>
					<br>
					<h5 class="font-bold  margin-b-20" ><a href="#s_collapse_5" data-toggle="collapse" >Job Type <i class="fa ic-arrow-toogle fa-angle-right pull-right"></i></a> </h5>
					<div class="collapse in" id='s_collapse_5' style='padding-top: 10px;'>
			  			<div class="list-area">
			  			    <ul class="list-unstyled ">
			  			    @php $job_type[0] = "Any" @endphp	
				            	@foreach($job_nature as $jn)
				            		
				            		@php $type = "" @endphp	
				            		<?php  	
								      	switch($jn->job_nature_id){
								      		case('1'):	$type = "Full Time (". $jn->job_count.")"; $job_type[$jn->job_nature_id] = $type;break;
								      		case('2'):	$type = "Part Time (". $jn->job_count.")"; $job_type[$jn->job_nature_id] = $type; break;
								      		case('3'):  $type = "Freelance (". $jn->job_count.")"; $job_type[$jn->job_nature_id] = $type; break; 
								      		case('4'):	$type = "Contract (". $jn->job_count.")"; $job_type[$jn->job_nature_id] = $type; break; 
								      		case('5'):	$type = "Temporary (". $jn->job_count.")"; $job_type[$jn->job_nature_id] = $type; break; 
											default:	$type = "Any"; $job_type[0] = $type;break; 	
										}									
										
									?>
				              	@endforeach			              	
					    		{{ Form::select('jobType', $job_type, '0',array('id'=>'jobType','class' => 'form-control list-unstyled'))}} 
				            </ul>
			  			</div>
					</div>
					<br>
					<h5 class="font-bold  margin-b-20"><a href="#s_collapse_2" data-toggle="collapse" >Category <i class="fa ic-arrow-toogle fa-angle-right pull-right"></i></a>  </h5>
					<div class="collapse in" id="s_collapse_2" style='padding-top: 10px;'>
				  		<div class="list-area">
				            <ul class="list-unstyled ">	
				            	@php $category[0] = "Any" @endphp				            
				                @foreach($category_list as $cat) 
				                @php	$category[$cat->job_category_id] = $cat->category ." (".$cat->job_count.")" @endphp	            
					            @endforeach 
					            {{ Form::select('category', $category, '0',array('id'=>'category','class' => 'form-control list-unstyled'))}} 
				            </ul>
				  		</div>
					</div>
					<br>
					<h5 class="font-bold  margin-b-20"><a href="#s_collapse_3" data-toggle="collapse" >Company <i class="fa ic-arrow-toogle fa-angle-right pull-right"></i></a> </h5>
					<div class="collapse in" id="s_collapse_3" style='padding-top: 10px;'>
				  		<div class="list-area">
				            <ul class="list-unstyled ">
				            	@php $company[0] = "Any" @endphp
					            @foreach($company_list as $com) 
				            	@php	$company[$com->company_id] = $com->company_name ." (".$com->job_count.")" @endphp	
					            @endforeach  
					            {{ Form::select('company', $company, '0',array('id'=>'company','class' => 'form-control list-unstyled'))}} 
				            </ul>
				  		</div>
					</div>
					<br>	
					<h5 class="font-bold  margin-b-20" ><a href="#s_collapse_4" data-toggle="collapse" class="collapsed" >Location  <i class="fa ic-arrow-toogle fa-angle-right pull-right"></i> </a></h5>
					<div class="collapse in" id='s_collapse_4' style='padding-top: 10px;'>
				  		<div class="list-area">
				            <ul class="list-unstyled ">
				            	@php $tsp[0] = "Any" @endphp
				            	@foreach($location as $loc) 
				            	@php	$tsp[$loc->township_id] = $loc->township ." (".$loc->job_count.")" @endphp
					            @endforeach  
					            {{ Form::select('township', $tsp, '0',array('id'=>'township','class' => 'form-control list-unstyled'))}}  
				            </ul>
				  		</div>
					</div>
					<a class="btn btn-primary" id="btnHpSearch">
						<span class="more">{{ trans('label.search') }}</span>
					</a>
				</div>
			</div>
		</div>
				<!-- recent jobs -->
				<div class="col-sm-9">
				<h2>{{ trans('label.recent_job') }}</h2>	
				<div class='row'>
					<div class="col-sm-2" ></div>
					<div class="col-sm-6" >
					        {{ Form::text('search', Input::old('search'), array('id'=>'txtKeywordSearch', 'placeholder'=>'Keywords, e.g Job Title', 'class'=>'form-control')) }}
					</div>
					<div class="col-sm-2" style='margin-top:5px;' >
						<a class="btn btn-primary" id="btnKeywordSearch">
						<span class="more">{{ trans('label.search') }}</span>
					</a>
					</div>
				</div>
				<br>
				<table id="tblLatestJobs" class="table table-responsive jobs">
	        		<thead><tr><th></th></tr></thead>
	    	  		</table>				
				</div>
				<!-- filte section -->
				
	</div>
</div>
</section>
</div>		
</div>
<!--	<section id="companies" class="color1">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 dashboard">
				<h2>Featured Companies</h2>				
				<div class="carousel slide multi-item-carousel" id="theCarousel">
			        <div class="carousel-inner">
			        	<?php $i = 0;?>	
			        	@foreach($featured_company as $com) 
			        	@if($i == 0)
		            		<div class="item active">	
	            		@else
	            			<div class="item">
	            		@endif
			          	<div class="col-sm-4">
			          		@php 
			          			$img = URL::to('/')."/uploads/company_logo/".$com->company_logo; 
			          			$url = URL::to(Session::get('lang').'/company_job/' . encrypt($com->company_id));
			          		@endphp
			          		<a href="{{$url}}"><img src="{{ $img }}" class="img-responsive"><span style='color: rgb(111, 102, 102); border-radius: 5px 20px 5px; background: #A6E919;; padding: 10px; margin-left: 10px; margin-top: 0px;
border: 1px solid rgb(233, 208, 208); position: fixed;'>{{ $com->job_count}}</span></a>
			 			</div>
			 			</div>
			 			<?php $i++; ?>
			         	@endforeach				        	
		          	</div>
		          	<a class="left carousel-control" href="#theCarousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
        			<a class="right carousel-control" href="#theCarousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
     	        </div>
	        </div>  
        </div>
    </div>
</section>
-->

<div id="subscriber" class="parallax text-center">
	<div class="tint"></div>	
	<div class="subscribecount">	
		<div class="row" id="subscribe">
			<h4>How many people we've helped</h4>
			<div class="subscribe">
				<div class="number">{{ $member_count }}</div>
				<div class="description">Members</div>
			</div>
			<div class="subscribe">
				<div class="number">{{ sizeof($job_list) }}</div>
				<div class="description">Jobs</div>
			</div>
		</div>
	</div>
</div>
<section id="stats" class="parallax text-center">
	<div class="tint"></div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h1>Subscribers</h1>
				<h4>How many people we've helped</h4>
			</div>
		</div>
		<div class="row" id="counter">			
			<div class="counter">
				<div class="number">{{ $member_count }}</div>
				<div class="description">Members</div>
			</div>
		
			<div class="counter">
				<div class="number">{{ sizeof($job_list) }}</div>
				<div class="description">Jobs</div>
			</div>
		<!-- 
			<div class="counter">
				<div class="number">1,482</div>
				<div class="description">Resumes</div>
			</div>
		
			<div class="counter">
				<div class="number">83</div>
				<div class="description">Companies</div>
			</div>
 		-->
		</div> 
	</div>		
</section>	

<footer>
      <div id="prefooter">
        <div class="container">
          <div class="row">
            <div class="col-sm-4" id="newsletter">
              <h5 style='color:#fff;'>About Jobready365</h5><br>
                <p style='color:#fff;'>You can find employment for your dream job through our top job and work opportunity listings from top employers and vacant jobs such as IT, Accounting, etc.</p>
            </div>
            <div class="col-sm-4" id="newsletter">
            	<ul>
	              <!--  <li><a style='color:#fff;' href="#!">Registered User Agreement</a></li> -->
	              	@php $policy = Session::get('lang').'/policy' @endphp 
	                <li><a style='color:#fff;' href="{{ url($policy ) }}">{{ trans('label.policy') }}</a></li>
	                @php $terms= Session::get('lang').'/terms' @endphp 
	                <li><a style='color:#fff;' href="{{ url($terms) }}">{{ trans('label.terms') }}</a></li>
              	</ul>
            </div>
            <div class="col-sm-4">
            	<h5 style='color:#fff;'>How to join with us ?</h5><br>
                <p style='color:#fff;'>
                	<i class="fa fa-home" aria-hidden="true"></i> No 5, Third Floor, Parami Rd, Hlaing Township, Yangon, Myanmar.<br>		
			<i class="fa fa-phone" aria-hidden="true"></i> +95 94564 56978, +95 97777 1732, +95 97800 09117<br>
			<i class="fa fa-envelope" aria-hidden="true"></i> tech@goldenictsolutions.com<br>
                </p>
            </div>
            <!-- <div class="col-sm-3">
              <h5 style='color:#fff;'>Employer</h5>
              <ul>
                <li><a class="green-text text-darken-1" href="#!">Link 1</a></li>
                <li><a class="green-text text-darken-1" href="#!">Link 2</a></li>
                <li><a class="green-text text-darken-1" href="#!">Link 3</a></li>
                <li><a class="green-text text-darken-1" href="#!">Link 4</a></li>
              </ul>
            </div>
            <div class="col-sm-3">
                <h5 style='color:#fff;'>Job Seekers</h5>
                <ul>
                  <li><a class="green-text text-darken-1" href="#!">Link 1</a></li>
                  <li><a class="green-text text-darken-1" href="#!">Link 2</a></li>
                  <li><a class="green-text text-darken-1" href="#!">Link 3</a></li>
                  <li><a class="green-text text-darken-1" href="#!">Link 4</a></li>
                </ul>
            </div> -->
          </div>
        </div>
      </div>
</footer>
<div id="credits">
	<div class="container text-center">
		<div class="row">
			<div class="col-sm-6">
				&copy; 2017 JobReady365
				Designed &amp; Developed by Trustinno</a>
			</div>
            <!--<div class="col-sm-6" id="social-networks">
				<a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
				<a href="#"><i class="fa fa-2x fa-twitter-square"></i></a>
				<a href="#"><i class="fa fa-2x fa-google-plus-square"></i></a>
				<a href="#"><i class="fa fa-2x fa-youtube-square"></i></a>
				<a href="#"><i class="fa fa-2x fa-vimeo-square"></i></a>
				<a href="#"><i class="fa fa-2x fa-pinterest-square"></i></a>
				<a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
			</div>-->
		</div>
	</div>
</div>
<!-- Scripts -->
<script src="/custom/js/modernizr.custom.79639.js"></script>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/custom/js/jquery-1.11.2.min.js"></script>

<!-- Bootstrap Plugins -->
<script src="/custom/js/bootstrap.min.js"></script>

<!-- Retina Plugin -->
<script src="/custom/js/retina.min.js"></script>

<!-- ScrollReveal Plugin -->
<script src="/custom/js/scrollReveal.min.js"></script>

<!-- Flex Menu Plugin -->
<script src="/custom/js/jquery.flexmenu.js"></script>

<!-- Slider Plugin -->
<script src="/custom/js/jquery.ba-cond.min.js"></script>
<script src="/custom/js/jquery.slitslider.js"></script>

<!-- Carousel Plugin -->
<script src="/custom/js/owl.carousel.min.js"></script>

<!-- Parallax Plugin -->
<script src="/custom/js/parallax.js"></script>

<!-- Counterup Plugin -->
<script src="/custom/js/jquery.counterup.min.js"></script>
<script src="/custom/js/waypoints.min.js"></script>

<!-- No UI Slider Plugin -->
<script src="/custom/js/jquery.nouislider.all.min.js"></script>

<!-- Bootstrap Wysiwyg Plugin -->
<script src="/custom/js/bootstrap3-wysihtml5.all.min.js"></script>

<!-- Flickr Plugin -->
<script src="/custom/js/jflickrfeed.min.js"></script>

<!-- Fancybox Plugin -->
<script src="/custom/js/fancybox.pack.js"></script>

<!-- Magic Form Processing -->
<script src="/custom/js/magic.js"></script>

<!-- jQuery Settings -->
<script src="/custom/js/settings.js"></script>
		
<!-- SlimScroll -->
<script src="/plugins/slimScroll/jquery.slimscroll.min.js"></script>

<!-- DataTables  -->
<script src="/plugins/datatables/jquery.dataTables.min.js"></script>  
<script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>  
	
<!--<script src="/bootstrap/js/sweetalert.js"></script>-->

<script type="text/javascript">
$(function(){		
	showLatestJob('tblLatestJobs');
});
$('#btnHpSearch').on( 'click', function () {
	showLatestJob('tblLatestJobs');		    
});

$('#btnKeywordSearch').on( 'click', function () {
	showLatestJobbyKeyword('tblLatestJobs');		    
});

function showLatestJobbyKeyword(tbl_name){

  	var keyword = $("#txtKeywordSearch").val();	
	
	//alert(salary_range+'/'+type+'/'+category+'/'+township+'/'+company);
	$('#'+tbl_name).DataTable().destroy();
	$('#'+tbl_name).DataTable({
    		"jQueryUI":true,  
    		//"scrollY":"600px", 
    		"paging": true,
    		// "pagingType": "first_last_numbers",
		  	"lengthChange": false,		
  			"pageLength": 10,
		  	"searching": false,
		  	"ordering": false,
		  	"autoWidth": true,	
		  	processing: true,
      		serverSide: true,
	      	ajax: {
	  			method: 'GET', // Type of response and matches what we said in the route
	    	    url: 'en/getLatestJobbyKeyword', // This is the url we gave in the route
		    	data: {'keyword':keyword}, // a JSON object to send back
	    	   },
	          columns:[{data:'result', name:'result'}]        
	      });	
}

function showLatestJob(tbl_name){

  	var salary_range = $("#salary_range").val() - 1;
	var type = $("#jobType").val();
	var category = $("#category").val();		
	//var city =$("#city").val();
  	var township =$("#township").val();
  	var company = $("#company").val();  
	
	//alert(salary_range+'/'+type+'/'+category+'/'+township+'/'+company);
	$('#'+tbl_name).DataTable().destroy();
	$('#'+tbl_name).DataTable({
	 	"jQueryUI":true,  
    		//"scrollY":"600px", 
    		"paging": true,
    		// "pagingType": "first_last_numbers",
		  	"lengthChange": false,		
  			"pageLength": 10,
		  	"searching": false,
		  	"ordering": false,
		  	"autoWidth": true,	
		  	processing: true,
      		serverSide: true,
	      	ajax: {
	  			method: 'GET', // Type of response and matches what we said in the route
	    	    url: 'en/getLatestJob', // This is the url we gave in the route
		    	data: {'salary_range':salary_range, 'type':type, 'category':category, 'township':township, 'company':company }, // a JSON object to send back
	    	   },
	         	/*columns: [
	                  { data: 'open_date', name: 'open_date' },
	                  { data: 'job_title', name: 'job_title'},
	                  { data: 'location', name: 'location' },
	                  { data: 'type', name: 'type'},  
	                  ]*/
	          columns:[{data:'result', name:'result'}]        
	      });	
}


	$('.multi-item-carousel').carousel({
	  interval: 5000,
	  cycle:true
	});
	
	$('.multi-item-carousel .item').each(function(){
		  var next = $(this).next();
		  if (!next.length) {
		    next = $(this).siblings(':first');
		  }
		  next.children(':first-child').clone().appendTo($(this));
		  
		  if (next.next().length>0) {
		    next.next().children(':first-child').clone().appendTo($(this));
		  } else {
		  	$(this).siblings(':first').children(':first-child').clone().appendTo($(this));
		  }
	});

</script>
</body>
</html>
