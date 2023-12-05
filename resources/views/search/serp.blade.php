{{--
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
--}}






<?php
   $fullUrl = url(request()->getRequestUri());
   $tmpExplode = explode('?', $fullUrl);
   $fullUrlNoParams = current($tmpExplode);
   ?>
@extends('newlayouts.app')
@section('meta_title','')
@section('meta_keywords','')
@section('meta_description','')
@section('meta_image')
content="{{ Request::root() }}/images/logo-2.png"\
@endsection
@section('search')
@parent
{{-- @include('search.inc.form') --}}
@endsection
@section('content')

<style>
    .row.bor.mga {
    border: 1px solid #50b3a3;
    box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
}
.maint img {
    min-width: 237px;
}
button.lyo:hover {
    background: #403201;
    color: #fff!important;
}
button.lyo {
    border-radius: 12px;
    border: 2px solid snow;
    background: #29b5d2;
    color: #fdfdfd;
    font-size: 15px;
    padding: 3px;
}
.lefn h3 {
    text-transform: capitalize;
}
button.hex:hover {
    background: red;
    border: 1px solid red;

}
button.hex {
     border: 2px solid #499344;
    background: #4e984c;
    color: #fff;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 400;
}

</style>

<div class="main-content">
   <div class="listing-page-block">
      <div class="container-fluid">
         @include('search.inc.breadcrumbs')
         @include('search.inc.location')



         <section class="categorys">
            <div class="lemps">
               <div class="container-fluid">
                  <div class="row">



               <!-- Sidebar -->
               @if (config('settings.listing.left_sidebar'))
               @include('search.inc.sidebar')
               <?php $contentColSm = 'col-md-9'; ?>
               @else
               <?php  $contentColSm = 'col-md-12'; ?>
               @endif



               <!-- Content -->
               <div class="col-lg-9 col-md-12 order-first order-md-1">

                  <div>
                     <!-- <div class="col-md-12 page-content col-thin-left listing-col-wrap ">  --->
                        <!--- 7. <div class="category-list{{ ($contentColSm == 'col-md-12') ? ' noSideBar' : '' }}">  --->
                           <div class="tab-box" style="visibility:hidden; max-height:0px;">
                              @if (Session::has('flash_notification'))
                              @include('common.spacer')
                              <?php $paddingTopExists = true; ?>
                              <div class="container">
                                 <div class="row">
                                    <div class="col-xl-12">
                                       @include('flash::message')
                                    </div>
                                 </div>
                              </div>
                              <?php Session::forget('flash_notification.message'); ?>
                              @endif
                              <!-- Nav tabs -->
                              <!--- <ul id="postType" class="nav nav-tabs add-tabs tablist" style="visibility:hidden; max-height:0px;" role="tablist">
                                 <?php
                                    $liClass = 'nav-item';
                                    $spanClass = 'alert-danger';
                                    if (!request()->filled('type') or request()->get('type') == '') {
                                        $liClass = 'class="active nav-item"';
                                        $spanClass = 'badge-danger';
                                    }
                                    ?>
                                 <li {!! $liClass !!} style="visibility:hidden; max-height:0px;">
                                 <a href="{!! qsurl($fullUrlNoParams, request()->except(['page', 'type'])) !!}" role="tab" data-toggle="tab" class="nav-link">
                                 {{ t('All Ads') }} <span class="badge badge-pill {!! $spanClass !!}">{{ $count->get('all') }}</span>
                                 </a>
                                 </li>
                                 @if (!empty($postTypes))
                                 @foreach ($postTypes as $postType)
                                 <?php
                                    $postTypeUrl = qsurl($fullUrlNoParams, array_merge(request()->except(['page']), ['type' => $postType->tid]));
                                    $postTypeCount = ($count->has($postType->tid)) ? $count->get($postType->tid) : 0;
                                    ?>
                                 @if (request()->filled('type') && request()->get('type') == $postType->tid)
                                 <li class="active nav-item" style="visibility:hidden; max-height:0px;">
                                    <a href="{!! $postTypeUrl !!}" role="tab" data-toggle="tab" class="nav-link">
                                    {{ $postType->name }}
                                    <span class="badge badge-pill badge-danger">
                                    {{ $postTypeCount }}
                                    </span>
                                    </a>
                                 </li>
                                 @else
                                 <li class="nav-item" style="visibility:hidden; max-height:0px;">
                                    <a href="{!! $postTypeUrl !!}" role="tab" data-toggle="tab" class="nav-link">
                                    {{ $postType->name }}
                                    <span class="badge badge-pill alert-danger">
                                    {{ $postTypeCount }}
                                    </span>
                                    </a>
                                 </li>
                                 @endif
                                 @endforeach
                                 @endif
                              </ul> --->
                              {{--
                              <div class="tab-filter">
                                 <select id="orderBy" title="sort by" class="niceselecter select-sort-by" data-style="btn-select" data-width="auto">
                                    <option value="{!! qsurl($fullUrlNoParams, request()->except(['orderBy', 'distance'])) !!}">{{ t('Sort by') }}</option>
                                    <option{{ (request()->get('orderBy')=='priceAsc') ? ' selected="selected"' : '' }}
                                    value="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'priceAsc'])) !!}">
                                    {{ t('Price : Low to High') }}
                                    </option>
                                    <option{{ (request()->get('orderBy')=='priceDesc') ? ' selected="selected"' : '' }}
                                    value="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'priceDesc'])) !!}">
                                    {{ t('Price : High to Low') }}
                                    </option>
                                    <option{{ (request()->get('orderBy')=='relevance') ? ' selected="selected"' : '' }}
                                    value="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'relevance'])) !!}">
                                    {{ t('Relevance') }}
                                    </option>
                                    <option{{ (request()->get('orderBy')=='date') ? ' selected="selected"' : '' }}
                                    value="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'date'])) !!}">
                                    {{ t('Date') }}
                                    </option>
                                    @if (isset($isCitySearch) and $isCitySearch and \App\Helpers\DBTool::checkIfMySQLFunctionExists(config('larapen.core.distanceCalculationFormula')))
                                    @for($iDist = 0; $iDist <= config('settings.listing.search_distance_max', 500); $iDist += config('settings.listing.search_distance_interval', 50))
                                    <option{{ (request()->get('distance', config('settings.listing.search_distance_default', 100))==$iDist) ? ' selected="selected"' : '' }}
                                    value="{!! qsurl($fullUrlNoParams, array_merge(request()->except('distance'), ['distance' => $iDist])) !!}">
                                    {{ t('Around :distance :unit', ['distance' => $iDist, 'unit' => unitOfLength()]) }}
                                    </option>
                                    @endfor
                                    @endif
                                 </select>
                              </div>
                              --}}
                           </div>
                           <!--  <div class="listing-filter">
                              <div class="pull-left col-xs-6">
                                 <div class="breadcrumb-list">
                                    {!! (isset($htmlTitle)) ? $htmlTitle : '' !!}
                                 </div>
                                 <div style="clear:both;"></div>
                              </div>
                              {{--
                              @if ($paginator->getCollection()->count() > 0)
                              <div class="pull-right col-xs-6 text-right listing-view-action">
                                 <span class="list-view"><i class="icon-th"></i></span>
                                 <span class="compact-view"><i class="icon-th-list"></i></span>
                                 <span class="grid-view active"><i class="icon-th-large"></i></span>
                              </div>
                              @endif
                              --}}
                              <div style="clear:both"></div>
                           </div> -->
                           <!-- Mobile Filter bar -->
                           {{--
                           <div class="mobile-filter-bar col-xl-12">
                              <ul class="list-unstyled list-inline no-margin no-padding">
                                 <li class="filter-toggle">
                                    <a class="">
                                    <i class="icon-th-list"></i> {{ t('Filters') }}
                                    </a>
                                 </li>
                                 <li>
                                    <div class="dropdown">
                                       <a data-toggle="dropdown" class="dropdown-toggle">{{ t('Sort by') }}</a>
                                       <ul class="dropdown-menu">
                                          <li>
                                             <a href="{!! qsurl($fullUrlNoParams, request()->except(['orderBy', 'distance'])) !!}" rel="nofollow">
                                             {{ t('Sort by') }}
                                             </a>
                                          </li>
                                          <li>
                                             <a href="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'priceAsc'])) !!}" rel="nofollow">
                                             {{ t('Price : Low to High') }}
                                             </a>
                                          </li>
                                          <li>
                                             <a href="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'priceDesc'])) !!}" rel="nofollow">
                                             {{ t('Price : High to Low') }}
                                             </a>
                                          </li>
                                          <li>
                                             <a href="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'relevance'])) !!}" rel="nofollow">
                                             {{ t('Relevance') }}
                                             </a>
                                          </li>
                                          <li>
                                             <a href="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'date'])) !!}" rel="nofollow">
                                             {{ t('Date') }}
                                             </a>
                                          </li>
                                          @if (isset($isCitySearch) and $isCitySearch and \App\Helpers\DBTool::checkIfMySQLFunctionExists(config('larapen.core.distanceCalculationFormula')))
                                          @for($iDist = 0; $iDist <= config('settings.listing.search_distance_max', 500); $iDist += config('settings.listing.search_distance_interval', 50))
                                          <li>
                                             <a href="{!! qsurl($fullUrlNoParams, array_merge(request()->except('distance'), ['distance' => $iDist])) !!}" rel="nofollow">
                                             {{ t('Around :distance :unit', ['distance' => $iDist, 'unit' => unitOfLength()]) }}
                                             </a>
                                          </li>
                                          @endfor
                                          @endif
                                       </ul>
                                    </div>
                                 </li>
                              </ul>
                           </div>
                           --}}
                          <!--  <div class="menu-overly-mask"></div>  --->
                           <!-- Mobile Filter bar End-->
                           <!-- 2. <div class="adds-wrapper"> -->
                             <!-- Top -->
                             @include('search.inc.posts')
                             <!-- Bottom -->
                           <!-- 2. </div> -->
                           <!-- <div class="form-group">
                              <div class="col-md-12 text-center mt-4">
                                 <button id="loadMore" class="btn btn-primary btn-lg submitPostForm" onclick="loadMore()"> Loading .... </button>
                              </div>
                           </div> -->
                           <div class="tab-box save-search-bar text-center">
                              @if (request()->filled('q') and request()->get('q') != '' and $count->get('all') > 0)
                              <a name="{!! qsurl($fullUrlNoParams, request()->except(['_token', 'location'])) !!}" id="saveSearch"
                                 count="{{ $count->get('all') }}">
                              <i class="icon-star-empty"></i> {{ t('Save Search') }}
                              </a>
                              @else
                              <a href="#"> &nbsp; </a>
                              @endif
                           </div>

                           <div class="col-lg-12">
                               <div class="description">
                           <p>

                           @if(isset($bottom_text))
                           {!! $bottom_text !!}
                           @endif
                         </p>
                       </div>
                     </div>



                        <!--- 7. </div>  --->
                        {{--
                        <div class="post-promo text-center mb-5">
                           <h2> {{ t('Do have anything to sell or rent?') }} </h2>
                           <h5>{{ t('Sell your products and services online FOR FREE. It\'s easier than you think !') }}</h5>
                           @if (!auth()->check() and config('settings.single.guests_can_post_ads') != '1')
                           <a href="#quickLogin" class="btn btn-border btn-post btn-add-listing" data-toggle="modal">{{ t('Start Now!') }}</a>
                           @else
                           <a href="{{ lurl('posts/create') }}" class="btn btn-border btn-post btn-add-listing">{{ t('Start Now!') }}</a>
                           @endif
                        </div>
                        --}}
                     <!-- 1. </div>  --->

                  </div>

                    <div class="row">
                      <div class="col-md-12">
                          <div class="listing_company">
                             <h4></h4>
                         </div>
                      </div>
                        @if(isset($listingBanners))
                        @foreach($listingBanners as $banner)
                        <div class="col-md-3">
                        @if(!empty($banner->user))
                        <div class="banners-ads">
                           <?php $attr = ['countryCode' => config('country.icode'), 'username' => empty($banner->user->username) ? 'Guest' : $banner->user->username]; ?>
                           <div class="ads-img"><a target="_blank" href="{{ lurl(trans('routes.v-search-username', $attr), $attr) }}"><img alt="{{$banner->user->name}}" src="/storage/{{$banner->filename}}" loading="lazy" width="320px" height="320px"></a></div>
                        </div></div>
                        @endif
                        @endforeach
                        @endif


                 </div>

                 <br><br><br>
               </div>
               <div style="clear:both;"></div>
               <!-- Advertising -->
            </div>
         </div>
      </div>
   </div>
   <?php if(isset($subCat)){
      $keywords = "Looking for ".$subCat->name;
                     $sub = $subCat->name;
      }
      else if(isset($cat)){
      $keywords = "Looking for ".$cat->name;
                      $sub = $cat->name;

      }
      ?>
   @if(isset($sub))
   <section class="sub" style="">
      <div class="container">
         <h4 style="font-size: 28px;margin-bottom: 20px;text-align: center;font-weight: bold;text-transform: uppercase;">Popular Cities</h4>
         <div class="row justify-content-center">
            <ul class="list">
              @if(!empty($cities))
               @foreach ($cities as $_city)
               <li style="display: inline-block;">
                  <?php
                     $attr = ['countryCode' => config('country.icode'),'id'=>$_city->id,'city'=>slugify($_city->name)];
                     if(isset($cat)){
                     	if(isset($subCat)){
                     	$attr['catSlug'] = $subCat->slug;

                     	}
                     	else if($cat){
                     		$attr['catSlug'] = $cat->slug;

                     	}

                     	$fullUrlLocation = lurl(trans('routes.search-cat-location', $attr), $attr);
                     }
                     else{

                     	$fullUrlLocation = lurl(trans('routes.search-city', $attr), $attr);
                     }

                     $locationParams = [
                     	'l'  => $_city->id,
                     	'r'  => '',
                     	'c'  => (isset($cat)) ? $cat->tid : '',
                     	'sc' => (isset($subCat)) ? $subCat->tid : '',
                     ];
                     if(preg_match('/\/search/', $fullUrlNoParams)){

                     	//$fullUrlLocation =qsurl($fullUrlLocation, array_merge(request()->except(['page','l'] + array_keys($locationParams)), $locationParams));
                     	$fullUrlLocation =qsurl($fullUrlLocation);

                     }
                      ?>
                  @if ((isset($uriPathCityId) and $uriPathCityId == $_city->id) or (isset($city) and $city->id==$_city->id))
                  <a style="display: inline-block;color: #606060;font-size: 12px !important;font-weight: 200;padding: 0 4px 0 0;text-decoration: none;" href="{!! $fullUrlLocation !!}" title="{{ $_city->name }}">
                  {{$sub}} in {{ $_city->name }}
                  </a>
                  @else
                  <a style="display: inline-block;color: #606060;font-size: 12px !important;font-weight: 200;padding: 0 4px 0 0;text-decoration: none;" href="{!! $fullUrlLocation !!}" title="{{ $_city->name }}">
                  {{$sub}} in {{ $_city->name }}
                  </a>
                  @endif
               </li>
               @endforeach
              @endif
            </ul>
         </div>
      </div>
   </section>
   @endif
