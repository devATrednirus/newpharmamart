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
@section('content')
<div class="main-container" style="background:url('https://www.pharmafranchisemart.com/images/base.jpg');padding: 62px 0px;background-size: contain;">
   <div class="container">
      <div class="row">
         @if (isset($errors) and $errors->any())
         <div class="col-xl-12">
            <div class="alert alert-danger">
               <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
               <h5><strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong></h5>
               <ul class="list list-check">
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
               </ul>
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
         <div class="col-md-3 reg-sidebar" >
            <div class="reg-sidebar-inner text-center" style="background: #0A3F98;padding: 22px;border-radius: 12px;color: #fff;padding-top: 56px;">
               <h4 style="font-family: 'Poppins';font-style: normal;font-weight: 600;font-size: 20px;line-height: 18px;letter-spacing: 0.5px;">Why Join Pharmafranchisemart?</h4>
               <p style="font-family: 'Poppins';
                  font-style: normal;
                  font-weight: normal !important;
                  font-size: 11px;
                  margin: 16px 0px;
                  line-height: 15px;">We're India's largest online B2B marketplace, connecting buyers with suppliers.</p>
               <div class="promo-text-box">
                   <img src="/images/office-building.png">
                  <h3 style=""><strong>10000+ Companies in India</strong></h3>
                  <p style="">
                    Start your Business with 10000+ companies in india.                  </p>
               </div>
               <div class="promo-text-box">
                  <img src="/images/hand.png">

                  <h3><strong>Buyer Connect Service</strong></h3>
                  <p>Get connect with Buyers through Buyer Connect Service.</p>
               </div>
               <div class="promo-text-box">
                   <img src="/images/team.png">
                  <h3><strong>Over 2000+ Buyers</strong></h3>
                  <p>Over 2000+ of Buyers are Registered with us.</p>
               </div>
            </div>
         </div>
         <div class="col-md-9 page-content">


            <div class="contact-form">
               <h4>List your Company Free at Pharmafranchisemart.com</h4>
                  <p>Just complete this simple form, It takes less than a minute!</p>
               @if (config('settings.social_auth.social_login_activation'))
               <div class="row mb-3 d-flex justify-content-center pl-3 pr-3">
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
                     <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-fb">
                        <a href="{{ lurl('auth/facebook') }}" class="btn-fb"><i class="icon-facebook"></i> {!! t('Connect with Facebook') !!}</a>
                     </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
                     <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-danger">
                        <a href="{{ lurl('auth/google') }}" class="btn-danger"><i class="icon-googleplus-rect"></i> {!! t('Connect with Google') !!}</a>
                     </div>
                  </div>
               </div>
               <div class="row d-flex justify-content-center loginOr">
                  <div class="col-xl-12 mb-1">
                     <hr class="hrOr">
                     <span class="spanOr rounded">{{ t('or') }}</span>
                  </div>
               </div>
               @endif
               <div class="row mt-5">
                  <div class="col-xl-12">
                     <form id="signupForm" class="form-horizontal" method="POST" action="{{ url()->current() }}">
                        {!! csrf_field() !!}
                        <fieldset>
                           <!-- name -->
                           <?php $nameError = (isset($errors) and $errors->has('name')) ? ' is-invalid' : ''; ?>
                           <div class="form-group row required">
                              <label class="col-md-4 col-form-label">Company Name <sup>*</sup></label>
                              <div class="col-md-6">
                                 <input name="name" placeholder="Company Name" class="form-control input-md{{ $nameError }}" type="text" value="{{ old('name') }}">
                              </div>
                           </div>
                           <!-- name -->
                           <?php $first_nameError = (isset($errors) and $errors->has('first_name')) ? ' is-invalid' : '';
                              $last_nameError = (isset($errors) and $errors->has('last_name')) ? ' is-invalid' : '';


                              ?>
                           <div class="form-group row required">
                              <label class="col-md-4 col-form-label">First Name <sup>*</sup></label>
                              <div class="col-md-6">
                                 <input name="first_name" placeholder="First Name" class="form-control input-md{{ $last_nameError }}" type="text" value="{{ old('first_name') }}">
                              </div>
                           </div>
                           <div class="form-group row required">
                              <label class="col-md-4 col-form-label">Last Name <sup>*</sup></label>
                              <div class="col-md-6">
                                 <input name="last_name" placeholder="Last Name" class="form-control input-md{{ $last_nameError }}" type="text" value="{{ old('last_name') }}">
                              </div>
                           </div>
                           <!-- country_code -->
                           @if (empty(config('country.code')))
                           <?php $countryCodeError = (isset($errors) and $errors->has('country_code')) ? ' is-invalid' : ''; ?>
                           <div class="form-group row required">
                              <label class="col-md-4 col-form-label{{ $countryCodeError }}" for="country_code">{{ t('Your Country') }} <sup>*</sup></label>
                              <div class="col-md-6">
                                 <select id="countryCode" name="country_code" class="form-control sselecter{{ $countryCodeError }}">
                                 <option value="0" {{ (!old('country_code') or old('country_code')==0) ? 'selected="selected"' : '' }}>{{ t('Select') }}</option>
                                 @foreach ($countries as $code => $item)
                                 <option value="{{ $code }}" {{ (old('country_code', (!empty(config('ipCountry.code'))) ? config('ipCountry.code') : 0)==$code) ? 'selected="selected"' : '' }}>
                                 {{ $item->get('name') }}
                                 </option>
                                 @endforeach
                                 </select>
                              </div>
                           </div>
                           @else
                           <input id="countryCode" name="country_code" type="hidden" value="{{ config('country.code') }}">
                           @endif
                           @if (isEnabledField('phone'))
                           <!-- phone -->
                           <?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
                           <div class="form-group row required">
                              <label class="col-md-4 col-form-label">{{ t('Phone') }}
                              @if (!isEnabledField('email'))
                              <sup>*</sup>
                              @endif
                              </label>
                              <div class="col-md-6">
                                 <div class="input-group">
                                    <div class="input-group-prepend">
                                       <span id="phoneCountry" class="input-group-text">{!! getPhoneIcon(old('country', config('country.code'))) !!}</span>
                                    </div>
                                    <input name="phone"
                                       placeholder="{{ (!isEnabledField('email')) ? t('Mobile Phone Number') : t('Phone Number') }}"
                                       class="form-control input-md{{ $phoneError }}"
                                       type="text"
                                       value="{{ old('phone') }}"
                                       >
                                    <div class="input-group-append tooltipHere" data-placement="top"
                                       data-toggle="tooltip"
                                       data-original-title="{{ t('Hide the phone number on the ads.') }}">
                                       <span class="input-group-text">
                                       <input name="phone_hidden" id="phoneHidden" type="checkbox"
                                       value="1" {{ (old('phone_hidden')=='1') ? 'checked="checked"' : '' }}>&nbsp;<small>{{ t('Hide') }}</small>
                                       </span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @endif
                           @if (isEnabledField('email'))
                           <!-- email -->
                           <?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
                           <div class="form-group row required">
                              <label class="col-md-4 col-form-label" for="email">{{ t('Email') }}
                              @if (!isEnabledField('phone'))
                              <sup>*</sup>
                              @endif
                              </label>
                              <div class="col-md-6">
                                 <div class="input-group">
                                    <div class="input-group-prepend">
                                       <span class="input-group-text"><i class="icon-mail"></i></span>
                                    </div>
                                    <input id="email"
                                       name="email"
                                       type="email"
                                       class="form-control{{ $emailError }}"
                                       placeholder="{{ t('Email') }}"
                                       value="{{ old('email') }}"
                                       >
                                    <div class="input-group-append tooltipHere" data-placement="top"
                                       data-toggle="tooltip"
                                       data-original-title="{{ t('Hide the email on the ads.') }}">
                                       <span class="input-group-text">
                                       <input name="phone_hidden" id="emailHidden" type="checkbox"
                                       value="1" {{ (old('email_hidden')=='1') ? 'checked="checked"' : '' }}>&nbsp;<small>{{ t('Hide') }}</small>
                                       </span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @endif
                           @if (isEnabledField('username'))
                           <!-- username -->
                           <?php $usernameError = (isset($errors) and $errors->has('username')) ? ' is-invalid' : ''; ?>
                           <div class="form-group row required">
                              <label class="col-md-4 col-form-label" for="email">{{ t('Username') }}</label>
                              <div class="col-md-6">
                                 <div class="input-group">
                                    <div class="input-group-prepend">
                                       <span class="input-group-text"><i class="icon-user"></i></span>
                                    </div>
                                    <input id="username"
                                       name="username"
                                       type="text"
                                       class="form-control{{ $usernameError }}"
                                       placeholder="{{ t('Username') }}"
                                       value="{{ old('username') }}"
                                       >
                                 </div>
                              </div>
                           </div>
                           @endif
                           <!-- password -->
                           <?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
                           <div class="form-group row required">
                              <label class="col-md-4 col-form-label" for="password">{{ t('Password') }} <sup>*</sup></label>
                              <div class="col-md-6">
                                 <input id="password" name="password" type="password" class="form-control{{ $passwordError }}"
                                    placeholder="{{ t('Password') }}">
                                 <br>
                                 <input id="password_confirmation" name="password_confirmation" type="password" class="form-control{{ $passwordError }}"
                                    placeholder="{{ t('Password Confirmation') }}">
                                 <small id="" class="form-text text-muted">{{ t('At least 5 characters') }}</small>
                              </div>
                           </div>
                           @if (config('settings.security.recaptcha_activation'))
                           <!-- recaptcha -->
                           <?php $recaptchaError = (isset($errors) and $errors->has('g-recaptcha-response')) ? 'is-invalid' : ''; ?>
                           <div class="form-group row required">
                              <label class="col-md-4 col-form-label{{ $recaptchaError }}" for="g-recaptcha-response"></label>
                              <div class="col-md-6">
                                 {!! Recaptcha::render(['lang' => config('app.locale')]) !!}
                              </div>
                           </div>
                           @endif
                           <!-- term -->
                           <?php $termError = (isset($errors) and $errors->has('term')) ? ' is-invalid' : ''; ?>
                           <div class="form-group row required">
                              <label class="col-md-4 col-form-label"></label>
                              <div class="col-md-6">
                                 <div class="form-check">
                                    <input name="term" id="term"
                                    class="form-check-input{{ $termError }}"
                                    value="1"
                                    type="checkbox" {{ (old('term')=='1') ? 'checked="checked"' : '' }}
                                    >
                                    <label class="form-check-label" for="invalidCheck3">
                                    {!! t('I have read and agree to the <a :attributes>Terms & Conditions</a>', ['attributes' => getUrlPageByType('terms')]) !!}
                                    </label>
                                 </div>
                                 <div style="clear:both"></div>
                              </div>
                           </div>
                           <!-- Button  -->
                           <div class="form-group row">
                              <label class="col-md-4 col-form-label"></label>
                              <div class="col-md-6">
                                 <button id="signupBtn" class="registrer"> {{ t('Register') }} </button>
                              </div>
                           </div>
                           <div class="mb-5"></div>
                        </fieldset>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
