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

use App\Rules\EstablishmentYearRule;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Config\Repository;
use Illuminate\Validation\Rule;

class BankRequest extends Request
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

			'ifsc_code'      => ['required', 'regex:/[A-Z|a-z]{4}[0][\d]{6}$/'], 
			'bank_name'      => ['required'], 
			'account_no'      => ['required','regex:/^\d{9,18}$/'], 
			'account_type'      => ['required',Rule::in(['Saving Account','Current Account'])], 
			
		];
		 
		//dd($rules);
		return $rules;
	}
	
	/**
	 * @return array
	 */
	public function attributes()
	{
		$messages = [
			'ifsc_code' => 'IFSC Code',
			 
		];
		
		return $messages;
	}

	public function messages()
	{
		$messages = [
			//'ifsc_code.regex' => 'IFSC Code',
			'account_no.regex' => 'Account can be 9 digits to 18 digits',
			'account_type.in' => 'Account type can be Saving Account,Current Account'
			 
		];
		
		return $messages;
	}
}
