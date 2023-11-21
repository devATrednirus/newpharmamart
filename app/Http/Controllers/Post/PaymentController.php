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

use App\Http\Requests\PackageRequest;
use App\Models\Post;
use App\Models\User;
use App\Models\Package;
use App\Models\Scopes\StrictActiveScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use App\Http\Controllers\FrontController;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Payment as PaymentHelper;
use App\Http\Controllers\Post\Traits\PaymentTrait;

class PaymentController extends FrontController
{
	use PaymentTrait;
	
	public $request;
	public $data;
	public $msg = [];
	public $uri = [];
	public $packages;
	public $paymentMethods;
	
	/**
	 * PackageController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		
		// From Laravel 5.3.4 or above
		$this->middleware(function ($request, $next) {
			$this->request = $request;
			$this->commonQueries();
			
			return $next($request);
		});
	}
	
	/**
	 * Common Queries
	 */
	public function commonQueries()
	{
		

		$this->msg['checkout']['success'] = t("We have received your payment.");
		$this->msg['checkout']['cancel'] = t("We have not received your payment. Payment cancelled.");
		$this->msg['checkout']['error'] = t("We have not received your payment. An error occurred.");
		
		// Set URLs
		if (getSegment(1) == 'user') {
			$this->uri['previousUrl'] =  '/user/payment';
			$this->uri['nextUrl'] =  '/account/transactions';
			$this->uri['paymentCancelUrl'] = url( '/user/payment/cancel');
			$this->uri['paymentReturnUrl'] = url( '/user/payment/success');
		} else {

			$this->uri['previousUrl'] =  '/buylead/payment';
			$this->uri['nextUrl'] =  '/buylead/transactions';
			$this->uri['paymentCancelUrl'] = url( '/buylead/payment/cancel');
			$this->uri['paymentReturnUrl'] = url( '/buylead/payment/success');
		}
		
		// Payment Helper init.
		PaymentHelper::$country = collect(config('country'));
		PaymentHelper::$lang = collect(config('lang'));
		PaymentHelper::$msg = $this->msg;
		PaymentHelper::$uri = $this->uri;
		
		// Get Packages
		$this->packages = Package::where('pack_type','Subscription')->where('is_public','1')->trans()->applyCurrency()->with('currency')->orderBy('lft')->get();
		view()->share('packages', $this->packages);
		view()->share('countPackages', $this->packages->count());
		
		$this->buy_lead_packages = Package::where('pack_type','Buy-Leads')->trans()->applyCurrency()->with('currency')->orderBy('lft')->get();


		view()->share('buy_lead_packages', $this->buy_lead_packages);

		// Keep the Post's creation message
		// session()->keep(['message']);
		if (getSegment(2) == 'create') {
			if (session()->has('tmpPostId')) {
				session()->flash('message', t('Your ad has been created.'));
			}
		}
	}
	
	/**
	 * Show the form the create a new ad post.
	 *
	 * @param $postIdOrToken
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getForm($postIdOrToken)
	{
		$data = [];
		
		// Get Post
		if (getSegment(2) == 'create') {
			if (!session()->has('tmpPostId')) {
				return redirect('posts/create');
			}
			$post = Post::with(['latestPayment' =>  function ($builder) {
				$builder->with(['package'])->withoutGlobalScope(StrictActiveScope::class);
			}])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('id', session('tmpPostId'))
				->where('tmp_token', $postIdOrToken)
				->first();
		} else {
			$post = Post::with(['latestPayment' =>  function ($builder) {
				$builder->with(['package'])->withoutGlobalScope(StrictActiveScope::class);
			}])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('user_id', auth()->user()->id)
				->where('id', $postIdOrToken)
				->first();
		}
		
		if (empty($post)) {
			abort(404);
		}
		
		view()->share('post', $post);
		
		// Meta Tags
		if (getSegment(2) == 'create') {
			MetaTag::set('title', getMetaTag('title', 'create'));
			MetaTag::set('description', strip_tags(getMetaTag('description', 'create')));
			MetaTag::set('keywords', getMetaTag('keywords', 'create'));
		} else {
			MetaTag::set('title', t('Update My Ad'));
			MetaTag::set('description', t('Update My Ad'));
		}
		
		return view('post.packages', $data);
	}
	
	/**
	 * Store a new ad post.
	 *
	 * @param $postIdOrToken
	 * @param PackageRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	/*public function postForm($postIdOrToken, PackageRequest $request)
	{
		// Get Post
		if (getSegment(2) == 'create') {
			if (!session()->has('tmpPostId')) {
				return redirect('posts/create');
			}
			$post = Post::with(['latestPayment'])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('id', session('tmpPostId'))
				->where('tmp_token', $postIdOrToken)
				->first();
		} else {
			$post = Post::with(['latestPayment'])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('user_id', auth()->user()->id)
				->where('id', $postIdOrToken)
				->first();
		}
		
		if (empty($post)) {
			abort(404);
		}
		
		// MAKE A PAYMENT (IF NEEDED)
		
		// Check if the selected Package has been already paid for this Post
		$alreadyPaidPackage = false;
		if (!empty($post->latestPayment)) {
			if ($post->latestPayment->package_id == $request->input('package_id')) {
				$alreadyPaidPackage = true;
			}
		}
		
		// Check if Payment is required
		$package = Package::find($request->input('package_id'));
		if (!empty($package)) {
			if ($package->price > 0 && $request->filled('payment_method_id') && !$alreadyPaidPackage) {
				// Send the Payment
				return $this->sendPayment($request, $post);
			}
		}
		
		// IF NO PAYMENT IS MADE (CONTINUE)
		
		// Get the next URL
		if (getSegment(2) == 'create') {
			$request->session()->flash('message', t('Your ad has been created.'));
			$nextStepUrl = config('app.locale') . '/posts/create/' . $postIdOrToken . '/finish';
		} else {
			flash(t("Your ad has been updated."))->success();
			$nextStepUrl = config('app.locale') . '/' . $post->uri . '?preview=1';
		}
		
		// Redirect
		return redirect($nextStepUrl);
	}
*/

