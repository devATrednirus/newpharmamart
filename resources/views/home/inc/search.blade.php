<style>
.h-spacer {
    padding-top: 4px;
}

.search-row .icon-append {
    color: #949494;
    font-size: 28px;
    position: absolute;
    top: 13px;
    left: 7px;
}

.skin-blue button.btn-search {
    background-color: #e2bd26;

}
   button.btn.btn-primary.btn-search.btn-block.read-more {
    border-radius: 19px!important;

}
.skin-blue .intro {
    background: #167b6f;
}
</style>

<?php
// Init.
$sForm = [
	'enableFormAreaCustomization' => '0',
	'hideTitles'                  => '0',
	'title'                       => t('Sell and buy near you'),
	'subTitle'                    => t('Simple, fast and efficient'),
	'bigTitleColor'               => '', // 'color: #FFF;',
	'subTitleColor'               => '', // 'color: #FFF;',
	'backgroundColor'             => '', // 'background-color: #444;',
	'backgroundImage'             => '', // null,
	'height'                      => '', // '450px',
	'parallax'                    => '0',
	'hideForm'                    => '0',
	'formBorderColor'             => '', // 'background-color: #333;',
	'formBorderSize'              => '', // '5px',
	'formBtnBackgroundColor'      => '', // 'background-color: #4682B4; border-color: #4682B4;',
	'formBtnTextColor'            => '', // 'color: #FFF;',
];

// Get Search Form Options
if (isset($searchFormOptions)) {
	if (isset($searchFormOptions['enable_form_area_customization']) and !empty($searchFormOptions['enable_form_area_customization'])) {
		$sForm['enableFormAreaCustomization'] = $searchFormOptions['enable_form_area_customization'];
	}
	if (isset($searchFormOptions['hide_titles']) and !empty($searchFormOptions['hide_titles'])) {
		$sForm['hideTitles'] = $searchFormOptions['hide_titles'];
	}
	if (isset($searchFormOptions['title_' . config('app.locale')]) and !empty($searchFormOptions['title_' . config('app.locale')])) {
		$sForm['title'] = $searchFormOptions['title_' . config('app.locale')];
		$sForm['title'] = str_replace(['{app_name}', '{country}'], [config('app.name'), config('country.name')], $sForm['title']);
		if (str_contains($sForm['title'], '{count_ads}')) {
			try {
				$countPosts = \App\Models\Post::currentCountry()->unarchived()->count();
			} catch (\Exception $e) {
				$countPosts = 0;
			}
			$sForm['title'] = str_replace('{count_ads}', $countPosts, $sForm['title']);
		}
		if (str_contains($sForm['title'], '{count_users}')) {
			try {
				$countUsers = \App\Models\User::count();
			} catch (\Exception $e) {
				$countUsers = 0;
			}
			$sForm['title'] = str_replace('{count_users}', $countUsers, $sForm['title']);
		}
	}
	if (isset($searchFormOptions['sub_title_' . config('app.locale')]) and !empty($searchFormOptions['sub_title_' . config('app.locale')])) {
		$sForm['subTitle'] = $searchFormOptions['sub_title_' . config('app.locale')];
		$sForm['subTitle'] = str_replace(['{app_name}', '{country}'], [config('app.name'), config('country.name')], $sForm['subTitle']);
		if (str_contains($sForm['subTitle'], '{count_ads}')) {
			try {
				$countPosts = \App\Models\Post::currentCountry()->unarchived()->count();
			} catch (\Exception $e) {
				$countPosts = 0;
			}
			$sForm['subTitle'] = str_replace('{count_ads}', $countPosts, $sForm['subTitle']);
		}
		if (str_contains($sForm['subTitle'], '{count_users}')) {
			try {
				$countUsers = \App\Models\User::count();
			} catch (\Exception $e) {
				$countUsers = 0;
			}
			$sForm['subTitle'] = str_replace('{count_users}', $countUsers, $sForm['subTitle']);
		}
	}
	if (isset($searchFormOptions['parallax']) and !empty($searchFormOptions['parallax'])) {
		$sForm['parallax'] = $searchFormOptions['parallax'];
	}
	if (isset($searchFormOptions['hide_form']) and !empty($searchFormOptions['hide_form'])) {
		$sForm['hideForm'] = $searchFormOptions['hide_form'];
	}
}

