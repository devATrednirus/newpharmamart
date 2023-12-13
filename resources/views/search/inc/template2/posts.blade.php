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
		$alttag =  null;
		if ($pictures->count() > 0) {
			if(file_exists(storage_path($pictures->first()->filename))) {
					$postImg = resize($pictures->first()->filename, 'medium');
			} else {
					$postImg = resize($pictures->first()->filename, 'medium');
					$postImg = str_replace('storage','storage/app',$postImg);
			}


			$alttag = $pictures->first()->alttag;

		} else {
			$postImg = resize(config('larapen.core.picture.default'));
		}
		if(!$alttag){
			$alttag = $post->title;
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

	<div class="white-block" id="{{ $post->id}}">
				<div class="col-md-12">

	<div  class="item-list">

		<h2 class="item_title" id="<?php echo slugify($post->title);?>" style="font-size: 22px;color: red;
    line-height: 30px;">
						<?php $attr = ['slug' => slugify($post->title), 'id' => $post->id]; ?>
						{{$post->title}}
					</h2>
		<div class="row">
			<div class="col-md-4 col-sm-4">
			<div class="row">
				<div class="add-image col-md-10">

					<?php $attr = ['slug' => slugify($post->title), 'id' => $post->id]; ?>

						<img id="changeMe<?=$post->id?>" class="img-thumbnail no-margin" src="{{ $postImg }}" alt="{{$alttag}}">
				</div>

				<?php
				if($pictures->count()>2)
				{
				?>
				<div class="col-md-2" style="border-left: solid 1px #ccc; border-right: solid 1px #ccc;">
				<?php

				$moreimg=DB::table('pictures')->where(['post_id'=>$post->id])->get();
				foreach($moreimg as $imgrow)
				{
				?>
				<div class="col-md-12">
				<div style="border: solid 1px #000;
    padding: 6px;">
				<img id="preview<?=$imgrow->id?>" onclick="get_images(<?=$post->id?>,<?=$imgrow->id?>)" src="/storage/<?=$imgrow->filename?>">
				</div>
				</div>
				<?php
				}
				?>
				</div>
				<?php
				}
				?>
				<br>
				<br>
				<center>
				<a class="custom_btn bg_shop_red send_message" style="margin-bottom: 16px;
    background: green;
    padding: 10px 10px 10px 10px;
    height: 40px;
	margin-top: 10px;
    margin-left: 14px;
	" data-toggle="modal" data-id="{{ $post->id }}" href="#contactUser">  <span> Get Quote </span></a></center>
				</div>
			</div>
			<div class="col-md-8 col-sm-8 p-page-des">
				<div class="ads-details listing-name">
					<div class="short_description text-justify"> <p>{!!$post->short_description!!} </p></div>


				</div>
				@include('post.inc.fields-values')
			<div class="description des-with-pro-list" >
				<div class="table-responsive" id="fulldetails<?=$post->id?>"style="display:none;"> {!! transformDescription($post->description) !!} </div>
			<div id="rr<?=$post->id?>">
			<?php
			if($post->description)
			{
				 $content=strip_tags($post->description);
		//$pos=strpos(@$content,' ',500);
		echo substr($content,0,500);

			?>
			&nbsp; &nbsp;

			<?php
			}
			?>
			</div>
			<?php
			if($post->description)
			{
			?>
			<a style="color:#bf2626; padding:8px 12px; background: #0c9759; color: #fff; border-radius:5px; data-toggle="modal" data-id="{{ $post->id }}" href="#contactUser"font-size:16px; font-weight: 600;" id="view_more_button<?=$post->id?>" onclick="openfulldetail(<?=$post->id?>)">View More</a>
			<?php
			}
			?>
			</div>
			<br>
			<center>
				<a class="custom_btn bg_shop_red send_message" style="margin-bottom: 16px;" data-toggle="modal" data-id="{{ $post->id }}" href="#contactUser"><span> Submit Query </span></a> &nbsp; &nbsp;
			  </center>
			</div>




		</div>
		<div class="row">



		</div>


	</div>

	</div>
	</div>
	<script>
	function openfulldetail(id)
	{
		var a = $("#view_more_button"+id).text();
		$("#fulldetails"+id).toggle();
		$("#rr"+id).toggle();
		if(a=='View More')
		{
			$("#view_more_button"+id).text('View Less');
		}
		else{
			$("#view_more_button"+id).text('View More');
		}
	}
	function get_images(a,c) {
		let b=$('#preview'+c).attr('src');
    $('#changeMe'+a).prop('src', b);
}
$('html, body').animate({scrollTop: $(window.location.hash).offset().top - 50}, 1000);
	</script>
