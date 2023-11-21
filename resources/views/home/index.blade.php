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

@section('search')
	@parent
	@include('search.inc.form')
@endsection

@section('content')
	<div class="main-container" id="homepage">
		<!-- @include('search.inc.breadcrumbs') -->
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
@endsection

@section('modal_message')
	 
		@include('search.inc.compose-message')
		@include('search.inc.slider-message')
 
		
	 
@endsection

@section('after_scripts')
@endsection
<style>
.header-search .search-row .col-xs-12 button.btn-search.btn-block{
    background: #e9061b !important;
}
</style>