// Country Map status (shown/hidden)
$showMap = false;
if (file_exists(config('larapen.core.maps.path') . config('country.icode') . '.svg')) {
	if (isset($citiesOptions) and isset($citiesOptions['show_map']) and $citiesOptions['show_map'] == '1') {
		$showMap = true;
	}
}
?>
@if (isset($sForm['enableFormAreaCustomization']) and $sForm['enableFormAreaCustomization'] == '1')

	@if (isset($firstSection) and !$firstSection)
		<div class="h-spacer"></div>
	@endif


	<?php $sty = '';
	if(!empty($_GET['debu'])) {
	  if($_GET['debu'] == 1)  {
	    echo "home.inc.search";
	    $sty = ' style="border: 1px solid;" ';
	  }
	} ?>

	<?php $parallax = (isset($sForm['parallax']) and $sForm['parallax'] == '1') ? 'parallax' : ''; ?>
	<div class="wide-intro home-search {{ $parallax }}" style="border: 0px solid; width: 390px;  height:40px;">
		<div class="dtable hw100">
			<div class="dtable-cell hw100">
				<div class="container text-center">

					@if ($sForm['hideTitles'] != '1')
						{{-- <h1 class="intro-title animated fadeInDown"> {{ $sForm['title'] }} </h1> --}}
						{{-- <p class="sub animateme fittext3 animated fadeIn">
							{!! $sForm['subTitle'] !!}
						</p> --}}
					@endif

					@if ($sForm['hideForm'] != '1')
						<div class="search-row animated fadeInUp" >
							<?php $attr = ['countryCode' => config('country.icode')]; ?>
							<form id="search" name="search" action="{{ lurl(trans('routes.v-search', $attr), $attr) }}" method="GET">
								<div class="row m-0">
									<div class="col-sm-10 col-xs-12 search-col relative"     style="max-width: 57% !important;">
										<i class="icon-docs icon-append"></i>
										<input style="width:200px;" type="text" name="q" class="form-control keyword has-icon" placeholder="{{ t('What?') }}" value="">
									</div>
									{{--
									<div class="col-sm-5 col-xs-12 search-col relative locationicon">
										<i class="icon-location-2 icon-append"></i>
										<input type="hidden" id="lSearch" name="l" value="">
										@if ($showMap)
											<input type="text" id="locSearch" name="location" class="form-control locinput input-rel searchtag-input has-icon tooltipHere"
												   placeholder="{{ t('Where?') }}" value="" title="" data-placement="bottom"
												   data-toggle="tooltip" type="button"
												   data-original-title="{{ t('Enter a city name OR a state name with the prefix ":prefix" like: :prefix', ['prefix' => t('area:')]) . t('State Name') }}">
										@else
											<input type="text" id="locSearch" name="location" class="form-control locinput input-rel searchtag-input has-icon"
												   placeholder="{{ t('Where?') }}" value="">
										@endif
									</div>
									--}}

									<div class="col-sm-2 col-xs-12 search-col">
										<button class="btn btn-primary btn-search btn-block read-more" style="margin-top:13px;padding:3px; width:50px;">
											<i class="icon-search"></i> <strong>{{ t('Find') }}</strong>
										</button>
									</div>

								</div>
							</form>
						</div>
					@endif




				</div>
			</div>
		</div>


	</div>

@else

	@include('home.inc.spacer')
	<div class="container">
		<div class="intro">
			<div class="dtable hw100">
				<div class="dtable-cell hw100">
					<div class="container text-center">
						<?php $sty = '';
						if(!empty($_GET['debu'])) {
						  if($_GET['debu'] == 1)  {
						    echo "home.inc.search";
						    $sty = ' style="border: 1px solid;" ';
						  }
						} ?>
						<div class="search-row fadeInUp">
							<?php $attr = ['countryCode' => config('country.icode')]; ?>
							<form id="search" name="search" action="{{ lurl(trans('routes.v-search', $attr), $attr) }}" method="GET">
								<div class="row m-0">
									<div class="col-sm-5 col-xs-12 search-col relative"   style="max-width: 57% !important;">
										<i class="icon-docs icon-append"></i>
										<input type="text" style="width:252px;" name="q" class="form-control keyword has-icon" placeholder="{{ t('What?') }}" value="">
									</div>

									{{-- <div class="col-sm-5 col-xs-12 search-col relative locationicon">
										<i class="icon-location-2 icon-append"></i>
										<input type="hidden" id="lSearch" name="l" value="">
										@if ($showMap)
											<input type="text" id="locSearch" name="location" class="form-control locinput input-rel searchtag-input has-icon tooltipHere"
												   placeholder="{{ t('Where?') }}" value="" title="" data-placement="bottom"
												   data-toggle="tooltip" type="button"
												   data-original-title="{{ t('Enter a city name OR a state name with the prefix ":prefix" like: :prefix', ['prefix' => t('area:')]) . t('State Name') }}">
										@else
											<input type="text" id="locSearch" name="location" class="form-control locinput input-rel searchtag-input has-icon"
												   placeholder="{{ t('Where?') }}" value="">
										@endif
									</div> --}}

									<div class="col-sm-2 col-xs-12 search-col">
										<button class="btn btn-primary btn-search btn-block read-more"

										@if(str_contains(url()->current(), '/contact') || str_contains(url()->current(), '/detail'))
  style="margin-top: 5px;padding: 0px;width: 113px;"

												@else
											 	style="margin-top:13px;padding:3px; width:50px;"
										@endif



										 >  <!---    --->
											<i class="icon-search"></i> <strong>{{ t('Find') }}</strong>
										</button>
									</div>
									{!! csrf_field() !!}
								</div>
							</form>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

@endif
