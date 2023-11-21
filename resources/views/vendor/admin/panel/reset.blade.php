@extends('admin::auth.layout')

@section('content')
	
	@if (isset($errors) and $errors->any())
		<div class="col-xl-12 m-t-15">
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				@foreach ($errors->all() as $error)
					{{ $error }}<br>
				@endforeach
			</div>
		</div>
	@endif
    
    <div class="login-box-body">
        <p class="login-box-msg">Reset Password</p>
        
        <form action="{{ admin_url('login') }}" method="post">
            {!! csrf_field() !!}
            
            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="{{ trans('admin::messages.email_address') }}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" name="password" id="password" class="form-control" placeholder="{{ trans('admin::messages.password') }}">
             
    	<i class="fa fa-eye" id="eye" onclick="get_open()" style="float: right; margin-top: -25px; margin-right:9px; cursor: pointer;"></i>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
    
                        
            
            
        </form>
        
        
    </div>
    <!-- /.login-box-body -->
    <script>
	function get_open()
		{
		    //alert('ok');
		    $("#password").prop("type", "text");
		    $("#eye").attr('class', 'fa fa-eye-slash');
		    $("#eye").attr("onclick","get_close()");
		}
			function get_close()
		{
		    //alert('ok');
		    $("#password").prop("type", "password");
		    $("#eye").attr('class', 'fa fa-eye');
		    $("#eye").attr("onclick","get_open()");
		}
	</script>
@endsection
