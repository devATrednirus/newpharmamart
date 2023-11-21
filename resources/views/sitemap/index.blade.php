<?php

//dd($cats);
?>
@extends('layouts.master')

@section('search')
	@parent
@endsection
<style>
	.main-content {
		background-color: #fff !important;
	}

	.sitemaps h2 {
		margin-bottom: 20px !important;

	}

	.sitemaps h2 a {
		color: #fa2020;
		font-size: 22px;
		font-weight: bold;
		font-family: Roboto;
	}

	.sitemaps h3 a {
		color: #000;
		font-weight: bold;
		font-size: 18px;
		font-family: Roboto;
	}

	.sitemaps .subcat {
		background-color: #f0f0f0;
		margin-top: 30px;
		margin-bottom: 30px;
		padding: 15px;
	}

	.sitemaps .post-box {
		background-color: #f6f8fb;
		border: 2px solid #dddedf;
		height: 95px;
		 
 		margin-top: 17px;
		padding: 5px;
	}

	.sitemaps .post-text {
		float: left;
		width: 66%;
		margin-left: 5px;
		margin-top: 5px;
		color: #044ca5;
		font-weight: 500;
	}

	.sitemaps .catlisting {
		position: relative;
	}

	.sitemaps .viewmore {
		position: absolute;
		bottom: -55px;
		text-align: center;
		width: 100%;
	}

	.sitemaps .loadmore {
		padding: 10px 20px;
		background: Red;
		color: #fff;
		font-size: 14px;
		font-weight: bold;
	}

	.sitemaps .loadmore:hover,
	.loadmore:active,
	.loadmore:focus {
		padding: 20px;
		background: Red;
		color: #fff;
	}

	.post-img {
		width: 30%;
		height: 80px !important;
		float: left;
	}
 
</style>
@section('content')
	@include('common.spacer')
	<div class="main-content">
        <div class="view-all-cate-wrap">
            <div class="container">
                
                <div class="all-cate-wrap">
                    <div class="container">
                    	<div class="row">
                    	@foreach ($cats as $key => $iCat)
                			 
							
						<div class="col-md-12  cate-col sitemaps">
							<div class="cate-col-inner site">
                                	<?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $iCat->slug]; ?>
									<h2><a href="{{ lurl('/'.trans('routes.v-search-cat', $attr), $attr) }}">
										{{ $iCat->name }}
									</a></h2>



	                                        	@if (count($iCat->children))
													@foreach ($iCat->children as $iSubCat)
								<div class="col-md-12 subcat">
															<?php 

																$attr =  ['countryCode' => config('country.icode'), 'catSlug' => $iCat->slug, 'subCatSlug' => $iSubCat->slug]; ?>
									<h3>
															<a href="{{ lurl('/'.trans('routes.v-search-subCat', $attr), $attr) }}">
																{{ $iSubCat->name }} ({{$iSubCat->posts->count()}})
															</a>
									</h3>
									@if (count($iSubCat->children5))
									<div class="row catlisting" id="viewMore{{$iSubCat->id}}">
										<?php
											$counter = 0;
										?>
										@foreach ($iSubCat->children5 as $iSubCatMicro)
										<?php
										if($counter>3){
											continue;
										}
										?>
										<div class="col-md-4 col-lg-3 col-sm-6  ">
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
										<?php
										
										$counter++;
										?>
																		@endforeach
										<div class="viewmore">
											<a href="javascript:" id="loamore{{$iSubCat->id}}" class="loadmore" style="{{(count($iSubCat->children5)>4)?'':'display:none'}}" onclick="loadMorecat('{{$iSubCat->id}}');">View More</a>
											<a href="javascript:" id="loadless{{$iSubCat->id}}" class="loadmore" style="display:none" onclick="ViewLesscat('{{$iSubCat->id}}');">View Less</a>
										</div>

									</div>

															@endif
								</div>
													@endforeach
												@endif

                                </div>
                            </div>


							
                    	@endforeach
                    	</div>

                        
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection

@section('before_scripts')
@parent
<script>
	var catArr = [];

	function loadMorecat(catid) {

		if (!catArr[catid]) {
			catArr[catid] = ['loadmore', 2];
		}
		$('#loamore' + catid).hide();
		if (catArr[catid][0] == 'loadmore') {

			ajax = true;

			var listings = [];


			$.ajax({
				method: 'POST',
				url: 'sitemap/loadmore',
				data: {
					'view': 'ajax',
					'catid': catid,
					'nextrec': catArr[catid][1],
					'page': catArr[catid][1],
				}

			}).done(function(data) {
				$('#viewMore' + catid).append(data);
				$('#loamore' + catid).hide();
				$('#loadless' + catid).show();
				catArr[catid] = ['loaded', catArr[catid][1] + 1];
				ajax = false;

			});

		} else {
			$('.moreboxes').show('slow');
			$('#loamore' + catid).hide();
			$('#loadless' + catid).show();
		}
	}

	function ViewLesscat(catid) {
		$('#loamore' + catid).show('slow');
		$('#loadless' + catid).hide('slow');
		$('.moreboxes').hide('slow');
	}
</script>
@endsection