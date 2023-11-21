<div class="modal fade pk-login" id="quickLogin" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			
			<div class="modal-header" style="background:#0A3F98">
				<h4 class="modal-title"><i class="icon-login fa"></i> {{ t('Log In') }} </h4>
				
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">{{ t('Close') }}</span>
				</button>
			</div>
			
			<form role="form" method="POST" action="{{ lurl(trans('routes.login')) }}">
				{!! csrf_field() !!}
				<div class="modal-body">

					@if (isset($errors) and $errors->any() and old('quickLoginForm')=='1')
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<ul class="list list-check">
								@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					@if (config('settings.social_auth.social_login_activation'))
						<div class="row mb-3 d-flex justify-content-center pl-2 pr-2">
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
								<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-fb">
									<a href="{{ lurl('auth/facebook') }}" class="btn-fb"><i class="icon-facebook"></i> Facebook</a>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
								<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-danger">
									<a href="{{ lurl('auth/google') }}" class="btn-danger"><i class="icon-googleplus-rect"></i> Google</a>
								</div>
							</div>
						</div>
					@endif
					
					<?php
						$loginValue = (session()->has('login')) ? session('login') : old('login');
						$loginField = getLoginField($loginValue);
						 
					?>
					<!-- login -->
					<?php $loginError = (isset($errors) and $errors->has('login')) ? ' is-invalid' : ''; ?>
					<div class="form-group">
						<label for="login" class="control-label">{{ t('Login') . ' (' . getLoginLabel() . ')' }}</label>
						<div class="input-icon">
							<i class="icon-user fa"></i>
							<input id="mLogin" name="login" type="text" placeholder="{{ getLoginLabel() }}" class="form-control{{ $loginError }}" value="">
						</div>
					</div>
					
					<!-- password -->
					<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
					<div class="form-group">
						<label for="password" class="control-label">{{ t('Password') }}</label>
						<div class="input-icon">
							<i class="icon-lock fa"></i>
							<input id="mPassword" name="password" type="password" class="form-control{{ $passwordError }}" placeholder="{{ t('Password') }}" autocomplete="off">
						</div>
					</div>
					
					<!-- remember -->
					<?php $rememberError = (isset($errors) and $errors->has('remember')) ? ' is-invalid' : ''; ?>
					<div class="form-group">
						<label class="checkbox form-check-label pull-left mt-2" style="font-weight: normal;">
							<input type="checkbox" value="1" name="remember" id="mRemember" class="{{ $rememberError }}"> {{ t('Keep me logged in') }}
						</label>
						<p class="pull-right mt-2">
							<a href="{{ lurl('password/reset') }}"> {{ t('Lost your password?') }} </a> / <a href="{{ lurl(trans('routes.register')) }}">{{ t('Register') }}</a>
						</p>
						<div style=" clear:both"></div>
					</div>

					<div class="form-group">
						<p class="pull-right mt-2">
							<a href="#userLogin" class="nav-link login-nav" onclick="$('#quickLogin').modal('hide')" data-toggle="modal">Login with mobile? </a>
						</p>
						<div style=" clear:both"></div>
					</div>
					
					@if (config('settings.security.recaptcha_activation'))
						<!-- recaptcha -->
						<?php $recaptchaError = (isset($errors) and $errors->has('g-recaptcha-response')) ? ' is-invalid' : ''; ?>
						<div class="form-group required">
							<label class="control-label" for="g-recaptcha-response">{{ t('We do not like robots') }}</label>
							<div>
								{!! Recaptcha::render(['lang' => config('app.locale')]) !!}
							</div>
						</div>
					@endif
					
					 
					
						 
					<input type="hidden" name="quickLoginForm" value="1">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default close catclose" style="padding:10px 10px" data-dismiss="modal">{{ t('Cancel') }}</button>
					<button type="submit" class="btn btn-success pull-right" style="margin-right:15px">{{ t('Log In') }}</button>
				</div>
			</form>
			
		</div>
	</div>
</div>
