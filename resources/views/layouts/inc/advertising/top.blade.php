<?php
$advertising = \App\Models\Advertising::where('slug', 'top')->first();
?>
@if (!empty($advertising))
	@include('home.inc.spacer')
	 
<div class="container">
		{!! $advertising->tracking_code_large !!}
</div>

@section('after_scripts')
	  346: <script src="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
	<script>
	$('.single-item').slick({
	    infinite: true,
		autoplay: true,
	    slidesToShow: 1,
	    slidesToScroll: 1,
		dots: true
	});
	</script>
@endsection
@endif