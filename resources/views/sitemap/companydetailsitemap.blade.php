<?php

$userslug = $companyname;

$users = DB::table('users')->select('users.username', 'users.id', 'users.photo', 'posts.group_id', 'posts.updated_at', 'users.created_at', 'posts.title')->join('posts', 'posts.user_id', '=', 'users.id')->where('user_type_id', '1')->where('users.username', '=', $userslug)->groupby('posts.group_id')->get();


?>
<style>
	.dropdown-toggle {
		white-space: break-spaces ! important;
	}

	.h-left-b {
		float: left;
	}

	.item-location {
		background: #ed3237;
		color: #fff;
		padding: 0 12px;
		display: block;
		border-radius: 50px;
		font-size: 16px;
	}

	@media only screen and (max-width: 600px) {
		.nodisplyaonmobile {
			display: none;
		}

		.com-breadcrumbs {
			margin-top: 3px ! important;
		}

		.customclss {
			width: 50% !important;
		}

		.menuclss {
			width: 50% !important;
		}
	}

	.btnsave {
		border: 0px;
		background-color: #bf2626;
		padding: 6px 10px 6px 10px;
		color: #fff;
		border-radius: 10px;
	}

	.btnprevious {
		border: 0px;
		background-color: #000;
		padding: 6px 10px 6px 10px;
		color: #fff;
		border-radius: 10px;
	}

	.cookie-consent {
		background-color: #ccc;
		text-align: center;
		padding: 11px;
		position: fixed;
		height: 61px;
		bottom: 0px;
		left: 0px;
		right: 0px;
		margin-bottom: 0px;
		z-index: 9999;
	}

	.js-cookie-consent-agree {
		background-color: #0d7fa5;
		padding: 10px;
		color: #fff;
	}

	.sidebar_mobile_menu .mobile_menu_list .dropdown-menu .dropdown-menu a {
		padding-left: 22px !important;
	}
	.mega_menu  .page_links .viewmore a {color: #ce0b2b !important;	}
	.mega_menu  .page_links .allcat a{color: #fff !important;}
</style>

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

$phone = TextToImage::make($sUser->phone, config('larapen.core.textToImage'));
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

<style>
	.main-content {
		background-color: #fff !important;
	}



	.sitemaps h2 a {
		color: #000;
		font-weight: bold;
		font-size: 18px;
	}

	.sitemaps .subcat {
		background-color: #f0f0f0;
		margin-top: 30px;
		margin-bottom: 30px;
		padding: 15px;
		font-family: Tahoma;
	}

	.sitemaps .post-box {
		background-color: #f6f8fb;
		border: 2px solid #dddedf;
		height: 95px;

		margin-top: 17px;
		padding: 5px;
	}

	.sitemaps .post-text {
		float: left;
		width: 66%;
		margin-left: 5px;
		margin-top: 5px;
		color: #044ca5;
		font-weight: 500;
	}

	.sitemaps .catlisting {
		position: relative;
	}

	.sitemaps .viewmore {
		position: absolute;
		bottom: -55px;
		text-align: center;
		width: 100%;
	}

	.sitemaps .loadmore {
		padding: 10px 20px;
		background: Red;
		color: #fff;
		font-size: 14px;
		font-weight: bold;
	}

	.sitemaps .loadmore:hover,
	.sitemaps .loadmore:active,
	.sitemaps .loadmore:focus {
		padding: 20px;
		background: Red;
		color: #fff;
	}

	.post-img {
		width: 30%;
		height: 80px !important;
		float: left;
	}



	.ul_li_block>li:not(:last-child) {
		margin-right: 20px;
	}



	sidebar_mobile_menu .mobile_menu_list>ul>li>a {
		padding: 15px;
		display: block;
		line-height: 1;
		font-size: 18px;
		font-weight: 600;
		color: #6b6b6b;
	}

	.mega_menu .ul_li_block a:hover {
		background-color: rgb(194, 0, 0);
		color: #fff !important;
	}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<!-- fraimwork - css include -->
<link rel="stylesheet" type="text/css" href="{{ url('templates_new/css/bootstrap.min.css')}}">

<!-- icon - css include -->
<link rel="stylesheet" type="text/css" href="{{ url('templates_new/css/fontawesome.css')}}">

<!-- custom - css include -->
<link rel="stylesheet" type="text/css" href="{{ url('templates_new/css/style.css')}}">


@section('modal_message')
@include('search.inc.compose-company-message')
@include('search.inc.compose-message')
@include('search.inc.slider-message')
@includeWhen(!auth()->check(),'search.inc.user_login')
@includeWhen(!auth()->check(),'search.inc.user_login_otp')
@endsection


@section('content')

<div class="new-template2-container {{$template_color}}">
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


	<header class="header_section simple_shop_header sticky_header1 clearfix">
		<div class="header_content_wrap d-flex align-items-center">
			<div class="container maxw_1480">
				<div class="row align-items-center justify-content-lg-between">
					<div class="col-lg-4">
						<nav class="main_menu clearfix">
							<ul class="ul_li clearfix">
								<li><a href="{{ lurl($company_url) }}">Home</a></li>
								<li><a href="{{ lurl($about_us) }}">About us</a></li>
								<li class="menu_item_has_child">
									<a href="#!">Products</a>
									<div class="mega_menu" style="height:90%; overflow-y:scroll;">
										<div class="background pb-3" data-bg-color="#eee">
											<div style="padding:0px 3% 0px 3%">
												<div class="row mt__30">
													<?php
													$grounpCounter = 0;
													?>
													@foreach($groups as $key=>$group)
													@if($key=="others")
													<?php

													$group_url = trans($compnay_route_inner, [
														'slug' => 'other',
														'username'   =>  $sUser->username,
													]);

													?>
													@else
													<?php

													$group_url = trans($compnay_route_inner, [
														'slug' => $group['data']->slug,
														'username'   =>  $sUser->username,
													]);

													?>

													<div class="col-lg-3">
														<div class="page_links" style="border-bottom: solid 1px #999;">
															<a href="{{ lurl($group_url) }}">
																<h3 class="title_text" style="font-size:17px!important; line-height: 1.2"><?php echo isset($group['data']->name) ? $group['data']->name : ''; ?></h3>
															</a>
															<ul class="ul_li_block">
																<?php
																$postcounter = 0;
																?>
																@foreach($group['posts'] as $post)
																<li class="<?php echo ($postcounter > 3) ? 'post-' . $group['data']->id : '' ?> " style="{{($postcounter>3)?'display:none':''}}"> <a style="font-size:14px!important; line-height:1.2" href="{{ lurl($group_url) }}#{{slugify($post->title)}}"> {{$post->title}}</a></li>
																<?php
																@$postcounter++;
																?>
																@endforeach

															</ul>
															<div class="viewmore" style="{{($postcounter<4)?'display:none':''}}">
																<a href="{{ lurl($group_url) }}">View more</a>
															</div>
														</div>

													</div>
													@endif
													<?php
													if ($grounpCounter == 10) {
														break;
													}
													$grounpCounter++;
													?>
													@endforeach
													<div class="col-lg-3">
														<div class="page_links" style="">
															<div class="viewmore allcat">
																<a class="btn btn-danger" href="/{{$company_url}}/sitemap">View All Categories</a>
															</div>
														</div>

													</div>

												</div>
											</div>
										</div>
									</div>
								</li>

								<li><a href="{{ lurl($contact_us) }}">Contact us</a></li>
							</ul>
						</nav>
					</div>

					<div class="col-lg-4">
						<div class="row">
							<div class="col-lg-3 customclss" style="padding-right:0px;">
								<a class="brand_link" href="{{ lurl($company_url) }}">

									@if (!empty($sUser->photo))
									<img src="/storage/{{ $sUser->photo }}" style="width:80px; alt=" {{ $sUser->name }}">
									@else
									<img src="{{ url('images/user.jpg') }}" alt="{{ $sUser->name }}">
									@endif
								</a>
							</div>


							<div class="col-lg-9 nodisplyaonmobile" style="padding-left:0px;">
								<div class="h-left-b">
									<h3 style="line-height: 30px !important">{{$sUser->name}}</h3>
									<span class="info-row">
										@if ($sUser->city)
										<span class="item-location"><i class="fas fa-map-marker-alt"></i> {{ $sUser->city->name }}

										</span>
										@endif
									</span>
								</div>
							</div>
							<div class="col-lg-3 menuclss">
								<ul class="mh_action_btns ul_li clearfix">

									<li style="float:right;"><button type="button" class="mobile_menu_btn"><i class="far fa-bars"></i></button></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<ul class="action_btns_group ul_li_right clearfix">
							<li>
								<a class="custom_btn bg_black send_company" data-id="{{$sUser->id}}" data-toggle="modal" href="{{ $contactSellerURL }}"><i class="fal fa-envelope" style="padding-right: 3px;
    padding-top: 2px;"></i> Submit Query</a>
							</li>
							<!--<li>
									<a class="custom_btn bg_shop_red" href="#!" tabindex="-1">Send SMS</a>
								</li>-->
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div id="search_body_collapse" class="search_body_collapse collapse">
			<div class="search_body">
				<div class="container-fluid prl_90">
					<form action="#">
						<div class="form_item mb-0">
							<input type="search" name="search" placeholder="Type here...">
							<button type="submit"><i class="fal fa-search"></i></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</header>
	<div class="com-breadcrumbs">
		<div class="container-fluid">
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ lurl($company_url) }}">Home</a></li>
					<li class="breadcrumb-item">Sitemap</li>
				</ol>
			</nav>
		</div>
	</div>
	<main>
		<div style="clear:both"></div>

		<div class="main-content" font>
			<div class="view-all-cate-wrap">
				<div class="container">

					<div class="all-cate-wrap">

						<div class="row">
							<?php
							if ($users->count() > 0) {
								foreach ($users as $user) {
									$productgroup = \App\Models\ProductGroup::with('posts')->where(['id' => $user->group_id])->first();
									if ($productgroup) {
							?>

										<div class="col-md-12  cate-col sitemaps">
											<div class="cate-col-inner site subcat">
												<?php
												$url = '/' . $userslug . '/' . @$productgroup->slug . '#' . slugify(@$user->title);
												?>
												<h2><a href="{{ $url }}"><?php echo isset($productgroup->name) ? $productgroup->name : ''; ?></a></h2>
												<div class="row catlisting" id="viewMore{{$productgroup->id}}">
													<?php
													$counter = 0;
													?>
													@if (count($productgroup->posts))
													@foreach ($productgroup->posts as $post)

													<?php


													?>
													<div class="col-md-4 col-lg-3 col-sm-6  <?php echo ($counter > 3) ? ' moreitems' . $productgroup->id : '' ?>" style="<?php echo ($counter > 3) ? 'display:none' : '' ?>">
														<div class="post-box">
															<?php
															if ($post->alttag) {
																$alttag = $post->alttag;
															} else {
																$alttag = $post->name;
															}
															$group_url = trans('routes.v-company-group', [
																'slug' => $productgroup->slug,
																'username'   =>  $user->username,
															]);
															?>
															<a href="{{ lurl($group_url) }}#{{slugify($post->title)}}">

																<img src="{{$post->image}}" alt="{{ $alttag }}" loading="lazy" class="img-fluid post-img ">

																<div class="post-text">{{ \Illuminate\Support\Str::limit($post->title, 60, $end='...')  }} </div>
															</a>
														</div>
													</div>
													<?php

													$counter++;
													?>
													@endforeach
													@endif
													<div class="viewmore">
														<a href="javascript:" id="loamore{{$productgroup->id}}" class="loadmore" style="{{(($counter)>5)?'':'display:none'}}" onclick="loadMorecat('{{$productgroup->id}}');">View More</a>
														<a href="javascript:" id="loadless{{$productgroup->id}}" class="loadmore" style="display:none" onclick="ViewLesscat('{{$productgroup->id}}');">View Less</a>
													</div>




												</div>
											</div>
										</div>

							<?php
									}
								}
							}
							?>
						</div>


					</div>
				</div>
			</div>
		</div>
		<div class="sidebar-menu-wrapper">
			<div class="sidebar_mobile_menu">

				<div class="msb_widget mobile_menu_list clearfix">
					<ul class="ul_li_block clearfix">
						<li><a class="activemenu" href="{{ lurl($company_url) }}">Home</a></li>
						<li><a style='padding-top: 25px !important' href="{{ lurl($about_us) }}">About us</a></li>
						<li class="dropdown" style="height:auto; overflow-y:scroll;">
							<a href="#!" onclick="open_menu()" style='padding-top: 25px !important' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Products</a>
							<ul class="dropdown-menu" id="ddp">
								@foreach($groups as $key=>$group)
								@if($key=="others")
								<?php

								$group_url = trans($compnay_route_inner, [
									'slug' => 'other',
									'username'   =>  $sUser->username,
								]);



								?>
								@else
								<?php

								$group_url = trans($compnay_route_inner, [
									'slug' => $group['data']->slug,
									'username'   =>  $sUser->username,
								]);

								?>
								<li class="dropdown ul_li_block">
									<a style="font-weight:bold; font-size:14px; border-bottom:solid 1px; line-height: 18px" href="{{ lurl($group_url) }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{$group['data']->name}}</a>
									<ul class="dropdown-menu ul_li_block" style="background-color: #fff;">
										@foreach($group['posts'] as $post)
										<li style="border-bottom: solid 1px #ccc;"> <a href="{{ lurl($group_url) }}#{{slugify($post->title)}}" style="font-size:13px; line-height: 17px"> {{$post->title}}</a></li>
										@endforeach

									</ul>
								</li>
								@endif
								@endforeach






							</ul>
						</li>

						<li><a href="{{ lurl($contact_us) }}">Contact us</a></li>
					</ul>
				</div>

			</div>

			<div class="overlay"></div>
		</div>
		<!-- sidebar mobile menu & sidebar cart - end
			================================================== -->





		<?php
		$segments = explode('/', $_SERVER['REQUEST_URI']);
		$segments[1];
		$data = DB::table('users')->where(['username' => $segments[1]])->first();
		if (@$data->phone_hidden == '1') {
			$phone = '9888885364';
		} else {
			$phone = $data->phone;
		}
		?>
		<div class="row" id="mobiletab" style="display:none;">
			<div class="col-md-2" style="width:20%!important;text-align: center;">
				<a href="{{ lurl($company_url) }}" style="text-align:center; font-size: 10px; font-weight: bold; color:#000;">
					<center><i style="font-size:30px; color: #bf2626;" class="fa fa-home" aria-hidden="true"></i> <br>Home</center>
				</a>
			</div>

			<div class="col-md-2" style="width:20%!important;text-align: center;">
				<a href="/{{$company_url}}/sitemap" style="text-align:center; font-size: 10px; font-weight: bold; color:#000;">
				<center><i style="font-size: 30px; color:#bf2626;" class="fa fa-shopping-cart" aria-hidden="true"></i> <br>Product</center></a>
			</div>
			<div class="col-md-2" style="width:20%!important;text-align: center;">
				<a href="{{ lurl($contact_us) }}" style="text-align:center;font-size: 10px; font-weight: bold; color:#000;">
					<center><i style="font-size:30px; color: #bf2626;" class="fa fa-envelope" aria-hidden="true"></i> <br>Contact us</center>
				</a>
			</div>
			<div class="col-md-2" style="width:20%!important;text-align: center;">
				<a href="https://wa.me/+91<?= @$phone; ?>/?text=Hello, How can Pharmafranchisemart help you?" style="text-align:center; font-size: 10px; font-weight: bold; color:#000;">
					<center><i style="font-size: 30px; color: #bf2626;" class="fab fa-whatsapp" aria-hidden="true"></i> <br>Whatsapp</center>
				</a>
			</div>
			<div class="col-md-2" style="width:20%!important;text-align: center;">
				<a href="tel:+91<?= $phone ?>" style="text-align:center;font-size: 10px; font-weight: bold; color:#000;">
					<center><i style="font-size:30px; color: #bf2626;" class="fa fa-phone" aria-hidden="true"></i><br>Call us</center>
				</a>
			</div>
		</div>
	</main>

	<!-- footer_section - start
		================================================== -->


	<footer class="footer_section fashion_minimal_footer clearfix" data-bg-color="#000" style="padding:15px -1px; background-color:#000!important;">

		<div class="footer_widget_area sec_ptb_100 clearfix">
			<div class="container">
				<div class="row justify-content-lg-between">


					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="footer_widget footer_useful_links clearfix">
							<h3 class="footer_widget_title text-white"><i class="fal fa-map"></i> Reach Us</h3>
							<ul class="ul_li_block">

								<li><span style="font-size:20px; font-weight:bold">{{$sUser->gender->name}}. {{$sUser->first_name}} {{$sUser->last_name}}</span></li>
								<li>@if($sUser->city)
									<span style="line-height: 25px;"><?php echo str_replace(',', ',<br>', $sUser->address1); ?> @if($sUser->address2) {{$sUser->address2}} @endif {{$sUser->city->name}} {{($sUser->city->subAdmin1 && $sUser->city->name!=$sUser->city->subAdmin1->name)?$sUser->city->subAdmin1->name:''}} {{$sUser->pincode}}</span>
									@endif
								</li>
							</ul>
						</div>
					</div>
					<?php
					$customerdata = DB::table('users')->where(['id' => @Auth::user()->id])->first();
					?>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="footer_widget footer_useful_links clearfix">
							<div class="row" style="padding-bottom: 29px;">
								<div class="col-md-6">
									<h2 data-id="{{$sUser->id}}" class="send_company" data-toggle="modal" href="{{ $contactSellerURL }}" style="color: #fff; font-weight: 500; font-size: 25px; cursor:pointer"><i class="fal fa-phone"></i> Call Us</h2>
									<span data-id="{{$sUser->id}}" class="send_company" data-toggle="modal" href="{{ $contactSellerURL }}" style="font-size:18px; font-weight:bold;">+91-<?php if (@$customerdata->verified_email == 1) {
																																																echo $phone;
																																															} else {
																																																echo substr_replace($phone, "xxxxxxx", 0, 7);
																																															} ?></span>
								</div>
								<div class="col-md-6">
									<h2 style="color: #fff; font-weight: 500; font-size: 25px;"><i class="fal fa-share"></i> Share Us</h2>
									<ul class="circle_social_links ul_li_center clearfix mb-0">
										<li><a href="#!"><i class="fab fa-facebook-f"></i></a></li>
										<li><a href="#!"><i class="fab fa-twitter"></i></a></li>
										<li><a href="#!"><i class="fab fa-linkedin"></i></a></li>
									</ul>
								</div>
							</div>
							<div class="row" style="border-top: solid 1px #ccc;padding-top: 29px;">
								<div class="col-md-6">
									<h2 data-id="{{$sUser->id}}" class="send_company" data-toggle="modal" href="{{ $contactSellerURL }}" style="color: #fff; font-weight: 500; font-size: 25px; cursor:pointer"><i class="fal fa-envelope"></i> Send E-mail</h2>
								</div>
								<div class="col-md-6">
									<h2 data-id="{{$sUser->id}}" class="send_company" data-toggle="modal" href="{{ $contactSellerURL }}" style="color: #fff; font-weight: 500; font-size: 25px; cursor:pointer"><i class="fal fa-mobile"></i> Send SMS</h2>
								</div>
							</div>
						</div>
					</div>


				</div>
			</div>

		</div>

	</footer>
	<footer class="footer_section fashion_minimal_footer clearfix" data-bg-color="#c20000" style="padding:15px -1px; background-color:#c20000!important;">

		<div class="footer_widget_area sec_ptb_100 clearfix">
			<div class="container">
				<div class="row justify-content-lg-between">


					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="footer_widget footer_useful_links clearfix">
							<h3 class="footer_widget_title text-white">Our Company</h3>
							<ul class="ul_li_block" style="border-left:solid 1px">
								<li><a href="{{ lurl($company_url) }}">-&nbsp;&nbsp;Home</a></li>
								<li><a href="{{ lurl($about_us) }}">-&nbsp;&nbsp;Profile</a></li>
								<li><a class="send_message" data-id="{{$sUser->id}}" data-toggle="modal" href="{{ $contactSellerURL }}">-&nbsp;&nbsp;Distributor Enquiry Form</a></li>
								<li><a href="#!">-&nbsp;&nbsp;Coporate Video</a></li>
								<li><a href="{{ lurl($contact_us) }}">-&nbsp;&nbsp;Contact</a></li>
								<li>-&nbsp;&nbsp;<a href="/{{$company_url}}/sitemap">Sitemap</a></li>
							</ul>
						</div>
					</div>

					<div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
						<div class="footer_widget footer_useful_links clearfix">
							<h3 class="footer_widget_title text-white">Our Products</h3>
							<ul class="ul_li_block" style="border-left:solid 1px">
								<?php $i = 0; ?>
								@foreach($groups as $key=>$group)
								@if($key=="others")
								<?php

								$group_url = trans($compnay_route_inner, [
									'slug' => 'other',
									'username'   =>  $sUser->username,
								]);

								?>
								@else
								<?php

								$group_url = trans($compnay_route_inner, [
									'slug' => $group['data']->slug,
									'username'   =>  $sUser->username,
								]);

								?>
								@endif
								@foreach($group['posts'] as $pkey=>$post)
								<?php
								$i = $i + 1;

								if ($i < 8) {
								?>

									<li style="margin-bottom: -5px;"><a href="{{ lurl($group_url) }}#{{slugify($post->title)}}">-&nbsp;&nbsp;<p style="margin-top: -14px; margin-left: 17px;">{{$post->title}}</p></a></li>
								<?php
								}
								?>
								@endforeach
								@endforeach
							</ul>
						</div>
					</div>

					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="footer_widget footer_useful_links clearfix">

							<ul class="ul_li_block" style="border-left:solid 1px">
								<?php $x = 0; ?>
								@foreach($groups as $key=>$group)
								@if($key=="others")
								<?php

								$group_url = trans($compnay_route_inner, [
									'slug' => 'other',
									'username'   =>  $sUser->username,
								]);

								?>
								@else
								<?php

								$group_url = trans($compnay_route_inner, [
									'slug' => $group['data']->slug,
									'username'   =>  $sUser->username,
								]);

								?>
								@endif
								@foreach($group['posts'] as $pkey=>$post)
								<?php
								$x = $x + 1;

								if ($x >= 8 and $x <= 15) {
								?>

									<li style="margin-bottom: -5px;"><a href="{{ lurl($group_url) }}#{{slugify($post->title)}}">-&nbsp;&nbsp;<p style="margin-top: -14px; margin-left: 17px;">{{$post->title}}</p></a></li>
								<?php
								}
								?>
								@endforeach
								@endforeach
								@if($i>14)
								<li>-&nbsp;&nbsp;<a href="/{{$company_url}}/sitemap" class="custom_btn bg_default_black">View All</a></li>
								@endif
							</ul>
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="">
			<div class="footer_bottom " style="padding:0px 0px; background-color: #fff;">
				<!--<ul class="circle_social_links ul_li_center clearfix">
				<li><a href="#!"><i class="fab fa-facebook-f"></i></a></li>
				<li><a href="#!"><i class="fab fa-twitter"></i></a></li>
				<li><a href="#!"><i class="fab fa-youtube"></i></a></li>
			</ul>-->
				<div class="row">
					<div class="col-md-6 text-center">
						<p class="copyright_text mb-0" style="margin-top: 18px; color:#000; font-size:14px">
							© <span style="font-weight:bold">{{$sUser->name}}</span> All Rights Reserved.
							<span style="color:#000!important;"><br>Developed and Managed by <a style="color:#000!important;" href="https://www.pharmafranchisemart.com">Rednirusmart Digital Media</a></span>
						</p>
					</div>
					<div class="col-md-6 text-center">
						<a class="btn btn-danger powered-by" style=" background-color: #222;height:74px;" target="_blank" href="https://www.pharmafranchisemart.com">
							<h5 style="color:#fff;font-size: 13px; margin-top:-10px; margin-bottom: -5px;">Member</h5>
							<img style="width:100px; " src="https://www.pharmafranchisemart.com/storage/app/default/logo.png">
						</a>
					</div>
				</div>
			</div>
		</div>
	</footer>

	<!-- footer_section - end
		================================================== -->
	<!-- fraimwork - jquery include -->
	<script src="{{ url('templates_new/js/jquery-3.5.1.min.js')}}"></script>
	<script src="{{ url('templates_new/js/bootstrap.min.js')}}"></script>

	<!-- mobile menu - jquery include -->
	<script src="{{ url('templates_new/js/mCustomScrollbar.js')}}"></script>

	<!-- nice select - jquery include -->
	<!-- custom - jquery include -->
	<script src="{{ url('templates_new/js/custom.js')}}"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
	<script>
		function open_menu() {
			$("#ddp").toggle();
		}
	</script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#recipeCarousel').carousel({
				interval: 10000
			});

			$('.carousel .carousel-item').each(function() {
				var minPerSlide = 3;
				var next = $(this).next();
				if (!next.length) {
					next = $(this).siblings(':first');
				}
				next.children(':first-child').clone().appendTo($(this));

				for (var i = 0; i < minPerSlide; i++) {
					next = next.next();
					if (!next.length) {
						next = $(this).siblings(':first');
					}

					next.children(':first-child').clone().appendTo($(this));
				}
			});
		})
	</script>


	@section('modal_location')
	@include('layouts.inc.modal.location')

	@endsection

	@endsection

	@section('before_scripts')
	@parent
	<script>
		var catArr = [];

		function loadMorecat(catid) {

			if (!catArr[catid]) {
				catArr[catid] = ['loadmore', 2];
			}
			$('#loamore' + catid).hide();
			if (catArr[catid][0] == 'loadmore') {

				ajax = true;

				var listings = [];

				$('.moreitems' + catid).show();
				$('#loamore' + catid).hide();
				$('#loadless' + catid).show();
				catArr[catid] = ['loaded', catArr[catid][1] + 1];


			} else {
				$('.moreboxes').show('slow');
				$('#loamore' + catid).hide();
				$('#loadless' + catid).show();
				$('.moreitems' + catid).hide();
				catArr[catid] = ['loadmore', catArr[catid][1]];
			}
		}

		function ViewLesscat(catid) {
			$('#loamore' + catid).show('slow');
			$('#loadless' + catid).hide('slow');
			$('.moreitems' + catid).hide();

			$('.moreboxes').hide('slow');
		}
		$('.close_btn, .overlay').on('click', function() {
			$('.sidebar_mobile_menu').removeClass('active');
			$('.overlay').removeClass('active');
		});
		$('.mobile_menu_btn').on('click', function() {
			$('.sidebar_mobile_menu').addClass('active');
			$('.overlay').addClass('active');
		});
		var catArr = [];

		function loadMoreprod(groupid) {

			if (!catArr[groupid]) {
				catArr[groupid] = ['loadmore', 2];
			}
			$('#loamore' + groupid).hide();
			if (catArr[groupid][0] == 'loadmore') {

				ajax = true;

				var listings = [];

				$('.post-' + groupid).show();
				$('#loamore' + groupid).hide();
				$('#loadless' + groupid).show();
				catArr[groupid] = ['loaded', catArr[groupid][1] + 1];


			} else {
				$('.moreboxes').show('slow');
				$('#loamore' + groupid).hide();
				$('#loadless' + groupid).show();
				$('.post-' + groupid).hide();
				catArr[groupid] = ['loadmore', catArr[groupid][1]];
			}
		}

		function ViewLessprod(groupid) {
			$('#loamore' + groupid).show('slow');
			$('#loadless' + groupid).hide('slow');
			$('.post-' + groupid).hide('slow');
			catArr[groupid] = ['loadmore', catArr[groupid][1]];

		}
	</script>
	@endsection
