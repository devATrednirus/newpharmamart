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

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\UserRequest;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\StatutoryRequest;
use App\Http\Requests\BankRequest;
use App\Http\Requests\ApiRequest;
use App\Models\Scopes\VerifiedScope;
use App\Models\UserType;
use App\Models\BusinessType;
use App\Models\OwnershipType;
use App\Models\LocationHistory;
use App\Models\Category;
use Creativeorange\Gravatar\Facades\Gravatar;
use App\Models\Post;
use App\Models\SavedPost;
use App\Models\Gender;
use App\Models\City;
use App\Models\SubAdmin1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Models\User;
use App\Models\BankDetail;
use Illuminate\Support\Str;

class EditController extends AccountBaseController
{
	use VerificationTrait;
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$data = [];


		if(request()->ajax() && request()->term){

			$cities = City::select(DB::raw('name as value, name as label, subadmin1_code'))->with('subAdmin1')->where('country_code','IN')->where('name','like',request()->term.'%')->get();
			 
			return response()->json($cities,200);

		}
		else if(request()->ajax() && request()->cats){

			$categories = Category::select(DB::raw('id as value, name as label'))->where('parent_id','<>','0')->where('name','like','%'.request()->term.'%')->get();
			 
			return response()->json($categories,200);

		}

		
		$data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		$data['genders'] = Gender::trans()->get();
		$data['business_types'] = BusinessType::orderBy('name')->get();
		$data['bank_account_types'] = ['Saving Account','Current Account'];
		$data['cities'] = City::select(DB::raw('id,name as value, name as label, subadmin1_code'))->where('country_code','IN')->where('name','like',request()->term.'%')->get();

		$data['ownership_types'] = OwnershipType::get();
		$data['turn_overs'] = User::getTurnOvers();
		$data['no_of_employees'] = User::getNoOfEmployees();
		 
		$data['gravatar'] = (!empty(auth()->user()->email)) ? Gravatar::fallback(url('images/user.jpg'))->get(auth()->user()->email) : null;
		$data['userPhoto'] = $data['gravatar'];
		if (!empty(auth()->user()->photo)) {
			$data['userPhoto'] = resize(auth()->user()->photo);
		}
		
		// Mini Stats
		$data['countPostsVisits'] = DB::table('posts')
			->select('user_id', DB::raw('SUM(visits) as total_visits'))
			->where('country_code', config('country.code'))
			->where('user_id', auth()->user()->id)
			->groupBy('user_id')
			->first();
		$data['countPosts'] = Post::currentCountry()
			->where('user_id', auth()->user()->id)
			->count();
		$data['countFavoritePosts'] = SavedPost::whereHas('post', function ($query) {
			$query->currentCountry();
		})->where('user_id', auth()->user()->id)
			->count();
		
		// Meta Tags
		MetaTag::set('title', t('My account'));
		MetaTag::set('description', t('My account on :app_name', ['app_name' => config('settings.app.app_name')]));
		
