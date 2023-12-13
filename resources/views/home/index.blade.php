{{--
 * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from Codecanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
--}}
@extends('layouts.master')

{{-- @section('search')
	@parent
	@include('search.inc.form')
@endsection --}}

@section('content')
	<div class="main-container" id="homepage">
		{{-- @include('search.inc.breadcrumbs')  --}}
		@if (Session::has('flash_notification'))

			<?php $paddingTopExists = true; ?>
			<div class="container">
				<div class="row">
					<div class="col-xl-12">
						@include('flash::message')
					</div>
				</div>
			</div>
		@endif
		@include('home.inc.banner')



		@if (isset($sections) and $sections->count() > 0)
			@foreach($sections as $section)
				@if (view()->exists($section->view))

					@include($section->view, ['firstSection' => $loop->first])
				@endif
			@endforeach
		@endif



	</div>
  @include('modalbutton')
  	@include('whatsappbutton')
@endsection

@section('modal_message')

		@include('search.inc.compose-message')
		@include('search.inc.slider-message')



@endsection


@yield('loginotpslide')
@section('after_scripts')
  <script>
    //document.location = 'http://mart.redniruscare.com/category/pharmaceutical-eye-ear-drops';
  </script>
<script src="/assets/js/popper.min.js"></script>
  <!-- <script src="/assets/js/jquery.js"></script>

  <script src="/assets/js/bootstrap.min.js"></script>
  <script src="/assets/js/plugins.js"></script>
  <script src="/assets/js/owl.js"></script>
  <script src="/assets/js/wow.js"></script>
  <script src="/assets/js/validation.js"></script>
  <script src="/assets/js/jquery.fancybox.js"></script>
  <script src="/assets/js/appear.js"></script>
  <script src="/assets/js/scrollbar.js"></script>
  <script src="/assets/js/isotope.js"></script>
  <script src="/assets/js/jquery.nice-select.min.js"></script>
  <script src="/assets/js/jquery-ui.js"></script>
  <script src="/assets/js/parallax-scroll.js"></script>  --->

  <!-- main-js -->
  <!-- <script src="/assets/js/script.js"></script> --->
  <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
  <script src="/home/plugins/jquery.min.js"></script>
  <script src="/home/plugins/popper.min.js"></script>
  <script src="/home/plugins/bootstrap4/js/bootstrap.min.js"></script>
  <script src="/home/plugins/select2/dist/js/select2.full.min.js"></script>
  <script src="/home/plugins/owl-carousel/owl.carousel.min.js"></script>
  <script src="/home/plugins/jquery-bar-rating/dist/jquery.barrating.min.js"></script>
  <script src="/home/plugins/lightGallery/dist/js/lightgallery-all.min.js"></script>
  <script src="/home/plugins/slick/slick/slick.min.js"></script>
  <script src="/home/plugins/noUiSlider/nouislider.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.js"></script>

  <!-- custom code-->
  <script src="/home/js/main.js"></script>
  <style>
  .header-search .search-row .col-xs-12 button.btn-search.btn-block{
      background: #e9061b !important;
  }
  </style>
@endsection
