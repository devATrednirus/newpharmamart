@extends('admin::auth.layout')

@section('content')
    
    <div class="login-box-body">
        <p class="login-box-msg">{{ trans('admin::messages.reset_password') }} eee</p>
        
        <form action="{{ admin_url('password/reset') }}" method="post">
            {!! csrf_field() !!}
    
            <input type="hidden" name="token" value="{{ @$token }}">
            
            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" name="email" value="{{ $email ?? old('email') }}" class="form-control" placeholder="{{ trans('admin::messages.email_address') }}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" name="password" class="form-control" placeholder="{{ trans('admin::messages.password') }}">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
    
            <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('admin::messages.confirm_password') }}">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>
    
            @if (config('settings.security.recaptcha_activation'))
                <!-- g-recaptcha-response -->
                <div class="form-group required <?php echo (isset($errors) and $errors->has('g-recaptcha-response')) ? ' is-invalid' : ''; ?>">
                    <div class="no-label">
                        {!! Recaptcha::render(['lang' => config('app.locale')]) !!}
                    </div>
	
					@if ($errors->has('g-recaptcha-response'))
						<span class="help-block">
                        	<strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                    	</span>
					@endif
                </div>
            @endif
            
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('admin::messages.reset_password') }}</button>
                </div>
                <!-- /.col -->
            </div>
            
        </form>
        
        <a href="{{ admin_url('login') }}">{{ trans('admin::messages.login') }}</a><br>
    
    </div>
    <!-- /.login-box-body -->
    
@endsection
