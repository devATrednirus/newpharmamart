<!-- this (.mobile-filter-sidebar) part will be position fixed in mobile version -->
<?php
    $fullUrl = url(request()->getRequestUri());
    $tmpExplode = explode('?', $fullUrl);
    $fullUrlNoParams = current($tmpExplode);
?>

<div id="sidebar" class="col-lg-2 col-md-2 col-sm-3 main-sidebar mobile-filter-sidebar pb-4">
	<aside>
		<div class="sidebar-modern-inner enable-long-words">




            <!-- City -->
            {{--
			<div class="block-title has-arrow sidebar-header">
				<h5><strong><a href="#">{{ t('Locations') }}</a></strong></h5>
			</div>
			<div class="block-content list-filter locations-list">
				<ul class="browse-list list-unstyled long-list">
                    @if (isset($cities) and $cities->count() > 0)
						@foreach ($cities as $city)

								$attr = ['countryCode' => config('country.icode'),'id'=>$city->id,'city'=>slugify($city->name)];
								$fullUrlLocation = lurl(trans('routes.search-city', $attr), $attr);
								$locationParams = [
									'l'  => $city->id,
									'r'  => '',
									'c'  => (isset($cat)) ? $cat->tid : '',
									'sc' => (isset($subCat)) ? $subCat->tid : '',
								];

							<li>
								@if ((isset($uriPathCityId) and $uriPathCityId == $city->id) or (request()->input('l')==$city->id))
									<strong>
										<a href="{!! qsurl($fullUrlLocation, array_merge(request()->except(['page'] + array_keys($locationParams)), $locationParams)) !!}" title="{{ $city->name }}">
											{{ $city->name }}
										</a>
									</strong>
								@else
									<a href="{!! qsurl($fullUrlLocation, array_merge(request()->except(['page'] + array_keys($locationParams)), $locationParams)) !!}" title="{{ $city->name }}">
										{{ $city->name }}
									</a>
								@endif
							</li>
						@endforeach
                    @endif
				</ul>
			</div>
            --}}
            {{--
            <!-- Date -->
			<div class="block-title has-arrow sidebar-header">
				<h5><strong><a href="#"> {{ t('Date Posted') }} </a></strong></h5>
			</div>
            <div class="block-content list-filter">
                <div class="filter-date filter-content">
                    <ul>
                        @if (isset($dates) and !empty($dates))
                            @foreach($dates as $key => $value)
                                <li>
                                    <input type="radio" name="postedDate" value="{{ $key }}" id="postedDate_{{ $key }}" {{ (request()->get('postedDate')==$key) ? 'checked="checked"' : '' }}>
                                    <label for="postedDate_{{ $key }}">{{ $value }}</label>
                                </li>
                            @endforeach
                        @endif
                        <input type="hidden" id="postedQueryString" value="{{ httpBuildQuery(request()->except(['page', 'postedDate'])) }}">
                    </ul>
                </div>
            </div>
            --}}
            @if (isset($cat))
            	{{--
                @if (!in_array($cat->type, ['not-salable']))
					<!-- Price -->
					<div class="block-title has-arrow sidebar-header">
						<h5><strong><a href="#">{{ (!in_array($cat->type, ['job-offer', 'job-search'])) ? t('Price range') : t('Salary range') }}</a></strong></h5>
					</div>
					<div class="block-content list-filter">
						<form role="form" class="form-inline" action="{{ $fullUrlNoParams }}" method="GET">
							{!! csrf_field() !!}
							@foreach(request()->except(['page', 'minPrice', 'maxPrice', '_token']) as $key => $value)
								@if (is_array($value))
									@foreach($value as $k => $v)
										@if (is_array($v))
											@foreach($v as $ik => $iv)
												@continue(is_array($iv))
												<input type="hidden" name="{{ $key.'['.$k.']['.$ik.']' }}" value="{{ $iv }}">
											@endforeach
										@else
											<input type="hidden" name="{{ $key.'['.$k.']' }}" value="{{ $v }}">
										@endif
									@endforeach
								@else
									<input type="hidden" name="{{ $key }}" value="{{ $value }}">
								@endif
							@endforeach
							<div class="form-group col-sm-4 no-padding">
								<input type="text" placeholder="2000" id="minPrice" name="minPrice" class="form-control" value="{{ request()->get('minPrice') }}">
							</div>
							<div class="form-group col-sm-1 no-padding text-center hidden-xs"> -</div>
							<div class="form-group col-sm-4 no-padding">
								<input type="text" placeholder="3000" id="maxPrice" name="maxPrice" class="form-control" value="{{ request()->get('maxPrice') }}">
							</div>
							<div class="form-group col-sm-3 no-padding">
								<button class="btn btn-default pull-right btn-block-xs" type="submit">{{ t('GO') }}</button>
							</div>
						</form>
						<div style="clear:both"></div>
					</div>
                @endif
				--}}


				<?php $parentId = ($cat->parent_id == 0) ? $cat->tid : $cat->parent_id; ?>
                <!-- SubCategory -->
				<div id="subCatsList">
					<div class="block-title has-arrow sidebar-header">
						<h5><strong><a href="#"><i class="fa fa-angle-left"></i> {{ t('Others Categories') }}</a></strong></h5>
					</div>
					<div class="block-content list-filter categories-list">
						<ul class="list-unstyled">
							<li>
								@if ($cats->has($parentId))
									<?php

									if(isset($city)){


										$attr = ['countryCode' => config('country.icode'), 'catSlug' => $cats->get($parentId)->slug, 'city' => slugify($city->name), 'id' => $city->id];
										$searchUrl = lurl(trans('routes.search-cat-location', $attr), $attr);
										$catName= $cats->get($parentId)->name.' in '.$city->name;
									}
									else{
										$attr = ['countryCode' => config('country.icode'), 'catSlug' => $cats->get($parentId)->slug];
										$searchUrl = lurl(trans('routes.v-search-cat', $attr), $attr);
									 	$catName= $cats->get($parentId)->name;
									}
									/**/



									//$searchUrl = qsurl($searchUrl, request()->except([]));

									?>
									<a href="{{ $searchUrl }}" title="{{ $cats->get($parentId)->name }}">
										<span class="title"><strong>{{ $catName }}</strong>
										</span>
									</a>

								@endif
								<ul class="list-unstyled long-list">
									@if ($cats->groupBy('parent_id')->has($parentId))
									@foreach ($cats->groupBy('parent_id')->get($parentId) as $iSubCat)
										{{-- @continue(!$cats->has($iSubCat->parent_id)) --}}

										{{-- @if(isset($countSubCatPosts->get($iSubCat->tid)->total)) --}}
										<li>


											<?php


												if(isset($city)){


													$attr = ['countryCode' => config('country.icode'), 'catSlug' => $iSubCat->slug, 'city' => slugify($city->name), 'id' => $city->id];
													$searchUrl = lurl(trans('routes.search-subCat-location', $attr), $attr);
													//$searchUrl = qsurl($searchUrl, request()->except([]));
													$catName= $iSubCat->name.' in '.$city->name;
												}
												else{
													$attr = [
														'countryCode' => config('country.icode'),
														'catSlug'     => $cats->get($iSubCat->parent_id)->slug,
														'subCatSlug'  => $iSubCat->slug
													];

														$searchUrl = lurl(trans('routes.search-subCat', $attr), $attr) ;

													//	$searchUrl = qsurl($searchUrl, request()->except([]));

												 		$catName= $iSubCat->name;
												}
												/**/




											?>
											@if ((isset($subCat) and ($subCat->id == $iSubCat->id or $subCat->parent_id == $iSubCat->id) ) or (request()->input('sc') == $iSubCat->tid))
												<strong>
													<a href="{{ $searchUrl }}" title="{{ $iSubCat->name }}">
														{{ str_limit($catName, 100) }}

													</a>
												</strong>
												<ul>

												@foreach ($iSubCat->children as $siSubCat)
													<li>


													<?php


														if(isset($city)){


															$attr = ['countryCode' => config('country.icode'), 'catSlug' => $siSubCat->slug, 'city' => slugify($city->name), 'id' => $city->id];
															$searchUrl = lurl(trans('routes.search-subCat-location', $attr), $attr);
															//$searchUrl = qsurl($searchUrl, request()->except([]));
															$catName= $siSubCat->name.' in '.$city->name;
														}
														else{
															$attr = [
																'countryCode' => config('country.icode'),
																'catSlug'     => $cats->get($siSubCat->parent_id)->slug,
																'subCatSlug'  => $siSubCat->slug
															];

																$searchUrl = lurl(trans('routes.search-subCat', $attr), $attr) ;

															//	$searchUrl = qsurl($searchUrl, request()->except([]));

														 		$catName= $siSubCat->name;
														}
														/**/




													?>
														@if ((isset($subCat) and ($subCat->id == $siSubCat->id ) ) or (request()->input('sc') == $siSubCat->tid))

																<strong>
																	<a href="{{ $searchUrl }}" title="{{ $siSubCat->name }}">
																		{{ str_limit($catName, 100) }}

																	</a>
																</strong>
														@else
															<a href="{{ $searchUrl }}" title="{{ $iSubCat->name }}">
																{{ str_limit($catName, 100) }}

															</a>
														@endif
													</li>
												@endforeach
											</ul>


											@else
												<a href="{{ $searchUrl }}" title="{{ $iSubCat->name }}">
													{{ str_limit($catName, 100) }}

												</a>
											@endif
										</li>
										{{-- @endif --}}
									@endforeach
									@endif
								</ul>
							</li>
						</ul>







					</div>
				</div>
				<?php $style = 'style="display: none;"'; ?>



			@endif

            <!-- Category -->
			<div id="catsList" {!! (isset($style)) ? $style : '' !!}>
				<div class="block-title has-arrow sidebar-header" style="background-color:#00b5b7 !important;">
					<h5 style="color:#fff;background-color:#00b5b7 !important;"><strong>{{ t('All Categories') }}</strong></h5>
				</div>
				<div class="block-content list-filter categories-list">
					<ul class="list-unstyled">
						@if ($cats->groupBy('parent_id')->has(0))

						@foreach ($cats->groupBy('parent_id')->get(0) as $iCat)



							<?php

								if(isset($city)){


									$attr = ['countryCode' => config('country.icode'), 'catSlug' => $iCat->slug, 'city' => slugify($city->name), 'id' => $city->id];
									$searchUrl = lurl(trans('routes.search-cat-location', $attr), $attr);
									$catName= $iCat->name.' in '.$city->name;
								}
								else{
									$attr = ['countryCode' => config('country.icode'), 'catSlug' => $iCat->slug];
									$searchUrl = lurl(trans('routes.v-search-cat', $attr), $attr);
								 	$catName= $iCat->name;
								}
								/**/



								//$searchUrl = qsurl($searchUrl, request()->except([]));





							?>
							<li>
								@if ((isset($cat) and $cat->id == $iCat->id) or (request()->input('c') == $iCat->tid))
									<strong>
										<a href="{{ $searchUrl }}" title="{{ $iCat->name }}">
											<span class="title">{{ $catName}}</span>

										</a>
									</strong>
								@else
									<a href="{{ $searchUrl }}" title="{{ $iCat->name }}">
										<span class="title">{{ $catName }}</span>

									</a>
								@endif
							</li>

						@endforeach
						@endif
					</ul>
				</div>
			</div>


			{{-- @include('search.inc.fields') --}}
			<div style="clear:both"></div>


		</div>
       @isset($cat)
            @if($cat->youtubelink)
        <div class="ifrmae-video" style="margin-top: 24px;">
		<iframe width="100%" height="500" src="{{$cat->youtubelink}}" title="{{$cat->youtubetext}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
		</div>
         @endif
        @endisset






               <div class="whatappchat" style="    position: fixed;
    width: 200px;
    background: #E6DDD4;
    bottom: 60px;
    right: 21px;
    z-index: 9999;
    border-radius: 10px 10px 10px 10px;">
      <div class="headerss" style="display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px;
    background: #095E54;
    border-radius: 10px 10px
