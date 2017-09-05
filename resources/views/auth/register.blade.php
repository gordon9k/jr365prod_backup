@extends('layouts.navigate')
@section('title', 'Register')
@section('content')
<script src="/custom/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
    function getJobCategory(){
    $.ajax({
            method: "GET",
            url: "category" 
        })                 
        .success(function(data) {            
            var list = new Array(); 

        	// insert all the properties and their values into an array of objects
        	for(var propName in data) {
        	    list.push({ "id": propName, "value":data[propName] });
        	}
        	
        	// sort the array using the value instead of the id
        	list.sort(function compare(a,b) {
        	              if (a.value < b.value)
        	                 return -1;
        	              else
        	                 return 1; // we consider that if they have the same name, the first one goes first
        	            });
            select = '<div class="list-area"><ul class="list-unstyled ">';
            select = '<select name="category" id="category" class="form-control list-unstyled">';
          //  select +='<option value="0"  selected>Select Category</option>';
            $.each(list, function(key, value) {
                console.log('<option value="'+value.id+'">'+value.value+'</option>');
                select +='<option value="'+value.id+'">'+value.value+'</option>';
            	//select +='<option value="'+key+'">'+value+'</option>';
            });
            select += '</select></ul></div>'; 
          $("#category_div").html(select);
    });
}
function validateQty(event) {
        var key = window.event ? event.keyCode : event.which;

	    if (event.keyCode == 8 || event.keyCode == 46
	     || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {
	        return true;
	    }
	    else if ( key < 48 || key > 57 ) {
	        return false;
	    }
    	else return true;
    };
$(document).ready(function() {
    getJobCategory(); //use in register
});
</script>
<section id="register">
<div class="container">
	<form role="form" method="POST" action="{{ url('/register') }}">
    	{{ csrf_field() }}
    	<div class="row">
    	<div class="col-sm-4"></div>
		<div class="col-sm-4 dashboard"> 
			<h2>Register</h2>
			<div class="col-sm-12 form-group">
                <input type="radio" class="with-gap" name="user_type" id="rdoType1" value="1"><label for="rdoType1">{{ trans('label.employer') }}</label>
                <input type="radio" class="with-gap" style='margin-left:30px;' name="user_type" id="rdoType2" value="2" checked><label for="rdoType2">{{ trans('label.jobseeker') }}</label>
                @if ($errors->has('user_type'))
               		<span class="help-block">
                    	<strong style='color:red;'> * {{ $errors->first('user_type') }}</strong>
                    </span>
                @endif               
           	</div>    
	        <div class="col-sm-12 form-group">        	
	            <input id="login_name" type="text" placeholder='User Name' class="form-control" name="login_name" value="{{ old('login_name') }}" required autofocus>
	           	@if ($errors->has('login_name'))
	            	<span class="help-block">
	                	<strong style='color:red;'> * {{ $errors->first('login_name') }}</strong>
	               	</span>
	           	@endif
	        </div>
	        <div class="col-sm-12 form-group">
	            <input id="telephone_no" type="text" onkeypress='return validateQty(event);' placeholder='Telephone No' class="form-control" name="telephone_no" value="{{ old('telephone_no') }}"  required>
	            @if ($errors->has('telephone_no'))
	            <span class="help-block">
	            	<strong style='color:red;'> * {{ $errors->first('telephone_no') }}</strong>
	            </span>
	            @endif
	        </div>
	        <div class="col-sm-12 form-group">
		            <input id="email" type="email" placeholder='Email' class="form-control" name="email" value="{{ old('email') }}">
	                @if ($errors->has('email'))
	                	<span class="help-block">
	                 		<strong style='color:red;'> * {{ $errors->first('email') }}</strong>
	                	</span>
	                @endif
	        </div>
	        <div class="col-sm-12 form-group">
	            <input id="password" type="password" placeholder='Password' class="form-control" name="password" required>
	            @if ($errors->has('password'))
	                <span class="help-block">
	                    <strong style='color:red;'> * {{ $errors->first('password') }}</strong>
	                </span>
	            @endif
	        </div>
	        <div class="col-sm-12 form-group">
	            <input id="password-confirm" type="password" placeholder='Confirm Password' class="form-control" name="password_confirmation" required>
	        </div>
	        <div class="col-sm-12 form-group">
	            <div id='category_div'></div>
	                @if ($errors->has('category'))
	                    <span class="help-block">
	                        <strong style="color:red"> * {{ $errors->first('category') }}</strong>
	                    </span>
	                @endif
	        </div>
	        <div class="col-sm-12 form-group">
	            <center><button type="submit" class="btn btn-primary green">Register</button></center>
	        </div>
    	</div>
    	</div>	
    </form>
</div>
</section>
@endsection
@extends('layouts.footer')