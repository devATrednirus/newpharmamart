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
	@include('pages.inc.contact-intro')
@endsection

@section('content')
	@include('common.spacer')
	<div class="main-container">
		<div class="container">
			<div class="row clearfix">
				
				 

		 
				
				<div class="col-md-12">
					<div class="contact-form">
						<section class="thanku-bg">
			<center>
			<h1 style="font-size: 22px;max-width: 654px;line-height: 35px;">Thank you for your query, our suppliers will contact you soon.</h1>
			<div id="timer"></div>
			</center>
        </section>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script type="text/javascript">
		var seconds = 10;
	 
		 

		function incrementSeconds() {
		    seconds -= 1;
		    
		    $('#timer').html("Redirecting you in " + seconds + " seconds.");
		    if(seconds<=0){

		    	 
		    	clearInterval(cancel);
		    	window.location.replace('{!! session()->get('redirect_to') !!}');
		    	 
		    }

		}
 
		var cancel = setInterval(incrementSeconds, 1000);
	 
	</script>

@endsection
