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
use App\Http\Requests\ContactRequest;
use App\Models\City;
use App\Models\Page;
use App\Models\Permission;
use App\Models\User;
use App\Notifications\FormSent;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Torann\LaravelMetaTags\Facades\MetaTag;
use Session;
use App\Models\Enquiry;


class PageController extends FrontController
{
	/**
	 * @param $slug
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index($slug)
	{
		// Get the Page
		$page = Page::where('slug', $slug)->trans()->first();
		if (empty($page)) {
			abort(404);
		}
		view()->share('page', $page);
		view()->share('uriPathPageSlug', $slug);
		
		// Check if an external link is available
		if (!empty($page->external_link)) {
			return headerLocation($page->external_link);
		}
		
		// SEO
		$title = $page->title;
		$description = str_limit(str_strip($page->content), 200);
		
		// Meta Tags
		MetaTag::set('title', $title);
		MetaTag::set('description', $description);
		
		// Open Graph
		$this->og->title($title)->description($description);
		if (!empty($page->picture)) {
			if ($this->og->has('image')) {
				$this->og->forget('image')->forget('image:width')->forget('image:height');
			}
			$this->og->image(Storage::url($page->picture), [
				'width'  => 600,
				'height' => 600,
			]);
		}
		view()->share('og', $this->og);
		
		return view('pages.index');
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function contact()
	{
		// Get the Country's largest city for Google Maps
		$city = City::currentCountry()->orderBy('population', 'desc')->first();
		view()->share('city', $city);
		
		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'contact'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
		MetaTag::set('keywords', getMetaTag('keywords', 'contact'));
		
		return view('pages.contact');
	}
	
	/**
	 * @param ContactRequest $request
	 * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function contactPost(ContactRequest $request)
	{
		// Store Contact Info
		$contactForm = $request->all();
		$contactForm['country_code'] = config('country.code');
		$contactForm['country_name'] = config('country.name');
		$contactForm = Arr::toObject($contactForm);
		
		// Send Contact Email
		try {

			$enquiry = new Enquiry();
			$enquiry->first_name =$contactForm->first_name;
			$enquiry->last_name =$contactForm->last_name;
			$enquiry->company_name =$contactForm->company_name;
			$enquiry->email =$contactForm->email;
			$enquiry->phone =$contactForm->phone;
			$enquiry->message =$contactForm->message;

			if(auth()->user()){
				
				$enquiry->user_id = auth()->user()->id;
 

			}

			$enquiry->ip_address = request()->ip();

			$enquiry->save();

			
			if (config('settings.app.email')) {
				Notification::route('mail', config('settings.app.email'))->notify(new FormSent($contactForm));
			} else {

				$admins = User::permission(Permission::getStaffPermissions())->get();
				if ($admins->count() > 0) {
					Notification::send($admins, new FormSent($contactForm));
					/*
                    foreach ($admins as $admin) {
						Notification::route('mail', $admin->email)->notify(new FormSent($contactForm));
                    }
					*/
				}
			}
			flash(t("Your message has been sent to our moderators. Thank you"))->success();
		} catch (\Exception $e) {
			flash($e->getMessage())->error();
		}
		
		return redirect(config('app.locale') . '/' . trans('routes.contact'));
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function thankyou()
	{
		// Get the Country's largest city for Google Maps
		 

		if(!Session::has('flash_notification')){

		 	return redirect(config('app.locale') . '/search');
		}


		// Meta Tags
		MetaTag::set('title', 'Thank You  - Rednirus Mart');
		MetaTag::set('description', 'Contact Us - Rednirus Mart');
	
		return view('pages.thanksyou');
	}
}