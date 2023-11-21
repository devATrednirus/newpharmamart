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
 
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Config\Repository;
use Illuminate\Validation\Rule;
use App\Rules\BlacklistDomainRule;
use App\Rules\BlacklistEmailRule;

class ApiRequest extends Request
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
		 
		// Validation Rules
		$rules = [

			'sms_to_send'     => [ 'max:10'],
			'email_to_send'     => ['required', 'email', new BlacklistEmailRule(), new BlacklistDomainRule()],
			
		];

		$phoneChanged = ($this->input('sms_to_send') != auth()->user()->sms_to_send);
		 //dump($this->input('sms_to_send'));
		if ($phoneChanged && $this->input('sms_to_send')) {
			$rules['sms_to_send'][] = 'regex:/^[6-9]\d{9}$/';
		}
		//dd($rules);
		return $rules;
	}
	
	/**
	 * @return array
	 */
	public function attributes()
	{
		
		$messages = [];
		return $messages;
	}

	public function messages()
	{
		$messages = [
		 	'sms_to_send.regex' => "Please enter a valid mobile numer"
			 
		];
		
		return $messages;
	}
}
