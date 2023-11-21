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

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Models\PostType;
use App\Models\Category;
use App\Models\User;
use App\Models\City;
use App\Http\Requests\Admin\QuickQueryRequest as StoreRequest;
use App\Http\Requests\Admin\QuickQueryRequest as UpdateRequest;

class QuickMessageController extends PanelController
{
	use VerificationTrait;
	
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\QuickMessage');
		$this->xPanel->with(['category', 'user', 'city']);
		
		$this->xPanel->setRoute(admin_uri('queries'));
		$this->xPanel->setEntityNameStrings('Quick Message', 'Quick Messages');
		$this->xPanel->denyAccess(['create']);
		$this->xPanel->removeButton('update');
		 $this->xPanel->enableDetailsRow('details_row');
		if (!request()->input('order')) {
			$this->xPanel->orderBy('updated_at', 'DESC');
		}

		$entry = null;
		if (request()->segment(4) == 'edit') {
			$entry = $this->xPanel->model->find(request()->segment(3));
			if(!$entry || $entry->drugs_license!=null ){

		 	abort(404, 'Invalid request');
			}

			//dd($entry);
		}
 
		// Filters
		// -----------------------
	 
		// -----------------------
		$this->xPanel->addFilter([
			'name'  => 'from_to',
			'type'  => 'date_range',
			'label' => trans('admin::messages.Date range'),
		],
		false,
		function ($value) {
			$dates = json_decode($value);
			$this->xPanel->addClause('where', 'created_at', '>=', $dates->from);
			$this->xPanel->addClause('where', 'created_at', '<=', $dates->to);
		});

		$this->xPanel->addFilter([
			'name'  => 'session_id',
			'type'  => 'text',
			'label' => 'Session ID',
		],
		false,
		function ($value) {
			$this->xPanel->addClause('where', 'session_id', '=', "$value");
		});

		$this->xPanel->addFilter([
			'name'  => 'is_posted',
			'type'  => 'dropdown',
			'label' => 'Is Shared',
		], [
			'No'  => 'No',
			'Yes'   => 'Yes' 
		], function ($value) {
			if($value=="No"){
				$this->xPanel->addClause('where','drugs_license');
			}
			else{
				$this->xPanel->addClause('where','drugs_license','!=',null);
			}
			 
		});
		// -----------------------
		
		// -----------------------
		
		/*$this->xPanel->addFilter([
			'name'  => 'user',
			'type'  => 'text',
			'label' => 'User',
		],
		false,
		function ($value) {
			$this->xPanel->query = $this->xPanel->query->whereHas('user', function ($query) use ($value) {
				$query->where(function($query) use ($value){
					$query->where('first_name', 'LIKE', "%$value%")->orWhere('last_name', 'LIKE', "%$value%")->orWhere('name', 'LIKE', "%$value%");
				});
			});
		});*/

		$this->xPanel->addFilter([
			'name'  => 'name',
			'type'  => 'text',
			'label' => 'Name',
		],
		false,
		function ($value) {
			$this->xPanel->addClause('where', 'name', 'LIKE', "%$value%");
		});

		$this->xPanel->addFilter([
			'name'  => 'phone',
			'type'  => 'text',
			'label' => 'Phone',
		],
		false,
		function ($value) {
			$this->xPanel->addClause('where', 'phone', 'LIKE', "%$value%");
		});

		$this->xPanel->addFilter([
			'name'  => 'category',
			'type'  => 'text',
			'label' => 'Category',
		],
		false,
		function ($value) {
			$this->xPanel->query = $this->xPanel->query->whereHas('category', function ($query) use ($value) {
				$query->where('name', 'LIKE', "%$value%");
			});
		});
		// -----------------------
		$this->xPanel->addFilter([
			'name'  => 'city',
			'type'  => 'text',
			'label' => trans('admin::messages.City'),
		],
		false,
		function ($value) {
			$this->xPanel->query = $this->xPanel->query->whereHas('city', function ($query) use ($value) {
				$query->where('name', 'LIKE', "%$value%");
			});
		});

