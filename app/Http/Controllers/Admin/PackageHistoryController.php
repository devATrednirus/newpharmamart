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

use App\Models\Package;
use App\Models\PaymentMethod;
use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\Request as StoreRequest;
use App\Http\Requests\Admin\Request as UpdateRequest;

class PackageHistoryController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\PackageHistory');
		$this->xPanel->with(['user', 'package']);
		$this->xPanel->setRoute(admin_uri('package_history'));
		$this->xPanel->setEntityNameStrings('Package History','Package Histories');
		$this->xPanel->denyAccess(['create', 'update', 'delete']);
		$this->xPanel->removeAllButtons(); // Remove also: 'create' & 'reorder' buttons
		/*
		$this->xPanel->removeButton('update');
		$this->xPanel->removeButton('delete');
		$this->xPanel->removeButton('preview');
		$this->xPanel->removeButton('revisions');
		*/
		if (!request()->input('order')) {
			$this->xPanel->orderBy('end_date', 'asc');
		}
		
		$this->xPanel->addClause('where', 'end_date', '<>', null);

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
				'name'  => 'name',
				'type'  => 'text',
				'label' => 'Company Name',
			],
				false,
				function ($value) {
					//$this->xPanel->addClause('where', 'name', 'LIKE', "%$value%");

					$this->xPanel->query->whereHas('user', function ($query) use ($value) {
						$query->where(function($query) use ($value){
							$query->where('first_name', 'LIKE', "%$value%")->orWhere('last_name', 'LIKE', "%$value%")->orWhere('name', 'LIKE', "%$value%");
						});
					});
				});



		$this->xPanel->addFilter([
			'name'  => 'package',
			'type'  => 'select2',
			'label' => trans('admin::messages.Package'),
		],
		$this->getPackages(),
		function ($value) {
			$this->xPanel->addClause('where', 'package_id', '=', $value);
		});



		 
		// -----------------------
		$this->xPanel->addFilter([
			'name'  => 'status',
			'type'  => 'dropdown',
			'label' => trans('admin::messages.Status'),
		], [
			'Active' => 'Active',
			'Expired' => 'Expired',
		], function ($value) {

			$this->xPanel->addClause('where', 'status', '=', $value);
		});


		$this->xPanel->addFilter([
			'name'  => 'expiring_in',
			'type'  => 'dropdown',
			'label' => 'Expiring In',
		], [
			'last_7' => 'Last 7 Days',
			'last_15' => 'Last 15 Days',
			'last_30' => 'Last 30 Days',
			'last_60' => 'Last 60 Days',
			'before_60' => 'Before 60 Days',
			'next_7' => 'Next 7 Days',
			'next_15' => 'Next 15 Days',
			'next_30' => 'Next 30 Days',
			'next_60' => 'Next 60 Days',
			'after_60' => 'After 60 Days',
		], function ($value) {


			 $values = explode("_", $value);
			 

			 switch ($values[0]) {
			 	case 'last':
			 		 $this->xPanel->orderBy('end_date', 'desc');
			 		 $this->xPanel->addClause('where', 'status', '=','Expired');
			 		 $this->xPanel->addClause('where', 'end_date', '>=', \Carbon\Carbon::now()->subDays($values[1]));
			 		break;
			 	case 'next':
			 		 $this->xPanel->orderBy('end_date', 'asc');
			 		 $this->xPanel->addClause('where', 'status', '=','Active');
			 		 $this->xPanel->addClause('where', 'end_date', '<=', \Carbon\Carbon::now()->addDays($values[1]));
			 		break;

			 	case 'before':
			 		$this->xPanel->orderBy('end_date', 'desc');
			 		 $this->xPanel->addClause('where', 'status', '=','Expired');
			 		 $this->xPanel->addClause('where', 'end_date', '<', \Carbon\Carbon::now()->subDays($values[1]));
			 		break;
			 	case 'afetr':
			 		$this->xPanel->orderBy('end_date', 'asc');
			 		 $this->xPanel->addClause('where', 'status', '=','Active');
			 		 $this->xPanel->addClause('where', 'end_date', '>', \Carbon\Carbon::now()->addDays($values[1]));
			 		break;
			 	
			 	default:
			 		# code...
			 		break;
			 }

			
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
			'name'  => 'updated_at',
			'label' => 'Last Modified On',
			'orderable' => false,
		]);
		$this->xPanel->addColumn([
			'name'          => 'user_id',
			'label'         => 'Company Name',
			'type'          => 'model_function',
			'function_name' => 'getUserNameHtml',
			'orderable' => false,
		]);
		$this->xPanel->addColumn([
			'name'          => 'package_id',
			'label'         => trans("admin::messages.Package"),
			'type'          => 'model_function',
			'function_name' => 'getPackageNameHtml',
			'orderable' => false,
		]);

		
		$this->xPanel->addColumn([
			'name'  => 'lead_count',
			'label' => 'Promise Lead Count',
		]);
		$this->xPanel->addColumn([
			'name'  => 'start_date',
			'label' => 'Start Date',
		]);

		$this->xPanel->addColumn([
			'name'  => 'end_date',
			'label' => 'End Date',
		]);

/*		$this->xPanel->addColumn([
			'name'          => 'payment_method_id',
			'label'         => trans("admin::messages.Payment Method"),
			'type'          => 'model_function',
			'function_name' => 'getPaymentMethodNameHtml',
		]);*/
		$this->xPanel->addColumn([
			'name'          => 'status',
			'label'         => 'Status',
			'orderable' => false,
			//'type'          => 'model_function',
			//'function_name' => 'getActiveHtml',
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
