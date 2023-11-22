<?php
	$fullUrl = url(request()->getRequestUri());
	$tmpExplode = explode('?', $fullUrl);
	$fullUrlNoParams = current($tmpExplode);

	$contactSellerURL = '#contactCompany';
	if (!auth()->check()) {
		if (config('settings.single.guests_can_contact_ads_authors') != '1') {
			$contactSellerURL = '#quickLogin';
		}
	}

	$phone = Larapen\TextToImage\Facades\TextToImage::make($sUser->phone, config('larapen.core.textToImage'));
    $phoneLink = 'tel:' . $sUser->phone;
    $phoneLinkAttr = '';
    if (!auth()->check()) {
        if (config('settings.single.guests_can_contact_ads_authors') != '1') {
            $phone = t('Click to see');
            $phoneLink = '#quickLogin';
            $phoneLinkAttr = 'data-toggle="modal"';
        }
    }
?>
@extends('layouts.compnay_master')
@yield('before_styles')
@if (config('lang.direction') == 'rtl')
<link href="https://fonts.googleapis.com/css?family=Cairo|Changa" rel="stylesheet">
<link href="{{ url(mix('css/app.rtl.css')) }}" rel="stylesheet">
@else
<link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">
@endif
<link href="{{ url('css/custom.css') . getPictureVersion() }}" rel="stylesheet">
<script type="application/ld+json"> {
            "@context": "http://schema.org",
            "@type": "Review",
            "author": {
                "@type": "Person",
                "name": "Pharmafranchisemart",
                "sameAs": "GOOGLE-PLUS-LINK"
            },
            "url": "<data:blog.canonicalUrl/>",
            "datePublished": "2022-01-06T20:00",
            "publisher": {
                "@type": "Organization",
                "name": "Pharmafranchisemart",
                "sameAs": "https://www.pharmafranchisemart.com/"
            },
            "description": "<data:blog.metaDescription/>",
            "inLanguage": "en",
            "itemReviewed": {
                "@type": "Product",
                "name": "<data:blog.pageName/>",
                "sameAs": "<data:blog.canonicalUrl/>",
                "image": "<data:blog.postImageThumbnailUrl/>",
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "5",
                    "bestRating": "5",
                    "ratingCount": "19056"
                }
            }
        }</script>