		$this->xPanel->addFilter([
			'name'  => 'query',
			'type'  => 'text',
			'label' => 'Query',
		],
		false,
		function ($value) {
			$this->xPanel->addClause('where', 'query', 'LIKE', "%$value%");
		});
		 
		
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		 
		$this->xPanel->addColumn([
			'name'  => 'created_at',
			'label' => trans("admin::messages.Date"),
			'type'  => 'datetime',
		]);

		$this->xPanel->addColumn([
			'name'  => 'session_id',
			'label' => "Session ID",
			'type'          => 'model_function',
			'function_name' => 'getSearchesHtml',
		]);

		$this->xPanel->addColumn([
			'name'          => 'is_posted',
			'label'         => 'Is Shared',
			'type'          => 'model_function',
			'function_name' => 'getIsPostedHtml',
		]);

		$this->xPanel->addColumn([
			'name'          => 'shared_count',
			'label'         => 'Shared Count',
			'type'          => 'model_function',
			'function_name' => 'getSharedCounteHtml',
		]);
	/*	*/
		 
		/*$this->xPanel->addColumn([
			'name'          => 'contact_name',
			'label'         => 'User',
			'type'          => 'model_function',
			'function_name' => 'getUserNameHtml',
		]);*/

		$this->xPanel->addColumn([
			'name'  => 'name',
			'label' => 'Name',
			'type'  => 'text',
		]);

		$this->xPanel->addColumn([
			'name'  => 'phone',
			'label' => 'Phone',
			'type'  => 'text',
		]);


		
		$this->xPanel->addColumn([
			'name'          => 'categoty_id',
			'label'         => trans("admin::messages.Category"),
			'type'          => 'model_function',
			'function_name' => 'getCategoryHtml',
		]);

		$this->xPanel->addColumn([
			'name'          => 'city_id',
			'label'         => trans("admin::messages.City"),
			'type'          => 'model_function',
			'function_name' => 'getCityHtml',
		]);

		$this->xPanel->addColumn([
			'name'  => 'query',
			'label' => 'Query',
			'type'  => 'text',
		]);

		$this->xPanel->addColumn([
			'name'  => 'ip_address',
			'label' => 'IP Address',
			'type'  => 'ipaddress',
		]);



