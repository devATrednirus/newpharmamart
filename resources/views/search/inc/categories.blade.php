<?php $sty = '';
if(!empty($_GET['debu'])) {
  if($_GET['debu'] == 1)  {
    echo "search.inc.categories";
    $sty = ' style="border: 1px solid;" ';
  }
} ?>

<?php
if (!isset($cats)) {
    $cats = collect([]);
}

$cats = $cats->groupBy('parent_id');
$subCats = $cats;
if ($cats->has(0)) {
	$cats = $cats->get(0);
}
if ($subCats->has(0)) {
	$subCats = $subCats->forget(0);
}
?>
<?php
	if (
		(isset($subCats) and !empty($subCats) and isset($cat) and !empty($cat) and $subCats->has($cat->tid)) ||
		(isset($cats) and !empty($cats))
	):
?>
@if (isset($subCats) and !empty($subCats) and isset($cat) and !empty($cat))
	@if ($subCats->has($cat->tid))
		<div class="container hide-xs">
			<div class="category-links">
				<ul>
				@foreach ($subCats->get($cat->tid) as $iSubCat)
					@if(isset($countSubCatPosts->get($iSubCat->tid)->total))
					<li>
						<?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $cat->slug, 'subCatSlug' => $iSubCat->slug]; ?>
						<a href="{{ lurl(trans('routes.v-search-subCat', $attr), $attr) }}">
							{{ $iSubCat->name }}
						</a>
					</li>
					@endif
				@endforeach
				</ul>
			</div>
		</div>
	@endif
@else
	@if (isset($cats) and !empty($cats))
		<div class="container hide-xs">
			<div class="category-links">
				<ul>
				@foreach ($cats as $iCategory)
					@if ($iCategory->posts->count() > 0)
					<li>
						<?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $iCategory->slug]; ?>
						<a href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">
							{{ $iCategory->name }}
						</a>
					</li>
					@endif
				@endforeach
				</ul>
			</div>
		</div>
	@endif
@endif
<?php endif; ?>
