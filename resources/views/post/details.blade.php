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
@extends('layouts.master')
@section('search')
@parent
@include('search.inc.form')
@endsection
<?php
   // Phone
   
   $sUser = $user ;
   $phone = TextToImage::make($user->phone, config('larapen.core.textToImage'));
   $phoneLink = 'tel:' . $user->phone;
   $phoneLinkAttr = '';
   if (!auth()->check()) {
   	if (config('settings.single.guests_can_contact_ads_authors') != '1') {
   		$phone = t('Click to see');
   		$phoneLink = '#quickLogin';
   		$phoneLinkAttr = 'data-toggle="modal"';
   	}
   }
   
   // Contact Seller URL
   $contactSellerURL = '#contactUser';
   $class = 'send_message';
   if (!auth()->check()) {
   	if (config('settings.single.guests_can_contact_ads_authors') != '1') {
   		$contactSellerURL = '#quickLogin';
   		$class = '';
   
   	}
   }
   ?>
@section('content')
{!! csrf_field() !!}
<input type="hidden" id="post_id" value="{{ $post->id }}">
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
<div class="main-content">
   <div class="listing-details-block">
      <div class="container-fluid">
         <div class="inner-breadcrumb">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-md-12">

                     <nav aria-label="breadcrumb" role="navigation" class="pull-left">
                        <ol class="breadcrumb">
                           <li class="breadcrumb-item"><a href="{{ lurl('/') }}"><i class="icon-home fa"></i></a></li>
                           <li class="breadcrumb-item"><a href="{{ lurl('/') }}">{{ config('country.name') }}</a></li>
                           @if (!empty($post->category->parent))
                           <li class="breadcrumb-item">
                              <?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $post->category->parent->slug]; ?>
                              <a href="{{ lurl(trans('routes.v-search-cat', $attr)) }}">
                                 {{ $post->category->parent->name }}
                              </a>
                           </li>
                           @if ($post->category->parent->id != $post->category->id)
                           <li class="breadcrumb-item">
                              <?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $post->category->parent->slug, 'subCatSlug' => $post->category->slug]; ?>
                              <a href="{{ lurl(trans('routes.v-search-subCat', $attr)) }}">
                                 {{ $post->category->name }}
                              </a>
                           </li>
                           @endif
                           @else
                           <li class="breadcrumb-item">
                              <?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $post->category->slug]; ?>
                              <a href="{{ lurl(trans('routes.v-search-cat', $attr)) }}">
                                 {{ $post->category->name }}
                              </a>
                           </li>
                           @endif
                           <li class="breadcrumb-item active" aria-current="page">{{ str_limit($post->title, 70) }}</li>
                        </ol>
                     </nav>
                     <div class="pull-right backtolist">
                        <a href="{{ URL::previous() }}"><i class="fa fa-angle-double-left"></i> {{ t('Back to Results')
                           }}</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="listing-details-inner">
            <div class="listing-details-top">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-md-4 listing-big-img">
                        @if ($post->price && !in_array($post->category->type, ['not-salable']))
                        <div class="pricetag">
                           @if ($post->price > 0)
                           {!! \App\Helpers\Number::money($post->price) !!}
                           @else
                           {!! \App\Helpers\Number::money(' --') !!}
                           @endif
                        </div>
                        @endif
                        @if (count($post->pictures) > 0)
                        <ul class="bxslider">
                           @foreach($post->pictures as $key => $image)
                           <li><img src="{{ resize($image->filename, 'big') }}" alt="{{$post->title}} {{$key+1}}"></li>
                           @endforeach
                        </ul>
                        @if (count($post->pictures) > 1)
                        <div class="product-view-thumb-wrapper">
                           <ul id="bx-pager" class="product-view-thumb">
                              @foreach($post->pictures as $key => $image)
                              <li>
                                 <a class="thumb-item-link" data-slide-index="{{ $key }}" href="">
                                    <img src="{{ resize($image->filename, 'small') }}"
                                       alt="{{$post->title}} {{$key+1}}">
                                 </a>
                              </li>
                              @endforeach
                           </ul>
                        </div>
                        @endif
                        @else
                        <ul class="bxslider">
                           <li><img src="{{ resize(config('larapen.core.picture.default'), 'big') }}" alt="img"></li>
                        </ul>
                        <div class="product-view-thumb-wrapper">
                           <ul id="bx-pager" class="product-view-thumb">
                              <li>
                                 <a class="thumb-item-link" data-slide-index="0" href="">
                                    <img src="{{ resize(config('larapen.core.picture.default'), 'small') }}" alt="img">
                                 </a>
                              </li>
                           </ul>
                        </div>
                        @endif
                     </div>
                     <div class="col-md-5 listing-details-short">
                        <h2 class="enable-long-words">
                           <strong>
                              <?php $attr = ['slug' => slugify($post->title), 'id' => $post->id]; ?>
                              <a href="{{ lurl($post->uri, $attr) }}" title="{{ $post->title }}">
                                 {{ $post->title }}
                              </a>
                           </strong>
                           @if ($post->featured==1 and !empty($post->latestPayment))
                           @if (isset($post->latestPayment->package) and !empty($post->latestPayment->package))
                           <i class="icon-ok-circled tooltipHere"
                              style="color: {{ $post->latestPayment->package->ribbon }};" title=""
                              data-placement="right" data-toggle="tooltip"
                              data-original-title="{{ $post->latestPayment->package->short_name }}"></i>
                           @endif
                           @endif
                        </h2>
                        <span class="info-row">
                           <span class="category">{{ (!empty($post->category->parent)) ? $post->category->parent->name :
                              $post->category->name }}</span> -&nbsp;
                           <span class="item-location"><i class="fas fa-map-marker-alt"></i> {{ $post->city->name }}
                           </span> <!-- -&nbsp;
                              <span class="category">
                              	<i class="icon-eye-3"></i>&nbsp;
                              	{{ \App\Helpers\Number::short($post->visits) }} {{ trans_choice('global.count_views', getPlural($post->visits)) }}
                              </span> -->
                        </span>
                        <div class="listing-details-table">
                           {{-- <span class="price-item">20,000/ Unit</span> <a href="#">Get Latest Price</a> --}}
                           <ul class="nav nav-tabs">
                              @if (config('plugins.reviews.installed'))
                              <li class="nav-item">
                                 <a class="nav-link" href="#tab-{{ config('plugins.reviews.name') }}" data-toggle="tab">
                                    <h4>
                                       {{ trans('reviews::messages.Reviews') }}
                                       @if (isset($rvPost) and !empty($rvPost))
                                       ({{ $rvPost->rating_count }})
                                       @endif
                                    </h4>
                                 </a>
                              </li>
                              @endif
                           </ul>
                           <!-- Tab panes -->
                           <div class="content-details" style="padding-top:10px; padding-bottom:10px;">
                              <div class="content-details-inner" id="tab-details">
                                 <div class="row">
                                    <div
                                       class="ads-details-info col-md-12 col-sm-12 col-xs-12 enable-long-words from-wysiwyg">
                                       <!-- Description -->
                                       <div class="col-12">
                                          {!!$post->short_description!!}
                                       </div>
                                       <!-- Custom Fields -->
                                       @include('post.inc.fields-values')
                                       <!-- Tags -->
                                       @if (!empty($post->tags))
                                       <?php $tags = explode(',', $post->tags); ?>
                                       @if (!empty($tags))
                                       <div class="row">
                                          <div class="tags col-12">
                                             <h4><i class="icon-tag"></i> {{ t('Tags') }}:</h4>
                                             @foreach($tags as $iTag)
                                             <?php $attr = ['countryCode' => config('country.icode'), 'tag' => slugify($iTag)]; ?>
                                             <a href="{{ lurl(trans('routes.v-search-tag', $attr), $attr) }}">
                                                {{ $iTag }}
                                             </a>
                                             @endforeach
                                          </div>
                                       </div>
                                       @endif
                                       @endif
                                       <!-- Actions -->
                                       {{--
                                       <div class="row detail-line-action text-center hidden">
                                          <div class="col-4">
                                             @if (auth()->check())
                                             @if (auth()->user()->id == $post->user_id)
                                             <a href="{{ lurl('posts/' . $post->id . '/edit') }}">
                                                <i class="icon-pencil-circled tooltipHere" data-toggle="tooltip"
                                                   data-original-title="{{ t('Edit') }}"></i>
                                             </a>
                                             @else
                                             @if ($user->email != '')
                                             <a class="{{ $class }}" data-toggle="modal" href="{{ $contactSellerURL }}">
                                                <i class="icon-mail-2 tooltipHere" data-toggle="tooltip"
                                                   data-original-title="Submit Query"></i>
                                             </a>
                                             @else
                                             <i class="icon-mail-2" style="color: #dadada"></i>
                                             @endif
                                             @endif
                                             @else
                                             @if ($user->email != '')
                                             <a class="{{ $class }}" data-toggle="modal" href="{{ $contactSellerURL }}">
                                                <i class="icon-mail-2 tooltipHere" data-toggle="tooltip"
                                                   data-original-title="Submit Query"></i>
                                             </a>
                                             @else
                                             <i class="icon-mail-2" style="color: #dadada"></i>
                                             @endif
                                             @endif
                                          </div>
                                          <div class="col-4">
                                             <a class="make-favorite" id="{{ $post->id }}" href="javascript:void(0)">
                                                @if (auth()->check())
                                                @if (\App\Models\SavedPost::where('user_id',
                                                auth()->user()->id)->where('post_id', $post->id)->count() > 0)
                                                <i class="fa fa-heart tooltipHere" data-toggle="tooltip"
                                                   data-original-title="{{ t('Remove favorite') }}"></i>
                                                @else
                                                <i class="far fa-heart" class="tooltipHere" data-toggle="tooltip"
                                                   data-original-title="{{ t('Save ad') }}"></i>
                                                @endif
                                                @else
                                                <i class="far fa-heart" class="tooltipHere" data-toggle="tooltip"
                                                   data-original-title="{{ t('Save ad') }}"></i>
                                                @endif
                                             </a>
                                          </div>
                                          <div class="col-4">
                                             <a href="{{ lurl('posts/' . $post->id . '/report') }}">
                                                <i class="fa icon-info-circled-alt tooltipHere" data-toggle="tooltip"
                                                   data-original-title="{{ t('Report abuse') }}"></i>
                                             </a>
                                          </div>
                                       </div>
                                       --}}
                                    </div>
                                 </div>
                              </div>
                              @if (config('plugins.reviews.installed'))
                              @if (view()->exists('reviews::comments'))
                              @include('reviews::comments')
                              @endif
                              @endif
                           </div>
                           {{--
                           <table class="table">
                              <tbody>
                                 <tr>
                                    <td>Distribution Preferred:</td>
                                    <td>Single Party Distribution</td>
                                 </tr>
                                 <tr>
                                    <td>Medicine Type:</td>
                                    <td> Ayurvedic, Homeopathic, Allopathic</td>
                                 </tr>
                                 <tr>
                                    <td>Minimum Order Value:</td>
                                    <td>25000</td>
                                 </tr>
                                 <tr>
                                    <td>Promotional Material:</td>
                                    <td>Working Bag, Writing Pads, Dairies, Diaries, Visiting Cards</td>
                                 </tr>
                                 <tr>
                                    <td>Form Of Medicine:</td>
                                    <td> Tablets, Capsules, Liquid Orals, Powders, Ointments, and sachets.</td>
                                 </tr>
                                 <tr>
                                    <td>Grade Standard:</td>
                                    <td>Medicine</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- /.tab content -->
                           <a class="complete-des" href="#">View Complete Details</a> --}}
                           <div class="buttons-group">
                              @if (auth()->check())
                              @if (auth()->user()->id == $post->user_id)
                              <a class="btn btn-primary" href="{{ lurl('posts/' . $post->id . '/edit') }}"><i
                                    class="fa fa-pencil-square-o"></i> {{ t('Edit') }}</a>
                              @else
                              @if ($user->email != '')
                              <a class="btn btn-primary {{ $class }}" data-id="{{ $post->id }}" data-toggle="modal"
                                 href="{{ $contactSellerURL }}"><i class="icon-mail-2"></i> Submit Query</a>
                              @endif
                              @endif
                              @else
                              @if ($user->email != '')
                              <a class="btn btn-primary {{ $class }}" data-id="{{ $post->id }}" data-toggle="modal"
                                 href="{{ $contactSellerURL }}"><i class="icon-mail-2"></i> Submit Query </a>
                              @endif
                              @endif
                              @if ($user->phone_hidden != 1 and !empty($user->phone))
                              <a href="{{ $phoneLink }}" {!! $phoneLinkAttr !!} class="btn btn-primary">
                                 <i class="icon-phone-1"></i>
                                 {{$user->phone}} </a>
                              @endif
                              @if($post->brochure)
                              <a class="btn btn-primary" target="_blank"
                                 href="{{ lurl('storage/' . $post->brochure) }}"><i class="fa fa-file-pdf"
                                    aria-hidden="true"></i> Download List</a>
                              @endif
                           </div>
                           {{--
                           <div class="row">
                              <div class="col-12 ads-details">
                                 <div class="tab-content p-3 mb-3" style="border-top: 1px solid #ddd;">
                                    <div class="tab-pane active table-responsive">
                                       {!! transformDescription($post->description) !!}
                                    </div>
                                 </div>
                              </div>
                           </div>
                           --}}
                        </div>
                     </div>
                     <div class="col-lg-3 company-profile-main">

                        <div class="com-pro-inner">
                           <div class="cover-photo"></div>

                           @if (auth()->check() and auth()->user()->getAuthIdentifier() == $post->user_id)
                           <div class="card-header">{{ t('Manage Ad') }}</div>
                           @else
                           <div class="small-logo">
                              @if (!empty($userPhoto))
                              <img src="{{ $userPhoto }}" alt="{{ $post->user->name }}">

                              @else
                              <img src="{{ url('images/user.jpg') }}" alt="{{ $post->user->name }}">
                              @endif
                           </div>
                           <div class="box-inner-padding">
                              <h5 class="title">Contact Supplier</h5>
                              <h2 class="name-com">
                                 @if (isset($user) and !empty($user))
                                 <?php $attr = ['countryCode' => config('country.icode'), 'username' => $user->username]; ?>
                                 <a target="_blank" href="{{ lurl(trans('routes.v-search-username', $attr), $attr) }}">
                                    {{ $post->user->name }}
                                 </a>
                                 @else
                                 {{ $post->user->name }}
                                 @endif
                              </h2>
                              @if (config('plugins.reviews.installed'))
                              @if (view()->exists('reviews::ratings-user'))
                              @include('reviews::ratings-user')
                              @endif
                              @endif
                              <div class="card-address" style="padding-bottom:20px;">
                                 @if($post->user->city)
                                 <span class="add-com"> {{$post->user->address1}}, {{$post->user->address2}},
                                    {{$post->user->city->name}} {{($post->user->city->subAdmin1 &&
                                    $post->user->city->name!=$post->user->city->subAdmin1->name)?$post->user->city->subAdmin1->name:''}}
                                    {{$post->user->pincode}}</span>
                                 @endif
                                 <?php $evActionStyle = 'style="border-top: 0;"'; ?>
                                 @if (!auth()->check() or (auth()->check() and auth()->user()->getAuthIdentifier() !=
                                 $post->user_id))
                                 <?php $attr = ['countryCode' => config('country.icode'), 'city' => slugify($post->city->name), 'id' => $post->city->id]; ?>
                                 {{--
                                 <div class="card-body text-left">
                                    <div class="grid-col">
                                       <div class="col from">
                                          <i class="fas fa-map-marker-alt"></i>
                                          <span>{{ t('Location') }}</span>
                                       </div>
                                       <div class="col to">
                                          <span>
                                             <a href="{!! lurl(trans('routes.v-search-city', $attr), $attr) !!}">
                                                {{ $post->city->name }}
                                             </a>
                                          </span>
                                       </div>
                                    </div>
                                 </div>
                                 --}}
                                 <?php $evActionStyle = 'style="border-top: 1px solid #ddd;"'; ?>
                                 @endif
                              </div>
                              <div style="padding:0px;" class="ev-action" {!! $evActionStyle !!}>
                                 @if (auth()->check())
                                 @if (auth()->user()->id == $post->user_id)
                                 <a href="{{ lurl('posts/' . $post->id . '/edit') }}" class="btn btn-default btn-block">
                                    <i class="fa fa-pencil-square-o"></i> {{ t('Update the Details') }}
                                 </a>
                                 <a href="{{ lurl('posts/' . $post->id . '/photos') }}"
                                    class="btn btn-default btn-block">
                                    <i class="icon-camera-1"></i> {{ t('Update Photos') }}
                                 </a>
                                 {{--
                                 @if ($post->featured=='1')
                                 <a href="#" class="btn btn-danger btn-block">
                                    <i class="icon-ok-circled2"></i> Premium Listing
                                 </a>
                                 @endif
                                 @if ($post->featured=='0' and (isset($countPackages) and isset($countPaymentMethods)
                                 and $countPackages > 0 and $countPaymentMethods > 0) )
                                 <a href="{{ lurl('posts/' . $post->id . '/payment') }}"
                                    class="btn btn-success btn-block">
                                    <i class="icon-ok-circled2"></i> {{ t('Make It Premium') }}
                                 </a>
                                 @endif
                                 --}}
                                 @else
                                 @if ($user->email != '')
                                 <a href="{{ $contactSellerURL }}" data-toggle="modal"
                                    class="btn btn-default btn-block {{ $class }}" data-id="{{ $post->id }}">
                                    <i class="icon-mail-2"></i> Submit Query
                                 </a>
                                 @endif
                                 @if ($user->phone_hidden != 1 and !empty($user->phone))
                                 <a href="{{ $phoneLink }}" {!! $phoneLinkAttr !!}
                                    class="btn btn-success btn-block showphone">
                                    <i class="icon-phone-1"></i>
                                    {{$user->phone}}
                                 </a>
                                 @endif
                                 @endif
                                 @else
                                 @if ($user->email != '')
                                 <a href="{{ $contactSellerURL }}" data-toggle="modal"
                                    class="btn btn-primary view-mobile {{ $class }}" data-id="{{ $post->id }}">
                                    <i class="icon-mail-2"></i> Submit Query
                                 </a>
                                 @endif
                                 @if ($user->phone_hidden != 1 and !empty($user->phone))
                                 <a href="{{ $phoneLink }}" {!! $phoneLinkAttr !!} class="btn btn-primary view-mobile">
                                    <i class="icon-phone-1"></i>
                                    {!! $phone !!}{{-- t('View phone') --}}
                                 </a>
                                 @endif
                                 @endif
                              </div>
                           </div>
                           @endif
                           @if (config('settings.single.show_post_on_googlemap'))
                           <div class="card sidebar-card">
                              <div class="card-header">{{ t('Location\'s Map') }}</div>
                              <div class="card-content">
                                 <div class="card-body text-left p-0">
                                    <div class="ads-googlemaps">
                                       <iframe id="googleMaps" width="100%" height="250" frameborder="0" scrolling="no"
                                          marginheight="0" marginwidth="0" src=""></iframe>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @endif
                           @if (isVerifiedPost($post))
                           @include('layouts.inc.social.horizontal')
                           @endif
                           <div class="card sidebar-card safty-tips">
                              <div class="card-header">{{ t('Safety Tips for Buyers') }}</div>
                              <div class="card-content">
                                 <div class="card-body text-left">
                                    <ul class="list-check">
                                       <li> {{ t('Meet seller at a public place') }} </li>
                                       <li> {{ t('Check the item before you buy') }} </li>
                                       <li> {{ t('Pay only after collecting the item') }} </li>
                                    </ul>
                                    <?php $tipsLinkAttributes = getUrlPageByType('tips'); ?>
                                    @if (!str_contains($tipsLinkAttributes, 'href="#"') and
                                    !str_contains($tipsLinkAttributes, 'href=""'))
                                    <p>
                                       <a class="pull-right" {!! $tipsLinkAttributes !!}>
                                          {{ t('Know more') }}
                                          <i class="fa fa-angle-double-right"></i>
                                       </a>
                                    </p>
                                    @endif
                                 </div>
                              </div>
                           </div>
                           @if($post->video_link)
                           <div class="c-addresssss">
                              <iframe id="myvideo" width="100%" height="300px" src="{{$post->video_link}}"
                                 title="{{$post->title}}" frameborder="0"
                                 allow="accelerometer;autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                 allowfullscreen></iframe>
                           </div>

                           @endif
                           @if($sUser->corporate_video)
                           <div class="c-addresss">
                              <iframe id="myvideo" width="100%" height="40%" src="{{$sUser->corporate_video}}"
                                 title="{{$post->title}}" frameborder="0"
                                 allow="accelerometer;autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                 allowfullscreen></iframe>
                           </div>
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="pro-des-com-des">
               <div class="container">
                  <div class="row">
                     <div class="col-md-9 col-sm-9 pro-des-com-des-left">
                        <div class="smooth-scroll-tab">
                           <ul class="tab-item">
                              {{--
                              <li><a href="#productdetail">Product Details</a></li>
                              --}}
                              <li><a href="#companydetail">Company Details</a></li>
                           </ul>
                           {{--
                           <div id="productdetail">
                              <div class="listing-details-table">
                                 <h6 class="head-table">Product Specification</h6>
                                 <table class="table">
                                    <tbody>
                                       <tr>
                                          <td>Distribution Preferred:</td>
                                          <td>Single Party Distribution</td>
                                       </tr>
                                       <tr>
                                          <td>Medicine Type:</td>
                                          <td> Ayurvedic, Homeopathic, Allopathic</td>
                                       </tr>
                                       <tr>
                                          <td>Minimum Order Value:</td>
                                          <td>25000</td>
                                       </tr>
                                       <tr>
                                          <td>Promotional Material:</td>
                                          <td>Working Bag, Writing Pads, Dairies, Diaries, Visiting Cards</td>
                                       </tr>
                                       <tr>
                                          <td>Form Of Medicine:</td>
                                          <td> Tablets, Capsules, Liquid Orals, Powders, Ointments, and sachets.</td>
                                       </tr>
                                       <tr>
                                          <td>Grade Standard:</td>
                                          <td>Medicine</td>
                                       </tr>
                                    </tbody>
                                 </table>
                                 <h6 class="head-table">Product Description</h6>
                                 <p>Pharma Drugs is one of the leading player in pharma industries business in India. We
                                    have approximately 100 products, which is probably the largest range of product by a
                                    company in the country. We are manufacturing different dosage form of tablets,
                                    capsules, syrup, injection, powder, dry syrup, sachets and granules in various
                                    therapeutic segments.</p>
                                 <p><strong>We Provides all promotional Inputs like Visual Aid Folder, Samples,
                                       Literatures etc.</strong></p>
                                 <ul>
                                    <li>Timely Delivery</li>
                                    <li>Competitive Prices</li>
                                    <li>Attractive Packaging</li>
                                    <li>Marketing Backup</li>
                                    <li>Wide Range with Latest Molecules</li>
                                    <li>Guaranteed Monopoly</li>
                                 </ul>
                                 <h6 class="head-table">Product Image</h6>
                                 <img src="{{ lurl('/images/big-img.jpg') }}">
                              </div>
                           </div>
                           --}}


                           <div id="companydetail" class="full-desc">
                              <h6 style="font-family: poppins;" class="head-table">About Company</h6>
                              <div class="row">
                                 @if($user->establishment_year)
                                 <div class="col-md-4 col-sm-4">
                                    <h4 style="font-family: poppins;color:#000">Year of Establishment</h4>
                                    <h5 style="font-family: poppins;">{{$user->establishment_year}}</h5>
                                 </div>
                                 @endif
                                 {{-- @if($user->businessType)
                                 <div class="col-md-4 col-sm-4">
                                    <h4>Legal Status of Firm</h4>
                                    <h5>{{$user->businessType->name }}</h5>
                                 </div>
                                 @endif --}}
                                 @if($user->businessType)
                                 <div class="col-md-4 col-sm-4">
                                    <h4 style="font-family: poppins;font-weight:bold;color:#000">Nature of Business</h4>
                                    <h5 style="font-family: poppins;">{{$user->businessType->name}}</h5>
                                 </div>
                                 @endif
                                 @if($user->no_employees)
                                 <div class="col-md-4 col-sm-4">
                                    <h4 style="font-family: poppins;font-weight:bold;color:#000">Number of Employees
                                    </h4>
                                    <h5 style="font-family: poppins;">{{$user->no_employees}}</h5>
                                 </div>
                                 @endif
                                 @if($user->annual_turnover)
                                 <div class="col-md-4 col-sm-4">
                                    <h4 style="font-family: poppins;font-weight:bold;color:#000">Annual Turnover</h4>
                                    <h5 style="font-family: poppins;">{{$user->annual_turnover}}</h5>
                                 </div>
                                 @endif
                                 <div class="col-md-4 col-sm-4">
                                    <h4 style="font-family: poppins;font-weight:bold;color:#000">Member Since Rednirus
                                       Mart</h4>
                                    <h5 style="font-family: poppins;">{{$user->created_at->format('M Y')}}</h5>
                                 </div>
                                 @if($user->gstin)
                                 <div class="col-md-4 col-sm-4">
                                    <h4 style="font-family: poppins;color:#000;font-weight:bold">GST</h4>
                                    <h5 style="font-family: poppins;">{{$user->gstin}}</h5>
                                 </div>
                                 @endif
                                 @if($user->dgft_no)
                                 <div class="col-md-4 col-sm-4">
                                    <h4 style="font-family: poppins;;color:#000;font-weight:bold">Import Export Code
                                       (IEC)</h4>
                                    <h5 style="font-family: poppins;">{{$user->dgft_no}}</h5>
                                 </div>
                                 @endif
                              </div>
                              {!! transformDescription($user->about_us) !!}
                           </div>
                        </div>
                     </div>
                     @include('search.inc.template1.quick_query')
                     
                     {{--
                     <h3 class="form-heading">Send your enquiry to this supplier</h3>
                     <form>
                        <label>To</label><input class="form-control" name="product_name" type="text"
                           placeholder="Company name" readonly="readonly" disabled="disabled">
                        <input class="form-control" name="your_mobile" type="phone" placeholder="Enter your mobile"
                           required>
                        <textarea class="form-control" placeholder="Type your message here..."></textarea>
                        <input class="btn btn-default" type="button" value="Submit Requirement" />
                     </form>
                     --}}
                  </div>
               </div>
            </div>
         </div>
         {{--
         <section class="section-block requirement-form-wrap">
            <div class="section-bg">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-md-8">
                        <div class="requirement-form-inner">
                           <h2>Tell us your Requirement</h2>
                           <form>
                              <input class="form-control" name="product_name" type="text"
                                 placeholder="Enter Product / Service name" required>
                              <input class="form-control" name="your_mobile" type="phone"
                                 placeholder="Enter your mobile" required>
                              <input class="form-control" name="your_name" type="phone" placeholder="Enter your name"
                                 required>
                              <textarea class="form-control"
                                 placeholder="Additional details about your requirement..."></textarea>
                              <input class="btn btn-default" type="button" value="Submit Requirement" />
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         --}}
         <section class="section-block featured-pro-wrap">
            <div class="section-bg">
               @include('home.inc.featured', ['firstSection' => false])
            </div>
         </section>
         @include('home.inc.latest', ['firstSection' => false])
         @if (isVerifiedPost($post))
         @include('layouts.inc.tools.facebook-comments', ['firstSection' => false])
         @endif
      </div>
   </div>
