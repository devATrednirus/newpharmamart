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

class SignInRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
 
        $rules = [
            'signin_phone'     => ['required','regex:/^[6-9]\d{9}$/'],
        ];
     
        
        return $rules;
    }
    
    /**
     * @return array
     */
    public function messages()
    {
        $messages = [
            'signin_phone.regex' => "Please enter a valid mobile number"
             
        ];
        
        return $messages;
    }
}
