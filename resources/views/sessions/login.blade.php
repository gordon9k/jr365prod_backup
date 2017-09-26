@extends('layouts.navigate')
@section('title', 'Login')
@section('content')
<section id="login">
<div class="container">
<form role="form" method="POST" action="{{ url('/login') }}">
    {{ csrf_field() }}
	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-8"> 
		<center>
		<div style='width:auto; margin:10px;  background-color:#fff; padding:20px 10px 20px 10px; font-weight:bold; color:#000000; font-size:16px; border-radius:5px;  border:1px solid #01bc59;'>
			<div id="logo"><a href="/"><img src="{{asset('/jobready365.png')}}" alt="Jobseek - Job Board Responsive HTML Template" width="50px" height="50px" /></a></div>
			<div style='margin:10px 10px 15px 10px; padding:5px 10px; font-weight:bold; color:#222; font-size:16px; border-radius:5px;'>Login to Jobready365.com</div>
			
           	<div class="form-group"> 
                	<input id="telephone_no" type="text" name="telephone_no" placeholder="Telephone No." class="form-control" value="{{ old('telephone_no') }}" required autofocus> 
                	@if ($errors->has('telephone_no'))
                    <span class="help-block">
                    	<strong style='color:red;'> * {{ $errors->first('telephone_no') }}</strong>
                   	</span>
                    @endif
           	</div>
           	
           	<div class="form-group">              
	                <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                    	@if ($errors->has('password'))
                        	<span class="help-block">
                            	 <strong style='color:red;'> * {{ $errors->first('password') }}</strong>
                            </span>
                       	@endif
            </div>            
            
	        <div class="col-sm-12 form-group"> 
	        	<div class="switch">
				  <!--  <label>
				      Auto Login ?
				      <input type="checkbox" id="remember" name='remember' >
				      <span class="lever"></span>
				    </label> -->				    
				  </div>				  
          	</div>
          	
		<div class="form-group">
			<button type="submit" class="btn btn-primary">{{ trans('label.login') }}</button>
		</div>
		<div class="form-group">
			<a href="{{ url('/password/reset') }}">{{ trans('label.forget_password') }}</a>
		</div>
	</div>
	</center>
	</div>
	        
			<!-- <ul class="social-login" style='margin-top:20px;'>
				<li><a href='fbredirect' class="btn btn-facebook"><i class="fa fa-facebook"></i>Login with Facebook</a></li>
				<li><a class="btn btn-google"><i class="fa fa-google-plus"></i>Sign In with Google</a></li>
				<li><a class="btn btn-linkedin"><i class="fa fa-linkedin"></i>Sign In with LinkedIn</a></li>
			</ul> -->
		</div>	
	</div>
</form>
</div>
</section>
@endsection
@extends('layouts.footer')