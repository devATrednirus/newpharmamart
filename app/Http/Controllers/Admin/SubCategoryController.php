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

use App\Http\Requests\Admin\CategoryRequest as StoreRequest;
use App\Http\Requests\Admin\CategoryRequest as UpdateRequest;
use App\Models\Category;
use Larapen\Admin\app\Http\Controllers\PanelController;

class SubCategoryController extends PanelController
{
	public $parentId = null;

	public function setup()
	{
		// Get the Parent ID
		$this->parentId = request()->segment(3);

		// Get Parent Category name
		$this->parent = Category::findTransOrFail($this->parentId);

		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\Category');
		$this->xPanel->setRoute(admin_uri('categories/' . $this->parentId . '/subcategories'));
		$this->xPanel->setEntityNameStrings(
			trans('admin::messages.subcategory') . ' &rarr; ' . '<strong>' . $this->parent->name . '</strong>',
			trans('admin::messages.subcategories') . ' &rarr; ' . '<strong>' . $this->parent->name . '</strong>'
		);
		$this->xPanel->enableReorder('name', 1);
		$this->xPanel->enableDetailsRow();
		if (!request()->input('order')) {
			$this->xPanel->orderBy('lft', 'ASC');
		}

		$this->xPanel->enableParentEntity();
		$this->xPanel->setParentKeyField('parent_id');
		$this->xPanel->addClause('where', 'parent_id', '=', $this->parentId);
		$this->xPanel->setParentRoute(admin_uri('categories'));
		$this->xPanel->setParentEntityNameStrings('parent ' . trans('admin::messages.category'), 'parent ' . trans('admin::messages.categories'));
		$this->xPanel->allowAccess(['reorder', 'details_row', 'parent']);

		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		$this->xPanel->addButtonFromModelFunction('line', 'custom_fields', 'customFieldsBtn', 'beginning');
		$this->xPanel->addButtonFromModelFunction('line', 'sub_sub_categories', 'subCategoriesBtn', 'beginning');


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
			'name'          => 'name',
			'label'         => trans("admin::messages.Name"),
			'type'          => 'model_function',
			'function_name' => 'getNameHtml',
		]);
		$this->xPanel->addColumn([
			'name'          => 'active',
			'label'         => trans("admin::messages.Active"),
			'type'          => 'model_function',
			'function_name' => 'getActiveHtml',
			'on_display'    => 'checkbox',
		]);

		$this->xPanel->addColumn([
			'name'          => 'is_hidden',
			'label'         => 'Hidden',
			'type'          => 'model_function',
			'function_name' => 'getHiddenHtml',
			'on_display'    => 'checkbox',
		]);

		$this->xPanel->addColumn([
			'name'          => 'featured',
			'label'         => 'Featured',
			'type'          => 'model_function',
			'function_name' => 'getFeaturedHtml',
			'on_display'    => 'checkbox',
		]);

		$this->xPanel->addColumn([
			'name'          => 'exclude_location',
			'label'         => 'Exlude Location Preference',
			'type'          => 'model_function',
			'function_name' => 'getExludeLocationHtml',
			'on_display'    => 'checkbox',
		]);

		$this->xPanel->addFilter([
			'name'  => 'is_hidden',
			'type'  => 'dropdown',
			'label' => 'Hidden',
		], [
			'No'  => 'No',
			'Yes'   => 'Yes'

		], function ($value) {
			if($value=="No"){
				$this->xPanel->addClause('where', 'is_hidden', '=', '0');
			}
			else if($value=="Yes"){
				$this->xPanel->addClause('where', 'is_hidden', '=', '1');
			}

		});

		$this->xPanel->addFilter([
			'name'  => 'exclude_location',
			'type'  => 'dropdown',
			'label' => 'Exlude Location Preference',
		], [
			'No'  => 'No',
			'Yes'   => 'Yes'

		], function ($value) {
			if($value=="No"){
				$this->xPanel->addClause('where', 'exclude_location', '=', '0');
			}
			else if($value=="Yes"){
				$this->xPanel->addClause('where', 'exclude_location', '=', '1');
			}

		});


		// FIELDS
		// $this->xPanel->addField([
		// 	'name'  => 'parent_id',
		// 	'type'  => 'hidden',
		// 	'value' => $this->parentId,
		// ], 'create');

		$this->xPanel->addField([
			'label'       => 'Parent Category',
			'name'        => 'parent_id',
			'type'        => 'select2_from_array',
			'value' => $this->parentId,
			'options'     => $this->categories(),
			'allows_null' => false,
		]);

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
			'name'              => 'title',
			'label'             => 'Meta Title',
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => 'Meta Title',
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'keywords',
			'label'             => 'Meta Keywords',
			'type'              => 'textarea',
			'attributes'        => [
				'placeholder' => 'Meta Keywords',
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'slug',
			'label'             => trans("admin::messages.Slug"),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin::messages.Will be automatically generated from your name, if left empty.'),
			],
			'hint'              => trans('admin::messages.Will be automatically generated from your name, if left empty.'),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'       => 'description',
			'label'      => trans("admin::messages.Description"),
			'type'       => 'textarea',
			'attributes' => [
				'placeholder' => trans("admin::messages.Description"),
			],
			'hint'   => 'you can use {LOCATION} for like " in New Delhi"',
		]);

		$this->xPanel->addField([
			'name'       => 'bottom_text',
			'label'      => 'Bottom Text',
			'type'       => (config('settings.other.simditor_wysiwyg'))
				? 'simditor'
				: ((!config('settings.other.simditor_wysiwyg') && config('settings.other.ckeditor_wysiwyg')) ? 'ckeditor' : 'textarea'),
			'attributes' => [
				'placeholder' => 'Bottom Text',
			],
			'hint'   => 'you can use {LOCATION} for like " in New Delhi"',
		]);
		$this->xPanel->addField([
			'name'   => 'picture',
			'label'  => trans('admin::messages.Picture'),
			'type'   => 'image',
			'upload' => true,
			'disk'   => 'public',
			'hint'   => trans('admin::messages.Used in the categories area on the homepage (Related to the type of display: "Picture as Icon").'),
		]);
		$this->xPanel->addField([
			'name'              => 'alttag',
			'label'             => 'ALT Tag',
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => 'Image Alt Tag',
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-12',
			],
		]);
		$this->xPanel->addField([
			'name'  => 'type',
			'label' => trans('admin::messages.Type'),
			'type'  => 'enum',
		]);
		$this->xPanel->addField([
			'name'  => 'active',
			'label' => trans("admin::messages.Active"),
			'type'  => 'checkbox',
		]);

		$this->xPanel->addField([
			'name'  => 'featured',
			'label' => 'Is Featured',
			'type'  => 'checkbox',
		]);
		$this->xPanel->addField([
			'name'  => 'in_footer',
			'label' => "Show In Footer",
			'type'  => 'checkbox',
		]);

		$this->xPanel->addField([
			'name'  => 'is_hidden',
			'label' => 'Hidden',
			'type'  => 'checkbox',
		]);

		$this->xPanel->addField([
			'name'  => 'exclude_location',
			'label' => 'Exlude Location Preference',
			'type'  => 'checkbox',
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

	public function categories()
	{



		$entries = Category::trans()->where('parent_id', $this->parent->parent_id)->orderBy('lft')->get();
		if ($entries->count() <= 0) {
			return [];
		}

		$tab = [];

	//	$tab ['0']="All";

		foreach ($entries as $entry) {
			$tab[$entry->id] = $entry->name;


		}
		// dd($tab);
		return $tab;
	}
}
