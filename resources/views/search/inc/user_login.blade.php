<div class="modal fade pk-login" id="userLogin" tabindex="-1" role="dialog">
	search\inc\user_login.blade.php
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<h4 class="modal-title" style="color:#fff;">
					<i class="icon-mail-2"></i> Get Quotes From Multiple Suppliers
				</h4>

				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">{{ t('Close') }}</span>
				</button>
			</div>

			<form role="form" method="POST" action="/user/signin" enctype="multipart/form-data" onSubmit="return user_login_quick_form(this)">
				@csrf
				<div class="modal-body">

					@if (isset($errors) and $errors->any() and old('quickSignForm')=='1')
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<ul class="list list-check">
								@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<div class="row">


							 <!-- from_phone -->
						<?php
					//	dd(\Session::has('otp'));

						$fromSignInPhoneError = (isset($errors) and $errors->has('signin_phone')) ? ' is-invalid' : ''; ?>
						<div class="col-md-12">
							<div class="form-group required">
								<label for="phone" class="control-label">Sign in / Verify Mobile Number
									@if (!isEnabledField('phone'))
										<sup>*</sup>
									@endif
								</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span id="phoneCountry" class="input-group-text" style="height: 48px;"><i class="fa fa-phone"></i></span>
									</div>
									<input id="signin_phone"
										   name="signin_phone"
										   type="text"
										   placeholder="Mobile Number"
										   maxlength="60"
										   class="form-control{{ $fromSignInPhoneError }}"
										   value="{{ old('signin_phone',old('otp_phone',\Session::get('phone')))}}"
									>

								</div>

							</div>
						</div>
						<div class="col-md-12 with_username">
							<div class="form-group pull-right">
								<a href="#quickLogin" class="nav-link quickLogin" onclick="$('#userLogin').modal('hide')" data-toggle="modal">Login with username and password? </a>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<input type="hidden" id="signin_name" name="signin_name" value="">
					<input type="hidden" name="quickSignForm" value="1">
					<button type="button" class="btn btn-default close catclose" style="padding:10px 10px" data-dismiss="modal">{{ t('Cancel') }}</button>
					<button type="submit" class="btn btn-success pull-right" style="margin-right:15px">Get OTP</button>
				</div>
			</form>

		</div>
	</div>
</div>

<script type="text/javascript">

	function user_login_quick_form(obj) {



		    	var form =$(obj);
			    $.ajax({
						method: 'POST',
						url: '{{ lurl('api/user/signin') }}',
						data: {
							'signin_phone': form.find('[name="signin_phone"]').val(),
							'signin_name': form.find('[name="signin_name"]').val(),
							'_token': $('input[name=_token]').val()
						}
					}).done(function(data) {

						$('[name="quick_query_phone"]').val(form.find('[name="signin_phone"]').val());

<?php if(preg_match('/pharmafranchisemart.com/',$_SERVER['SERVER_NAME']) || preg_match('/redniruscare.com/',$_SERVER['SERVER_NAME'])){ ?>
						window.dataLayer =window.dataLayer || [];

						if(window.dataLayer[window.dataLayer.length-1]['gtm.elementClasses']){

							window.dataLayer.push({
								'event':'optInitiated','conversionValue':1
							});

						}
<?php } ?>
						$("#contactUser").hide();
						$('#userLogin').hide();
						$('#userLogin').removeClass('show');
						$('#userOTP').show();
						$('#userOTP').addClass('show');



					}).fail(function(response) {


						var responseJSON = response.responseJSON;

						if(responseJSON.code==0){
							alert("To many failed request, please try after 3 mins");
						}
						else{
							var data = responseJSON.data;
							var msg=[];
							$.each(data, function (index, value) {

								if(msg.length==0){
									form.find('[name="'+index+'"]').focus()
								}
								msg.push(value);
							});

							alert(msg.join("\n"));
						}



					});
					return false;
	}
</script>
