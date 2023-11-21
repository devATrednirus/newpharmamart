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

/*
 * $colorOptions = ['luminosity' => 'light', 'hue' => ['red','orange','yellow','green','blue','purple','pink']];
 * $colorOptions = ['luminosity' => 'light'];
 */

use App\Helpers\Arr;
use App\Helpers\RandomColor;
use App\Models\Post;
use App\Models\Package;
use App\Models\Message;
use App\Models\QuickMessage;
use App\Models\Country;
use App\Models\User;
use App\Models\City;
use Illuminate\Support\Facades\Config;
use Jenssegers\Date\Date;
use Larapen\Admin\app\Http\Controllers\PanelController;
use DB;

class DashboardController extends PanelController
{
	public $data = [];
	
	protected $countCountries;
	
	/**
	 * Create a new controller instance.
	 */
	public function __construct()
	{
		$this->middleware('admin');
		
		parent::__construct();
		
		// Get the Mini Stats data
		// Count Ads
		/*$countActivatedPosts = Post::verified()->count();    
		$countUnactivatedPosts = Post::unverified()->count();
		
		// Count Users
		$countActivatedUsers = 0;
		$countUnactivatedUsers = 0;
		$countUsers = 0;
		$countSellerUsers = 0;
		try {
			$countActivatedUsers = User::doesntHave('permissions')->verified()->count();
			$countUnactivatedUsers = User::doesntHave('permissions')->unverified()->count();
			$countUsers = User::doesntHave('permissions')->where('user_type_id', '2')->count();
			$this->countSellerUsers = User::doesntHave('permissions')->where('user_type_id', '1')->count();
		} catch (\Exception $e) {}
		
		// Count activated countries
		$this->countCountries = Country::where('active', 1)->count();
		
		view()->share('countActivatedPosts', $countActivatedPosts);
		view()->share('countUnactivatedPosts', $countUnactivatedPosts);
		view()->share('countActivatedUsers', $countActivatedUsers);
		view()->share('countUnactivatedUsers', $countUnactivatedUsers);
		view()->share('countUsers', $countUsers);
		view()->share('countSellerUsers', $this->countSellerUsers);

		view()->share('countCountries', $this->countCountries);*/
	}
	
