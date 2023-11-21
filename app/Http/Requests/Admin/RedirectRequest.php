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

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class RedirectRequest extends Request
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'to'        => ['required'],
			'from'       => ['required'] 
		];
		
		if (in_array($this->method(), ['POST', 'CREATE'])) {
			// Unique with additional Where Clauses
			$uniqueTo = Rule::unique('redirects')->where(function ($query) {
				return $query->where('from', $this->from);
			});
			
			$rules['from'][] = $uniqueTo;
		}
		
		return $rules;
	}
	
	/**
	 * @return array
	 */
	public function messages()
	{
		$messages = [];
		
		$messages['from.unique'] = 'A redirect is already exists for this page';
		
		return $messages;
	}
}
