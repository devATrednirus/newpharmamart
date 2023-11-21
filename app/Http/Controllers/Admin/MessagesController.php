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

class MessagesController extends PanelController
{
	use VerificationTrait;
	
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\Message');

		$this->xPanel->with_trashed = true;
	 
		$this->xPanel->with([ 'sender', 'receiver']);
		
		$this->xPanel->setRoute(admin_uri('messages'));
		$this->xPanel->setEntityNameStrings('Direct Message', 'Direct Messages');
		$this->xPanel->denyAccess(['create','delete']);
		$this->xPanel->removeAllButtons(); // Remove also: 'create' & 'reorder' buttons
		 $this->xPanel->enableDetailsRow('details_row');
		 //$this->xPanel->allowAccess(['reorder', 'details_row']);
		 $this->xPanel->removeButton('update');
		 //$this->xPanel->withTrash();
		if (!request()->input('order')) {
			$this->xPanel->orderBy('created_at', 'DESC');
		}
		$this->xPanel->addClause('where','message_id');
		$this->xPanel->addClause('where','quick_message_id');

		$entry = null;
		if (request()->segment(4) == 'edit') {
			$entry = $this->xPanel->model->withTrashed()->find(request()->segment(3));

		 
			if(!$entry || ($entry->is_sent!="0"  && $entry->shared()->count() >=15 ) ){

		 		abort(404, 'Invalid request');
			}

			//dd($entry);
		}


		//
 
		// Filters
		// -----------------------
	 
		// -----------------------
		
		$this->xPanel->addFilter([
			'name'  => 'sent_at',
			'type'  => 'date_range',
			'label' => 'Sent at',
		],
		false,
		function ($value) {
			$dates = json_decode($value);
			$this->xPanel->addClause('whereDate', 'sent_at', '>=', $dates->from);
			$this->xPanel->addClause('whereDate', 'sent_at', '<=', $dates->to);
		});


		/*$this->xPanel->addFilter([
			'name'  => 'from_to',
			'type'  => 'date_range',
			'label' => trans('admin::messages.Date range'),
		],
		false,
		function ($value) {
			$dates = json_decode($value);
			$this->xPanel->addClause('whereDate', 'created_at', '>=', $dates->from);
			$this->xPanel->addClause('whereDate', 'created_at', '<=', $dates->to);
		});*/


		$this->xPanel->addFilter([
			'name'  => 'type',
			'type'  => 'dropdown',
			'label' => 'Query Type',
		], [
			'direct' => 'direct',
			'quick' => 'quick' ,
			'purchased' => 'purchased',
		], function ($value) {
			if($value!="purchased"){
				
				$this->xPanel->addClause('where', 'type', '=', $value);

			}
			else{

				$this->xPanel->query = $this->xPanel->query->whereHas('buy');
			}
		});

		$this->xPanel->addFilter([
			'name'  => 'is_sent',
			'type'  => 'dropdown',
			'label' => 'Is Shared',
		], [
			'No'  => 'No',
			'Yes'   => 'Yes',
			'blocked'   => 'Blocked' 
		], function ($value) {
			if($value=="No"){
				$this->xPanel->addClause('where', 'is_sent', '=', '0');
			}
			else if($value=="blocked"){
				$this->xPanel->addClause('where', 'blocked', '=', '1');
			}
			else{
				$this->xPanel->addClause('where', 'is_sent', '=', '1');
			}
			 
		});

		$this->xPanel->addFilter([
			'name'  => 'is_submitted',
			'type'  => 'dropdown',
			'label' => 'Is Submitted',
		], [
			'No'  => 'No',
			'Yes'   => 'Yes' 
		], function ($value) {
			if($value=="No"){
				$this->xPanel->addClause('where', 'drugs_license');
			}
			else{
				$this->xPanel->addClause('where', 'drugs_license', '<>', null);
			}
			 
		});


