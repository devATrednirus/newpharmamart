@if (isset($categoriesOptions) and isset($categoriesOptions['type_of_display']))
@if (isset($categories_featured) and $categories_featured->count() > 0)
	
<section class="section-block featured-pro-wrap">
	    <div class="container-fluid">
		    <div class="section-bg">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<h2 class="featured">Featured Categories</h2>
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
								<div class=" col-md-2">
								    <div class="cards">
								    	        <img src="{{ resize($cat->picture, 'square') . getPictureVersion() }}" alt="{{$alttag}}" loading="lazy" width="100%" height="100%">
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


@if (isset($categories) and $categories->count() > 0)
	@foreach($categories as $key => $cat)
		<?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $cat->slug]; 
		if($cat->alttag){
			$alttag= $cat->alttag;
		} else {
			$alttag= $cat->name;

		}
		 ?>


		<section class="section-block listing-grid">
		    <div class="container-fluid">
			   					<div class="container-fluid">

				<h2><a href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">{{ $cat->name }}</a></h2>	
			    <div class="row">

				        <div class="col-md-3 cate-left-img">
						    <div class="cate-left-img-inner">
								<img src="{{ \Storage::url($cat->picture) . getPictureVersion() }}" alt="{{$alttag}}" loading="lazy" width="100%" height="100%">
								<a class="v-all-btn" href="{{ lurl('/sitemap/'.$cat->slug) }}">View All</a>
							</div>
						</div>
						  
						<div class="col-md-9">
						    <div class="row">
		 
				    	@if (isset($cat->children) and $cat->children->count() > 0)
								@foreach($cat->children as $skey => $scat)
									<?php 
										
										if($skey>=9){
											continue;
										}
										if($scat->alttag){
											$alttag = $scat->alttag;
										} else {
											$alttag = $scat->name;
										}
										$attr = ['countryCode' => config('country.icode'), 'catSlug' => $scat->slug];  ?>
									<div class="col-md-4">

                                                                             
                                                                           
                                                                    
									    <div class="category-item">
										
										    <div class="category-thumbnail"><img src="{{ \Storage::url($scat->picture) . getPictureVersion() }}" alt="{{ $alttag }}" loading="lazy" width="100px" height="100px"></div>
											
											<div class="category-meta">
											<h3 class="main-cate"><a href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">{{ $scat->name }}</a></h3>
											<ul>
												@foreach($scat->children as $key=>$sub_cat)

												<?php 
												if($key>=4){
													break;
												}
												$subattr = ['countryCode' => config('country.icode'), 'catSlug' => $scat->slug, 'subCatSlug' => $sub_cat->slug]; ?>
										 
												<li><a href="{{ lurl(trans('routes.v-search-subCat', $subattr), $subattr) }}">{{ $sub_cat->name }}</a></li>
												@endforeach 
											</ul>
											<a class="" href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">View all</a>

											</div>
										</div>
									</div>
									  
								@endforeach
							@endif
					    
						 </div>
						 </div>

				</div>
				
				</div>
				</div>
		    </div>
		</section>  
	@endforeach
@endif




@endif



<!-- static Find Suppliers from Top Cities -->

@if (isset($location_posts) )
<section class="section-block top-cities-home">
	    <div class="container-fluid">
		    <div class="section-bg">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<h2>Find Suppliers from Top Cities</h2>
							
							<div class="row">
								@foreach($location_posts as $key => $location)
									<?php 
										//$attr = ['countryCode' => config('country.icode'), 'catSlug' => $cat->slug];  
										$attr = ['countryCode' => config('country.icode'),'city'=>slugify($location->name)];
										$fullUrlLocation = lurl(trans('routes.search-city', $attr), $attr);
										
									?>
									<div class="city-col">
										<a href="{!! $fullUrlLocation !!}" title="{{ $location->name }}" >
										    <img src="images/city-1.png" alt="{{$location->name}}" loading="lazy" width="75px" height="75px">
											<h3>{{$location->name}}</h3>
										</a>
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
<!-- static Fetured Categories -->

@section('before_scripts')
	@parent
	@if (isset($categoriesOptions) and isset($categoriesOptions['max_sub_cats']) and $categoriesOptions['max_sub_cats'] >= 0)
		<script>
			var maxSubCats = {{ (int)$categoriesOptions['max_sub_cats'] }};
		</script>
	@endif
@endsection


<style>
 .title-with-bg h2 {
    /* font-size: 42px !important; */
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 600;
    font-size: 69.3333px;
    line-height: 104px;
    color: #000000;
}
.title-with-bg {
    background-size: cover;
    background-position: inherit;
    padding: 34px
px
;
}

.containers {
  position: relative;
 }

.images {
  display: block;
  width: 100%;
  height: auto;
}

.overlays {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: #008CBA;
  overflow: hidden;
  width: 100%;
  height: 100%;
  -webkit-transform: scale(0);
  -ms-transform: scale(0);
  transform: scale(0);
  -webkit-transition: .3s ease;
  transition: .3s ease;
}

.containers:hover .overlays {
  -webkit-transform: scale(1);
  -ms-transform: scale(1);
  transform: scale(1);
}

.texts {
  color: white;
  font-size: 20px;
  position: absolute;
  top: 50%;
  left: 50%;
  -webkit-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  text-align: center;
}

.cards h3 a {
    color: #000;
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 600;
    font-size: 14px;
    line-height: 21px;
    text-align: center;
}
.cards img {
    border-radius: 50%;
    width: 150px;
    height: 145px;
    box-shadow: 0px 4px 4px rgb(0 0 0 / 25%), inset 0px 7px 7px rgb(0 0 0 / 25%);
    margin-bottom: 16px;}
.cards h3 {
    font-size: 14px;
    color: #000;
}
.cards {
    text-align: center;
    margin-bottom: 34px;
}
h2.featured {
    text-align: center;
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 800;
    font-size: 29px;
    line-height: 36px;
}
</style>

