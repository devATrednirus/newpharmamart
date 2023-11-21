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

use App\Helpers\Arr;
use App\Helpers\DBTool;
use App\Models\Post;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\SubAdmin1;
use App\Models\City;
use App\Models\User;
use App\Models\Package;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;

class HomeController extends FrontController
{
	/**
	 * HomeController constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		// Check Country URL for SEO
		$countries = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		view()->share('countries', $countries);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$data = [];
		$countryCode = config('country.code');

		if(request()->ajax()){


			if(request()->get('_type')=="header"){
				return view('layouts.inc.header');
			}
		}




		// Get all homepage sections
		$cacheId = $countryCode . '.homeSections';
		$data['sections'] = Cache::remember($cacheId, $this->cacheExpiration, function () use ($countryCode) {
			$sections = collect([]);

			// Check if the Domain Mapping plugin is available
			if (config('plugins.domainmapping.installed')) {
				try {
					$sections = \App\Plugins\domainmapping\app\Models\DomainHomeSection::where('country_code', $countryCode)->orderBy('lft')->get();
				} catch (\Exception $e) {}
			}

			// Get the entry from the core
			if ($sections->count() <= 0) {
				$sections = HomeSection::orderBy('lft')->get();
			}

			return $sections;
		});

		if ($data['sections']->count() > 0) {
			foreach ($data['sections'] as $section) {

				// Clear method name
				$method = str_replace(strtolower($countryCode) . '_', '', $section->method);

				// Check if method exists
				if (!method_exists($this, $method)) {
					continue;
				}

				// Call the method
				try {
					if (isset($section->value)) {
						$this->{$method}($section->value);
					} else {
						$this->{$method}();
					}
				} catch (\Exception $e) {
					flash($e->getMessage())->error();
					continue;
				}
			}
		}

		// Get SEO
		$this->setSeo();

		return view('home.index', $data);
	}

	/**
	 * Get search form (Always in Top)
	 *
	 * @param array $value
	 */
	protected function getSearchForm($value = [])
	{
		view()->share('searchFormOptions', $value);
	}

	/**
	 * Get search form (Always in Top)
	 *
	 * @param array $value
	 */
	protected function packages()
	{
		$data = [];

		$data['packages'] = Package::where('active','1')->where('is_public','1')->where('pack_type','Subscription')->orderBy('lft','desc')->get();



		return view('home.packages', $data);
	}

	/**
	 * Get locations & SVG map
	 *
	 * @param array $value
	 */
	protected function getLocations($value = [])
	{
		// Get the default Max. Items
		$maxItems = 14;
		if (isset($value['max_items'])) {
			$maxItems = (int)$value['max_items'];
		}

		// Get the Default Cache delay expiration
		$cacheExpiration = $this->getCacheExpirationTime($value);

		// Modal - States Collection
		$cacheId = config('country.code') . '.home.getLocations.modalAdmins';
		$modalAdmins = Cache::remember($cacheId, $cacheExpiration, function () {
			$modalAdmins = SubAdmin1::currentCountry()->orderBy('name')->get(['code', 'name'])->keyBy('code');

			return $modalAdmins;
		});
		view()->share('modalAdmins', $modalAdmins);

		// Get cities
		$cacheId = config('country.code') . 'home.getLocations.cities';
		$cities = Cache::remember($cacheId, $cacheExpiration, function () use ($maxItems) {
			$cities = City::currentCountry()->take($maxItems)->orderBy('population', 'DESC')->orderBy('name')->get();

			return $cities;
		});
		$cities = collect($cities)->push(Arr::toObject([
			'id'             => 999999999,
			'name'           => t('More cities') . ' &raquo;',
			'subadmin1_code' => 0,
		]));

		// Get cities number of columns
		$numberOfCols = 4;
		if (file_exists(config('larapen.core.maps.path') . strtolower(config('country.code')) . '.svg')) {
			if (isset($value['show_map']) && $value['show_map'] == '1') {
				$numberOfCols = (isset($value['items_cols']) && !empty($value['items_cols'])) ? (int)$value['items_cols'] : 3;
			}
		}

		// Chunk
		$maxRowsPerCol = round($cities->count() / $numberOfCols, 0); // PHP_ROUND_HALF_EVEN
		$maxRowsPerCol = ($maxRowsPerCol > 0) ? $maxRowsPerCol : 1; // Fix array_chunk with 0
		$cities = $cities->chunk($maxRowsPerCol);

		view()->share('cities', $cities);
		view()->share('citiesOptions', $value);
	}