	/**
	 * Show the form the create a new ad post.
	 *
	 * @param $postIdOrToken
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getUserForm()
	{
		$data = [];
		
		 if(!auth()->user()){

			 return redirect("/");
		}

		$user  =  User::with('package')->find(auth()->user()->id);

		view()->share('user', $user);
		
		// Meta Tags
		if (getSegment(2) == 'create') {
			MetaTag::set('title', getMetaTag('title', 'create'));
			MetaTag::set('description', strip_tags(getMetaTag('description', 'create')));
			MetaTag::set('keywords', getMetaTag('keywords', 'create'));
		} else {
			MetaTag::set('title', 'Upgrade Package');
			MetaTag::set('description', 'Upgrade Package');
		}
		
		return view('account.packages', $data);
	}
	
	/**
	 * Store a new ad post.
	 *
	 * @param $postIdOrToken
	 * @param PackageRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function postUserForm(PackageRequest $request)
	{
		
		if(!auth()->user()){

			 return redirect("/");
		}

		$user  =  User::with('package')->find(auth()->user()->id);

		// MAKE A PAYMENT (IF NEEDED)
		
		// Check if the selected Package has been already paid for this Post
		$alreadyPaidPackage = false;
		if (!empty($user->package)) {

			if ($user->package->id == $request->input('package_id')) {
				$alreadyPaidPackage = true;
			}
		}
		
		// Check if Payment is required
		$package = Package::find($request->input('package_id'));
		 
		if (!empty($package)) {
			if ($package->price > 0 && $request->filled('payment_method_id') && !$alreadyPaidPackage) {
				// Send the Payment


				return $this->sendUserPayment($request,$user);
			}
		}
		
		// IF NO PAYMENT IS MADE (CONTINUE)
		
		// Get the next URL
		if (getSegment(2) == 'create') {
			$request->session()->flash('message', t('Your ad has been created.'));
			$nextStepUrl = config('app.locale') . '/posts/create/' . $postIdOrToken . '/finish';
		} else {
			flash(t("Your ad has been updated."))->success();
			$nextStepUrl = config('app.locale') . '/' . $post->uri . '?preview=1';
		}
		
		// Redirect
		return redirect($nextStepUrl);
	}


	/**
	 * Show the form the create a new ad post.
	 *
	 * @param $postIdOrToken
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getBuyLeadsForm()
	{
		$data = [];
		
		 if(!auth()->user()){

			 return redirect("/");
		}

		$user  =  User::with('package')->find(auth()->user()->id);

		view()->share('user', $user);
		
		// Meta Tags
		if (getSegment(2) == 'create') {
			MetaTag::set('title', getMetaTag('title', 'create'));
			MetaTag::set('description', strip_tags(getMetaTag('description', 'create')));
			MetaTag::set('keywords', getMetaTag('keywords', 'create'));
		} else {
			MetaTag::set('title', 'Upgrade Package');
			MetaTag::set('description', 'Upgrade Package');
		}
		
		return view('account.buy_leads_packages', $data);
	}
	
	/**
	 * Store a new ad post.
	 *
	 * @param $postIdOrToken
	 * @param PackageRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function postBuyLeadsForm(PackageRequest $request)
	{
	  	 	
	if(!auth()->user()){

			 return redirect("/");
		}

		$user  =  User::with('package')->find(auth()->user()->id);
                

		// Check if Payment is required
		$package = Package::find($request->input('package_id'));
	         

		if (!empty($package)) {
			if ($package->price > 0 && $request->filled('payment_method_id')) {
				// Send the Payment
                             

				return $this->sendUserPayment($request,$user,'Buy-Leads');
			}
		}
		
		// IF NO PAYMENT IS MADE (CONTINUE)
		
		// Get the next URL
		if (getSegment(2) == 'create') {
			$request->session()->flash('message', t('Your ad has been created.'));
			$nextStepUrl = config('app.locale') . '/posts/create/' . $postIdOrToken . '/finish';
		} else {
			flash(t("Your ad has been updated."))->success();
			$nextStepUrl = config('app.locale') . '/' . $post->uri . '?preview=1';
		}
		
		// Redirect
		return redirect($nextStepUrl);
	}
}
