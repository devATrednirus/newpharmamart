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

use App\Models\Enquiry;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\Request as StoreRequest;
use App\Http\Requests\Admin\Request as UpdateRequest;

class EnquiryController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/

		$this->xPanel->with_trashed = false;
		$this->xPanel->setModel('App\Models\Enquiry');
		//$this->xPanel->with(['user', 'package']);
		$this->xPanel->setRoute(admin_uri('enquiries'));
		$this->xPanel->setEntityNameStrings('Enquiry','Enquiries');
		$this->xPanel->denyAccess(['create', 'update', 'delete']);
		$this->xPanel->removeAllButtons(); // Remove also: 'create' & 'reorder' buttons
		/*
		$this->xPanel->removeButton('update');
		$this->xPanel->removeButton('delete');
		$this->xPanel->removeButton('preview');
		$this->xPanel->removeButton('revisions');
		*/
		if (!request()->input('order')) {
			$this->xPanel->orderBy('created_at', 'desc');
		}

		$this->xPanel->enableDetailsRow('details_row');
		
		//$this->xPanel->addClause('where', 'end_date', '<>', null);

		// Filters
		// -----------------------s
		 
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
		// -----------------------
	
		// -----------------------

		$this->xPanel->addFilter([
				'name'  => 'first_name',
				'type'  => 'text',
				'label' => 'First Name',
			],
				false,
				function ($value) {
					$this->xPanel->addClause('where', 'first_name', 'LIKE', "%$value%");

				 
				});
		$this->xPanel->addFilter([
				'name'  => 'last_name',
				'type'  => 'text',
				'label' => 'last Name',
			],
				false,
				function ($value) {
					$this->xPanel->addClause('where', 'last_name', 'LIKE', "%$value%");

				 
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
				'name'  => 'email',
				'type'  => 'text',
				'label' => 'Email',
			],
				false,
				function ($value) {
					$this->xPanel->addClause('where', 'email', 'LIKE', "%$value%");

				 
				});
		$this->xPanel->addFilter([
				'name'  => 'company_name',
				'type'  => 'text',
				'label' => 'Company Name',
			],
				false,
				function ($value) {
					$this->xPanel->addClause('where', 'company_name', 'LIKE', "%$value%");

				 
				});



		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'  => 'id',
			'label' => "ID",
			'orderable' => false,
		]);
		 

		$this->xPanel->addColumn([
			'name'  => 'created_at',
			'label' => 'Submit On',
			'orderable' => true,
		]);
		/*$this->xPanel->addColumn([
			'name'          => 'user_id',
			'label'         => 'Company Name',
			'type'          => 'model_function',
			'function_name' => 'getUserNameHtml',
			'orderable' => false,
		]);*/
		/*$this->xPanel->addColumn([
			'name'          => 'package_id',
			'label'         => trans("admin::messages.Package"),
			'type'          => 'model_function',
			'function_name' => 'getPackageNameHtml',
			'orderable' => false,
		]);*/

		
		$this->xPanel->addColumn([
			'name'  => 'first_name',
			'label' => 'First Name',
		]);
		$this->xPanel->addColumn([
			'name'  => 'last_name',
			'label' => 'Last Name',
		]);

		$this->xPanel->addColumn([
			'name'  => 'phone',
			'label' => 'Phone',
		]);


		$this->xPanel->addColumn([
			'name'  => 'email',
			'label' => 'Email',
		]);

		$this->xPanel->addColumn([
			'name'  => 'company_name',
			'label' => 'Company Name',
		]);

		$this->xPanel->addColumn([
			'name'  => 'message',
			'label' => 'Message',
		]);


		
		// FIELDS
	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}
	
	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
	
	public function getPackages()
	{
		$entries = Package::trans()->orderBy('currency_code', 'asc')->orderBy('lft', 'asc')->get();
		
		$arr = [];
		if ($entries->count() > 0) {
			foreach ($entries as $entry) {
				$arr[$entry->id] = $entry->name . ' (' . $entry->price . ' ' . $entry->currency_code . ')';
			}
		}
		
		return $arr;
	}
	
	public function getPaymentMethods()
	{
		$entries = PaymentMethod::orderBy('lft', 'asc')->get();
		
		$arr = [];
		if ($entries->count() > 0) {
			foreach ($entries as $entry) {
				$arr[$entry->id] = $entry->display_name;
			}
		}
		
		return $arr;
	}
}