		$this->xPanel->addFilter([
			'name'  => 'verified_status',
			'type'  => 'dropdown',
			'label' => 'Verified Status',
		], [
			'No'  => 'No',
			'By OTP'=>'By OTP',
			'Verified By Phone'=>'Verified By Phone',
			'Invalid Number'=>'Invalid Number',
			  'Posted by Mistake'=>'Posted by Mistake',
			  'Not Interested'=>'Not Interested',
			  'Not Required now'=>'Not Required now',
			  'Call Not Picked'=>'Call Not Picked',
			  'Testing'=>'Testing',
			  'Duplicate'=>'Duplicate',
			  //'Company Only'=>'Company Only' 
		], function ($value) {
			if($value=="No"){
				$this->xPanel->query = $this->xPanel->query->whereHas('sender', function ($query) use ($value) {
				$query->where(function($query) use ($value){
					$query->where('verified_phone', '!=', "1");
				});
			});
			}
			else if($value=="By OTP"){
				$this->xPanel->query = $this->xPanel->query->where(function($query){

						$query->whereNull('verified_status')->orWhere('verified_status','By OTP');
				})->whereHas('sender', function ($query) use ($value) {
					$query->where(function($query) use ($value){
						$query->whereNotNull('phone')->where('verified_phone', '=', "1");
					});
				});
				$this->xPanel->addClause('where', 'drugs_license', '<>', null);
				//$this->xPanel->addClause('where', 'verified_status', '<>','Verified By Phone');
				//$this->xPanel->addClause('where', 'verified_status', '<>','Invalid Number');
			}
			else{
				$this->xPanel->addClause('where', 'verified_status', '=',$value);
			}
			 
		});

