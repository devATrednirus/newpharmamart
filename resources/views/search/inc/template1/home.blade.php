<?php

    //$pageClass = (($groups)?"col-md-6":"col-md-12");

    $pageClass = "col-md-6";




?>
@if($banners->count()>0)
	<div class="row">
<div class="group-slider">

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
</div>
</div>
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

	<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 company-about-left" style="margin-top:20px;">
                <h1>Welcome to<span> {{$sUser->name}} </span></h1>
                {!! transformDescription($sUser->about_us) !!}


            </div>
            <div class="col-md-12 company-info">
                <h3>About Company</h3>
                <table class="table table-light table-bordered">
                    <tbody>
                        @if($sUser->businessType)
                        <tr>
                            <td>Nature of Business</td>
                            <td>{{$sUser->businessType->name}}</td>
                        </tr>
                        @endif
                        @if($sUser->ownershipType)
                        <tr>
                            <td>Ownership Type</td>
                            <td>{{$sUser->ownershipType->name}}</td>
                        </tr>
                        @endif
                        @if($sUser->ceo_first_name)
                        <tr>
                            <td>Company CEO</td>
                            <td>{{$sUser->ceo_first_name}} {{$sUser->ceo_last_name}}</td>
                        </tr>
                        @endif
                        @if($sUser->no_employees)
                        <tr>
                            <td>Total Number of Employees</td>
                            <td>{{$sUser->no_employees}}</td>
                        </tr>
                        @endif
                        @if($sUser->establishment_year)
                        <tr>
                            <td>Year of Establishment</td>
                            <td>{{$sUser->establishment_year}}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
               <a class="btn pull-right more-btn" href="{{ lurl($about_us) }}">read more</a>
            </div>
        </div>
    </div>
    </div>

    <!-- <div class="{{$pageClass}}">

                    <h2>{{$sUser->name}}</h2>
                    {!! transformDescription($sUser->about_us) !!}

                </div> -->
                @if($groups)
				<div class="f-p-slider">
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <h2>Featured Products</h2>
                            <div class="product-crousal">
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
                                @endif
                                @foreach($group['posts'] as $pkey=>$post)
                                <?php

                                                        $pictures = \App\Models\Picture::where('post_id', $post->id)->orderBy('position')->orderBy('id');
                                                        if ($pictures->count() > 0) {
                                                            $postImg = resize($pictures->first()->filename, 'medium');
                                                        } else {
                                                            $postImg = resize(config('larapen.core.picture.default'));
                                                        }


                                                    ?>
                                <div>
                                    @if(file_exists($postImg))
                                    <img src="{{ $postImg }}" alt="{{ $post->title }}">
                                    @else
                                      <img src="{{ str_replace('storage','storage/app',$postImg) }}" alt="{{ $post->title }}">
                                    @endif
                                    <div class="slide-text"><a title="{{ $post->title }}" href="{{ lurl($group_url) }}#{{slugify($post->title)}}">{{substr($post->title,0,17)}}
                                            @if(strlen($post->title)>17)...
                                            @endif
                                        </a></div>
                                </div>
                                @endforeach
                                @endforeach
                            </div>
                        </div>
                	</div>
                </div>
                <div class="o-p-block">
                    <div class="col-md-12 " style="margin-top:20px;">
                        <div class="row">
                            @if($groups)

                			<div class="col-md-9 pro-bottom">
                			<div class="row">
                            <h2 class="pro-title">Our Products</h2>
                            @foreach($groups as $key=>$group)
                            <div class="col-md-4 sidebar-block-right" style="margin-top:20px;">
                                <div class="inner-box">
                                    <?php

                                                    if(isset($group['data']['image']) && $group['data']['image']){
                                                        $postImg = $group['data']['image'];
                                                    }
                                                    else{
                                                        $pictures = \App\Models\Picture::where('post_id', $group['posts'][0]->id)->orderBy('position')->orderBy('id');
                                                        if ($pictures->count() > 0) {
                                                            $postImg = resize($pictures->first()->filename, 'medium');
                                                        } else {
                                                            $postImg = resize(config('larapen.core.picture.default'));
                                                        }
                                                    }


                                                    $post_url = trans('routes.company-post', [
                                                        'slug' => slugify($group['posts'][0]->title),
                                                        'id'   => $group['posts'][0]->id,
                                                        'username'   =>  $sUser->username,
                                                    ]);

                                                ?>
                                    <a href="/<?=$sUser->username.'/'.slugify($group['data']['name']);?>">
                                      @if(file_exists($postImg))
                                        <img class="img-thumbnail no-margin" src="{{ $postImg }}" alt="img">
                                      @else
                                      <img class="img-thumbnail no-margin" src="{{ str_replace('storage','storage/app',$postImg) }}" alt="img">
                                      @endif
                                    </a>
                                    @if($key=="others")
                                    <?php

                                                    $group_url = trans($compnay_route_inner, [
                                                        'slug' => 'other',
                                                        'username'   =>  $sUser->username,
                                                    ]);

                                                ?>
                                    <a class="dropdown-item service-heading" href="{{ lurl($group_url) }}"><strong>{{$group['data']['name']}} Services</strong></a>
                                    <ul>
                                        @foreach($group['posts'] as $pkey => $post)
                                        <?php
                                                        if($pkey>1){
                                                            continue;
                                                        }

                                                        $post_url = trans('routes.company-post', [
                                                            'slug' => slugify($post->title),
                                                            'id'   => $post->id,
                                                            'username'   =>  $sUser->username,
                                                        ]);

                                                    ?>
                                        <li><a class="dropdown-item" href="{{ lurl($group_url) }}#{{slugify($post->title)}}">{{$post->title}}</a></li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <?php

                                                    $group_url = trans($compnay_route_inner, [
                                                        'slug' => $group['data']->slug,
                                                        'username'   =>  $sUser->username,
                                                    ]);

                                                ?>
                                    <a class="dropdown-item service-heading" href="{{ lurl($group_url) }}"><strong>{{$group['data']->name}}</strong></a>
                                    <ul>
                                        @foreach($group['posts'] as $pkey => $post)
                                        <?php

                                                        if($pkey>1){
                                                            continue;
                                                        }
                                                        $post_url = trans('routes.company-post', [
                                                            'slug' => slugify($post->title),
                                                            'id'   => $post->id,
                                                            'username'   =>  $sUser->username,
                                                        ]);

                                                    ?>
                                        <li><a class="dropdown-item" href="{{ lurl($group_url) }}#{{slugify($post->title)}}">{{$post->title}}</a></li>
                                        @endforeach
                                    </ul>
                                    @endif
                                    @if(count($group['posts'])>1)
                                    <a class="pull-right va" href="{{ lurl($group_url) }}">view all</a>
                                    @endif
                                </div>
                            </div>


                            @endforeach
                            @endif

                        </div>
                        </div>


                            @include('search.inc.template1.quick_query')

                        </div>
                    </div>
                </div>
                @endif


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
    <style type="text/css">
    </style>
    @endsection
    @section('after_scripts')
    <script src="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
    <script type="text/javascript">




    $(document).ready(function() {

        $('.slider').slick({
            dots: true,
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
        });

    });
    </script>
    <script type="text/javascript">
    $(document).ready(function() {

        $('.product-crousal').slick({

            dots: false,
            autoplay: true,
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 1,

            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]








        });

    });
    </script>






    @endsection
