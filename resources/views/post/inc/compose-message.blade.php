<div class="modal fade" id="contactUser" tabindex="-1" role="dialog">
	<div class="modal-dialog" style="background: none !important;">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title">
					<i class="icon-mail-2"></i> {{ t('Contact advertiser') }}
				</h4>
				
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">{{ t('Close') }}</span>
				</button>
			</div>
			
			<form role="form" method="POST" action="{{ lurl('posts/' . $post->id . '/contact') }}" enctype="multipart/form-data">
				{!! csrf_field() !!}
				<div class="modal-body">

					@if (isset($errors) and $errors->any() and old('messageForm')=='1')
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
					@if (auth()->check())
						<input type="hidden" name="from_name" value="{{ auth()->user()->name }}">
						@if (!empty(auth()->user()->email))
							<input type="hidden" name="from_email" value="{{ auth()->user()->email }}">
						@else
							<!-- from_email -->
							<?php $fromEmailError = (isset($errors) and $errors->has('from_email')) ? ' is-invalid' : ''; ?>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="from_email" class="control-label">{{ t('E-mail') }}
										@if (!isEnabledField('phone'))
											<sup>*</sup>
										@endif
									</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="icon-mail"></i></span>
										</div>
										<input id="from_email" name="from_email" type="text" placeholder="{{ t('i.e. you@gmail.com') }}"
											   class="form-control{{ $fromEmailError }}" value="{{ old('from_email', auth()->user()->email) }}">
									</div>
								</div>
							</div>
						@endif
					@else
						 <!-- from_name -->
							<?php $fromNameError = (isset($errors) and $errors->has('from_name')) ? ' is-invalid' : ''; ?>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="from_name" class="control-label">{{ t('Name') }} <sup>*</sup></label>
									<div class="input-group">
										<input id="from_name"
											   name="from_name"
											   class="form-control{{ $fromNameError }}"
											   placeholder="{{ t('Your name') }}"
											   type="text"
											   value="{{ old('from_name') }}"
										>
									</div>
								</div>
							</div>
								
							<!-- from_email -->
							<?php $fromEmailError = (isset($errors) and $errors->has('from_email')) ? ' is-invalid' : ''; ?>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="from_email" class="control-label">{{ t('E-mail') }}
										@if (!isEnabledField('email'))
											<sup>*</sup>
										@endif
									</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="icon-mail"></i></span>
										</div>
										<input id="from_email" name="from_email" type="text" placeholder="{{ t('i.e. you@gmail.com') }}"
											   class="form-control{{ $fromEmailError }}" value="{{ old('from_email') }}">
									</div>
								</div>
							</div>
							@endif
						
						<!-- from_phone -->
						<?php $fromPhoneError = (isset($errors) and $errors->has('from_phone')) ? ' is-invalid' : ''; ?>
						<div class="col-md-6">
							<div class="form-group required">
								<label for="phone" class="control-label">{{ t('Phone Number') }}
									@if (!isEnabledField('phone'))
										<sup>*</sup>
									@endif
								</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span id="phoneCountry" class="input-group-text"><i class="icon-phone-1"></i></span>
									</div>
									<input id="from_phone"
										   name="from_phone"
										   type="text"
										   placeholder="{{ t('Phone Number') }}"
										   maxlength="60"
										   class="form-control{{ $fromPhoneError }}"
										   value="{{ old('from_phone', (auth()->check()) ? auth()->user()->phone : '') }}"
									>
								</div>
							</div>
						</div>

						<!-- location -->
							<?php $locationError = (isset($errors) and $errors->has('location')) ? ' is-invalid' : ''; ?>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="location" class="control-label">Location For Franchise <sup>*</sup></label>
									<div class="input-group">
										<input id="location"
											   name="location"
											   class="form-control{{ $locationError }}"
											   placeholder="Location For Franchise"
											   type="text"
											   value="{{ old('location') }}"
										>
									</div>
								</div>
							</div>

						<!-- location -->
							<?php $addressError = (isset($errors) and $errors->has('address')) ? ' is-invalid' : ''; ?>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="address" class="control-label">Address <sup>*</sup></label>
									<div class="input-group">
										<input id="address"
											   name="address"
											   class="form-control{{ $addressError }}"
											   placeholder="Address"
											   type="text"
											   value="{{ old('address') }}"
										>
									</div>
								</div>
							</div>

						<!-- location -->
							<?php $cityError = (isset($errors) and $errors->has('city')) ? ' is-invalid' : ''; ?>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="city" class="control-label">City <sup>*</sup></label>
									<div class="input-group">
										<input id="city"
											   name="city"
											   class="form-control{{ $cityError }}"
											   placeholder="City"
											   type="text"
											   value="{{ old('city') }}"
										>
									</div>
								</div>
							</div>

						<!-- location -->
							<?php $pincodeError = (isset($errors) and $errors->has('pincode')) ? ' is-invalid' : ''; ?>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="pincode" class="control-label">Pincode <sup>*</sup></label>
									<div class="input-group">
										<input id="pincode"
											   name="pincode"
											   class="form-control{{ $pincodeError }}"
											   placeholder="City"
											   type="text"
											   value="{{ old('pincode') }}"
										>
									</div>
								</div>
							</div>

							<?php $drugs_licenseError = (isset($errors) and $errors->has('drugs_license')) ? ' is-invalid' : ''; ?>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="drugs_license" class="control-label">Do You Have Drugs License? <sup>*</sup></label>
									<div class="input-group">
										<select id="drugs_license"
											   name="drugs_license"
											   class="form-control{{ $drugs_licenseError }}">

										<option value="">--Select--</option>
										<option value="Yes" <?=(old('drugs_license')=="Yes"?"selected":"")?>>Yes</option>
										<option value="No" <?=(old('drugs_license')=="No"?"selected":"")?>>No</option> 

									</select>
									</div>
								</div>
							</div>

							<?php $have_gst_numberError = (isset($errors) and $errors->has('have_gst_number')) ? ' is-invalid' : ''; ?>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="have_gst_number" class="control-label">Do You Have GST Number? <sup>*</sup></label>
									<div class="input-group">
										<select id="have_gst_number"
											   name="have_gst_number"
											   class="form-control{{ $have_gst_numberError }}">

										<option value="">--Select--</option>
										<option value="Yes" <?=(old('have_gst_number')=="Yes"?"selected":"")?>>Yes</option>
										<option value="No" <?=(old('have_gst_number')=="No"?"selected":"")?>>No</option> 

									</select>
									</div>
								</div>
							</div>

							<?php $minimum_investmentError = (isset($errors) and $errors->has('minimum_investment')) ? ' is-invalid' : ''; ?>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="minimum_investment" class="control-label">Minimum Investment<sup>*</sup></label>
									<div class="input-group">
										<select id="minimum_investment"
											   name="minimum_investment"
											   class="form-control{{ $minimum_investmentError }}">

										<option value="">--Select--</option>
										<option value="5000 Rs to 25000 Rs" <?=(old('minimum_investment')=="5000 Rs to 25000 Rs"?"selected":"")?>>5000 Rs to 25000 Rs</option>
										<option value="25000 Rs to 50000 Rs" <?=(old('minimum_investment')=="25000 Rs to 50000 Rs"?"selected":"")?>>25000 Rs to 50000 Rs</option> 
										<option value="Above 50000 Rs" <?=(old('minimum_investment')=="Above 50000 Rs"?"selected":"")?>>Above 50000 Rs</option> 

									</select>
									</div>
								</div>
							</div>

							<?php $purchase_periodError = (isset($errors) and $errors->has('purchase_period')) ? ' is-invalid' : ''; ?>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="purchase_period" class="control-label">Purchase Period<sup>*</sup></label>
									<div class="input-group">
										<select id="purchase_period"
											   name="purchase_period"
											   class="form-control{{ $purchase_periodError }}">

										<option value="">--Select--</option>
										<option value="1 Days - 15 Days" <?=(old('purchase_period')=="1 Days - 15 Days"?"selected":"")?>>1 Days - 15 Days</option>
										<option value="16 Days - 30 Days" <?=(old('purchase_period')=="16 Days - 30 Days"?"selected":"")?>>16 Days - 30 Days</option> 
										<option value="More Than 30 Days" <?=(old('purchase_period')=="More Than 30 Days"?"selected":"")?>>More Than 30 Days</option> 

									</select>
									</div>
								</div>
							</div>

							<?php $call_back_timeError = (isset($errors) and $errors->has('call_back_time')) ? ' is-invalid' : ''; ?>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="call_back_time" class="control-label">Call Back Time<sup>*</sup></label>
									<div class="input-group">
										<select id="call_back_time"
											   name="call_back_time"
											   class="form-control{{ $call_back_timeError }}">

										<option value="">--Select--</option>
										<option value="10 AM - 12 Noon" <?=(old('call_back_time')=="10 AM - 12 Noon"?"selected":"")?>>10 AM - 12 Noon</option>
										<option value="12 Noon - 2 PM" <?=(old('call_back_time')=="12 Noon - 2 PM"?"selected":"")?>>12 Noon - 2 PM</option> 
										<option value="2 PM - 4 PM" <?=(old('call_back_time')=="2 PM - 4 PM"?"selected":"")?>>2 PM - 4 PM</option>
										<option value="4 PM - 6 PM" <?=(old('call_back_time')=="4 PM - 6 PM"?"selected":"")?>>4 PM - 6 PM</option> 
										<option value="After 6 PM" <?=(old('call_back_time')=="After 6 PM"?"selected":"")?>>After 6 PM</option>
										<option value="Any Time" <?=(old('call_back_time')=="Any Time"?"selected":"")?>>Any Time</option> 

									</select>
									</div>
								</div>
							</div>

							<?php $professionError = (isset($errors) and $errors->has('profession')) ? ' is-invalid' : ''; ?>
							<div class="col-md-6">
								<div class="form-group required">
									<label for="profession" class="control-label">Profession <sup>*</sup></label>
									<div class="input-group">
										<select id="profession"
											   name="profession"
											   class="form-control{{ $professionError }}">

										<option value="">--Select--</option>
										<option value="Student" <?=(old('profession')=="Student"?"selected":"")?>>Student</option>
										<option value="Retailer" <?=(old('profession')=="Retailer"?"selected":"")?>>Retailer</option> 
										<option value="Doctor" <?=(old('profession')=="Doctor"?"selected":"")?>>Doctor</option>
										<option value="Distributer" <?=(old('profession')=="Distributer"?"selected":"")?>>Distributer</option> 
										<option value="Wholesaler" <?=(old('profession')=="Wholesaler"?"selected":"")?>>Wholesaler</option>
										<option value="Medical Rap" <?=(old('profession')=="Medical Rap"?"selected":"")?>>Medical Rap</option> 

									</select>
									</div>
								</div>
							</div>


						
					
					<!-- message -->
					<?php $messageError = (isset($errors) and $errors->has('message')) ? ' is-invalid' : ''; ?>
					<div class="col-md-12">
						<div class="form-group required">
							<label for="message" class="control-label">
								{{ t('Message') }} <span class="text-count">(500 max)</span> <sup>*</sup>
							</label>
							<textarea id="message"
									  name="message"
									  class="form-control required{{ $messageError }}"
									  placeholder="{{ t('Your message here...') }}"
									  rows="5"
							>{{ old('message') }}</textarea>
						</div>
					</div>



					@if (isset($parentCat) and isset($parentCat->type) and in_array($parentCat->type, ['job-offer']))
						<!-- filename -->
						<?php $filenameError = (isset($errors) and $errors->has('filename')) ? ' is-invalid' : ''; ?>
						<div class="form-group required" {!! (config('lang.direction')=='rtl') ? 'dir="rtl"' : '' !!}>
							<label for="filename" class="control-label{{ $filenameError }}">{{ t('Resume') }} </label>
							<input id="filename" name="filename" type="file" class="file{{ $filenameError }}">
							<small id="" class="form-text text-muted">
								{{ t('File types: :file_types', ['file_types' => showValidFileTypes('file')]) }}
							</small>
						</div>
						<input type="hidden" name="parentCatType" value="{{ $parentCat->type }}">
					@endif
					
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
					
					<input type="hidden" name="country_code" value="{{ config('country.code') }}">
 					<input type="hidden" name="post_id" id="post_id" value="{{$post->id }}">
					<input type="hidden" name="messageForm" value="1">
				</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{{ t('Cancel') }}</button>
					<button type="submit" class="btn btn-success pull-right">{{ t('Send message') }}</button>
				</div>
			</form>
			
		</div>
	</div>