		/*$this->xPanel->addFilter([
			'name'  => 'session_id',
			'type'  => 'text',
			'label' => 'Session ID',
		],
		false,
		function ($value) {
			$this->xPanel->addClause('where', 'session_id', '=', "$value");
		});*/
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
			'name'  => 'from_name',
			'type'  => 'text',
			'label' => 'Sender Name',
		],
		false,
		function ($value) {
			$this->xPanel->addClause('where', 'from_name', 'LIKE', "$value%");
		});

		$this->xPanel->addFilter([
			'name'  => 'from_phone',
			'type'  => 'text',
			'label' => 'Sender Phone',
		],
		false,
		function ($value) {
			$this->xPanel->addClause('where', 'from_phone', 'LIKE', "%$value%");
		});

	/*	$this->xPanel->addFilter([
			'name'  => 'from_phone',
			'type'  => 'text',
			'label' => 'Phone',
		],
		false,
		function ($value) {
			$this->xPanel->addClause('where', 'phone', 'LIKE', "%$value%");
		});*/

		$this->xPanel->addFilter([
			'name'  => 'sender',
			'type'  => 'text',
			'label' => 'Sender',
		],
		false,
		function ($value) {
			$this->xPanel->query = $this->xPanel->query->whereHas('sender', function ($query) use ($value) {
				$query->where(function($query) use ($value){
					$query->where('first_name', 'LIKE', "%$value%")->orWhere('last_name', 'LIKE', "%$value%");
				});
			});
		});

		

		$this->xPanel->addFilter([
			'name'  => 'receiver',
			'type'  => 'text',
			'label' => 'Receiver',
		],
		false,
		function ($value) {
			$this->xPanel->query = $this->xPanel->query->whereHas('receiver', function ($query) use ($value) {
				$query->where('name', 'LIKE', "%$value%");
			});
		});

		$this->xPanel->addFilter([
			'name'  => 'post',
			'type'  => 'text',
			'label' => 'Post',
		],
		false,
		function ($value) {
			$this->xPanel->query = $this->xPanel->query->whereHas('post', function ($query) use ($value) {
				$query->where('title', 'LIKE', "%$value%");
			});
		});
		// -----------------------
		 

		$this->xPanel->addFilter([
				'name'  => 'search_profession',
				'type'  => 'dropdown',
				'label' => 'Profession',
			], [
				'blank' => 'Blank',
				'Student'=>'Student',
				  'Retailer'=>'Retailer',

					'Doctor'=>'Doctor',
					'Distributer'=>'Distributer',
					'Wholesaler'=>'Wholesaler',
					'Medical Rap'=>'Medical Rap',
			], function ($value) {
				if ($value == 'blank') {
					$this->xPanel->addClause('where', 'profession', '=', null);
				}
				else{					

					$this->xPanel->addClause('where', 'profession', '=', $value);
				
				}
			});

		$this->xPanel->addFilter([
			'name'  => 'message',
			'type'  => 'text',
			'label' => 'Message',
		],
		false,
		function ($value) {
			$this->xPanel->addClause('where', 'message', 'LIKE', "%$value%");
		});
		 
		
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		
		$this->xPanel->addColumn([
			'name'  => 'id',
			'label' => 'ID',
			//'type'  => 'datetime',
		]);

		$this->xPanel->addColumn([
			'name'  => 'sent_at',
			'label' => 'Sent at',
			//'type'  => 'datetime',
		]);

		$this->xPanel->addColumn([
			'name'  => 'created_at',
			'label' => trans("admin::messages.Date"),
			'type'  => 'datetime',
		]);


		$this->xPanel->addColumn([
			'name'  => 'type',
			'label' => 'Query Type',
			'type'  => 'string',
		]);

		$this->xPanel->addColumn([
			'name'          => 'is_posted',
			'label'         => 'Is Shared',
			'type'          => 'model_function',
			'function_name' => 'getIsPostedHtml',
		]);

		$this->xPanel->addColumn([
			'name'          => 'is_submitted',
			'label'         => 'Is Submitted',
			'type'          => 'model_function',
			'function_name' => 'isSubmittedHtml',
		]);


		

		$this->xPanel->addColumn([
			'name'  => 'session_id',
			'label' => "Session ID",
			'type'          => 'model_function',
			'function_name' => 'getSearchesHtml',
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


		if(auth()->user()->id=="1"){
			$this->xPanel->addColumn([
				'name'          => 'verified_status',
				'label'         => 'Verified Status',
				'type'          => 'model_function',
				'function_name' => 'getVerifiedStatusHtml',
			]);
			$this->xPanel->addColumn([
				'name'          => 'verified_phone',
				'label'         => trans("admin::messages.Verified Phone"),
				'type'          => 'model_function',
				'function_name' => 'getVerifiedPhoneHtml',
			]);
		}

		$this->xPanel->addColumn([
					'name'  => 'profession',
					'label' => 'Profession',
				]);

		/*$this->xPanel->addColumn([
			'name'          => 'from_user_id',
			'label'         => 'Sender',
			'type'          => 'model_function',
			'function_name' => 'getSenderHtml',
		]);*/


		$this->xPanel->addColumn([
			'name'  => 'from_name',
			'label' => 'Sender Name',
			'type'  => 'text',
		]);

		$this->xPanel->addColumn([
			'name'  => 'from_phone',
			'label' => 'Sender Phone',
			'type'  => 'text',
		]);



		 

		
		

		

		$this->xPanel->addColumn([
			'name'          => 'to_user_id',
			'label'         => 'Receiver',
			'type'          => 'model_function',
			'function_name' => 'getReceiverHtml',
		]);

		$this->xPanel->addColumn([
			'name'          => 'post_id',
			'label'         => 'Post',
			'type'          => 'model_function',
			'function_name' => 'getPostTitleHtml',
		]);

		$this->xPanel->addColumn([
			'name'  => 'message',
			'label' => 'Message',
			'type'  => 'text',
		]);

		

		// FIELDS
		$this->xPanel->addField([
			'name'       => 'from_name',
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
			'name'       => 'from_phone',
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
			'name'       => 'message',
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
			'label'       => 'City',
			'name'        => 'city_id',
			'type'        => 'select2_from_array',
			'options'     => getCities(false),
			'allows_null' => true,
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
	

		/*$this->xPanel->addField([
			'name'       => 'city',
			'label'      => 'City',
			'type'       => 'text',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		 
		]);*/

		/*$this->xPanel->addField([
			'name'       => 'looking_for',
			'label'      => 'Looking For',
			'type'       => 'text',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		 
		]);*/

		//dd($entry);
		if($entry){

			$this->xPanel->addField([
				'label'       => trans("admin::messages.Category"),
				'name'        => 'category_id',
				'type'        => 'select2_from_array',
				'options'     => $this->categories(),
				'allows_null' => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
		}


		$this->xPanel->addField([
			'name'       => 'limit_sent',
			'label'      => 'Limit sharing',
			'type'       => 'text',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		 
		]);
		 	
			
		 	if($entry){

				$this->xPanel->addField([
					'name'        => 'verified_status',
					'label'       => 'Verified Status',
					'type'        => 'select2_from_array',
					'options'     => [
									  '' => '--Select--',
									  'By OTP'=>'By OTP',
									  'Verified By Phone'=>'Verified By Phone',
									  'Invalid Number'=>'Invalid Number',
									  'Posted by Mistake'=>'Posted by Mistake',
									  'Not Interested'=>'Not Interested',
									  'Not Required now'=>'Not Required now',
									  'Call Not Picked'=>'Call Not Picked',
									  'Testing'=>'Testing',
			  						  'Duplicate'=>'Duplicate',
			  						//  'Company Only'=>'Company Only' 
									],
					'allows_null' => false,
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
					],
				]);

				if($entry->type=="direct" && $entry->post_id=="0"){


					$this->xPanel->addField([
						'name'        => 'company_only',
						'label'       => 'Company Only',
						'type'        => 'select2_from_array',
						'options'     => [
										  '' => '--Select--',
										  'Yes'=>'Yes',
										  'No'=>'No'
										],
						'allows_null' => false,
						'wrapperAttributes' => [
							'class' => 'form-group col-md-6',
						],
					]);
				}
		 	}


		 
	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}
	
	public function update(UpdateRequest $request)
	{

		
		$entry = $this->xPanel->getEntry($request->input('id'));
		

		if($request->input('city_id')){
            $city = \App\Models\City::find($request->input('city_id')); 

            if($city){
                
               request()->merge(['city' => $city->name]);
            }
        }

       
        if($request->input('category_id')){
            $category = \App\Models\Category::find($request->input('category_id')); 
            
            if($category){
                
               request()->merge(['looking_for' => $category->name]);
            }
        }

		//$entry->deleted_at=null;
		request()->merge(['is_updated_by_admin' => '1','is_sent' => '0','deleted_at' => null,'created_at'=>\Carbon\Carbon::now()]);

		//$entry->save();

		
		$user = User::find($entry->from_user_id);

		if($user && $user->id!='1'){

			if($user->phone!=null && $user->verified_phone != 1){

				if($request->input('verified_status')=="Verified By Phone"){
					$user->verified_phone= "1";	

					$user->verified_by = $user->id;			
				}

			}

			if($user->email==null && $request->from_email){

	        	$check = User::where('email',$request->from_email)->count();

	        	if($check=="0"){

	        		$user->email =  $request->from_email;
	        		

	        	}

			}

			if(!$user->city_id && $request->city_id){
					
				$user->city_id = $request->city_id;
			}

			$input = $request->only(['location_for_franchise','address1','drugs_license','have_gst_number','minimum_investment','purchase_period','call_back_time','profession']);


		 

			foreach ($input as $key => $value) {
					 
				$user->$key = $value;
			}

			if($user->isDirty()){

				//dd($user);
				$user->save();

			}
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
			//$tab[$entry->tid] = $entry->name;
			$tab[$entry->name] = [];
			$subEntries = Category::trans()->where('parent_id', $entry->id)->orderBy('lft')->get();
			if (!empty($subEntries)) {
				foreach ($subEntries as $subEntrie) {
					$tab[$entry->name][$subEntrie->tid] = "---| " . $subEntrie->name;
				}
			}
		}
		
		return $tab;
	}
}
