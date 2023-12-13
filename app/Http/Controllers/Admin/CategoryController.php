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
use App\Http\Requests\Admin\CategoryRequest as StoreRequest;
use App\Http\Requests\Admin\CategoryRequest as UpdateRequest;
use Illuminate\Support\Facades\Session;
class CategoryController extends PanelController
{
	public $parentId = 0;

	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\Category');
		$this->xPanel->addClause('where', 'parent_id', '=', 0);
		$this->xPanel->setRoute(admin_uri('categories'));
		$this->xPanel->setEntityNameStrings(trans('admin::messages.category'), trans('admin::messages.categories'));
		$this->xPanel->enableReorder('name', 1);
		$this->xPanel->enableDetailsRow();
		$this->xPanel->allowAccess(['reorder', 'details_row']);
		if (!request()->input('order')) {
			$this->xPanel->orderBy('lft', 'ASC');
		}

		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		// $this->xPanel->addButtonFromModelFunction('line', 'custom_fields', 'customFieldsBtn', 'beginning');
		$this->xPanel->addButtonFromModelFunction('line', 'sub_categories', 'subCategoriesBtn', 'beginning');

		// Filters
		// -----------------------
		$this->xPanel->addFilter([
			'name'  => 'status',
			'type'  => 'dropdown',
			'label' => trans('admin::messages.Type'),
		], [
			'classified'  => 'Classified',
			'job-offer'   => 'Job Offer',
			'job-search'  => 'Job Search',
			'not-salable' => 'Not-Salable',
		], function ($value) {
			$this->xPanel->addClause('where', 'type', '=', $value);
		});

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


		// FIELDS
		$this->xPanel->addField([
			'name'  => 'parent_id',
			'type'  => 'hidden',
			'value' => $this->parentId,
		]);
		$this->xPanel->addField([
			'name'              => 'menuname',
			'label'             => "Name Of Menu",
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => "Name Of Menu",
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
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
			'label'             => trans('admin::messages.Slug'),
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
			'name'              => 'youtubelink',
			'label'             => 'you tube link',
			'type'              => 'text',

			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
                      $this->xPanel->addField([
			'name'              => 'youtubetext',
			'label'             => 'you tube text',
			'type'              => 'text',

			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);


		$this->xPanel->addField([
			'name'       => 'description',
			'label'      => trans('admin::messages.Description'),
			'type'       => 'textarea',
			'attributes' => [
				'placeholder' => trans('admin::messages.Description'),
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
			'name'        => 'icon_class',
			'label'       => trans('admin::messages.Icon'),
			'type'        => 'select2_from_array',
			'options'     => collect(config('fontello'))->map(function ($iconCode, $iconClass) {
				return $iconClass . ' (' . $iconCode . ')';
			})->toArray(),
			'allows_null' => true,
			'hint'        => trans('admin::messages.Used in the categories area on the home & sitemap pages.'),
		]);
		$this->xPanel->addField([
			'name'  => 'type',
			'label' => trans('admin::messages.Type'),
			'type'  => 'enum',
			'hint'  => trans('admin::messages.category_types_info'),
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
