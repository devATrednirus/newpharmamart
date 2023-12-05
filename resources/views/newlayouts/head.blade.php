<!-- header Part Start-->
  <header class="ps-header ps-header--1">
           <div class="ps-header__top">
                <div class="container">

                    <div class="ps-header__text">Need help? <strong> +91 9888885364</strong></div>
                </div>
            </div>
            <div class="ps-header__middle">
                <div class="container">
                    <div class="ps-logo"><a href="/"> <img src="/assets/images/jenus.jpg" alt><img class="sticky-logo" src="/assets/images/jenus.jpg" alt></a></div><a class="ps-menu--sticky" href="#"><i class="fa fa-bars"></i></a>

                    	@include('home.inc.search')


                    <div class="ps-header__right">
                        <ul class="ps-header__icons">
                            <li><a class="ps-header__item open-search" href="#"><i class="icon-magnifier"></i></a></li>

                        </ul>
                        <div class="ps-language-currency">
                        <!--    <div class="ps-header__search">-->
                        <!--    <form  method="post">-->
                        <!--        <div class="ps-search-table">-->
                        <!--            <div class="input-group">-->
                        <!--                <input class="form-control ps-input" type="text" placeholder="Search for products">-->
                        <!--                <div class="input-group-append"><a href="#"><i class="fa fa-search"></i></a></div>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </form>-->
                        <!--</div>-->
                        <div class="userlogin">
                            <!-- <ul>
                                <li><a href="/admin"><i class="fa fa-user" aria-hidden="true"></i>
 Sign in</a></li>
                                <li><a href="/createaccount"> <i class="fa fa-user-plus" aria-hidden="true"></i>
 Join free</a></li>
