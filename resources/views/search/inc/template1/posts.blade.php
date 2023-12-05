<?php



		// Get Pack Info
        $package = null;

        $cacheId = 'package.' . $post->py_package_id . '.' . config('app.locale');
        $package = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
            $package = \App\Models\Package::findTrans($post->py_package_id);
            return $package;
        });

		// Get PostType Info
		/*$cacheId = 'postType.' . $post->post_type_id . '.' . config('app.locale');
    	$postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
            $postType = \App\Models\PostType::findTrans($post->post_type_id);
			return $postType;
		});
		if (empty($postType)) continue;
  */
		// Get Post's Pictures
		$pictures = \App\Models\Picture::where('post_id', $post->id)->orderBy('position')->orderBy('id');
		if ($pictures->count() > 0) {
			$postImg = resize($pictures->first()->filename, 'medium');
		} else {
			$postImg = resize(config('larapen.core.picture.default'));
		}

		// Get the Post's City
		$cacheId = config('country.code') . '.city.' . $post->city_id;
    	$city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
            $city = \App\Models\City::find($post->city_id);
			return $city;
		});


		// Convert the created_at date to Carbon object
		$post->created_at = \Date::parse($post->created_at)->timezone(config('timezone.id'));


		// Category
		$cacheId = 'category.' . $post->category_id . '.' . config('app.locale');
		$liveCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
			$liveCat = \App\Models\Category::find($post->category_id);
			return $liveCat;
		});

		// Check parent
		if (empty($liveCat->parent_id)) {
			$liveCatParentId = $liveCat->id;
			$liveCatType = $liveCat->type;
		} else {
			$liveCatParentId = $liveCat->parent_id;

			$cacheId = 'category.' . $liveCat->parent_id . '.' . config('app.locale');
			$liveParentCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($liveCat) {
				$liveParentCat = \App\Models\Category::find($liveCat->parent_id);
				return $liveParentCat;
			});
			$liveCatType = (!empty($liveParentCat)) ? $liveParentCat->type : 'classified';
		}

		// Check translation
		if ($cats->has($liveCatParentId)) {
			$liveCatName = $cats->get($liveCatParentId)->name;
		} else {
			$liveCatName = $liveCat->name;
		}

		$customFields = $post->customFields;

	?>
	<div id="{{slugify($post->title)}}" class="item-list">

		<h2 class="cae-title">
						<?php $attr = ['slug' => slugify($post->title), 'id' => $post->id]; ?>
						{{ str_limit($post->title, 70) }}
					</h2>
		<div class="row">
			<div class="col-md-3 no-padding photobox">
				<div class="add-image">
					<span class="photo-count"><i class="fa fa-camera"></i> {{ $pictures->count() }} </span>
					<?php $attr = ['slug' => slugify($post->title), 'id' => $post->id]; ?>

						<img class="img-thumbnail no-margin" src="{{ $postImg }}" alt="img">



				</div>
			</div>
			<div class="col-md-9 com-des-right">
				<div class="ads-details listing-name">
					<div class="short_description">{!!$post->short_description !!}</div>
					@include('post.inc.fields-values')

					<div class="description"><div class="table-responsive"> {!! transformDescription($post->description) !!} </div></div>

					<div class="other-listing-btn">
						<a class="btn pull-right btn-md btn-default send_message" data-toggle="modal" data-id="{{ $post->id }}" href="#contactUser"><i class="icon-mail-2"></i> <span> Submit Query </span></a>
					</div>
				</div>
			</div>

		</div>
		<div class="row">



		</div>



	</div>
