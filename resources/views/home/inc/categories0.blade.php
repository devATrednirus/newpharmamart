<?php $sty = '';
if(!empty($_GET['debu'])) {
  if($_GET['debu'] == 1)  {
    echo "home.inc.categories";
    $sty = ' style="border: 1px solid;" ';
  }
} ?>


@include('home.inc.featcats')


@if (isset($categoriesOptions) and isset($categoriesOptions['type_of_display']))
<?php $User = \App\Models\User::where('photo','!=','')->whereIn('package_id' , [1,2,3,16])->limit(12)->get();
   ?>
@if (isset($categories) and $categories->count() > 0)
	@foreach($categories as $key => $cat)

  <?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $cat->slug];
 if($cat->alttag){
   $alttag= $cat->alttag;
 } else {
   $alttag= $cat->name;

 }
  ?>

     @if( $loop->iteration % 2 != 0)
          @include('home.inc.promoted')
     @else
          @include('home.inc.cardlayout')


     {{--  OLD CODE
		<section class="section-block listing-grid">
		    <div class="container-fluid">
			   					<div class="container-fluid">

				<h2 style="text-align: center;font-size:30;margin:20px 0px;"><a href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">{{ $cat->name }}</a></h2>
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
    --}}



    @endif




	@endforeach
@endif




@endif



@if (isset($location_posts) )

@include('home.inc.companies')
@include('home.inc.cities')
<!-- static Find Suppliers from Top Cities -->


{{--
  <section class="section-block featured-pro-wrap">
	    <div class="container-fluid">
		    <div class="section-bg">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<h2 class="feat">Featured Company home.inc.categories</h2>
							<div class="row">
								@foreach($User as $user)
								    <div class="col-md-2">
								    <div class="cards">

										<a href="/{{$user->username}}"><img src="{{ resize($user->photo, 'square') . getPictureVersion() }}" alt="{{ $user->name }}" loading="lazy"></a>
										<h3><a href="/{{$user->username}}">{{ $user->name }}</a></h3>

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




<section class="section-block top-cities-home">
	    <div class="container-fluid">
		    <div class="section-bg">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<h2 style="text-align: center;font-size:30;margin:20px 0px;">Find Suppliers from Top Cities home.inc.categories</h2>

							<div class="row">
								@foreach($location_posts as $key => $location)
									<?php
										//$attr = ['countryCode' => config('country.icode'), 'catSlug' => $cat->slug];
										$attr = ['countryCode' => config('country.icode'),'city'=>slugify($location->name)];
										$fullUrlLocation = lurl(trans('routes.search-city', $attr), $attr);

									?>
									<div class="city-col">

										<a href="{!! $fullUrlLocation !!}" title="{{ $location->name }}" >
                                                                                    @if($location->name =='Chandigarh')
										    <img src="images/Chandigarh.png" alt="{{$location->name}}" loading="lazy">
									             @elseif($location->name =='Assam')
										    <img src="images/Assam.png" alt="{{$location->name}}" loading="lazy">
                                                                                     @elseif($location->name =='Punjab')
										    <img src="images/Punjab.png" alt="{{$location->name}}" loading="lazy">
                                                                                    @elseif($location->name =='Himachal Pradesh')
										    <img src="images/Himachal Pradesh.png" alt="{{$location->name}}" loading="lazy">
                                                                                    @elseif($location->name =='Haryana')
										    <img src="images/haryana.png" alt="{{$location->name}}" loading="lazy">
                                                                                   @elseif($location->name =='Madhya Pradesh')
										    <img src="images/Madhya pradesh.png" alt="{{$location->name}}" loading="lazy">
                                                                                    @elseif($location->name =='New Delhi')
										    <img src="images/New Delhi.png" alt="{{$location->name}}" loading="lazy">
                                                                                     @elseif($location->name =='Puducherry')
										    <img src="images/pondycherry.png" alt="{{$location->name}}" loading="lazy">
                                                                                     @elseif($location->name =='West Bengal')
										    <img src="images/West Bengal.png" alt="{{$location->name}}" loading="lazy">

                                                                                    @elseif($location->name =='Karnataka')
										    <img src="images/karnataka.png" alt="{{$location->name}}" loading="lazy">

                                                                                         @elseif($location->name =='Rajasthan')
										    <img src="images/rajsthan.png" alt="{{$location->name}}" loading="lazy">
                                                                                         @elseif($location->name =='Gujarat')
										    <img src="images/Gujarat.png" alt="{{$location->name}}" loading="lazy">
                                                                                        @elseif($location->name =='Jharkhand')
										    <img src="images/jharkhand.png" alt="{{$location->name}}" loading="lazy">
                                                                                        @elseif($location->name =='Bihar')
										    <img src="images/bihar.png" alt="{{$location->name}}" loading="lazy">
                                                                                        @elseif($location->name =='Uttar Pradesh')
										    <img src="images/uttar pradesh.png" alt="{{$location->name}}" loading="lazy">
                                                                                        @elseif($location->name =='Andhra Pradesh')
										    <img src="images/Andhra Pradesh.png" alt="{{$location->name}}" loading="lazy">
                                                                                        @elseif($location->name =='Jammu and Kashmir')
										    <img src="images/j & k.png" alt="{{$location->name}}" loading="lazy">
                                                                                        @elseif($location->name =='Telangana')
										    <img src="images/Telangana.png" alt="{{$location->name}}" loading="lazy">
                                                                                        @elseif($location->name =='Uttarakhand')
										    <img src="images/Uttrakhand.png" alt="{{$location->name}}" loading="lazy">
                                                                                        @elseif($location->name =='Maharashtra')
										    <img src="images/maharashtra.png" alt="{{$location->name}}" loading="lazy">
                                                                                        @elseif($location->name =='Tamil Nadu')
										    <img src="images/Tamil nadu.png" alt="{{$location->name}}" loading="lazy">















                                                                                          @else
                                                                                        <img src="images/city-1.png" alt="{{$location->name}}" loading="lazy" width="75px" height="75px">

                                                                                     @endif
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

  --}}
@endif


@include('home.inc.bottomtxt')

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
    padding: 34px;
}

.cards {
    text-align: center;
    margin-bottom: 39px;
  box-shadow: rgb(14 30 37 / 12%) 0px 2px 4px 0px, rgb(14 30 37 / 32%) 0px 2px 16px 0px;
    padding: 34px 3px;
    min-height: 251px;
    border-radius: 10px;
}

.cards h3 a {
    color: #000;
}
.cards h3 {
    font-family: 'poppins';
    font-size: 13px;
    font-weight: 700;
}
.cards img {
    border-radius: 50%;
    width: 140px;
    height: 140px;
    margin-bottom: 14px;
}
h2.feat {
font-family: 'Poppins';
line-height: 36px;
color: #000000;
text-align: center;
font-weight:bold;
font-size:30;
margin:20px 0px;
}
.cards:hover {
    margin-top: -12px;
    transition: 1s;
}
.listing img {
    clip-path: circle(50% at 50% 50%);
}
.listing {
    margin-bottom: 36px;
    text-align: center;
}
.listing h3 a {
    color: #000;
    font-weight: bold;
}

.listing h3 {
    font-size: 14px;
    font-family: 'poppins';
    color: #000 !important;
    margin: 10px 0px 20px 0px;
}
.justify-content-center {
    justify-content: center;
}
a.cat-links {
    background: #00b5b7;
    color: #fff;
    padding: 3px 20px;
    border-radius: 17px;
    font-family: poppins;
}
.category-title {
    text-align: center;
}
</style>
