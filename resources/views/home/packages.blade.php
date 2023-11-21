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

@section('content')
	<div class="main-container" id="homepage">
		@include('search.inc.breadcrumbs')		
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
		@endif
		


		 <section class="products-grid-packages">
        <div class="container-fluid">
            <div class="page-title">
                <h1>Packages</h1>
            </div>
            <div class="row">


            	@foreach($packages as $package)

	                <div class="item-packages col-md-4">
	                    <div class="inner-box-packages clearfix">
	                        <h2 class="graybackground product-name" style="background: #ddd;padding: 8px;border-radius: 0 20px 0 20px;box-shadow: 0 3px 0px 0 #aaa;margin-bottom: 22px;position: relative;margin-top: 10px;">{{$package->name}}</h2>
	                        
	                        <div>
	                        	{!! $package->description !!}
	                        </div>
	                        <a href="{{lurl('/user/payment')}}">
		                        <div class="price-box-packages clearfix">
		                            <span id="product-price-15" class="regular-price">
		                                <span class="price">
		                                    <center>Rs. {{$package->price}} + GST</center>
		                                </span>
		                            </span>
		                        </div>
		                    </a>
	                    </div>
	                </div>

				@endforeach
 
            </div>
        </div>
    </section>


	</div>
@endsection

@section('modal_message')
	 
		@include('search.inc.compose-message')
		@include('search.inc.slider-message')
 
		
	 
@endsection

@section('after_scripts')
@endsection

@section('after_styles')
	<style>
		
.products-grid-packages .page-title h1{
	font-family: roboto !important;
}
.products-grid-packages .page-title{
	margin-bottom:20px;
	margin-top:30px;
}

.inner-box-packages {
	background: #eee;
	padding: 15px;
	margin-bottom: 15px;
	box-shadow: 0 0px 3px 0 #ccc;
	border: 1px solid #ccc;
	border-radius: 5px;
}

.inner-box-packages:hover {
	box-shadow: 0 0px 15px 0 #ccc;
}

.graybackground.product-name {
	font-family: roboto !important;
	padding: 8px 15px 8px 15px!important;
}

.inner-box-packages li {
	color: #333
}

.inner-box-packages li {
	color: #333;
	list-style-type: circle;
	list-style-position: inside;
	padding-bottom: 8px;
	font-size: 17px;
	border-bottom: ;
	font-family: roboto !important;
}


.item-packages:nth-child(1) .graybackground.product-name:after {
	content: "";
	background: #ddd;
	position: absolute;
	top: -15px;
	width: 30px;
	height: 30px;
	margin: auto;
	left: 0;
	right: 0;
	border-radius: 50px;
}
.item-packages:nth-child(2) .graybackground.product-name:after {
	content: "";
	background: #e7cf4a;
	position: absolute;
	top: -15px;
	width: 30px;
	height: 30px;
	margin: auto;
	left: 0;
	right: 0;
	border-radius: 50px;
}
.item-packages:nth-child(3) .graybackground.product-name:after {
	content: "";
	background: silver;
	position: absolute;
	top: -15px;
	width: 30px;
	height: 30px;
	margin: auto;
	left: 0;
	right: 0;
	border-radius: 50px;
}
.item-packages:nth-child(4) .graybackground.product-name:after {
	content: "";
	background: #9eaff0;
	position: absolute;
	top: -15px;
	width: 30px;
	height: 30px;
	margin: auto;
	left: 0;
	right: 0;
	border-radius: 50px;
}
.item-packages:nth-child(5) .graybackground.product-name:after {
	content: "";
	background: #e9b883;
	position: absolute;
	top: -15px;
	width: 30px;
	height: 30px;
	margin: auto;
	left: 0;
	right: 0;
	border-radius: 50px;
}
.item-packages:nth-child(6) .graybackground.product-name:after {
	content: "";
	background: #e99a9a;
	position: absolute;
	top: -15px;
	width: 30px;
	height: 30px;
	margin: auto;
	left: 0;
	right: 0;
	border-radius: 50px;
}

.item-packages .price-box-packages span.price center {
	display: inline-block;
	border: 2px solid #eb7777;
	padding: 8px 29px;
	border-radius: 50px;
	font-weight: 700;
	font-size: 18px;
	color: #333;
}












	</style>
@endsection