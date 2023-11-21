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

/*
 * Increase PHP page execution time for this controller.
 * NOTE: This function has no effect when PHP is running in safe mode (http://php.net/manual/en/ini.sect.safe-mode.php#ini.safe-mode).
 * There is no workaround other than turning off safe mode or changing the time limit (max_execution_time) in the php.ini.
 */
set_time_limit(0);

use App\Helpers\Arr;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Models\Category;
use App\Models\Page;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\City;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Watson\Sitemap\Facades\Sitemap;
use App\Models\SubAdmin1;
use DB;
class SitemapsController extends FrontController
{
	protected $defaultDate = '2022-06-30T20:10:00+02:00';
	
	/**
	 * SitemapsController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Get Countries
		$this->countries = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		
		// From Laravel 5.3.4 or above
		$this->middleware(function ($request, $next) {
			$this->commonQueries();
			return $next($request);
		});
	}
	
	/**
	 * Common Queries
	 */
	public function commonQueries()
	{
		// Set the Country's Locale & Default Date
		$this->applyCountrySettings();
	}
	
	/**
	 * Index Sitemap
	 *
	 * @return mixed
	 */
	public function index()
	{
		foreach ($this->countries as $item) {
			// Get Country Settings
			$country = $this->getCountrySettings($item->get('code'), false);
			if (empty($country)) {
				continue;
			}
			
			Sitemap::addSitemap(localUrl($country, $country->icode . '/sitemaps.xml'));
		}
		
		return Sitemap::index();
	}
	
