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

class LoginRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		// If previous page is not the Login page...
		if (!str_contains(url()->previous(), trans('routes.login'))) {
			// Save the previous URL to retrieve it after success or failed login.
			session()->put('url.intended', url()->previous());
		}

      //dd(url()->current());
		  if(strpos(url()->current(),'/admin')) {
        $rules = [
            'email'    => ['required'],
            'password' => ['required', 'min:5', 'max:50'],
        ];
    } else {
      $rules = [
          'login'    => ['required'],
          'password' => ['required', 'min:5', 'max:50'],
      ];
    }
        // reCAPTCHA
        if (config('settings.security.recaptcha_activation')) {
            $rules['g-recaptcha-response'] = ['required'];
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