	/**
	 * Show the admin dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function dashboard()
	{

		//return redirect(admin_uri('messages'));

		if(isset($_GET['test']) && $_GET['test']=="dash"){

		}
		else{

//			return redirect(admin_uri('messages'));
		}

		// Dashboard Latest Entries Chart: 'bar' or 'line'
		Config::set('settings.app.dashboard_latest_entries_chart', 'line');
		
		// Limit latest entries
		$latestEntriesLimit = config('settings.app.latest_entries_limit', 5);
		
		// -----
		
		// Get latest Ads
		$this->data['latestPosts'] = Post::with('user')->take($latestEntriesLimit)->orderBy('id', 'DESC')->get();

		$this->data['latestMessages'] = Message::take($latestEntriesLimit)->where('blocked','0')->orderBy('sent_at', 'DESC')->get();
		
		// Get latest Users
		$this->data['latestUsers'] = User::take($latestEntriesLimit)->where('user_type_id', '2')->orderBy('id', 'DESC')->get();

		$this->data['latestSeller'] = User::take($latestEntriesLimit)->where('user_type_id', '1')->orderBy('id', 'DESC')->get();

		

		$blockedLocations = City::select('cities.subadmin1_code',DB::raw('count(cities.id) as count'))->join('subadmin1', 'cities.subadmin1_code', '=', 'subadmin1.code')->has('locationFilter')->groupBy('subadmin1_code')->get();
		//dd(DB::getQueryLog());

		$this->data['blockedLocations'] = $blockedLocations->chunk(ceil($blockedLocations->count() / 2));

		//dd($this->data['blockedLocations']);

		$index = 0;

		

		$currentDate = Date::now()->startOfMonth();

		if (request()->get('index') != '') {

			$index= (int)request()->get('index');	
			if($index>0){
			$currentDate->subMonths($index);		

			}
		}
		

		
		$endDate = clone $currentDate;

		$endDate->endOfMonth();
		 
		$this->data['date_index'] =$index;
		// Get latest entries charts
		$statDayNumber = $currentDate->diff($endDate)->days+1;

		$this->data['date_range'] = $currentDate->format('d-M-Y')." to ".$endDate->format('d-M-Y');

		 
		$this->data['latestPostsChart'] = $this->getLatestPostsChart($statDayNumber,clone $currentDate);
		$this->data['latestUsersChart'] = $this->getLatestUsersChart($statDayNumber,clone $currentDate);

		$this->data['packageUsersChart'] = $this->getPackageUsersChart();
		
		$this->data['sharedCounts'] = $this->getSharedCounts($currentDate,$endDate);

		$this->data['total_shared'] = 0;
		$this->data['total_direct'] = 0;
		$this->data['total_promise'] = 0;

		$this->data['latestMessageChart'] = $this->getLatestMessageChart($statDayNumber,clone $currentDate);
		$this->data['latestDirectMessageChart'] = $this->getLatestDirectMessageChart($statDayNumber,clone $currentDate);
		

		
		// Get entries per country charts
		if (config('settings.app.show_countries_charts')) {
			$countriesLimit = 10;
			$this->data['postsPerCountry'] = $this->getPostsPerCountryChart($countriesLimit);
			$this->data['usersPerCountry'] = $this->getUsersPerCountryChart($countriesLimit);
		}
		
		// -----
		
		// Page Title
		$this->data['title'] = trans('admin::messages.dashboard');
		
		return view('admin::dashboard.index', $this->data);
	}
	
	/**
	 * @param int $statDayNumber
	 * @return array
	 */
	private function getLatestPostsChart($statDayNumber = 30,$currentDate)
	{
		// Init.
		$statDayNumber = (is_numeric($statDayNumber)) ? $statDayNumber : 30;
		 
		
		$stats = [];
		for ($i = 1; $i <= $statDayNumber; $i++) {
			$dateObj = ($i == 1) ? $currentDate : $currentDate->addDay();
			$date = $dateObj->toDateString();
			

			 
			// Ads Stats
			$countActivatedPosts = Post::verified()
				->where('created_at', '>=', $date)
				->where('created_at', '<=', $date . ' 23:59:59')
				->count();
			
			$countUnactivatedPosts = Post::unverified()
				->where('created_at', '>=', $date)
				->where('created_at', '<=', $date . ' 23:59:59')
				->count();
			
			$stats['posts'][$i]['y'] = mb_ucfirst($dateObj->formatLocalized('%b %d'));
			$stats['posts'][$i]['activated'] = $countActivatedPosts;
			$stats['posts'][$i]['unactivated'] = $countUnactivatedPosts;
		}

		
		//$stats['posts'] = array_reverse($stats['posts'], true);
		
		$data = json_encode(array_values($stats['posts']), JSON_NUMERIC_CHECK);
		
		$boxData = [
			'title' => trans('admin::messages.Ads Stats'),
			'data'  => $data,
		];
		$boxData = Arr::toObject($boxData);
		
		return $boxData;
	}
	

	private function getPackageUsersChart()
	{
		// Init.

		//dd($this->countSellerUsers);
		$packages = Package::withCount('users')->orderBy('lft','asc')->get();
		
		$stats = [];
		foreach ($packages as $key => $value) {

			$stats[$key]['y'] = $value->name;
			$stats[$key]['users_count'] = $value->users_count;
		}

 
		$data = json_encode(array_values($stats), JSON_NUMERIC_CHECK);
	 
		$boxData = [
			'title' => 'Package wise users',
			'data'  => $data,
		];
		$boxData = Arr::toObject($boxData);
		 
		return $boxData;
	}

