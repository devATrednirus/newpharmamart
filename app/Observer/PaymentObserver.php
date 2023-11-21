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

namespace App\Observer;

use App\Models\Payment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PaymentApproved;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PackageExpired;
use App\Notifications\PackageActivated;
use Carbon\Carbon;

class PaymentObserver
{
	/**
	 * Listen to the Entry updating event.
	 *
	 * @param  Payment $payment
	 * @return void
	 */
	public function saved(Payment $payment)
	{

		if($payment->skip_notification==true){
			return;
		}
		// Get the original object values
		$original = $payment->getOriginal();
	 
		//if (config('settings.mail.confirmation') == 1) {
			// The Payment was not approved
			
			if (!isset($original['active']) || $original['active'] != "1") {

				if ($payment->active == "1") {
					$user = User::find($payment->user_id);
					 
					if (!empty($user)) {
						try {


							if($payment->payment_type=="Subscription"){
								

								if($payment->start_date==null){

									$payment->start_date = Carbon::now();
									$payment->end_date = Carbon::now()->addDay(364);
									$payment->skip_notification=true;
									$payment->save();
								}

								$user->package_id = $payment->package_id;
								$user->package_start_date = $payment->start_date->format('Y-m-d');
								$user->package_end_date = $payment->end_date->format('Y-m-d');
								$user->skip_package_update=true;
								//dd($user);
								$user->save();
							}
							
							$user->notify(new PackageActivated($user,$payment));
						

						} catch (\Exception $e) {

							
				 			//dd($e->getMessage());
						}
					}

				 
				}
			}
			else if (isset($original['active']) && $original['active'] == "1") {
				if ($payment->active == "3") {
					$user = User::find($payment->user_id);
					if (!empty($user)) {
						try {
							if($payment->payment_type=="Subscription"){
								$user->notify(new PackageExpired($user,$payment));
							}
						} catch (\Exception $e) {

							//dd($e->getMessage());
							//flash($e->getMessage())->error();
						}
					}
				}
			}
		
		//}

	 
	}
	
 
	
	/**
	 * Listen to the Entry deleted event.
	 *
	 * @param  Payment $payment
	 * @return void
	 */
	public function deleted(Payment $payment)
	{
		// Removing Entries from the Cache
		//$this->clearCache($payment);
	}
	
	/**
	 * Removing the Entity's Entries from the Cache
	 *
	 * @param $payment
	 */
	private function clearCache($payment)
	{

		return;
		if (!isset($payment->post) || empty($payment->post)) {
			return;
		}
		
		$post = $payment->post;
		
		Cache::forget($post->country_code . '.sitemaps.posts.xml');
		
		Cache::forget($post->country_code . '.home.getPosts.sponsored');
		Cache::forget($post->country_code . '.home.getPosts.latest');
		
		Cache::forget('post.withoutGlobalScopes.with.city.pictures.' . $post->id);
		Cache::forget('post.with.city.pictures.' . $post->id);
		
		Cache::forget('post.withoutGlobalScopes.with.city.pictures.' . $post->id . '.' . config('app.locale'));
		Cache::forget('post.with.city.pictures.' . $post->id . '.' . config('app.locale'));
		
		Cache::forget('posts.similar.category.' . $post->category_id . '.post.' . $post->id);
		Cache::forget('posts.similar.city.' . $post->city_id . '.post.' . $post->id);
	}
}
