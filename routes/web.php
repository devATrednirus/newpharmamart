<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Upgrading
|--------------------------------------------------------------------------
|
| The upgrading process routes
|
*/


/*Route::group(['middleware' => ['web'], 'namespace' => 'App\Http\Controllers'], function () {
	Route::get('upgrade', 'UpgradeController@version');
});*/

/*
|--------------------------------------------------------------------------
| Installation
|--------------------------------------------------------------------------
|
| The installation process routes
|
*/
/*Route::group([
	'middleware' => ['web', 'install.checker'],
	'namespace'  => 'App\Http\Controllers',
], function () {
	Route::get('install', 'InstallController@starting');
	Route::get('install/site_info', 'InstallController@siteInfo');
	Route::post('install/site_info', 'InstallController@siteInfo');
	Route::get('install/system_compatibility', 'InstallController@systemCompatibility');
	Route::get('install/database', 'InstallController@database');
	Route::post('install/database', 'InstallController@database');
	Route::get('install/database_import', 'InstallController@databaseImport');
	Route::get('install/cron_jobs', 'InstallController@cronJobs');
	Route::get('install/finish', 'InstallController@finish');
});*/


/*
|--------------------------------------------------------------------------
| Back-end
|--------------------------------------------------------------------------
|
| The admin panel routes
|
*/
Route::group([
	'namespace'  => 'App\Http\Controllers\Admin',
	'middleware' => ['web', 'install.checker'],
	'prefix'     => config('larapen.admin.route_prefix', 'admin'),
], function ($router) {
	// Auth
	//Route::auth();
	Route::get('logout', 'Auth\LoginController@logout');
	Route::get('reset','ImportController@reset');
	// Admin Panel Area
	Route::group([
		'middleware' => ['clearance', 'banned.user'],     //'middleware' => ['admin', 'clearance', 'banned.user', 'prevent.back.history'],
	], function ($router) {
		// Dashboard
		Route::get('dashboard', 'DashboardController@dashboard');
		Route::get('/', 'DashboardController@redirect');
		// Extra (must be called before CRUD)
		Route::get('homepage/{action}', 'HomeSectionController@reset')->where('action', 'reset_(.*)');
		Route::get('languages/sync_files', 'LanguageController@syncFilesLines');
		Route::get('permissions/create_default_entries', 'PermissionController@createDefaultEntries');

		// CRUD
		// CRUD
		CRUD::resource('advertisings', 'AdvertisingController');
		CRUD::resource('blacklists', 'BlacklistController');
		CRUD::resource('categories', 'CategoryController');
		CRUD::resource('categories/{catId}/subcategories', 'SubCategoryController');
		CRUD::resource('categories/{catId}/custom_fields', 'CategoryFieldController');
		CRUD::resource('cities', 'CityController');
		CRUD::resource('countries', 'CountryController');
		CRUD::resource('countries/{countryCode}/cities', 'CityController');
		CRUD::resource('countries/{countryCode}/admins1', 'SubAdmin1Controller');
		CRUD::resource('currencies', 'CurrencyController');
		CRUD::resource('custom_fields', 'FieldController');
		CRUD::resource('custom_fields/{cfId}/options', 'FieldOptionController');
		CRUD::resource('custom_fields/{cfId}/categories', 'CategoryFieldController');
		CRUD::resource('genders', 'GenderController');
		CRUD::resource('homepage', 'HomeSectionController');
		CRUD::resource('admins1/{admin1Code}/cities', 'CityController');
		CRUD::resource('admins1/{admin1Code}/admins2', 'SubAdmin2Controller');
		CRUD::resource('admins2/{admin2Code}/cities', 'CityController');
		CRUD::resource('languages', 'LanguageController');
		CRUD::resource('meta_tags', 'MetaTagController');
		CRUD::resource('redirects', 'RedirectController');
		CRUD::resource('packages', 'PackageController');
		CRUD::resource('pages', 'PageController');
		CRUD::resource('payments', 'PaymentController');
		CRUD::resource('payment_methods', 'PaymentMethodController');
		CRUD::resource('permissions', 'PermissionController');
		CRUD::resource('pictures', 'PictureController');
		CRUD::resource('posts', 'PostController');
		CRUD::resource('p_types', 'PostTypeController');
		CRUD::resource('report_types', 'ReportTypeController');
		CRUD::resource('roles', 'RoleController');
		CRUD::resource('settings', 'SettingController');
		CRUD::resource('time_zones', 'TimeZoneController');
		CRUD::resource('users', 'UserController');
		CRUD::resource('user_groups', 'UserGroupController');
		CRUD::resource('user_groups/{groupId}/users', 'UserGroupMapController');
		CRUD::resource('package_history', 'PackageHistoryController');
		CRUD::resource('enquiries', 'EnquiryController');
		CRUD::resource('buy-leads', 'BuyleadController');



		CRUD::resource('searches', 'SearchHistoryController');
		CRUD::resource('queries', 'QuickMessageController');
		CRUD::resource('messages', 'MessagesController');
		CRUD::resource('banners', 'BannerController');
		CRUD::resource('company/{account_id}/banners', 'CompanyBannerController');

		//Route::crud('blocked-locations/{state_id}/show', 'BlockedLocationController');
		Route::get('blocked-locations/{state_id}', 'BlockedLocationController@showBlocked');

		Route::get('blocked-locations/{state_id}/{city_id}', 'BlockedLocationController@showBlocked');

		// Others
		Route::get('account', 'UserController@account');
		Route::post('ajax/{table}/{field}', 'InlineRequestController@make');

		// Backup
		Route::get('backups', 'BackupController@index');
		Route::put('backups/create', 'BackupController@create');
		Route::get('backups/download/{file_name?}', 'BackupController@download');
		Route::delete('backups/delete/{file_name?}', 'BackupController@delete')->where('file_name', '(.*)');

		// Actions
		Route::get('actions/clear_cache', 'ActionController@clearCache');
		Route::get('actions/call_ads_cleaner_command', 'ActionController@callAdsCleanerCommand');
		Route::post('actions/maintenance_down', 'ActionController@maintenanceDown');
		Route::get('actions/maintenance_up', 'ActionController@maintenanceUp');

		// Re-send Email or Phone verification message
		Route::get('verify/user/{id}/resend/email', 'UserController@reSendVerificationEmail');
		Route::get('verify/user/{id}/resend/sms', 'UserController@reSendVerificationSms');
		Route::get('verify/post/{id}/resend/email', 'PostController@reSendVerificationEmail');
		Route::get('verify/post/{id}/resend/sms', 'PostController@reSendVerificationSms');

		// Plugins
		Route::get('plugins', 'PluginController@index');
		Route::post('plugins/{plugin}/install', 'PluginController@install');
		Route::get('plugins/{plugin}/install', 'PluginController@install');
		Route::get('plugins/{plugin}/uninstall', 'PluginController@uninstall');
		Route::get('plugins/{plugin}/delete', 'PluginController@delete');

	    Route::post('import_category/','ImportController@import_category');
            Route::post('export_category/','ImportController@export_category');

		Route::post('import_users/','ImportController@import_users');
		Route::post('download','ImportController@download');
		Route::get('cityexport/{countrycode}','ImportController@cityexport');
		Route::post('userexcelexport','ImportController@userexcelexport');
		Route::get('productexcelexport','ImportController@productexcelexport');
		Route::post('import_product/','ImportController@import_product');
		Route::post('import_city/','ImportController@import_city');

		Route::get('downloadusercsv','ImportController@downloadusercsv');
		Route::get('downloadcategorycsv','ImportController@downloadcategorycsv');
		Route::get('downloadcitycsv','ImportController@downloadcitycsv');
	});
	 Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
			Route::post('password/emailaddress', 'Auth\ForgotPasswordController@sendResetLinkEmaila');

			 //Reset Password using Token
			Route::get('password/token', 'Auth\ForgotPasswordController@showTokenRequestForm');
			Route::post('password/token', 'Auth\ForgotPasswordController@sendResetToken');

			// Reset Password using Link (Core Routes...)
			Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
			Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});

