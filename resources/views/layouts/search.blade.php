@section('search')
<div class="col-sm-4">
	<h2>Search by</h2>
	<div class="block-section-sm side-right">
			<!--<div class="row">
	        <div class="col-xs-6">
	          <p><strong>Sort by: </strong></p>
	        </div>
	        <div class="col-xs-6">
	          <p class="text-right">
	            <strong>Relevance</strong> - <a href="#" class="link-black"><strong>Date</strong></a>
	          </p>
	        </div>
			</div>-->
		<div class="result-filter">
			<h5 class="no-margin-top font-bold margin-b-20 " ><a href="#s_collapse_1" data-toggle="collapse" >Salary Estimate <i class="fa ic-arrow-toogle fa-angle-right pull-right"></i> </a></h5>
			<div class="collapse in" id="s_collapse_1">
					<div class="list-area">
					<ul class="list-unstyled">
			            <li>
			                <a  href="#" >Negotiate</a>
			            </li>
			            <li>
			                <a  href="#" >KS 100000+</a>
			            </li>
			            <li>
			                <a  href="#" >KS 300000+</a>
			            </li>
			            <li>
			                <a  href="#" >KS 500000+</a>
			            </li>
			            <li>
			                <a  href="#" >KS 700000+</a>
			            </li>
					</ul>
					</div>
			</div>
			<br>
			<h5 class="font-bold  margin-b-20" ><a href="#s_collapse_5" data-toggle="collapse" >Job Type <i class="fa ic-arrow-toogle fa-angle-right pull-right"></i></a> </h5>
			<div class="collapse in" id='s_collapse_5'>
		  		<div class="list-area">
		            <ul class="list-unstyled ">
		              <li>
		                <a  href="#" >Full-time </a> (558)
		              </li>
		              <li>
		                <a  href="#" >Part-time </a> (438)
		              </li>
		              <li>
		                <a  href="#" >Contract </a> (313)
		              </li>
		              <li>
		                <a  href="#" >Freelance</a> (169)
		              </li>
		              <li>
		                <a  href="#" >Temporary  </a> (156)
		              </li>
		            </ul>
		  		</div>
			</div>
			<br>
			<h5 class="font-bold  margin-b-20"><a href="#s_collapse_2" data-toggle="collapse" >Category <i class="fa ic-arrow-toogle fa-angle-right pull-right"></i></a>  </h5>
			<div class="collapse in" id="s_collapse_2">
		  		<div class="list-area">
		            <ul class="list-unstyled ">					            
		            	@php $i = 1	@endphp
			            @foreach($category_list as $cat) 
			            @if($i < 11)	
			              <li>
			                <a  href="#" >{{ $cat->category}}</a>
			              </li>
			            @endif
		              	@php $i++	@endphp
			            @endforeach 
		            </ul>
		  		</div>
			</div>
			<br>
			<h5 class="font-bold  margin-b-20"><a href="#s_collapse_3" data-toggle="collapse" >Company <i class="fa ic-arrow-toogle fa-angle-right pull-right"></i></a> </h5>
			<div class="collapse in" id="s_collapse_3">
		  		<div class="list-area">
		            <ul class="list-unstyled ">
		            	@php $i = 1	@endphp
			            @foreach($company_list as $com) 
			            @if($i < 8)	
			              <li>
			                <a  href="#" >{{ $com->company_name}}</a>
			              </li>
			            @endif
		              	@php $i++	@endphp
			            @endforeach  
		            </ul>
		  		</div>
			</div>
			<br>	
			<h5 class="font-bold  margin-b-20" ><a href="#s_collapse_4" data-toggle="collapse" class="collapsed" >Location  <i class="fa ic-arrow-toogle fa-angle-right pull-right"></i> </a></h5>
			<div class="collapse" id='s_collapse_4'>
		  		<div class="list-area">
		            <ul class="list-unstyled ">
		              <li>
		                <a  href="#" >New York, NY </a> (558)
		              </li>
		              <li>
		                <a  href="#" >San Francisco, CA </a> (438)
		              </li>
		              <li>
		                <a  href="#" >Washington, DC </a> (313)
		              </li>
		              <li>
		                <a  href="#" >Chicago, IL</a> (169)
		              </li>
		              <li>
		                <a  href="#" >Austin, TX  </a> (156)
		              </li>
		              <li>
		                <a  href="#" >More ... </a> 
		              </li>
		            </ul>
		  		</div>
			</div>
		</div>
	</div>
	</div>
	</div>
@endsection