<?php $urlnotfound=str_replace('%20', '-', $_SERVER['REQUEST_URI']);
check404url($urlnotfound); 
?>
@extends('errors.layouts.master')

@section('title', t('Page not found', []))

@section('search')
	@parent
	@include('errors/layouts/inc/search')
@endsection

@section('content')
	@if (!(isset($paddingTopExists) and $paddingTopExists))
		<div class="h-spacer"></div>
	@endif
	<div class="main-container inner-page">
		<div class="container">
			<div class="section-content">
				<div class="row">

					<div class="col-md-12 page-content">
						<div class="error-page" style="margin: 100px 0;">
							<h2 class="headline text-center" style="font-size: 180px; float: none;"> 404</h2>
							<div class="text-center m-l-0" style="margin-top: 60px;">
								<h3 class="m-t-0"><i class="fa fa-warning"></i> :-( {{ t('Page not found') }} !</h3>
								<p>
									<?php
									$defaultErrorMessage = t('Meanwhile, you may <a href=":url">return to homepage</a>', ['url' => url('/')]);
									?>
									{!! isset($exception) ? ($exception->getMessage() ? $exception->getMessage() : $defaultErrorMessage) : $defaultErrorMessage !!}
								</p>
								<div id="timer"></div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- /.main-container -->
@endsection


@section('after_scripts')
	<script type="text/javascript">
		var seconds = 2;
	 

		function incrementSeconds() {
		    seconds -= 1;
		    
		    $('#timer').html("Redirecting you in " + seconds + " seconds.");
		    if(seconds<=0){

		    	clearInterval(cancel);
		    	window.location.href='/';
		    	return ;
		    }

		}

		var cancel = setInterval(incrementSeconds, 1000);
		 
	</script>

@endsection

