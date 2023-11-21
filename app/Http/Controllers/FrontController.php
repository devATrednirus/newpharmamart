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

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\CommonTrait;
use App\Http\Controllers\Traits\LocalizationTrait;
use App\Http\Controllers\Traits\RobotsTxtTrait;
use App\Http\Controllers\Traits\SettingsTrait;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;

class FrontController extends Controller
{
	use LocalizationTrait, SettingsTrait, RobotsTxtTrait, CommonTrait;
	
	public $request;
	public $data = [];
	
	/**
	 * FrontController constructor.
	 */
	public function __construct()
	{
		// Check & Change the App Key (If needed)
		$this->checkAndGenerateAppKey();
		
		// Load the Plugins
		$this->loadPlugins();
		
		// From Laravel 5.3.4+
		$this->middleware(function ($request, $next)
		{
			$this->loadLocalizationData();
			$this->checkDotEnvEntries();
			$this->applyFrontSettings();
			$this->checkRobotsTxtFile();
			
			return $next($request);
		});
		
		// Check the 'Currency Exchange' plugin
		if (config('plugins.currencyexchange.installed')) {
			$this->middleware(['currencies', 'currencyExchange']);
		}
		
		// Check the 'Domain Mapping' plugin
		if (config('plugins.domainmapping.installed')) {
			$this->middleware(['domain.verification']);
		}
		$this->getCategories();
	}

	/**
	 * Get list of categories
	 *
	 * @param array $value
	 */
	protected function getCategories($value = [])
	{
		// Get the default Max. Items
		$maxItems = 12;
		if (isset($value['max_items'])) {
			$maxItems = (int)$value['max_items'];
		}
		
		// Number of columns
		$numberOfCols = 3;
		
		// Get the Default Cache delay expiration
		$cacheExpiration = $this->getCacheExpirationTime($value);
		
		$cacheId = 'categories.parents.' . config('app.locale') . '.take.' . $maxItems;
		
		if (isset($value['type_of_display']) && in_array($value['type_of_display'], ['cc_normal_list', 'cc_normal_list_s'])) {
			
			$categories = Cache::remember($cacheId, $cacheExpiration, function () {
				$categories = Category::where('is_hidden','0')->trans()->orderBy('lft')->get();
				
				return $categories;
			});
			$categories = collect($categories)->keyBy('translation_of');
			$categories = $subCategories = $categories->groupBy('parent_id');
			
			if ($categories->has(0)) {
				$categories = $categories->get(0)->take($maxItems);
				$subCategories = $subCategories->forget(0);
				
				$maxRowsPerCol = round($categories->count() / $numberOfCols, 0, PHP_ROUND_HALF_EVEN);
				$maxRowsPerCol = ($maxRowsPerCol > 0) ? $maxRowsPerCol : 1;
				$categories = $categories->chunk($maxRowsPerCol);
			} else {
				$categories = collect([]);
				$subCategories = collect([]);
			}

			
			view()->share('categories', $categories);
			view()->share('subCategories', $subCategories);
			
		} else {
			
			$categories = Cache::remember($cacheId, $cacheExpiration, function () use ($maxItems) {
				$categories = Category::where('is_hidden','0')->trans()->with(['children'=>function($query){
					$query->where('is_hidden','0')->orderBy('lft');
				}])->where('parent_id', 0)->take($maxItems)->orderBy('lft')->get();
				
				return $categories;
			});
 
			
			/*if (isset($value['type_of_display']) && $value['type_of_display'] == 'c_picture_icon') {
				$categories = collect($categories)->keyBy('id');
			} else {
				// $maxRowsPerCol = round($categories->count() / $numberOfCols, 0); // PHP_ROUND_HALF_EVEN
				$maxRowsPerCol = ceil($categories->count() / $numberOfCols);
				$maxRowsPerCol = ($maxRowsPerCol > 0) ? $maxRowsPerCol : 1; // Fix array_chunk with 0
				$categories = $categories->chunk($maxRowsPerCol);
			}*/
			
			// dd($categories);
			view()->share('categories', $categories);
			
		}

		$cacheId = 'categories.featured.' . config('app.locale') . '.take.' . $maxItems;
		/*$categories_featured = Cache::remember($cacheId, $cacheExpiration, function () use ($maxItems) {
			$categories_featured = Category::where('is_hidden','0')->trans()->where('featured', 1)->take($maxItems)->orderBy('lft')->get();
			
			return $categories_featured;
		});*/

		$categories_featured = Category::where('is_hidden','0')->trans()->where('featured', 1)->take($maxItems)->orderBy('lft')->get();

		
		if (isset($value['type_of_display']) && $value['type_of_display'] == 'c_picture_icon') {
			$categories_featured = collect($categories_featured)->keyBy('id');
		} else {
			// $maxRowsPerCol = round($categories_featured->count() / $numberOfCols, 0); // PHP_ROUND_HALF_EVEN
			$maxRowsPerCol = ceil($categories_featured->count() / $numberOfCols);
			$maxRowsPerCol = ($maxRowsPerCol > 0) ? $maxRowsPerCol : 1; // Fix array_chunk with 0
			$categories_featured = $categories_featured->chunk($maxRowsPerCol);
		}
		
		view()->share('categories_featured', $categories_featured);

		if(method_exists($this, 'getLocationPost')){

			$this->getLocationPost();
		}
		
		view()->share('categoriesOptions', $value);
	}

	protected function getCacheExpirationTime($value = [])
	{
		// Get the default Cache Expiration Time
		$cacheExpiration = 0;
		if (isset($value['cache_expiration'])) {
			$cacheExpiration = (int)$value['cache_expiration'];
		}
		
		return $cacheExpiration;
	}
}
