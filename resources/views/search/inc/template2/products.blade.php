<style>
  /* Make the image fully responsive */
  .carousel-inner img {
    width: 100%;
    height: 60%;
                        }
  .com-breadcrumbs{margin-top:100px!important;}
  li {list-style-type: none;}
  .rightsidenav{
  	font-weight: 600; font-size: 16px; color: #000;
  	line-height: 20px;
                        }
  .rightsidenav: hover{
  	font-weight: 600; font-size: 16px; color: red;
                            }
  .rightsidesubnav{
  	font-weight: 500; font-size: 14px; color: #000; 
                        }
  .rightsidesubnav: hover{
  	font-weight: 500; font-size: 14px; color: red; 
  }
  .activeaccordian:hover{
	  background-color:red;
	  color:#fff;
	  padding:5px;
  }
  .rightsidesubnav:hover {
	  color:#fff;
  }
  .bg_shop_red:hover{background-color:#000!important;}
  .custom_btn:hover{background-color:#000!important;}
  ul li{
	  list-style-type: none;
  }
  </style>
                        



			<div class="com-breadcrumbs"> 
		        <div class="container-fluid">
		            <nav aria-label="breadcrumb" role="navigation"> 
		                <ol class="breadcrumb"> 
		                    <li class="breadcrumb-item"><a href="{{ lurl($company_url) }}">Home</a></li> 
		                    <li class="breadcrumb-item">Our Products</li> 
		                </ol>
		            </nav>
		        </div>
		    </div>





<div class="company-profile-about com-product-page">
    <div class="container-fluid">
	    
		<div class="row"> 
			<div class="col-md-12 company-infomation-full company-des-full"> 
			
			
			
			<div class="row">
		
			<div class="col-md-9 list-block">

			@if($product_groups)
			<div class="row-temp1">
		    <div class="row-temp2">
		   @foreach($product_groups as $key=>$group)
			
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
		        	<h1 style="margin-left:15px; color:#bf2626; font-size: 25px;">{{$group['data']['name']}} Services</h1>
		          	
		          	<hr>
		          	@foreach($group['posts'] as $post)
		          			 @include('search.inc.template2.posts',['post'=>$post,'group'=>$group['data']])
		          		@endforeach

		          	@else

		          	<h1 style="margin-left:15px;">{{$group['data']->name}}</h1>
		          	<hr style="margin:10px">
		          	<div style="margin-left:17px;">{!! transformDescription($group['data']->description) !!}</div>
		          	<?php
          		 
	          			$group_url = trans($compnay_route_inner, [
							'slug' => $group['data']->slug,
							'username'   =>  $sUser->username,
						]);
	          			 
	          		?>
	          		 
		          	 
		          		@foreach($group['posts'] as $post)
		          			 @include('search.inc.template2.posts',['post'=>$post,'group'=>$group['data']])
		          		@endforeach
		          
		          	@endif
	          	
	          
				</div>
				
				</div>
				@endforeach 
			</div>
			@endif
	

			</div>
			
			
			
			
			
			<div class="col-md-3">
			    <div class="white-block com-floating-cate">
				<div class="col-md-12">
				
			    <div id="accordion">
			    		 
<?php
$userid='';
foreach($groups as $key=>$group)
{
	foreach($group['posts'] as $post)
	{
		$userid=$post->user_id;
	}
}
$url=$_SERVER['REQUEST_URI'];
$arr=explode('/',$url);
$urlvalue=str_replace('-',' ',$arr[2]);
$company=$arr[1];

if($arr[2]!='')
{
	
	$data=DB::table('posts')
								 ->join('product_groups','product_groups.id','=','posts.group_id')
								 ->where(['posts.user_id'=>$userid])->where(['posts.reviewed'=>1])->where(['product_groups.slug'=>$arr[2]])->groupby('posts.group_id')->get();
								 foreach($data as $key => $row)
								 {
			    		?>
									<div class="card mb-2">
							<div class="card-header" id="heading-{{$key}}" onclick="openclose({{$key}})" style="background-color: #fff; border-bottom:solid 1px #ccc;">
							  <div class="row">
							  <div class="col-md-12">
							  <h5 class="mb-0">
                        <?php
								$data2=DB::table('posts')->where(['group_id'=>$row->group_id])->where(['user_id'=>$userid])->get();
			          		?>
								<a role="button" class="rightsidenav"  href="/<?=$company?>/<?=$row->slug?>" aria-expanded="true" aria-controls="collapse-{{$key}}">
								  {{$row->name}} 
								  <hr style="margin: 6px 0px;">
                                 <span style="font-weight: 500;
    font-size: 10px;"> <?php echo count($data2)?> products available</span>
								</a>
							  </h5>
							</div>
							  <!-- <div class="col-md-2"><i style="color: #000;" id="{{$key}}" class="<?php if(@$expanded=='true'){ echo "fa fa-minus-circle"; }else{ echo "fa fa-plus-circle";}?>"></i></div> -->
							</div>
							</div>
							<div id="collapse-{{$key}}" class="collapse show" data-parent="#accordion" aria-labelledby="heading-{{$key}}">
							  <div class="card-body">
								<ul>
								
								    @foreach($data2 as $post)
								      <li style="margin-left: -35px"><a onclick="scrolldown('<?=$post->id;?>')" class="rightsidesubnav activeaccordian" ><i class="fa fa-dot-circle-o"></i>{{$post->title}}</a></li>
									@endforeach
								</ul>
							  </div>
							</div>
						  </div>
                        <?php
								 }
								 $data=DB::table('posts')
								 ->join('product_groups','product_groups.id','=','posts.group_id')
								 ->where(['posts.user_id'=>$userid])->where(['posts.reviewed'=>1])->where('product_groups.name','!=',$urlvalue)->orderby('product_groups.name')->groupby('posts.group_id')->get();
								 foreach($data as $key => $row)
								 {
			          		?>
									<div class="card mb-2">
							<div class="card-header" id="heading-{{$key}}" onclick="openclose({{$key}})" style="background-color: #fff; border-bottom:solid 1px #ccc;">
							  <div class="row">
							  <div class="col-md-12">
							  <h5 class="mb-0">
							  <?php
								$data2=DB::table('posts')->where(['group_id'=>$row->group_id])->where(['user_id'=>$userid])->get();
								?>
								<a role="button" class="rightsidenav"  href="/<?=$company?>/{{ $row->slug }}" aria-expanded="{{@$expanded}}" aria-controls="collapse-{{$key}}">
								  {{$row->name}} 
								  <hr style="margin: 6px 0px;">
                                 <span style="font-weight: 500;
    font-size: 10px;"> <?php echo count($data2)?> products available</span>
								</a>
							  </h5>
							</div>
							  <!-- <div class="col-md-2"><i style="color: #000;" id="{{$key}}" class="<?php if(@$expanded=='true'){ echo "fa fa-minus-circle"; }else{ echo "fa fa-plus-circle";}?>"></i></div> -->
							</div>
							</div>
							<div id="collapse-{{$key}}" class="active collapse " data-parent="#accordion" aria-labelledby="heading-{{$key}}">
							  <div class="card-body">
								<ul>
								
								    @foreach($data2 as $post)
								      <li style="margin-left: -35px"><a  class="rightsidesubnav" ><i class="fa fa-dot-circle-o"></i> {{$post->title}}</a></li>
									@endforeach
								</ul>
							  </div>
							</div>
						  </div>
						  <?php
								 }

}
?>
				</div>
				</div>
				</div>

				</div>
			</div>
			</div>
			</div>
		</div>
	

		
	</div>


<script>
function openclose(a)
{
	let m=$('.fa-minus-circle').attr('class');
	
	var className = $('#'+a).attr('class');
	if(className=="fa fa-plus-circle")
	{ 

		$("#"+a).removeClass("fa fa-plus-circle");
		$("#"+a).addClass("fa fa-minus-circle");
	}
	if(className=="fa fa-minus-circle"){

		$("#"+a).removeClass("fa fa-minus-circle");
		$("#"+a).addClass("fa fa-plus-circle");
	}
}
function scrolldown(a){
 $('html, body').animate({
        scrollTop: $("#"+a).offset().top - 80
    }, 2000);
}
</script>































