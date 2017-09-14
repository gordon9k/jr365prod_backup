@extends('layouts.app')
@section('navigate')
<script type="text/javascript">
function changetoen(){alert('en');
	<?php $_SESSION['lang']= 'en'; ?>  		
	var url = window.location.toString();	alert(url);
}

function changetomm(){alert('mm');
	<?php $_SESSION['lang']= 'mm'; ?>
	var url = window.location.toString();	alert(url);		
}
</script>
@php $lang = Session::get('lang') @endphp   
<ul class="nav">				
		@if (Auth::guest())
		<li><a href="{{ url($lang.'/login') }}"><b>Login</b></a></li>
	    <li><a href="{{ url($lang.'/register') }}"><b>Register</b></a></li>
		@elseif(Auth::user()->is_admin == 1)
			<li><a href="{{ url($lang.'/employer/'.encrypt(Auth::user()->id).'/edit') }}"><b>{{ ucfirst(Auth::user()->login_name) }}</b></a></li>
    	@elseif(Auth::user()->user_type == '1')
	    	<li><a href="{{ url($lang.'/employer/'.encrypt(Auth::user()->id).'/edit') }}"><b>{{Auth::user()->login_name}}</b></a>
	   @else
	    	<li><a href="{{ url($lang.'/applicant/'.encrypt(Auth::user()->id).'/edit') }}"><b>{{Auth::user()->login_name}}</b></a>
		@endif 
	   	@if (!Auth::guest()) 
	    <li><a href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><b>Logout</b></a>
    	<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
        	{{ csrf_field() }}
        </form>
   	</li> 
   	@endif                     
</ul>
@endsection