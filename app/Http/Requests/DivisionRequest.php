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
use App\Rules\BlacklistDomainRule;
use App\Rules\BlacklistEmailRule;
use App\Rules\BlacklistTitleRule;
use App\Rules\BlacklistWordRule;


class DivisionRequest extends Request
{
	protected $cfMessages = [];
	
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			 
			//'slug' => ['required' , new GroupSlug()],
			'name'        => ['required', new BetweenRule(2, 150), new BlacklistTitleRule()],
			'pdf_filename'        => [
						 
						'file',
						'mimes:pdf',
						'max:' . (int)config('settings.upload.max_file_size', 1000)
					],
		//	'description'  => ['required', new BetweenRule(5, 60000), new BlacklistWordRule()],
			//'contact_name' => ['required', new BetweenRule(2, 200)],
			//'email'        => ['max:100', new BlacklistEmailRule(), new BlacklistDomainRule()],
			//'phone'        => ['max:20'],
			//'city_id'      => ['required', 'not_in:0'],
		];



		//dd($rules);
		   
		
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
