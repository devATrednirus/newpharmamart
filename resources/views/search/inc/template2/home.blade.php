<div class="com-breadcrumbs" style="margin-top: 100px!important;">
                <div class="container-fluid">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ lurl($company_url) }}">Home</a></li>

                        </ol>
                    </nav>
                </div>
            </div>

<style type="text/css">

  .para{
    font-size: 14px;
    color: #fff;
}
</style>
<?php
$url=$_SERVER['REQUEST_URI'];
$arr=explode('/',$url);
$company=$arr[1];
$userid='';
foreach($groups as $key=>$group)
{
    foreach($group['posts'] as $post)
    {
        $userid=$post->user_id;
    }
}

$dataslider=DB::table('posts')
->join('saved_posts','saved_posts.post_id','=','posts.id')
->join('pictures','pictures.post_id','=','posts.id')
->where(['posts.user_id'=>$userid])->count();

if($dataslider>2)
{
?>
            <section class="bottom-slider d-none d-sm-block">
      <div class="row mx-auto my-auto justify-content-center">

<div id="recipeCarousel" class="carousel slide w-100" data-ride="carousel">
   <ol class="carousel-indicators">
    <?php
            $dataslider2=DB::table('posts')
->join('saved_posts','saved_posts.post_id','=','posts.id')
->join('pictures','pictures.post_id','=','posts.id')
->where(['posts.user_id'=>$userid])->get();
$i=0;
            foreach($dataslider2 as $indicator)
            {

      if($i==1){
        $indicator_class="active";
      }else{
        $indicator_class="";
        }  ?>

    <li data-target="#recipeCarousel" data-slide-to="<?php echo $i; ?>" class="<?php echo $indicator_class; ?>"></li>
<?php $i++; } ?>
  </ol>
            <div class="carousel-inner" role="listbox">
      <?php
            $dataslider1=DB::table('posts')
->join('saved_posts','saved_posts.post_id','=','posts.id')
->join('pictures','pictures.post_id','=','posts.id')
->where(['posts.user_id'=>$userid])->get();
$i=0;
            foreach($dataslider1 as $sld)
            {
                $i++;
      if($i==1){
        $catactive_class="active";
      }else{
        $catactive_class="";
        }
		$datacat=DB::table('product_groups')->where(['id'=>$sld->group_id])->first();
		?>
                              <div class="carousel-item <?php echo $catactive_class; ?>" style="background: none;">
                    <div class="col-lg-3 col-sm-6 col-md-6" style="width: 20%!important; padding: 3px; max-width: 25%;">
                        <div class="movie-card zoomimage" style="padding:10px; box-shadow: 0px 5px 5px 1px #ddd;">
                                <a href="/<?php echo $company.'/'.slugify($datacat->name).'#'.slugify($sld->title) ?>" style="text-decoration:none;color:white">
                                                                    <img src="<?php echo lurl('/').'/storage/'.$sld->filename?>" style="width: 100%; height: 260px;" alt="<?=@$sld->title?>" loading="lazy" class="img-fluid">

      <strong><h5 style="font-size:16px; line-height:22px; margin-top:10px"><?=@$sld->title ?></h5></strong>
      <center>
                           <a data-toggle="modal" class="send_message" data-id="<?=$sld->post_id?>" href="#contactUser" style="background-color: red; padding: 7px 10px 7px 10px; color: #fff; border-top-right-radius: 10px; border-bottom-left-radius: 10px; font-weight: bold; font-size: 14px;"> Get Quote</a>
                        </center>

                            </a>
                        </div><br>
                    </div>
                </div>
            <?php } ?>


            </div>
            <!-- <a class="carousel-control-prev" href="#recipeCarousel" role="button" data-slide="prev" style="top:35%; background:#000;width:40px; height: 70px; opacity: 0.9;">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#recipeCarousel" role="button" data-slide="next" style="top:35%; background:#000;width:40px; height: 70px; opacity: 0.9;">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a> -->
        </div>
    </div>
     </div>

  </section>
<style type="text/css">
 .carousel { background-color: #f5f2f2; padding: 10px 15px;}

.carousel-inner .carousel-item.active,
.carousel-inner .carousel-item-next,
.carousel-inner .carousel-item-prev {
    display: flex;
}
@media (max-width: 768px) {
    .bottom-slider {
        display: none !important;
    }
    .sec_ptb_100{
        padding: 10px !important;
    }
}

.carousel-indicators li {
    width: 15px;
    height: 15px;
    border-radius: 100%;
    color: red;
    background-color: red;
    box-shadow: inset 1px 1px 1px 1px red;
}
.carousel-indicators li .active {
    background-color: red;
}
.carousel-indicators {
    bottom: -20px;
}
</style>
<?php } ?>

<section class="feature_section sec_ptb_100 clearfix mt-md-5 pt-md-5 mt-xs-0">
	<div class="container maxw_1480">
            <div class="ss_section_title text-center mb_30">
		<h3 class="title_text">Welcome To <span style="color:#bf2626">{{$sUser->name}}</span></h3>
	   </div>
	<div class="row  mt-5 mb-5 pb-3 text-center" style="background-color: #000000ed;">
		<div class="col-md-3 col-sm-3">
			<i class="fa fa-medkit  text-center circle-box"></i>
			<h5 style="color: #c31919;">Nature of Business</h5>
			<p class="para">{{@$sUser->businessType->name}}</p>
		</div>
		<div class="col-md-3 col-sm-3">
			<i class="fa fa-users text-center circle-box"></i>
			<h5 style="color: #c31919;">Number of Employees</h5>
			<p class="para">{{@$sUser->no_employees}}</p>
		</div>
		<div class="col-md-3 col-sm-3">
			<i class="fa fa-building text-center circle-box"></i>
			<h5 style="color: #c31919;">Annual Turnover</h5>
			<p class="para">{{@$sUser->annual_turnover}}</p>
		</div>
		<div class="col-md-3 col-sm-3">
			<i class="fa fa-handshake text-center circle-box"></i>
			<h5 style="color: #c31919;">Company CEO</h5>
			 <p class="para">{{$sUser->ceo_first_name}} {{$sUser->ceo_last_name}}</p>
			</div>
		</div>
		<div class="about-short-content"><?php $content=$sUser->about_us;
        //$pos=strpos($content,' ',800);
		echo substr($content,0,800);
		?>
    </div>
	<?php if($content!=''){ ?>
    	<div class="d-flex mt-5" data-toggle="modal" data-target="#quickview_modal">
             <a class="custom_btn bg_shop_red ml-auto mr-auto" href="{{ lurl($about_us) }}" tabindex="-1">Know More!</a>
         </div>
          <?php } ?>
	</div>
</section>




      <!-- feature_section - start
			================================================== -->
			<section class="feature_section sec_ptb_100 clearfix" data-bg-color="#f7f7f7">
				<div class="container maxw_1480">

					<div class="ss_section_title text-center mb_30">
						<h3 class="title_text">Our Category</h3>
					</div>
                               					<div class="ss_featured_carousel arrow_ycenter position-relative">
						<div class="row" >



													@foreach($groups as $key=>$group)
													@if($key=="others")
													<?php

													$group_url = trans($compnay_route_inner, [
														'slug' => 'other',
														'username'   =>  $sUser->username,
													]);



													?>
													@else
													<?php

													$group_url = trans($compnay_route_inner, [
														'slug' => $group['data']->slug,
														'username'   =>  $sUser->username,
													]);

													?>

													<div class="col-lg-3">

														<div class="category"> @if($group['data']->image)
         <img src="{{$group['data']->image}}">
   @else
  <img src="https://www.pharmafranchisemart.com/assets/img/dummy.jpg">

  @endif

															<a href="{{ lurl($group_url) }}">
																<h3 class="title_text" style="font-size:17px!important; line-height: 1.2">{{$group['data']->name}}</h3>
															</a>
																														<div class="viewmore" style="{{($postcounter<4)?'display:none':''}}">
																															</div>
														</div>

													</div>
													@endif
													@endforeach


						</div>
						<!--<div class="d-flex mt-5" data-toggle="modal" data-target="#quickview_modal">
						<a class="custom_btn bg_shop_red ml-auto mr-auto" href="#!" tabindex="-1">View More Products</a>
                         </div>-->

					</div>

				</div>
			</section>
			<!-- feature_section - end
			================================================== -->

@include('search.inc.template2.quick_query')
@if($divisions->count()>0)
<section class="our-division-block">
    <div class="container-fluid">
        <h2 class="div-title">Our Divisions</h2>
        <div class="logo-item-row">
            @foreach($divisions as $division)
            <div class="logo-item">

                @if($division->image)
                <img src="{{ lurl('storage/'.$division->image)}}">
                @else
                    <h2>{{$division->name}}</h2>
                @endif
                @if($division->image)
                <a class="list-download" target="_blank" href="{{ lurl('storage/'.$division->pdf)}}">Download List</a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif


   @section('after_styles')
    @endsection
    @section('after_scripts')

    <style>
    .category img {
    width: 203px;
    border-radius: 50%;
}

.category {
  min-height: 299px;
    text-align: center;
    padding: 10px 0px 0px 0px;
    border-radius: 10px;
    margin-bottom: 32px;
    box-shadow: rgb(0 0 0 / 35%) 0px 5px 15px;
}
 .category h4 a {
    color: #000;
}

.category h4 {
    font-size: 16px;
    line-height: 22px;
    color: #000;
    padding: 20px 20px;


}
  </style>


    @endsection
