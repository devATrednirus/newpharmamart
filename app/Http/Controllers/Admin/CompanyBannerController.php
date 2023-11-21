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
use App\Models\Category;
use App\Models\Post;
use App\Models\User;

class CompanyBannerController extends PanelController
{
	public function setup()
	{

		 
		
		// Get the parent Entity slug
		$this->parentEntity = request()->segment(3);

		 
		$user = User::where('user_type_id','1')->find($this->parentEntity);

		if (!$user) {
			abort(404);
		}

		

		 

		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\CompanyBanner');
		$this->xPanel->with(['user']);
		$this->xPanel->setRoute(admin_uri('company-banners'));

		$this->xPanel->setRoute(admin_uri('company/' . $this->parentEntity.'/banners'));
		$this->xPanel->setEntityNameStrings('Company Banner', 'Company Banners');
		//$this->xPanel->removeButton('create');
		if (!request()->input('order')) {
			$this->xPanel->orderBy('lft', 'ASC');
		}
		$this->xPanel->enableReorder('title', 1);
		$this->xPanel->allowAccess(['reorder']);


		$this->xPanel->addClause('where', 'user_id', '=', $user->id);
 

		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		$this->xPanel->addButtonFromModelFunction('line', 'edit_post', 'editPostBtn', 'beginning');
		
		// -----------------------
		
		$this->xPanel->addFilter([
			'name'  => 'post_id',
			'type'  => 'text',
			'label' => trans('admin::messages.Ad') . ' (ID)',
		],
		false,
		function ($value) {
			$this->xPanel->addClause('where', 'post_id', '=', $value);
		});

		$this->xPanel->addFilter([
			'name'  => 'status',
			'type'  => 'dropdown',
			'label' => trans('admin::messages.Status'),
		], [
			1 => trans('admin::messages.Unactivated'),
			2 => trans('admin::messages.Activated'),
		], function ($value) {
			if ($value == 1) {
				$this->xPanel->addClause('where', 'active', '=', 0);
			}
			if ($value == 2) {
				$this->xPanel->addClause('where', 'active', '=', 1);
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
			'label' => '',
			'type'  => 'checkbox',
			'orderable' => false,
		]);


		$this->xPanel->addColumn([
			'name'          => 'filename',
			'label'         => trans("admin::messages.Filename"),
			'type'          => 'model_function',
			'function_name' => 'getFilenameHtml',
		]);

		$this->xPanel->addColumn([
			'name'          => 'post_id',
			'label'         => 'Post',
			'type'          => 'model_function',
			'function_name' => 'getPostHtml',
		]);


		$this->xPanel->addColumn([
			'name'  => 'title',
			'label' => 'Title',
			'type'  => 'text',
		]);


		$this->xPanel->addColumn([
			'name'          => 'user_id',
			'label'         => 'User',
			'type'          => 'model_function',
			'function_name' => 'getUserHtml',
		]);

 

		

		$this->xPanel->addColumn([
			'name'          => 'active',
			'label'         => trans("admin::messages.Active"),
			'type'          => 'model_function',
			'function_name' => 'getActiveHtml',
		]);
		
		// FIELDS


		$this->xPanel->addField([
			'name'       => 'title',
			'label'      => trans("admin::messages.Title"),
			'type'       => 'text',
			'attributes' => [
				'placeholder' => trans("admin::messages.Title"),
			],
		]);
 

		$this->xPanel->addField([
			'name'  => 'user_id',
			'type'  => 'hidden',
			'value' => $user->id,
		], 'create');

		$this->xPanel->addField([
			'label'       => 'Post',
			'name'        => 'post_id',
			'type'        => 'select2_from_array',
			'options'     => $this->posts(),
			'allows_null' => false,
		]);

		$this->xPanel->addField([
				'name'              => 'link',
				'label'             => 'URL',
				'type'              => 'url',
				'attributes'        => [
					'placeholder' => 'URL',
				],
				 
			]);
		
		$this->xPanel->addField([
			'name'   => 'filename',
			'label'  => trans("admin::messages.Picture"),
			'type'   => 'image',
			'upload' => true,
			'disk'   => 'public',
		]);
		$this->xPanel->addField([
			'name'  => 'active',
			'label' => trans("admin::messages.Active"),
			'type'  => 'checkbox',
			'value' => 1,
		]);
	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud($request);
	}
	
	public function update(UpdateRequest $request)
	{
		return parent::updateCrud($request);
	}

	public function categories()
	{
		$entries = Category::trans()->where('parent_id', 0)->orderBy('lft')->get();
		if ($entries->count() <= 0) {
			return [];
		}
		
		$tab = [];

		$tab ['0']="All";

		foreach ($entries as $entry) {
			$tab[$entry->tid] = "-| " .$entry->name;
			
			$subEntries = Category::trans()->where('parent_id', $entry->id)->orderBy('lft')->get();
			if (!empty($subEntries)) {
				foreach ($subEntries as $subEntrie) {
					$tab[$subEntrie->tid] = "---| " . $subEntrie->name;
				}
			}
		}
		
		return $tab;
	}

	public function posts()
	{
		$entries = Post::where('user_id', $this->parentEntity)->orderBy('title')->get();
		if ($entries->count() <= 0) {
			return [];
		}
		 
		$tab = [];

		 
		$tab[""] =  "";
		foreach ($entries as $entry) {
			$tab[$entry->id] =  $entry->title;
			
			 
		}

 
		
		return $tab;
	}


}
