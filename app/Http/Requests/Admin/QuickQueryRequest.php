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

use App\Models\User;
use App\Rules\BetweenRule;

class QuickQueryRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'from_name'  => ['required', new BetweenRule(2, 200)],
         //   'from_email' => ['required','email','max:100'],
            'from_phone' => ['required','phone:IN','max:20'],
            'message'    => ['required', new BetweenRule(5, 500)],
            'drugs_license'    => ['required'],
            'have_gst_number'    => ['required'],
            'minimum_investment'    => ['required'],
            'purchase_period'    => ['required'],
            'call_back_time'    => ['required'],
            'profession'    => ['required'] ,
            'location'    => ['required'],
            'address'    => ['required'],
            'city_id'    => ['required'],
            //'looking_for'    => ['required'],
            //'category_id'    => ['required'],
           // 'pincode'    => ['required', 'regex:/^[1-9][0-9]{5}$/'],
            
            
        ];
    



        
        if($this->id){
            
            if(!in_array($this->verified_status,['Verified By Phone','By OTP'])){
             
                 $rules = [
                  
                    'from_phone' => ['required','phone:IN','max:20']
                    
                ];
            }
            else{
                
                $entry = \App\Models\Message::with('sender')->withTrashed()->find($this->id); 


                if(($entry->type=="direct" && $entry->post_id=="0") || ($entry->type=="quick" && $entry->category_id==null)){
                    $rules['category_id'] =['required'];                
                }
                if($entry &&  $entry->sender->phone!=null && $entry->sender->verified_phone != 1){


                    $rules['verified_status'] =['required'];
                }
            }



            
             
        }

        
        
         
        return $rules;
     
    }

     public function messages()
    {
        $messages = [
            'category_id.required' => trans('admin::messages.The :field is required.', ['field' => trans('admin::messages.category')]),
            'verified_status.required' =>  trans('admin::messages.The :field is required.', ['field' => 'Verification Status']),
        ];
        
        /*
        $messages['category_id.unique_ccf'] = trans('validation.custom_field_unique_rule', [
            'field_1' => trans('admin::messages.category'),
            'field_2' => trans('admin::messages.custom field'),
        ]);
        $messages['category_id.unique_ccf_parent'] = trans('validation.custom_field_unique_parent_rule', [
            'field_1' => trans('admin::messages.category'),
            'field_2' => trans('admin::messages.custom field'),
        ]);
        $messages['category_id.unique_ccf_children'] = trans('validation.custom_field_unique_children_rule', [
            'field_1' => trans('admin::messages.category'),
            'field_2' => trans('admin::messages.custom field'),
        ]);
        
        $messages['field_id.unique_ccf'] = trans('validation.custom_field_unique_rule_field', [
            'field_1' => trans('admin::messages.custom field'),
            'field_2' => trans('admin::messages.category'),
        ]);
        $messages['field_id.unique_ccf_parent'] = trans('validation.custom_field_unique_parent_rule_field', [
            'field_1' => trans('admin::messages.custom field'),
            'field_2' => trans('admin::messages.category'),
        ]);
        $messages['field_id.unique_ccf_children'] = trans('validation.custom_field_unique_children_rule_field', [
            'field_1' => trans('admin::messages.custom field'),
            'field_2' => trans('admin::messages.category'),
        ]);
        */
        
        return $messages;
    }
}
