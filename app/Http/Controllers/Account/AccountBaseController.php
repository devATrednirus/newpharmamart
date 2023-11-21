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

namespace App\Http\Controllers\Account;

use App\Http\Controllers\FrontController;
use App\Models\Post;
use App\Models\User;
use App\Models\ProductGroup;
use App\Models\Division;
use App\Models\Message;
use App\Models\QuickMessage;
use App\Models\Payment;
use App\Models\SavedPost;
use App\Models\Banner;
use App\Models\SavedSearch;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use DB;
abstract class AccountBaseController extends FrontController
{
    public $countries;
    public $myPosts;
    public $archivedPosts;
    public $favouritePosts;
    public $pendingPosts;
    public $conversations;
    public $buyleads;
    public $transactions;
    public $banners;

    /**
     * AccountBaseController constructor.
     */
    public function __construct()
    {
        parent::__construct();
		
        $this->middleware(function ($request, $next) {
            $this->leftMenuInfo();
            return $next($request);
        });
	
		view()->share('pagePath', '');
    }

    public function leftMenuInfo()
    {
    	// Get & Share Countries
        $this->countries = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
        view()->share('countries', $this->countries);
        

        $user = User::with('locationFilter.subAdmin1')->withoutGlobalScopes([VerifiedScope::class])->find(auth()->user()->id);
        // Share User Info

        $filterCity=[];
        if($user->locationFilter){

            
            foreach ($user->locationFilter as $city) {
                
                $filterCity[]=$city->name." (".$city->subAdmin1->name.')';
            }
        }
        
        view()->share('filterCity', $filterCity); 
        view()->share('user', $user);

        // My Posts
        $this->myPosts = Post::currentCountry()
            ->where('user_id', auth()->user()->id)
            ->verified()
			->unarchived()
			->reviewed()
            ->with(['pictures','category.parent', 'city', 'latestPayment' => function ($builder) { $builder->with(['package']); }])
            ->orderByDesc('id');
        view()->share('countMyPosts', $this->myPosts->count());

        // Division
        $this->divisions = Division::where('user_id', auth()->user()->id)
            
            ->orderByDesc('id');
        view()->share('countDivisions', $this->divisions->count());

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
        

 
        $this->buyleads = Message::where('blocked','0')
            ->where('parent_id', '0')
            // ->where('type','quick')
            ->where('is_sent', '1')
            ->where('shareable_count','>', '0')
            ->whereNull('message_id')
            ->whereDoesntHave("buy", function($subQuery){
                $subQuery->where("to_user_id", "=", auth()->user()->id);
            })
            ->orderBy('sent_at','desc');
         

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
		

        $this->buy_leads = Payment::where('user_id', auth()->user()->id)->where('payment_type','Buy-Leads')->where('active','1')->whereHas('package', function($query){
            $query->where('duration','0');
        })->sum('remaining');
        view()->share('buy_leads', $this->buy_leads);
        

        $this->old_buy_leads = Payment::where('user_id', auth()->user()->id)->where('payment_type','Buy-Leads')->with('package')->where('active','1')->where('remaining','>','0')->whereHas('package', function($query){
            $query->where('duration','<>','0');
        })->first();

        // dd($this->old_buy_leads);
        view()->share('old_buy_leads', $this->old_buy_leads);

		// Payments
		$this->transactions = Payment::where('user_id', auth()->user()->id)->whereIn('active',['1','3'])->with(['paymentMethod', 'package' => function ($builder) { $builder->with(['currency']); }])
			->orderByDesc('id');
		view()->share('countTransactions', $this->transactions->count());


        $this->banners = Banner::with('category')->where('user_id', auth()->user()->id)
            ->orderByDesc('id');
            
        view()->share('countBanners', $this->banners->count());
    }
}
