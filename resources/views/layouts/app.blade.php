<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title') 	</title>

    <!-- Styles 
    <link href="{{asset('/css/app.css')}}" rel="stylesheet">
	-->
	<!-- favicon -->
    <link rel="shortcut icon" href="{{asset('/jobready365.png')}}">	
     
	<link rel="stylesheet" href="/bootstrap/css/sweetalert.css">
	
	<link href="/custom/css/style.css" rel="stylesheet">
	
	<link href="/custom/custom.css" rel="stylesheet">	
	
	<!-- <link href="/materialize-css/css/materialize.css" rel="stylesheet">-->
	
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
<body>
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
			@yield('navigate') 			
		</div>
	
		<header>
			<div id="header-background"></div>
			<div class="container">
				<div class="pull-left">
					<div id="logo"><a href="/"><img src="{{asset('/jobready365.png')}}" alt="Jobseek - Job Board Responsive HTML Template" width="50px" height="50px" />
						<span style='font-weight:bold; margin:50px 0 0 10px; color:#ffffff; font-size:16px;'>jobready365.com</span></a>
					</div>
				</div>				
				<!-- <div id="menu-open" class="pull-right">
					<a class="fm-button"><i class="fa fa-bars fa-lg"></i></a>
				</div>	 -->
				<div id="menu1" class="pull-right">					 
					@php $lang = Session::get('lang') @endphp   
					<ul class="nav_wide">
						@if (Auth::guest())
						<li style='float:left; list-style: none;'><a href="{{ url($lang.'/login') }}"><b>Login</b></a></li>
					     	<li style='float:left; list-style: none;'><a href="{{ url($lang.'/register') }}"><b>Register</b></a></li>  
						@else
							<li style='float:left; list-style: none;'><a href="{{ url($lang.'/dashboard/') }}" role="button" aria-expanded="false"><b>{{ ucfirst(Auth::user()->telephone_no) }}</b></a></li>
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
					<a href="{{ url('language/en') }}">EN</a> | <a href="{{ url('language/mm') }}">ျမန္မာ</a>
				</div>				
			</div>
		</header>	

		<!-- ============ HEADER END ============ -->

		<!-- ============ SLIDES START ============ -->

		@yield('slider')    
	</div>
    <div id="app">  
        <div class="row">
        	
        	<section id="child_page">
			<div class="container dashboard">	
			@if (!Auth::guest())		
			<div class="col-sm-3">
				<div class="row">
				@yield('innernavigate')  
				</div>
			</div>
			@yield('content') 
			@else
				@yield('content') 
			@endif    
	          
	        </div>
	        </section>      
        </div>    
    </div>
	@yield('footer')
<!-- Scripts -->
<script src="/custom/js/modernizr.custom.79639.js"></script>

<!-- jQuery (necessary for Bootstraps JavaScript plugins) -->
<script src="/custom/js/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>  
 
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
	
<script src="/bootstrap/js/sweetalert.js"></script>

<script src="/custom/custom.js"></script>

 <!-- <script src="/materialize-css/js/materialize.js"></script>
	
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>   
	
<script src="/js/app.js"></script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 
<script src="/materialize-css/js/materialize.min.js"></script> -->

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
</body>
</html>
