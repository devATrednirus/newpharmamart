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
                
                 @include('post.inc.notification')
                 
                
                <div class="col-md-12 page-content">
                    <div class="inner-box">
						
                        <h2 class="title-2"><strong><i class="icon-tag"></i> Buy Leads</strong></h2>
						
                        <div class="row">
                            <div class="col-sm-12">
                                <form class="form" id="postForm" method="POST" action="{{ url()->current() }}">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <fieldset>
                                        
                                        @if (isset($buy_lead_packages) and isset($paymentMethods) and $buy_lead_packages->count() > 0 and $paymentMethods->count() > 0)
                                            <div class="well pb-0">
                                                 
												<?php $packageIdError = (isset($errors) and $errors->has('package_id')) ? ' is-invalid' : ''; ?>
                                                <div class="form-group mb-0">
                                                    <table id="buy_lead_packagesTable" class="table table-hover checkboxtable mb-0">
														<?php
															// Get Current Payment data
															$currentPaymentMethodId = 0;
															$currentPaymentActive = 1;
															/*if (isset($post->latestPayment) and !empty($post->latestPayment)) {
																$currentPaymentMethodId = $post->latestPayment->payment_method_id;
																if ($post->latestPayment->active == 0) {
																	$currentPaymentActive = 0;
																}
															}*/
														?>
                                                        @foreach ($buy_lead_packages as $package)
                                                            <?php
                                                            $currentPackageId = 0;
                                                            $currentPackagePrice = 0;
                                                            $buy_lead_packagestatus = '';
                                                            $badge = '';
															
                                                            ?>
                                                            <tr>
                                                                <td class="text-left align-middle p-3">
																	<div class="form-check">
																		<input class="form-check-input package-selection{{ $packageIdError }}"
																			   type="radio"
																			   name="package_id"
																			   id="packageId-{{ $package->tid }}"
																			   value="{{ $package->tid }}"
																			   data-name="{{ $package->name }}"
																			   data-currencysymbol="{{ $package->currency->symbol }}"
																			   data-currencyinleft="{{ $package->currency->in_left }}"
																				{{ (old('package_id', $currentPackageId)==$package->tid) ? ' checked' : (($package->price==0) ? ' checked' : '') }} {{ $buy_lead_packagestatus }}
																		>
                                                                        <label class="form-check-label mb-0{{ $packageIdError }}">
                                                                            <strong class="tooltipHere"
																					title=""
																					data-placement="right"
																					data-toggle="tooltip"
																					data-original-title="{!! $package->description !!}"
																			>{!! $package->name . $badge !!} </strong>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td class="text-right align-middle p-3">
                                                                    <p id="price-{{ $package->tid }}" class="mb-0">
                                                                        @if ($package->currency->in_left == 1)
                                                                            <span class="price-currency">{!! $package->currency->symbol !!}</span>
                                                                        @endif
                                                                        <span class="price-int">{{ $package->price }}</span>
                                                                        @if ($package->currency->in_left == 0)
                                                                            <span class="price-currency">{!! $package->currency->symbol !!}</span>
                                                                        @endif
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        
                                                        <tr>
                                                            <td class="text-left align-middle p-3">
																<?php $paymentMethodIdError = (isset($errors) and $errors->has('payment_method_id')) ? ' is-invalid' : ''; ?>
                                                                <div class="form-group mb-0">
                                                                    <div class="col-md-10 col-sm-12 p-0">
                                                                        <select class="form-control selecter{{ $paymentMethodIdError }}" name="payment_method_id" id="paymentMethodId">
                                                                            @foreach ($paymentMethods as $paymentMethod)
                                                                                @if (view()->exists('payment::' . $paymentMethod->name))
                                                                                    <option value="{{ $paymentMethod->id }}" data-name="{{ $paymentMethod->name }}" {{ (old('payment_method_id', $currentPaymentMethodId)==$paymentMethod->id) ? 'selected="selected"' : '' }}>
                                                                                        @if ($paymentMethod->name == 'offlinepayment')
                                                                                            {{ trans('offlinepayment::messages.Offline Payment') }}
                                                                                        @else
                                                                                            {{ $paymentMethod->display_name }}
                                                                                        @endif
                                                                                    </option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="text-right align-middle p-3">
                                                                <p class="mb-0">
                                                                    <strong>
																		{{ t('Payable Amount') }}:
																		<span class="price-currency amount-currency currency-in-left" style="display: none;"></span>
                                                                        <span class="payable-amount">0</span>
																		<span class="price-currency amount-currency currency-in-right" style="display: none;"></span>
                                                                    </strong>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    
                                                    </table>
                                                </div>
                                            </div>
                                        
                                            @if (isset($paymentMethods) and $paymentMethods->count() > 0)
                                                <!-- Payment Plugins -->
                                                <?php $hasCcBox = 0; ?>
                                                @foreach($paymentMethods as $paymentMethod)
                                                    @if (view()->exists('payment::' . $paymentMethod->name))
                                                        @include('payment::' . $paymentMethod->name, [$paymentMethod->name . 'PaymentMethod' => $paymentMethod])
                                                    @endif
                                                    <?php if ($paymentMethod->has_ccbox == 1 && $hasCcBox == 0) $hasCcBox = 1; ?>
                                                @endforeach
                                            @endif
                                        @endif


                                        <!-- Button  -->
                                        <div class="form-group">
                                            <div class="col-md-12 text-center mt-4">
                                             
                                                <button id="submitPostForm" class="btn btn-success btn-lg submitPostForm"> {{ t('Pay') }} </button>
                                            </div>
                                        </div>
                                        
                                       
                                    
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.page-content -->
            </div>
        </div>
    </div>