</ul>  --->
<nav class="ps-main-menu" >


                            <ul class="menu">


                    					@if (!auth()->check() && !Cookie::get('user'))


                    						<li class="has-mega-menu">
                    							@if (config('settings.security.login_open_in_modal'))
                    								<a href="#userLogin" class="nav-link login-nav" data-toggle="modal"><i class="fa fa-user"></i> {{ t('Sign In') }}</a>
                    							@else
                    								<a href="{{ lurl(trans('routes.login')) }}" class="nav-link"><i class="fa fa-user"></i> {{ t('Sign In') }}</a>
                    							@endif
                    						</li>
                    						<li class="has-mega-menu">
                    							<a href="{{ lurl(trans('routes.register')) }}" class="nav-link"><i class="fa-user-plus fa"></i> Join Free</a>


                    					@elseif(!auth()->check()  && Cookie::get('user'))
                    						<li class="has-mega-menu">
                    							@if (config('settings.security.login_open_in_modal'))
                    								<a href="#userLogin" class="nav-link login-nav" data-toggle="modal"><i class="fa fa-user"></i> {{ t('Log In') }}</a>
                    							@else
                    								<a href="{{ lurl(trans('routes.login')) }}" class="nav-link"><i class="fa fa-user"></i> {{ t('Log In') }}</a>
                    							@endif
                    						</li>
                    						<li class="has-mega-menu">



                    							<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                    								<i class="fa fa-user hidden-sm"></i>
                    								<span>{{ $cookie_user->name }}</span>

                    								<span class="badge badge-pill badge-important count-conversations-with-new-messages hidden-sm">0</span>
                    								<!-- <i class="fa fa-caret-down hidden-sm"></i>  -->
                    							</a>
                                  <div class="mega-menu"><div class="container"><div class="mega-menu__row"><div class="mega-menu__column">
                    							<ul  class="sub-menu--mega" >
                    								<li >
                    									<a href="{{ lurl('account') }}">
                    										<i class="fa fa-home"></i> {{ t('Personal Home') }}
                    									</a>
                    								</li>

                    								@if($cookie_user->user_type_id!="2")
                    								<li ><a href="{{ lurl('account/my-posts') }}"><i class="fa fa-th-large"></i> {{ t('My ads') }} </a></li>
                    								<li ><a href="{{ lurl('account/favourite') }}"><i class="fa fa-heart"></i> {{ t('Favourite ads') }} </a></li>
                    								<li ><a href="{{ lurl('account/saved-search') }}"><i class="fa fa-search"></i> {{ t('Saved searches') }} </a></li>
                    								<li ><a href="{{ lurl('account/pending-approval') }}"><i class="fa fa-hourglass-half"></i> {{ t('Pending approval') }} </a></li>
                    								<li ><a href="{{ lurl('account/archived') }}"><i class="fa fa-folder"></i> {{ t('Archived ads') }}</a></li>
                    								<li >
                    									<a href="{{ lurl('account/conversations') }}">
                    										<i class="fa fa-envelop"></i> {{ t('Conversations') }}
                    										<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
                    									</a>
                    								</li>
                    								<li ><a href="{{ lurl('account/transactions') }}"><i class="fa fa-money"></i> {{ t('Transactions') }}</a></li>
                    								@else
                    									<li >
                    									<a href="{{ lurl('account/conversations') }}">
                    										<i class="fa fa-envelope"></i> {{ t('Conversations') }}
                    										<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
                    									</a>
                    								</li>
                    								@endif
                    								<li >
                    									<a data-toggle="modal"   href="#userLogin" class="login-nav">
                    										<i class="fa fa-sign-in"></i> Sign in as other user
                    									</a>
                    								</li>
                    							</ul> </div></div></div></div>
                    						</li>
                    					@else

                    						<li class="has-mega-menu">



                    							<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                    								<i class="fa fa-user hidden-sm"></i>
                    								@if(auth()->user()->user_type_id=="1")
                    								<span>{{ auth()->user()->name }}</span>
                    								@else
                    								<span>{{ auth()->user()->first_name }} {{auth()->user()->last_name}}</span>
                    								@endif
                    								<span class="badge badge-pill badge-important count-conversations-with-new-messages hidden-sm">0</span>
                    								<!-- <i class="fa fa-caret-down hidden-sm"></i> -->
                    							</a>
                                  <div class="mega-menu"><div class="container"><div class="mega-menu__row"><div class="mega-menu__column">
                    							<ul  class="sub-menu--mega">
                    								<li >
                    									<a href="{{ lurl('account') }}">
                    										<i class="fa fa-home"></i> Company Profile
                    									</a>
                    								</li>

                    								@if(auth()->user()->user_type_id=="1")
                    								<li ><a href="{{ lurl('account/my-posts') }}"><i class="fa fa-th-large"></i> {{ t('My ads') }} </a></li>
                    							<li ><a href="{{ lurl('account/my-groups') }}"><i class="fa fa-file"></i> Manage Product Categories </a></li>
                    								<li ><a href="{{ lurl('account/favourite') }}"><i class="fa fa-heart"></i> {{ t('Favourite ads') }} </a></li>
                    								<!--<li class="dropdown-item"><a href="{{ lurl('account/saved-search') }}"><i class="icon-star-circled"></i> {{ t('Saved searches') }} </a></li>-->
                    								<li ><a href="{{ lurl('account/pending-approval') }}"><i class="fa fa-hourglass-half"></i> {{ t('Pending approval') }} </a></li>
                    								<li ><a href="{{ lurl('account/archived') }}"><i class="fa fa-folder"></i> {{ t('Archived ads') }}</a></li>
                    								<li >
                    									<a href="{{ lurl('account/conversations') }}">
                    										<i class="fa fa-envelope"></i> My Leads
                    										<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
                    									</a>
                    								</li>
                    								<li ><a href="{{ lurl('account/transactions') }}"><i class="fa fa-money"></i> {{ t('Transactions') }}</a></li>
                    								@else
                    									<li >
                    									<a href="{{ lurl('account/conversations') }}">
                    										<i class="fa fa-envelope"></i> {{ t('Conversations') }}
                    										<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
                    									</a>
                    								</li>
                    								@endif

                    								<li>
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
                    							</ul> </div></div></div></div>
                    						</li>
                    					@endif

                    					@if (config('plugins.currencyexchange.installed'))
                    						@include('currencyexchange::select-currency')
                    					@endif


                    					@include('layouts.inc.menu.select-language')

                    				</ul>


                        </nav>



                      </div>

                    </div>
                </div>
            </div>
            <div class="ps-navigation">
                <div class="container">
                    <div class="ps-navigation__left">
                        <nav class="ps-main-menu">
                            <ul class="menu">
                                <li class="has-mega-menu"><a href="/">Home</a></li>
																<?php
																	$parents = DB::table('categories')->where('active','1')->limit(3)->orderBy('id', 'ASC')->get();   //->orderByRaw("(id <> '".$category->parent_id."')  ASC,name")
																?>

                                 <!-- <li class="has-mega-menu"><a href="#"> Categoriesddd<span class="sub-toggle"><i class="fa fa-chevron-down"></i></span></a>
                                    <div class="mega-menu">
                                        <div class="container">
                                            <div class="mega-menu__row">
                                                <div class="mega-menu__column">
                                                   <ul class="sub-menu--mega">
                                                       <li><a href="/pharma-pcd-company">Pharma PCD Company</a></li>
                                                       <li><a href="/pharma-franchise-company">	Pharma Franchise Company</a></li>
                                                       <li><a href="/pcd-pharma-franchise-company">	PCD Pharma Franchise Company</a></li>
                                                    </ul>
                                                </div>
                                                <div class="mega-menu__column">
                                                    <ul class="sub-menu--mega">
                                                       <li><a href="/pharma-contract-manufacturers">	pharma contract manufacturers</a></li>
                                                       <li><a href="/pcd-manufacturing-company">	PCD Manufacturing Company</a></li>
																											 <li><a href="/Third-Party-Manufacturers"> Third Party Manufacturers	</a></li>
                                                    </ul>
                                                </div>

                                            </div>
                                         </div>
                                    </div>
                                </li> --->


																@foreach($parents as $pa)
																<li class="has-mega-menu"><a href="#"><?php $ctname = substr($pa->name,0,18);  ?>  {{$ctname}}...<span class="sub-toggle"><i class="fa fa-chevron-down"></i></span></a>
																	 <div class="mega-menu">
																			 <div class="container">
																					 <div class="mega-menu__row">

																						 	  <?php $sub = DB::table('categories')->where('parent_id',$pa->id)->orderBy('id','ASC')->where('active','1')->get();
																									$ncols = floor($sub->count()/2);


																								 ?>
