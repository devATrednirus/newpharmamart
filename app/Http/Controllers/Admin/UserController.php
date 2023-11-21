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
use App\Http\Requests\Admin\Request;
use App\Http\Requests\Admin\UserRequest as StoreRequest;
use App\Http\Requests\Admin\UserRequest as UpdateRequest;
use App\Models\Gender;
use App\Models\Package;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Larapen\Admin\app\Http\Controllers\PanelController;
use Illuminate\Support\Str;
use App\Models\User;
class UserController extends PanelController
{
	use VerificationTrait;

	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\User');
		$this->xPanel->setRoute(admin_uri('users'));

		if (!request()->input('order')) {
			$this->xPanel->orderBy('created_at', 'DESC');
		}




		$type = "1";
		if(request()->has('type')){
			$type= request()->get('type');
		}

		if($type=="customer"){

			$this->xPanel->setEntityNameStrings('Buyer','Buyers');
			$this->xPanel->addClause('where', 'user_type_id', '=', '2');



			$this->xPanel->denyAccess(['create', 'update']);

		}
		else{
			$this->xPanel->setEntityNameStrings('Seller','Sellers');
			$this->xPanel->addClause('where', 'user_type_id', '!=', '2');
			$this->xPanel->addButtonFromModelFunction('line', 'banners', 'bannerBtn', 'beginning');
		}




		$this->xPanel->addButtonFromModelFunction('line', 'impersonate', 'impersonateBtn', 'beginning');

			$this->xPanel->addField([
   			'name'    => 'created_by',
   			'type'    => 'hidden',
    			'default' => auth()->user()->id,
    		], 'create');

		if(auth()->user()->id!="1"){
			$this->xPanel->denyAccess([ 'update','delete']);
		}
		else{
			$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
			$this->xPanel->removeButton('delete');
			$this->xPanel->addButtonFromModelFunction('line', 'delete', 'deleteBtn', 'end');
		}

		// Filters
		// -----------------------
		$this->xPanel->addFilter([
			'name'  => 'id',
			'type'  => 'text',
			'label' => 'ID',
		],
			false,
			function ($value) {
				$this->xPanel->addClause('where', 'id', '=', $value);
			});
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

		if($type=="customer"){
			// -----------------------
			$this->xPanel->addFilter([
				'name'  => 'first_name',
				'type'  => 'text',
				'label' => 'Name',
			],
				false,
				function ($value) {
					$this->xPanel->addClause('where', 'first_name', 'LIKE', "%$value%");
				});

			/*$this->xPanel->addFilter([
				'name'  => 'last_name',
				'type'  => 'text',
				'label' => 'Last Name',
			],
				false,
				function ($value) {
					$this->xPanel->addClause('where', 'last_name', 'LIKE', "%$value%");
				});*/

		}
		else{
			$this->xPanel->addFilter([
				'name'  => 'name',
				'type'  => 'text',
				'label' => 'Company Name',
			],
				false,
				function ($value) {
					$this->xPanel->addClause('where', 'name', 'LIKE', "%$value%");
				});

			$this->xPanel->addFilter([
			'name'  => 'package_id',
			'type'  => 'select2',
			'label' => 'Package',
		],
			$this->packages(),
			function ($value) {
				$this->xPanel->addClause('where', 'package_id', '=', $value);
			});
		}


			$this->xPanel->addFilter([
				'name'  => 'phone',
				'type'  => 'text',
				'label' => trans('admin::messages.Phone'),
			],
				false,
				function ($value) {
					$this->xPanel->addClause('where', 'phone', 'LIKE', "%$value%");
				});

		// -----------------------
		$this->xPanel->addFilter([
			'name'  => 'city',
			'type'  => 'select2',
			'label' => 'city',
		],
			getCities(),
			function ($value) {
				if($value=="none"){
					$this->xPanel->addClause('where', 'city_id', '=', null);
				}
				else{

					$this->xPanel->addClause('where', 'city_id', '=', $value);
				}
			});



