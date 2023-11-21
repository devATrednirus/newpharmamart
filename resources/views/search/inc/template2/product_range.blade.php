<style>
 .title_text {
    font-size: 41px!important;
}
.title_text{
	  font-size: 31px!important;
  }
   
.com-breadcrumbs{margin-top:100px!important;}
.para{font-size: 14px;
    color: #fff;}
		
  </style>
 
<div class="com-breadcrumbs"> 
		        <div class="container-fluid">
		            <nav aria-label="breadcrumb" role="navigation"> 
		                <ol class="breadcrumb"> 
		                    <li class="breadcrumb-item"><a href="{{ lurl($company_url) }}">Home</a></li> 
		                    <li class="breadcrumb-item">Product Range</li> 
		                </ol>
		            </nav>
		        </div>
		    </div>

<div class="company-profile-about com-product-page">
   <div class="container">
      <div class="row">
         <div class="col-md-12 company-infomation-full company-des-full">
            <div class="row">
               <div class="col-md-9 list-block">
                  <div class="table-responsive">
                     <table class="table table-bordered" style="border-radius:10px">
                        <thead>
                           <tr style="border-radius: 16px;background: #bf2626;color: #fff;">
                              <th>Image</th>
                              <th>Name</th>
                              <th>Short Description</th>
                               <th>Query</th>
                                                        </tr>
                        </thead>
                        <tbody>
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
                         
                           
                           <?php
                              $pictures = \App\Models\Picture::where('post_id', $post->id)->orderBy('position')->orderBy('id');
                              if ($pictures->count() > 0) {
                                  $postImg = resize($pictures->first()->filename, 'medium');
                              } else {
                                  $postImg = resize(config('larapen.core.picture.default'));
                              }
                              
                              
                              ?>
                            
                           @foreach($group['posts'] as $pkey => $post)
                          
                           <tr>
                              <td><img width="40px" class="img-responsive" src="{{ $postImg }}" alt="{{ $post->title }}"></td>
                              <td><a style="font-size: 13px;" href="{{ lurl($group_url) }}#{{slugify($post->title)}}">{{ $post->title }}</a></td>
                              <td style="font-size: 13px;"> {!! Illuminate\Support\Str::limit($post->short_description, 100, ' ...') !!} </td>
                              <td><a class="custom_btn bg_shop_red send_message" style="font-size: 10px;margin-bottom: 16px;background: #bf2626; padding: 10px 10px 10px 10px;height: 40px;margin-top: 10px;margin-left: 14px;
" data-toggle="modal" data-id="{{ $post->id }}" href="#contactUser">  <span> Get Quote </span></a></center>
				</div>
</td>
                            </tr>
                           @endforeach
                           @endforeach
                           <tr>
                        </tbody>
                     </table>
                  </div>
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