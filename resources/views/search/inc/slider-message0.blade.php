<?php

if (auth()->user()) {
	$authuser = \App\Models\User::with('city')->find(Auth::id());
} else {
	$authuser = null;
}



?><div class="modal" id="sliderForm" role="dialog">
	search\inc\slider-message.blade.php
	<div class="modal-dialog" style=" padding: 40px; height:60%;">
		<div class="modal-content" style="background: #fff; border:0px;">
			<div class="row">
				<div class="col-md-12">
					<form id="msform" role="form" method="POST" enctype="multipart/form-data">
						{!! csrf_field() !!}
						<input type="hidden" name="query_type" id="query_type">
						<input type="hidden" name="query_id" id="query_id">

						<fieldset id="city_box">
							<!--  <h2 class="fs-title">Comunication Details </h2> -->


							<div class="col-md-12">
								<div class="form-group required">

									<div class="row">
										<div class="col-md-3">
											<label style="font-weight: bold; color: #3c3a3a;">Email</label>
										</div>
										<div class="col-md-9">
											<input id="slider_from_email" name="from_email" type="email" placeholder="Email" required="required" value="niitpuneetkumar@gmail.com" class="form-control" value="{{($authuser?$authuser->email:'')}}">
										</div>
									</div>
								</div>
							</div>



							<div class="col-md-12">
								<div class="form-group required">

									<div class="row">
										<div class="col-md-3">
											<label style="font-weight: bold; color: #3c3a3a;">Address</label>
										</div>
										<div class="col-md-9">
											<textarea name="address" class="form-control" placeholder="Address">{{($authuser?$authuser->address1.' '.$authuser->address1:'')}}</textarea>
										</div>
									</div>
								</div>
							</div>



							<div class="col-md-12">
								<div class="form-group required">

									<div class="row">
										<div class="col-md-3">
											<label style="font-weight: bold; color: #3c3a3a;">City</label>
										</div>
										<div class="col-md-9">
											<select id="city_id" required="required" name="city_id" class="form-control" style="width: 100%; text-align: left;">
												<option value="" {{ (!old('city_id') or old('city_id')==0) ? 'selected="selected"' : '' }}>
													{{ ('Select a city') }}
												</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<button type="button" name="next" class="btn btn-primary  next btnsave ">Next</button>
							<button type="button" class="btn btn-secondary close-btn " data-dismiss="modal">Close</button>
						</fieldset>

						<fieldset>
							<!-- <h2 class="fs-title">Comunication Details 2</h2> -->


							<div class="col-md-12">
								<div class="form-group required">

									<div class="row">
										<div class="col-md-12">
											<label style="font-weight: bold; color: #3c3a3a;">Location For Franchise</label>
										</div>
										<div class="col-md-12">
											<input name="location_for_franchise" type="text" placeholder="Location For Franchise" required="required" class="form-control" value="{{($authuser ?$authuser->location_for_franchise:'')}}">
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group required">

									<div class="row">
										<div class="col-md-12">
											<label style="font-weight: bold; color: #3c3a3a;">Do You Have Drugs License?</label>
										</div>
										<div class="col-md-12">
											<select id="drugs_license" name="drugs_license" class="form-control" required="required">

												<option value="">--Select--</option>
												<option value="Yes" {{($authuser && $authuser->drugs_license=="Yes" ?'selected':'')}}>Yes</option>
												<option value="No" {{($authuser && $authuser->drugs_license=="No" ?'selected':'')}}>No</option>

											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group required">

									<div class="row">
										<div class="col-md-12">
											<label style="font-weight: bold; color: #3c3a3a;">Do You Have GST Number?</label>
										</div>
										<div class="col-md-12">
											<select id="have_gst_number" name="have_gst_number" class="form-control" required="required">

												<option value="">--Select--</option>
												<option value="Yes" {{($authuser && $authuser->have_gst_number=="Yes" ?'selected':'')}}>Yes</option>
												<option value="No" {{($authuser && $authuser->have_gst_number=="No" ?'selected':'')}}>No</option>

											</select>
										</div>
									</div>
								</div>
							</div>



							<input type="button" name="previous" style="border:0px;" class="previous action-button-previous btnprevious" value="Previous" />
							<input type="button" name="next" style="border:0px;" class="next action-button btnsave" value="Next" />
						</fieldset>

						<fieldset>
							<!--  <h2 class="fs-title">Comunication Details</h2> -->



							<div class="col-md-12">
								<div class="form-group required">

									<div class="row">
										<div class="col-md-12">
											<label style="font-weight: bold; color: #3c3a3a;">Minimum Investment</label>
										</div>
										<div class="col-md-12">
											<select id="minimum_investment" name="minimum_investment" class="form-control" required="required">

												<option value="">--Select--</option>
												<option value="5000 Rs to 25000 Rs" {{($authuser && $authuser->minimum_investment=="5000 Rs to 25000 Rs" ?'selected':'')}}>5000 Rs to 25000 Rs</option>
												<option value="25000 Rs to 50000 Rs" {{($authuser && $authuser->minimum_investment=="25000 Rs to 50000 Rs" ?'selected':'')}}>25000 Rs to 50000 Rs</option>
												<option value="Above 50000 Rs" {{($authuser && $authuser->minimum_investment=="Above 50000 Rs" ?'selected':'')}}>Above 50000 Rs</option>

											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group required">

									<div class="row">
										<div class="col-md-12">
											<label style="font-weight: bold; color: #3c3a3a;">Purchase Period</label>
										</div>
										<div class="col-md-12">
											<select id="purchase_period" name="purchase_period" class="form-control" required="required">

												<option value="">--Select--</option>
												<option value="1 Days - 15 Days" {{($authuser && $authuser->purchase_period=="1 Days - 15 Days" ?'selected':'')}}>1 Days - 15 Days</option>
												<option value="16 Days - 30 Days" {{($authuser && $authuser->purchase_period=="16 Days - 30 Days" ?'selected':'')}}>16 Days - 30 Days</option>
												<option value="More Than 30 Days" {{($authuser && $authuser->purchase_period=="More Than 30 Days" ?'selected':'')}}>More Than 30 Days</option>

											</select>
										</div>
									</div>
								</div>
							</div>





							<input type="button" name="previous" style="border:0px;" class="previous action-button-previous btnprevious" value="Previous" />
							<input type="button" name="next" style="border:0px;" class="next action-button btnsave" value="Next" />
						</fieldset>

						<fieldset>
							<!--   <h2 class="fs-title">Comunication Details</h2> -->





							<div class="col-md-12">
								<div class="form-group required">

									<div class="row">
										<div class="col-md-12">
											<label style="font-weight: bold; color: #3c3a3a;">Call Back Time</label>
										</div>
										<div class="col-md-12">
											<select id="call_back_time" name="call_back_time" class="form-control" required="required">

												<option value="">--Select--</option>
												<option value="10 AM - 12 Noon" {{($authuser && $authuser->call_back_time=="10 AM - 12 Noon" ?'selected':'')}}>10 AM - 12 Noon</option>
												<option value="12 Noon - 2 PM" {{($authuser && $authuser->call_back_time=="12 Noon - 2 PM" ?'selected':'')}}>12 Noon - 2 PM</option>
												<option value="2 PM - 4 PM" {{($authuser && $authuser->call_back_time=="2 PM - 4 PM" ?'selected':'')}}>2 PM - 4 PM</option>
												<option value="4 PM - 6 PM" {{($authuser && $authuser->call_back_time=="4 PM - 6 PM" ?'selected':'')}}>4 PM - 6 PM</option>
												<option value="After 6 PM" {{($authuser && $authuser->call_back_time=="After 6 PM" ?'selected':'')}}>After 6 PM</option>
												<option value="Any Time" {{($authuser && $authuser->call_back_time=="Any Time" ?'selected':'')}}>Any Time</option>

											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group required">

									<div class="row">
										<div class="col-md-12">
											<label style="font-weight: bold; color: #3c3a3a;">Profession</label>
										</div>
										<div class="col-md-12">
											<select id="profession" name="profession" class="form-control" required="required">

												<option value="">--Select--</option>
												<option value="Student" {{($authuser && $authuser->profession=="Student" ?'selected':'')}}>Student</option>
												<option value="Retailer" {{($authuser && $authuser->profession=="Retailer" ?'selected':'')}}>Retailer</option>
												<option value="Doctor" {{($authuser && $authuser->profession=="Doctor" ?'selected':'')}}>Doctor</option>
												<option value="Distributer" {{($authuser && $authuser->profession=="Distributer" ?'selected':'')}}>Distributer</option>
												<option value="Wholesaler" {{($authuser && $authuser->profession=="Wholesaler" ?'selected':'')}}>Wholesaler</option>
												<option value="Wholesaler" {{($authuser && $authuser->profession=="Wholesaler" ?'selected':'')}}>Wholesaler</option>
												<option value="Medical Rap" {{($authuser && $authuser->profession=="Medical Rap" ?'selected':'')}}>Medical Rap</option>

											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group required">

									<div class="row">
										<div class="col-md-12">
											<label style="font-weight: bold; color: #3c3a3a;">Any specific query</label>
										</div>
										<div class="col-md-12">
											<textarea id="specific_query" name="specific_query" class="form-control" placeholder="Any specific query"></textarea>

										</div>
									</div>
								</div>
							</div>


							<input type="button" name="previous" style="border:0px;" class="previous action-button-previous btnprevious" value="Previous" />
							<input type="submit" name="submit" style="border:0px;" class="submit action-button btnsave" value="Submit" />
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@section('after_scripts')
@parent
<script>
	if (typeof adminType === 'undefined') {
	var adminType = 0;
}
if (typeof selectedAdminCode === 'undefined') {
	var selectedAdminCode = 0;
}
if (typeof cityId === 'undefined') {
	var cityId = 0;
}
var select2Language = languageCode;
if (typeof langLayout !== 'undefined' && typeof langLayout.select2 !== 'undefined') {
	select2Language = langLayout.select2;
}

