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
	@include('common.spacer')
	<div class="main-container">
		<div class="container">
			<div class="row">
				<div class="col-md-3 page-sidebar" >
					@include('account.inc.sidebar')
				</div>
				<!--/.page-sidebar-->

				<div class="col-md-9 page-content">

					@include('flash::message')

					@if (isset($errors) and $errors->any())
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h5><strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong></h5>
							<ul class="list list-check">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<div id="avatarUploadError" class="center-block" style="width:100%; display:none"></div>
					<div id="avatarUploadSuccess" class="alert alert-success fade show" style="display:none;"></div>
					@if(auth()->user()->user_type_id!="2")
					<div class="inner-box default-inner-box">
						<div class="row">


							<div class="col-md-4 col-xxs-12">
                                        <div class="box" style="background-color: #f2e6b5 !important;background-image: linear-gradient(191deg, #f2e6b5 0%, #f2a378 100%) !important;">
                                        <h3 class="no-padding text-center-480 useradmin" style="font-size: 17px;"><b>Current Package</b> :  @if($user->package) {{$user->package->name}} @endif
</h3>
                                       @if($user->subscription)
				                    <div style="font-size: 17px;"><b>Daily Limit</b> :
					                    @if($user->subscription->daily_send_limit > 0)


					                        {{$user->subscription->daily_send_limit}}
					                    @else
					                        {{$user->subscription->package->daily_send_limit}}
					                    @endif
					                    Leads
					                </div>
				                @endif
                                        <a class="btn btn-success" href="{{ lurl('user/payment') }}" style="border-radius: 35px; border-color:#6a1616;background-color: #6a1616;
