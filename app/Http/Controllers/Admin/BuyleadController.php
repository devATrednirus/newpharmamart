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
use App\Http\Requests\Admin\PackageRequest as StoreRequest;
use App\Http\Requests\Admin\PackageRequest as UpdateRequest;

class BuyleadController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/


		$this->xPanel->setModel('App\Models\Package');
		$this->xPanel->setRoute(admin_uri('buy-leads'));
		$this->xPanel->setEntityNameStrings('Buy Lead','Buy Leads');


 
		if(request()->segment(4)=="edit"){

			$current = $this->xPanel->model->find(request()->segment(3));


			if($current->pack_type!="Buy-Leads"){

				
				header('Location:/'.admin_uri('buy-leads'));
				exit;

			}

			 
		}
		
		$this->xPanel->enableReorder('name', 1);
		$this->xPanel->enableDetailsRow();
		$this->xPanel->allowAccess(['reorder', 'details_row']);
		if (!request()->input('order')) {
			$this->xPanel->orderBy('lft', 'ASC');
		}
		
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		
 
		$this->xPanel->addClause('where', 'pack_type', '=', 'Buy-Leads');

		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'  => 'id',
			'label' => '',
			'type'  => 'checkbox',
			'orderable' => false,
		]);
		$this->xPanel->addColumn([
			'name'  => 'name',
			'label' => trans("admin::messages.Name"),
		]);
		$this->xPanel->addColumn([
			'name'  => 'price',
			'label' => trans("admin::messages.Price"),
		]);
		$this->xPanel->addColumn([
			'name'  => 'monthly_leads',
			'label' => 'Buy Leads',
		]);
 

		$this->xPanel->addColumn([
			'name'          => 'users_count',
			'label'         => 'Users',
			'type'          => 'model_function',
			'function_name' => 'getUserCounteHtml',
		]);

		$this->xPanel->addColumn([
			'name'          => 'active',
			'label'         => trans("admin::messages.Active"),
			'type'          => 'model_function',
			'function_name' => 'getActiveHtml',
			'on_display'    => 'checkbox',
		]);
		
		// FIELDS

		$this->xPanel->addField([
   			'name'    => 'pack_type',
   			'type'    => 'hidden',
    			'default' => 'Buy-Leads',
    		], 'create');



		$this->xPanel->addField([
			'name'              => 'name',
			'label'             => trans("admin::messages.Name"),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans("admin::messages.Name"),
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'short_name',
			'label'             => trans('admin::messages.Short Name'),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin::messages.Short Name'),
			],
		//	'hint'              => trans('admin::messages.Short name for ribbon label'),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);

		$this->xPanel->addField([
			'name'              => 'price',
			'label'             => trans("admin::messages.Price"),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans("admin::messages.Price"),
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'label'             => trans("admin::messages.Currency"),
			'name'              => 'currency_code',
			'model'             => 'App\Models\Currency',
			'entity'            => 'currency',
			'attribute'         => 'code',
			'type'              => 'select2',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);

		$this->xPanel->addField([
			'name'              => 'monthly_leads',
			'label'             => 'Leads',
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => 'Buy Leads',
			],
			'hint'              => 'No of leads can buy',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);


		$this->xPanel->addField([
			'name'              => 'duration',
			'label'             => 'Number of days old leads',
			'type'              => 'text',
			'default' => '0',
			'attributes'        => [
				'placeholder' => 'Number of days old leads',
			],
			'hint'              => 'example- 0: means all, 1: 1 day old, 7: 7 days old  ',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		/*

		
		$this->xPanel->addField([
			'name'              => 'share_per_lead',
			'label'             => 'Max Share per Leads',
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => 'Max Share per Leads',
			],
			'hint'              => 'No sharing per lead',
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
			'hint'              => 'No daily sent limit',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
*/		
		$this->xPanel->addField([
			'name'       => 'description',
			'label'      => trans('admin::messages.Description'),
			'type'       => 'text',
			'attributes' => [
				'placeholder' => trans('admin::messages.Description'),
			],
		]);
		$this->xPanel->addField([
			'name'              => 'lft',
			'label'             => trans('admin::messages.Position'),
			'type'              => 'text',
			'hint'              => trans('admin::messages.Quick Reorder') . ': '
				. trans('admin::messages.Enter a position number.') . ' '
				. trans('admin::messages.NOTE: High number will allow to show ads in top in ads listing. Low number will allow to show ads in bottom in ads listing.'),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'active',
			'label'             => trans("admin::messages.Active"),
			'type'              => 'checkbox',
			'hint'              => '<br><br>',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
				'style' => 'margin-top: 20px;',
			],
		]);
		

	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}
	
	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
