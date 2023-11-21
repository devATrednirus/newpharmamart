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

use App\Models\Redirect;
use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\RedirectRequest as StoreRequest;
use App\Http\Requests\Admin\RedirectRequest as UpdateRequest;

class RedirectController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\Redirect');
		$this->xPanel->setRoute(admin_uri('redirects'));
		$this->xPanel->setEntityNameStrings('Redirect','Redirects');
		//$this->xPanel->enableDetailsRow();
		//$this->xPanel->allowAccess(['details_row']);
		
		//$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		
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
			'name'  => 'from',
			'label' => 'From URL',
		]); 

		$this->xPanel->addColumn([
			'name'  => 'to',
			'label' => 'To URL',
		]);

		$this->xPanel->addColumn([
			'name'  => 'status',
			'label' => 'Status',
		]);
 	
 		$this->xPanel->addColumn([
				'name'  => 'created_at',
				'label' => trans("admin::messages.Date"),
				'type'  => 'datetime',
		]);

		$this->xPanel->addColumn([
				'name'  => 'update_at',
				'label' => 'Updated At',
				'type'  => 'datetime',
			]);

		$this->xPanel->addField([
			'name'       => 'from',
			'label'      => 'From URL',
			'type'       => 'text'
		]);
		$this->xPanel->addField([
			'name'       => 'to',
			'label'      => 'To URL',
			'type'       => 'text'
		]); 

			$this->xPanel->addField([
				'name'        => 'status',
				'label'       => 'Status',
				'type'        => 'select2_from_array',
				'options'     => ['Active'=>'Active',
								'In-Active'=>'In-Active',
								],
				'allows_null' => false,
				
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