		// -----------------------
		$this->xPanel->addFilter([
			'name'  => 'status',
			'type'  => 'dropdown',
			'label' => trans('admin::messages.Status'),
		], [
			1 => trans('admin::messages.Unactivated'),
			2 => trans('admin::messages.Activated'),
		], function ($value) {
			if ($value == 1) {
				$this->xPanel->addClause('where', 'verified_email', '=', 0);
				$this->xPanel->addClause('orWhere', 'verified_phone', '=', 0);
			}
			if ($value == 2) {
				$this->xPanel->addClause('where', 'verified_email', '=', 1);
				$this->xPanel->addClause('where', 'verified_phone', '=', 1);
			}
		});

		if($type=="customer"){


			$this->xPanel->addFilter([
				'name'  => 'has_email',
				'type'  => 'dropdown',
				'label' => 'Has Email',
			], [
				'No' => 'No',
				'Yes' => 'Yes',
			], function ($value) {
				if ($value == 'No') {
					$this->xPanel->addClause('where', 'email', '=', null);
				}
				if ($value == 'Yes') {
					$this->xPanel->addClause('where', 'email', '<>', '');
				}
			});

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
				'name'  => 'has_name',
				'type'  => 'dropdown',
				'label' => 'Has Name',
			], [
				'No' => 'No',
				'Yes' => 'Yes',
			], function ($value) {
				if ($value == 'No') {
					$this->xPanel->addClause('where', 'first_name', '=', null);
				}
				if ($value == 'Yes') {
					$this->xPanel->addClause('where', 'first_name', '<>', '');
				}
			});
		}
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		if (request()->segment(2) != 'account') {
			// COLUMNS
			$this->xPanel->addColumn([
				'name'  => 'id',
				'label' => '',
				'type'  => 'checkbox',
				'orderable' => false,
			]);
			$this->xPanel->addColumn([
				'name'  => 'created_at',
				'label' => trans("admin::messages.Date"),
				'type'  => 'datetime',
			]);


			if($type=="customer"){
				$this->xPanel->addColumn([
					'name'  => 'first_name',
					'label' => 'Name',
				]);
				$this->xPanel->addColumn([
					'name'  => 'profession',
					'label' => 'Profession',
				]);
			}
			else{

				$this->xPanel->addColumn([
					'name'          => 'price', // Put unused field column
					'label'         => 'Logo',
					'type'          => 'model_function',
					'function_name' => 'getPictureHtml',
				]);

				$this->xPanel->addColumn([
					'name'  => 'name',
					'label' => 'Company Name',
				]);
			}
			$this->xPanel->addColumn([
				'name'  => 'email',
				'label' => trans("admin::messages.Email"),
			]);


			$this->xPanel->addColumn([
				'label'         => 'City',
				'name'          => 'city_id',
				'type'          => 'model_function',
				'function_name' => 'getCityHtml',
			]);



			if($type!="customer"){
				$this->xPanel->addColumn([
					'label'         => 'Created By',
					'name'          => 'created_by',
					'type'          => 'model_function',
					'function_name' => 'getCreatedByHtml',
				]);

				$this->xPanel->addColumn([
					'label'         => 'Package',
					'name'          => 'package_code',
					'type'          => 'model_function',
					'function_name' => 'getPackageHtml',
				]);
			}

				$this->xPanel->addColumn([
					'label' => trans('admin::messages.Phone'),
					'name'          => 'phone',

				]);



			if(auth()->user()->id=="1"){
				$this->xPanel->addColumn([
					'name'          => 'verified_email',
					'label'         => trans("admin::messages.Verified Email"),
					'type'          => 'model_function',
					'function_name' => 'getVerifiedEmailHtml',
				]);
				$this->xPanel->addColumn([
					'name'          => 'verified_phone',
					'label'         => trans("admin::messages.Verified Phone"),
					'type'          => 'model_function',
					'function_name' => 'getVerifiedPhoneHtml',
				]);
			}
			$this->xPanel->addField([
				'name'              => 'name',
				'label'             => 'Company Name',
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => 'Company Name',
				],
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);

			$this->xPanel->addField([
				'name'              => 'username',
				'label'             => 'Company URL',
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => 'Company URL',
				],
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);

			// FIELDS
			$emailField = [
				'name'       => 'email',
				'label'      => trans('admin::messages.Email'),
				'type'       => 'email',
				'attributes' => [
					'placeholder' => trans('admin::messages.Email'),
				],
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				]
			];
			$this->xPanel->addField($emailField);

			$passwordField = [
				'name'       => 'password',
				'label'      => trans('admin::messages.Password'),
				'type'       => 'password',
				'attributes' => [
					'placeholder' => trans('admin::messages.Password'),
				],
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				]
			];
			$this->xPanel->addField($passwordField);//, 'create'
			if(auth()->user()->id=="1"){

				$this->xPanel->addField([
					'label'             => 'Package',
					'name'              => 'package_id',
					'type'              => 'select2_from_array',
					'options'           => $this->packages(),
					'allows_null'       => false,
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
					],
				]);

				$emailField = [
					'name'       => 'package_start_date',
					'label'      => 'Package Start Date',
					'type'       => 'text',
					'attributes' => [
						'placeholder' => 'Package Start Date',
						'readonly' => 'readonly',
					],
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
					]
				];
				$this->xPanel->addField($emailField);

				$emailField = [
					'name'       => 'package_end_date',
					'label'      => 'Package End Date',
					'type'       => 'date',
					'attributes' => [
						'placeholder' => 'Package End Date',
					],
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
					]
				];
				$this->xPanel->addField($emailField);
			}
			$this->xPanel->addField([
				'label'             => trans('admin::messages.Gender'),
				'name'              => 'gender_id',
				'type'              => 'select2_from_array',
				'options'           => $this->gender(),
				'allows_null'       => false,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);

			$this->xPanel->addField([
				'name'              => 'first_name',
				'label'             => 'First Name',
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => 'First Name',
				],
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);

			$this->xPanel->addField([
				'name'              => 'last_name',
				'label'             => 'Last Name',
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => 'Last Name',
				],
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);

			$this->xPanel->addField([
				'name'              => 'phone',
				'label'             => trans('admin::messages.Phone'),
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => trans('admin::messages.Phone'),
				],
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);

			$countryField = [
				'label'             => trans("admin::messages.Country"),
				'name'              => 'country_code',
				'model'             => 'App\Models\Country',
				'entity'            => 'country',
				'attribute'         => 'asciiname',
				'type'              => 'select2',
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			];
			$this->xPanel->addField($countryField);


			$notifcationField = [
				'name'       => 'email_to_send',
				'label'      => 'Notifcation Email',
				'type'       => 'email',
				'attributes' => [
					'placeholder' =>'Notifcation Email',
				],
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				]
			];
			$this->xPanel->addField($notifcationField);


			$this->xPanel->addField([
				'name'              => 'sms_to_send',
				'label'             => 'SMS mobile number',
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => 'SMS mobile number',
				],
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);



			if(auth()->user()->id=="1"){
				$this->xPanel->addField([
					'name'              => 'domain',
					'label'             => 'Whitelabeled Domain',
					'type'              => 'text',
					'attributes'        => [
						'placeholder' => 'Whitelabeled Domain',
					],
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
					],
				]);

				$this->xPanel->addField([
					'name'        => 'template',
					'label'       => 'Template',
					'type'        => 'select2_from_array',
					'options'     => ['template2'=>'Template 2'],
					'allows_null' => true,
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
					],
				]);

				$this->xPanel->addField([
					'name'        => 'color',
					'label'       => 'Color',
					'type'        => 'select2_from_array',
					'options'     => ['colourv2'=>'color v2 - Green','colourv3'=>'color v3 - Dark Green','colourv4'=>'color v4 - Cyan Green','colourv5'=>'color v5 - Purple'],
					'allows_null' => true,
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
					],
				]);

				$this->xPanel->addField([
					'name'        => 'buy_leads_alerts',
					'label'       => 'Buy Leads Alerts',
					'type'        => 'select2_from_array',
					'options'     => ['Yes'=>'Yes','No'=>'No'],
					'allows_null' => false,
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
					],
				]);
			$this->xPanel->addField([
				'name'              => 'api_key',
				'label'             => 'API Key',
				'type'              => 'text',
				'attributes'        => [
					'readonly' => 'readonly',
				],
				'wrapperAttributes' => [
					'class' => 'form-group col-md-8',
				]
			]);



			$this->xPanel->addField([
				'name'              => 'create_key',
				'label'             => 'Generate API Key',
				'type'              => 'checkbox',
			 	'value' => 'Generate',
				'wrapperAttributes' => [
					'class' => 'form-group col-md-4',
				]
			]);

			$phoneHiddenField = [
				'name'              => 'phone_hidden',
				'label'             => trans("admin::messages.Phone hidden"),
				'type'              => 'checkbox',
			];
			$this->xPanel->addField($phoneHiddenField + [
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
					]
				], 'create');
			$this->xPanel->addField($phoneHiddenField + [
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
						'style' => 'margin-top: 20px;',
					]
				], 'update');

				$emailHiddenField = [
				'name'              => 'email_hidden',
				'label'             => 'email hidden',
				'type'              => 'checkbox',
			];
			$this->xPanel->addField($emailHiddenField + [
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
					]
				], 'create');
			$this->xPanel->addField($emailHiddenField + [
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
						'style' => 'margin-top: 20px;',
					]
				], 'update');


			$this->xPanel->addField([
				'name'              => 'verified_email',
				'label'             => trans("admin::messages.Verified Email"),
				'type'              => 'checkbox',
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'verified_phone',
				'label'             => trans("admin::messages.Verified Phone"),
				'type'              => 'checkbox',
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'blocked',
				'label'             => trans("admin::messages.Blocked"),
				'type'              => 'checkbox',
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$entity = $this->xPanel->getModel()->find(request()->segment(3));
			if (!empty($entity)) {
				$this->xPanel->addField([
					'name'  => 'ip_addr',
					'type'  => 'custom_html',
					'value' => '<h5><strong>IP:</strong> ' . $entity->ip_addr . '</h5>',
				], 'update');
			}

				if (auth()->user()->id != request()->segment(3)) {
					$this->xPanel->addField([
						'name'  => 'separator',
						'type'  => 'custom_html',
						'value' => '<hr>'
					]);
					$this->xPanel->addField([
						// two interconnected entities
						'label'             => trans('admin::messages.user_role_permission'),
						'field_unique_name' => 'user_role_permission',
						'type'              => 'checklist_dependency',
						'name'              => 'roles_and_permissions', // the methods that defines the relationship in your Model
						'subfields'         => [
							'primary'   => [
								'label'            => trans('admin::messages.roles'),
								'name'             => 'roles', // the method that defines the relationship in your Model
								'entity'           => 'roles', // the method that defines the relationship in your Model
								'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
								'attribute'        => 'name', // foreign key attribute that is shown to user
								'model'            => config('permission.models.role'), // foreign key model
								'pivot'            => true, // on create&update, do you need to add/delete pivot table entries?]
								'number_columns'   => 3, //can be 1,2,3,4,6
							],
							'secondary' => [
								'label'          => ucfirst(trans('admin::messages.permission_singular')),
								'name'           => 'permissions', // the method that defines the relationship in your Model
								'entity'         => 'permissions', // the method that defines the relationship in your Model
								'entity_primary' => 'roles', // the method that defines the relationship in your Model
								'attribute'      => 'name', // foreign key attribute that is shown to user
								'model'          => config('permission.models.permission'), // foreign key model
								'pivot'          => true, // on create&update, do you need to add/delete pivot table entries?]
								'number_columns' => 3, //can be 1,2,3,4,6
							],
						],
					]);
				}
			}
		}
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function account()
	{

	/*	$credentials = [
				'email'    => 'surinder@rednirus.in',
				'password' => 'Newpassword',
				'blocked'  => 0,
		]; */



		// Auth the User
		//Auth::attempt($credentials);

		// FIELDS
		$this->xPanel->addField([
			'label'             => trans("admin::messages.Gender"),
			'name'              => 'gender_id',
			'type'              => 'select2_from_array',
			'options'           => $this->gender(),
			'allows_null'       => false,
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'name',
			'label'             => trans("admin::messages.Name"),
			'type'              => 'text',
			'placeholder'       => trans("admin::messages.Name"),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'email',
			'label'             => trans("admin::messages.Email"),
			'type'              => 'email',
			'placeholder'       => trans("admin::messages.Email"),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'password',
			'label'             => trans("admin::messages.Password"),
			'type'              => 'password',
			'placeholder'       => trans("admin::messages.Password"),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'phone',
			'label'             => trans("admin::messages.Phone"),
			'type'              => 'text',
			'placeholder'       => "Phone",
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'phone_hidden',
			'label'             => trans("admin::messages.Phone hidden"),
			'type'              => 'checkbox',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
				'style' => 'margin-top: 20px;',
			],
		]);
		$this->xPanel->addField([
			'label'             => trans("admin::messages.Country"),
			'name'              => 'country_code',
			'model'             => 'App\Models\Country',
			'entity'            => 'country',
			'attribute'         => 'asciiname',
			'type'              => 'select2',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);

		// Get logged user
		if (auth()->check()) {
			return $this->edit(auth()->user()->id);
		} else {
			abort(403, 'Not allowed.');
		}
	}

	public function store(StoreRequest $request)
	{
				dd(1);
		$this->handleInput($request);

		return parent::storeCrud();
	}

	public function update(UpdateRequest $request)
	{
				dd(2);
		$this->handleInput($request);


		if($request->create_key){


			$user = User::find($request->id);


			$user->api_key = slugify(Hash::make(Str::random(60)));

			$user->save();


		}

		// Prevent user's role removal
		if (
			auth()->user()->id == request()->segment(3)
			|| str_contains(URL::previous(), admin_uri('account'))
		) {
			$this->xPanel->disableSyncPivot();
		}

		return parent::updateCrud();
	}

	// PRIVATE METHODS

	/**
	 * @return array
	 */
	private function gender()
	{
		$entries = Gender::trans()->get();


		return $this->getTranslatedArray($entries);
	}

	// PRIVATE METHODS

	/**
	 * @return array
	 */
	private function packages()
	{
		$entries = Package::trans()->where('active','1')->where('pack_type','Subscription')->orderBy('lft','asc')->get();

		return $this->getTranslatedArray($entries);
	}

	/**
	 * Handle Input values
	 *
	 * @param \App\Http\Requests\Admin\Request $request
	 */
	private function handleInput(Request $request)
	{
		$this->handlePasswordInput($request);

		$this->handleUserName($request);
		if ($this->isAdminUser($request)) {
			request()->merge(['is_admin' => 1]);
		} else {
			request()->merge(['is_admin' => 0]);
		}
	}

	/**
	 * Handle password input fields
	 *
	 * @param Request $request
	 */
	private function handlePasswordInput(Request $request)
	{




		// Remove fields not present on the user
		$request->request->remove('password_confirmation');

		/*
		// Encrypt password if specified
		if ($request->filled('password')) {
			$request->request->set('password', Hash::make($request->input('password')));
		} else {
			$request->request->remove('password');
		}
		*/

		// Encrypt password if specified (OK)
		if (request()->filled('password')) {
			request()->merge(['password' => Hash::make(request()->input('password'))]);
		} else {
			request()->replace(request()->except(['password']));
		}
	}

	private function handleUserName(Request $request)
	{

		if(!request()->input('username')){
			request()->merge(['username' =>request()->input('name')]);
		}

		request()->merge(['username' =>slugify(request()->input('username'))]);


	}





	/**
	 * Check if the set permissions are corresponding to the Staff permissions
	 *
	 * @param \App\Http\Requests\Admin\Request $request
	 * @return bool
	 */
	private function isAdminUser(Request $request)
	{
		$isAdmin = false;
		if (request()->filled('roles')) {
			$rolesIds = request()->input('roles');
			foreach ($rolesIds as $rolesId) {
				$role = Role::find($rolesId);
				if (!empty($role)) {
					$permissions = $role->permissions;
					if ($permissions->count() > 0) {
						foreach ($permissions as $permission) {
							if (in_array($permission->name, Permission::getStaffPermissions())) {
								$isAdmin = true;
							}
						}
					}
				}
			}
		}

		if (request()->filled('permissions')) {
			$permissionIds = request()->input('permissions');
			foreach ($permissionIds as $permissionId) {
				$permission = Permission::find($permissionId);
				if (in_array($permission->name, Permission::getStaffPermissions())) {
					$isAdmin = true;
				}
			}
		}

		return $isAdmin;
	}
}