</div>
@endsection
@section('modal_message')
@include('search.inc.compose-message')
@include('search.inc.slider-message')
@endsection
@section('after_styles')
<style type="text/css">
   .detail-line {
      clear: both;
      width: 100%;
      padding-left: 17px;
   }

   .detail-line div {
      background-color: #fff;
   }
</style>
<!-- bxSlider CSS file -->
@if (config('lang.direction') == 'rtl')
<link href="{{ url('assets/plugins/bxslider/jquery.bxslider.rtl.css') }}" rel="stylesheet" />
@else
<link href="{{ url('assets/plugins/bxslider/jquery.bxslider.css') }}" rel="stylesheet" />
@endif
@endsection
@section('after_scripts')
@if (config('services.googlemaps.key'))
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.googlemaps.key') }}"
   type="text/javascript"></script>
@endif
<!-- bxSlider Javascript file -->
<script src="{{ url('assets/plugins/bxslider/jquery.bxslider.min.js') }}"></script>
<script>
   $(document).ready(function () {
      $('#show').click(function () {
         $('.menu').toggle("slide");
      });
   });
</script>
<script>
   /* Favorites Translation */
   var lang = {
      labelSavePostSave: "{!! t('Save ad') !!}",
      labelSavePostRemove: "{!! t('Remove favorite') !!}",
      loginToSavePost: "{!! t('Please log in to save the Ads.') !!}",
      loginToSaveSearch: "{!! t('Please log in to save your search.') !!}",
      confirmationSavePost: "{!! t('Post saved in favorites successfully !') !!}",
      confirmationRemoveSavePost: "{!! t('Post deleted from favorites successfully !') !!}",
      confirmationSaveSearch: "{!! t('Search saved successfully !') !!}",
      confirmationRemoveSaveSearch: "{!! t('Search deleted successfully !') !!}"
   };

   $(document).ready(function () {
      /* Slider */
      var $mainImgSlider = $('.bxslider').bxSlider({
         speed: 1000,
         pagerCustom: '#bx-pager',
         adaptiveHeight: true
      });

      /* Initiates responsive slide */
      var settings = function () {
         var mobileSettings = {
            slideWidth: 80,
            minSlides: 2,
            maxSlides: 5,
            slideMargin: 5,
            adaptiveHeight: true,
            pager: false
         };
         var pcSettings = {
            slideWidth: 100,
            minSlides: 3,
            maxSlides: 6,
            pager: false,
            slideMargin: 10,
            adaptiveHeight: true
         };
         return ($(window).width() < 768) ? mobileSettings : pcSettings;
      };

      var thumbSlider;

      function tourLandingScript() {
         thumbSlider.reloadSlider(settings());
      }

      thumbSlider = $('.product-view-thumb').bxSlider(settings());
      $(window).resize(tourLandingScript);


      @if (config('settings.single.show_post_on_googlemap'))
         /* Google Maps */
         getGoogleMaps(
            '{{ config('services.googlemaps.key') }}',
            '{{ (isset($post->city) and !empty($post->city)) ? addslashes($post->city->name) . ', ' . config('country.name') : config('country.name') }}',
            '{{ config('app.locale') }}'
         );
      @endif

      /* Keep the current tab active with Twitter Bootstrap after a page reload */
      /* For bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line */
      $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
         /* save the latest tab; use cookies if you like 'em better: */
         localStorage.setItem('lastTab', $(this).attr('href'));
      });
      /* Go to the latest tab, if it exists: */
      var lastTab = localStorage.getItem('lastTab');
      if (lastTab) {
         $('[href="' + lastTab + '"]').tab('show');
      }
   })
