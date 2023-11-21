<!-- <style>table > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th { background-color: #efefef; }
td{padding:1px;}</style> -->
<?php
if (!isset($cacheExpiration)) {
    $cacheExpiration = (int)config('settings.other.cache_expiration');
}

 
$keywords = rawurldecode(request()->get('q'));

$searched_keyword = $keywords;
if(!$keywords){

	if(isset($subCat)){
		$keywords = "Looking for ".$subCat->name;
                 $sub = $subCat->name;
	}
	else if(isset($cat)){
		$keywords = "Looking for ".$cat->name;
                  $sub = $cat->name;

	}


}


 
if($keywords!="" && isset($city)){

		$keywords.=" in ".$city->name;
}

$i=0;
?>

@if (isset($paginator) and $paginator->getCollection()->count() > 0)
	<?php
		if (!isset($cats)) {
			$cats = collect([]);
		}
  
		foreach($paginator->getCollection() as $key => $post):

      $i=$i+1;

			

		$sUser = \App\Models\User::with('city')->where('id', $post->user_id)->first();

		 
			//dump([$key]);
		if (empty($countries) or !$countries->has($post->country_code)) continue;
	
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
		$alttag = null;
		if ($pictures->count() > 0) {
			$postImg = resize($pictures->first()->filename, 'medium');
			$alttag = $pictures->first()->alttag;
		} else {
			$postImg = resize(config('larapen.core.picture.default'));
			
		}
		if(!$alttag)
			$alttag = $post->title;
  
		// Get the Post's City
		$cacheId = config('country.code') . '.city.' . $post->city_id;
    	$city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
            $city = \App\Models\City::find($post->city_id);
			return $city;
		});
          
		if (empty($city)) continue;
	
		// Convert the created_at date to Carbon object
		$post->created_at = \Date::parse($post->created_at)->timezone(config('timezone.id'));
		$post->created_at = $post->created_at->ago();
		
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
	?>
	<div class="item-list listing-col-box" data-id="{{$post->user_id}}">
        @if (isset($package) and !empty($package))
            @if ($package->ribbon != '')
                <div class="cornerRibbons {{ $package->ribbon }}">
					<a href="#"> {{ $package->short_name }}</a>
				</div>
            @endif
        @endif
		<?php
		
		$url=$_SERVER['REQUEST_URI'];
		$arr =explode('/',$url);
		 $search_tags=in_array("tag", $arr);
         if($search_tags)
		 {
			 $i=0;
			 $tag = str_replace('-','',$arr[2]);
			 $posts=DB::table('posts')->select('posts.id as post_id','posts.title','posts.description',
			 'posts.short_description','users.name','users.address1','users.address2',
			 'users.pincode','users.username','users.id','users.city_id','cities.name as city_name','posts.user_id')
			 ->join('users','users.id','=','posts.user_id')
			 ->join('cities','cities.id','=','users.city_id')
 			 ->whereRaw('FIND_IN_SET(\''.$tag.'\', REPLACE(LOWER(posts.tags)," ","") ) > 0')->get();
			 
			 foreach($posts as $post)
			 {
				 $i=$i+1;
				 $pictures=DB::table('pictures')->where(['post_id'=>$post->post_id])->get();
                $img='';				
				foreach($pictures as $imgrow)
				 {
					 $img = $imgrow->filename;
 					 $alttag = $imgrow->alttag;					 
				 }
				 if(!$alttag){
 					$alttag = $post->title;
				 }
			?>
			
			<div class="row">
			<div class="col-md-3 col-xs-12 photobox listing-img-block">
				<div class="add-image listing-img-inner">
					<span class="photo-count"><i class="fa fa-camera"></i> <?=$pictures->count();?> </span>
					
					<a href="<?=lurl('/')?>/detail/<?=$post->post_id?>/<?=slugify(@$post->title)?>">
						<img class="img-thumbnail no-margin" loading="lazy" src="<?=lurl('/')?>/storage/<?=$img?>" alt="{{$alttag}}" width="320px" height="270px">
					</a>
				</div>
			</div>
	
			<div class="col-md-6 col-xs-12 listing-details-col" >
				<div class="ads-details listing-name">
					<h2 class="add-title" id="sony">
						
						<a target="_blank" href="<?=lurl('/')?>/detail/<?=$post->post_id?>/<?=slugify(@$post->title)?>">{{ str_limit(@$post->title, 70) }} 
						<?php
								//dd($post);
							if($searched_keyword!="" && preg_match('/'.$searched_keyword.'/i', $post->description)){ 
							//	echo "<small>offering ".$searched_keyword."</small>";
							}


						?>


						</a>
					</h2>
					
					

					
				</div>
	
				@if (config('plugins.reviews.installed'))
					@if (view()->exists('reviews::ratings-list'))
						@include('reviews::ratings-list')
					@endif
				@endif

				<div class="readmores" style="height:100px; overflow:hidden;padding:6px 0px 0px 0px;">{!! \Illuminate\Support\Str::limit($post->short_description, 150, $end='...')  !!}</div>

				
				<?php 
				$v=strip_tags($post->short_description);
				 $strcount = $strcount=strlen($v);
				if($strcount>150)
				{
					?>
					<a href="<?=lurl('/')?>/detail/<?=$post->post_id?>/<?=slugify(@$post->title)?>" style='color: red; font-weight: bold;'>Read More...</a>
				<?php
				}
				?>
			</div>
	
			<div class="col-md-3 col-xs-12 lis-contact-col">
			    <span class="info-row">
					    
						<?php $attr = ['countryCode' => config('country.icode'), 'username' => @$post->username]; ?>
						<h4><a class="company-name" style="color: #f12227; font-weight: 700;" target="_blank" href="{{ lurl(trans('routes.v-search-username', $attr), $attr) }}">
						 	{{ @$post->name }}
							
						</a></h4>

					{{--	<span class="date"><i class="icon-clock"></i> {{ $post->created_at }} </span>
						@if (isset($liveCatParentId) and isset($liveCatName))
							<span class="category">
								<i class="icon-folder-circled"></i>&nbsp;
								<a href="{!! qsurl(trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge(request()->except('c'), ['c'=>$liveCatParentId])) !!}" class="info-link">{{ $liveCatName }}</a>
							</span>
						@endif
					 --}}
						
				</span>
				
				
					<div class="c-address">
					     <?=$post->city_name?>
						 <br>
							{{$post->address1}}, {{$post->address2}}, {{$post->pincode}}
					</div>
				
			
			
				{{--
				<h4 class="item-price">
					@if (isset($liveCatType) and !in_array($liveCatType, ['not-salable']))
						@if ($post->price > 0)
							{!! \App\Helpers\Number::money($post->price) !!}
						@else
							{!! \App\Helpers\Number::money(' --') !!}
						@endif
					@else
						{{ '--' }}
					@endif
				</h4>
				--}}
			 
			 

				<a class="btns view-number send_message" data-toggle="modal" data-id="{{ $post->id }}" href="#contactUser"> <span> {{ t('Send a message') }} </span></a>
						<?php $attr = ['countryCode' => config('country.icode'), 'username' => $post->username]; ?>
						<a class="btns c-supllier" target="_blank" href="{{ lurl(trans('routes.v-search-username', $attr), $attr) }}">
							View Website
						</a>
			</div>

		</div>
		
			<?php
			 }
		 }
		 else
		 {
 		?>
		<div class="row">
			<div class="col-md-3 col-xs-12 photobox listing-img-block">
				<div class="add-image listing-img-inner">
					<span class="photo-count"><i class="fa fa-camera"></i> {{ $pictures->count() }} </span>
					<?php $attr = ['slug' => slugify($post->title), 'id' => $post->id]; ?>
					<a href="{{ lurl($post->uri, $attr) }}">
						<img class="img-thumbnail no-margin" loading="lazy" src="{{ $postImg }}" alt="{{$alttag}}" width="320px" height="240px">
					</a>
				</div>
			</div>
	
			<div class="col-md-6 col-xs-12 listing-details-col" >
				<div class="ads-details listing-name">
					<h2 class="add-title" id="sony">
						<?php $attr = ['slug' => slugify($post->title), 'id' => $post->id]; ?>
						<a target="_blank" href="{{ lurl($post->uri, $attr) }}">{{ str_limit($post->title, 70) }} 
						<?php
								//dd($post);
							if($searched_keyword!="" && preg_match('/'.$searched_keyword.'/i', $post->description)){ 
								echo "<small>offering ".$searched_keyword."</small>";
							}


						?>


						</a>
					</h2>
					
					

					
				</div>
	
				@if (config('plugins.reviews.installed'))
					@if (view()->exists('reviews::ratings-list'))
						@include('reviews::ratings-list')
					@endif
				@endif

				<div class="readmores" style="height:100px; overflow:hidden;padding:6px 0px 0px 0px;">{!! \Illuminate\Support\Str::limit(strip_tags($post->short_description), 300, $end='...')  !!}</div>
                <?php 
				$v=strip_tags($post->short_description);
				 $strcount= strlen($v);
				if($strcount>300)
				{
					echo "<a href=".lurl($post->uri, $attr)." style='color: red; font-weight: bold;'>Read More...</a>";
				}
				?>
				
				
			</div>
	
			<div class="col-md-3 col-xs-12 lis-contact-col">
			    <span class="info-row">
					    
						<?php $attr = ['countryCode' => config('country.icode'), 'username' => $post->username]; ?>
						<h4><a class="company-name" target="_blank" href="{{ lurl(trans('routes.v-search-username', $attr), $attr) }}">
						 	{{ $post->company_name }}
							
						</a></h4>

					{{--	<span class="date"><i class="icon-clock"></i> {{ $post->created_at }} </span>
						@if (isset($liveCatParentId) and isset($liveCatName))
							<span class="category">
								<i class="icon-folder-circled"></i>&nbsp;
								<a href="{!! qsurl(trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge(request()->except('c'), ['c'=>$liveCatParentId])) !!}" class="info-link">{{ $liveCatName }}</a>
							</span>
						@endif
					 --}}
						<p class="listing-address">
							<a href="{!! qsurl(trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge(request()->except(['l', 'location']), ['l'=>$post->city_id])) !!}" class="info-link">{{ $city->name }}</a> {{ (isset($post->distance)) ? '- ' . round(lengthPrecision($post->distance), 2) . unitOfLength() : '' }}
						</p>
				</span>
				
				@if(isset($sUser->city))
					<div class="c-address">
							{{$sUser->address1}}, {{$sUser->address2}}, {{$sUser->city->name}} {{($sUser->city->subAdmin1 && $sUser->city->name!=$sUser->city->subAdmin1->name)?$sUser->city->subAdmin1->name:''}} {{$sUser->pincode}}
					</div>
					@endif
			        
			      
				{{--
				<h4 class="item-price">
					@if (isset($liveCatType) and !in_array($liveCatType, ['not-salable']))
						@if ($post->price > 0)
							{!! \App\Helpers\Number::money($post->price) !!}
						@else
							{!! \App\Helpers\Number::money(' --') !!}
						@endif
					@else
						{{ '--' }}
					@endif
				</h4>
				--}}
			 
			 

				<a style="max-width: initial !important;" class="btns view-number send_message" data-toggle="modal" data-id="{{ $post->id }}" href="#contactUser"> <span> {{ t('Send a message') }} </span></a>
						<?php $attr = ['countryCode' => config('country.icode'), 'username' => $post->username]; ?>
						<a style="max-width: initial !important;" class="btns c-supllier" target="_blank" href="{{ lurl(trans('routes.v-search-username', $attr), $attr) }}">
							View Website
						</a>
			</div>

		</div>
		
		<?php
         }
			$otherPosts = \App\Models\Post::where('user_id',$post->user_id)->where('id','<>',$post->id)->limit(3)->get();;
			

		?>

		@if($otherPosts->count()>0)

		<!-- static Content -->
									<div class="related-pro-bottom">
										<div class="row">
										
											@foreach($otherPosts as $related)
											    <div class="col-lg-4 col-md-4 cl-sm-4 related-pro-col">
												    <div class="pro-thumb" id="otherPosts">
												    	<?php

												    	//dd($related);
														$alttag = null;
												    		if ($related->picture ) {
																$relatedImg = resize($related->picture->filename, 'medium');
																if ($related->picture->alttag ) {
																	$alttag = $related->picture->alttag;
																}
															} else {
																$relatedImg = resize(config('larapen.core.picture.default'));
															}
															 if(!$alttag) {
																$alttag = $related->title;
															}
															$related->uri = trans('routes.v-post', ['slug' => slugify($related->title), 'id' => $related->id]);
									 
														?>
												         <a href="{{ lurl($related->uri) }}">
															<img  src="{{ $relatedImg }}" alt="<?php echo $alttag;?>" loading="lazy">
														</a>
													</div>
													<div class="related-pro-des">
													    <h4><a href="{{ lurl($related->uri) }}">{{$related->title}}</a></h4>
										 
														<a class="btn  btn-md btn-default send_message get-quote" data-toggle="modal" data-id="{{ $related->id }}" href="#contactUser">Get Quote</a>
													</div>
												</div>
												@endforeach
											

										</div>
									</div>
		@endif

	</div>

		@if((($key+1)%15==0) || ($paginator->getCollection()->count()<15 && ($key+1 == $paginator->getCollection()->count())))
 			<?php

 			$bannerCat="";
 			if(isset($subCat)){

 				$relatedCat = \App\Models\Category::where('parent_id',$cat->id)->where('id','<>',$subCat->id)->limit(5)->inRandomOrder()->get();
 				$bannerCat=$subCat;
 			}
 			else if(isset($cat)){

 				$relatedCat = \App\Models\Category::where('parent_id','0')->where('id','<>',$cat->id)->limit(5)->inRandomOrder()->get();
 				$bannerCat=$cat;

 			}
 			else{

 				$relatedCat = \App\Models\Category::where('parent_id','0')->limit(5)->inRandomOrder()->get();
 			}


 			$banner  = \App\Models\Banner::with('user')->where('location','listing')->where('active','1')->where(function($query)use($bannerCat){

	            $query->where('category_id','0');

	            if($bannerCat!=""){

	            	$query->orWhere('category_id',$bannerCat->id);
	 				

	 			}

	           
	            
	        })->inRandomOrder()->first();
 			  
 			?>

 			@if($banner)
 				<div class="banners-ads">
					<?php $attr = ['countryCode' => config('country.icode'), 'username' => $banner->user->username]; ?>
				    <div class="ads-img"><a target="_blank" href="{{ lurl(trans('routes.v-search-username', $attr), $attr) }}"><img src="/storage/{{$banner->filename}}" loading="lazy" width="320px" height="320px"></a></div>
				</div>
 			@endif

 			@if($relatedCat->count()>0)
	
 			<div class="related-cate-block new">
					<h2>Related Categories we serve</h2>
					<div class="related-cate-inner">
						 @foreach($relatedCat as $related)

						<?php 

						 	if(isset($subCat)){

				 				$attr = ['countryCode' => config('country.icode'), 'catSlug' => $cat->slug, 'subCatSlug' => $related->slug];
				 				$url = lurl(trans('routes.v-search-subCat', $attr), $attr);
								$heightimg='120';
					 		}
					 		else{

					 			$attr = ['countryCode' => config('country.icode'), 'catSlug' => $related->slug];
					 			$url = lurl(trans('routes.v-search-cat', $attr), $attr);
								$heightimg='200';
					 		}
						?>
						
					    <div class="related-pro">
						    <div class="related-pro-inner">
						    <div class="re-pic">
							   <a href="/category/<?=$related->slug?>"> <img src="{{ \Storage::url($related->picture) . getPictureVersion() }}" alt="{{ $related->name }}" loading="lazy" width="220px" height="{{ $heightimg }}px"></a>
							</div>
							<h3><a href="/category/<?=$related->slug?>">{{$related->name}}</a></h3>
							</div>
						</div> 
						@endforeach

					</div>
			</div>
			 
			@if(isset($subCat))

			<!-- <section class="section-block requirement-form-wrap">
				<div class="section-bg">
					<div class="container-fluid">
						<div class="row">
							    <div class="col-md-6">
								<div class="requirement-form-inner">
							    <h2>Tell us your Requirement</h2>
			                    <form name="quick_query_listing" class="quick_query_form listing_form" onSubmit="return submitQuery(this)">
									<input class="form-control" type="text" placeholder="Tell Us Your Requirement" name="quick_query"  value="{{ old('quick_query',$keywords)}}">
									<?php 

							 
										if(auth()->check()){
											if(auth()->user()->user_type_id!="2"){

												$name = old('quick_query_name', auth()->user()->name);
											}
											else{

												$name = old('quick_query_name', auth()->user()->first_name);
											}

										}
										else{

												$name = old('quick_query_name');;

												 
										}	

									?>
									
									
									
									<input class="form-control" type="text"  name="quick_query_name" placeholder="Name" value="{{$name}}">
									<input class="form-control" type="text" name="quick_query_phone" placeholder="Mobile No." value="{{(auth()->check()) ? auth()->user()->phone : ''}}">
									<input class="btn btn-default" type="submit" value="Submit Requirement">
								</form>
								</div>
								</div>
								 
								
								
								
			                </div>				
						</div>
				</div>
			</section>
 -->
			
			@endif
			
			
			
			@endif

			 
	
	
	@endif
	
	
	
	
	
	
		
	
	
	<?php endforeach; ?>
