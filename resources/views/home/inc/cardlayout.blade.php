<div class="container">
 <section class="ps-section--featured pt-5 mt-5 mb-5 pb-5 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.7s" style="visibility: visible; animation-duration: 1s; animation-delay: 0.7s; animation-name: fadeInUp;">
     <h3 class="ps-section__title">{{ $cat->name }}</h3>
     <div class="ps-section__content">


         <div class="row m-0">



           @if (isset($cat->children) and $cat->children->count() > 0)
           <?php $iterctr = 1; ?>
           <?php $mnctr = 1; ?>
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
              @if($iterctr == 1)
            <div class="col-6 col-md-6 col-lg-6dot4 p-0" style="padding-top:15px !important;">
              <div class="row">
                <div class="limpos" style="margin-right: 30px;">
                  @endif
                  <div class="col-lg-6">

                    <div class="ps-section__product  wow zoomIn" data-wow-duration="1s" data-wow-delay="1s" style="visibility: visible; animation-duration: 1s; animation-delay: 1s; animation-name: zoomIn;">
                      <div class="ps-product ps-product--standard">

                        <div class="ps-product__thumbnail"><a class="ps-product__image" target="blank" href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">
                          @if (file_exists(storage_path($scat->picture)))
                           <figure><img src="{{ \Storage::url($scat->picture) . getPictureVersion() }}" alt="{{$scat->picture}}" onerror="this.src='{{ \Storage::url('app/default/categories/fa-folder-skin-default.png') }}'; this.style.width='250px'; this.style.height='156px'; " /></figure>
                          @else
                            <figure><img src="{{ \Storage::url('app/categories/default/categories/fa-folder-skin-default.png') }}" alt="" /></figure>
                          @endif

                        </a></div>
                        <div class="ps-product__content">
                           <h5 class="ps-product__title text-center"><a target="blank" href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">{{ Str::words($scat->name,5,'...') }}</a></h5>
                        </div>
                      </div>
                    </div>
                  </div>








                  @if($iterctr == 2)

                </div>
              </div>
            </div>
@endif

            <?php if($iterctr == 1) { $iterctr++;  } else { $iterctr = 1; } ?>

              @if ($mnctr == 8)
                    @break
              @endif
              <?php $mnctr++; ?>
             @endforeach
           @endif



          </div>
          <div class="ps-shop__more"><a target="blank" href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">Show all</a></div>
     </div>
 </section>
</div>
