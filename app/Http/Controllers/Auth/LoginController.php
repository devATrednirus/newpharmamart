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

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\FrontController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignOTPRequest;
use App\Events\UserWasLogged;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use DB;
use Illuminate\Support\Facades\Session;
class LoginController extends FrontController
{
    use AuthenticatesUsers , VerificationTrait;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    // If not logged in redirect to
    protected $loginPath = 'login';

    // The maximum number of attempts to allow
    protected $maxAttempts = 500000;

    // The number of minutes to throttle for
    protected $decayMinutes = 15;

    // After you've logged in redirect to
    protected $redirectTo = 'account';

    // After you've logged out redirect to
    protected $redirectAfterLogout = '/';

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('guest')->except(['except' => 'logout']);

		// Set default URLs
		$isFromLoginPage = str_contains(url()->previous(), '/' . trans('routes.login'));
		$this->loginPath = $isFromLoginPage ? config('app.locale') . '/' . trans('routes.login') : url()->previous();
		$this->redirectTo = $isFromLoginPage ? config('app.locale') . '/account' : url()->previous();
		// $this->redirectAfterLogout = config('app.locale') . '/' . trans('routes.login');
		$this->redirectAfterLogout = config('app.locale');

		// Get values from Config
		$this->maxAttempts = (int)config('settings.security.login_max_attempts', $this->maxAttempts);
		$this->decayMinutes = (int)config('settings.security.login_decay_minutes', $this->decayMinutes);
    }

    // -------------------------------------------------------
    // Laravel overwrites for loading LaraClassified views
    // -------------------------------------------------------

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm(Request $req )
    {
        // Remembering Login
        //if (Auth::viaRemember()) {
            //return redirect()->intended($this->redirectTo);
        //}

        // Meta Tags
        MetaTag::set('title', getMetaTag('title', 'login'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'login')));
        MetaTag::set('keywords', getMetaTag('keywords', 'login'));

        //echo $req->url();
        if( strpos($req->url(),'/admin')) {
          return view('vendor.admin.auth.login');
        } else {
          return view('auth.login');
        }
        //$r =  \Request;
        //if($request->url) {

        //} else {

        //}

        //return view('auth.login');


    }

	/**
	 * @param LoginRequest $request
	 * @return $this|\Illuminate\Http\RedirectResponse|void
	 * @throws \Illuminate\Validation\ValidationException
	 */
    public function login(LoginRequest $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // Get the right login field
        if(!empty($request->input('login'))) {
          $loginField = getLoginField($request->input('login'));
        }
       /* dump([$loginField,$request->input('login')]);
        DB::enableQueryLog();
        $user =  DB::select( DB::raw("SELECT * FROM users WHERE ".$loginField." = '".$request->input('login')."'") );

        if($user){

            $user= $user[0];

            if($loginField=="phone"){

             $check = $user->verified_phone;
            }
            else{
                $check = $user->verified_email;


            }

            if($check!="1"){

                if($loginField=="phone"){

                    $this->showReSendVerificationSmsLink($user, 'user');
                }
                else{
                   $this->showReSendVerificationEmailLink($user, 'user');


                }
            }
            $nextUrl = config('app.locale') . '/register/finish';
            return redirect($nextUrl);
        }*/
        //dd(DB::getQueryLog());
           // dd($user);
          // dd( strpos($request->url(),'/admin'));

        if(strpos($request->url(),'/admin')) {

          $credentials = [
       				'email'    => $request->input('email'),
       				'password' => $request->input('password'),
       				'blocked'  => 0,
       		];

        } else {
          // Get credentials values
          $credentials = [
              $loginField => $request->input('login'),
              'password'  => $request->input('password'),
              'blocked'   => 0,
          ];
          if (in_array($loginField, ['email', 'phone'])) {
              $credentials['verified_' . $loginField] = 1;
          } else {
              $credentials['verified_email'] = 1;
              $credentials['verified_phone'] = 1;
          }

        }


        //dd($credentials);
        // Auth the User

        $old_sid = Session()->getId();
        if (Auth::attempt($credentials)) {

            Session()->setId($old_sid);

            Session()->save();

            // Update last user logged Date
            Event::dispatch(new UserWasLogged(User::find(Auth::user()->id)));
                Session::put('login_type','user');
            //
            if(strpos($request->url(),'/admin')) {
              return redirect('admin/dashboard');
            } else {
                return redirect()->intended($this->redirectTo);
            }
        }


        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        // Check and retrieve previous URL to show the login error on it.
        if (Session()->has('url.intended')) {
			$this->loginPath = Session()->get('url.intended');
		}

        return redirect($this->loginPath)->withErrors(['error' => trans('auth.failed')])->withInput();
    }




     /**
     * @param LoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function signotp(SignOTPRequest $request)
    {



        $phone = "+91".$request->input('otp_phone');

        $user = User::withoutGlobalScopes([\App\Models\Scopes\VerifiedScope::class])->where('phone',$phone)->orWhere('phone',$request->input('otp_phone'))->first();




        if($user && $user->phone_token==$request->input('otp')){


             $user->verified_phone = 1;


             $user->save();

             $old_sid = Session()->getId();


             if (Auth::loginUsingId($user->id)) {

                Event::dispatch(new UserWasLogged(User::find(Auth::user()->id)));
                //return redirect()->back();
                Session()->setId($old_sid);

                Session()->save();


                return response()->json([

                    'message' => "success"
                ],200);
             }


        }

        return response()->json([
            'code' => 100,
            'message' => "OTP Error"
        ],422);



    }
    /**
     * @param LoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function signin(SignInRequest $request)
    {



        $phone = "+91".$request->input('signin_phone');
      //  dd($phone);
        $user = User::withoutGlobalScopes([\App\Models\Scopes\VerifiedScope::class])->where('phone',$phone)->orWhere('phone',$request->input('signin_phone'))->first();

        if(!$user){

            $user = new User;
            $user->phone = $request->input('signin_phone');
            $user->first_name = $request->input('signin_name');
            $user->verified_phone = 0;
            $user->user_type_id = 2;

        }

        $user->phone_token = mt_rand(100000, 999999);
        ///$user->phone_token = "123456";
        $user->phone_token_created = \Carbon\Carbon::now();

        $user->save();
        $this->sendVerificationSms($user);

        exit;
        return response()->json([
                'error' => 'false',
                'message' => "OTP Sent"
            ],200);
        //
        //return redirect()->back()->with(['otp'=>'sent','phone'=>$request->input('signin_phone')]);

    }


    function textlocal($numbers,$message){

        // Account details
        $apiKey = urlencode('ICJh7x872y4-0NUwrosA2Uk3nCSTjNrYsD8NzmszeX');

        // Message details

        $sender = urlencode('PMAKBR');



        // Prepare data for POST request
        $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

        // Send the POST request with cURL
        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        dd($response);
        // Process your response here
        echo $response;

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        // Get the current Country
        if (Session()->has('country_code')) {
            $countryCode = Session('country_code');
        }

        // Remove all session vars
        $this->guard()->logout();
        $request->Session()->flush();
        $request->Session()->regenerate();

        // Retrieve the current Country
        if (isset($countryCode) && !empty($countryCode)) {
            Session(['country_code' => $countryCode]);
        }

        $message = t('You have been logged out.') . ' ' . t('See you soon.');
        //flash($message)->success();
        return redirect('/home');

        //return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
}
