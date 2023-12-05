<?php $sty = '';
if(!empty($_GET['debu'])) {
  if($_GET['debu'] == 1)  {
    echo "home.inc.featured";
    $sty = ' style="border: 1px solid;" ';
  }
} ?>

<?php
if (!isset($cacheExpiration)) {
    $cacheExpiration = (int)config('settings.other.cache_expiration');
}
?>

@if (isset($featured) and !empty($featured) and !empty($featured->posts))
	@include('home.inc.spacer')
	<div class="container-fluid">

		<div class="col-xl-12 content-box layout-section sponser-ads">
			<div class="row row-featured row-featured-category">
				<div class="col-xl-12 box-title">
					<div class="inner">
						<h2>
							<span class="title-3">{!! $featured->title !!}</span>
							<a href="{{ $featured->link }}" class="sell-your-item">
								{{ t('View more') }} <i class="icon-th-list"></i>
							</a>
						</h2>
					</div>
				</div>

				<div style="clear: both"></div>

				<div class="relative content featured-list-row clearfix">

					<div class="large-12 columns">
						<div class="no-margin featured-list-slider owl-carousel owl-theme">
							<?php
							foreach($featured->posts as $key => $post):
								if (empty($countries) or !$countries->has($post->country_code)) continue;

								$sUser = \App\Models\User::with('city')->where('id', $post->user_id)->first();

								// Picture setting
								$pictures = \App\Models\Picture::where('post_id', $post->id)->orderBy('position')->orderBy('id');
								if ($pictures->count() > 0) {
									$postImg = resize($pictures->first()->filename, 'medium');
								} else {
									$postImg = resize(config('larapen.core.picture.default'));
								}

								// Category
								$cacheId = 'category1111.' . $post->category_id . '.' . config('app.locale');
								$liveCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
									$liveCat = \App\Models\Category::find($post->category_id);
									return $liveCat;
								});

								// Check parent
								if (empty($liveCat->parent_id)) {
									$liveCatType = $liveCat->type;
								} else {
									$cacheId = 'category.' . $liveCat->parent_id . '.' . config('app.locale');
									$liveParentCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($liveCat) {
										$liveParentCat = \App\Models\Category::find($liveCat->parent_id);
										return $liveParentCat;
									});
									$liveCatType = (!empty($liveParentCat)) ? $liveParentCat->type : 'classified';
								}
								?>
								<div class="item">

									<?php $attr = ['slug' => slugify($post->title), 'id' => $post->id]; ?>
									<a href="{{ lurl($post->uri, $attr) }}">
										<span class="item-carousel-thumb">
											<img class="img-fluid" src="{{ $postImg }}" width="320px" height="240px" alt="{{ $post->title }}" style="border: 1px solid #e7e7e7; margin-top: 2px;" loading="lazy">
										</span>
										<h3 class="item-name">{{ str_limit($post->title, 70) }}</h3>

										<span class="item-name">{{ str_limit($post->compnay_name, 70) }}</span>

										@if(isset($sUser->city))
										<div class="c-address">
											 <i class="fa fa-map" aria-hidden="true"></i>

												{{$sUser->address1}}, {{$sUser->address2}}, {{$sUser->city->name}} {{($sUser->city->subAdmin1 && $sUser->city->name!=$sUser->city->subAdmin1->name)?$sUser->city->subAdmin1->name:''}} {{$sUser->pincode}}
										</div>
										@endif

										@if (config('plugins.reviews.installed'))
											@if (view()->exists('reviews::ratings-list'))
												@include('reviews::ratings-list')
											@endif
										@endif

									</a>

									<a class="btn  btn-md btn-default send_message" data-toggle="modal" data-id="{{ $post->id }}" href="#contactUser"><i class="icon-mail-2"></i> <span> {{ t('Send a message') }} </span></a>
								</div>
							<?php endforeach; ?>

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
@endif

@section('after_style')
	@parent
@endsection

@section('before_scripts')
	@parent
	<script>
		/* Carousel Parameters */
		var carouselItems = {{ (isset($featured) and isset($featured->posts)) ? collect($featured->posts)->count() : 0 }};
		var carouselAutoplay = {{ (isset($featuredOptions) && isset($featuredOptions['autoplay'])) ? $featuredOptions['autoplay'] : 'true' }};

		var carouselAutoplayTimeout = {{ (isset($featuredOptions) && isset($featuredOptions['autoplay_timeout'])) ? $featuredOptions['autoplay_timeout'] : 1500 }};
		var carouselLang = {
			'navText': {
				'prev': "{{ t('prev') }}",
				'next': "{{ t('next') }}"
			}
		};
	</script>
@endsection