</div>




</div>
</div>
</div>
</section>




@endsection
@section('modal_location')
@include('layouts.inc.modal.location')
@endsection
@section('modal_message')
@include('search.inc.compose-message')
@include('search.inc.slider-message')
@endsection
@section('after_scripts')
<script src="{{ url('assets/js/readmore.min.js') }}" type="text/javascript"></script>
<script>
   var ajax = false;
   var enableAjax = true;
   var searchParams = new URLSearchParams(window.location.search);

   @if(isset($city) && $city)

    	if(window.location.pathname=="/search"){



   	    searchParams.set("l", '{{$city->id}}');
   	    searchParams.set("location", '{{$city->name}}');
   	    window.history.pushState('Search','',window.location.origin+'/'+window.location.pathname+'?'+searchParams.toString());

    	}


   @endif
   var paginate = 1;
   $(document).ready(function () {

   		$( "#lSearch" ).change(function() {
   	  alert( "Handler for .change() called." );
   	});
   	$('.readmore').readmore({
   	speed: 100,
   	collapsedHeight: 100,
   	heightMargin: 16,
   	moreLink: '<a href="#">Read More</a>',
   	lessLink: '<a href="#">Close</a>',
   	embedCSS: true,
   	blockCSS: 'display: block; width: 100%;',
   	startOpen: false,
   	blockProcessed: function() {},
   	beforeToggle: function(){},
   	afterToggle: function(){}
   	});

   	$('#postType a').click(function (e) {
   		e.preventDefault();
   		var goToUrl = $(this).attr('href');
   		redirect(goToUrl);
   	});
   	$('#orderBy').change(function () {
   		var goToUrl = $(this).val();
   		redirect(goToUrl);
   	});





   	$(window).scroll(function (event) {
   		var windowScrollTop = $(this).scrollTop()+$(this).height();
   		var windowScrollBottom = $(document).height();
   		 if($(window).width()>768){
                     var scrollheight=6000;
                 }else{
                     var scrollheight=18000;
                 }
   		if(windowScrollBottom-windowScrollTop<scrollheight ){
   			loadMore(paginate);
   		}
   		else{
   			ajax = false;
   		}


   	});
   	$(document.body).on('touchmove', function onScroll(){
   		var windowScrollTop = $(document.body).scrollTop()+$(document.body).height();
   		var windowScrollBottom = $(document).height();
   		console.log(windowScrollBottom-windowScrollTop);
   		if(windowScrollBottom-windowScrollTop<800 ){
   			loadMore(paginate);
   		}
   		else{
   			ajax = false;
   		}
   	});
   });


   function loadMore(){

   	if(ajax == false && enableAjax == true){

   				ajax = true;

   				var listings=[];
   				$('.item-list').each(function(index){

   					listings.push($(this).data('id'));

   				});

   				$.ajax({
   					method: 'POST',
   					url: window.location.href+'?page='+paginate,
   					data:{
   						'view' :'ajax',
   						'listings_ids' : JSON.stringify(listings)
   					}

   				}).done(function(data) {

   					 if(data.match(/No result/g)){
   					 	enableAjax == false;
   					 	$('#loadMore').hide();

   					 }else{
   					 	 $('.adds-wrapper').append(data);
   						  paginate++;


   					 }

   					 ajax = false;

   				});

   			}
   }
</script>
<style>
   ul.list li a:after {
   content: "|";
   padding-left: 2px;
   padding-right: 1px;
   font-size: 15px;
   }
   .sub{
   background: #e6ddd4;
   padding: 33px 33px;
   }
.listing_company h4 {
    font-size: 31px;
    font-weight: bold;
}
.listing_company {
    text-align: center;
    margin: 40px 0px;
}
.main-content {
    padding-top: 100px;
}
</style>
@endsection
