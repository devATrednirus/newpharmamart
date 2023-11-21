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

class SendQuickQueryMessageRequest extends Request
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'quick_query_name'  => ['required', new BetweenRule(2, 200)],
			'quick_query_phone' => ['required','phone:IN','max:10'],
			'quick_query'    => ['required', new BetweenRule(5, 500)] 
		];
		 
		
		return $rules;
	}
	
	/**
	 * @return array
	 */
	public function messages()
	{
		$messages = [
			 
		];
		
		return $messages;
	}

	public function attributes()
	{
		$messages = [
			'quick_query_name' => 'name',
			'quick_query_phone'    => 'Mobile',
			'quick_query'    => 'query',
		];
		
		return $messages;
	}
}
