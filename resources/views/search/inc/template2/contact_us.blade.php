<style>
  /* Make the image fully responsive */
  .carousel-inner img {
    width: 100%;
    height: 60%;
  }
  .com-breadcrumbs{margin-top:100px!important;}
  </style>
@if($banners->count()>0)
<!--<div class="group-slider net-temp2-slider">
    
    <div class="products-slider">
        <div class="slider">
            @foreach($banners as $banner)

             <div>
                @if($banner->post)
                <a href="{{ lurl($banner->post->uri)}}"><img src="{{ \Storage::url($banner->filename) }}" alt="{{ $banner->name }}">
                @else
                <a href="{{ $banner->link}}"><img src="{{ \Storage::url($banner->filename) }}" alt="{{ $banner->name }}">
                @endif
                </a>
            </div>
            @endforeach

        </div>
    </div>
</div>-->
@endif

{{--
<div class="group-slider">
    
    <div class="products-slider">
        <div class="slider">

            @if($groups)
                @foreach($groups as $key=>$group)
                @if($key=="others")
                < ?php
                                     
                    $group_url = trans($compnay_route_inner, [
                        'slug' => 'other',
                        'username'   =>  $sUser->username,
                    ]);
                         
                ?>
                @else
                < ?php
                     
                    $group_url = trans($compnay_route_inner, [
                        'slug' => $group['data']->slug,
                        'username'   =>  $sUser->username,
                    ]);
                     
                ?>
                @endif
                    @foreach($group['posts'] as $pkey=>$post)
                    < ?php


                        if($pkey>0){
                            continue;
                        }

                        if(isset($group['data']['image']) && $group['data']['image']){
                            $postImg = $group['data']['image'];
                        }
                        else{
                            $pictures = \App\Models\Picture::where('post_id', $post->id)->orderBy('position')->orderBy('id');
                            if ($pictures->count() > 0) {
                                $postImg = resize($pictures->first()->filename, 'medium');
                            } else {
                                $postImg = resize(config('larapen.core.picture.default'));
                            }
                        }
                        


                    ?>
                    <div>
                        <img src="{{ $postImg }}" alt="{{ $group['data']['name'] }}">
                        <div class="slide-text"><a href="{{ lurl($group_url) }}#{{slugify($post->title)}}">{{$group['data']['name']}}</a></div>
                    </div>
                    @endforeach
                @endforeach

            @endif
        </div>
    </div>
   
</div>
--}}


			<div class="com-breadcrumbs"> 
		        <div class="container-fluid">
		            <nav aria-label="breadcrumb" role="navigation"> 
		                <ol class="breadcrumb"> 
		                    <li class="breadcrumb-item"><a href="{{ lurl($company_url) }}">Home</a></li> 
		                    <li class="breadcrumb-item">Contact Us</li> 
		                </ol>
		            </nav>
		        </div>
		    </div>




<div class="company-profile-about">
    <div class="container-fluid">
		<div class="row"> 
			<div class="col-md-12 company-infomation-full com-contact-page"> 
			<div class="row">
                <div class="col-md-6 col-sm-6 cont-form">
                @include('search.inc.template2.quick_query')
            </div> 
			<div class="col-md-6 col-sm-6 cont-add">
			<div class="white-block">
			<div class="col-md-12">
			<h2 style="color: red; margin-left: 35px">{{$sUser->name}}</h2> 
			<ul style="list-style-type: none; font-size: 18px; line-height: 35px;">
			@if($sUser->first_name)
			 <li><strong><i class="fal fa-user" style="color:red; font-weight: bold;"> </i> </strong> &nbsp; {{$sUser->gender->name}}. {{$sUser->first_name}} {{$sUser->last_name}}</li>
		 @endif 
		 @if($sUser->phone_hidden!=1)
			    <li><strong><i class="fal fa-phone" style="color:red; font-weight: bold;"> </i> </strong> &nbsp; <a href="tel:+91{{$sUser->phone}}"> +91-{{$sUser->phone}}</a></li>
			@endif
			@if($sUser->email_hidden!=1)
			    <li><strong><i class="fal fa-envelop" style="color:red; font-weight: bold;"> </i> </strong> &nbsp; <a href="mailto:{{$sUser->email}}"> {{$sUser->email}}</a></li> 
			@endif
				@if($sUser->city)
			    <li><strong><i class="fal fa-map" style="color:red; font-weight: bold;"> </i> </strong> &nbsp; {{$sUser->address1}} {{$sUser->address2}} {{$sUser->city->name}} {{($sUser->city->subAdmin1 && $sUser->city->name!=$sUser->city->subAdmin1->name)?$sUser->city->subAdmin1->name:''}}  {{$sUser->pincode}} </li>
			@endif
			</ul>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3431.2033914297667!2d76.825558!3d30.684553!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390fecab5144a939%3A0xaf75a12363c80229!2s<?php str_replace(' ','+',$sUser->address1.$sUser->address2.$sUser->city->name.$sUser->city->subAdmin1->name.$sUser->pincode); ?>!5e0!3m2!1sen!2sin!4v1662141934956!5m2!1sen!2sin" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
			</div>
			</div> 
			
			</div>
			</div>
		</div>
	
	 
 
		
	</div>
</div>




 