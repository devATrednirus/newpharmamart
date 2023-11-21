<?php
/**
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
 */

namespace App\Http\Requests;

use App\Rules\BetweenRule;

class SendMessageRequest extends Request
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'from_name'  => ['required', new BetweenRule(2, 200)],
	//		'from_email' => ['required','email','max:100'],
			'from_phone' => ['required','phone:IN','max:10'],
			'message'    => ['required', new BetweenRule(5, 500)],
		//	'post_id'    => ['required', 'numeric'],
		//	'location'    => ['required'],
	//		'address'    => ['required'],
	//		'city'    => ['required'],
		//	'pincode'    => ['required', 'regex:/^[1-9][0-9]{5}$/'],
	//		'drugs_license'    => ['required'],
	//		'have_gst_number'    => ['required'],
	//		'minimum_investment'    => ['required'],
	//		'purchase_period'    => ['required'],
	//		'call_back_time'    => ['required'],
	//		'profession'    => ['required'] 
		];
		
		// reCAPTCHA
		if (config('settings.security.recaptcha_activation')) {
			$rules['g-recaptcha-response'] = ['required'];
		}
		
		// Check 'resume' is required
		if ($this->filled('parentCatType') && in_array($this->input('parentCatType'), ['job-offer'])) {
			$rules['filename'] = ['required', 'mimes:' . getUploadFileTypes('file'), 'max:' . (int)config('settings.upload.max_file_size', 1000)];
		}
		
		// Email
		if ($this->filled('from_email')) {
			$rules['from_email'][] = 'email';
		}
		if (isEnabledField('email')) {
			if (isEnabledField('phone') && isEnabledField('email')) {
				$rules['from_email'][] = 'required_without:from_phone';
			} else {
				$rules['from_email'][] = 'required';
			}
		}
		
		// Phone
		if (config('settings.sms.phone_verification') == 1) {
			if ($this->filled('from_phone')) {
				$countryCode = $this->input('country_code', config('country.code'));
				if ($countryCode == 'UK') {
					$countryCode = 'GB';
				}
				$rules['from_phone'][] = 'phone:' . $countryCode;
			}
		}
		if (isEnabledField('phone')) {
			if (isEnabledField('phone') && isEnabledField('email')) {
				$rules['from_phone'][] = 'required_without:from_email';
			} else {
				$rules['from_phone'][] = 'required';
			}
		}
		
		return $rules;
	}
	
	/**
	 * @return array
	 */
	public function messages()
	{
		$messages = [];
		
		return $messages;
	}
}