</script>
@endsection
<style>
   @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap');

   .posts-image .pricetag {
      /* bottom: 0; */
      height: 50px;
      top: 250px;
   }

   h2 {
      font-weight: bolder;
   }

   b,
   strong,
   h1,
   h2,
   h3,
   h4,
   h5,
   h6,
   p,
   a,
   li,
   span,
   div {


      font-family: 'Poppins' !important;
   }

   .item-name {
      height: auto !important;
   }

   .col-lg-12.content-footer.text-left.footer-btns {
      margin-left: 0;
      margin-right: 0;
      padding-left: 0;
      padding-right: 0;
      box-shadow: inherit;
      border: none;
      background: none;
   }

   .c-addresssss {
      margin-top: 51px;
   }

   .pro-des-com-des-left h2 {
      margin-bottom: 10px;

   }
   .ads-details-info p {
  text-align: justify;
}
   .pro-des-com-des-left {
      box-shadow: rgb(14 30 37 / 12%) 0px 2px 4px 0px, rgb(14 30 37 / 32%) 0px 2px 16px 0px;
   }

   .pro-des-com-des-left p {
      font-size: 15px !important;
      line-height: 28px !important;
      text-align: justify !important;
   }

   .table-responsive td,
   .table-responsive th {
      border: 1px solid #ccc;
      padding: 4px 6px;
   }

   .select2-container .select2-selection--single {
      height: 40px !important;
   }

   #msform {
      margin-bottom: 20px;
</style>