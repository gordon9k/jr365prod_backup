@section('footer')
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
@endsection