<!-- this (.mobile-filter-sidebar) part will be position fixed in mobile version -->
<?php
    $fullUrl = url(request()->getRequestUri());
    $tmpExplode = explode('?', $fullUrl);
    $fullUrlNoParams = current($tmpExplode);
?>






            <!-- City -->

            @if (isset($cat))


<!---         {{$subCat->name}}
 {{$cat->name}}   --->

<?php
  //$cattoshow = $cats->groupBy('parent_id')->orderByRaw("(id = '".$cat->id."')  ASC,name")->get(0);
  //$cattoshow = $cats->groupBy('parent_id')->get(0);
  $cattoshow =  DB::table('categories')->where('parent_id',0)->where('active',1)->orderByRaw("(id <> '".$cat->id."')  ASC,name")->get();
?>








            <div class="col-sm-12 col-md-12 col-lg-3">
              <?php $sty = '';
              if(!empty($_GET['debu'])) {
                if($_GET['debu'] == 1)  {
                  echo "search.inc.sidebar";
                  $sty = ' style="border: 1px solid;" ';
                }
              } ?>




              @foreach ($cattoshow as $iSubCatis)
               <div class="sidebar-cat">
                  <h4 class="catr">{{$iSubCatis->name}}</h4>
                  <div class="list-group">
                    <?php
                      //$cattoshow = $cats->groupBy('parent_id')->orderByRaw("(id = '".$cat->id."')  ASC,name")->get(0);
                      //$cattoshow = $cats->groupBy('parent_id')->get(0);
                      $iSubCatiss =  DB::table('categories')->where('parent_id',$iSubCatis->id)->where('active',1)->orderByRaw("(id <> '".$subCat->id."')  ASC,name")->get();
                    ?>

                    @foreach ($iSubCatiss as $val)
                      <?php $attr = [
                        'countryCode' => config('country.icode'),
                        'catSlug'     => $iSubCatis->slug,
                        'subCatSlug'  => $val->slug
                      ];
                      $searchUrl = lurl(trans('routes.search-subCat', $attr), $attr) ; ?>
                      @if($val->name == '')
                        <a href="{{$searchUrl}}" style ="background-color:#00c6ff;">
                      @else
                        <a href="{{$searchUrl}}" >
                      @endif
                          {{$val->name}}
                        </a>
                    @endforeach
                  </div>
               </div>
              @endforeach




        <div class="form-groups">
          <a class="send_message" data-toggle="modal"  data-id="0"  href="#contactUser"><h4 class="catr">Enquire Now</h4></a>
        </div>




<!-- Closed -->


				<?php $parentId = ($cat->parent_id == 0) ? $cat->tid : $cat->parent_id; ?>



				<?php $style = 'style="display: none;"'; ?>



			@endif










@include('search.inc.catvideo')


@include('search.inc.whatsapp')




</div>






<script>
$(window).scroll(function(){
  var sticky = $('.page-sidebar'),
      scroll = $(window).scrollTop();

  if (scroll >= 50) sticky.addClass('fixed');
  else sticky.removeClass('fixed');
});
</script>
