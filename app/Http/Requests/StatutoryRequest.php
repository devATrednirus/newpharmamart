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

class StatutoryRequest extends Request
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

			'gstin'      => ['nullable', 'regex:/^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$/'], 
			'pan_no'      => ['nullable', 'regex:/^([A-Za-z]{5})([0-9]{4})([A-Za-z]{1})$/'], 
			'tan_no'      => ['nullable', 'regex:/[A-Za-z]{4}[0-9]{5}[A-Za-z]{1}$/'], 
			'cin_no'      => ['nullable', 'regex:/^([L|U]{1})([0-9]{5})([A-Za-z]{2})([0-9]{4})([A-Za-z]{3})([0-9]{6})$/'], 
			
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
			'gstin' => 'GSTIN',
			'pan_no' => 'PAN No',
			'tan_no' => 'TAN No',
			'cin_no' => 'CIN No',
		];
		
		return $messages;
	}
}