</div>

@section('after_styles')
	@parent
	<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
	@if (config('lang.direction') == 'rtl')
		<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
	@endif
	<style>
		.krajee-default.file-preview-frame:hover:not(.file-preview-error) {
			box-shadow: 0 0 5px 0 #666666;
		}
	</style>
@endsection

@section('after_scripts')
    @parent
	
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
	@if (file_exists(public_path() . '/assets/plugins/bootstrap-fileinput/js/locales/'.ietfLangTag(config('app.locale')).'.js'))
		<script src="{{ url('assets/plugins/bootstrap-fileinput/js/locales/'.ietfLangTag(config('app.locale')).'.js') }}" type="text/javascript"></script>
	@endif

	<script>
		/* Initialize with defaults (Resume) */
		$('#filename').fileinput(
		{
			theme: "fa",
            language: '{{ config('app.locale') }}',
			@if (config('lang.direction') == 'rtl')
				rtl: true,
			@endif
			showPreview: false,
			allowedFileExtensions: {!! getUploadFileTypes('file', true) !!},
			showUpload: false,
			showRemove: false,
			maxFileSize: {{ (int)config('settings.upload.max_file_size', 1000) }}
		});
	</script>
	<script>
		$(document).ready(function () {

			$(document).on('click', '.send_message', function(){
				var url = "{{ lurl('posts') }}/"+$(this).data('id')+"/contact";
				$('#post_id').val($(this).data('id'));
				 
			    $('#contactUser form').attr('action',url);
			});

			@if ($errors->any())
				@if ($errors->any() and old('messageForm')=='1')
					var url = "{{ lurl('posts') }}/{{old('post_id')}}/contact";
					$('#post_id').val("{{old('post_id')}}");
				    $('#contactUser form').attr('action',url);
					$('#contactUser').modal();
				@endif
			@endif
		});
	</script>
@endsection