	/**
	 * Index Single Country Sitemap
	 *
	 * @param null $countryCode
	 * @return bool|\Illuminate\Http\Response
	 */
	public function site($countryCode = null)
	{
		if (empty($countryCode)) {
			$countryCode = config('country.code');
		}
		
		// Get Country Settings
		$country = $this->getCountrySettings($countryCode);
		if (empty($country)) {
			return false;
		}
		
		Sitemap::addSitemap(localUrl(collect($country), $country->icode . '/sitemaps/pages.xml'));
		Sitemap::addSitemap(localUrl(collect($country), $country->icode . '/sitemaps/categories.xml'));
		Sitemap::addSitemap(localUrl(collect($country), $country->icode . '/sitemaps/states.xml'));
		//Sitemap::addSitemap(localUrl(collect($country), $country->icode . '/sitemaps/cities.xml'));
		Sitemap::addSitemap(localUrl(collect($country), $country->icode . '/sitemaps/companies.xml'));

		
		$countPosts = Post::verified()->countryOf($country->code)->count();
		if ($countPosts > 0) {
			Sitemap::addSitemap(localUrl(collect($country), $country->icode . '/sitemaps/posts.xml'));
		}


		$limit = 1000;
		$cacheId = $country->icode . '.cities.take.' . $limit;
		$cities = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country, $limit) {
			$cities = City::countryOf($country->code)->take($limit)->orderBy('population', 'DESC')->orderBy('name')->get();
                      //->where(['seo'=>1])
			
			return $cities;
		});


		
		if ($cities->count() > 0) {
			foreach ($cities as $city) {
				$city->name = trim(head(explode('/', $city->name)));
 
				Sitemap::addSitemap(localUrl(collect($country), $country->icode . '/sitemaps/cities/'.slugify($city->name).'.xml'));


			}
		}
		
		return Sitemap::index();
	}
	
	/**
	 * @param null $countryCode
	 * @return bool|\Illuminate\Http\Response
	 */
	public function pages($countryCode = null)
	{
		if (empty($countryCode)) {
			$countryCode = config('country.code');
		}
		
		// Get Country Settings
		$country = $this->getCountrySettings($countryCode);
		if (empty($country)) {
			return false;
		}
		
		$queryString = '?d=' . $country->code;
		
		$url = lurl('/') . $queryString;
		Sitemap::addTag($url, $this->defaultDate, 'weekly', '0.9');
		
		$attr = ['countryCode' => $country->icode];
		$url = lurl(trans('routes.v-sitemap', $attr, $country->locale), $attr, $country->locale) . $queryString;
		Sitemap::addTag($url, $this->defaultDate, 'weekly', '0.9');
		
		$attr = ['countryCode' => $country->icode];
		$url = lurl(trans('routes.v-search', $attr, $country->locale), $attr, $country->locale) . $queryString;
		Sitemap::addTag($url, $this->defaultDate, 'weekly', '0.9');
		
		$pages = Cache::remember('pages.' . $country->locale, $this->cacheExpiration, function () use ($country) {
			$pages = Page::transIn($country->locale)->orderBy('lft', 'ASC')->get();
			
			return $pages;
		});
		
		if ($pages->count() > 0) {
			foreach ($pages as $page) {
				$attr = ['slug' => $page->slug];
				$url = lurl(trans('routes.v-page', $attr, $country->locale), $attr, $country->locale);
				Sitemap::addTag($url, $this->defaultDate, 'daily', '0.9');
			}
		}
		
		$url = lurl(trans('routes.contact', [], $country->locale) . $queryString, [], $country->locale);
		Sitemap::addTag($url, $this->defaultDate, 'weekly', '0.8');
		
		return Sitemap::render();
	}
	
	/**
	 * @param null $countryCode
	 * @return bool|\Illuminate\Http\Response
	 */
	public function categories($countryCode = null)
	{
		if (empty($countryCode)) {
			$countryCode = config('country.code');
		}
		
		// Get Country Settings
		$country = $this->getCountrySettings($countryCode);
		if (empty($country)) {
			return false;
		}
		
		// Categories
		$cacheId = 'categories.' . $country->locale . '.all';
		$cats = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country) {
			$cats = Category::transIn($country->locale)->orderBy('lft')->get();
			
			return $cats;
		});
		
		if ($cats->count() > 0) {
			$cats = collect($cats)->keyBy('translation_of');
			$cats = $subCats = $cats->groupBy('parent_id');
			$cats = $cats->get(0);
			$subCats = $subCats->forget(0);
			
			foreach ($cats as $cat) {
				$attr = ['countryCode' => $country->icode, 'catSlug' => $cat->slug];
				$url = lurl(trans('routes.v-search-cat', $attr, $country->locale), $attr, $country->locale);
				Sitemap::addTag($url, $this->defaultDate, 'daily', '1');
				
				if ($subCats->get($cat->tid)) {
					$datasub=DB::table('categories')->where(['parent_id'=>$cat->tid])->get();
					foreach ($datasub as $subCat) {

						$attr = [
							'countryCode' => $country->icode,
							'catSlug'     => $subCat->slug,
							'subCatSlug'  => $subCat->slug,
						];
						$url = lurl(trans('routes.v-search-subCat', $attr, $country->locale), $attr, $country->locale);
						Sitemap::addTag(url('/').'/'.$url, $this->defaultDate, 'daily', '1');

$datamicro=DB::table('categories')->where(['parent_id'=>$subCat->id])->get();
foreach ($datamicro as $microCat) {

						$attr = [
							'countryCode' => $country->icode,
							'catSlug'     => $microCat->slug,
							'subCatSlug'  => $microCat->slug,

						];
						$url = lurl(trans('routes.v-search-subCat', $attr, $country->locale), $attr, $country->locale);
						Sitemap::addTag(url('/').'/'.$url, $this->defaultDate, 'daily', '1');
					}

					}
			}
			}
		}
		
		return Sitemap::render();
	}
	
	/**
	 * @param null $countryCode
	 * @return bool|\Illuminate\Http\Response
	 */
	public function cities($countryCode = null)
	{
		if (empty($countryCode)) {
			$countryCode = config('country.code');
		}
		
		/*// Get Country Settings
		$country = $this->getCountrySettings($countryCode);
		if (empty($country)) {
			return false;
		}
		
		$limit = 1000;
		$cacheId = $country->icode . '.cities.take.' . $limit;
		$cities = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country, $limit) {
			$cities = City::countryOf($country->code)->take($limit)->orderBy('population', 'DESC')->where('seo' , 1)->orderBy('name')->get();
			
			return $cities;
		});
		
		if ($cities->count() > 0) {
			foreach ($cities as $city) {
				$city->name = trim(head(explode('/', $city->name)));
				$attr = [
					'countryCode' => $country->icode,
					'city'        => slugify($city->name),
					'id'          => $city->id,
				];
				$url = lurl(trans('routes.v-search-city', $attr, $country->locale), $attr, $country->locale);
				Sitemap::addTag($url, $this->defaultDate, 'weekly', '0.7');


			}
		}*/
		
		return Sitemap::render();
	}

	/**
	 * @param null $countryCode
	 * @return bool|\Illuminate\Http\Response
	 */
	public function citiesCats($countryCode = null,$city)
	{

		
		if (empty($countryCode)) {
			$countryCode = config('country.code');
		}
		
		// Get Country Settings
		$country = $this->getCountrySettings($countryCode);
		if (empty($country)) {
			return false;
		}
		
		$limit = 1000;
		$cacheId = $country->icode.$city. '.cities.take.' . $limit;
		$cities = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country,$city, $limit) {
			$cities = City::countryOf($country->code)->where('name',str_replace("-", " ", $city))->take($limit)->orderBy('population', 'DESC')->where('seo' , 1)->orderBy('name')->get();
			
			return $cities;
		});

		if ($cities->count() > 0) {
			foreach ($cities as $city) {

				$city->name = trim(head(explode('/', $city->name)));
				$attr = [
					'countryCode' => $country->icode,
					'city'        => slugify($city->name),
					'id'          => $city->id,
				];
				$url = lurl(trans('routes.v-search-city', $attr, $country->locale), $attr, $country->locale);
				Sitemap::addTag($url, $this->defaultDate, 'weekly', '0.9');
				
				$cacheId = 'categories.' . $country->locale . '.all';
				$cats = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country) {
					$cats = Category::transIn($country->locale)->orderBy('lft')->get();
					
					return $cats;
				});
				
				if ($cats->count() > 0) {
					$cats = collect($cats)->keyBy('translation_of');
					$cats = $subCats = $cats->groupBy('parent_id');
					$cats = $cats->get(0);
					$subCats = $subCats->forget(0);
					
					foreach ($cats as $cat) {
						$attr = ['countryCode' => $country->icode, 'city'        => slugify($city->name),'catSlug' => $cat->slug];
						$url = lurl(trans('routes.v-search-cat-location', $attr, $country->locale), $attr, $country->locale);
					
						

						Sitemap::addTag($url, $this->defaultDate, 'weekly', '0.9');
						
						if ($subCats->get($cat->tid)) {
							$datasub=DB::table('categories')->where(['parent_id'=>$cat->tid])->get();	
							foreach ($subCats->get($cat->tid) as $subCat) {
								$attr = [
									'countryCode' => $country->icode,
									'catSlug'     => $subCat->slug,
									'city'        => slugify($city->name),
									//'subCatSlug'  => $subCat->slug,
								];
								$url = lurl(trans('routes.v-search-cat-location', $attr, $country->locale), $attr, $country->locale);
								Sitemap::addTag($url, $this->defaultDate, 'weekly', '0.9');
						$datamicro=DB::table('categories')->where(['parent_id'=>$subCat->id])->get();
foreach ($datamicro as $microCat) {

						$attr = [
							'countryCode' => $country->icode,
							'catSlug'     => $microCat->slug,
							'city'  => slugify($city->name),

						];
						$url = lurl(trans('routes.v-search-subCat-location', $attr, $country->locale), $attr, $country->locale);
						Sitemap::addTag($url, $this->defaultDate, 'weekly', '0.9');
					}
							}
						}
					}
				}
			}
		}
		
		return Sitemap::render();
	}

	/**
	 * @param null $countryCode
	 * @return bool|\Illuminate\Http\Response
	 */
	public function states_back($countryCode = null)
	{
		if (empty($countryCode)) {
			$countryCode = config('country.code');
		}
		
		// Get Country Settings
		$country = $this->getCountrySettings($countryCode);
		if (empty($country)) {
			return false;
		}
		
		$limit = 1000;
		$cacheId = $country->icode . '.state.take.' . $limit;
		//$cities = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country, $limit) {
			$cities = SubAdmin1::countryOf($country->code)->take($limit)->orderBy('name', 'asc')->orderBy('name')->get();
			
		/*	return $cities;
 
		});*/
		
		if ($cities->count() > 0) {
			foreach ($cities as $city) {
				$city->name = trim(head(explode('/', $city->name)));
				$attr = [
					'countryCode' => $country->icode,
					'city'        => slugify($city->name),
					'id'          => $city->id,
				];
				$url = lurl(trans('routes.v-search-city', $attr, $country->locale), $attr, $country->locale);
				Sitemap::addTag($url, $this->defaultDate, 'weekly', '0.9');

				$cacheId = 'categories.' . $country->locale . '.all';
				$cats = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country) {
					$cats = Category::transIn($country->locale)->orderBy('lft')->get();
					
					return $cats;
				});
				
				if ($cats->count() > 0) {
					$cats = collect($cats)->keyBy('translation_of');
					$cats = $subCats = $cats->groupBy('parent_id');
					$cats = $cats->get(0);
					$subCats = $subCats->forget(0);
					
					foreach ($cats as $cat) {
						$attr = ['countryCode' => $country->icode, 'city'        => slugify($city->name),'catSlug' => $cat->slug];
						$url = lurl(trans('routes.v-search-cat-location', $attr, $country->locale), $attr, $country->locale);
						
						

						Sitemap::addTag($url, $this->defaultDate, 'daily', '0.9');
						
						if ($subCats->get($cat->tid)) {
						$datasub=DB::table('categories')->where(['parent_id'=>$cat->tid])->get();	
							foreach ($subCats->get($cat->tid) as $subCat) {
								$attr = [
									'countryCode' => $country->icode,
									'catSlug'     => $subCat->slug,
									'city'        => slugify($city->name),
									//'subCatSlug'  => $subCat->slug,
								];
								$url = lurl(trans('routes.v-search-cat-location', $attr, $country->locale), $attr, $country->locale);
								Sitemap::addTag($url, $this->defaultDate, 'daily', '0.9');
$datamicro=DB::table('categories')->where(['parent_id'=>$subCat->id])->get();
foreach ($datamicro as $microCat) {

						$attr = [
							'countryCode' => $country->icode,
							'catSlug'     => $microCat->slug,
							'city'  => slugify($city->name),

						];
						$url = lurl(trans('routes.v-search-subCat-location', $attr, $country->locale), $attr, $country->locale);
						Sitemap::addTag($url, $this->defaultDate, 'daily', '0.9');
					}
							}
						}
							}
						}
					}
				}

		return Sitemap::render();
	}
	/**
	 * @param null $countryCode
	 * @return bool|\Illuminate\Http\Response
	 */
	public function statedetail($countryCode = null, $statename)
	{
		if (empty($countryCode)) {
			$countryCode = config('country.code');
		}

		// Get Country Settings
		$country = $this->getCountrySettings($countryCode);
		if (empty($country)) {
			return false;
		}
		 
		$limit = 1000;
		$cacheId = $country->icode . '.state.take.' . $limit;
		//$cities = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country, $limit) {
		$cities = SubAdmin1::countryOf($country->code)->take($limit)->orderBy('name', 'asc')->orderBy('name')->get();

		/*	return $cities;
 
		});*/

		if ($cities->count() > 0) {

			$cacheId = 'categories.' . $country->locale . '.all';
			$cats = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country) {
				$cats = Category::transIn($country->locale)->where(['active'=>1])->orderBy('lft')->get();

				return $cats;
			});

			if ($cats->count() > 0) {
				$cats = collect($cats)->keyBy('translation_of');
				$cats = $subCats = $cats->groupBy('parent_id');
				$cats = $cats->get(0);
				$subCats = $subCats->forget(0);

				foreach ($cats as $cat) {
					$attr = ['countryCode' => $country->icode, 'city'        => $statename, 'catSlug' => $cat->slug];
					$url = lurl(trans('routes.v-search-cat-location', $attr, $country->locale), $attr, $country->locale);



					Sitemap::addTag($url, $this->defaultDate, 'daily', '0.9');

					if ($subCats->get($cat->tid)) {
						$datasub = DB::table('categories')->where(['parent_id' => $cat->tid])->get();
						foreach ($subCats->get($cat->tid) as $subCat) {
							$attr = [
								'countryCode' => $country->icode,
								'catSlug'     => $subCat->slug,
								'city'        => $statename,
								//'subCatSlug'  => $subCat->slug,
							];
							$url = lurl(trans('routes.v-search-cat-location', $attr, $country->locale), $attr, $country->locale);
							Sitemap::addTag($url, $this->defaultDate, 'daily', '0.9');
							$datamicro = DB::table('categories')->where(['parent_id' => $subCat->id])->get();
							foreach ($datamicro as $microCat) {

								$attr = [
									'countryCode' => $country->icode,
									'catSlug'     => $microCat->slug,
									'city'  => $statename,

								];
								$url = lurl(trans('routes.v-search-subCat-location', $attr, $country->locale), $attr, $country->locale);
								Sitemap::addTag($url, $this->defaultDate, 'daily', '0.9');
							}
						}
					}
				}
			}
		}
		
		return Sitemap::render();
	}
	public function states($countryCode = null)
	{
		if (empty($countryCode)) {
			$countryCode = config('country.code');
		}

		// Get Country Settings
		$country = $this->getCountrySettings($countryCode);
		if (empty($country)) {
			return false;
		}

		$limit = 1000;
		//$cities = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country, $limit) {
		$cities = SubAdmin1::countryOf($country->code)->take($limit)->orderBy('name', 'asc')->orderBy('name')->get();

		/*	return $cities;
 
		});*/

		if ($cities->count() > 0) {
			foreach ($cities as $city) {
				$city->name = trim(head(explode('/', $city->name)));
				$attr = [
					'countryCode' => $country->icode,
					'city'        => slugify($city->name),
					'id'          => $city->id,
				];
				$url = lurl(trans('routes.v-search-city', $attr, $country->locale), $attr, $country->locale);
				//Sitemap::addTag($url, $this->defaultDate, 'weekly', '0.9');
				Sitemap::addSitemap(localUrl(collect($country), $country->icode . '/state/' . slugify($city->name) . '.xml'));
			}
		}

		return Sitemap::index();
	}
	

	
	/**
	 * @param null $countryCode
	 * @return bool|\Illuminate\Http\Response
	 */
	public function posts($countryCode = null)
	{
		if (empty($countryCode)) {
			$countryCode = config('country.code');
		}
		
		// Get Country Settings
		$country = $this->getCountrySettings($countryCode);
		if (empty($country)) {
			return false;
		}
		
		$limit = 50000;
		$cacheId = $country->icode . '.sitemaps.posts.xml';
		$posts = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country, $limit) {
			$posts = Post::verified()->countryOf($country->code)->take($limit)->orderBy('created_at', 'DESC')->get();
			
			return $posts;
		});
		
		if ($posts->count() > 0) {
			foreach ($posts as $post) {
				$attr = ['slug' => slugify($post->title), 'id' => $post->id];
				$url = lurl($post->uri, $attr, $country->locale);
				Sitemap::addTag($url, $post->created_at, 'daily', '1');
			}
		}
		
		return Sitemap::render();
	}

	/**
	 * @param null $countryCode
	 * @return bool|\Illuminate\Http\Response
	 */
	public function companies($countryCode = null)
	{
return response()->view('sitemap/indexcompany')->header('Content-Type', 'text/xml');
		
		}
		
	public function companydetail($countryCode = null, $companyname)
	{
		return response()->view('sitemap/companydetail', compact('companyname'))->header('Content-Type', 'text/xml');
	}
	public function companydetailSitemap($companyname)
	{
			 
		$data = array('companyname' => $companyname);


		$compnay_route_home = 'routes.search-user';
		$compnay_route_inner = 'routes.v-company-group';
		$sUser = User::with('businessType')->with('ownershipType')->with('city.subAdmin1')->with(['posts'=>function($query){

            $query->select('id','title','user_id')->limit(5);
        }])->where('id','<>','1')->where('username', $companyname)->first();


		view()->share('sUser', $sUser);

		view()->share('compnay_route_home', $compnay_route_home);
		view()->share('compnay_route_inner', $compnay_route_inner);


		$about_us = trans($compnay_route_inner, [
			'slug' => 'about-us',
			'username'   =>  $companyname,
		]);
		view()->share('about_us', $about_us);

		$contact_us = trans($compnay_route_inner, [
			'slug' => 'contact-us',
			'username'   =>  $companyname,
		]);
		view()->share('contact_us', $contact_us);

		$company_url  = trans($compnay_route_home, [
			'username'   =>  $companyname,
		]);
		view()->share('company_url', $company_url);
		$template = 'template1';
        $template_color = '';

        if($sUser->template){

                $template = $sUser->template;

        }

        if($sUser->color){

                $template_color = $sUser->color;

        }

        if(isset($_GET['template']) && in_array($_GET['template'],['2'])){

            $template = 'template'.$_GET['template'];
        }

        if(isset($_GET['template_color'])){

            $template_color = $_GET['template_color'];
        }

        view()->share('template', $template);

        view()->share('template_color', $template_color);
		$posts = Post::with('group')->where('user_id',$sUser->id)->orderBy('group_id','desc')->get();
	
        $groups=[];
        foreach ($posts as $key => $post) {
                
                if($post->group){
                    if(!isset($groups[$post->group_id])){

                        $groups[$post->group_id]=['data'=>$post->group,'posts'=>[]];
                    }

                   $groups[$post->group_id]['posts'][]=$post; 
                }
                else{
                    if(!isset($groups['others'])){

                        $groups['others']=['data'=>['name'=>'Others','id'=>'others'],'posts'=>[]];
                    }

                 $groups['others']['posts'][]=$post;    
                }
        }

       
        view()->share('groups', $groups);

		return view('sitemap.companydetailsitemap', $data);
			}
	/**
	 * Set the Country's Locale & Default Date
	 *
	 * @param null $locale
	 * @param null $timeZone
	 */
	public function applyCountrySettings($locale = null, $timeZone = null)
	{
		// Set the App Language
		if (!empty($locale)) {
			App::setLocale($locale);
		} else {
			App::setLocale(config('app.locale'));
		}
		
		// Date: Carbon object
		$this->defaultDate = Carbon::parse(date('Y-m-d H:i'));
		if (!empty($timeZone)) {
			$this->defaultDate->timezone($timeZone);
		} else {
			if (config('timezone.id')) {
				$this->defaultDate->timezone(config('timezone.id'));
			}
		}
	}
	
	/**
	 * Get Country Settings
	 *
	 * @param $countryCode
	 * @param bool $canApplySettings
	 * @return array|null
	 */
	public function getCountrySettings($countryCode, $canApplySettings = true)
	{
		$tab = [];
		
		// Get Country Info
		$country = CountryLocalization::getCountryInfo($countryCode);
		if ($country->isEmpty()) {
			return null;
		}
		
		$tab['code'] = $country->get('code');
		$tab['icode'] = $country->get('icode');
		
		// Language
		if (!$country->get('lang')->isEmpty() && $country->get('lang')->has('abbr')) {
			$tab['locale'] = $country->get('lang')->get('abbr');
		} else {
			$tab['locale'] = config('app.locale');
		}
		
		// TimeZone
		if (!empty($country->get('timezone')) && isset($country->get('timezone')->time_zone_id)) {
			$tab['timezone'] = $country->get('timezone')->time_zone_id;
		} else {
			$tab['timezone'] = config('timezone.id');
		}
		
		$tab = Arr::toObject($tab);
		
		// Set the Country's Locale & Default Date
		if ($canApplySettings) {
			$this->applyCountrySettings($tab->locale, $tab->timezone);
		}
		
		return $tab;
	}
}
