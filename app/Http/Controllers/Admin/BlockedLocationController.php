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

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\Request as StoreRequest;
use App\Http\Requests\Admin\Request as UpdateRequest;
use App\Models\SubAdmin1;
use App\Models\City;
use App\Models\User;
use DB;
class BlockedLocationController extends PanelController
{
    public function showBlocked()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->stateEntity = request()->segment(3);

        $this->cityEntity = request()->segment(4);

        //dd($this->parentEntity);
        $state = SubAdmin1::find($this->stateEntity);

        
		if (!$state) {
			abort(404);
		}

		$this->data['state'] = $state;

		$this->data['type'] = 'cities';
		DB::enableQueryLog();
		if($this->cityEntity){

			$city = City::find($this->cityEntity);

			$this->data['city'] = $city;

			$this->data['type'] = 'users';

			$blockedLocations = User::whereHas('locationFilter',function($query){
				$query->where('city_id',$this->cityEntity);
			})->get();
		}
		else{

			$blockedLocations = City::select('cities.id','cities.name','cities.subadmin1_code')->withCount('locationFilter')->has('locationFilter')->where('subadmin1_code',$this->stateEntity)->get();
		}

		//dd(DB::getQueryLog());

	//	dd($blockedLocations);

		
		//
		//DB::raw('count(cities.id) as count');
		
		//

		$this->data['blockedLocations'] = $blockedLocations->chunk(ceil($blockedLocations->count() / 2));

		///dd($blockedLocations);

		return view('admin::dashboard.blocked_location', $this->data);

    }
}