<style>
 .promo-text-box h3{
   font-family: 'Poppins';
font-style: normal;
font-weight: 600;
font-size: 15px;
line-height: 18px;
letter-spacing: 0.5px;
}

button#signupBtn {
    background: #0a3f98;
    border: navajowhite;
    color: #fff;
    padding: 11px 26px;
    font-size: 16px;
    font-family: 'Poppins';
    border-radius: 6px;
    /* width: 100%; */
}




.promo-text-box p{
 font-family: 'Poppins';
font-style: normal;
font-weight: 400;
font-size: 10px;
line-height: 18px;
text-align: center;
letter-spacing: 0.5px;
}
.contact-form {
    background: #FFFFFF;
    box-shadow: 0px 4px 7px rgb(0 0 0 / 35%);
    border-radius: 17px;
    padding: 50px 0px;
}
.contact-form p {
    font-family: 'Roboto';
    font-style: normal;
    font-weight: 400;
    font-size: 15px;
    line-height: 18px;
    text-align: center;
    letter-spacing: 0.5px;
    color: #000000;
}
.contact-form h4 {
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 600;
    font-size: 24px;
    text-align: center;
    line-height: 18px;
    letter-spacing: 0.5px;
    margin-bottom: 14px;
    color: #003C94;
}
.form-horizontal .col-form-label {
    font-weight: 600;
    font-size: 16px;
    color: #303030;
    font-family: 'Poppins';
    font-weight: bold !important;
}
.promo-text-box {
    padding: 15px 20px;
    margin-top: 28px;
}
label.form-check-label {
    font-family: 'Poppins';
}
::placeholder {
    font-size: 15px;
    font-family: 'Poppins';
    font-weight: 700;
}
</style>
@section('after_scripts')
<script>
   $(document).ready(function () {
   	/* Submit Form */
   	$("#signupBtn").click(function () {
   		$("#signupForm").submit();
   		return false;
   	});
   });
</script>
@endsection
