<?php
// Search parameters
$queryString = (request()->getQueryString() ? ('?' . request()->getQueryString()) : '');

// Get the Default Language
$cacheExpiration = (isset($cacheExpiration)) ? $cacheExpiration : config('settings.other.cache_expiration', 60);
$defaultLang = Cache::remember('language.default', $cacheExpiration, function () {
    $defaultLang = \App\Models\Language::where('default', 1)->first();
    return $defaultLang;
});

// Check if the Multi-Countries selection is enabled
$multiCountriesIsEnabled = false;
$multiCountriesLabel = '';
if (config('settings.geo_location.country_flag_activation')) {
	if (!empty(config('country.code'))) {
		if (\App\Models\Country::where('active', 1)->count() > 1) {
			$multiCountriesIsEnabled = true;
			$multiCountriesLabel = 'title="' . t('Select a Country') . '"';
		}
	}
}

// Logo Label
$logoLabel = '';
if (getSegment(1) != trans('routes.countries')) {
	$logoLabel = config('settings.app.app_name') . ((!empty(config('country.name'))) ? ' ' . config('country.name') : '');
}

$cookie_user = json_decode(Cookie::get('user'));

 
?>
<style>
@media only screen and (max-width: 600px) {
  #userMenuDropdown{
	  display:block;
	  width:96%;
  }
}
</style>
<div class="header main-header">
	<nav class="navbar fixed-top navbar-site navbar-light bg-light navbar-expand-md" role="navigation">
		<div class="container-fluid">
			
			<div class="navbar-identity">
				{{-- Logo --}}
				<a href="{{ lurl('/') }}" class="navbar-brand logo logo-title">
					<img width="214px" height="60px" src="{{ lurl('/') }}/storage/app/default/logo.png"
						 alt="{{ strtolower(config('settings.app.app_name')) }}" class="tooltipHere main-logo" />
				</a>
				
				
				{{-- Toggle Nav (Mobile) --}}
				<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggler pull-right" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="30" height="30" focusable="false">
						<title>{{ t('Menu') }}</title>
						<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M4 7h22M4 15h22M4 23h22"></path>
					</svg>
				</button>
				
			</div>
			@section('search')
			@show
			 
			<!-- <div class="header-search">
				   <div class="search-row animated fadeInUp">
							<?php $attr = ['countryCode' => config('country.icode')]; ?>
							<form id="search" name="search" action="{{ lurl(trans('routes.v-search', $attr), $attr) }}" method="GET">
								<div class="row m-0">
									<div class="col-sm-10 col-xs-12 search-col relative">
										<i class="icon-docs icon-append"></i>
										<input type="text" name="q" class="form-control keyword has-icon" placeholder="{{ t('What?') }}" value="">
									</div>
									{{-- 
									<div class="col-sm-5 col-xs-12 search-col relative locationicon">
										<i class="icon-location-2 icon-append"></i>
										<input type="hidden" id="lSearch" name="l" value="">
										@if ($showMap)
											<input type="text" id="locSearch" name="location" class="form-control locinput input-rel searchtag-input has-icon tooltipHere"
												   placeholder="{{ t('Where?') }}" value="" title="" data-placement="bottom"
												   data-toggle="tooltip" type="button"
												   data-original-title="{{ t('Enter a city name OR a state name with the prefix ":prefix" like: :prefix', ['prefix' => t('area:')]) . t('State Name') }}">
										@else
											<input type="text" id="locSearch" name="location" class="form-control locinput input-rel searchtag-input has-icon"
												   placeholder="{{ t('Where?') }}" value="">
										@endif
									</div>
									--}}
									
									<div class="col-sm-2 col-xs-12 search-col">
										<button class="btn btn-primary btn-search btn-block">
											<i class="icon-search"></i> <strong>{{ t('Find') }}</strong>
										</button>
									</div>
								 
								</div>
							</form>
						</div>
				</div> -->
			
			<div class="navbar-collapse collapse">
 
				<ul class="nav navbar-nav ml-auto navbar-right">

					 
					@if (!auth()->check() && !Cookie::get('user'))


						<li class="nav-item">
							@if (config('settings.security.login_open_in_modal'))
								<a href="#userLogin" class="nav-link login-nav" data-toggle="modal"><i class="fa fa-user"></i> {{ t('Sign In') }}</a>
							@else
								<a href="{{ lurl(trans('routes.login')) }}" class="nav-link"><i class="fa fa-user"></i> {{ t('Sign In') }}</a>
							@endif
						</li>
						<li class="nav-item">
							<a href="{{ lurl(trans('routes.register')) }}" class="nav-link"><i class="fa-user-plus fa"></i> Join Free</a>
						</li>
                                           {{-- <li class="nav-item">
							<div id="google_translate_element"></div>
						</li> --}}

					@elseif(!auth()->check()  && Cookie::get('user'))
						<li class="nav-item">
							@if (config('settings.security.login_open_in_modal'))
								<a href="#userLogin" class="nav-link login-nav" data-toggle="modal"><i class="fa fa-user"></i> {{ t('Log In') }}</a>
							@else
								<a href="{{ lurl(trans('routes.login')) }}" class="nav-link"><i class="fa fa-user"></i> {{ t('Log In') }}</a>
							@endif
						</li>
						<li class="nav-item dropdown no-arrow">

							

							<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
								<i class="fa fa-user hidden-sm"></i>
								<span>{{ $cookie_user->name }}</span>
								
								<span class="badge badge-pill badge-important count-conversations-with-new-messages hidden-sm">0</span>
								<i class="fa fa-caret-down hidden-sm"></i>
							</a>
							<ul id="userMenuDropdown" class="dropdown-menu user-menu dropdown-menu-right shadow-sm" >
								<li class="dropdown-item active">
									<a href="{{ lurl('account') }}">
										<i class="fa fa-home"></i> {{ t('Personal Home') }}
									</a>
								</li>

								@if($cookie_user->user_type_id!="2")
								<li class="dropdown-item"><a href="{{ lurl('account/my-posts') }}"><i class="fa fa-th-large"></i> {{ t('My ads') }} </a></li>
								<li class="dropdown-item"><a href="{{ lurl('account/favourite') }}"><i class="fa fa-heart"></i> {{ t('Favourite ads') }} </a></li>
								<li class="dropdown-item"><a href="{{ lurl('account/saved-search') }}"><i class="fa fa-search"></i> {{ t('Saved searches') }} </a></li>
								<li class="dropdown-item"><a href="{{ lurl('account/pending-approval') }}"><i class="fa fa-hourglass-half"></i> {{ t('Pending approval') }} </a></li>
								<li class="dropdown-item"><a href="{{ lurl('account/archived') }}"><i class="fa fa-folder"></i> {{ t('Archived ads') }}</a></li>
								<li class="dropdown-item">
									<a href="{{ lurl('account/conversations') }}">
										<i class="fa fa-envelop"></i> {{ t('Conversations') }}
										<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
									</a>
								</li>
								<li class="dropdown-item"><a href="{{ lurl('account/transactions') }}"><i class="fa fa-money"></i> {{ t('Transactions') }}</a></li>
								@else
									<li class="dropdown-item">
									<a href="{{ lurl('account/conversations') }}">
										<i class="fa fa-envelope"></i> {{ t('Conversations') }}
										<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
									</a>
								</li>
								@endif
								<li class="dropdown-item">
									<a data-toggle="modal"   href="#userLogin" class="login-nav">
										<i class="fa fa-sign-in"></i> Sign in as other user
									</a>
								</li>
							</ul>
						</li>
					@else 
						
						<li class="nav-item dropdown no-arrow">

							

							<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
								<i class="fa fa-user hidden-sm"></i>
								@if(auth()->user()->user_type_id=="1")
								<span>{{ auth()->user()->name }}</span>
								@else
								<span>{{ auth()->user()->first_name }} {{auth()->user()->last_name}}</span>
								@endif
								<span class="badge badge-pill badge-important count-conversations-with-new-messages hidden-sm">0</span>
								<i class="fa fa-caret-down hidden-sm"></i>
							</a>
							<ul id="userMenuDropdown" class="dropdown-menu user-menu dropdown-menu-right shadow-sm originl"> 
								<li class="dropdown-item active">
									<a href="{{ lurl('account') }}">
										<i class="fa fa-home"></i> Company Profile
									</a>
								</li>

								@if(auth()->user()->user_type_id=="1")
								<li class="dropdown-item"><a href="{{ lurl('account/my-posts') }}"><i class="fa fa-th-large"></i> {{ t('My ads') }} </a></li>
							<li class="dropdown-item"><a href="{{ lurl('account/my-groups') }}"><i class="fa fa-file"></i> Manage Product Categories </a></li>
								<li class="dropdown-item"><a href="{{ lurl('account/favourite') }}"><i class="fa fa-heart"></i> {{ t('Favourite ads') }} </a></li>
								<!--<li class="dropdown-item"><a href="{{ lurl('account/saved-search') }}"><i class="icon-star-circled"></i> {{ t('Saved searches') }} </a></li>-->
								<li class="dropdown-item"><a href="{{ lurl('account/pending-approval') }}"><i class="fa fa-hourglass-half"></i> {{ t('Pending approval') }} </a></li>
								<li class="dropdown-item"><a href="{{ lurl('account/archived') }}"><i class="fa fa-folder"></i> {{ t('Archived ads') }}</a></li>
								<li class="dropdown-item">
									<a href="{{ lurl('account/conversations') }}">
										<i class="fa fa-envelope"></i> My Leads
										<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
									</a>
								</li>
								<li class="dropdown-item"><a href="{{ lurl('account/transactions') }}"><i class="fa fa-money"></i> {{ t('Transactions') }}</a></li>
								@else
									<li class="dropdown-item">
									<a href="{{ lurl('account/conversations') }}">
										<i class="fa fa-envelope"></i> {{ t('Conversations') }}
										<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
									</a>
								</li>
								@endif
                                
								<li class="dropdown-item">
									@if (app('impersonate')->isImpersonating())
										<a href="{{ route('impersonate.leave') }}" class="">
											<i class="fa fa-sign-out "></i> {{ t('Leave') }}
										</a>
									@else
										<a href="{{ lurl(trans('routes.logout')) }}" class="">
											<i class="fa fa-sign-out"></i> {{ t('Log Out') }}
										</a>
									@endif
								</li>
							</ul>
						</li>
					@endif
					
					@if (config('plugins.currencyexchange.installed'))
						@include('currencyexchange::select-currency')
					@endif
					{{--
					<li class="nav-item postadd">
						@if (!auth()->check() && !Cookie::get('user'))
							@if (config('settings.single.guests_can_post_ads') != '1')
								<a class="btn btn-block btn-border btn-post btn-add-listing" href="#userLogin" data-toggle="modal">
									<i class="fa fa-plus-circle"></i> {{ t('Add Listing') }}
								</a>
							@else
								<a class="btn btn-block btn-border btn-post btn-add-listing" href="{{ lurl('posts/create') }}">
									<i class="fa fa-plus-circle"></i> {{ t('Add Listing') }}
								</a>
							@endif
						@elseif(!auth()->check() && Cookie::get('user'))
							@if($cookie_user->user_type_id!="2")

							<a class="btn btn-block btn-border btn-post btn-add-listing" href="{{ lurl('posts/create') }}">
								<i class="fa fa-plus-circle"></i> {{ t('Add Listing') }}
							</a>
							@else

							<a class="btn btn-block btn-border btn-post btn-add-listing" href="{{ lurl('account') }}">
								<i class="fa fa-home"></i> My Account
							</a>
							@endif
						@else
							@if(auth()->user()->user_type_id!="2")

							<a class="btn btn-block btn-border btn-post btn-add-listing" href="{{ lurl('posts/create') }}">
								<i class="fa fa-plus-circle"></i> {{ t('Add Listing') }}
							</a>
							@else

							<a class="btn btn-block btn-border btn-post btn-add-listing" href="{{ lurl('account') }}">
								<i class="fa fa-home"></i> My Account
							</a>
							@endif

						@endif
					</li>
					--}}
					
					@include('layouts.inc.menu.select-language')
					
				</ul>
			</div>
			
			
		</div>
	</nav>
</div>


 