	/**
	 * @param int $statDayNumber
	 * @return array
	 */
	private function getLatestUsersChart($statDayNumber = 30,$currentDate)
	{
		// Init.
		$statDayNumber = (is_numeric($statDayNumber)) ? $statDayNumber : 30;
		 
		
		$stats = [];
		for ($i = 1; $i <= $statDayNumber; $i++) {
			$dateObj = ($i == 1) ? $currentDate : $currentDate->addDay();
			$date = $dateObj->toDateString();
			
			// Users Stats
			$countActivatedUsers = User::doesntHave('permissions')
				->verified()
				->where('user_type_id', '2')
				->where('created_at', '>=', $date)
				->where('created_at', '<=', $date . ' 23:59:59')
				->count();
			
			$countUnactivatedUsers = User::doesntHave('permissions')
				->unverified()
				->where('user_type_id', '2')
				->where('created_at', '>=', $date)
				->where('created_at', '<=', $date . ' 23:59:59')
				->count();

			$countActivatedSellers = User::doesntHave('permissions')
				->verified()
				->where('user_type_id', '1')
				->where('created_at', '>=', $date)
				->where('created_at', '<=', $date . ' 23:59:59')
				->count();
			
			$countUnactivatedSellers = User::doesntHave('permissions')
				->unverified()
				->where('user_type_id', '1')
				->where('created_at', '>=', $date)
				->where('created_at', '<=', $date . ' 23:59:59')
				->count();
			
			$stats['users'][$i]['y'] = mb_ucfirst($dateObj->formatLocalized('%b %d'));
			$stats['users'][$i]['activated'] = $countActivatedUsers;
			$stats['users'][$i]['unactivated'] = $countUnactivatedUsers;

			$stats['users'][$i]['activated_sellers'] = $countActivatedSellers;
			$stats['users'][$i]['unactivated_sellers'] = $countUnactivatedSellers;
		}
		
		//$stats['users'] = array_reverse($stats['users'], true);
		
		$data = json_encode(array_values($stats['users']), JSON_NUMERIC_CHECK);
		
		$boxData = [
			'title' => trans('admin::messages.Users Stats'),
			'data'  => $data,
		];
		$boxData = Arr::toObject($boxData);
		
		return $boxData;
	}

	/**
	 * @param int $statDayNumber
	 * @return array
	 */
	private function getSharedCounts($currentDate,$endDate)
	{
		 
		DB::enableQueryLog();
		$countCompanyMessages = User::where('user_type_id','1')->whereDate('created_at','<=',$endDate)->where('id','<>','1')->where('package_id','<>','0')->withCount(['shared'=>function($query)use($currentDate,$endDate){
			$query->whereBetween('sent_at',[$currentDate,$endDate]);
		}])->withCount(['direct'=>function($query)use($currentDate,$endDate){
			$query->whereBetween('sent_at',[$currentDate,$endDate]);
		}])->with('package')->get()->sortByDesc(function($message, $key) {
			if($message->package)
				return [$message->package->monthly_leads,$message->shared_count];
			else
				return [0,$message->shared_count];
		});;
		//dd(DB::getQueryLog());
		 /*

		 
		$countCompanyMessages = Message::select(DB::raw('count(id) as shared_count, to_user_id'))->whereBetween('sent_at',[$currentDate,$endDate])
				->where(function($query){
					$query->whereNotNull('message_id')->orWhereNotNull('quick_message_id');
					
					
				})
				->where('to_user_id','<>','1')
				->whereNotNull('to_user_id')
				->where('blocked','0')
				->with('receiver.package')
				->whereHas('receiver',function($query){
					$query->where('package_id','<>','0');
				})
				->groupBy('to_user_id')->get()->sortByDesc(function($message, $key) {

					 
		          return [$message->receiver->package->monthly_leads,$message->count];
		        });;
		
		//dd($countCompanyMessages);
		foreach ($countCompanyMessages as $data) {
			
			$direct = Message::select(DB::raw('count(id) as count, to_user_id'))->whereBetween('sent_at',[$currentDate,$endDate])
				->where(function($query){
					$query->whereNull('message_id')->whereNull('quick_message_id');
				})
				->where('to_user_id',$data->to_user_id)
				 ->where('blocked','0')
				->groupBy('to_user_id')->first();

			if(isset($direct->count)){
				$data->count =  $direct->count;

			}
			else{
				$data->count = 0;				
			}
			
			 
		}*/

		return $countCompanyMessages;
	}

