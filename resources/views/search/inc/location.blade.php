<?php
$fullUrl = url(request()->getRequestUri());
$tmpExplode = explode('?', $fullUrl);
$fullUrlNoParams = current($tmpExplode);
// Location
if (isset($city) and !empty($city)) {
	$qLocationId = (isset($city->id)) ? $city->id : 0;
	$qLocation = $city->name;
	$qAdmin = request()->get('r');
} else {
	$qLocationId = request()->get('l');
	$qLocation = (request()->filled('r')) ? t('area:') . rawurldecode(request()->get('r')) : request()->get('location');
    $qAdmin = request()->get('r');
}

?>
 @if (isset($cities) and $cities->count() > 0)
    
<div class="location-categories">
			<div class="category-links">
				<ul>
					<li class="enter-loc"><input type="text" id="locSearch" name="location" class="form-control locinput input-rel searchtag-input has-icon tooltipHere"
							   placeholder="{{ t('Where?') }}" value="{{ $qLocation }}" title="" data-placement="top"
							   data-toggle="tooltip" type="button"
							   data-original-title="{{ t('Enter a city name OR a state name with the prefix ":prefix" like: :prefix', ['prefix' => t('area:')]) . t('State Name') }}"></li>
					<?php
						$attr = ['countryCode' => config('country.icode'),'id'=>'','city'=>''];

						if(isset($cat)){
							if(isset($subCat)){
							$attr['catSlug'] = $subCat->slug;
							}
							else if($cat){
								$attr['catSlug'] = $cat->slug;
							}

							$fullUrlLocation = lurl(trans('routes.search-cat', $attr), $attr);
						}
						else{

							$fullUrlLocation = lurl(trans('routes.search', $attr), $attr);
						}
						
						$locationParams = [
							'l'  => '',
							'location'  => '',
							'q'  => (isset($keywords)) ? $keywords : '',
							'r'  => '',
							'c'  => (isset($cat)) ? $cat->tid : '',
							'sc' => (isset($subCat)) ? $subCat->tid : '',
						];

						if(preg_match('/\/search/', $fullUrlNoParams)){
							$fullUrlLocation =qsurl($fullUrlLocation, array_merge(request()->except(['page'] + array_keys($locationParams)), $locationParams));
						}
						//
					?>
					<li>
						@if(isset($city))
							<a href="{!! $fullUrlLocation !!}" title="All India">
											All India
										</a>
						@else
						<strong>
						<a href="{!! $fullUrlLocation !!}" title="All India">
											All India
										</a>
										</strong>
						@endif
					</li>
				@foreach ($cities as $_city)

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
				 
					<li>
						@if ((isset($uriPathCityId) and $uriPathCityId == $_city->id) or (isset($city) and $city->id==$_city->id))
									<strong>
										<a href="{!! $fullUrlLocation !!}" title="{{ $_city->name }}">
											{{ $_city->name }}  
										</a>
									</strong>
								@else
									<a href="{!! $fullUrlLocation !!}" title="{{ $_city->name }}">
										{{ $_city->name }}
									</a>
								@endif
					</li>
					 
				@endforeach
				</ul>
			</div>
	 </div>
 
@endif
 
 

