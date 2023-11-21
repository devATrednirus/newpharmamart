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

class PaymentController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\Payment');
		$this->xPanel->with(['user', 'package', 'paymentMethod']);
		$this->xPanel->setRoute(admin_uri('payments'));
		$this->xPanel->setEntityNameStrings(trans('admin::messages.payment'), trans('admin::messages.payments'));
		$this->xPanel->denyAccess([ 'delete']);
		$this->xPanel->enableDetailsRow('details_row');
		$this->xPanel->removeAllButtons(); // Remove also: 'create' & 'reorder' buttons
		/*
		$this->xPanel->removeButton('update');
		$this->xPanel->removeButton('delete');
		$this->xPanel->removeButton('preview');
		$this->xPanel->removeButton('revisions');
		*/
		if (!request()->input('order')) {
			$this->xPanel->orderBy('id', 'DESC');
		}



		if(request()->segment(4)=="edit"){

			$this->current = $this->xPanel->model->find(request()->segment(3));

			if(request()->input('invoice')=="true"){

				if(in_array($this->current->active, ["1","3"])){

					
					header('Location:/'.admin_uri('payments'));
					exit;

				}

			}
			else{
				
				if($this->current->active!="1"){

					
					header('Location:/'.admin_uri('payments'));
					exit;

				}

			}

			 
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
		// -----------------------
		$this->xPanel->addFilter([
			'name'  => 'user_id',
			'type'  => 'text',
			'label' => 'Company Name',
		],
		false,
		function ($value) {

			$this->xPanel->query = $this->xPanel->query->whereHas('user', function ($query) use ($value) {
				$query->where('name', 'like', '%'.$value.'%');
			});

		});
		// -----------------------
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
			'name'  => 'payment_method',
			'type'  => 'dropdown',
			'label' => trans('admin::messages.Payment Method'),
		],
		$this->getPaymentMethods(),
		function ($value) {
			$this->xPanel->addClause('where', 'payment_method_id', '=', $value);
		});
		// -----------------------
		
		$this->xPanel->addFilter([
			'name'  => 'status',
			'type'  => 'dropdown',
			'label' => trans('admin::messages.Status'),
		], [
			1 => trans('admin::messages.Unapproved'),
			2 => trans('admin::messages.Approved'),
			3 => 'Expired',
		], function ($value) {
			
			if ($value == 1) {
				$this->xPanel->addClause('where', 'active', '=', 0);
			}
			else if ($value == 2) {
				$this->xPanel->addClause('where', 'active', '=', 1);
			}
			else if ($value == 3) {
				$this->xPanel->addClause('where', 'active', '=', 3);
			}
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
			 		 $this->xPanel->addClause('where', 'active', '=', 3);
			 		 $this->xPanel->addClause('where', 'end_date', '>=', \Carbon\Carbon::now()->subDays($values[1]));
			 		 //$this->xPanel->addClause('where', 'end_date', '<=', \Carbon\Carbon::now());
			 		break;
			 	case 'next':
			 		 $this->xPanel->orderBy('end_date', 'asc');
			 		 $this->xPanel->addClause('where', 'active', '=', 1);
			 		 $this->xPanel->addClause('where', 'end_date', '<=', \Carbon\Carbon::now()->addDays($values[1]));
			 		break;

			 	case 'before':
			 		$this->xPanel->orderBy('end_date', 'desc');
			 		 $this->xPanel->addClause('where', 'active', '=', 3);
			 		 $this->xPanel->addClause('where', 'end_date', '<', \Carbon\Carbon::now()->subDays($values[1]));
			 		break;
			 	case 'after':
			 		$this->xPanel->orderBy('end_date', 'asc');
			 		 $this->xPanel->addClause('where', 'active', '=', 1);
			 		 $this->xPanel->addClause('where', 'end_date', '>', \Carbon\Carbon::now()->addDays($values[1]));
			 		break;
			 	
			 	default:
			 		# code...
			 		break;
			 }

			
		});

		$this->xPanel->addFilter([
			'name'  => 'payment_type',
			'type'  => 'dropdown',
			'label' => 'Package Type',
		], [
			'Buy-Leads' => 'Buy-Leads',
			'Subscription' => 'Subscription',
		], function ($value) {
			$this->xPanel->addClause('where', 'payment_type', '=', $value);
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
		]);
		$this->xPanel->addColumn([
			'name'  => 'created_at',
			'label' => trans("admin::messages.Date"),
		]);
		$this->xPanel->addColumn([
			'name'          => 'user_id',
			'label'         => 'Company Name',
			'type'          => 'model_function',
			'function_name' => 'getUserTitleHtml',
		]);
		$this->xPanel->addColumn([
			'name'          => 'package_id',
			'label'         => trans("admin::messages.Package"),
			'type'          => 'model_function',
			'function_name' => 'getPackageNameHtml',
		]);

		$this->xPanel->addColumn([
			'name'          => 'payment_type',
			'label'         => "Package Type",
			///'type'          => 'model_function',
			//'function_name' => 'getPackageTypeHtml',
		]);

		$this->xPanel->addColumn([
			'name'          => 'no_leads',
			'label'         => "Promise Lead Count",
			'type'          => 'model_function',
			'function_name' => 'getPackageLeadsHtml',
			'orderable' => false,
		]);

		
		$this->xPanel->addColumn([
			'name'          => 'start_date',
			'label'         => 'Start Date',
			//'type'          => 'date',
		//	'type'          => 'model_function',
			//'function_name' => 'getPaymentMethodNameHtml',
		]);

		$this->xPanel->addColumn([
			'name'          => 'end_date',
			'label'         => 'End Date',
			//'type'          => 'date',
			//'function_name' => 'getPaymentMethodNameHtml',
		]);
 

		$this->xPanel->addColumn([
			'name'          => 'payment_method_id',
			'label'         => trans("admin::messages.Payment Method"),
			'type'          => 'model_function',
			'function_name' => 'getPaymentMethodNameHtml',
		]);
		$this->xPanel->addColumn([
			'name'          => 'active',
			'label'         => trans("admin::messages.Approved"),
			'type'          => 'model_function',
			'function_name' => 'getActivePaymentHtml',
		]);


		$this->xPanel->addColumn([
			'name'          => 'invoice',
			'label'         => 'Invoice',
			'type'          => 'model_function',
			'function_name' => 'getInvoiceHtml',
		]);


		$this->xPanel->addColumn([
			'name'          => 'limits',
			'label'         => 'Set Limits',
			'type'          => 'model_function',
			'function_name' => 'getLimitsHtml',
		]);



		if(request()->segment(4)=="edit"){
			if(request()->input('invoice')=="true"){

				$this->xPanel->addField([
					'name'   => 'invoice',
					'label'  => trans("admin::messages.Picture"),
					'type'   => 'image',
					'upload' => true,
					'disk'   => 'public',
				]);
			}
			else{


				

				$this->xPanel->addField([
					'name'              => 'monthly_leads',
					'label'             => 'Monthly Leads',
					'type'              => 'text',
					'attributes'        => [
						'placeholder' => 'Monthly Leads',
					],
					'hint'              => 'No of leads per Months  (0 means Package default limits), Package ('.$this->current->package->name.') default limit is '.$this->current->package->monthly_leads,
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
					],
				]);

				$this->xPanel->addField([
					'name'              => 'daily_send_limit',
					'label'             => 'Daily Send Limit',
					'type'              => 'text',
					'attributes'        => [
						'placeholder' => 'daily Send Limit',
					],
					'hint'              => 'No daily sent limit (0 means Package default limits), Package ('.$this->current->package->name.') default limit is '.$this->current->package->daily_send_limit,
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
					],
				]);
			}

		}


		
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
		$entries = Package::trans()->where('price', '>', 0)->orderBy('currency_code', 'asc')->orderBy('lft', 'asc')->get();
		
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
