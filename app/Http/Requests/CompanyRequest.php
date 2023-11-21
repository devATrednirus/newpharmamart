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
use App\Rules\BlacklistWordRule;
use App\Rules\UsernameIsAllowedRule;
use App\Rules\UsernameIsValidRule;

class CompanyRequest extends Request
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
		 

		$usernameChanged = ($this->filled('username') && $this->input('username') != auth()->user()->username);
		// Validation Rules
		$rules = [
			//'gender_id' => ['required', 'not_in:0'],
			'name'      => ['required', 'max:100'],
			'website'      => ['nullable','url'],
			'establishment_year'      => ['nullable','date_format:Y', new EstablishmentYearRule()],
			'about_us'  => [new BlacklistWordRule()],
			//'username'  => ['between:3,100', new UsernameIsAllowedRule($router, $files, $config)],
			//'phone'     => ['required', 'max:20'],
			//'email'     => ['required', 'email', new BlacklistEmailRule(), new BlacklistDomainRule()],
			//'username'  => ['between:3,100', new UsernameIsValidRule(), new UsernameIsAllowedRule($router, $files, $config)],
		];

		//Username
		if ($usernameChanged) {
			$rules['username'][] = 'required';
			$rules['username'][] = 'unique:users,username';
		}

		if($this->filled('filename') ){

			$rules['filename'] = [ 'mimes:' . getUploadFileTypes('file'), 'max:' . (int)config('settings.upload.max_file_size', 1000)];
		}

		 
	//	dd($rules);
		return $rules;
	}
	
	/**
	 * @return array
	 */
	public function attributes()
	{
		$messages = [
			'establishment_year' => 'Please enter number in Establishment Year example: 2001',
			'username' => 'company url',
		];
		
		return $messages;
	}
}
