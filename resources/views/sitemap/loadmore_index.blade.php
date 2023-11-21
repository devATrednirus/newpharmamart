@foreach ($categories as $iSubCatMicro)
<div class=" col-md-3 col-lg-3 col-sm-6   moreboxes">
	<div class="post-box">
		<?php
		if ($iSubCatMicro->alttag) {
			$alttag = $iSubCatMicro->alttag;
		} else {
			$alttag = $iSubCatMicro->name;
		}

		$attr =  ['countryCode' => config('country.icode'), 'catSlug' => $iSubCatMicro->slug, 'subCatSlug' => $iSubCatMicro->slug];
		?>
		<a href="{{ lurl('/'.trans('routes.v-search-subCat', $attr), $attr) }}">
			<img src="{{ resize($iSubCatMicro->picture, 'small') }}" alt="{{ $alttag }}" loading="lazy" class="img-fluid post-img ">

			<div class="post-text">{{ \Illuminate\Support\Str::limit($iSubCatMicro->name, 50, $end='...')  }} ({{$iSubCatMicro->posts->count()}})</div>
		</a>
	</div>
</div>
@endforeach