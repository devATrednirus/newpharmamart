<div class="container">
  <div class="ps-delivery" data-background="/home/img/promotion/" style="background-image:url("/home/t;img/promotion/")">
      <div class="ps-delivery__content">
          <div class="ps-delivery__text"> <i class="icon-shield-check"></i><span> <strong>Get Popular </strong>{{ Str::limit(ucfirst($cat->name),35,'...') }}.</span></div><a class="ps-delivery__more" target="blank" href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">More</a>
      </div>
  </div>
</div>
