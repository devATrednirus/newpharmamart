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

namespace App\Rules;

use App\Models\Blacklist;
use Illuminate\Contracts\Validation\Rule;
use App\Models\ProductGroup;

class GroupSlug implements Rule
{
	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		/*$value = strtolower($value);
		
		$savedGroup = ProductGroup::where('user_id', auth()->user()->id)->where('slug', slugify($value))->first();
dd($savedGroup);
		if($value<="1900"){

			return false;
		}
*/
 
		return true;
	}
	
	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return 'Year should be greater than 1900';
	}
}