		return view('account.edit', $data);
	}

	public function getBankByIFSC(Request $request)
	{

		 
		$bank= BankDetail::where('bank_ifsc',$request->ifsc)->first();
		
		return response()->json($bank);
		 
	}

	 
	/**
	 * @param UserRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function updateLocationFilter(Request $request)
	{


		//DB::enableQueryLog();

		$user = User::with('locationFilter')->withoutGlobalScopes([VerifiedScope::class])->find(auth()->user()->id);
		//dd(auth()->user()->id);
		//dump(DB::getQueryLog()); 
		//dd($user->locationFilter);

	 
		$input = explode(",",$request->excluded_location);
		$cities = [];


		

		$new = [];

	 
		foreach ($input as $key => $value) {
			
			preg_match('/(.*) \((.*)\)/', $value,$matches);

			if(count($matches)==3){

				$state = SubAdmin1::where('name',$matches[2])->first();

				if($state){

					 
					$city = City::where('name',$matches[1])->where('subadmin1_code',$state->id)->first();

					if($city){
						$cities[]= $city->id;
						$new[]= $city->name;
					}

				}
				 

			}
		 

			
		}

		
		$user->locationFilter()->sync($cities); 
	  	
	  	$history = new LocationHistory;
	  	if(app('impersonate')->isImpersonating()){

	  		$history->updated_by = app('impersonate')->getImpersonatorId();
	  	}
	  	else{
	  		$history->updated_by = auth()->user()->id;
	  	}
		$history->user_id = auth()->user()->id;
		$history->old_locations = $user->locationFilter->pluck('name')->toArray();
		$history->new_locations =$new;

		$history->save();

		// Message Notification & Redirection
		flash("Updated successfully.")->success();
		$nextUrl = config('app.locale') . '/account#location-preference';

		// Redirection
		return redirect($nextUrl);

	}

	/**
	 * @param UserRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function updateApiDetails(ApiRequest $request)
	{

		$user = User::withoutGlobalScopes([VerifiedScope::class])->find(auth()->user()->id);

		 
		$input = $request->only(['email_to_send','sms_to_send','buy_leads_alerts']);
		foreach ($input as $key => $value) {
			 
			$user->{$key} = $value;
		}


		//if(!app('impersonate')->isImpersonating()  || (app('impersonate')->isImpersonating() && app('impersonate')->getImpersonatorId()=="1" )){
			
			if($request->regenerate_api=="1"){
				$user->api_key = slugify(Hash::make(Str::random(60)));
			}
		//}
		 
	 
		// Save
		$user->save();

		// Message Notification & Redirection
		flash("Updated successfully.")->success();
		$nextUrl = config('app.locale') . '/account#api-details';

		// Redirection
		return redirect($nextUrl);

	}
	/**
	 * @param UserRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function updateBankDetails(BankRequest $request)
	{

		$user = User::withoutGlobalScopes([VerifiedScope::class])->find(auth()->user()->id);

		 
		$input = $request->only(['ifsc_code','bank_name','account_no','account_type']);
		foreach ($input as $key => $value) {
			 
			$user->{$key} = $value;
		}

		$bank= BankDetail::where('bank_ifsc',$user->ifsc_code)->first();

		if($bank){
			$user->bank_name = $bank->bank_name;
		}

	 
		// Save
		$user->save();

		// Message Notification & Redirection
		flash("Your bank details has updated successfully.")->success();
		$nextUrl = config('app.locale') . '/account#bank-details';

		// Redirection
		return redirect($nextUrl);

	}
	/**
	 * @param UserRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function updateStatutoryDetails(StatutoryRequest $request)
	{

		$user = User::withoutGlobalScopes([VerifiedScope::class])->find(auth()->user()->id);

		 
		$input = $request->only(['gstin','pan_no','tan_no','cin_no','dgft_no']);
		foreach ($input as $key => $value) {
			 
			$user->{$key} = strtoupper($value);
		}

		// Save
		$user->save();

		// Message Notification & Redirection
		flash("Your statutory details has updated successfully.")->success();
		$nextUrl = config('app.locale') . '/account#statutory-details';

		// Redirection
		return redirect($nextUrl);

	}
	/**
	 * @param UserRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function updateCompanyDetails(CompanyRequest $request)
	{


		

	 	

               
		$user = User::withoutGlobalScopes([VerifiedScope::class])->find(auth()->user()->id);


		

		//$usernameChanged = $request->filled('username') && $request->input('username') != auth()->user()->username;
		
		$file = $request->file('filename');
		if ($file &&  $file->isValid()) {


			if($user->brochure){
				$oldfileexists = Storage::exists( $user->brochure );

				if($oldfileexists){
					 Storage::delete( $user->brochure);
				}
	 
			}

			if(!$user->username){
				$user->username = slugify($user->name);
			}
			
	 		$destinationPath = 'files/brochure/' . $user->username;

	 		$filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $newFilename = slugify($user->name).'-product-list.' . $extension;
            $filePath = $destinationPath . '/' . $newFilename;

          	Storage::put($filePath, File::get($file->getrealpath()));
            $user->brochure =  $filePath ;
        }
         
		 
		// Save
		$user->save();

		// Message Notification & Redirection
		flash("Your company details has updated successfully.")->success();
		$nextUrl = config('app.locale') . '/account#business-profile';

		// Redirection
		return redirect($nextUrl);

	}
	/**
	 * @param UserRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function updateDetails(UserRequest $request)
	{
		// Check if these fields has changed
		$emailChanged = $request->filled('email') && $request->input('email') != auth()->user()->email;
		$phoneChanged = $request->filled('phone') && $request->input('phone') != auth()->user()->phone;
		
		
		// Conditions to Verify User's Email or Phone
		$emailVerificationRequired = config('settings.mail.email_verification') == 1 && $emailChanged;
		$phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $phoneChanged;
		
		// Get User
		$user = User::withoutGlobalScopes([VerifiedScope::class])->find(auth()->user()->id);
		
		// Update User
		$input = $request->only($user->getFillable());
		foreach ($input as $key => $value) {
			if (in_array($key, ['email', 'phone','email_hidden','phone_hidden']) && empty($value)) {
				continue;
			}
			$user->{$key} = $value;
		}


		if($user->getOriginal('city_id')!=$user->city_id && $user->city){

			$user->lat =$user->city->latitude;
			$user->lon =$user->city->longitude;
			DB::table('posts')->where('user_id', $user->id)->update(array('city_id' => $user->city->id,'lat' => $user->city->latitude,'lon' => $user->city->longitude));
 
		}
		
		if(app('impersonate')->isImpersonating()){
		
			$user->phone_hidden = $request->input('phone_hidden');

			$user->email_hidden = $request->input('email_hidden');

		}
		
		// Email verification key generation
		if ($emailVerificationRequired) {
			$user->email_token = md5(microtime() . mt_rand());
			$user->verified_email = 0;
		}
		
		// Phone verification key generation
		if ($phoneVerificationRequired) {
			$user->phone_token = mt_rand(100000, 999999);
			$user->verified_phone = 0;
		}
		
		// Don't logout the User (See User model)
		if ($emailVerificationRequired || $phoneVerificationRequired) {
			session(['emailOrPhoneChanged' => true]);
		}
		
		// Save
		$user->save();
		
		// Message Notification & Redirection
		flash("Your account details has updated successfully.")->success();
		$nextUrl = config('app.locale') . '/account';
		
		// Send Email Verification message
		if ($emailVerificationRequired) {
			$this->sendVerificationEmail($user);
			$this->showReSendVerificationEmailLink($user, 'user');
		}
		
		// Send Phone Verification message
		if ($phoneVerificationRequired) {
			// Save the Next URL before verification
			session(['itemNextUrl' => $nextUrl]);
			
			$this->sendVerificationSms($user);
			$this->showReSendVerificationSmsLink($user, 'user');
			
			// Go to Phone Number verification
			$nextUrl = config('app.locale') . '/verify/user/phone/';
		}
		
		// Redirection
		return redirect($nextUrl);
	}
	
	/**
	 * Store the User's photo.
	 *
	 * @param $userId
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function updatePhoto($userId, Request $request)
	{
		// Get User
		$user = User::find($userId);
		
		if (empty($user)) {
			if ($request->ajax()) {
				return response()->json(['error' => t('User not found')]);
			}
			abort(404);
		}
		
		// Save all pictures
		$file = $request->file('photo');
		if (!empty($file)) {
			// Post Picture in database
			$user->photo = $file;
			$user->save();
		}
		
		// Ajax response
		if ($request->ajax()) {
			$data = [];
			$data['initialPreview'] = [];
			$data['initialPreviewConfig'] = [];
			
			if (!empty($user->photo)) {
				// Get Deletion Url
				$initialPreviewConfigUrl = lurl('account/' . $user->id . '/photo/delete');
				
				// Build Bootstrap-Input plugin's parameters
				$data['initialPreview'][] = resize($user->photo);
				
				$data['initialPreviewConfig'][] = [
					'caption' => last(explode('/', $user->photo)),
					'size'    => (int)File::size(filePath($user->photo)),
					'url'     => $initialPreviewConfigUrl,
					'key'     => $user->id,
					'extra'   => ['id' => $user->id],
				];
			}
			
			return response()->json($data);
		}
		
		flash(t('Your photo or avatar have been updated.'))->success();
		
		// Non ajax response
		return redirect(lurl('account'));
	}
	
	/**
	 * Delete the User's photo
	 *
	 * @param $userId
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function deletePhoto($userId, Request $request)
	{
		if (isDemo()) {
			$message = t('This feature has been turned off in demo mode.');
			
			if ($request->ajax()) {
				return response()->json(['error' => $message]);
			}
			
			flash($message)->info();
			
			return back();
		}
		
		// Get User
		$user = User::find($userId);
		
		if (empty($user)) {
			if ($request->ajax()) {
				return response()->json(['error' => t('User not found')]);
			}
			abort(404);
		}
		
		// Remove all the current user's photos, by removing his photo directory.
		$destinationPath = substr($user->photo, 0, strrpos($user->photo, '/'));
		Storage::deleteDirectory($destinationPath);
		
		// Delete the photo path from DB
		$user->photo = null;
		$user->save();
		
		if ($request->ajax()) {
			return response()->json([]);
		}
		
		flash(t("Your photo or avatar has been deleted."))->success();
		
		return back();
	}
	
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function updateSettings(Request $request)
	{
		// Validation
		if ($request->filled('password')) {
			$rules = ['password' => 'between:6,60|dumbpwd|confirmed'];
			$this->validate($request, $rules);
		}
		
		// Get User
		$user = User::find(auth()->user()->id);
		
		// Update
		$user->disable_comments = (int)$request->input('disable_comments');
		if ($request->filled('password')) {
			$user->password = Hash::make($request->input('password'));
		}
		
		// Save
		$user->save();
		
		flash(t("Your settings account has updated successfully."))->success();
		
		return redirect(config('app.locale') . '/account');
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function updatePreferences()
	{
		$data = [];
		
		return view('account.edit', $data);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function deletebrochure()
	{
		$user = User::find(auth()->user()->id);

		

		if($user->brochure){
			$oldfileexists = Storage::exists( $user->brochure );

			if($oldfileexists){
				 Storage::delete( $user->brochure);

				 
			}

			$user->brochure = null;

			$user->save();
 
		}


		flash("brochure has been deleted.")->success();
		$nextUrl = config('app.locale') . '/account#business-profile';

		// Redirection
		return redirect($nextUrl);
		 
	}

	
}