/*
|--------------------------------------------------------------------------
| Purchase Code Checker
|--------------------------------------------------------------------------
|
| Checking your purchase code. If you do not have one, please follow this link:
| https://codecanyon.net/item/laraclassified-geo-classified-ads-cms/16458425
| to acquire a valid code.
|
| IMPORTANT: Do not change this part of the code.
|
*/
// $tab = [
//     'install',
//     config('larapen.admin.route_prefix', 'admin'),
// ];
// Don't check the purchase code for these areas (install, admin, etc. )
/*if (!in_array(\Illuminate\Support\Facades\Request::segment(1), $tab)) {
    // Make the purchase code verification only if 'installed' file exists
    if (file_exists(storage_path('installed')) && !config('settings.error')) {
        // Get purchase code from 'installed' file
        $purchaseCode = file_get_contents(storage_path('installed'));

        // Send the purchase code checking
        if (
            $purchaseCode == '' or
            config('settings.purchase_code') == '' or
            $purchaseCode != config('settings.purchase_code')
        ) {
            $apiUrl = config('larapen.core.purchaseCodeCheckerUrl') . config('settings.purchase_code') . '&item_id=' . config('larapen.core.itemId');
            $data = \App\Helpers\Curl::fetch($apiUrl);

            // Check & Get cURL error by checking if 'data' is a valid json
            if (!isValidJson($data)) {
                $data = json_encode(['valid' => true, 'message' => 'Invalid purchase code. ' . strip_tags($data)]);
            }

            // Format object data
            $data = json_decode($data);

            // Check if 'data' has the valid json attributes
            if (!isset($data->valid) || !isset($data->message)) {
                $data = json_encode(['valid' => true, 'message' => 'Invalid purchase code. Incorrect data format.']);
                $data = json_decode($data);
            }

            // Checking
            if ($data->valid == true) {
                file_put_contents(storage_path('installed'), $data->license_code);
            }
        }
    }
}*/

