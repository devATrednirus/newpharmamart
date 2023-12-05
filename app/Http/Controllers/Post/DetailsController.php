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

namespace App\Http\Controllers\Post;

use App\Events\PostWasVisited;
use App\Helpers\Arr;
use App\Helpers\DBTool;
use App\Http\Controllers\Post\Traits\CustomFieldTrait;
use Illuminate\Http\Request;
use App\Http\Requests\SendMessageRequest;
use App\Http\Requests\SendCompanyMessageRequest;
use App\Http\Requests\SendQuickQueryMessageRequest;
use App\Models\Permission;
use App\Models\Post;
use App\Models\Category;
use App\Models\City;
use App\Models\Message;
use App\Models\QuickMessage;
use App\Models\Package;
use App\Http\Controllers\FrontController;
use App\Models\User;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use App\Notifications\SellerContacted;
use App\Notifications\CompanyContacted;
use App\Notifications\QuickQueryContacted;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Date\Date;
use Larapen\TextToImage\Facades\TextToImage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Models\UserFilterLocation;

class DetailsController extends FrontController
{
	use CustomFieldTrait;

	/**
	 * Post expire time (in months)
	 *
	 * @var int
	 */
	public $expireTime = 24;

	/**
	 * DetailsController constructor.
	 */
	public function __construct()
	{
		parent::__construct();

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
		// Check Country URL for SEO
		$countries = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		view()->share('countries', $countries);

		// Count Packages
		$countPackages = Package::trans()->applyCurrency()->count();
		view()->share('countPackages', $countPackages);

		// Count Payment Methods
		view()->share('countPaymentMethods', $this->countPaymentMethods);
	}