		// FIELDS
		$this->xPanel->addField([
			'name'       => 'name',
			'label'      => trans("admin::messages.Name"),
			'type'       => 'text',
			'attributes' => [
				'placeholder' => trans("admin::messages.Name"),
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'       => 'phone',
			'label'      => 'Phone',
			'type'       => 'text',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		 
		]);
		$this->xPanel->addField([
			'name'       => 'from_email',
			'label'      => 'Email',
			'type'       => 'text',
			'attributes' => [
				'placeholder' => 'Email',
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);


		$this->xPanel->addField([
			'name'       => 'query',
			'label'      => 'Query',
			'type'       => 'textarea',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		 
		]);

		$this->xPanel->addField([
				'name'        => 'drugs_license',
				'label'       => 'Drugs License',
				'type'        => 'select2_from_array',
				'options'     => ['No'=>'No','Yes'=>'Yes'],
				'allows_null' => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);

		$this->xPanel->addField([
				'name'        => 'have_gst_number',
				'label'       => 'Have GST Number',
				'type'        => 'select2_from_array',
				'options'     => ['No'=>'No','Yes'=>'Yes'],
				'allows_null' => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);

		$this->xPanel->addField([
				'name'        => 'minimum_investment',
				'label'       => 'Minimum Investment',
				'type'        => 'select2_from_array',
				'options'     => ['5000 Rs to 25000 Rs'=>'5000 Rs to 25000 Rs',
									'25000 Rs to 50000 Rs'=>'25000 Rs to 50000 Rs',
									'Above 50000 Rs'=>'Above 50000 Rs',


								],
				'allows_null' => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);

		$this->xPanel->addField([
				'name'        => 'purchase_period',
				'label'       => 'Purchase Period',
				'type'        => 'select2_from_array',
				'options'     => ['1 Days - 15 Days'=>'1 Days - 15 Days',
									'16 Days - 30 Days'=>'16 Days - 30 Days',
									'More Than 30 Days'=>'More Than 30 Days',
								],
				'allows_null' => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);

		$this->xPanel->addField([
				'name'        => 'call_back_time',
				'label'       => 'Call Back Time',
				'type'        => 'select2_from_array',
				'options'     => ['10 AM - 12 Noon'=>'10 AM - 12 Noon',
								  '12 Noon - 2 PM'=>'12 Noon - 2 PM',

									'2 PM - 4 PM'=>'2 PM - 4 PM',
									'4 PM - 6 PM'=>'4 PM - 6 PM',
									'After 6 PM'=>'After 6 PM',
									'Any Time'=>'Any Time',
								],
				'allows_null' => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);

		$this->xPanel->addField([
				'name'        => 'profession',
				'label'       => 'Profession',
				'type'        => 'select2_from_array',
				'options'     => ['Student'=>'Student',
								  'Retailer'=>'Retailer',

									'Doctor'=>'Doctor',
									'Distributer'=>'Distributer',
									'Wholesaler'=>'Wholesaler',
									'Medical Rap'=>'Medical Rap',
								],
				'allows_null' => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);


		$this->xPanel->addField([
			'name'       => 'location',
			'label'      => 'Location',
			'type'       => 'text',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		 
		]);

		$this->xPanel->addField([
			'name'       => 'address',
			'label'      => 'Address',
			'type'       => 'text',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		 
		]);


		$this->xPanel->addField([
			'name'       => 'city_name',
			'label'      => 'City',
			'type'       => 'text',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		 
		]);

		$this->xPanel->addField([
			'name'       => 'looking_for',
			'label'      => 'Looking For',
			'type'       => 'text',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		 
		]);

		$this->xPanel->addField([
			'label'       => trans("admin::messages.Category"),
			'name'        => 'category_id',
			'type'        => 'select2_from_array',
			'options'     => $this->categories(),
			'allows_null' => false,
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);

		

		 
	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}
	
	public function update(UpdateRequest $request)
	{

		//dd($request->input());
		$entry = $this->xPanel->getEntry($request->input('id'));
		
		$user = User::find($entry->user_id);

		if($user->email==null && $request->from_email){

        	$check = User::where('email',$request->from_email)->count();

        	if($check=="0"){

        		$user->email =  $request->from_email;
        		

        	}

		}

		if(!$user->city_id && $request->city_name){
			$city = City::where('name',$request->city_name)->first();

			if($city){
				
				$user->city_id = $city->id;

			}
		}
		 
		$input = $request->only(['location_for_franchise','address1','drugs_license','have_gst_number','minimum_investment','purchase_period','call_back_time','profession','website','about_us','why_us','our_product']);


		 

		foreach ($input as $key => $value) {
				 
			$user->$key = $value;
		}

		if($user->isDirty()){

			$user->save();

		}

		return parent::updateCrud();
	}
	
	public function postType()
	{
		$entries = PostType::trans()->get();
		
		return $this->getTranslatedArray($entries);
	}
	
	public function categories()
	{
		$entries = Category::trans()->where('parent_id', 0)->orderBy('lft')->get();
		if ($entries->count() <= 0) {
			return [];
		}
		
		$tab = [];
		foreach ($entries as $entry) {
			$tab[$entry->tid] = $entry->name;
			
			$subEntries = Category::trans()->where('parent_id', $entry->id)->orderBy('lft')->get();
			if (!empty($subEntries)) {
				foreach ($subEntries as $subEntrie) {
					$tab[$subEntrie->tid] = "---| " . $subEntrie->name;
				}
			}
		}
		
		return $tab;
	}
}
