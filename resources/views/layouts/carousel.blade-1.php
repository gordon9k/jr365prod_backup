@section('carousel11')
<div class="carousel carousel-slider center" data-indicators="true">
 @php $img = URL::to('/')."/uploads/company_logo/C.png"; @endphp
 <a class="carousel-fixed-item center"  href=""><img style="border:3px solid #a5d6a7; border-radius:5px;" src="{{ $img }}"></a>
  @php $img1 = URL::to('/')."/uploads/company_logo/C-2.png"; @endphp
 <a class="carousel-fixed-item center"  href=""><img style="border:3px solid #a5d6a7; border-radius:5px;" src="{{ $img1 }}"></a> 		
</div>
@endsection