/*
|--------------------------------------------------------------------------
| Front-end
|--------------------------------------------------------------------------
|
| The not translated front-end routes
|
*/
Route::group([
	'middleware' => ['web'], //, 'install.checker'
	'namespace'  => 'App\Http\Controllers',
], function ($router) {
	// SEO
	if(!isset($_SERVER['SERVER_NAME']) || (preg_match('/mart.redniruscare.com/',$_SERVER['SERVER_NAME']) || preg_match('/www.dheerajlocalmachine.com/',$_SERVER['SERVER_NAME']) || preg_match('/mart.dheerajlocalmachine.com/',$_SERVER['SERVER_NAME'])  || preg_match('/pharmafranchisemart.com/',$_SERVER['SERVER_NAME']) || preg_match('/127.0.0.1/',$_SERVER['SERVER_NAME']) ) ){
		Route::get('sitemaps.xml', 'SitemapsController@index');
	}

	// Impersonate (As admin user, login as an another user)
	Route::group(['middleware' => 'auth'], function ($router) {
		Route::impersonate();
	});
});


/*
|--------------------------------------------------------------------------
| Front-end
|--------------------------------------------------------------------------
|
| The translated front-end routes
|

Route::group([
	'prefix'     => LaravelLocalization::setLocale(),
	'middleware' => ['locale'],
	'namespace'  => 'App\Http\Controllers',
], function ($router) {   */



	Route::group(['middleware' => ['web'],
	'namespace'  => 'App\Http\Controllers',], function ($router) { //, 'install.checker'
		// HOMEPAGE
		Route::get('/', 'HomeController@index');



		Route::get('/packages', 'HomeController@packages');

		Route::get(LaravelLocalization::transRoute('routes.countries'), 'CountriesController@index');


		// AUTH
		Route::group(['middleware' => ['guest', 'prevent.back.history']], function () {
			// Registration Routes...
			Route::get(LaravelLocalization::transRoute('routes.register'), 'Auth\RegisterController@showRegistrationForm');
			Route::post(LaravelLocalization::transRoute('routes.register'), 'Auth\RegisterController@register');
			Route::get('register/finish', 'Auth\RegisterController@finish');

			// Authentication Routes...
			//Route::get(LaravelLocalization::transRoute('routes.login'), 'Auth\LoginController@showLoginForm');
			Route::get('admin/login', 'Auth\LoginController@showLoginForm');
			Route::get('login', 'Auth\LoginController@showLoginForm');

			//Route::post(LaravelLocalization::transRoute('routes.login'), 'Auth\LoginController@login');
			Route::post('admin/login', 'Auth\LoginController@login');
			Route::post('login', 'Auth\LoginController@login');

			// Forgot Password Routes...
			Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
			Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

			// Reset Password using Token
			Route::get('password/token', 'Auth\ForgotPasswordController@showTokenRequestForm');
			Route::post('password/token', 'Auth\ForgotPasswordController@sendResetToken');

			// Reset Password using Link (Core Routes...)
			Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
			Route::post('password/reset', 'Auth\ResetPasswordController@reset');

			// Social Authentication
			Route::get('auth/facebook', 'Auth\SocialController@redirectToProvider');
			Route::get('auth/facebook/callback', 'Auth\SocialController@handleProviderCallback');
			Route::get('auth/google', 'Auth\SocialController@redirectToProvider');
			Route::get('auth/google/callback', 'Auth\SocialController@handleProviderCallback');
			Route::get('auth/twitter', 'Auth\SocialController@redirectToProvider');
			Route::get('auth/twitter/callback', 'Auth\SocialController@handleProviderCallback');


			Route::group(['prefix' => 'api','middleware' => 'throttle:111,3'], function () {

 				Route::post('user/signin', 'Auth\LoginController@signin');
 				Route::post('verify/otp', 'Auth\LoginController@signotp');
			});



		});

		// Email Address or Phone Number verification
		$router->pattern('field', 'email|phone');
		Route::get('verify/user/{id}/resend/email', 'Auth\RegisterController@reSendVerificationEmail');
		Route::get('verify/user/{id}/resend/sms', 'Auth\RegisterController@reSendVerificationSms');
		Route::get('verify/user/{field}/{token?}', 'Auth\RegisterController@verification');
		Route::post('verify/user/{field}/{token?}', 'Auth\RegisterController@verification');
		Route::post('sitemap/loadmore', 'SitemapController@loadmore');
		// User Logout
		Route::get(LaravelLocalization::transRoute('routes.logout'), 'Auth\LoginController@logout');


		// POSTS
		Route::group(['namespace' => 'Post'], function ($router) {
			//$router->pattern('id', '[0-9]+');
			// $router->pattern('slug', '.*');
			//$router->pattern('slug', '^(?=.*)((?!\/).)*$');

			Route::get('posts/create/{tmpToken?}', 'CreateController@getForm');
			Route::post('posts/create', 'CreateController@postForm');
			Route::put('posts/create/{tmpToken}', 'CreateController@postForm');
			Route::get('posts/create/{tmpToken}/photos', 'PhotoController@getForm');
			Route::post('posts/create/{tmpToken}/photos', 'PhotoController@postForm');
			Route::post('posts/create/{tmpToken}/photos/{id}/delete', 'PhotoController@delete');
			//Route::get('posts/create/{tmpToken}/payment', 'PaymentController@getForm');
			//Route::post('posts/create/{tmpToken}/payment', 'PaymentController@postForm');
			Route::get('posts/create/{tmpToken}/finish', 'CreateController@finish');




			// Payment Gateway Success & Cancel
			//Route::get('posts/create/{tmpToken}/payment/success', 'PaymentController@paymentConfirmation');
			//Route::get('posts/create/{tmpToken}/payment/cancel', 'PaymentController@paymentCancel');

			// Email Address or Phone Number verification
			$router->pattern('field', 'email|phone');
			Route::get('verify/post/{id}/resend/email', 'CreateController@reSendVerificationEmail');
			Route::get('verify/post/{id}/resend/sms', 'CreateController@reSendVerificationSms');
			Route::get('verify/post/{field}/{token?}', 'CreateController@verification');
			Route::post('verify/post/{field}/{token?}', 'CreateController@verification');

			Route::group(['middleware' => 'auth'], function ($router) {
				$router->pattern('id', '[0-9]+');

				Route::get('posts/{id}/edit', 'EditController@getForm');
				Route::put('posts/{id}/edit', 'EditController@postForm');
				Route::get('post/deletebrochure/{id}', 'EditController@deletebrochure');

				Route::get('groups/{id}/edit', 'EditController@getGroupForm');
				Route::put('groups/{id}/edit', 'EditController@postGroupForm');

				Route::get('groups/create', 'EditController@getGroupCreate');
				Route::put('groups/create', 'EditController@postGroupCreate');


				Route::get('division/create', 'EditController@getDivisionCreate');
				Route::put('division/create', 'EditController@postDivisionCreate');

				Route::get('division/{id}/edit', 'EditController@getDivisionForm');
				Route::put('division/{id}/edit', 'EditController@postDivisionForm');






				Route::get('posts/{id}/photos', 'PhotoController@getForm');
				Route::post('posts/{id}/photos', 'PhotoController@postForm');
				Route::post('posts/{token}/photos/{id}/delete', 'PhotoController@delete');

			});


			Route::get('user/payment', 'PaymentController@getUserForm');
			Route::post('user/payment', 'PaymentController@postUserForm');


			Route::get('user/buy-leads', 'PaymentController@getBuyLeadsForm');
			Route::post('user/buy-leads', 'PaymentController@postBuyLeadsForm');


			Route::any('user/payment/success', 'PaymentController@paymentUserConfirmation');
			Route::any('user/payment/cancel', 'PaymentController@paymentUserCancel');

			// Payment Gateway Success & Cancel
			//Route::get('posts/{id}/payment/success', 'PaymentController@paymentConfirmation');
			//Route::get('posts/{id}/payment/cancel', 'PaymentController@paymentCancel');

			// Post's Details
			Route::get(LaravelLocalization::transRoute('routes.post'), 'DetailsController@index');
			Route::POST(LaravelLocalization::transRoute('routes.post'), 'DetailsController@index');
			//Route::get(':id/:slug', 'DetailsController@index');

			// Contact Post's Author
			Route::post('posts/{id}/contact', 'DetailsController@sendMessage');

			Route::post('company/{id}/contact', 'DetailsController@sendCompanyMessage');


			Route::post('quick_query', 'DetailsController@sendQuickQueryMessage');
			Route::post('query_detail', 'DetailsController@updateQueryMessage');






			// Send report abuse
			Route::get('posts/{id}/report', 'ReportController@showReportForm');
			Route::post('posts/{id}/report', 'ReportController@sendReport');
		});


		// ACCOUNT
		Route::group(['middleware' => ['auth', 'banned.user', 'prevent.back.history'], 'namespace' => 'Account'], function ($router) {
			$router->pattern('id', '[0-9]+');

			// Users
			Route::get('account', 'EditController@index');
			//Route::group(['middleware' => 'impersonate.protect'], function () {
				Route::put('account', 'EditController@updateDetails');
				Route::put('account/settings', 'EditController@updateSettings');
				Route::put('account/company', 'EditController@updateCompanyDetails');
				Route::put('account/statutory', 'EditController@updateStatutoryDetails');
				Route::get('account/deletebrochure', 'EditController@deletebrochure');


				Route::put('account/bank', 'EditController@updateBankDetails');
				Route::put('account/api', 'EditController@updateApiDetails');
				Route::put('account/location-preference', 'EditController@updateLocationFilter');

				Route::post('ajax/ifsc', 'EditController@getBankByIFSC');

				Route::put('account/preferences', 'EditController@updatePreferences');
				Route::post('account/{id}/photo', 'EditController@updatePhoto');
				Route::post('account/{id}/photo/delete', 'EditController@deletePhoto');
			//});
			//Route::get('account/close', 'CloseController@index');
			Route::group(['middleware' => 'impersonate.protect'], function () {
				Route::post('account/close', 'CloseController@submit');
			});

			// Posts
			Route::get('account/saved-search', 'PostsController@getSavedSearch');
			$router->pattern('pagePath', '(my-posts|my-groups|archived|favourite|pending-approval|saved-search|divisions)+');
			Route::get('account/{pagePath}', 'PostsController@getPage');
			Route::get('account/my-posts/{id}/offline', 'PostsController@getMyPosts');
			Route::get('account/archived/{id}/repost', 'PostsController@getArchivedPosts');
			Route::get('account/{pagePath}/{id}/delete', 'PostsController@destroy');
			Route::post('account/{pagePath}/delete', 'PostsController@destroy');
			Route::post('account/repostproduct','PostsController@repostproduct');
			Route::post('account/archiveproduct','PostsController@archiveproduct');
			Route::post('account/changerows','PostsController@numberofrows');
			Route::post('account/addfavourite','PostsController@addfavourite');

			Route::post('account/removefavourite','PostsController@removefavourite');
			// Conversations
			Route::get('account/conversations', 'ConversationsController@index')->name('conversations');


			Route::get('account/conversations/{id}/delete', 'ConversationsController@destroy');
			Route::post('account/conversations/delete', 'ConversationsController@destroy');
			Route::post('account/conversations/{id}/reply', 'ConversationsController@reply');
			$router->pattern('msgId', '[0-9]+');
			Route::get('account/conversations/{id}/messages', 'ConversationsController@messages')->name('conversations');
			Route::get('account/conversations/{id}/buy-messages', 'ConversationsController@messages')->name('buy_lead');

			Route::get('account/conversations/{id}/messages/{msgId}/delete', 'ConversationsController@destroyMessages');
			Route::post('account/conversations/{id}/messages/delete', 'ConversationsController@destroyMessages');


			// Buy Leads
			Route::get('account/buy-leadsold', 'BuyLeadsController@index');

			Route::any('account/buy-leads', 'ConversationsController@index')->name('buy_lead');


			Route::get('account/purchased-leads', 'BuyLeadsController@purchased');
			Route::get('account/buy-leads/{id}/messages', 'BuyLeadsController@messages');
			Route::get('account/buy-leads/{id}', 'BuyLeadsController@buy');



			// Transactions
			Route::get('account/transactions', 'TransactionsController@index');

			Route::get('account/banners', 'TransactionsController@banners');
			Route::post('account/productexcelexport','ImportController@productexcelexport');
		    Route::post('account/productimport/','ImportController@import_product');
	        Route::get('account/downloadproductcsvformat','ImportController@downloadproductcsvformat');
		});


		// AJAX
		Route::group(['prefix' => 'ajax'], function ($router) {
			Route::get('countries/{countryCode}/admins/{adminType}', 'Ajax\LocationController@getAdmins');
			Route::get('countries/{countryCode}/admins/{adminType}/{adminCode}/cities', 'Ajax\LocationController@getCities');
			Route::get('countries/{countryCode}/cities/{id}', 'Ajax\LocationController@getSelectedCity');
			Route::post('countries/{countryCode}/cities/autocomplete', 'Ajax\LocationController@searchedCities');
			Route::post('countries/{countryCode}/admin1/cities', 'Ajax\LocationController@getAdmin1WithCities');
			Route::post('category/sub-categories', 'Ajax\CategoryController@getSubCategories');
			Route::post('category/custom-fields', 'Ajax\CategoryController@getCustomFields');
			Route::post('save/post', 'Ajax\PostController@savePost');
			Route::post('save/search', 'Ajax\PostController@saveSearch');
			Route::post('post/phone', 'Ajax\PostController@getPhone');
			Route::post('post/pictures/reorder', 'Ajax\PostController@picturesReorder');
			Route::post('messages/check', 'Ajax\ConversationController@checkNewMessages');
			Route::post('group/create', 'Ajax\PostController@saveGroup');
		});


		// FEEDS
		Route::feeds();


		// Country Code Pattern
		$countryCodePattern = implode('|', array_map('strtolower', array_keys(getCountries())));
		$router->pattern('countryCode', $countryCodePattern);


		// XML SITEMAPS
		Route::get('{countryCode}/sitemaps.xml', 'SitemapsController@site');
		Route::get('{countryCode}/sitemaps/pages.xml', 'SitemapsController@pages');
		Route::get('{countryCode}/sitemaps/categories.xml', 'SitemapsController@categories');
		Route::get('{countryCode}/sitemaps/cities.xml', 'SitemapsController@cities');
		Route::get('{countryCode}/sitemaps/states.xml', 'SitemapsController@states');
		Route::get('{countryCode}/sitemaps/posts.xml', 'SitemapsController@posts');
		Route::get('{countryCode}/sitemaps/companies.xml', 'SitemapsController@companies');
		Route::get('{countryCode}/sitemaps/cities/{city}.xml', 'SitemapsController@citiesCats');
		Route::get('{countryCode}/company/{comanyname}.xml', 'SitemapsController@companydetail');
		Route::get('{comanyname}/sitemap', 'SitemapsController@companydetailSitemap');
		Route::get('{countryCode}/state/{statename}.xml', 'SitemapsController@statedetail');


		// STATICS PAGES
		Route::get(LaravelLocalization::transRoute('routes.page'), 'PageController@index');
		Route::get(LaravelLocalization::transRoute('routes.contact'), 'PageController@contact');
		Route::post(LaravelLocalization::transRoute('routes.contact'), 'PageController@contactPost');
		Route::get(LaravelLocalization::transRoute('routes.sitemap')."/{category?}", 'SitemapController@index');

		Route::get('thankyou', 'PageController@thankyou');

		// DYNAMIC URL PAGES
		$router->pattern('id', '[0-9]+');
		//$router->pattern('username', '[a-zA-Z0-9]+');
		Route::get(LaravelLocalization::transRoute('routes.search'), 'Search\SearchController@index');
		Route::post(LaravelLocalization::transRoute('routes.search'), 'Search\SearchController@index');


		if(!isset($_SERVER['SERVER_NAME']) || (preg_match('/mart.dheerajlocalmachine.com/',$_SERVER['SERVER_NAME']) || preg_match('/www.dheerajlocalmachine.com/',$_SERVER['SERVER_NAME']) || preg_match('/mart.redniruscare.com/',$_SERVER['SERVER_NAME']) || preg_match('/pharmafranchisemart.com/',$_SERVER['SERVER_NAME']) || preg_match('/127.0.0.1/',$_SERVER['SERVER_NAME']) ) ){
			Route::get(LaravelLocalization::transRoute('routes.search-user'), 'Search\UserController@index');
			//Route::get(LaravelLocalization::transRoute('routes.search-username'), 'Search\UserController@profile');
			//Route::get(LaravelLocalization::transRoute('routes.company-group'), 'Search\SearchController@index');

			Route::get(LaravelLocalization::transRoute('routes.search-tag'), 'Search\TagController@index');
			Route::get(LaravelLocalization::transRoute('routes.search-city'), 'Search\SearchController@index');
			Route::post(LaravelLocalization::transRoute('routes.search-city'), 'Search\SearchController@index');

			Route::get(LaravelLocalization::transRoute('routes.search-subCat'), 'Search\CategoryController@index');
			Route::post(LaravelLocalization::transRoute('routes.search-subCat'), 'Search\CategoryController@index');


			Route::get(LaravelLocalization::transRoute('routes.search-cat'), 'Search\CategoryController@index');
			Route::post(LaravelLocalization::transRoute('routes.search-cat'), 'Search\CategoryController@loadmore');

			Route::get(LaravelLocalization::transRoute('routes.search-cat-location'), 'Search\SearchController@index');
			Route::post(LaravelLocalization::transRoute('routes.search-cat-location'), 'Search\SearchController@index');

			Route::get(LaravelLocalization::transRoute('routes.search-subCat-location'), 'Search\SearchController@index');
			Route::post(LaravelLocalization::transRoute('routes.search-subCat-location'), 'Search\SearchController@index');


		}
		else{

			Route::get(LaravelLocalization::transRoute('routes.domain-home'), 'Search\SearchController@index')->name('company_name');
			Route::get(LaravelLocalization::transRoute('routes.domain-inner'), 'Search\SearchController@index')->name('company_name');

		}
});


//});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
