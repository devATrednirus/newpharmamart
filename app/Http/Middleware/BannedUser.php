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

namespace App\Http\Middleware;

use App\Models\Blacklist;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Cache;
use Prologue\Alerts\Facades\Alert;

class BannedUser
{
	protected $message = 'This user has been banned.';

	/**
	 * @param \Illuminate\Http\Request $request
	 * @param Closure $next
	 * @param null $guard
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function handle($request, Closure $next, $guard = null)
	{


		$this->message = t($this->message);

		if (auth()->check()) {
			// Block the access if User is blocked (as registered User)
			$this->invalidateBlockedUser($request, $guard);

			// Block & Delete the access if User is banned (from Blacklist with its email address)
			$this->invalidateBannedUser($request);


			if (auth()->guard($guard)->user()->user_type_id=="2") {


				if(in_array($request->route()->uri(), ['account/{pagePath}','account/saved-search','posts/create/{tmpToken?}'])){


						if ($request->ajax() || $request->wantsJson()) {
							return response($this->message, 401);
						} else {

							flash()->error('You are not allowed');

							return redirect('account');

						}
				}

			}
			$user = auth()->user();
 			//dd($user->messages()->count());
			if($user->user_type_id=="2"){

				$name =  $user->first_name.($user->last_name?' '.$user->last_name:'');
			}
			else{
				$name =  $user->name;
			}

			/*$phone = str_replace("+91", "", $user->phone);
			if(request()->get('action')!="download"){
				return $next($request)->withCookie(cookie()->forever('user',json_encode(['name'=>$name,'id'=>$user->id,'user_type_id'=>$user->user_type_id,'phone'=>$phone])));
			}*/



		}


		//dd($request);

		return $next($request);

	}


	/**
	 * Block the access if User is blocked (as registered User)
	 *
	 * @param $request
	 * @param null $guard
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
	 */
	private function invalidateBlockedUser($request, $guard = null)
	{
		if (auth()->guard($guard)->user()->blocked) {
			if ($request->ajax() || $request->wantsJson()) {
				return response($this->message, 401);
			} else {
				if (isFromAdminPanel()) {
					Alert::error($this->message)->flash();

					return redirect()->guest(admin_uri('login'));
				} else {
					flash()->error($this->message);

					return redirect()->guest(trans('routes.login'));
				}
			}
		}
	}

	/**
	 * Block & Delete the access if User is banned (from Blacklist with its email address)
	 *
	 * @param $request
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
	 */
	private function invalidateBannedUser($request)
	{
		$cacheExpiration = (int)config('settings.other.cache_expiration', 1440);

		// Check if the user's email address has been banned
		$cacheId = 'blacklist.email.' . auth()->user()->email;
		$bannedUser = Cache::remember($cacheId, $cacheExpiration, function () {
			$bannedUser = Blacklist::ofType('email')->where('entry', auth()->user()->email)->first();

			return $bannedUser;
		});

		if (!empty($bannedUser)) {
			$user = User::find(auth()->user()->id);
			$user->delete();

			if ($request->ajax() || $request->wantsJson()) {
				return response($this->message, 401);
			} else {
				if (isFromAdminPanel()) {
					Alert::error($this->message)->flash();

					return redirect()->guest(admin_uri('login'));
				} else {
					flash()->error($this->message);

					return redirect()->guest(trans('routes.login'));
				}
			}
		}
	}
}
