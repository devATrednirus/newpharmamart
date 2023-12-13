<style>
.ps-block--about.wow.zoomIn img {
    height: 64% !important;
}
</style>
<div class="container">
  <section style="margin-bottom: 90px !important;" class="ps-about--info mb-5 pb-5 pt-5 mt-5 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.7s"
    style="visibility: visible; animation-duration: 1s; animation-delay: 0.7s; animation-name: fadeInUp;">
    <h2 class="ps-about__title lefting">Find PCD Pharma Companies by Cities</h2>
    <p class="ps-about__subtitle">Get Products & Price List from India’s Leading Pharma Franchise Companies. </p>
    <div class="ps-about__extent">
      <div class="row m-0">
        	@foreach($location_posts as $key => $location)

          <?php
            //$attr = ['countryCode' => config('country.icode'), 'catSlug' => $cat->slug];
            $attr = ['countryCode' => config('country.icode'),'city'=>slugify($location->name)];
            $fullUrlLocation = lurl(trans('routes.search-city', $attr), $attr);

          ?>
        <div class="col-6 col-md-2 p-0">
          <div class="ps-block--about wow zoomIn" data-wow-duration="1s" data-wow-delay=".25s"
            style="visibility: visible; animation-duration: 1s; animation-delay: 0.25s; animation-name: zoomIn;">
              <!--- 'images/{{urlencode(Str::lower($location->name))}}.png'   --->
              {{-- @if (file_exists(public_path('images/{{urlencode(Str::lower($location->name))}}.png')))
              <img class="zoom-icon happy" src="images/{{Str::lower($location->name)}}.png" alt="{{$location->name}}"
                width="100%" height="100%">
              @else
              <img class="zoom-icon happy" src="images/{{($location->name)}}.png" alt="{{$location->name}}"
                width="100%" height="100%">
              @endif --}}

              <a class="" target="blank" href="{!! $fullUrlLocation !!}">
              <img class="zoom-icon happy" src="images/{{Str::lower($location->name)}}.png" alt="{{$location->name}}"
                 onerror="this.src='images/maharashtra.png';"  width="100%" height="100%">
              <h3 class="ps-block__title hrupa">{{$location->name}}</h3>
              </a>
          </div>
        </div>
        @endforeach



      </div>
    </div>
  </section>
</div>