@else
	<div class="p-4" style="width: 100%;">
<div class="adds-wrapper">
<div class="item-list listing-col-box">
<?php
		
		$url=$_SERVER['REQUEST_URI'];
		$arr =explode('/',$url);
		 $search_tags=in_array("tag", $arr);
         if($search_tags)
		 {
			 $i=0;
			 $tag = str_replace('-',' ',$arr[2]);
			 $posts=DB::table('posts')->select('posts.id as post_id','posts.title','posts.description',
			 'posts.short_description','users.name','users.address1','users.address2',
			 'users.pincode','users.username','users.id','users.city_id','cities.name as city_name')
			 ->join('users','users.id','=','posts.user_id')
			 ->join('cities','cities.id','=','users.city_id')
			 ->where('posts.tags','like','%'.$tag.'%')->get();
			 foreach($posts as $post)
			 {
				 $i=$i+1;
				 $pictures=DB::table('pictures')->where(['post_id'=>$post->post_id])->get();
                $img='';				
				foreach($pictures as $imgrow)
				 {
 					 $img=$imgrow->filename;
 					 $alttag=$imgrow->alttag;
				 }
				 if(!$alttag){
					$alttag = $post->title;
				 }
			?>
			<div class="row">
			<div class="col-md-3 col-xs-12 photobox listing-img-block">
				<div class="add-image listing-img-inner">
					<span class="photo-count"><i class="fa fa-camera"></i> <?=$pictures->count();?> </span>
					
					<a href="<?=lurl('/')?>/detail/<?=$post->post_id?>/<?=slugify(@$post->title)?>">
						<img class="img-thumbnail no-margin" loading="lazy" src="<?=lurl('/')?>/storage/<?=$img?>" alt="{{@$alttag}}" width="320px" height="240px">
					</a>
				</div>
			</div>
	
			<div class="col-md-6 col-xs-12 listing-details-col" >
				<div class="ads-details listing-name">
					<h2 class="add-title" id="sony">
						
						<a target="_blank" href="<?=lurl('/')?>/detail/<?=$post->post_id?>/<?=slugify(@$post->title)?>">{{ str_limit(@$post->title, 70) }} 
						<?php
								//dd($post);
							if($searched_keyword!="" && preg_match('/'.$searched_keyword.'/i', $post->description)){ 
								echo "<small>offering ".$searched_keyword."</small>";
							}


						?>


						</a>
					</h2>
					
					

					
				</div>
	
				@if (config('plugins.reviews.installed'))
					@if (view()->exists('reviews::ratings-list'))
						@include('reviews::ratings-list')
					@endif
				@endif

				<div class="readmores" style="height:100px; overflow:hidden;padding:6px 0px 0px 0px;">{!! \Illuminate\Support\Str::limit(strip_tags($post->short_description), 150, $end='...')  !!}</div>
                               
				 
				<?php 
				$v=strip_tags($post->short_description);
			    $strcount=strlen($v);
				if($strcount>150)
				{
					?>
               <a href="<?=lurl('/')?>/detail/<?=$post->post_id?>/<?=slugify(@$post->title)?>" style='color: red; font-weight: bold;'>Read More...</a>
				<?php
				}
				?>

                          
			</div>
	               
			<div class="col-md-3 col-xs-12 lis-contact-col">
			    <span class="info-row">
					    
						<?php $attr = ['countryCode' => config('country.icode'), 'username' => @$post->username]; ?>
						<h4><a class="company-name" style="color: #f12227; font-weight: 700;" target="_blank" href="{{ lurl(trans('routes.v-search-username', $attr), $attr) }}">
						 	{{ @$post->name }}
							
						</a></h4>

					{{--	<span class="date"><i class="icon-clock"></i> {{ $post->created_at }} </span>
						@if (isset($liveCatParentId) and isset($liveCatName))
							<span class="category">
								<i class="icon-folder-circled"></i>&nbsp;
								<a href="{!! qsurl(trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge(request()->except('c'), ['c'=>$liveCatParentId])) !!}" class="info-link">{{ $liveCatName }}</a>
							</span>
						@endif
					 --}}
						
				</span>
				
				
					<div class="c-address">
					     <?=$post->city_name?>
						 <br>
							{{$post->address1}}, {{$post->address2}}, {{$post->pincode}}
					</div>
				
			
			
				{{--
				<h4 class="item-price">
					@if (isset($liveCatType) and !in_array($liveCatType, ['not-salable']))
						@if ($post->price > 0)
							{!! \App\Helpers\Number::money($post->price) !!}
						@else
							{!! \App\Helpers\Number::money(' --') !!}
						@endif
					@else
						{{ '--' }}
					@endif
				</h4>
				--}}
			 
			 

				<a class="btns view-number send_message" data-toggle="modal" data-id="{{ $post->id }}" href="#contactUser"> <span> {{ t('Send a message') }} </span></a>
						<?php $attr = ['countryCode' => config('country.icode'), 'username' => $post->username]; ?>
						<a class="btns c-supllier" target="_blank" href="{{ lurl(trans('routes.v-search-username', $attr), $attr) }}">
							View Website
						</a>
			</div>

		</div>
		<?php
			 }
		 }
		?>
		</div>
		</div>
		<?php
		if($i==0)
		{
		?>
		
		<p class="no-result">{{ t('No result. Refine your search using other criteria.') }}</p>
		<?php
		}
		?>
		@if(!request()->ajax())
		<?php

 			 
 			if(isset($subCat)){
 				
 				$relatedCat = \App\Models\Category::where('parent_id',$cat->id)->where('id','<>',$subCat->id)->limit(5)->inRandomOrder()->get();
 				 
 			}
 			else if(isset($cat)){

 				$relatedCat = \App\Models\Category::where('parent_id','0')->where('id','<>',$cat->id)->limit(5)->inRandomOrder()->get();
 				
 				
 			}
 			else{

 				$relatedCat = \App\Models\Category::where('parent_id','0')->limit(5)->inRandomOrder()->get();
 			}

 			 

 			  

 			?>
                         
 			@if($relatedCat->count()>0)
	
 			<div class="related-cate-block">
					<h2>Related Categories</h2>
					<div class="related-cate-inner">
						 @foreach($relatedCat as $related)

						<?php 

						 	if(isset($subCat)){

				 				$attr = ['countryCode' => config('country.icode'), 'catSlug' => $cat->slug, 'subCatSlug' => $related->slug];

				 				$url = lurl(trans('routes.v-search-subCat', $attr));
					 		}
					 		else{

					 			$attr = ['countryCode' => config('country.icode'), 'catSlug' => $related->slug];
					 			$url = lurl(trans('routes.v-search-cat', $attr));
					 		}
						?>
						
					    <div class="related-pro">
						    <div class="related-pro-inner">
						    <div class="re-pic">
							   <a href="{{ $url }}"> <img src="{{ \Storage::url($related->picture) . getPictureVersion() }}" alt="{{ $related->name }}" loading="lazy" width="320px" height="140px"></a>
							</div>
							<h3><a href="{{ $url }}">{{$related->name}}</a></h3>
							</div>
						</div> 
						@endforeach

					</div>
			</div>
			
			  

			<div class="help-block listing-help-block">
			    <div class="container-fluid">
					<div class="help-form">
						    <div class="help-form-inner">
							
							    <h3 class="bounce">Let us Help You</h3>
			                    <form name="quick_query_listing" class="quick_query_form listing_form" onSubmit="return submitQuery(this)">
								    <div class="field-box">
									    <div class="input-group">
										    <i class="icon-help"></i>
									        <input type="text" placeholder="Tell Us Your Requirement" name="quick_query"  value="{{ old('quick_query',$keywords)}}">
										</div>
									</div>
									<?php 

							 
										if(auth()->check()){
											if(auth()->user()->user_type_id!="2"){

												$name = old('quick_query_name', auth()->user()->name);
											}
											else{

												$name = old('quick_query_name', auth()->user()->first_name);
											}

										}
										else{

												$name = old('quick_query_name');;

												 
										}	

									?>
									<div class="row">
										
										<div class="col-md-6 field-box" style="padding-right:4px;">
										    <div class="input-group">
											    <i class="icon-user fa hidden-sm"></i>
											    <input type="text"  name="quick_query_name" placeholder="Name" value="{{$name}}">
											</div>
										</div>

										<div class="col-md-6 field-box" style="padding-left:4px;">
										    <div class="input-group">
											    <i class="icon-phone-1"></i>
											    <input type="text" name="quick_query_phone" placeholder="Mobile No." value="{{(auth()->check()) ? auth()->user()->phone : ''}}">
											</div>
										</div>
									</div>
									<div class="field-box">
									    <div class="input-group">

									    	 <input type="hidden" name="l" value="{{old('l',(isset($city)?$city->id:''))}}">
											 <input type="hidden" name="c" value="{{old('c',(isset($cat)?$cat->id:''))}}">
											 <input type="hidden" name="sc" value="{{old('sc',(isset($subCat)?$subCat->id:''))}}">
									        <input type="submit" value="Submit">
										</div>
									</div>
								</form>
								 
							
			                </div>				
						</div>
				</div>
			</div>
			
			
			
			
			
			
			
			@endif
		@endif
		
	</div>
