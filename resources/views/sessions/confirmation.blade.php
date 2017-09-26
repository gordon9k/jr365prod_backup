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
       <form role="form" method="POST" action="{{ route('confirmCode') }}">

           {{ csrf_field() }}

           <div class="row">
               <div class="col-sm-4"></div>
               
               <div class="col-sm-4 dashboard">
                    <h2>Please insert your confirmation code</h2>

                    {{-- <a href="{{ route('resendCode', compact('user')) }}" class="pull-right" style="margin-right: 14px; margin-bottom: 5px;">Resend Code?</a><br> --}}

                    @include ('errors.confirm-error')

                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <input type="hidden" name="telephone_no" value="{{ $user->telephone_no }}">

                    <div class="col-sm-12 form-group">
                        <input id="activation_code" type="text" onkeypress='return validateQty(event);' placeholder='confirmcode' class="form-control" name="activation_code" value="{{ old('activation_code') }}"  required>
                        @if ($errors->has('activation_code'))
                        <span class="help-block">
                            <strong style='color:red;'> * {{ $errors->first('activation_code') }}</strong>
                        </span>
                        @endif
                    </div>
        	       <div class="col-sm-12 form-group">
                       <center><button type="submit" class="btn btn-primary green">Confirm</button></center>
                    </div>
                </div>
            </div>	
        </form>
    </div>

</section>

@endsection
@extends('layouts.footer')