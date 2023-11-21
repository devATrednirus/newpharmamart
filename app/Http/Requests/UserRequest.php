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


use App\Rules\BlacklistDomainRule;
use App\Rules\BlacklistEmailRule;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Config\Repository;

class UserRequest extends Request
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return auth()->check();
	}
	
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @param \Illuminate\Routing\Router $router
	 * @param \Illuminate\Filesystem\Filesystem $files
	 * @param \Illuminate\Config\Repository $config
	 * @return array
	 */
	public function rules(Router $router, Filesystem $files, Repository $config)
	{
		// Check if these fields has changed
		$emailChanged = ($this->input('email') != auth()->user()->email);
		$phoneChanged = ($this->input('phone') != auth()->user()->phone);
		
		
		// Validation Rules
		$rules = [
			//'gender_id' => ['required', 'not_in:0'],
			'first_name'      => ['required', 'max:50'],
			//'last_name'      => ['required', 'max:50'],
			'address1'      => ['required', 'min:5'],
			//'address2'      => ['required', 'min:5'],

			//'pincode'      => ['required', 'regex:/^[1-9][0-9]{5}$/'],
			'phone'     => ['required', 'max:20'],
			'email'     => ['required', 'email', new BlacklistEmailRule(), new BlacklistDomainRule()],
			'city_id'      => ['required', 'not_in:0'],
			//
		];
		
		// Phone
		/*if (config('settings.sms.phone_verification') == 1) {
			if ($this->filled('phone')) {
				$countryCode = $this->input('country_code', config('country.code'));
				if ($countryCode == 'UK') {
					$countryCode = 'GB';
				}
				$rules['phone'][] = 'phone:' . $countryCode;
			}
		}*/
		if ($phoneChanged) {
			$rules['phone'][] = 'unique:users,phone';
			$rules['phone'][] = 'regex:/^[6-9]\d{9}$/';

			
		}
		
		// Email
		if ($emailChanged) {
			$rules['email'][] = 'unique:users,email';
		}
		
		
		
		return $rules;
	}
	
	/**
	 * @return array
	 */
	public function messages()
	{
		$messages = [
			'pincode.regex' => "Please enter a valid pincode"
		];
		
		return $messages;
	}
}
