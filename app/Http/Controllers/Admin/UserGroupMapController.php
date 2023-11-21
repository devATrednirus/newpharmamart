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

use App\Models\User;
use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Models\UserGroup;
use App\Http\Requests\Admin\CategoryFieldRequest as StoreRequest;
use App\Http\Requests\Admin\CategoryFieldRequest as UpdateRequest;

class UserGroupMapController extends PanelController
{
	public $parentEntity = null;
	private $userGroupId = null;
	private $fieldId = null;
	
	public function setup()
	{
		// Parents Entities
		$parentEntities = ['user_groups'];
		
		// Get the parent Entity slug
		$this->parentEntity = request()->segment(2);

		if (!in_array($this->parentEntity, $parentEntities)) {
			abort(404);
		}
		

	 
		$this->userGroupId = request()->segment(3);
		
		// Get Parent Category's name
		$category = UserGroup::findOrFail($this->userGroupId);
		 
 		 
		
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\UserMapGroup');
		//$this->xPanel->with(['group', 'user']);
	//	$this->xPanel->enableParentEntity();
		

		 
		$this->xPanel->setRoute(admin_uri('user_groups/' . $category->id . '/users'));
		$this->xPanel->setEntityNameStrings('User &rarr; '.$category->name,'Users &rarr; '.$category->name);
		//$this->xPanel->enableReorder('field.name', 1);
		/*if (!request()->input('order')) {
			$this->xPanel->orderBy('lft', 'ASC');
		}*/
		$this->xPanel->setParentKeyField('group_id');
		$this->xPanel->addClause('where', 'group_id', '=', $category->id);

		$search = request()->input('search');
		if($search && count($search)>0){


			$value = $search['value']; 

			 

			$this->xPanel->query->whereHas('user', function ($query) use ($value) {
				$query->where(function($query) use ($value){
					$query->where('first_name', 'LIKE', "%$value%")->orWhere('last_name', 'LIKE', "%$value%")->orWhere('name', 'LIKE', "%$value%");
				});
			});

		}

		$this->xPanel->setParentRoute(admin_uri('user_groups'));
		$this->xPanel->setParentEntityNameStrings('User Group', 'User Group');
		$this->xPanel->allowAccess(['parent']);
		 
		 
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		
		
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
		
		// Category => CategoryField
		 
			$this->xPanel->addColumn([
				'name'          => 'user_id',
				'label'         => 'User Name',
				'type'          => 'model_function',
				'function_name' => 'getUserHtml',
			]);
		 
			$this->xPanel->addField([
				'name'  => 'group_id',
				'type'  => 'hidden',
				'value' => $this->userGroupId,
			], 'create');

			/*
			$this->xPanel->addField([
				'name'        => 'field_id',
				'label'       => mb_ucfirst(trans("admin::messages.Select a Custom field")),
				'type'        => 'select2_from_array',
				'options'     => $this->fields($this->fieldId),
				'allows_null' => false,
			]);*/

			$this->xPanel->addField([
				'name'        => 'user_id',
				'label'       => 'User',
				'type'        => 'select2_from_array',
				'options'     => $this->fields($this->fieldId),
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
	

	private function fields($selectedEntryId)
	{
		

		$entries = User::where('user_type_id','1')->orderBy('name')->get();
		if ($entries->count() <= 0) {
			return [];
		}
		
		$tab = [];
		foreach ($entries as $entry) {
			$tab[$entry->id] = $entry->name;
			
			 
		}
		
		return $tab;
		
		 
	}
	 
}
