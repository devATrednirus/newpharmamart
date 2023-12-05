

<section id="speciality" class="wide-60 courses-section division">
  <div class="container">
    <!-- SECTION TITLE -->
    <div class="row">
      <div class="col-md-12">
        <div class="sehhf">
        <div class="section-title mb-60">
            <a target="blank" href="/contact-us"><h3 class="h3-sm">Featured Companies</h3>
            <p class="p-md maxi">The listed pharma companies on Pharma Franchise Mart brings you the wide range of the best quality products for the distribution business.</p>
            </a>
        </div>
       </div>
      </div>
    </div>

    <div class="container mb-50">
      <div class="cotjyy">
        <div class="ps-home--block wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".1s" style="visibility: visible; animation-duration: 1s; animation-delay: 0.1s; animation-name: fadeInLeft;">
              <!-- <h3 class="gernal">General Range</h3> --->
              <div class="row">


          <div class="col-md-12 col-md-12">
            <div class="ps-block__product">
              <div class="row m-0">



  @foreach($User as $user)

  <?php //dd($user); ?>
                <div class="col-md-12 col-lg-3 p-0">
                  <div class="ps-product ps-product--standard lift">
                    <div class="ps-product__thumbnail">
                      <a class="ps-product__image" target="blank" href="/{{$user->username}}">
                        <figure><img src="{{ resize($user->photo, 'square') . getPictureVersion() }}" alt="{{ $user->name }}" class="img-fluid" style="max-height: 140px">
                        </figure>
                        <h6>{{ $user->name }}</h6>
                        <p class="lemp-1">{!! Str::words(strip_tags($user->about_us),20,'...') !!}</p>
                        <button type="button" class="btn btn-warning">Enquire Now</button>
                        <a target="blank" href="/{{$user->username}}"><button type="button" class="btn btn-info">More Details</a></button>
                      </a>
                      </div>
                    </div>
                </div>
              @if($loop->iteration == 4)
                @break
              @endif

@endforeach



                <div class="col-md-12 col-lg-12 p-0">
                 <div class="lemis">

            <a target="blank" href="#">
            <button type="button" class="btn btn-success">View More</a></button>
            </div>
            </div>

                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<br>

</div>
</div>
</section>
