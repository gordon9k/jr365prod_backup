@if ($confirm_error = session('confirm_error'))
	<div class="alert alert-danger" role="alert"> 
		<strong>Oh snap!</strong> {{ $confirm_error }} 
	</div>
@endif