@foreach($sub as $k => $v)

<?php
$attr = [
  'countryCode' => config('country.icode'),
  'catSlug'     => $pa->slug,
  'subCatSlug'  => $v->slug
];
$searchUrl = lurl(trans('routes.search-subCat', $attr), $attr) ;
?>


																							 @if($loop->first || $ncols+1 == $k)<div class="mega-menu__column">  <ul class="sub-menu--mega"> @endif

																											<li><a href="{{$searchUrl}}">{{$v->name}}</a></li>

																							 @if($loop->last || $ncols == $k ) </ul> </div> @endif
@endforeach


																					 </div>
																				</div>
																	 </div>
															 </li>
															 @endforeach

                                <li class="has-mega-menu"><a href="/blog">Blog</a></li>
                                <li class="has-mega-menu"><a href="{{ lurl(trans('routes.contact')) }}">Contact Us</a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="ps-navigation__right"><a href="tel:91-9888885364">Need help? <strong>(  +91 9888885364)</strong></a></div>
                </div>
            </div>
        </header>
        <!--<header class="ps-header ps-header--1 ps-header--mobile">
            <div class="ps-header__middle">
                <div class="container">
                    <div class="ps-logo"><a href="/"> <img src="/assets/images/jenus.jpg" alt></a></div>
                    <div class="ps-header__right">
                        <ul class="ps-header__icons">
                            <li><a class="ps-header__item open-search" href="#"><i class="fa fa-search"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>-->
        <header class="ps-header ps-header--1 ps-header--mobile">
        <div class="topnav">

 <div class="ps-logo"><a href="/"> <img src="/assets/images/jenus.jpg" alt></a></div>
  <div id="myLinks">
    <a href="/">Home</a>
		<?php
			$parents = DB::table('categories')->where('active','1')->limit(3)->orderBy('id', 'ASC')->get();   //->orderByRaw("(id <> '".$category->parent_id."')  ASC,name")
		?>
	@foreach($parents as $pa)
     <button class="dropdown-btn">Categoriesfffff
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-container">
		<?php $sub = DB::table('categories')->where('parent_id',$pa->id)->orderBy('id','ASC')->where('active','1')->get(); ?>
@foreach($sub as $k => $v)
   <a href="#">{{$v->name}}</a>
@endforeach
  </div>
	@endforeach



   <a href="/blog">Blog</a>
    <a href="{{ lurl(trans('routes.contact')) }}">Contact Us</a>





  </div>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a>
</div>

<div style="padding-left:16px">



</div>

<!-- End smartphone / tablet look -->
</div>

</header>
