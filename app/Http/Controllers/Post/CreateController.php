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

use App\Helpers\Ip;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Controllers\Post\Traits\AutoRegistrationTrait;
use App\Http\Controllers\Post\Traits\CustomFieldTrait;
use App\Http\Requests\PostRequest;
use App\Models\Permission;
use App\Models\Post;
use App\Models\PostType;
use App\Models\Category;
use App\Models\Package;
use App\Models\City;
use App\Models\Scopes\VerifiedScope;
use App\Models\User;
use App\Http\Controllers\FrontController;
use App\Models\Scopes\ReviewedScope;
use App\Notifications\PostActivated;
use App\Notifications\PostNotification;
use App\Notifications\PostReviewed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Http\Controllers\Post\Traits\EditTrait;
use App\Models\ProductGroup;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Models\Message;
use App\Models\QuickMessage;
use App\Models\Payment;
use App\Models\SavedPost;
use App\Models\SavedSearch;
class CreateController extends FrontController
{
	use EditTrait, VerificationTrait, CustomFieldTrait, AutoRegistrationTrait;

	public $data;

	/**
	 * CreateController constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		// Check if guests can post Ads
		if (config('settings.single.guests_can_post_ads') != '1') {
			$this->middleware('auth')->only(['getForm', 'postForm']);
		}

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
		// References
		$data = [];

		// Get Countries
		$data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		view()->share('countries', $data['countries']);

		// Get Categories
		$cacheId = 'categories.parentId.0.with.children' . config('app.locale');
		$data['categories'] = Cache::remember($cacheId, $this->cacheExpiration, function () {
			$categories = Category::trans()->where('parent_id', 0)->with([
				'children' => function ($query) {
					$query->trans();
				},
			])->orderBy('lft')->get();
                      	return $categories;
		});
         		view()->share('categories', $data['categories']);

		if (auth()->check()) {

			$data['groups'] =  ProductGroup::where('user_id', auth()->user()->id)->orderBy('name')->get();
			view()->share('groups', $data['groups']);

		}






		// Get Post Types
		$cacheId = 'postTypes.all.' . config('app.locale');
		$data['postTypes'] = Cache::remember($cacheId, $this->cacheExpiration, function () {
			$postTypes = PostType::trans()->orderBy('lft')->get();
			return $postTypes;
		});
		view()->share('postTypes', $data['postTypes']);

		// Count Packages
		$data['countPackages'] = Package::trans()->applyCurrency()->count();
		view()->share('countPackages', $data['countPackages']);

		// Count Payment Methods
		$data['countPaymentMethods'] = $this->countPaymentMethods;

		// Save common's data
		$this->data = $data;
	}

	/**
	 * New Post's Form.
	 *
	 * @param null $tmpToken
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getForm($tmpToken = null)
	{

		$this->getLeftDetails();
		// Check possible Update
		if (!empty($tmpToken)) {
			session()->keep(['message']);

			return $this->getUpdateForm($tmpToken);
		}

		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'create'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'create')));
		MetaTag::set('keywords', getMetaTag('keywords', 'create'));

		// Create
		return view('post.create');
	}

	private function getLeftDetails()
	{
		 // My Posts
        $this->myPosts = Post::currentCountry()
            ->where('user_id', auth()->user()->id)
            ->verified()
			->unarchived()
			->reviewed()
            ->with(['pictures','category.parent', 'city', 'latestPayment' => function ($builder) { $builder->with(['package']); }])
            ->orderByDesc('id');
        view()->share('countMyPosts', $this->myPosts->count());


        // My Groups
        $this->myGroups = ProductGroup::where('user_id', auth()->user()->id)

            ->orderByDesc('id');
        view()->share('countMyGroups', $this->myGroups->count());

        // Archived Posts
        $this->archivedPosts = Post::currentCountry()
            ->where('user_id', auth()->user()->id)
            ->archived()
            ->with(['pictures', 'city', 'latestPayment' => function ($builder) { $builder->with(['package']); }])
            ->orderByDesc('id');
        view()->share('countArchivedPosts', $this->archivedPosts->count());

        // Favourite Posts
        $this->favouritePosts = SavedPost::whereHas('post', function($query) {
                $query->currentCountry();
            })
            ->where('user_id', auth()->user()->id)
            ->with(['post.pictures', 'post.city'])
            ->orderByDesc('id');
        view()->share('countFavouritePosts', $this->favouritePosts->count());

        // Pending Approval Posts
        $this->pendingPosts = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
            ->currentCountry()
            ->where('user_id', auth()->user()->id)
            ->unverified()
            ->with(['pictures', 'city', 'latestPayment' => function ($builder) { $builder->with(['package']); }])
            ->orderByDesc('id');
        view()->share('countPendingPosts', $this->pendingPosts->count());

        // Save Search
        $savedSearch = SavedSearch::currentCountry()
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('id');
        view()->share('countSavedSearch', $savedSearch->count());

        // Conversations

            $this->conversations = Message::with('latestReply')->with('post')
            // ->whereHas('post', function($query) {
            //  $query->currentCountry();
            // })
            ->byUserId(auth()->user()->id)
            ->where('blocked','0')
            ->where('parent_id', 0)
            ->orderBy('sent_at','desc');


          //  dd($this->conversations->count());
        //dd($this->conversations->get());
            //->orderByDesc('id');
		view()->share('countConversations', $this->conversations->count());

		 view()->share('pagePath', '');
	}

	/**
	 * Store a new Post.
	 *
	 * @param null $tmpToken
	 * @param PostRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function postForm($tmpToken = null, PostRequest $request)
	{

		//dd($request->all());
		// Check possible Update
		if (!empty($tmpToken)) {
			session()->keep(['message']);

			return $this->postUpdateForm($tmpToken, $request);
		}

		$user = User::withoutGlobalScopes([VerifiedScope::class])->find(auth()->user()->id);



		// Get the Post's City
		$city = City::find($user->city_id);
		if (empty($city)) {
			flash('Please update your address to create listing')->error();

			return back()->withInput();
		}

		// Conditions to Verify User's Email or Phone
		if (auth()->check()) {
			$emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email') && $request->input('email') != auth()->user()->email;
			$phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone') && $request->input('phone') != auth()->user()->phone;
		} else {
			$emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email');
			$phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone');
		}

		// New Post
		$post = new Post();
		$input = $request->only($post->getFillable());
		foreach ($input as $key => $value) {
			$post->{$key} = $value;
		}

		//dd($post);
		$post->country_code = config('country.code');
		$post->user_id = (auth()->check()) ? auth()->user()->id : 0;
		$post->negotiable = $request->input('negotiable');
		$post->phone_hidden = $request->input('phone_hidden');
                $post->video_link = $request->input('video');
		$post->city_id = $city->id;
		$post->lat = $city->latitude;
		$post->lon = $city->longitude;
		$post->ip_addr = Ip::get();
		$post->tmp_token = md5(microtime() . mt_rand(100000, 999999));
		$post->verified_email = 1;
		$post->verified_phone = 1;
		$post->reviewed = 0;
        $post->short_description=$_POST['short_description'];
		//exit;
		$post->email = $user->email;
		$post->phone = $user->phone;
		$post->contact_name = $user->first_name.($user->last_name?" ".$user->last_name:"");

		$post->address = $user->address1.($user->address2?" ".$user->address2:"");

		// Email verification key generation
		if ($emailVerificationRequired) {
			$post->email_token = md5(microtime() . mt_rand());
			$post->verified_email = 0;
		}

		// Mobile activation key generation
		if ($phoneVerificationRequired) {
			$post->phone_token = mt_rand(100000, 999999);
			$post->verified_phone = 0;
		}


		$file = $request->file('filename');


        if ($file && $file->isValid()) {


            if($post->brochure){
                $oldfileexists = Storage::exists( $post->brochure );

                if($oldfileexists){
                     Storage::delete( $post->brochure);
                }

            }

            $destinationPath = 'files/brochure/' . $user->username;

            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $newFilename = slugify($post->title). '.' . $extension;
            $filePath = $destinationPath . '/' . $newFilename;

            Storage::put($filePath, File::get($file->getrealpath()));
            $post->brochure =  $filePath ;

        }

		// Save
		$post->save();

		// Save ad Id in session (for next steps)
		session(['tmpPostId' => $post->id]);

		// Custom Fields
		$this->createPostFieldsValues($post, $request);

		// Auto-Register the Author
		$user = $this->register($post);

		// The Post's creation message
		if (getSegment(2) == 'create') {
			session()->flash('message', t('Your ad has been created.'));
		}

		// Get Next URL
		//$nextStepUrl = config('app.locale') . '/posts/create/' . $post->tmp_token . '/photos';
		$nextStepUrl = config('app.locale') . '/posts/' . $post->id . '/photos';

		// Send Admin Notification Email
		if (config('settings.mail.admin_notification') == 1) {
			try {
				// Get all admin users
				$admins = User::permission(Permission::getStaffPermissions())->get();
				if ($admins->count() > 0) {
					Notification::send($admins, new PostNotification($post));
					/*
					foreach ($admins as $admin) {
						Notification::route('mail', $admin->email)->notify(new PostNotification($post));
					}
					*/
				}
			} catch (\Exception $e) {
				flash($e->getMessage())->error();
			}
		}

		// Send Verification Link or Code
		if ($emailVerificationRequired || $phoneVerificationRequired) {

			// Save the Next URL before verification
			session(['itemNextUrl' => $nextStepUrl]);

			// Email
			if ($emailVerificationRequired) {
				// Send Verification Link by Email
				$this->sendVerificationEmail($post);

				// Show the Re-send link
				$this->showReSendVerificationEmailLink($post, 'post');
			}

			// Phone
			if ($phoneVerificationRequired) {
				// Send Verification Code by SMS
				$this->sendVerificationSms($post);

				// Show the Re-send link
				$this->showReSendVerificationSmsLink($post, 'post');

				// Go to Phone Number verification
				$nextStepUrl = config('app.locale') . '/verify/post/phone/';
			}

			// Send Confirmation Email or SMS,
			// When User clicks on the Verification Link or enters the Verification Code.
			// Done in the "app/Observers/PostObserver.php" file.

		} else {

			// Send Confirmation Email or SMS
			if (config('settings.mail.confirmation') == 1) {
				try {
					if (config('settings.single.posts_review_activation') == 1) {
						$post->notify(new PostActivated($post));
					} else {
						$post->notify(new PostReviewed($post));
					}
				} catch (\Exception $e) {
					flash($e->getMessage())->error();
				}
			}

		}

		$nextStepUrl = str_replace('en/', '',$nextStepUrl);

	//dd($nextStepUrl);


		if(str_contains($nextStepUrl, 'post')) {
				return redirect($nextStepUrl);
		} else {
				return redirect('account/my-posts');
		}



		// Redirection
		//
	}

	/**
	 * Confirmation
	 *
	 * @param $tmpToken
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function finish($tmpToken)
	{
		// Keep Success Message for the page refreshing
		session()->keep(['message']);
		if (!session()->has('message')) {
			//return redirect(config('app.locale') . '/');
		}

		// Clear the steps wizard
		if (session()->has('tmpPostId')) {
			// Get the Post
			$post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', session('tmpPostId'))->where('tmp_token', $tmpToken)->first();
			if (empty($post)) {
				//abort(404);
			}

			// Apply finish actions
			$post->tmp_token = null;
			$post->save();
			session()->forget('tmpPostId');
		}

		// Redirect to the Post,
		// - If User is logged
		// - Or if Email and Phone verification option is not activated
		if (auth()->check() || (config('settings.mail.email_verification') != 1 && config('settings.sms.phone_verification') != 1)) {
			if (!empty($post)) {
				flash(session('message'))->success();

				//return redirect(config('app.locale') . '/' . $post->uri . '?preview=1');
			}
		}

		// Meta Tags
		MetaTag::set('title', session('message'));
		MetaTag::set('description', session('message'));

		return view('post.finish');
	}
}
