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

namespace App\Http\Middleware;

use Closure;
use Mews\Purifier\Facades\Purifier;

class TransformInput
{
	/**
	 * @param \Illuminate\Http\Request $request
	 * @param Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (in_array(strtolower($request->method()), ['post', 'put', 'patch'])) {
			$this->processBeforeValidation($request);
		}
		
		return $next($request);
	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 */
	public function processBeforeValidation($request)
	{
		// Don't apply this to the Admin Panel
		if (isFromAdminPanel()) {
			return;
		}
		
		$input = $request->all();
		
		// title
		if ($request->filled('title')) {
			$input['title'] = strCleanerLite($request->input('title'));
			$input['title'] = onlyNumCleaner($input['title']);
		}
		
		// name
		if ($request->filled('name')) {
			$input['name'] = strCleanerLite($request->input('name'));
			if ($request->filled('email') || $request->filled('phone')) {
				$input['name'] = onlyNumCleaner($input['name']);
			}
		}
		
		// contact_name
		if ($request->filled('contact_name')) {
			$input['contact_name'] = strCleanerLite($request->input('contact_name'));
			$input['contact_name'] = onlyNumCleaner($input['contact_name']);
		}
		
		// description
		if ($request->filled('description')) {
			if (config('settings.single.simditor_wysiwyg') || config('settings.single.ckeditor_wysiwyg')) {
				try {
					$input['description'] = Purifier::clean($request->input('description'));
				} catch (\Exception $e) {
					$input['description'] = $request->input('description');
				}
			} else {
				$input['description'] = strCleaner($request->input('description'));
			}
		}

		if ($request->filled('about_us')) {
			if (config('settings.single.simditor_wysiwyg') || config('settings.single.ckeditor_wysiwyg')) {
				try {
					$input['about_us'] = Purifier::clean($request->input('about_us'));
				} catch (\Exception $e) {
					$input['about_us'] = $request->input('about_us');
				}
			} else {
				$input['about_us'] = strCleaner($request->input('about_us'));
			}
		}

		if ($request->filled('why_us')) {
			if (config('settings.single.simditor_wysiwyg') || config('settings.single.ckeditor_wysiwyg')) {
				try {
					$input['why_us'] = Purifier::clean($request->input('why_us'));
				} catch (\Exception $e) {
					$input['why_us'] = $request->input('why_us');
				}
			} else {
				$input['why_us'] = strCleaner($request->input('why_us'));
			}
		}
		
		// price
		if ($request->filled('price')) {
			$input['price'] = str_replace(',', '.', $request->input('price'));
			$input['price'] = preg_replace('/[^0-9\.]/', '', $input['price']);
		}
		
		// phone
		/*if ($request->filled('phone')) {
			$input['phone'] = phoneFormatInt($request->input('phone'), $request->input('country_code', session('country_code')));
		}*/
		
		// login (phone)
		if ($request->filled('login')) {
			$loginField = getLoginField($request->input('login'));
			if ($loginField == 'phone') {
				$input['login'] = phoneFormatInt($request->input('login'), $request->input('country_code', session('country_code')));
			}
		}
		
		$request->replace($input);
	}
}
