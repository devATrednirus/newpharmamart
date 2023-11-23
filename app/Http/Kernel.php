<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            //\App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\TransformInput::class,
            \App\Http\Middleware\XSSProtection::class,
			\App\Http\Middleware\BannedUser::class,
			\App\Http\Middleware\HttpsProtocol::class,
        ],

        'admin' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

			\App\Http\Middleware\Admin::class,
            \App\Http\Middleware\XSSProtection::class,
			\App\Http\Middleware\BannedUser::class,
			\App\Http\Middleware\HttpsProtocol::class,
        ],


        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'locale' => ['localize', 'localizationRedirect', 'localeSessionRedirect', 'localeViewPath', 'html.minify'],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
		'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'client' => \Laravel\Passport\Http\Middleware\CheckClientCredentials::class,

            'banned.user' => \App\Http\Middleware\BannedUser::class,

            'localize' => \Larapen\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'localizationRedirect' => \Larapen\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeSessionRedirect' => \Larapen\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
    		'localeViewPath' => \Larapen\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,

            'html.minify' => \App\Http\Middleware\HtmlMinify::class,
            'install.checker' => \App\Http\Middleware\InstallationChecker::class,
            'prevent.back.history' => \App\Http\Middleware\PreventBackHistory::class,
            'only.ajax' => \App\Http\Middleware\OnlyAjax::class,
		'demo.restriction' => \App\Http\Middleware\DemoRestriction::class,
    ];

    protected $middlewarePriority = [
  		\Illuminate\Session\Middleware\StartSession::class,
  		\Illuminate\View\Middleware\ShareErrorsFromSession::class,
  		\App\Http\Middleware\Authenticate::class,
  		\Illuminate\Session\Middleware\AuthenticateSession::class,
  		\Illuminate\Routing\Middleware\SubstituteBindings::class,
  		\Illuminate\Auth\Middleware\Authorize::class,
  	];


    public function handle($request)
   {

          if(strpos($request->url(), 'en/')) {
              //redirect(str_replace('en/','',$request->url()));
              //return Redirect::route(str_replace('en/','',$request->url()));
              header('Location: '.str_replace('https://','http://',str_replace('en/','',$request->url())));
              exit();

          }

          return parent::handle($request);
    }





}