	/**
	 * @param int $statDayNumber
	 * @return array
	 */
	private function getLatestMessageChart($statDayNumber = 30,$currentDate)
	{
		// Init.
		$statDayNumber = (is_numeric($statDayNumber)) ? $statDayNumber : 30;
		 
		
		$stats = [];
		for ($i = 1; $i <= $statDayNumber; $i++) {
			$dateObj = ($i == 1) ? $currentDate : $currentDate->addDay();
			$date = $dateObj->toDateString();
			
			// Ads Stats
			$countCompanyMessages = Message::where('sent_at', '>=', $date)
				->where('sent_at', '<=', $date . ' 23:59:59')
				->whereNull('message_id')
				->where('blocked','0')
				->whereNull('quick_message_id')
				->where('type','direct')
				->where('post_id','0')
				->count();

			$countPostMessages = Message::where('sent_at', '>=', $date)
				->where('sent_at', '<=', $date . ' 23:59:59')
				->where('type','direct')
				->where('blocked','0')
				->whereNull('message_id')
				->whereNull('quick_message_id')
				->where('post_id','<>','0')
				->count();
			
			/*$countSharedMessages = Message::where('sent_at', '>=', $date)
				->where('sent_at', '<=', $date . ' 23:59:59')
				->where(function($query){
					$query->whereNotNull('message_id');//->orWhereNotNull('quick_message_id');
				})
				->where('type','direct')
				->count();*/

			$countSharedMessages = Message::where('sent_at', '>=', $date)
				->where('sent_at', '<=', $date . ' 23:59:59')
				->whereHas('mainQuery',function($query){
					$query->where('type','direct');
				})
				->where('blocked','0')
				->where(function($query){
					$query->whereNotNull('message_id');
				})
				->count();			
			$stats['messages'][$i]['y'] = mb_ucfirst($dateObj->formatLocalized('%b %d'));
			$stats['messages'][$i]['company'] = $countCompanyMessages;
			$stats['messages'][$i]['post'] = $countPostMessages;
			$stats['messages'][$i]['shared'] = $countSharedMessages;

			
		}

		
		
		//$stats['messages'] = array_reverse($stats['messages'], true);


		
		$data = json_encode(array_values($stats['messages']), JSON_NUMERIC_CHECK);
		
		$boxData = [
			'title' => 'Direct Queries',
			'data'  => $data,
		];
		$boxData = Arr::toObject($boxData);
		  
		return $boxData;
	}

	private function getLatestDirectMessageChart($statDayNumber = 30,$currentDate)
	{


		// Init.
		$statDayNumber = (is_numeric($statDayNumber)) ? $statDayNumber : 30;
	 
		
		$stats = [];
		for ($i = 1; $i <= $statDayNumber; $i++) {
			$dateObj = ($i == 1) ? $currentDate : $currentDate->addDay();
			$date = $dateObj->toDateString();
			
			// Ads Stats
			$countSubmittedMessages = Message::where('sent_at', '>=', $date)
				->where('sent_at', '<=', $date . ' 23:59:59')
				->where('type','quick')
				->where('blocked','0')
				->whereNull('message_id')
				->whereNull('quick_message_id')
				->count();
			
			$countPendingMessages = Message::onlyTrashed()->where('sent_at', '>=', $date)
				->where('sent_at', '<=', $date . ' 23:59:59')
				->where('type','quick')
				->where('blocked','0') 
				->count();

			$countSharedMessages = Message::where('sent_at', '>=', $date)
				->where('sent_at', '<=', $date . ' 23:59:59')
				->where('blocked','0')
				->whereHas('mainQuery',function($query){
					$query->where('type','quick');
				})
				->where(function($query){
					$query->whereNotNull('message_id');
				})
				->count();
			
			$stats['messages'][$i]['y'] = mb_ucfirst($dateObj->formatLocalized('%b %d'));
			$stats['messages'][$i]['submitted'] = $countSubmittedMessages;
			$stats['messages'][$i]['pedning'] = $countPendingMessages;
			$stats['messages'][$i]['shared'] = $countSharedMessages;

			
		}

		
	 
		//$stats['messages'] = array_reverse($stats['messages'], true);
		
		$data = json_encode(array_values($stats['messages']), JSON_NUMERIC_CHECK);
		
		$boxData = [
			'title' => 'Quick Queries',
			'data'  => $data,
		];
		$boxData = Arr::toObject($boxData);
		  

		return $boxData;
	}
	