	/**
	 * Get sponsored posts
	 *
	 * @param array $value
	 */

        public function queriesget(){
        $message = \DB::table('messages')->orderBy('id','DESC')->get();
        $enquiry = \DB::table('enquiries')->orderBy('id','DESC')->get();
        return response()->json(['success' => true,"status" => 202, 'message'=>$message,'enq'=>$enquiry]);
       }


	protected function getSponsoredPosts($value = [])
	{
		// Get the default Max. Items
		$maxItems = 24;
		if (isset($value['max_items'])) {
			$maxItems = (int)$value['max_items'];
		}
		$maxItems = 24;

		// Get the default orderBy value
		$orderBy = 'random';
		if (isset($value['order_by'])) {
			$orderBy = $value['order_by'];
		}

		// Get the default Cache delay expiration
		$cacheExpiration = $this->getCacheExpirationTime($value);

		$sponsored = null;

		// Get featured posts
		$posts = $this->getPosts($maxItems, 'sponsored', $cacheExpiration);

		if (!empty($posts)) {
			if ($orderBy == 'random') {
				$posts = Arr::shuffle($posts);
			}
			$attr = ['countryCode' => config('country.icode')];
			$sponsored = [
				'title' => t('Home - Sponsored Ads'),
				'link'  => lurl(trans('routes.v-search', $attr), $attr),
				'posts' => $posts,
			];
			$sponsored = Arr::toObject($sponsored);
		}

		view()->share('featured', $sponsored);
		view()->share('featuredOptions', $value);
	}

	/**
	 * Get latest posts
	 *
	 * @param array $value
	 */
	protected function getLatestPosts($value = [])
	{
		// Get the default Max. Items
		$maxItems = 12;
		if (isset($value['max_items'])) {
			$maxItems = (int)$value['max_items'];
		}

		// Get the default orderBy value
		$orderBy = 'date';
		if (isset($value['order_by'])) {
			$orderBy = $value['order_by'];
		}

		// Get the Default Cache delay expiration
		$cacheExpiration = $this->getCacheExpirationTime($value);

		// Get latest posts
		$posts = $this->getPosts($maxItems, 'latest', $cacheExpiration);

		if (!empty($posts)) {
			if ($orderBy == 'random') {
				$posts = Arr::shuffle($posts);
			}
		}

		view()->share('posts', $posts);
		view()->share('latestOptions', $value);
	}




	protected function getLocationPost($limit = 24,$value=[])
	{
		$cacheExpiration = $this->getCacheExpirationTime($value);
		$reviewedCondition = '';
		if (config('settings.single.posts_review_activation')) {
			$reviewedCondition = ' AND a.reviewed = 1';
		}

		$sql = 'SELECT DISTINCT  states.name
                FROM ' . DBTool::table('posts') . ' as a
                LEFT JOIN users u on u.id=a.user_id
                INNER JOIN ' . DBTool::table('categories') . ' as c ON c.id=a.category_id AND c.active=1
                INNER JOIN ' . DBTool::table('packages') . ' as p ON p.id=u.package_id

                INNER JOIN ' . DBTool::table('cities') . '  ON cities.id=a.city_id

                INNER JOIN ' . DBTool::table('subadmin1') . ' as states ON states.code=cities.subadmin1_code


                WHERE a.country_code = "IN"
                	AND (u.verified_email=1 AND u.verified_phone=1)
                	AND a.archived!=1 ' . $reviewedCondition . '
                GROUP BY u.id, states.name 
                ORDER BY p.lft DESC, rand()
                LIMIT 0,' . (int)$limit;
		$bindings = [
			'countryCode' => config('country.code'),
		];




		$cacheId = config('country.code') . '.home.getLocationPost.'.$limit ;
		$location_posts = Cache::remember($cacheId, $cacheExpiration, function () use ($sql, $bindings) {

			$location_posts = DB::select(DB::raw($sql), $bindings);
			return $location_posts;
		});


		view()->share('location_posts', $location_posts);
	}
	/**
	 * Get mini stats data
	 */
	protected function getStats()
	{
		// Count posts
		$countPosts = Post::currentCountry()->unarchived()->count();

		// Count cities
		$countCities = City::currentCountry()->count();

		// Count users
		$countUsers = User::count();

		// Share vars
		view()->share('countPosts', $countPosts);
		view()->share('countCities', $countCities);
		view()->share('countUsers', $countUsers);
	}