@endsection

@section('after_styles')
@endsection

@section('after_scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.2.3/jquery.payment.min.js"></script>
    @if (file_exists(public_path() . '/assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js'))
        <script src="{{ url('/assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js') }}" type="text/javascript"></script>
    @endif

    <script>
        @if (isset($buy_lead_packages) and isset($paymentMethods) and $buy_lead_packages->count() > 0 and $paymentMethods->count() > 0)
			
			var currentPackagePrice = {{ $currentPackagePrice }};
			var currentPaymentActive = {{ $currentPaymentActive }};
			$(document).ready(function ()
			{
				/* Show price & Payment Methods */
				var selectedPackage = $('input[name=package_id]:checked').val();
				var packagePrice = getPackagePrice(selectedPackage);
				var packageCurrencySymbol = $('input[name=package_id]:checked').data('currencysymbol');
				var packageCurrencyInLeft = $('input[name=package_id]:checked').data('currencyinleft');
				var paymentMethod = $('#paymentMethodId').find('option:selected').data('name');
				showAmount(packagePrice, packageCurrencySymbol, packageCurrencyInLeft);
				showPaymentSubmitButton(currentPackagePrice, packagePrice, currentPaymentActive, paymentMethod);
				
				/* Select a Package */
				$('.package-selection').click(function () {
					selectedPackage = $(this).val();
					packagePrice = getPackagePrice(selectedPackage);
					packageCurrencySymbol = $(this).data('currencysymbol');
					packageCurrencyInLeft = $(this).data('currencyinleft');
					showAmount(packagePrice, packageCurrencySymbol, packageCurrencyInLeft);
					showPaymentSubmitButton(currentPackagePrice, packagePrice, currentPaymentActive, paymentMethod);
				});
				
				/* Select a Payment Method */
				$('#paymentMethodId').on('change', function () {
					paymentMethod = $(this).find('option:selected').data('name');
					showPaymentSubmitButton(currentPackagePrice, packagePrice, currentPaymentActive, paymentMethod);
				});
				
				/* Form Default Submission */
				$('#submitPostForm').on('click', function (e) {
					e.preventDefault();
					
					if (packagePrice <= 0) {
						$('#postForm').submit();
					}
					
					return false;
				});
			});
        
        @endif

		/* Show or Hide the Payment Submit Button */
		/* NOTE: Prevent Package's Downgrading */
		/* Hide the 'Skip' button if Package price > 0 */
		function showPaymentSubmitButton(currentPackagePrice, packagePrice, currentPaymentActive, paymentMethod)
		{
			if (packagePrice > 0) {
				$('#submitPostForm').show();
				$('#skipBtn').hide();
				
				if (currentPackagePrice > packagePrice) {
					$('#submitPostForm').hide();
				}
				if (currentPackagePrice == packagePrice) {
					if (paymentMethod == 'offlinepayment' && currentPaymentActive != 1) {
						$('#submitPostForm').hide();
						$('#skipBtn').show();
					}
				}
			} else {
				$('#skipBtn').show();
			}
		}
    </script>
@endsection
