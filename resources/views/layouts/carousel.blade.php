@section('carousel')
<div class="carousel carousel-slider center" data-indicators="true">
Commercial Here
 @foreach ($company_list as $com)  
 @if($com->company_logo != '')
 @php $img = URL::to('/')."/uploads/company_logo/".$com->company_logo; @endphp
 <a class="carousel-item"  href=""><img style="border:3px solid #a5d6a7; border-radius:5px;" src="{{ $img }}"></a>
 @endif
 @endforeach 	
</div>
@endsection
