<div class="modal fade" id="userOTP" tabindex="-1" role="dialog" style="padding-top: 54px;">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title" style="color:#fff;">
					<i class="icon-mail-2"></i> OTP Verification
				</h4>
				
				<button type="button" class="close close-btn" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">{{ t('Close') }}</span>
				</button>
			</div>
			
			<form role="form" method="POST" action="/verify/otp" enctype="multipart/form-data" onSubmit="return optFormSubmit(this)">
				{!! csrf_field() !!}
				<div class="modal-body">

					@if (isset($errors) and $errors->any() and old('otp_phone')!='')
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


						$fromOtpError = (isset($errors) and $errors->has('otp')) ? ' is-invalid' : ''; ?>
						<div class="col-md-12">
							<div class="form-group required">
								<label for="phone" class="control-label">OTP
									 
										<sup>*</sup>
									
								</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span id="phoneCountry" class="input-group-text" style="height: 48px;"><i class="fa fa-phone"></i></span>
									</div>
									<input id="otp" name="otp" type="text" placeholder="OTP" maxlength="6" class="form-control{{ $fromOtpError }}" value="">

								</div>
								
								<div class="pull-right"><a   data-dismiss="modal" class="send_message_prev" data-toggle="modal">resend?</a></div>
							</div>
						</div>

					</div>
				</div>
				
				<div class="modal-footer">
					<input type="hidden" name="otp_phone" value="{{old('otp_phone',\Session::get('phone'))}}">
					<button type="button" class="btn btn-default close-btn catclose" data-dismiss="modal">{{ t('Cancel') }}</button>
					<button type="submit" class="btn btn-success pull-right" style="margin-right: 15px;">Verify</button>
				</div>
			</form>
			
		</div>
	</div>
</div>
 

<script type="text/javascript">
	function optFormSubmit(obj) {
 
    
		var form = $(obj);
	    $.ajax({
				method: 'POST',
			url: '{{ lurl('api/verify/otp') }}',
				data: {
					'otp': form.find('[name="otp"]').val(),
					'otp_phone': $('#signin_phone').val(),
					'_token': $('input[name=_token]').val()
				}
			}).done(function(data) {
				

				$.ajax({
					method: 'GET',
				url: '{{ lurl('/') }}',
					data: {
						'_type': 'header' 
					}
				}).done(function(data) {
					
					$('.main-header').replaceWith(data) 
					 
					
					 
				});

				$('#userOTP').removeClass('show');
				$('#userOTP').hide();
				$('.active_query').submit();


				
				 
			}).fail(function(response) {
				 
				 
				var responseJSON = response.responseJSON;

			if (responseJSON.code == 0) {
					alert("To many failed request, please try after 3 mins");
			} else if (responseJSON.code == 100) {
					
					alert("Invalid OTP");
			} else {
					var data = responseJSON.data;
				var msg = [];
				$.each(data, function(index, value) {
						
					if (msg.length == 0) {
						form.find('[name="' + index + '"]').focus()
						}
						msg.push(value);
					});

					alert(msg.join("\n"));
				}
				

				 
			});  

			return false;		 
	}
	$(".close-btn").click(function(){
                    $('#contactUser').removeClass('show');
                    $(".modal-backdrop").remove();
                    $('#contactUser').hide();
                    $('#userOTP').removeClass('show');

                    $('#userOTP').hide();
            });
</script>
<style>
	#userOTP .modal-dialog {
		width: auto;
		max-width: 366px;
	}

	.modal.fade .modal-dialog {
		transition: -webkit-transform .3s ease-out;
		transition: transform .3s ease-out;
		transition: transform .3s ease-out, -webkit-transform .3s ease-out;
		-webkit-transform: translate(0, -25%);
		transform: translate(0, -25%);
	}
 
	.modal-content {
		border: none;
		margin-top: 150px;
	}

	.modal-content {
		border: none;
	}

	.modal-content {
		position: relative;
		display: -ms-flexbox;
		display: flex;
		-ms-flex-direction: column;
		flex-direction: column;
		width: 100%;
		pointer-events: auto;
		background-color: #fff;
		background-clip: padding-box;
		border: 1px solid rgba(0, 0, 0, .2);
		border-radius: .3rem;
		outline: 0;
	}

	#userOTP .modal-header {
		background: #dc0002;
		border-color: #dc0002;
		color: #fff;
	}

	.modal-header {
		border-bottom: solid 1px #ddd;
		border-bottom-color: rgb(221, 221, 221);
		border-radius: 3px 3px 0 0;
		font-weight: 700;
		background: #f8f8f8;
		border-top: solid 1px #ddd;
		border-top-color: rgb(221, 221, 221);
		padding: 8px;
		position: relative;
		display: flex;
		-ms-flex-align: start;
		align-items: flex-start;
		-ms-flex-pack: justify;
		justify-content: space-between;
	}

	.modal-title {
		margin-bottom: 0;
		line-height: 1.5 !important;
		text-align: left;
		font-size: 17px;
	}
  
	#userOTP .input-group-text {
		color: #fff;
		background-color: #039eb5;
		border: 1px solid #039eb5;
	}

	.input-group-text {
		display: -ms-flexbox;
		display: flex;
		-ms-flex-align: center;
		align-items: center;
		padding: .675rem .75rem;
		font-size: 1rem;
		font-weight: 400;
		line-height: 1.5;
		color: #495057;
		text-align: center;
		background-color: #e9ecef;
		border: 1px solid #ced4da;
		border-radius: .25rem;
	}
	.pull-right {
  float: right;
}
.input-group {
  position: relative;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -ms-flex-align: stretch;
  align-items: stretch;
  width: 100%;
}
.skin-blue a {
  color: #000;
}
#userOTP .btn.btn-success.pull-right {
  background: #039eb5;
  border-color: #039eb5;
  height: 39px;
  padding-top: 0;
  padding-bottom: 0;
  line-height: normal;
  color: #fff;
}
#userOTP .btn.btn-default {
  background: #dc0002;
  border-color: #dc0002;
  height: 39px;
  padding-top: 0;
  padding-bottom: 0;
  line-height: normal;
  color: #fff;
}
.modal-footer{ border:none;}
#userOTP .close {
  color: #fff !important;
  opacity: 1;
}
</style>