"><i class="fa fa-pencil-square-o"></i> Upgrade Package</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xxs-12">
                                        <div class="box2" style="background-color: #FAACA8 !important;background-image: linear-gradient(19deg, #FAACA8 0%, #DDD6F3 100%) !important;">
                                        <div>
								<h3 class="no-padding text-center-480 useradmin " style="font-size: 17px;">
									<strong> Buy Leads</strong> :  {{$buy_leads}}
								</h3>

								@if($old_buy_leads)
								<h3 class="no-padding text-center-480 useradmin ">
									 Old Buy Leads:  <strong>{{$old_buy_leads->remaining}}</strong>
								</h3>
								@endif



									<a class="btn btn-success2" style="background-color: #2e3192;border-color:#2e3192;box-shadow:none" href="{{ lurl('user/buy-leads') }}"><i class="fa fa-pencil-square-o"></i> Get more Buy Leads</a>
								</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-8 col-xxs-12">
                                        <div class="box3" style="background-color: #c996f7 !important;background-image: linear-gradient(225deg, #c996f7 50%, #96c8ea 100%) !important;">
                                        <div class="header-data text-center-xs">
                                            <div class="hdata" >
                                                <div class="mcol-left"><i class="fas fa-envelope ln-shadow" style="background-color:#e70002"></i></div>
                                                <div class="mcol-right">
                                                    <p>
                                                        <a href="{{ lurl('account/conversations') }}">
													{{ isset($countConversations) ? \App\Helpers\Number::short($countConversations) : 0 }}
													<em>{{ trans_choice('global.count_mails', getPlural($countConversations)) }}</em>
												</a>
                                                    </p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="hdata">
                                                <div class="mcol-left"><i class="fa fa-eye ln-shadow" style="background-color:#e70002"></i></div>
                                                <div class="mcol-right">
                                                    <p>
                                                        <a href="{{ lurl('account/my-posts') }}">
													<?php $totalPostsVisits = (isset($countPostsVisits) and $countPostsVisits->total_visits) ? $countPostsVisits->total_visits : 0 ?>
													{{ \App\Helpers\Number::short($totalPostsVisits) }}
													<em>{{ trans_choice('global.count_visits', getPlural($totalPostsVisits)) }}</em>
												</a>
                                                    </p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="hdata">
                                                <div class="mcol-left"><i class="icon-th-thumb ln-shadow" style="background-color:#e70002"></i></div>
                                                <div class="mcol-right">
                                                    <p>
                                                        <a href="{{ lurl('account/my-posts') }}">
													{{ \App\Helpers\Number::short($countPosts) }}
													<em>{{ trans_choice('global.count_posts', getPlural($countPosts)) }}</em>
												</a>
                                                    </p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <!-- <div class="hdata">
                                                <div class="mcol-left"><i class="fa fa-user ln-shadow"></i></div>
                                                <div class="mcol-right">
                                                    <p>
                                                        <a href="https://dev.pharmafranchisemart.com/account/favourite"> 0 <em>favorite </em> </a>
                                                    </p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div> -->
                                        </div>
                                    </div>
                                    </div>
						</div>
					</div>

					@endif

					<div class="inner-box default-inner-box">

						<div class="welcome-msg">
							<h3 class="page-sub-header2 clearfix no-padding" style="font-size:22px; color:#2e3192;font-weight:bold">{{ t('Hello') }} {{ $user->name }} ! </h3>
							<span class="page-sub-header-sub small" style="font-size:14px">
                               <b > {{ t('You last logged in at') }}</b> : {{ $user->last_login_at->formatLocalized(config('settings.app.default_datetime_format')) }}
                            </span>
						</div>



	                <div class="row">
	                    <div class="col-md-12">
	                        <nav>
	                            <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
	                                <a class="nav-item nav-link active" id="contact-profile-tab" data-toggle="tab" href="#contact-profile" role="tab" aria-controls="contact-profile" aria-selected="true">Contact Profile</a>

	                                @if(auth()->user()->user_type_id!="2")
	                                <a class="nav-item nav-link" id="business-profile-tab" data-toggle="tab" href="#business-profile" role="tab" aria-controls="business-profile" aria-selected="false">Business Profile</a>
	                                <a class="nav-item nav-link" id="statutory-details-tab" data-toggle="tab" href="#statutory-details" role="tab" aria-controls="statutory-details" aria-selected="false">Statutory Details</a>
	                                <a class="nav-item nav-link" id="bank-details-tab" data-toggle="tab" href="#bank-details" role="tab" aria-controls="bank-details" aria-selected="false">Bank Details</a>
	                                <a class="nav-item nav-link" id="api-details-tab" data-toggle="tab" href="#api-details" role="tab" aria-controls="bank-details" aria-selected="false">API/Notifications</a>
	                                <a class="nav-item nav-link" id="location-preference-tab" data-toggle="tab" href="#location-preference" role="tab" aria-controls="bank-details" aria-selected="false">Location Preference</a>
	                                @endif
	                            </div>
	                        </nav>
	                        <div class="tab-content card" id="nav-tabContent" style="border:solid 1px #4682b4;">
	                            <div class="tab-pane fade show active " id="contact-profile" role="tabpanel" aria-labelledby="contact-profile-tab">
	                            	<div class="row">

	                            	 <div class="col-md-12">
	                            	 		<form name="details" class="form-horizontal" role="form" method="POST" action="{{ url()->current() }}">
											{!! csrf_field() !!}
											<input name="_method" type="hidden" value="PUT">
											<input name="panel" type="hidden" value="userPanel">

											<!-- gender_id -->
											<?php $genderIdError = (isset($errors) and $errors->has('gender_id')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required">
												<label class="col-md-3 col-form-label">{{ t('Gender') }}</label>
												<div class="col-md-9">
													@if ($genders->count() > 0)
                                                        @foreach ($genders as $gender)
															<div class="form-check form-check-inline pt-2">
																<input name="gender_id"
																	   id="gender_id-{{ $gender->tid }}"
																	   value="{{ $gender->tid }}"
																	   class="form-check-input{{ $genderIdError }}"
																	   type="radio" {{ (old('gender_id', $user->gender_id)==$gender->tid) ? 'checked="checked"' : '' }}
																>
																<label class="form-check-label" for="gender_id-{{ $gender->tid }}">
																	{{ $gender->name }}
																</label>
															</div>
                                                        @endforeach
													@endif
												</div>
											</div>

											<!-- name -->
											<?php $first_nameError = (isset($errors) and $errors->has('first_name')) ? ' is-invalid' : '';
												  $last_nameError = (isset($errors) and $errors->has('last_name')) ? ' is-invalid' : '';


											?>
											<div class="form-group row required">
												<label class="col-md-3 col-form-label">{{ t('Name') }} <sup>*</sup></label>
												<div class="col-md-9">
													<div class="row">
														<div class="col-md-6">
															<input name="first_name" type="text" class="form-control{{ $first_nameError }}" placeholder="First Name" value="{{ old('first_name', $user->first_name) }}">

														</div>
														<div class="col-md-6">

															<input name="last_name" type="text" class="form-control{{ $last_nameError }}" placeholder="Last Name" value="{{ old('last_name', $user->last_name) }}">
														</div>
													</div>
												</div>
											</div>



											<!-- email -->
											<?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required">
												<label class="col-md-3 col-form-label">{{ t('Email') }} <sup>*</sup></label>
												<div class="input-group col-md-9">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="icon-mail"></i></span>
													</div>

													<input id="email"
														   name="email"
														   type="email"
														   class="form-control{{ $emailError }}"
														   placeholder="{{ t('Email') }}"
														   value="{{ old('email', $user->email) }}"
													>
													@if (app('impersonate')->isImpersonating())
													<div class="input-group-append">
														<span class="input-group-text">
															<input name="email_hidden" id="emailHidden" type="checkbox"
																   value="1" {{ (old('email_hidden', $user->email_hidden)=='1') ? 'checked="checked"' : '' }}>&nbsp;
															<small>{{ t('Hide') }}</small>
														</span>
													</div>
													@endif
												</div>
											</div>

                                            <!-- country_code -->
                                            <?php
                                            /*
                                            <?php $countryCodeError = (isset($errors) and $errors->has('country_code')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required">
												<label class="col-md-3 control-label{{ $countryCodeError }}" for="country_code">
                                            		{{ t('Your Country') }} <sup>*</sup>
                                            	</label>
												<div class="col-md-9">
													<select name="country_code" class="form-control sselecter{{ $countryCodeError }}">
														<option value="0" {{ (!old('country_code') or old('country_code')==0) ? 'selected="selected"' : '' }}>
															{{ t('Select a country') }}
														</option>
														@foreach ($countries as $item)
															<option value="{{ $item->get('code') }}" {{ (old('country_code', $user->country_code)==$item->get('code')) ? 'selected="selected"' : '' }}>
																{{ $item->get('name') }}
															</option>
														@endforeach
													</select>
												</div>
											</div>
                                            */
                                            ?>
                                            <input name="country_code" type="hidden" value="{{ $user->country_code }}">

											<!-- phone -->
											<?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required">
												<label for="phone" class="col-md-3 col-form-label">{{ t('Phone') }} <sup>*</sup></label>
												<div class="input-group col-md-9">
													<div class="input-group-prepend">
														<span id="phoneCountry" class="input-group-text">{!! getPhoneIcon(old('country_code', $user->country_code)) !!}</span>
													</div>

													<input id="phone" name="phone" type="text" class="form-control{{ $phoneError }}"
														   placeholder="{{ (!isEnabledField('email')) ? t('Mobile Phone Number') : t('Phone Number') }}"
														   value="{{ old('phone', $user->phone) }}">
													@if (app('impersonate')->isImpersonating())
													<div class="input-group-append">
														<span class="input-group-text">
															<input name="phone_hidden" id="phoneHidden" type="checkbox"
																   value="1" {{ (old('phone_hidden', $user->phone_hidden)=='1') ? 'checked="checked"' : '' }}>&nbsp;
															<small>{{ t('Hide') }}</small>
														</span>
													</div>
													@endif
												</div>
											</div>

											<div class="form-group row">
												<div class="offset-md-3 col-md-9">

													<h3>Address</h3>
													<?php $address1Error = (isset($errors) and $errors->has('address1')) ? ' is-invalid' : '';
												  $address2Error = (isset($errors) and $errors->has('address2')) ? ' is-invalid' : '';

												  $cityIdError = (isset($errors) and $errors->has('city_id')) ? ' is-invalid' : '';
												  $pincodeError = (isset($errors) and $errors->has('pincode')) ? ' is-invalid' : '';

											 ?>
													<div class="form-group  row required">
														<div class="col">
															<label >Address 1 <sup>*</sup></label>

																<input name="address1" type="text" class="form-control{{ $address1Error }}" placeholder="" value="{{ old('address1', $user->address1) }}">

														</div>
														<div class="col">
															<label  >Address 2 </label>

																<input name="address2" type="text" class="form-control{{ $address2Error }}" placeholder="" value="{{ old('address2', $user->address2) }}">

														</div>
													</div>

													<div class="form-group  row required">
														<div class="col">
															<label >City <sup>*</sup></label>

																<select id="cityId" name="city_id" class="form-control sselecter{{ $cityIdError }}">
																	<option value="0" {{ (!old('city_id') or old('city_id')==0) ? 'selected="selected"' : '' }}>
																		{{ t('Select a city') }}
																	</option>
																	@foreach ($cities as $item)
																		<option value="{{ $item->id }}" {{ (old('city_id', $user->city_id)==$item->id) ? 'selected="selected"' : '' }}>
																			{{ $item->label }}
																		</option>
																	@endforeach
																</select>

														</div>
														<div class="col">
															<label  >State <sup>*</sup></label>
 																<input name="state" type="text" disabled="disabled" id="state" class="form-control" placeholder="" value="{{ old('state', $user->state) }}">

														</div>
													</div>

													<div class="form-group  row required">
														<div class="col">
															<label >Pincode </label>

																<input name="pincode" type="text" class="form-control{{ $pincodeError }}" placeholder="" value="{{ old('pincode', $user->pincode) }}">

														</div>
														 <div class="col">
														 </div>
													</div>

												</div>
											</div>

											<!-- Button -->
											<div class="form-group row">
												<div class="offset-md-3 col-md-9">
													<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
												</div>
											</div>
										</form>
	                            	 </div>
	                            	</div>

	                            	<!-- SETTINGS -->
	                            	<div id="accordion" class="panel-group">
									<div class="card card-default">
										<div class="card-header">
											<h4 class="card-title"><a href="#settingsPanel" data-toggle="collapse" data-parent="#accordion">{{ t('Settings') }}</a></h4>
										</div>
										<div class="panel-collapse collapse {{ (old('panel')=='settingsPanel') ? 'show' : '' }}" id="settingsPanel">
											<div class="card-body">
												<form name="settings" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/settings') }}">
													{!! csrf_field() !!}
													<input name="_method" type="hidden" value="PUT">
													<input name="panel" type="hidden" value="settingsPanel">

													@if (config('settings.single.activation_facebook_comments') and config('services.facebook.client_id'))
														<!-- disable_comments -->
														<div class="form-group row">
															<label class="col-md-3 col-form-label"></label>
															<div class="col-md-9">
																<div class="form-check form-check-inline pt-2">
																	<label>
																		<input id="disable_comments"
																			   name="disable_comments"
																			   value="1"
																			   type="checkbox" {{ ($user->disable_comments==1) ? 'checked' : '' }}
																		>
																		{{ t('Disable comments on my ads') }}
																	</label>
																</div>
															</div>
														</div>
													@endif

													<!-- password -->
													<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
													<div class="form-group row">
														<label class="col-md-3 col-form-label">{{ t('New Password') }}</label>
														<div class="col-md-9">
															<input id="password" name="password" type="password" class="form-control{{ $passwordError }}" placeholder="{{ t('Password') }}">
														</div>
													</div>

													<!-- password_confirmation -->
													<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
													<div class="form-group row">
														<label class="col-md-3 col-form-label">{{ t('Confirm Password') }}</label>
														<div class="col-md-9">
															<input id="password_confirmation" name="password_confirmation" type="password"
																   class="form-control{{ $passwordError }}" placeholder="{{ t('Confirm Password') }}">
														</div>
													</div>

													<!-- Button -->
													<div class="form-group row">
														<div class="offset-md-3 col-md-9">
															<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>

								</div>

	                            </div>


	                            <div class="tab-pane fade" id="business-profile" role="tabpanel" aria-labelledby="business-profile-tab">

	                                <div class="row">
	                            	 <div class="col-md-3">
                            	 		<label>
										 Company Logo
										</label>
										<form name="details" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/' . $user->id . '/photo') }}">
											<div class="row">
												<div class="col-xl-12 text-center">

													<?php $photoError = (isset($errors) and $errors->has('photo')) ? ' is-invalid' : ''; ?>
													<div class="photo-field">
														<div class="file-loading">
															<input id="photoField" name="photo" type="file" class="file {{ $photoError }}">
														</div>
													</div>

												</div>
											</div>
										</form>
										<em>Please upload Image of 120X120 px</em>

	                            	 </div>
	                            	 <div class="col-md-9">
	                            	 		<form name="details" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/company') }}#business-profile" enctype="multipart/form-data">
											{!! csrf_field() !!}
											<input name="_method" type="hidden" value="PUT">
											<input name="panel" type="hidden" value="userPanel">

												<!-- username -->
											<?php $usernameError = (isset($errors) and $errors->has('username')) ? ' is-invalid' : ''; ?>


											<div class="form-group row required">
												<label class="col-md-12 "> Company URL <a target="_blank" style="color:blue;" href="{{ lurl('/') }}/{{ $user->username }}">{{ lurl('/') }}/{{ $user->username }}</a></label>

											</div>
											<!-- name -->
											<?php $nameError = (isset($errors) and $errors->has('name')) ? ' is-invalid' : '';
												  $establishmentError = (isset($errors) and $errors->has('establishment_year')) ? ' is-invalid' : '';
											 ?>
											<div class="form-group  row required">
												<div class="col">
													<label >{{ t('Company Name') }} <sup>*</sup></label>

														<input name="name" type="text" class="form-control{{ $nameError }}" placeholder="Company Name" value="{{ old('name', $user->name) }}">

												</div>
												<div class="col">
													<label  >Establishment Year</label>

														<input name="establishment_year" type="text" class="form-control{{ $establishmentError }}" placeholder="Establishment Year" value="{{ old('establishment_year', $user->establishment_year) }}">

												</div>
											</div>

											<div class="form-group  row required">
												<div class="col">
													<label >Promoter / CEO  (First name)</label>

														<input name="ceo_first_name" type="text" class="form-control" placeholder="" value="{{ old('ceo_first_name', $user->ceo_first_name) }}">

												</div>
												<div class="col">
													<label >Promoter / CEO  (Lasts name)</label>

														<input name="ceo_last_name" type="text" class="form-control" placeholder="" value="{{ old('ceo_last_name', $user->ceo_last_name) }}">

												</div>
											</div>

											<div class="form-group  row required">
												<div class="col">
													<label >Primary Business Type</label>

														<select name="business_type" class="form-control sselecter">
														<option value="0" {{ (!old('business_type') or old('business_type')==0) ? 'selected="selected"' : '' }}>
														 ---Choose One---
														</option>

														@foreach ($business_types as $item)
															<option value="{{ $item->id }}" {{ (old('business_type', $user->business_type)==$item->id) ? 'selected="selected"' : '' }}>
																{{ $item->name }}
															</option>
														@endforeach
													</select>

												</div>
												<div class="col">
													<label >Ownership Type</label>

														<select name="owner_type" class="form-control sselecter">
														<option value="0" {{ (!old('owner_type') or old('owner_type')==0) ? 'selected="selected"' : '' }}>
														 ---Choose One---
														</option>

														@foreach ($ownership_types as $item)
															<option value="{{ $item->id }}" {{ (old('owner_type', $user->owner_type)==$item->id) ? 'selected="selected"' : '' }}>
																{{ $item->name }}
															</option>
														@endforeach
													</select>

												</div>
											</div>

											<div class="form-group  row required">
												<div class="col">
													<label >Number of Employees</label>

														<select name="no_employees" class="form-control sselecter">
														<option value="" {{ (!old('no_employees') or old('no_employees')=="") ? 'selected="selected"' : '' }}>
														 ---Choose One---
														</option>

														@foreach ($no_of_employees as $item)
															<option value="{{ $item }}" {{ (old('no_employees', $user->no_employees)==$item) ? 'selected="selected"' : '' }}>
																{{ $item }}
															</option>
														@endforeach
													</select>

												</div>
												<div class="col">
													<label >Annual Turnover</label>

														<select name="annual_turnover" class="form-control sselecter">
														<option value="" {{ (!old('annual_turnover') or old('annual_turnover')=="") ? 'selected="selected"' : '' }}>
														 ---Choose One---
														</option>

														@foreach ($turn_overs as $item)
															<option value="{{ $item }}" {{ (old('annual_turnover', $user->annual_turnover)==$item) ? 'selected="selected"' : '' }}>
																{{ $item}}
															</option>
														@endforeach
													</select>

												</div>
											</div>



											<div class="form-group  row required">
												<div class="col-md-6">
													<label >Corporate video (You Tube Iframe Link) </label>

														<input name="corporate_video" type="text" class="form-control" placeholder="" value="{{  old('corporate_video', $user->corporate_video)   }}">

												</div>
                                                                                                 <div class="col-md-6">
													<label >title </label>

														<input name="corporate_video_title" type="text" class="form-control" placeholder="" value="{{  old('corporate_video_title', $user->corporate_video_title)   }}">

												</div>

																							</div>




											<!-- description -->
										<?php $descriptionError = (isset($errors) and $errors->has('about_us')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required">
											<?php
												$descriptionErrorLabel = '';
												$descriptionColClass = 'col-md-8';
												if (config('settings.single.simditor_wysiwyg') or config('settings.single.ckeditor_wysiwyg')) {
													$descriptionColClass = 'col-md-12';
													$descriptionErrorLabel = $descriptionError;
												}
												$ckeditorClass = (config('settings.single.ckeditor_wysiwyg')) ? ' ckeditor' : '';
											?>
											<label class="col-md-3" for="about_us">
												About Us
											</label>
											<div class="{{ $descriptionColClass }}">

												<textarea
														class="form-control{{ $ckeditorClass . $descriptionError }}"
														id="description"
														name="about_us"
														rows="10"
												>{{ old('about_us', $user->about_us) }}</textarea>

                                            </div>
										</div>

										<?php $descriptionError = (isset($errors) and $errors->has('why_us')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required">
											<?php
												$descriptionErrorLabel = '';
												$descriptionColClass = 'col-md-8';
												if (config('settings.single.simditor_wysiwyg') or config('settings.single.ckeditor_wysiwyg')) {
													$descriptionColClass = 'col-md-12';
													$descriptionErrorLabel = $descriptionError;
												}
												$ckeditorClass = (config('settings.single.ckeditor_wysiwyg')) ? ' ckeditor' : '';
											?>
											<label class="col-md-3" for="why_us">
												Why Us
											</label>
											<div class="{{ $descriptionColClass }}">

                                                                                                        												<textarea
														class="form-control{{ $ckeditorClass . $descriptionError }}"
														id="why_us"
														name="why_us"
														rows="10"
												>{{ old('why_us',strip_tags($user->why_us) ) }}</textarea>

                                            </div>
										</div>

										<?php $descriptionError = (isset($errors) and $errors->has('our_product')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required">
											<?php
												$descriptionErrorLabel = '';
												$descriptionColClass = 'col-md-8';
												if (config('settings.single.simditor_wysiwyg') or config('settings.single.ckeditor_wysiwyg')) {
													$descriptionColClass = 'col-md-12';
													$descriptionErrorLabel = $descriptionError;
												}
												$ckeditorClass = (config('settings.single.ckeditor_wysiwyg')) ? ' ckeditor' : '';
											?>
											<label class="col-md-3" for="our_product">
												Mission / Vision
											</label>
											<div class="{{ $descriptionColClass }}">

												<textarea
														class="form-control{{ $ckeditorClass . $descriptionError }}"
														id="our_product"
														name="our_product"
														rows="10"
												>{{ old('our_product', strip_tags($user->our_product)) }}</textarea>

                                            </div>
										</div>

										<?php $filenameError = (isset($errors) and $errors->has('filename')) ? ' is-invalid' : ''; ?>
										<div class="form-group required" {!! (config('lang.direction')=='rtl') ? 'dir="rtl"' : '' !!}>
											<label for="filename" class="control-label{{ $filenameError }}">Brochure </label>
											@if($user->brochure)
											<label class="pull-right"><a href="{{ lurl('storage/' . $user->brochure) }}" target="_blank">Current</a> | <a href="javascript:void(0)" onclick="deleteBrochure()">Delete</a></label>
											@endif
											<input id="filename" name="filename" type="file" class="file{{ $filenameError }}">
											<small id="" class="form-text text-muted">
												{{ t('File types: :file_types', ['file_types' => showValidFileTypes('file')]) }}
												<br>Max file size : {{ (int)config('settings.upload.max_file_size', 1000)/1000 }} MB
											</small>
										</div>




											<!-- Button -->
											<div class="form-group row">
												<div class="offset-md-3 col-md-9">
													<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
												</div>
											</div>
										</form>
	                            	 </div>
	                            	</div>
	                            </div>
	                            <div class="tab-pane fade" id="statutory-details" role="tabpanel" aria-labelledby="statutory-details-tab">

	                            	<div class="row">

	                            	 <div class="col-md-12">
	                            	 		<form name="details" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/statutory') }}#statutory-details">
											{!! csrf_field() !!}
											<input name="_method" type="hidden" value="PUT">
											<input name="panel" type="hidden" value="userPanel">


											<!-- name -->
											<?php $gstinError = (isset($errors) and $errors->has('gstin')) ? ' is-invalid' : '';
												  $pan_noError = (isset($errors) and $errors->has('pan_no')) ? ' is-invalid' : '';

												  $tan_noError = (isset($errors) and $errors->has('tan_no')) ? ' is-invalid' : '';
												  $cin_noError = (isset($errors) and $errors->has('cin_no')) ? ' is-invalid' : '';

												  $dgft_noError = (isset($errors) and $errors->has('dgft_no')) ? ' is-invalid' : '';


											 ?>
											<div class="form-group  row required">
												<div class="col">
													<label >GSTIN</label>

														<input name="gstin" type="text" class="form-control{{ $gstinError }}" placeholder="" value="{{ old('gstin', $user->gstin) }}">

												</div>
												<div class="col">
													<label  >PAN No.</label>

														<input name="pan_no" type="text" class="form-control{{ $pan_noError }}" placeholder="" value="{{ old('pan_no', $user->pan_no) }}">

												</div>


											</div>

											<div class="form-group  row required">

												<div class="col">
													<label >TAN No.</label>

														<input name="tan_no" type="text" class="form-control{{ $tan_noError }}" placeholder="" value="{{ old('tan_no', $user->tan_no) }}">

												</div>
												<div class="col">
													<label  >CIN No.</label>

														<input name="cin_no" type="text" class="form-control{{ $cin_noError }}" placeholder="" value="{{ old('cin_no', $user->cin_no) }}">

												</div>
											</div>

											<div class="form-group  row required">

												<div class="col">
													<label >DGFT/IE Code</label>

														<input name="dgft_no" type="text" class="form-control{{ $dgft_noError }}" placeholder="" value="{{ old('dgft_no', $user->dgft_no) }}">

												</div>

											</div>





											<!-- Button -->
											<div class="form-group row">
												<div class="offset-md-3 col-md-9">
													<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
												</div>
											</div>
										</form>
	                            	 </div>
	                            	</div>

	                            </div>

	                            <div class="tab-pane fade" id="bank-details" role="tabpanel" aria-labelledby="bank-details-tab">

	                            	<div class="row">

	                            	 <div class="col-md-12">
	                            	 		<form name="details" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/bank') }}#bank-details">
											{!! csrf_field() !!}
											<input name="_method" type="hidden" value="PUT">
											<input name="panel" type="hidden" value="userPanel">


											<!-- name -->
											<?php $ifsc_codeError = (isset($errors) and $errors->has('ifsc_code')) ? ' is-invalid' : '';
												  $bank_nameError = (isset($errors) and $errors->has('bank_name')) ? ' is-invalid' : '';

												  $account_noError = (isset($errors) and $errors->has('account_no')) ? ' is-invalid' : '';
												  $account_typeError = (isset($errors) and $errors->has('account_type')) ? ' is-invalid' : '';

												  $bank_nameReadonly = (old('bank_name', $user->bank_name)) ? 'readonly' : '';



											 ?>
											<div class="form-group  row required">
												<div class="col">
													<label class="col-form-label{{$ifsc_codeError}}">IFSC Code <sup>*</sup></label>

														<input name="ifsc_code" id="ifsc_code" type="text" class="form-control{{ $ifsc_codeError }}" placeholder="" value="{{ old('ifsc_code', $user->ifsc_code) }}">

												</div>
												<div class="col">
													<label  class="col-form-label{{$bank_nameError}}">Bank Name  (As Per IFSC Code) <sup>*</sup></label>

														<input name="bank_name" id="bank_name" type="text" class="form-control{{ $bank_nameError }}" readonly="{{ $bank_nameReadonly }}" placeholder="" value="{{ old('bank_name', $user->bank_name) }}">

												</div>


											</div>

											<div class="form-group  row required">

												<div class="col">
													<label class="col-form-label{{$account_noError}}">Account No. <sup>*</sup></label>

														<input name="account_no" type="text" class="form-control{{ $account_noError }}" placeholder="" value="{{ old('account_no', $user->account_no) }}">

												</div>
												<div class="col">
													<label  class="col-form-label{{$account_typeError}}">Account Type.<sup>*</sup></label>

													 	<select name="account_type" class="form-control sselecter{{$account_typeError}}">
														<option value="" {{ (!old('account_type') or old('account_type')=="") ? 'selected="selected"' : '' }}>
														 ---Choose One---
														</option>

														@foreach ($bank_account_types as $item)
															<option value="{{ $item }}" {{ (old('account_type', $user->account_type)==$item) ? 'selected="selected"' : '' }}>
																{{ $item}}
															</option>
														@endforeach
													</select>


												</div>
											</div>






											<!-- Button -->
											<div class="form-group row">
												<div class="offset-md-3 col-md-9">
													<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
												</div>
											</div>
										</form>
	                            	 </div>
	                            	</div>

	                            </div>


	                            <div class="tab-pane fade" id="api-details" role="tabpanel" aria-labelledby="api-details-tab">

	                            	<div class="row">

	                            	 <div class="col-md-12">
	                            	 		<form name="details" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/api') }}#api-details">
											{!! csrf_field() !!}
											<input name="_method" type="hidden" value="PUT">
											<input name="panel" type="hidden" value="userPanel">


											<!-- name -->
											<?php $email_to_sendError = (isset($errors) and $errors->has('email_to_send')) ? ' is-invalid' : '';
												  $sms_to_sendError = (isset($errors) and $errors->has('sms_to_send')) ? ' is-invalid' : '';

												  $allowEditing = (app('impersonate')->isImpersonating() && app('impersonate')->getImpersonatorId()=="1" );
												  $readonly = "";
												  if(!$allowEditing){

												  	$readonly = 'readonly="readonly"';

												  }

											 ?>
											<div class="form-group  row required">
												<div class="col">
													<label class="col-form-label{{$email_to_sendError}}">Notifcation Email <sup>*</sup></label>

														<input name="email_to_send" id="email_to_send" type="text" class="form-control{{ $email_to_sendError }}" {{$readonly}} placeholder="" value="{{ old('email_to_send', $user->email_to_send) }}">

												</div>
												<div class="col">
													<label  class="col-form-label{{$sms_to_sendError}}">SMS mobile number </label>

														<input name="sms_to_send" id="sms_to_send" type="text" class="form-control{{ $sms_to_sendError }}" {{$readonly}}  placeholder="" value="{{ old('sms_to_send', $user->sms_to_send) }}">

												</div>


											</div>

											<div class="form-group  row required">

												<div class="col">
													<label  class="col-form-label">API Key </label>

														<input name="api_key" id="api_key" type="text" class="form-control" readonly="readonly" placeholder="" value="{{  $user->api_key }}">

														<input name="regenerate_api" value="1"  type="checkbox"> Re-Generate API Key


												</div>


												<div class="col">
													<label >Buy Leads Alerts</label>

														<select name="buy_leads_alerts" class="form-control sselecter">
														<option value="" {{ (!old('buy_leads_alerts') or old('buy_leads_alerts')=="") ? 'selected="selected"' : '' }}>
														 ---Choose One---
														</option>


															<option value="Yes" {{ (old('buy_leads_alerts', $user->buy_leads_alerts)=='Yes') ? 'selected="selected"' : '' }}>
																Yes
															</option>

															<option value="No" {{ (old('buy_leads_alerts', $user->buy_leads_alerts)=='No') ? 'selected="selected"' : '' }}>
																No
															</option>



													</select>

												</div>

											</div>





											@if($allowEditing)
											<!-- Button -->
											<div class="form-group row">
												<div class="offset-md-3 col-md-9">
													<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
												</div>
											</div>
											@endif
										</form>
	                            	 </div>
	                            	</div>

	                            </div>
	                            <div class="tab-pane fade" id="location-preference" role="tabpanel" aria-labelledby="location-preference-tab">

	                            	<div class="row">

	                            	 <div class="col-md-12">
	                            	 		<form name="details" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/location-preference') }}#location-preference">
											{!! csrf_field() !!}
											<input name="_method" type="hidden" value="PUT">
											<input name="panel" type="hidden" value="userPanel">

											<!-- gender_id -->
											<?php $genderIdError = (isset($errors) and $errors->has('location_preference')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required">
												<label class="col-md-3 col-form-label">Excluded Location</label>
												<div class="col-md-9">


													<?php $tagsError = (isset($errors) and $errors->has('tags')) ? ' is-invalid' : ''; ?>
													  		<input id="excluded_location"
																   name="excluded_location"
																   placeholder="Excluded Location"
																   class="form-control input-md{{ $tagsError }}"
																   type="text"
																   value="{{implode(',',$filterCity)}}"
															>


													<div>You can exclude maximum 250 locations</div>



												</div>
											</div>










											<!-- Button -->
											<div class="form-group row">
												<div class="offset-md-3 col-md-9">
													<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
												</div>
											</div>
										</form>

										@if (app('impersonate')->isImpersonating())
										<div class="row">

	                            	 		<div class="col-md-12">
	                            	 				<h2>Location History</h2>
	                            	 			<div class="table-responsive">
													<table class="table table-bordered">
														<thead>
														<tr>

															<th>Updated By</th>
															<th>Previous Locations</th>
															<th>New Locations</th>
															<th>Updated At</th>

														</tr>
														</thead>
															@foreach ($user->locationHistory as $history)

																<tr>
                                                                	<td class="text-left align-middle p-3">
                                                                		{{$history->updatedby->name}}
                                                                	</td>
                                                                	<td class="text-left align-middle p-3">
                                                                		{{implode(",",$history->old_locations)}}
                                                                	</td>
                                                                	<td class="text-left align-middle p-3">
                                                                		{{implode(",",$history->new_locations)}}
                                                                	</td>
                                                                	<td class="text-left align-middle p-3">
                                                                		{{$history->created_at}}
                                                                	</td>
                                                                </tr>


															@endforeach
														<tbody>

														</tbody>
													</table>
												</div>
	                            	 		</div>
	                            	 	</div>


										@endif
	                            	 </div>
	                            	</div>

	                            </div>


	                        </div>
	                    </div>
	                </div>



					</div>
				</div>
				<!--/.page-content-->
			</div>
			<!--/.row-->
		</div>
		<!--/.container-->
	</div>
	<!-- /.main-container -->
@endsection

@section('after_styles')
	@include('layouts.inc.tools.wysiwyg.css')
	<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
	@if (config('lang.direction') == 'rtl')
		<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
	@endif
	<style>
		.krajee-default.file-preview-frame:hover:not(.file-preview-error) {
			box-shadow: 0 0 5px 0 #666666;
		}
		.file-loading:before {
			content: " {{ t('Loading') }}...";
		}
	</style>
	<style>
		/* Avatar Upload */
		.photo-field {
			display: inline-block;
			vertical-align: middle;
		}
		.photo-field .krajee-default.file-preview-frame,
		.photo-field .krajee-default.file-preview-frame:hover {
			margin: 0;
			padding: 0;
			border: none;
			box-shadow: none;
			text-align: center;
		}
		.photo-field .file-input {
			display: table-cell;
			width: 150px;
		}
		.photo-field .krajee-default.file-preview-frame .kv-file-content {
			width: 150px;
			height: 160px;
		}
		.kv-reqd {
			color: red;
			font-family: monospace;
			font-weight: normal;
		}

		.file-preview {
			padding: 2px;
		}
		.file-drop-zone {
			margin: 2px;
		}
		.file-drop-zone .file-preview-thumbnails {
			cursor: pointer;
		}

		.krajee-default.file-preview-frame .file-thumbnail-footer {
			height: 30px;
		}
		.select2-container--default .select2-selection--single {
 			height: 48px !important;
 		}
		 .select2-container--default .select2-selection--single .select2-selection__arrow{
			height: 48px !important;
		 }
	</style>
@endsection

@section('after_scripts')
	 @include('layouts.inc.tools.wysiwyg.js')

	 <script type="text/javascript">
	 	var array = ['{!! implode('\',\'',$filterCity) !!}'];
	 	$(document).ready(function() {
			$('#excluded_location').tagit({

				fieldName: 'excluded_location',
				placeholderText: 'Add Excluded Location',
				caseSensitive: false,
				allowDuplicates: false,
				allowSpaces: false,

				 tagSource: function(request, response)
		        {
		            $.ajax({
		                data: { term:request.term },
		                type: "GET",
		                url:        "/account",
		                dataType:   "json",
		                 success: function( data ) {

		                    response( $.map( data, function( item ) {
		                    	console.log(item);
		                    	var value = item.label+' ('+item.sub_admin1.name+')';

		                    	array.push(value);
			                        return {
			                            label:value,
			                            value: value
			                        }
		                        }));
		                    }

		            });
		            },
				beforeTagAdded: function(event, ui) {

		            if(array.indexOf(ui.tagLabel) == -1)
		            {
		                return false;
		            }
		            if(ui.tagLabel == "not found")
		            {
		                return false;
		            }

		        },
				tagLimit: {{ 250 }},
				singleFieldDelimiter: ','
			});
		});


    </script>

	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
	@if (file_exists(public_path() . '/assets/plugins/bootstrap-fileinput/js/locales/'.ietfLangTag(config('app.locale')).'.js'))
		<script src="{{ url('assets/plugins/bootstrap-fileinput/js/locales/'.ietfLangTag(config('app.locale')).'.js') }}" type="text/javascript"></script>
	@endif
	<script>
		var lang = {
			'select': {
				'city': "{{ t('Select a city') }}",
				'admin': "{{ t('Select a city') }}"
			},

			'nextStepBtnLabel': {
			    'next': "{{ t('Next') }}",
                'submit': "{{ t('Submit') }}"
			}
		};


		function getBankName(){

			var ifsc = $('#ifsc_code').val();
			$('#bank_name').val("");
			$('#bank_name').removeAttr('readonly');

			var reg = /^[A-Za-z]{4}[0-9]{6,7}$/;

		    if (ifsc.match(reg)) {

		      $('#ifsc_code').removeClass('is-invalid');



				$.ajax({
					method: 'POST',
					url: siteUrl + '/ajax/ifsc',
					data: {
						'ifsc': ifsc
					}
				}).done(function(data) {


					if (data.bank_name) {

						$('#bank_name').val(data.bank_name);
						$('#bank_name').attr('readonly','readonly');

					}


				});


		    } else {
		      $('#ifsc_code').addClass('is-invalid');
		    }
		}
		$('#ifsc_code').on('change',function(){

			getBankName();
		});
		$('#cityId').on('change',function(){

			if($(this).val()>0){
				$.ajax({
					method: 'GET',
					url: siteUrl + '/ajax/countries/IN/cities/'+$(this).val(),

				}).done(function(data) {

			 	  	$('#state').val(data.state);


				});
			}


		});

		$('.custom_city').on('change',function(){

			if($(this).val()>0){
				console.log($(this).val())
				/*$.ajax({
					method: 'GET',
					url: siteUrl + '/ajax/countries/IN/cities/'+$(this).val(),

				}).done(function(data) {

			 	  	$('#state').val(data.state);


				});*/
			}


		});

		var url = document.location.toString();
		if (url.match('#')) {
		    $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
		}
		var photoInfo = '<h6 class="text-muted pb-0">{{ t('Click to select') }}</h6>';
		var footerPreview = '<div class="file-thumbnail-footer pt-2">\n' +
			'    {actions}\n' +
			'</div>';

		var cityId = '{{ old('city_id', (isset($user) ? $user->city_id : 0)) }}';

		$('#photoField').fileinput(
		{
			theme: "fa",
			language: '{{ config('app.locale') }}',
			@if (config('lang.direction') == 'rtl')
				rtl: true,
			@endif
			overwriteInitial: true,
			showCaption: false,
			showPreview: true,
			allowedFileExtensions: {!! getUploadFileTypes('image', true) !!},
			uploadUrl: '{{ lurl('account/' . $user->id . '/photo') }}',
			uploadAsync: false,
			showBrowse: false,
			showCancel: true,
			showUpload: false,
			showRemove: false,
			maxFileSize: {{ (int)config('settings.upload.max_file_size', 1000) }},
			browseOnZoneClick: true,
			minFileCount: 0,
			maxFileCount: 1,
			validateInitialCount: true,
			uploadClass: 'btn btn-primary',
			defaultPreviewContent: '<img src="{{ !empty($gravatar) ? $gravatar : url('images/user.jpg') }}" alt="{{ t('Your Photo or Avatar') }}">' + photoInfo,
			/* Retrieve current images */
			/* Setup initial preview with data keys */
			initialPreview: [
				@if (isset($user->photo) and !empty($user->photo))
					'{{ resize($user->photo) }}'
				@endif
			],
			initialPreviewAsData: true,
			initialPreviewFileType: 'image',
			/* Initial preview configuration */
			initialPreviewConfig: [
				{
					<?php
						// File size
						try {
							$fileSize = (int)File::size(filePath($user->photo));
						} catch (\Exception $e) {
							$fileSize = 0;
						}
					?>
					@if (isset($user->photo) and !empty($user->photo))
						caption: '{{ last(explode('/', $user->photo)) }}',
						size: {{ $fileSize }},
						url: '{{ lurl('account/' . $user->id . '/photo/delete') }}',
						key: {{ (int)$user->id }}
					@endif
				}
			],

			showClose: false,
			fileActionSettings: {
				removeIcon: '<i class="far fa-trash-alt"></i>',
				removeClass: 'btn btn-sm btn-danger',
				removeTitle: '{{ t('Remove file') }}'
			},

			elErrorContainer: '#avatarUploadError',
			msgErrorClass: 'alert alert-block alert-danger',

			layoutTemplates: {main2: '{preview} {remove} {browse}', footer: footerPreview}
		});

		/* Auto-upload added file */
		$('#photoField').on('filebatchselected', function(event, data, id, index) {
			if (typeof data === 'object') {
				{{--
					Display the exact error (If it exists (Before making AJAX call))
					NOTE: The index '0' is available when the first file size is smaller than the maximum size allowed.
					      This index does not exist in the opposite case.
				--}}
				if (data.hasOwnProperty('0')) {
					$(this).fileinput('upload');
					return true;
				}
			}

			return false;
		});

		/* Show upload status message */
		$('#photoField').on('filebatchpreupload', function(event, data, id, index) {
			$('#avatarUploadSuccess').html('<ul></ul>').hide();
		});

		/* Show success upload message */
		$('#photoField').on('filebatchuploadsuccess', function(event, data, previewId, index) {
			/* Show uploads success messages */
			var out = '';
			$.each(data.files, function(key, file) {
				if (typeof file !== 'undefined') {
					var fname = file.name;
					out = out + {!! t('Uploaded file #key successfully') !!};
				}
			});
			$('#avatarUploadSuccess ul').append(out);
			$('#avatarUploadSuccess').fadeIn('slow');

			$('#userImg').attr({'src':$('.photo-field .kv-file-content .file-preview-image').attr('src')});
		});

		/* Delete picture */
		$('#photoField').on('filepredelete', function(jqXHR) {
			var abort = true;
			if (confirm("{{ t('Are you sure you want to delete this picture?') }}")) {
				abort = false;
			}
			return abort;
		});

		$('#photoField').on('filedeleted', function() {
			$('#userImg').attr({'src':'{!! !empty($gravatar) ? $gravatar : url('images/user.jpg') !!}'});

			var out = "{{ t('Your photo or avatar has been deleted.') }}";
			$('#avatarUploadSuccess').html('<ul><li></li></ul>').hide();
			$('#avatarUploadSuccess ul li').append(out);
			$('#avatarUploadSuccess').fadeIn('slow');
		});
	</script>

	<script>

		function deleteBrochure(){

			if (confirm('Are you sure you want to delete this brochure?')) {
				window.location.href="{{ lurl('account/deletebrochure') }}"
			}
		}
		/* Initialize with defaults (Resume) */
		$('#filename').fileinput(
		{
			theme: "fa",
            language: '{{ config('app.locale') }}',
			@if (config('lang.direction') == 'rtl')
				rtl: true,
			@endif
			showPreview: false,
			allowedFileExtensions:  {!! getUploadFileTypes('file', true) !!},
			showUpload: false,
			showRemove: false,
			maxFileSize: {{ (int)config('settings.upload.max_file_size', 1000) }}
		});
 				$("#cityId").val($("#cityId").val()).trigger("change");
	</script>
	<script src="{{ url('assets/js/app/d.select.location.js') . vTime() }}"></script>
@endsection
