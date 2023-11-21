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

class PackageController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\Package');
		$this->xPanel->setRoute(admin_uri('packages'));
		$this->xPanel->setEntityNameStrings(trans('admin::messages.package'), trans('admin::messages.packages'));
		
		$this->xPanel->enableReorder('name', 1);
		$this->xPanel->enableDetailsRow();
		$this->xPanel->allowAccess(['reorder', 'details_row']);
		if (!request()->input('order')) {
			$this->xPanel->orderBy('lft', 'ASC');
		}
		
		if(request()->segment(4)=="edit"){

			$current = $this->xPanel->model->find(request()->segment(3));


			if($current->pack_type!="Subscription"){

				
				header('Location:/'.admin_uri('packages'));
				exit;

			}

			 
		}

		
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		
 		
		$this->xPanel->addClause('where', 'pack_type', '=', 'subscription');


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
			'label' => 'Monthly Leads',
		]);
		$this->xPanel->addColumn([
			'name'  => 'share_per_lead',
			'label' => 'Max Share per Leads',
		]);

		$this->xPanel->addColumn([
			'name'  => 'daily_send_limit',
			'label' => 'Daily Send Limit',
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
			'hint'              => trans('admin::messages.Short name for ribbon label'),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'ribbon',
			'label'             => trans('admin::messages.Ribbon'),
			'type'              => 'enum',
			'hint'              => trans('admin::messages.Show ads with ribbon when viewing ads in search results list'),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'has_badge',
			'label'             => trans("admin::messages.Show ads with a badge (in addition)"),
			'type'              => 'checkbox',
			'hint'              => '<br><br>',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
				'style' => 'margin-top: 20px;',
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
			'name'              => 'duration',
			'label'             => trans('admin::messages.Duration'),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin::messages.Duration (in days)'),
			],
			'hint'              => trans('admin::messages.Duration to show posts (in days). You need to schedule the AdsCleaner command.'),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);

		$this->xPanel->addField([
			'name'              => 'monthly_leads',
			'label'             => 'Monthly Leads',
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => 'Monthly Leads',
			],
			'hint'              => 'No of leads per Months',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
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
		$this->xPanel->addField([
			'name'       => 'description',
			'label'      => trans('admin::messages.Description'),
			'type'       => (config('settings.other.simditor_wysiwyg'))
				? 'simditor'
				: ((!config('settings.other.simditor_wysiwyg') && config('settings.other.ckeditor_wysiwyg')) ? 'ckeditor' : 'textarea'),
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

		$this->xPanel->addField([
			'name'              => 'is_public',
			'label'             => 'Display in Frontend',
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
