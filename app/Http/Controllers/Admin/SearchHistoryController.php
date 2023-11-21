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
use App\Http\Requests\Admin\PostRequest as StoreRequest;
use App\Http\Requests\Admin\PostRequest as UpdateRequest;

class SearchHistoryController extends PanelController
{
	use VerificationTrait;
	
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\SearchHistory');
		$this->xPanel->with(['category', 'user', 'city']);
		
		$this->xPanel->setRoute(admin_uri('searches'));
		$this->xPanel->setEntityNameStrings('Search History', 'Search Histories');
		$this->xPanel->denyAccess(['create', 'update']);
		//$this->xPanel->removeButtons(['create', 'update']); // Remove also: 'create' & 'reorder' buttons
		 
		if (!request()->input('order')) {
			$this->xPanel->orderBy('created_at', 'DESC');
		}

		$this->xPanel->addClause('where', 'ip_address', '!=', '127.0.0.1');
 
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
		// -----------------------
		
		// -----------------------
		
		$this->xPanel->addFilter([
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
			'name'  => 'serach_term',
			'type'  => 'text',
			'label' => 'Search Term',
		],
		false,
		function ($value) {
			$this->xPanel->addClause('where', 'serach_term', 'LIKE', "%$value%");
		});

		$this->xPanel->addFilter([
			'name'  => 'user_agent',
			'type'  => 'text',
			'label' => 'User Agent',
		],
		false,
		function ($value) {
			$this->xPanel->addClause('where', 'user_agent', 'LIKE', "%$value%");
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
			'name'          => 'contact_name',
			'label'         => 'User',
			'type'          => 'model_function',
			'function_name' => 'getUserNameHtml',
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
			'name'  => 'serach_term',
			'label' => 'Search Term',
			'type'  => 'text',
		]);

		$this->xPanel->addColumn([
			'name'  => 'count',
			'label' => 'Search Result Count',
			'type'  => 'text',
		]);

		$this->xPanel->addColumn([
			'name'  => 'ip_address',
			'label' => 'IP Address',
			'type'  => 'ipaddress',
		]);

		$this->xPanel->addColumn([
			'name'  => 'user_agent',
			'label' => 'User Agent',
			'type'  => 'text',
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