	/**
	 * Show Dost's Details.
	 *
	 * @param $postId
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index($postId)
	{
		$data = [];

		// Get and Check the Controller's Method Parameters
		$parameters = request()->route()->parameters();

		// Show 404 error if the Post's ID is not numeric
		if (!isset($parameters['id']) || empty($parameters['id']) || !is_numeric($parameters['id'])) {
			abort(404);
		}

		// Set the Parameters
		$postId = $parameters['id'];
		if (isset($parameters['slug'])) {
			$slug = $parameters['slug'];
		}

		// GET POST'S DETAILS
		if (auth()->check()) {
			// Get post's details even if it's not activated and reviewed
			$cacheId = 'post.withoutGlobalScopes.with.city.pictures.' . $postId . '.' . config('app.locale');
			$post = Cache::remember($cacheId, $this->cacheExpiration, function () use ($postId) {
				$post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
					->withCountryFix()
					->unarchived()
					->where('id', $postId)
					->with([
						'category' => function ($builder) { $builder->with(['parent']); },
						'postType',
						'city',
						'pictures',
						'latestPayment' => function ($builder) { $builder->with(['package']); },
					])
					->first();

				return $post;
			});

			// If the logged user is not an admin user...
			if (!auth()->user()->can(Permission::getStaffPermissions())) {
				// Then don't get post that are not from the user
				if (!empty($post) && $post->user_id != auth()->user()->id) {
					$cacheId = 'post.with.city.pictures.' . $postId . '.' . config('app.locale');
					$post = Cache::remember($cacheId, $this->cacheExpiration, function () use ($postId) {
						$post = Post::withCountryFix()
							->unarchived()
							->where('id', $postId)
							->with([
								'category' => function ($builder) { $builder->with(['parent']); },
								'postType',
								'city',
								'user',
								'pictures',
								'latestPayment' => function ($builder) { $builder->with(['package']); },
							])
							->first();

						return $post;
					});
				}
			}
		} else {
			$cacheId = 'post.with.city.pictures.' . $postId . '.' . config('app.locale');
			$post = Cache::remember($cacheId, $this->cacheExpiration, function () use ($postId) {
				$post = Post::withCountryFix()
					->unarchived()
					->where('id', $postId)
					->with([
						'category' => function ($builder) { $builder->with(['parent']); },
						'postType',
						'city',
						'pictures',
						'user',
						'latestPayment' => function ($builder) { $builder->with(['package']); },
					])
					->first();

				return $post;
			});
		}
		// Preview Post after activation
		if (request()->filled('preview') && request()->get('preview') == 1) {
			// Get post's details even if it's not activated and reviewed
			$post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->withCountryFix()
				->where('id', $postId)
				->with([
					'category' => function ($builder) { $builder->with(['parent']); },
					//'postType',
					'user',
					'city',
					'pictures',
					'latestPayment' => function ($builder) { $builder->with(['package']); },
				])
				->first();
		}

		// Post not found
		if (empty($post) || empty($post->category)  || empty($post->city)) {

			if(empty($post)){
				//dump($postId);
				$post = Post::with('category.parent')->archived()->find($postId);


				if($post && $post->category && $post->category->parent){

					$attr = ['countryCode' => config('country.icode'), 'catSlug' => $post->category->parent->slug, 'subCatSlug' => $post->category->slug];

	 				$url = lurl(trans('routes.v-search-subCat', $attr), $attr);



					return \Redirect::to($url, 301);
				}

			}
			abort(404, t('Post not found'));
		}


		if($parameters['slug']!=slugify($post->title)){

			$attr = ['slug' => slugify($post->title), 'id' => $post->id];

			return \Redirect::to(lurl($post->uri, $attr), 301);


		}
		// Share post's details
		view()->share('post', $post);

		// Get possible post's Author (User)
		$user = null;
		if (isset($post->user_id) && !empty($post->user_id)) {
			$user = User::find($post->user_id);
		}
		view()->share('user', $user);

		// Get user picture
		$userPhoto = (!empty($post->email)) ? Gravatar::fallback(url('images/user.jpg'))->get(trim($post->email)) : null;
		if (isset($user) && !empty($user) && isset($user->photo) && !empty($user->photo)) {
			$userPhoto = resize($user->photo);
		}
		view()->share('userPhoto', $userPhoto);

		// Get Post's user decision about comments activation
		$commentsAreDisabledByUser = false;
		if (isset($user) && !empty($user)) {
			if ($user->disable_comments == 1) {
				$commentsAreDisabledByUser = true;
			}
		}
		view()->share('commentsAreDisabledByUser', $commentsAreDisabledByUser);

		// Get Category nested IDs
		$catNestedIds = (object)[
			'parentId' => $post->category->parent_id,
			'id'       => $post->category->tid,
		];

		// Get Custom Fields
		$customFields = $this->getPostFieldsValues($catNestedIds, $post->id);
		view()->share('customFields', $customFields);

		// Increment Post visits counter
		Event::dispatch(new PostWasVisited($post));

		// GET SIMILAR POSTS
		$featured = $this->getCategorySimilarPosts($post->category, $post->user_id);
		// $featured = $this->getLocationSimilarPosts($post->city, $post->id);
		$data['featured'] = $featured;



		// SEO
		$title = $post->title . (($post->user)?', ' .$post->user->name:'') .(($post->city)?', ' .$post->city->name:'') ;//' - '.$post->city->name;

		$description = str_limit(str_strip(strip_tags($post->short_description)), 200);

		// Meta Tags
		MetaTag::set('title', $title);
		MetaTag::set('description', $description);
		if (!empty($post->tags)) {
			MetaTag::set('keywords', str_replace(',', ', ', $post->tags));
		}

		// Open Graph
		$this->og->title($title)
			->description($description)
			->type('article');
		if (!$post->pictures->isEmpty()) {
			/*if ($this->og->has('image')) {
				$this->og->forget('image')->forget('image:width')->forget('image:height');
			}
			foreach ($post->pictures as $picture) {
				$this->og->image(resize($picture->filename, 'large'), [
					'width'  => 600,
					'height' => 600,
				]);
			}*/
		}
		view()->share('og', $this->og);

		/*
		// Expiration Info
		$today = Date::now(config('timezone.id'));
		if ($today->gt($post->created_at->addMonths($this->expireTime))) {
			flash(t("Warning! This ad has expired. The product or service is not more available (may be)"))->error();
		}
		*/

		// Reviews Plugin Data
		if (config('plugins.reviews.installed')) {
			try {
				$rvPost = \App\Plugins\reviews\app\Models\Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->find($post->id);
				view()->share('rvPost', $rvPost);
			} catch (\Exception $e) {
			}
		}

		// View
		return view('post.details', $data);
	}

	/**
	 * @param $postId
	 * @param SendMessageRequest $request
	 * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function sendMessage($postId, SendMessageRequest $request)
	{

		if(!auth()->check()){

			$user = User::where('phone',$request->from_phone)->first();

			if(!$user){

				$user = new User;
	            $user->phone = $request->from_phone;
	            $user->first_name = $request->from_name;
	            $user->verified_phone = 0;
	            $user->user_type_id = 2;
	            $user->save();
			}

			$user_id = $user->id;
		}
		else{

			$user_id = auth()->user()->id;

			$user = User::find($user_id);

			if($user->roles->count() > 0 ){


				return response()->json([
	                'code' => 422,
	                'message' => "Your are not allowed to send query"
	            ],422);
				exit;
			}
		}



		// Get the Post

		$post = Post::with('user')->with('category')->unarchived()->findOrFail($postId);



		$message =  Message::withTrashed()->whereNull('message_id')->whereNull('quick_message_id')->where('from_user_id',$user_id)->where('post_id',$post->id)->whereNull('drugs_license')->first();

		if(!$message){

			// New Message
			$message = new Message();
			$message->deleted_at = \Carbon\Carbon::now();
		}




		$input = $request->only($message->getFillable());
		foreach ($input as $key => $value) {
			$message->{$key} = $value;
		}

		$message->post_id = $post->id;
		$message->from_user_id = $user_id;
		$message->to_user_id = $post->user_id;
		$message->to_name = $post->user->first_name.($post->user->last_name?" ".$post->user->last_name:"");
		$message->to_email = $post->user->email;
		$message->to_phone = $post->user->phone;
		$message->subject = $post->title;
		$message->looking_for = $post->category->name;

		$message->message = $request->input('message');;

		$message->session_id = (isset($_COOKIE['__cfduid'])?$_COOKIE['__cfduid']:session()->getId());
		$message->ip_address = request()->ip();

		$message->category_id = $post->category->id;


		//dump($message->session_id);

		/*

		if(isset($post->user->sms_to_send)){


			$message->to_phone = $post->user->sms_to_send;
		}

		if(isset($post->user->email_to_send)){
			$message->to_email = $post->user->email_to_send;
		}


		$attr = ['slug' => slugify($post->title), 'id' => $post->id];*/

			// . '<br><br>'
			// . t('Related to the ad')
			// . ': <a href="' . lurl($post->uri, $attr) . '">' . t('Click here to see') . '</a>';

		//$post->notify(new SellerContacted($post, $message));
		//dd($message);
		// Save
		$message->save();


	 	if(!auth()->check()){

 		 return response()->json([
                'code' => 100,
                'message' => "login"
            ],422);
        }
        else{

        	return response()->json([
	            "type"=>"direct_query",
	            "id"=>$message->id,
	            "email"=>auth()->user()->email,
	            "message" => "success"
	        ],200);

        }




		/*// Save and Send user's resume
		if ($request->hasFile('filename')) {
			$message->filename = $request->file('filename');
			$message->save();
		}

		// Send a message to publisher
		try {
			$message->notify(new SellerContacted($post, $message));

			$msg = t("Your message has sent successfully to :contact_name.", ['contact_name' => $post->user->name]);
			flash($msg)->success();
		} catch (\Exception $e) {
			$message->error_log = $e->getMessage();
			$message->save();
			flash($e->getMessage())->error();
		}



		return redirect(config('app.locale') . '/thankyou')->with('redirect_to', '/'.$post->uri);*/
		//return back();

		//return redirect(config('app.locale') . '/' . $post->uri);
	}

	/**
	 * @param $postId
	 * @param SendCompanyMessageRequest $request
	 * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function sendCompanyMessage($userId, SendCompanyMessageRequest $request)
	{



		if(!auth()->check()){

			$sender = User::where('phone',$request->from_phone)->first();

			if(!$sender){

				$sender = new User;
	            $sender->phone = $request->from_phone;
	            $sender->first_name = $request->from_name;
	            $sender->verified_phone = 0;
	            $sender->user_type_id = 2;
	            $sender->save();
			}

			$user_id = $sender->id;

		}
		else{

			$user_id = auth()->user()->id;

			$sender = User::find($user_id);

			if($sender->roles->count() > 0 ){


				return response()->json([
	                'code' => 422,
	                'message' => "Your are not allowed to send query"
	            ],422);
				exit;
			}
		}



		/**/


		$message =  Message::withTrashed()->whereNull('message_id')->whereNull('quick_message_id')->where('from_user_id',$user_id)->where('post_id','0')->where('to_user_id',$userId)->whereNull('drugs_license')->first();


		if(!$message){

			// New Message
			$message = new Message();
			$message->deleted_at = \Carbon\Carbon::now();
			$message->from_user_id = $user_id;
		}




		// Get the Post
		$user = User::findOrFail($userId);



		$input = $request->only($message->getFillable());
		foreach ($input as $key => $value) {
			$message->{$key} = $value;
		}



		$message->post_id = 0;
		$message->company_only = 'Yes';

		$message->to_user_id = $userId;


		$message->to_name = $user->first_name.($user->last_name?" ".$user->last_name:"");
		$message->to_email = $user->email;
		$message->to_phone = $user->phone;
		$message->subject = "Query from Rednirus Mart";

		$message->session_id = (isset($_COOKIE['__cfduid'])?$_COOKIE['__cfduid']:session()->getId());

		if(isset($user->sms_to_send)){

			$message->to_phone = $user->sms_to_send;
		}

		if(isset($user->email_to_send)){
			$message->to_email = $user->email_to_send;
		}

		$message->ip_address = request()->ip();



		// Save


		$message->save();

		if(!auth()->check()){
			return response()->json([
                'code' => 100,
                'message' => "login"
            ],422);
		}
		else{
			return response()->json([
	            "type"=>"company_query",
	            "id"=>$message->id,
	            "email"=>auth()->user()->email,
	            "message" => "success"
	        ],200);
		}




		/*// Save and Send user's resume
		if ($request->hasFile('filename')) {
			$message->filename = $request->file('filename');
			$message->save();
		}

		// Send a message to publisher
		try {
			$message->notify(new CompanyContacted($user, $message));


			$msg = t("Your message has sent successfully to :contact_name.", ['contact_name' => $user->name]);
			flash($msg)->success();
		} catch (\Exception $e) {
			$message->error_log = $e->getMessage();
			$message->save();
			flash($e->getMessage())->error();
		}

		return redirect(config('app.locale') . '/thankyou')->with('redirect_to', '/'.$user->username);*/
		//return back();
		//return redirect(config('app.locale') . '/' . $user->username);
	}


	/**
	 * @param $postId
	 * @param SendQuickQueryMessageRequest $request
	 * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function sendQuickQueryMessage(SendQuickQueryMessageRequest $request)
	{

		//dd($request->quick_query_phone);

		//dd($request->from_phone);
		if(!auth()->check()){

			$sender = User::where('phone',$request->quick_query_phone)->first();

			if(!$sender){


				$sender = new User;
	            $sender->phone = $request->quick_query_phone;
	            $sender->first_name = $request->quick_query_name;
	            $sender->verified_phone = 0;
	            $sender->user_type_id = 2;
	            $sender->save();
			}

			$user_id = $sender->id;

		}
		else{

			$user_id = auth()->user()->id;

			$sender = User::find($user_id);

			if($sender->roles->count() > 0 ){


				return response()->json([
	                'code' => 422,
	                'message' => "Your are not allowed to send query"
	            ],422);
				exit;
			}
		}




		$message =  Message::withTrashed()->where('type','quick')->where('from_user_id',$user_id)->whereNull('drugs_license')->first();


		if(!$message){

			// New Message
			$message = new Message();
			$message->deleted_at = \Carbon\Carbon::now();
			$message->from_user_id =  $user_id;
			$message->type = 'quick';
		}



		$message->to_user_id = 1;

		$user = User::findOrFail($message->to_user_id);


		$message->to_name = $user->first_name.($user->last_name?" ".$user->last_name:"");


		$message->to_email = $user->email;
		$message->to_phone = $user->phone;


		$message->from_name = $request->quick_query_name;
		$message->from_phone = $request->quick_query_phone;
		$message->message = $request->quick_query;




		if(!$sender->first_name){
			$sender->first_name = $message->from_name;
			$sender->save();
		}


 		if((isset($request->c) && !isset($request->sc)) || isset($request->sc) ){

	        if(isset($request->c) && !isset($request->sc)){
	            $message->category_id = $request->c;

	        }
	        else if(isset($request->sc)){
	            $message->category_id = $request->sc;
	        }
 		}
 		else{
 			$message->category_id = null;
 		}

        if(isset($request->l)){

        	$city = City::where('id',$request->l)->first();

        	if($city){

            	$message->city = $city->name;
        	}
        }


        $message->session_id = (isset($_COOKIE['__cfduid'])?$_COOKIE['__cfduid']:session()->getId());
        $message->ip_address = request()->ip();



        if($message->category_id){

	        $category = Category::find($message->category_id);
	        if($category){

	        	$message->looking_for = $category->name;

	        }
        }



        $message->save();

	 	if(!auth()->check()){
			return response()->json([
                'code' => 100,
                'message' => "login"
            ],422);
		}
		else{
			return response()->json([
	            "type"=>"quick_query",
	            "id"=>$message->id,
	            "email"=>auth()->user()->email,
	            "message" => "success"
	        ],200);

		}


	}

	public function updateQueryMessage(Request $request)
	{


		if(!auth()->check()){
			return response()->json([
                'code' => 100,
                'message' => "login"
            ],422);
		}


	/* 	$validatedData = $request->validate([
	        'data.from_email' => 'required|email',
	        'data.drugs_license' => 'required',
	    ]); */
		parse_str($request->data,$data);

		$result = filter_var( $data['from_email'], FILTER_VALIDATE_EMAIL );

		if(!$result){

			return response()->json([
	                'code' => 400,
	                'message' => "Please enter a valid email id"
	            ],422);

		}




		$message =  Message::withTrashed()->whereNull('message_id')->whereNull('quick_message_id')->where('from_user_id',auth()->user()->id)->whereNull('drugs_license')->find($data['query_id']);




		if(!$message){

			return response()->json([
                'code' => 400,
                'message' => "invalid"
            ],422);
		}


		$message->deleted_at = null;

		$user = User::find(auth()->user()->id);



		$message->from_email = $data['from_email'];
		$message->location = $data['location_for_franchise'];
		$message->address = $data['address'];


		$message->drugs_license = $data['drugs_license'];
		$message->have_gst_number = $data['have_gst_number'];
		$message->minimum_investment = $data['minimum_investment'];

		$message->purchase_period = $data['purchase_period'];
		$message->call_back_time = $data['call_back_time'];
		$message->profession = $data['profession'];

		$message->verified_status ="By OTP";

		$message->include_in_share = "1";


		$city = City::with('subAdmin1')->where('id',$data['city_id'])->first();

		if($city){

			$message->city = $city->name.(($city->subAdmin1)?", ".$city->subAdmin1->name:"");

			$message->city_id = $city->id;


			$check_filter_location = UserFilterLocation::where('user_id',$message->to_user_id)->where('city_id',$message->city_id)->count();

			if($check_filter_location>0){
				$message->include_in_share = "0";
			}
			//ALTER TABLE `messages` ADD `city_id` INT NULL DEFAULT NULL AFTER `sending_log`;

		}



		if($data['specific_query']!=""){


			$message->message.="\n\n Specific Query:\n".$data['specific_query'];

		}





		//dd($message);

        $message->save();

        if($user->email==null){

        	$check = User::where('email',$message->from_email)->count();

        	if($check=="0"){

        		$user->email =  $message->from_email;


        	}

		}


		if(!$user->location_for_franchise){
			$user->location_for_franchise = $message->location;
		}

		if(!$user->address1){
			$user->address1 = $message->address;
		}

		if(!$user->drugs_license){
			$user->drugs_license = $message->drugs_license;
		}

		if(!$user->have_gst_number){
			$user->have_gst_number = $message->have_gst_number;
		}

		if(!$user->minimum_investment){
			$user->minimum_investment = $message->minimum_investment;
		}

		if(!$user->purchase_period){
			$user->purchase_period = $message->purchase_period;
		}

		if(!$user->call_back_time){
			$user->call_back_time = $message->call_back_time;
		}

		if(!$user->profession){
			$user->profession = $message->profession;
		}

		if(!$user->city_id && $message->city_id){


			$user->city_id = $message->city_id;


		}




		if($user->isDirty()){

			$user->save();

		}









			return response()->json([
	        	"code" => 200,
	            "message" => "success"
	        ],200);

		//return redirect(config('app.locale') . '/thankyou')->with('redirect_to', back()->getTargetUrl());
		//return back();
		//return redirect(config('app.locale') . '/' . $user->username);
	}
	//

	/**
	 * Get similar Posts (Posts in the same Category)
	 *
	 * @param $cat
	 * @param int $currentPostId
	 * @return array|null|\stdClass
	 */
	private function getCategorySimilarPosts($cat, $currentPostId = 0)
	{
		$limit = 24;
		$featured = null;

		// Get the sub-categories of the current ad parent's category
		$similarCatIds = [];
		if (!empty($cat)) {

			/*if ($cat->tid == $cat->parent_id) {
				$similarCatIds[] = $cat->tid;
			} else {
				if (!empty($cat->parent_id)) {
					$similarCatIds = Category::trans()->where('parent_id', $cat->parent_id)->get()->keyBy('tid')->keys()->toArray();
					$similarCatIds[] = (int)$cat->parent_id;
				} else {
					$similarCatIds[] = (int)$cat->tid;
				}
			}*/

			$similarCatIds[] = $cat->tid;
		}
	//	dd($similarCatIds);
		// Get ads from same category
		$posts = [];
		if (!empty($similarCatIds)) {
			if (count($similarCatIds) == 1) {
				$similarPostSql = 'AND a.category_id=' . ((isset($similarCatIds[0])) ? (int)$similarCatIds[0] : 0) . ' ';
			} else {
				$similarPostSql = 'AND a.category_id IN (' . implode(',', $similarCatIds) . ') ';
			}
			$reviewedCondition = '';
			if (config('settings.single.posts_review_activation')) {
				$reviewedCondition = ' AND a.reviewed = 1';
			}
			$sql = 'SELECT a.* ,u.name as compnay_name ,u.username ' . '
				FROM ' . DBTool::table('posts') . ' as a

				LEFT JOIN users u on u.id=a.user_id
				left JOIN ' . DBTool::table('packages') . ' as p ON p.id=u.package_id
				WHERE a.country_code = :countryCode ' . $similarPostSql . '
					AND (a.verified_email=1 AND a.verified_phone=1)

					AND a.archived!=1
					AND a.deleted_at IS NULL ' . $reviewedCondition . '
					AND a.user_id != :currentPostId
				ORDER BY p.lft DESC, rand()
				LIMIT 0,' . (int)$limit;
			$bindings = [
				'countryCode'   => config('country.code'),
				'currentPostId' => $currentPostId,
			];

			$cacheId = 'posts.similar.category.' . $cat->tid . '.post.' . $currentPostId;
			$posts = Cache::remember($cacheId, $this->cacheExpiration, function () use ($sql, $bindings) {
				try {
					$posts = DB::select(DB::raw($sql), $bindings);
				} catch (\Exception $e) {
					return [];
				}

				return $posts;
			});
		}



		if (count($posts) > 0) {
			// Append the Posts 'uri' attribute
			$posts = collect($posts)->map(function ($post) {
				$post->title = mb_ucfirst($post->title);
				$post->uri = trans('routes.v-post', ['slug' => slugify($post->title), 'id' => $post->id]);

				return $post;
			})->toArray();

			// Randomize the Posts
			$posts = collect($posts)->shuffle()->toArray();
			$posts = collect($posts)->toArray();

			// Featured Area Data
			$featured = [
				'title' => t('Similar Ads'),
				'link'  => qsurl(trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge(request()->except('c'), ['c' => $cat->tid])),
				'posts' => $posts,
			];
			$featured = Arr::toObject($featured);
		}

		return $featured;
	}

	/**
	 * Get Posts in the same Location
	 *
	 * @param $city
	 * @param int $currentPostId
	 * @return array|null|\stdClass
	 */
	private function getLocationSimilarPosts($city, $currentPostId = 0)
	{
		$distance = 50; // km OR miles
		$limit = 10;
		$featured = null;

		if (!empty($city)) {
			// Get ads from same location (with radius)
			$reviewedCondition = '';
			if (config('settings.single.posts_review_activation')) {
				$reviewedCondition = ' AND a.reviewed = 1';
			}
			$sql = 'SELECT a.*, 3959 * acos(cos(radians(' . $city->latitude . ')) * cos(radians(a.lat))'
				. '* cos(radians(a.lon) - radians(' . $city->longitude . '))'
				. '+ sin(radians(' . $city->latitude . ')) * sin(radians(a.lat))) as distance
				FROM ' . DBTool::table('posts') . ' as a
				INNER JOIN ' . DBTool::table('categories') . ' as c ON c.id=a.category_id AND c.active=1
				WHERE a.country_code = :countryCode
					AND (a.verified_email=1 AND a.verified_phone=1)
					AND a.archived!=1  ' . $reviewedCondition . '
					AND a.id != :currentPostId
				HAVING distance <= ' . $distance . '
				ORDER BY distance ASC, a.id DESC
				LIMIT 0,' . (int)$limit;
			$bindings = [
				'countryCode'   => config('country.code'),
				'currentPostId' => $currentPostId,
			];

			$cacheId = 'posts.similar.city.' . $city->id . '.post.' . $currentPostId;
			$posts = Cache::remember($cacheId, $this->cacheExpiration, function () use ($sql, $bindings) {
				try {
					$posts = DB::select(DB::raw($sql), $bindings);
				} catch (\Exception $e) {
					return [];
				}

				return $posts;
			});

			if (count($posts) > 0) {
				// Append the Posts 'uri' attribute
				$posts = collect($posts)->map(function ($post) {
					$post->title = mb_ucfirst($post->title);
					$post->uri = trans('routes.v-post', ['slug' => slugify($post->title), 'id' => $post->id]);

					return $post;
				})->toArray();

				// Randomize the Posts
				$posts = collect($posts)->shuffle()->toArray();

				// Featured Area Data
				$featured = [
					'title' => t('More ads at :distance :unit around :city', [
						'distance' => $distance,
						'unit'     => unitOfLength(config('country.code')),
						'city'     => $city->name,
					]),
					'link'  => qsurl(trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge(request()->except(['l', 'location']), ['l' => $city->id])),
					'posts' => $posts,
				];
				$featured = Arr::toObject($featured);
			}
		}

		return $featured;
	}
}