px
 0px 0px;">
         <div class="headers-left">
           <i class="fa fa-user" style="text-align: center;
    font-size: 15px;
    color: #fff;
    border-radius: 20px;
    width: 30px;
    height: 30px;
    padding-top: 6px;
    border: 1px solid;"></i>
         </div>
          <div class="headers-left">
           <h4 style="color: #fff;
    align-self: center;font-size: 13px;">Welcome To Rednirus Mart</h4>
           <p style="color: #fff;
    align-self: center;font-size: 10px;">Typing...</p>
         </div>
      </div>
      <div class="whatss-body" style="width: 134px;
    background: #fff;
    margin: 20px;
    border-radius: 0px 0px 10px 10px;">
          <p style="font-size: 12px;
    margin: 0px;
    padding: 10px;">Hi there ??<br>
          How may help you...</p>
      </div>
      <div class="whatfooter" style="    background: #FFFFFF;
    text-align: center;
    /* margin: 0px 10px; */
    margin: auto;
    align-items: center;
    justify-content: center;
    flex-direction: row;
    padding: 10px 0px;
    border-radius: 0px 0px 10px 10px;">
        <a style="background: #14C656;
    padding: 2px 26px;
    border-radius: 32px;
    display: block;
    width: 153px;
    color: #fff;
    font-size: 12px;
    margin: auto;" href="https://wa.me/+919888885364/?text=Hello,  How can Pharmafranchisemart help you? ({{Request::url()}})"><i class="fab fa-whatsapp"></i> Start Chat</a>  
      </div>
</div>



	</aside>

</div>
{{--
@section('after_scripts')
    @parent
    <script>
        var baseUrl = '{{ $fullUrlNoParams }}';

        $(document).ready(function ()
        {
            $('input[type=radio][name=postedDate]').click(function() {
                var postedQueryString = $('#postedQueryString').val();

                if (postedQueryString != '') {
                    postedQueryString = postedQueryString + '&';
                }
                postedQueryString = postedQueryString + 'postedDate=' + $(this).val();

                var searchUrl = baseUrl + '?' + postedQueryString;
				redirect(searchUrl);
            });
        });
    </script>
@endsection

--}}



{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> --}}
<script>
$(window).scroll(function(){
  var sticky = $('.page-sidebar'),
      scroll = $(window).scrollTop();

  if (scroll >= 50) sticky.addClass('fixed');
  else sticky.removeClass('fixed');
});
</script>
