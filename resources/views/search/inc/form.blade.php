<?php
// Keywords
$keywords = rawurldecode(request()->get('q'));

// Category
$ct =  request()->get('ct');

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

	 <div class="search-row-wrapper header-search">
		<?php $sty = '';
		if(!empty($_GET['debu'])) {
		  if($_GET['debu'] == 1)  {
		    echo "search.inc.form";
		    $sty = ' style="border: 1px solid;" ';
		  }
		} ?>

		<div class="container search-row">
			<?php $attr = ['countryCode' => config('country.icode')]; ?>
			<form id="seach" name="search" action="search{{-- lurl(trans('routes.v-search', $attr), $attr) --}}" method="GET">
				<div class="row m-0">
					<div class="col-xl-3 col-md-3 col-sm-12 col-xs-12" style="display: block;">
						<select name="ct" id="catSearch" class="form-control selecter" style="height: 38px;width:100px !important;font-size: 1rem;">
							<option value="" {{ ($ct=='') ? 'selected="selected"' : '' }}> Product </option>
							<option value="company" {{ ($ct=='company') ? 'selected="selected"' : '' }}> Company </option>

						</select>
					</div>

					<div class="col-xl-7 col-md-7 col-sm-12 col-xs-12" style="height: 38px;font-size: 1rem;">
						<input name="q" class="form-control keyword" required="required"  type="text" placeholder="{{ t('What?') }}" value="{{ $keywords }}">
					</div>
					{{-- <input type="hidden" id="lSearch" name="l" value="{{ $qLocationId }}">
					<input type="hidden" id="lSearch" name="c" value="{{ ((isset($cat)) ? $cat->tid : '') }}">
					<input type="hidden" id="lSearch" name="sc" value="{{ ((isset($subCat)) ? $subCat->tid : '') }}"> --}}

					{{--
					<div class="col-xl-3 col-md-3 col-sm-12 col-xs-12 search-col locationicon">
						<i class="icon-location-2 icon-append"></i>
						<input type="text" id="locSearch" name="location" class="form-control locinput input-rel searchtag-input has-icon tooltipHere"
							   placeholder="{{ t('Where?') }}" value="{{ $qLocation }}" title="" data-placement="top"
							   data-toggle="tooltip" type="button"
							   data-original-title="{{ t('Enter a city name OR a state name with the prefix ":prefix" like: :prefix', ['prefix' => t('area:')]) . t('State Name') }}">
					</div>

					--}}
					<!--
					<input type="hidden" id="rSearch" name="r" value="{{ $qAdmin }}"> -->

					<div class="col-xl-1 col-md-2 col-sm-12 col-xs-12">
						<button  style="border:0px;width:50px !important;padding-top:5px;"> <!-- class="btn btn-primary btn-search btn-block" --->
							<i class="fa fa-search" style="margin-left:-8px;"></i> <strong>{{ t('Find') }}</strong>
						</button>
					</div>

				</div>
			</form>
		</div>
	</div>