	/**
	 * Set SEO information
	 */
	protected function setSeo()
	{
		$title = getMetaTag('title', 'home');
		$description = getMetaTag('description', 'home');
		$keywords = getMetaTag('keywords', 'home');

		// Meta Tags
		MetaTag::set('title', $title);
		MetaTag::set('description', strip_tags($description));
		MetaTag::set('keywords', $keywords);

		// Open Graph
		$this->og->title($title)->description($description);
		view()->share('og', $this->og);
	}

	/**
	 * @param int $limit
	 * @param string $type (latest OR sponsored)
	 * @param int $cacheExpiration
	 * @return mixed
	 */
	private function getPosts($limit = 24, $type = 'latest', $cacheExpiration = 0)
	{
		$paymentJoin = '';
		$sponsoredCondition = '';
		$sponsoredOrder = '';
		if ($type == 'sponsored') {
			//$paymentJoin .= 'INNER JOIN ' . DBTool::table('payments') . ' as py ON py.post_id=a.id AND py.active=1' . "\n";
			$paymentJoin .= 'INNER JOIN ' . DBTool::table('packages') . ' as p ON p.id=u.package_id ' . "\n";
			$sponsoredCondition = " AND p.short_name in ('Premium+','Premium','Standard')";
		//	$sponsoredCondition = " AND p.short_name in ('Premium+','Premium','Standard')";
			$sponsoredOrder = 'p.lft DESC, ';
		} else {
			// $paymentJoin .= 'LEFT JOIN ' . DBTool::table('payments') . ' as py ON py.post_id=a.id AND py.active=1' . "\n";
			//$paymentJoin .= 'LEFT JOIN (SELECT MAX(id) max_id, post_id FROM ' . DBTool::table('payments') . ' WHERE active=1 GROUP BY post_id) mpy ON mpy.post_id = a.id AND a.featured=1' . "\n";
			//$paymentJoin .= 'LEFT JOIN ' . DBTool::table('payments') . ' as py ON py.id=mpy.max_id' . "\n";
			$paymentJoin .= 'LEFT JOIN ' . DBTool::table('packages') . ' as p ON p.id=u.package_id' . "\n";
		}
		$reviewedCondition = '';
		if (config('settings.single.posts_review_activation')) {
			$reviewedCondition = ' AND a.reviewed = 1';
		}
		$sql = 'SELECT DISTINCT a.*,u.name as compnay_name ,u.username , p.id as py_package_id' . '
                FROM ' . DBTool::table('posts') . ' as a
                LEFT JOIN users u on u.id=a.user_id
                INNER JOIN ' . DBTool::table('categories') . ' as c ON c.id=a.category_id AND c.active=1
                ' . $paymentJoin . '
                WHERE a.country_code = :countryCode
                	AND (u.verified_email=1 AND u.verified_phone=1)
                	AND a.archived!=1 ' . $reviewedCondition . $sponsoredCondition . '
                GROUP BY u.id
                ORDER BY ' . $sponsoredOrder . 'rand()
                LIMIT 0,' . (int)$limit;
		$bindings = [
			'countryCode' => config('country.code'),
		];


		$cacheId = config('country.code') . '.home.getPosts.' . $type;
		$posts = Cache::remember($cacheId, $cacheExpiration, function () use ($sql, $bindings) {
			$posts = DB::select(DB::raw($sql), $bindings);

			return $posts;
		});


		// Append the Posts 'uri' attribute
		$posts = collect($posts)->map(function ($post) {
			$post->title = mb_ucfirst($post->title);
			$post->uri = trans('routes.v-post', ['slug' => slugify($post->title), 'id' => $post->id]);

			return $post;
		})->toArray();

		return $posts;
	}

	/**
	 * @param array $value
	 * @return int
	 */

}