$(document).ready(function()
{
	/* CSRF Protection */
	var token = $('meta[name="csrf-token"]').attr('content');
	if (token) {
		$.ajaxSetup({
			headers: {'X-CSRF-TOKEN': token},
			async: true,
			cache: false
		});
	}

    /* Get and Bind administrative divisions */
    /*getAdminDivisions(countryCode, adminType, selectedAdminCode);
    $('#countryCode').bind('click, change', function() {
		countryCode = $(this).val();
        getAdminDivisions(countryCode, adminType, 0);
    });*/

    /* Get and Bind the selected city */
    if (adminType == 0) {
		getSelectedCity(countryCode, cityId);
	}

    /* Get and Bind cities */
    $('#cityId,.custom_city').select2({
		language: select2Language,
        ajax: {
            url: function () {
				/* Get the current country code */
				var selectedCountryCode = $('#countryCode').val();
				if (typeof selectedCountryCode !== "undefined") {
					countryCode = selectedCountryCode;
				}

                /* Get the current admin code */
                var selectedAdminCode = $('#adminCode').val();
                if (typeof selectedAdminCode === "undefined") {
                    selectedAdminCode = 0;
                }
                return siteUrl + '/ajax/countries/' + countryCode + '/admins/' + adminType + '/' + selectedAdminCode + '/cities';
            },
            dataType: 'json',
            delay: 50,
            data: function (params) {
                var query = {
                    languageCode: languageCode,
                    q: params.term, /* search term */
                    page: params.page
                };

                return query;
            },
            processResults: function (data, params) {
            	/*
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                */
                params.page = params.page || 1;

                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 10) < data.totalEntries
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, /* let our custom formatter work */
        minimumInputLength: 2,
        templateResult: function (data) {
            return data.text;
        },
        templateSelection: function (data, container) {
            return data.text;
        }
    });
});

/**
 * Get and Bind Administrative Divisions
 *
 * @param countryCode
 * @param adminType
 * @param selectedAdminCode
 * @returns {*}
 */
function getAdminDivisions(countryCode, adminType, selectedAdminCode)
{
    if (countryCode == 0 || countryCode == '') return false;

	/* Make ajax call */
	$.ajax({
		method: 'GET',
		url: siteUrl + '/ajax/countries/' + countryCode + '/admins/' + adminType + '?languageCode=' + languageCode
	}).done(function(obj)
	{
		/* Init. */
		$('#adminCode').empty().append('<option value="0">' + lang.select.admin + '</option>').val('0').trigger('change');
		$('#cityId').empty().append('<option value="0">' + lang.select.city + '</option>').val('0').trigger('change');

		/* Bind data into Select list */
		if (typeof obj.error !== 'undefined') {
			$('#adminCode').find('option').remove().end().append('<option value="0"> '+ obj.error.message +' </option>');
			$('#adminCode').closest('.form-group').addClass('has-error');
			return false;
		} else {
			$('#adminCode').closest('.form-group').removeClass('has-error');
		}

		if (typeof obj.data === 'undefined') {
			return false;
		}
		$.each(obj.data, function (key, item) {
			if (selectedAdminCode == item.code) {
				$('#adminCode').append('<option value="' + item.code + '" selected="selected">' + item.name + '</option>');
			} else {
				$('#adminCode').append('<option value="' + item.code + '">' + item.name + '</option>');
			}
		});

		/* Get and Bind the selected city */
		getSelectedCity(countryCode, cityId);
	});

    return selectedAdminCode;
}

/**
 * Get and Bind (Selected) City by ID
 *
 * @param countryCode
 * @param cityId
 * @returns {number}
 */
function getSelectedCity(countryCode, cityId)
{
	/* Clear by administrative divisions selection */
	$('#adminCode').bind('click, change', function() {
		$('#cityId').empty().append('<option value="0">' + lang.select.city + '</option>').val('0').trigger('change');
	});

	/* Make ajax call */
	$.ajax({
		method: 'GET',
		url: siteUrl + '/ajax/countries/' + countryCode + '/cities/' + cityId + '?languageCode=' + languageCode
	}).done(function(data)
	{
		$('#cityId').empty().append('<option value="' + data.id + '">' + data.text + '</option>').val(data.id).trigger('change');
		return data.id;
	}).fail(function()
	{
		$('#cityId').empty().append('<option value="0">' + lang.select.city + '</option>').val('0').trigger('change');
		return 0;
	});

	return 0;
}
</script>
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
	var cityId = '{{ old('
	city_id ', (isset($authuser) ? $authuser->city_id : 0)) }}';
	var current_fs, next_fs, previous_fs;
	var left, opacity, scale;
	var animating;
	$("#sliderForm .next").click(function() {
		if (animating) return false;
		animating = true;

		current_fs = $(this).parent();
		next_fs = current_fs.next();

		var isValid = true;


		var curInputs = current_fs.find("input[type='text'],input[type='url'],input[type='email'],select,textarea");

		for (var i = 0; i < curInputs.length; i++) {

			if (!curInputs[i].validity.valid) {
				isValid = false;
				$(curInputs[i]).closest(".form-group").addClass("has-error");
				if ($(curInputs[i]).attr('id') == "city_id") {
					$(curInputs[i]).select2('open');
				} else {
					$(curInputs[i]).focus();
				}
				animating = false;
				break;
			}
		}

		if (isValid) {
			next_fs.show();

			current_fs.animate({
				opacity: 0
			}, {
				step: function(now, mx) {

					scale = 1 - (1 - now) * 0.2;
					left = (now * 50) + "%";

					opacity = 1 - now;
					current_fs.css({
						'transform': 'scale(' + scale + ')',
						'position': 'absolute'
					});
					next_fs.css({
						'left': left,
						'opacity': opacity
					});
				},
				duration: 800,
				complete: function() {
					current_fs.hide();
					animating = false;
				},

			});
		}


	});
	$("#sliderForm .previous").click(function() {
		if (animating) return false;
		animating = true;

		current_fs = $(this).parent();
		previous_fs = $(this).parent().prev();




		previous_fs.show();
		current_fs.animate({
			opacity: 0
		}, {
			step: function(now, mx) {

				scale = 0.8 + (1 - now) * 0.2;

				left = ((1 - now) * 50) + "%";

				opacity = 1 - now;
				current_fs.css({
					'left': left
				});
				previous_fs.css({
					'transform': 'scale(' + scale + ')',
					'opacity': opacity
				});
			},
			duration: 800,
			complete: function() {
				current_fs.hide();
				animating = false;
			},

		});
	});
	$(".close-btn").click(function(){
                     $(".modal-backdrop").remove();
                    $('#sliderForm').hide();
                    $('#sliderForm').removeClass('show');

                    $('#userOTP').hide();
                    $('#contactCompany').hide();
            });
	$(document).on("submit", "#sliderForm #msform", function(e) {
		e.preventDefault();

		var form = $(this).serialize();
		$("#sliderForm #msform .submit").attr('disabled', 'disabled');
		$.ajax({
			method: 'POST',
			url: '{{ lurl('query_detail') }}',
			data: {
				'data': form,
				'_token': $('input[name=_token]').val()
			}
		}).done(function(data) {

			$("#sliderForm #msform .submit").removeAttr('disabled');

			$("#sliderForm #msform .submit").removeAttr('disabled');
			window.dataLayer = window.dataLayer || [];
			var submited_form = $('.active_query');
			submited_form.removeClass('active_query');

			if ($('#query_type').val() == "quick_query") {
				window.dataLayer.push({
					'event': 'quickQueryExtra',
					'conversionValue': 1
				});
			} else if ($('#query_type').val() == "direct_query") {

				window.dataLayer.push({
					'event': 'directQueryExtra',
					'conversionValue': 1
				});
			} else if ($('#query_type').val() == "company_query") {

				window.dataLayer.push({
					'event': 'directCompanyQueryExtra',
					'conversionValue': 1
				});
			}
			$("#specific_query").val("");
			alert("Thank You");
			location.reload();
			$('#sliderForm').removeClass('show');


			/*

			if(submited_form.hasClass('listing_form')){
				submited_form.parent().html("Thank You");
			}
			else{
				alert("Thank You");
			}*/


		}).fail(function(response) {
			var data = response.responseJSON;

			$("#sliderForm #msform .submit").removeAttr('disabled');

			alert(data.message);
		});
	});
	$(document).ready(function() {
		$('#city_id').select2({
			language: select2Language,
			ajax: {
				url: function() {

					/* Get the current country code */
					var selectedCountryCode = $('#countryCode').val();
					if (typeof selectedCountryCode !== "undefined") {
						countryCode = selectedCountryCode;
					}
					/* Get the current admin code */
					var selectedAdminCode = $('#adminCode').val();
					if (typeof selectedAdminCode === "undefined") {
						selectedAdminCode = 0;
					}
					return siteUrl + '/ajax/countries/' + countryCode + '/admins/' + adminType + '/' + selectedAdminCode + '/cities';
				},
				dataType: 'json',
				delay: 50,
				data: function(params) {
					var query = {
						languageCode: languageCode,
						q: params.term,
						/* search term */
						page: params.page
					};

					return query;
				},
				processResults: function(data, params) {
					/*
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                */
					params.page = params.page || 1;

					return {
						results: data.items,
						pagination: {
							more: (params.page * 10) < data.totalEntries
						}
					};
				},
				cache: true
			},
			escapeMarkup: function(markup) {
				return markup;
			},
			/* let our custom formatter work */
			minimumInputLength: 0,
			templateResult: function(data) {
				return data.text;
			},
			templateSelection: function(data, container) {
				return data.text;
			}
		});
	});
	/* Get and Bind cities */
</script>

@endsection


@section('after_styles')
@parent
<style type="text/css">
	.has-error .select2 {
		border: 1px solid #ee0979;
	}

	.select2-container--open {
		z-index: 999999999;
	}

	#msform fieldset {
		background: #fff;
		border: 0;
		border-radius: 0;
		box-shadow: none !important;
		padding: 0px;
		box-sizing: border-box;
		width: 80%;
		margin: 0 10%;
		position: relative;
	}

	.btn-secondary {
		color: #fff;
		background-color: #6c757d;
		border-color: #6c757d;
	}

	#msform {
		text-align: center;
		position: relative;
		margin-top: 30px;
	}

	.skin-blue .btn-primary:active,
	.skin-blue .btn-primary:focus,
	.skin-blue .btn-primary:hover {
		background-color: #628fb5;
		border-color: #628fb5;
		color: #fff;
	}

	.skin-blue .btn-primary {
		background-color: #32b5ed;
		border-color: #32b2ed;
		color: #fff;
	}

	.skin-blue .btn:focus,
	.skin-blue .btn:hover {
		color: #333;
	}

	#msform input,
	#msform select,
	#msform textarea {
		border: 1px solid #ccc;
		border-radius: 0;
		margin-bottom: 10px;
		width: 100%;
		box-sizing: border-box;
		font-family: montserrat;
		color: #2c3e50;
		font-size: 13px;
	}

	.form-control {
		border: 1px solid #ddd;
		box-shadow: 1px 1px 20px 0 #e8e8e8;
		display: block;
		width: 100%;
		height: 48px;
		padding: .5rem .75rem;
		font-size: 1rem;
		line-height: 1.25;
		color: #464a4c;
		background-color: #fff;
		background-image: none;
		background-clip: padding-box;
		border: none;
		border-radius: .2rem;
		-webkit-transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
		-moz-transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
		-o-transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
		transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
	}

	#msform .action-button-previous:focus,
	#msform .action-button-previous:hover {
		box-shadow: 0 0 0 2px #fff, 0 0 0 3px #c5c5f1;
	}

	#msform .action-button-previous {
		width: 100px;
		background: #c5c5f1;
		font-weight: 700;
		color: #fff;
		border: 0;
		border-radius: 25px;
		cursor: pointer;
		padding: 10px 5px;
		margin: 10px 5px;
	}

	#msform .action-button {
		width: 100px;
		background: #ee0979;
		font-weight: 700;
		color: #fff;
		border: 0;
		border-radius: 25px;
		cursor: pointer;
		padding: 10px 5px;
		margin: 10px 5px;
	}

	#msform input,
	#msform select,
	#msform textarea {
		border: 1px solid #ccc;
		border-radius: 0;
		margin-bottom: 10px;
		width: 100%;
		box-sizing: border-box;
		font-family: montserrat;
		color: #2c3e50;
		font-size: 13px;
	}

	@media screen and (min-width: 992px) {
		.modal-dialog {
			width: 600px;
			max-width: 950px;
		}

	}

	@media screen and (min-width: 768px) {
		.modal-dialog {
			width: 600px;
			max-width: 950px;
		}
	}

	@media (min-width: 576px) {
		.modal-dialog {
			max-width: 500px;
			margin: 1.75rem auto;
		}
	}
</style>

@endsection
