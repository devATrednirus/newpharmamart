
<div class="container">
<div class="row"> 
        <div class="col-md-12"> 
            <nav aria-label="breadcrumb" role="navigation" class="pull-left"> 
                <ol class="breadcrumb"> 
                    <li class="breadcrumb-item"><a href="{{ lurl($company_url) }}">Home</a></li> 
                    <li class="breadcrumb-item">Our Products</li> 
                </ol>
            </nav>
        </div>
    </div>

@if($product_groups)
<div class="row">
	<div class="col-md-12">
		<h2 style="color: #dc0002;">Our Products</h2>
		<div class="row">
			@foreach($product_groups as $key=>$group)
				<div class="col-md-12">
					<div>

					<?php

					
		        		$pictures = \App\Models\Picture::where('post_id', $group['posts'][0]->id)->orderBy('position')->orderBy('id');
						if ($pictures->count() > 0) {
							$postImg = resize($pictures->first()->filename, 'medium');
						} else {
							$postImg = resize(config('larapen.core.picture.default'));
						}

						 
		        	?>
		        	
		        	@if($key=="others") 

		        	
		        	<?php
          		 
	          			$group_url = trans($compnay_route_inner, [
							'slug' => 'other',
							'username'   =>  $sUser->username,
						]);
	          			 
	          		?>
		        	<h2 style="color: #00d0e3;">{{$group['data']['name']}} Services</h2>

		          	
		          	@foreach($group['posts'] as $post)
		          			 @include('search.inc.template1.posts',['post'=>$post,'group'=>$group['data']])
		          		@endforeach

		          	@else

		          	<h2>{{$group['data']->name}}</h2>
		          	
		          	<div >{!! transformDescription($group['data']->description) !!}</div>
		          	<?php
          		 
	          			$group_url = trans($compnay_route_inner, [
							'slug' => $group['data']->slug,
							'username'   =>  $sUser->username,
						]);
	          			 
	          		?>
	          		 
		          	 
		          		@foreach($group['posts'] as $post)
		          			 @include('search.inc.template1.posts',['post'=>$post,'group'=>$group['data']])
		          		@endforeach
		          
		          	@endif
	          	
	          
				</div>
			</div>
	        @endforeach 
			
	    </div>
	</div>

</div>

@endif
</div>