@section('modal_message')
@include('search.inc.compose-company-message')
@include('search.inc.compose-message')
@include('search.inc.slider-message')
@includeWhen(!auth()->check(),'search.inc.user_login')
@includeWhen(!auth()->check(),'search.inc.user_login_otp')
@endsection
@section('content')
<div class="compnay-container">
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

    <div class="main-header-block">
        <div class="company-profile-block">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-1 company-logo abc">
                        <a href="{{ lurl($company_url) }}">
                            @if (!empty($userPhoto))
                            <img src="/{{ $userPhoto }}" alt="{{ $sUser->name }}">
                            @else
                            <img src="{{ url('images/user.jpg') }}" alt="{{ $sUser->name }}">
                            @endif
                        </a>
                    </div>
                    <div class="col-sm-8 company-name-top">
					    <div class="h-left-b">
                        <h1>{{$sUser->name}}</h1>
                        <span class="info-row">
                            @if ($sUser->city)
                            <span class="item-location"><i class="fas fa-map-marker-alt"></i> {{ $sUser->city->name }}
                                @if ($sUser->city->subAdmin1 && ($sUser->city->name != $sUser->city->subAdmin1->name))
                                , {{ $sUser->city->subAdmin1->name }}
                                @endif
                            </span>
                            @endif
                            @if ($sUser->gstin)
                            <span class="category">
                                | GSTIN {{$sUser->gstin}}
                            </span>
                            @endif
                            @if ($sUser->cin_no)
                            <span class="category">
                                | CIN No. {{$sUser->cin_no}}
                            </span>
                            @endif
                            @if ($sUser->dgft_no)
                            <span class="category">
                                | DGFT/IE Code {{$sUser->dgft_no}}
                            </span>
                            @endif
                        </span>
						</div>
						<div class="page-nav">
						<nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item {{($active=='home'?'active':'')}}">
                                <a class="nav-link" href="{{ lurl($company_url) }}">Home</a>
                            </li>
                            @if($groups)
                            <li class="nav-item dropdown {{($active=='products'?'active':'')}}">
                                <a class="nav-link dropdown-toggle hide-mobile" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Our Products
                                </a>
								<label for="cssmenutoggle" class="t-menu hidden-desktop showmobile">Our Products ▼</label>
								<input class="hidden-desktop showmobile" id="cssmenutoggle" type="checkbox" name="css-menu-toggle">
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown" id="myDropdown">
                                    @foreach($groups as $key=>$group)
                                    @if($key=="others")
                                    <?php

						          			$group_url = trans($compnay_route_inner, [
												'slug' => 'other',
												'username'   =>  $sUser->username,
											]);



						          		?>
                                    <a class="dropdown-item" href="{{ lurl($group_url) }}"><strong>{{$group['data']['name']}}</strong></a>
                                    <ul>
                                        @foreach($group['posts'] as $post)
                                        <li><a class="dropdown-item" href="{{ lurl($group_url) }}#{{slugify($post->title)}}">{{$post->title}}</a></li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <?php

						          			$group_url = trans($compnay_route_inner, [
												'slug' => $group['data']->slug,
												'username'   =>  $sUser->username,
											]);

						          		?>
                                    <a class="dropdown-item" href="{{ lurl($group_url) }}"><strong>{{$group['data']->name}}</strong></a>
                                    <ul>
                                        @foreach($group['posts'] as $post)
                                        <li><a class="dropdown-item" href="{{ lurl($group_url) }}#{{slugify($post->title)}}">{{$post->title}}</a></li>
                                        @endforeach
                                    </ul>
                                    @endif
                                    <div class="dropdown-divider"></div>
                                    @endforeach
                                </div>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link {{($active=='about-us'?'active':'')}}" href="{{ lurl($about_us) }}">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{($active=='contact-us'?'active':'')}}" href="{{ lurl($contact_us) }}">Contact Us</a>
                            </li>
                        </ul>
                    </div>
                </nav>
						</div>

                    </div>
                    <div class="col-sm-3 company-btns">
                        <a class="btn btn-default send_company" data-id="{{$sUser->id}}" data-toggle="modal" href="{{ $contactSellerURL }}"><i class="icon-mail-2"></i> Submit Query </a>
                        @if($sUser->brochure)
                        <a class="btn btn-danger price-list" target="_blank" href="{{ lurl('storage/' . $sUser->brochure) }}"><i class="fa fa-file-pdf" aria-hidden="true"></i> Download List</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="dummy-wrap">
            <?php $contentColSm = 'col-md-12'; ?>
            <!-- Content -->
            <div class="{{ $contentColSm }} page-content col-thin-left">
                <div class="row" style="margin-top: 10px;margin-bottom: 10px">
                    <div class="col-md-12">
                        @if($active=='home')
                        	@include('search.inc.template1.home')
                        @elseif($active=='products')
                        	@include('search.inc.template1.products')
                        @elseif($active=='about-us')
                        	@include('search.inc.template1.about_us')
                        @elseif($active=='contact-us')
                        	@include('search.inc.template1.contact_us')
                        @endif
                        <div class="m-footer row">
                            <div class="company-profile-footer">
                                <div class="container-fluid">
                                    <div class="row">
                                        {{-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                            <div class="footer-col">
                                                <h4 class="footer-title">Follow us on</h4>
                                                <ul class="list-unstyled list-inline footer-nav social-list-footer social-list-color footer-nav-inline">
                                                    @if (config('settings.social_link.facebook_page_url'))
                                                    <li>
                                                        <a class="icon-color fb" title="" data-placement="top" data-toggle="tooltip" href="{{ config('settings.social_link.facebook_page_url') }}" data-original-title="Facebook">
                                                            <i class="fab fa-facebook"></i>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if (config('settings.social_link.twitter_url'))
                                                    <li>
                                                        <a class="icon-color tw" title="" data-placement="top" data-toggle="tooltip" href="{{ config('settings.social_link.twitter_url') }}" data-original-title="Twitter">
                                                            <i class="fab fa-twitter"></i>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if (config('settings.social_link.linkedin_url'))
                                                    <li>
                                                        <a class="icon-color lin" title="" data-placement="top" data-toggle="tooltip" href="{{ config('settings.social_link.linkedin_url') }}" data-original-title="LinkedIn">
                                                            <i class="fab fa-linkedin"></i>
                                                        </a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        --}}
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6">
                                            <div class="footer-col footer-col-info">
                                                <h4 class="footer-title">Contact Us</h4>
                                                <h5 class="footer-company-name">{{$sUser->name}}</h5>
                                                @if($sUser->first_name)
                                                <span class="director-name">@if($sUser->gender) {{$sUser->gender->name}}. @endif {{$sUser->first_name}} {{$sUser->last_name}} </span>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                            <div class="footer-col footer-col-info">
                                                <ul class="list-unstyled footer-nav">
                                                    @if ($sUser->phone_hidden != 1 and !empty($sUser->phone))
                                                    <li><a href="{{ $phoneLink }}" {!! $phoneLinkAttr !!} class="">
                                                            <i class="icon-phone-1"></i>:
                                                            {!! $phone !!}
                                                        </a>
                                                    </li>
                                                    @endif
                                                    <li><a href="#"><i class="icon-mail"></i>: {{$sUser->email}} </a></li>

													@if($sUser->city)
                                                <li><a href="#"><i class="icon-mail-2"></i>: {{$sUser->address1}}, @if($sUser->address2) {{$sUser->address2}},@endif {{$sUser->city->name}} {{($sUser->city->subAdmin1 && $sUser->city->name!=$sUser->city->subAdmin1->name)?$sUser->city->subAdmin1->name:''}} {{$sUser->pincode}} </a></li>
                                                @endif

                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 company-btns">
                                            <div class="footer-col">
                                                <a class="btn btn-default send_company" data-id="{{$sUser->id}}" data-toggle="modal" href="{{ $contactSellerURL }}"><i class="icon-mail-2"></i> Submit Query </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

							<div class="company-profile-copy">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-7 copy-txt-left">
                                        <p>© <span class="com-name">{{$sUser->name}}</span>. All Rights Reserved</p>
                                    </div>
                                    <div class="col-md-5 powered-box">
                                        <a class="btn btn-danger powered-by" target="_blank" href="{{ lurl('/') }}">
                                            <h5>Member</h5><img src="{{ lurl('storage/app/default/logo.png') }}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        </div>



                    </div>
                </div>
            </div>

    </div>
    @endsection
    @section('modal_location')
    @include('layouts.inc.modal.location')
    @endsection
