
@extends('admin::auth.layout')

@section('content')
	
	<div class="login-box-body" style="background: #d2d6de;">
		<div class="">
			<div class="">

				@if (isset($errors) and $errors->any())
					<div class="col-xl-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<ul class="list list-check">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					</div>
				@endif

				@if (session('status'))
					<div class="col-xl-12">
						<div class="alert alert-success">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p>{{ session('status') }}</p>
						</div>
					</div>
				@endif

				@if (session('email'))
					<div class="col-xl-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p>{{ session('email') }}</p>
						</div>
					</div>
				@endif
					
				@if (session('phone'))
					<div class="col-xl-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p>{{ session('phone') }}</p>
						</div>
					</div>
				@endif
					
				@if (session('login'))
					<div class="col-xl-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p>{{ session('login') }}</p>
						</div>
					</div>
				@endif

				@if (Session::has('flash_notification'))
					<div class="col-xl-12">
						<div class="row">
							<div class="col-xl-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif

				<div class="col-lg-5 col-md-8 col-sm-10 col-xs-12 login-box">
					<div class="card card-default">
						<div class="panel-intro text-center">
							<h2 class="logo-title">
								<span class="logo-icon"> </span> {{ t('Password') }} <span> </span>
							</h2>
						</div>
						
						<div class="card-body">
							<form id="pwdForm" role="form" method="POST" action="{{ admin_url('password/emailaddress') }}">
								{!! csrf_field() !!}
								
								<!-- login -->
								<?php $loginError = (isset($errors) and $errors->has('login')) ? ' is-invalid' : ''; ?>
								<div class="form-group">
									<label for="login" class="col-form-label">{{ t('Login') . ' (' . getLoginLabel() . ')' }}:</label>
									<div class="input-icon">
										<i class="icon-user fa"></i>
										<input id="email"
											   name="email"
											   type="text"
											   placeholder="{{ getLoginLabel() }}"
											   class="form-control{{ $loginError }}"
											   value="{{ old('login') }}"
										>
									</div>
								</div>
								
								@if (config('settings.security.recaptcha_activation'))
									<!-- recaptcha -->
									<?php $recaptchaError = (isset($errors) and $errors->has('g-recaptcha-response')) ? ' is-invalid' : ''; ?>
									<div class="form-group required">
										<div class="no-label">
											{!! Recaptcha::render(['lang' => config('app.locale')]) !!}
										</div>
									</div>
								@endif
								
								<!-- Submit -->
								<div class="form-group">
									<button id="pwdBtn" type="submit" class="btn btn-primary btn-lg btn-block">{{ t('Submit') }}</button>
								</div>
							</form>
						</div>
						
						<div class="card-footer text-center">
							<a href="{{ admin_url(trans('login')) }}">Login </a>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script>
		$(document).ready(function () {
			$("#pwdBtn").click(function () {
				$("#pwdForm").submit();
				return false;
			});
		});
	</script>
@endsection