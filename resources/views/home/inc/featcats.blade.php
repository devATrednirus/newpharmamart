{{-- OLD CODE


@if (isset($categories_featured) and $categories_featured->count() > 0)
<section class="section-block featured-pro-wrap">
	    <div class="container-fluid">
		    <div class="section-bg">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<h2 class="feat">Featured Categories home.inc.categories</h2>
							<div class="row">
								@foreach($categories_featured as $key => $cat)
									<?php
										$attr = ['countryCode' => config('country.icode'), 'catSlug' => $cat->slug];
										if($cat->alttag){
											$alttag = $cat->alttag;
										} else {
											$alttag = $cat->name;
										}

									?>
								<div class="col-md-2">
								    <div class="cards">

										<a href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}"><img src="{{ resize($cat->picture, 'square') . getPictureVersion() }}" alt="{{$alttag}}" loading="lazy" width="100%" height="100%"></a>
										<h3><a href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">{{ $cat->name }}</a></h3>

									</div>
								</div>
								@endforeach

							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</section>

@endif
--}}

@if (isset($categories_featured) and $categories_featured->count() > 0)
                <div class="container">
                  <section class="ps-section--categories pt-5 mt-5">
                      <div class="ps-section__content wow fadeInUp animated" data-wow-duration="1s" data-wow-delay="0.7s" style="visibility: visible; animation-duration: 1s; animation-delay: 0.7s; animation-name: fadeInUp;">
                          <div class="ps-categories__list">
                            @foreach($categories_featured as $key => $cat)
                              <?php
            										$attr = ['countryCode' => config('country.icode'), 'catSlug' => $cat->slug];
            										if($cat->alttag){
            											$alttag = $cat->alttag;
            										} else {
            											$alttag = $cat->name;
            										}
            									?>
                              <div class="ps-categories__item"><a class="ps-categories__link" target="blank" href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}"><img src="images/why-choose/tablete.png" alt=""></a><a class="ps-categories__name" target="blank" href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">{{ Str::limit($cat->name,10,'...') }}</a></div>
                            @endforeach
                          </div>
                      </div>
                  </section>
                </div>
@endif