@endif









@section('after_scripts')
	@parent
	<script>
            
          




		function submitQuery(form){

 		 	
		    
 			var form =$(form);
 			
 			$('form').removeClass('active_query');

 			form.addClass('active_query');

 			$(".quick_query_form [type='submit']").attr('disabled','disabled');

		    $.ajax({
					method: 'POST',
					url: '{{ lurl('quick_query') }}',
					data: {
						'quick_query': form.find('[name="quick_query"]').val(),
						'l': form.find('[name="l"]').val(),
						'c': form.find('[name="c"]').val(),
						'sc': form.find('[name="sc"]').val(),
						'quick_query_name': form.find('[name="quick_query_name"]').val(),
						'quick_query_phone': form.find('[name="quick_query_phone"]').val(),
						'_token': $('input[name=_token]').val()
					}
				}).done(function(data) {
					
					$(".quick_query_form [type='submit']").removeAttr('disabled');
					$('#query_type').val(data.type);
					$('#query_id').val(data.id);
					$('#slider_from_email').val(data.email);

					$('[name="quick_query_phone"]').val(form.find('[name="quick_query_phone"]').val());
					$('[name="quick_query_name"]').val(form.find('[name="quick_query_name"]').val());

					

					window.dataLayer =window.dataLayer || [];
					
					window.dataLayer.push({
						'event':'quickQuery','conversionValue':1
					});

				 

					$("#sliderForm #msform fieldset").removeAttr('style').hide(); 
					$("#sliderForm #msform fieldset:eq(0)").show();
					$("#sliderForm").modal({backdrop: 'static', keyboard: false}); 					

				 

				 
					 
					
					 
				}).error(function(response) {
					
					
					$(".quick_query_form [type='submit']").removeAttr('disabled');
					var responseJSON = response.responseJSON;

					if(responseJSON.code==100){
						$('#signin_phone').val(form.find('[name="quick_query_phone"]').val());
						$('#signin_name').val(form.find('[name="quick_query_name"]').val());
						$('#userLogin form').submit();
					}
					else{
						var data = responseJSON.data;


						var msg=[];
						$.each(data, function (index, value) {
							
							if(msg.length==0){
								form.find('[name="'+index+'"]').focus()
							}
							msg.push(value);
						});

						alert(msg.join("\n"));
					}

					
 
					 
				});

				return false;
 		}
	 
	 
		@if ($count->get('all') > 0)
			@if (config('settings.listing.display_mode') == '.grid-view')
				gridView('.grid-view');
			@elseif (config('settings.listing.display_mode') == '.list-view')
				listView('.list-view');
			@elseif (config('settings.listing.display_mode') == '.compact-view')
				compactView('.compact-view');
			@else
				gridView('.grid-view');
			@endif
		@else
			listView('.list-view');
		@endif
		/* Save the Search page display mode */
		var listingDisplayMode = readCookie('listing_display_mode');
		if (!listingDisplayMode) {
			createCookie('listing_display_mode', '{{ config('settings.listing.display_mode', '.grid-view') }}', 7);
		}
		
		/* Favorites Translation */
		var lang = {
			labelSavePostSave: "{!! t('Save ad') !!}",
			labelSavePostRemove: "{!! t('Remove favorite') !!}",
			loginToSavePost: "{!! t('Please log in to save the Ads.') !!}",
			loginToSaveSearch: "{!! t('Please log in to save your search.') !!}",
			confirmationSavePost: "{!! t('Post saved in favorites successfully !') !!}",
			confirmationRemoveSavePost: "{!! t('Post deleted from favorites successfully !') !!}",
			confirmationSaveSearch: "{!! t('Search saved successfully !') !!}",
			confirmationRemoveSaveSearch: "{!! t('Search deleted successfully !') !!}"
		};


                                       

	</script>
