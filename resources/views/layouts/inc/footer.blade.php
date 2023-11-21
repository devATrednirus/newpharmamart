<?php
if (
	config('settings.other.ios_app_url') ||
	config('settings.other.android_app_url') ||
	config('settings.social_link.facebook_page_url') ||
	config('settings.social_link.twitter_url') ||
	config('settings.social_link.google_plus_url') ||
	config('settings.social_link.linkedin_url') ||
	config('settings.social_link.pinterest_url') ||
	config('settings.social_link.instagram_url')
) {
	$colClass1 = 'col-lg-3 col-md-3 col-sm-3 col-xs-6';
	$colClass2 = 'col-lg-3 col-md-3 col-sm-3 col-xs-6';
	$colClass3 = 'col-lg-2 col-md-2 col-sm-2 col-xs-12';
	$colClass4 = 'col-md-6 col-xs-12';
} else {
	$colClass1 = 'col-lg-4 col-md-4 col-sm-4 col-xs-6';
	$colClass2 = 'col-lg-4 col-md-4 col-sm-4 col-xs-6';
	$colClass3 = 'col-lg-4 col-md-4 col-sm-4 col-xs-12';
	$colClass4 = 'col-md-6 col-xs-12';
}
?>
<footer class="main-footer skin-blue">
		<div class="container-fluid">

			<div class="container-fluid">
	                    <div class="col-md-12 footer-menu">
							<ul>
							@if (isset($pages) and $pages->count() > 0)
									@foreach($pages as $page)
										<li>
											<?php
												$linkTarget = '';
												if ($page->target_blank == 1) {
													$linkTarget = 'target="_blank"';
												}
											?>
											@if (!empty($page->external_link))
												<a href="{!! $page->external_link !!}" rel="nofollow" {!! $linkTarget !!}> {{ $page->name }} </a>
											@else
												<?php $attr = ['slug' => $page->slug]; ?>
												<a href="{{ lurl(trans('routes.v-page', $attr), $attr) }}" {!! $linkTarget !!}> {{ $page->name }} </a>
											@endif
										</li>
									@endforeach
								@endif

								<!-- -->

								<li><a href="{{ lurl(trans('routes.contact')) }}"> {{ t('Contact') }} </a></li>
								<?php $attr = ['countryCode' => config('country.icode')]; ?>
								<li><a href="{{ lurl(trans('routes.v-sitemap', $attr), $attr) }}"> {{ t('Sitemap') }} </a></li>
								@if (\App\Models\Country::where('active', 1)->count() > 1)
									<li><a href="{{ lurl(trans('routes.countries')) }}"> {{ t('Countries') }} </a></li>
								@endif


								<!-- -->

								@if (!auth()->user())
									<li>
										@if (config('settings.security.login_open_in_modal'))
											<a href="#quickLogin" data-toggle="modal"> {{ t('Log In') }} </a>
										@else
											<a href="{{ lurl(trans('routes.login')) }}"> {{ t('Log In') }} </a>
										@endif
									</li>
									<li><a href="{{ lurl(trans('routes.register')) }}"> {{ t('Register') }} </a></li>
								@else
									<li><a href="{{ lurl('account') }}"> {{ t('Personal Home') }} </a></li>
									<li><a href="{{ lurl('account/my-posts') }}"> {{ t('My ads') }} </a></li>
									<li><a href="{{ lurl('account/favourite') }}"> {{ t('Favourite ads') }} </a></li>
								@endif



							</ul>
						</div>

							@if(auth()->check())

							<?php
								$searc_history = \App\Models\SearchHistory::with('category')->where('user_id',auth()->user()->id)->whereNotNull('category_id')->orderBy('id','desc')->groupBy('category_id')->limit(15)->get();

							?>
	                        <div class="col-md-12 bottom-links">
								<ul>
									@foreach($searc_history as $key => $history)
									 @if($history->category)
                                     <li><a href="{{ lurl('/category/'.slugify($history->category->slug)) }}">{{ $history->category->name }}</a></li>
                                     @endif
                                    @endforeach
								</ul>

							</div>
							@endif

							@if (isset($categories) and $categories->count() > 0)
							<div class="footer-services-sec">
							    <h5><strong style="color: #333;">Some of our services that will prove useful to you on a day-to-day basis are:</strong></h5>
							    <div class="row service-wrap">
						            <?php $i=0; ?>
						            @foreach($categories as $key => $cat)

										<?php

										if($cat->in_footer){



											$attr = ['countryCode' => config('country.icode'), 'catSlug' => $cat->slug];

											if(isset($city)){
												$description = str_replace('{LOCATION}',$city->name, $cat->description);
											}
											else{
												$description = str_replace('{LOCATION}','', $cat->description);

											}

										?>
										@if($i!=0 && $i%4==0)
											</div><div class="row service-wrap">
										@endif
										<div class="col-md-3 col-sm-3 footer-col">
										    <div class="row">

												<div class="col-md-10 service-txt skin-blue">
													<h3><a href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">{{ $cat->name }}</a></h3>
													{{ $description }}
												</div>
							                </div>
										</div>
										<?php $i++; } ?>
									@endforeach

								</div>
							</div>
							@endif

	</div>


		</div>

		<div class="footer-copy">
	    <div class="container-fluid">
		    <div class="copy-inner row">
		    	<div class="{{ $colClass4 }}">
		     <p>© {{ date('Y') }} {{ config('settings.app.app_name') }}. {{ t('All Rights Reserved') }}.
						@if (!config('settings.footer.hide_powered_by'))
							@if (config('settings.footer.powered_by_info'))
								{{ t('Powered by') }} {!! config('settings.footer.powered_by_info') !!}
							@else
							@endif
						@endif</p>
					</div>

			 @if (
						config('settings.other.ios_app_url') or
						config('settings.other.android_app_url') or
						config('settings.social_link.facebook_page_url') or
						config('settings.social_link.twitter_url') or
						config('settings.social_link.google_plus_url') or
						config('settings.social_link.linkedin_url') or
						config('settings.social_link.pinterest_url') or
						config('settings.social_link.instagram_url')
						) <!--
						<div class="{{ $colClass4 }}" style="text-align: right;">
							<div class="footer-col row">
								<?php
									$footerSocialClass = '';
									$footerSocialTitleClass = '';
								?>
								{{-- @todo: API Plugin --}}
								@if (config('settings.other.ios_app_url') or config('settings.other.android_app_url'))
									<div class="col-sm-12 col-xs-6 col-xxs-12 no-padding-lg">
										<div class="mobile-app-content">
											<h4 class="footer-title">{{ t('Mobile Apps') }}</h4>
											<div class="row ">
												@if (config('settings.other.ios_app_url'))
												<div class="col-xs-12 col-sm-6">
													<a class="app-icon" target="_blank" href="{{ config('settings.other.ios_app_url') }}">
														<span class="hide-visually">{{ t('iOS app') }}</span>
														<img src="{{ url('images/site/app-store-badge.svg') }}" loading="lazy" alt="{{ t('Available on the App Store') }}">
													</a>
												</div>
												@endif
												@if (config('settings.other.android_app_url'))
												<div class="col-xs-12 col-sm-6">
													<a class="app-icon" target="_blank" href="{{ config('settings.other.android_app_url') }}">
														<span class="hide-visually">{{ t('Android App') }}</span>
														<img src="{{ url('images/site/google-play-badge.svg') }}" loading="lazy" alt="{{ t('Available on Google Play') }}">
													</a>
												</div>
												@endif
											</div>
										</div>
									</div>
									<?php
										$footerSocialClass = 'hero-subscribe';
										$footerSocialTitleClass = 'no-margin';
									?>
								@endif -->

								@if (
									config('settings.social_link.facebook_page_url') or
									config('settings.social_link.twitter_url') or
									config('settings.social_link.google_plus_url') or
									config('settings.social_link.linkedin_url') or
									config('settings.social_link.pinterest_url') or
									config('settings.social_link.instagram_url')
									)
									<div class="{{ $colClass4 }}" align="right">
										<div class="{!! $footerSocialClass !!}">

											<ul class="list-unstyled list-inline footer-nav social-list-footer social-list-color footer-nav-inline">
												@if (config('settings.social_link.quora_page_url'))
												<li>
													<a class="icon-color gp" target="_blank" rel="nofollow" title="Quora" data-placement="top" data-toggle="tooltip" href="{{ config('settings.social_link.quora_page_url') }}" data-original-title="Quora">
														<i class="fab fa-quora"></i>
													</a>
												</li>
												@endif
												@if (config('settings.social_link.twitter_url'))
												<li>
													<a class="icon-color tw" target="_blank" rel="nofollow" title="Twitter" data-placement="top" data-toggle="tooltip" href="{{ config('settings.social_link.twitter_url') }}" data-original-title="Twitter">
														<i class="fab fa-twitter"></i>
													</a>
												</li>
												@endif


												@if (config('settings.social_link.facebook_page_url'))
												<li>
													<a class="icon-color fb" target="_blank" rel="nofollow" title="Facebook" data-placement="top" data-toggle="tooltip" href="{{ config('settings.social_link.facebook_page_url') }}" data-original-title="Facebook">
														<i class="fab fa-facebook"></i>
													</a>
												</li>
												@endif

												@if (config('settings.social_link.instagram_url'))
													<li>
														<a class="icon-color pin" title="Instagram" data-placement="top" data-toggle="tooltip" href="{{ config('settings.social_link.instagram_url') }}" data-original-title="Instagram">
															<i class="icon-instagram-filled"></i>
														</a>
													</li>
												@endif
												@if (config('settings.social_link.google_plus_url'))
												<li>
													<a class="icon-color gp" target="_blank" rel="nofollow" title="Google+" data-placement="top" data-toggle="tooltip" href="{{ config('settings.social_link.google_plus_url') }}" data-original-title="Google+">
														<i class="fab fa-google-plus"></i>
													</a>
												</li>
												@endif
												@if (config('settings.social_link.linkedin_url'))
												<li>
													<a class="icon-color lin" target="_blank" rel="nofollow" title="LinkedIn" data-placement="top" data-toggle="tooltip" href="{{ config('settings.social_link.linkedin_url') }}" data-original-title="LinkedIn">
														<i class="fab fa-linkedin"></i>
													</a>
												</li>
												@endif

												@if (config('settings.social_link.youtube_page_url'))
												<li>
													<a class="icon-color lin" target="_blank" rel="nofollow" title="Youtube" data-placement="top" data-toggle="tooltip" href="{{ config('settings.social_link.youtube_page_url') }}" data-original-title="Youtube">
														<i class="fab fa-youtube"></i>
													</a>
												</li>
												@endif

												@if (config('settings.social_link.pinterest_url'))
												<li>
													<a class="icon-color pin" target="_blank" rel="nofollow" title="Pinterest" data-placement="top" data-toggle="tooltip" href="{{ config('settings.social_link.pinterest_url') }}" data-original-title="Pinterest">
														<i class="fab fa-pinterest-p"></i>
													</a>
												</li>
												@endif
												@if (config('settings.social_link.reddit_page_url'))
												<li>
													<a class="icon-color lin" target="_blank" rel="nofollow" title="Reddit" data-placement="top" data-toggle="tooltip" href="{{ config('settings.social_link.reddit_page_url') }}" data-original-title="Reddit">
														<i class="fab fa-reddit"></i>
													</a>
												</li>
												@endif
											</ul>
										</div>
									</div>
								@endif
							</div>
						</div>
					@endif

		</div>
		</div>
	</div>



<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
}
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


<style type="text/css">
	.bottom-links ul li:after {
    display: inline-block;
    font-size: 15px;
}
</style>


</footer>
