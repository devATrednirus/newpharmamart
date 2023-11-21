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

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Controllers\Post\Traits\CustomFieldTrait;
use App\Http\Requests\PostRequest;
use App\Http\Requests\GroupRequest;
use App\Http\Requests\DivisionRequest;

use App\Models\PostType;
use App\Models\Category;
use App\Models\Package;
use App\Models\PaymentMethod;
use App\Http\Controllers\FrontController;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Http\Controllers\Post\Traits\EditTrait;
use App\Http\Controllers\Post\Traits\EditGroupTrait;
use App\Models\ProductGroup;
use App\Models\Division;
use App\Http\Controllers\Post\Traits\EditDivisionTrait;
use App\Models\Post;
use App\Models\Message;
use App\Models\QuickMessage;
use App\Models\Payment;
use App\Models\SavedPost;
use App\Models\SavedSearch;
use Illuminate\Support\Facades\Storage;

class EditController extends FrontController
{
    use EditTrait, VerificationTrait, CustomFieldTrait , EditGroupTrait,EditDivisionTrait;

    public $data;
    public $msg = [];
    public $uri = [];

    /**
     * EditController constructor.
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
        // References
        $data = [];

        // Get Countries
        $data['countries'] = $this->countries = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
        $this->countries = $data['countries'];
        view()->share('countries', $data['countries']);

        // Get Categories
        $data['categories'] = Category::trans()->where('parent_id', 0)->with([
            'children' => function ($query) {
                $query->trans();
            },
        ])->orderBy('lft')->get();
        view()->share('categories', $data['categories']);

        // Get Post Types
        $data['postTypes'] = PostType::trans()->get();
        view()->share('postTypes', $data['postTypes']);
    
        // Count Packages
        $data['countPackages'] = Package::trans()->applyCurrency()->count();
        view()->share('countPackages', $data['countPackages']);
    
        // Count Payment Methods
        $data['countPaymentMethods'] = $this->countPaymentMethods;

        if (auth()->check()) {

            $data['groups'] =  ProductGroup::where('user_id', auth()->user()->id)->orderBy('name')->get();
            view()->share('groups', $data['groups']);

        }
    
        // Save common's data
        $this->data = $data;
    }

    /**
     * Show the form the create a new ad post.
     *
     * @param $postId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getForm($postId)
    {

        $this->getLeftDetails();
        return $this->getUpdateForm($postId);
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
     * Update ad post.
     *
     * @param $postId
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postForm($postId, PostRequest $request)
    {
        return $this->postUpdateForm($postId, $request);
    }

    /**
     * Show the form the create a new ad post.
     *
     * @param $groupId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getGroupForm($groupId)
    {

        $this->getLeftDetails();
        return $this->getGroupUpdateForm($groupId);
    }

    /**
     * Update ad post.
     *
     * @param $postId
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postGroupForm($groupId, GroupRequest $request)
    {
        return $this->postGroupUpdateForm($groupId, $request);
    }

    /**
     * Show the form the create a new ad post.
     *
     * @param $groupId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getGroupCreate()
    {
        $this->getLeftDetails();

        return $this->getGroupCreateForm();
    }

    /**
     * Update ad post.
     *
     * @param $postId
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postGroupCreate(GroupRequest $request)
    {
        return $this->postGroupUpdateForm(null, $request);
    }

    /**
     * Show the form the create a new ad post.
     *
     * @param $groupId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDivisionCreate()
    {
        $this->getLeftDetails();

        return $this->getDivisionCreateForm();
    }

    /**
     * Update ad post.
     *
     * @param $postId
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postDivisionCreate(DivisionRequest $request)
    {

        return $this->postDivisionUpdateForm(null, $request);
    }

        /**
     * Show the form the create a new ad post.
     *
     * @param $groupId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDivisionForm($divisionId)
    {


        $this->getLeftDetails();
      
        
        return $this->getDivisionUpdateForm($divisionId);
    }

    /**
     * Update ad post.
     *
     * @param $postId
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postDivisionForm($divisionId, DivisionRequest $request)
    {
        return $this->postDivisionUpdateForm($divisionId, $request);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deletebrochure($postId)
    {
        $post = Post::where('user_id', auth()->user()->id)->find($postId);
        
        if(!$post){
            flash("Invalid request.")->error();
            $nextUrl = config('app.locale') . '/account';

            return redirect($nextUrl);
        }

        if($post->brochure){
            $oldfileexists = Storage::exists( $post->brochure );
             
            if($oldfileexists){
                 Storage::delete( $post->brochure);

                 
            }

            $post->brochure = null;

            $post->save();
 
        }


        flash("brochure has been deleted.")->success();
        $nextUrl = config('app.locale') . '/posts/'.$post->id.'/edit';

        // Redirection
        return redirect($nextUrl);
         
    }

    
}