<script>
$(document).ready(function(){
    $('#show').click(function() {
      $('.menu').toggle("slide");
    });
});
</script>@endsection



<style>
.name a {
	color: #007eff !important;
	text-transform: capitalize;
	font-size: 15px;
	font-weight: 400;
	/* font-family: oswald; */
	display: inline-block;
	border-radius: 3px;
	margin-top: 10px;
	/* background: #008eac; */
	line-height: normal;
}
.listing-name h5.add-title a {
	color: #fa7722;
	font-size: 20px;
}


.listing-name .item-location i{
	color: #f70000;
}
.listing-name .category i{
	color: #007eff;
}


.listing-name .info-row a{
	color:#424242;
}

.price-box a {
	margin-top: 7px;
}

.btn.send_message {
	background: #16a085;
	color: #fff;
	border-color: #16a085;
}
.btn.premium-btn {
	background: #ee4935!important;
	border-color: #ee4935!important;
}

.sidebar-modern-inner .block-title h5 a {
	color: #fff!important;
}
.sidebar-modern-inner .block-title {
	background: #03b5c6;
}
.sidebar-modern-inner .block-title.has-arrow::after {
	border-color: #03b5c6 transparent transparent!important;
}
.company-about-left p {
	text-align: justify !important;
}
div#show i {
    color: #f61c0d;
}
.price-box a.view-website {
	background: #00c1ea;
	color: #fff !important;
	border-color: #00c1ea;
	max-width: 149px;
	width: 100%;
	font-size: 14px;
}
.modal-content{
	padding-bottom: 25px;
}
#userOTP .modal-header {
    background: #039eb5 !important;
    border-color: #039eb5 !important;
    color: #fff !important;
    }
</style>