	/**
	 * @param int $limit
	 * @param array $colorOptions
	 * @return array
	 */
	private function getPostsPerCountryChart($limit = 10, $colorOptions = [])
	{
		// Init.
		$limit = (is_numeric($limit) && $limit > 0) ? $limit : 10;
		$colorOptions = (is_array($colorOptions)) ? $colorOptions : [];
		$data = [];
		
		// Get Data
		if ($this->countCountries > 1) {
			$countries = Country::active()->has('posts')->withCount('posts')->get()->sortByDesc(function ($country) {
				return $country->posts_count;
			})->take($limit);
			
			// Format Data
			if ($countries->count() > 0) {
				foreach ($countries as $country) {
					$data['datasets'][0]['data'][] = $country->posts_count;
					$data['datasets'][0]['backgroundColor'][] = RandomColor::one($colorOptions);
					$data['labels'][] = (!empty($country->asciiname)) ? $country->asciiname : $country->name;
				}
				$data['datasets'][0]['label'] = trans('admin::messages.Posts Dataset');
			}
		}
		
		$data = json_encode($data, JSON_NUMERIC_CHECK);
		
		$boxData = [
			'title'          => trans('admin::messages.Ads per Country') . ' (' . trans('admin::messages.Most active Countries') . ')',
			'data'           => $data,
			'countCountries' => $this->countCountries,
		];
		$boxData = Arr::toObject($boxData);
		
		return $boxData;
	}
	
	/**
	 * @param int $limit
	 * @param array $colorOptions
	 * @return array
	 */
	private function getUsersPerCountryChart($limit = 10, $colorOptions = [])
	{
		// Init.
		$limit = (is_numeric($limit) && $limit > 0) ? $limit : 10;
		$colorOptions = (is_array($colorOptions)) ? $colorOptions : [];
		$data = [];
		
		// Get Data
		if ($this->countCountries > 1) {
			$countries = Country::active()->has('users')->withCount('users')->get()->sortByDesc(function ($country) {
				return $country->users_count;
			})->take($limit);
			
			// Format Data
			if ($countries->count() > 0) {
				foreach ($countries as $country) {
					$data['datasets'][0]['data'][] = $country->users_count;
					$data['datasets'][0]['backgroundColor'][] = RandomColor::one($colorOptions);
					$data['labels'][] = (!empty($country->asciiname)) ? $country->asciiname : $country->name;
				}
				$data['datasets'][0]['label'] = trans('admin::messages.Users Dataset');
			}
		}
		
		$data = json_encode($data, JSON_NUMERIC_CHECK);
		
		$boxData = [
			'title'          => trans('admin::messages.Users per Country') . ' (' . trans('admin::messages.Most active Countries') . ')',
			'data'           => $data,
			'countCountries' => $this->countCountries,
		];
		$boxData = Arr::toObject($boxData);
		
		return $boxData;
	}
	
	/**
	 * Redirect to the dashboard.
	 *
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function redirect()
	{
		// The '/admin' route is not to be used as a page, because it breaks the menu's active state.
		return redirect(admin_uri('dashboard'));
	}
}
