<style>
.hrc
{
	margin-top: -2px;
    margin-bottom: 0px;
}

</style>
<aside>
	<div class="inner-box">
		<div class="user-panel-sidebar">
               <div class="useradmin left-logo" >
			   <?php
			   $userdata=DB::table('users')->where(['id'=>Auth::user()->id])->first();
			   ?>
			   <div class="row"> 
			   <div class="col-md-3">
                                           <a href="">
										@if ($userdata->photo=='')
											<img id="userImg" class="userImg" src="{{ url('images/user.jpg') }}" alt="user">
											
										@else
											<img id="userImg" class="userImg" src="<?=url('/')?>/storage/<?=$userdata->photo?>" alt="user">&nbsp;
										@endif
										</a>
										</div>
										<div class="col-md-9">
										<h2 style="font-weight: bold;
    color: #085aae;
    font-size: 26px;
    line-height: 43px;">{{ $userdata->name }}</h2>
										</div>
									
									</div>
                                        </div>
			<!--<div class="collapse-box" style="margin-bottom: -26px;">
				<h5 class="collapse-title no-border">
					{{ t('My Account') }}&nbsp;
					<a href="#MyClassified" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a>
				</h5>
				<div class="panel-collapse collapse show" id="MyClassified">
					<ul class="acc-list">
						<li>
							<a {!! ($pagePath=='') ? 'class="active"' : '' !!} href="{{ lurl('account') }}" style="text-align:center;">
								<i class="icon-home"></i> {{ t('Personal Home') }}
							</a>
						</li>
						
						@if(auth()->user()->user_type_id=="2")
						<li>
							<a{!! ($pagePath=='conversations') ? ' class="active"' : '' !!} href="{{ lurl('account/conversations') }}" style"text-align:center;">
							<i class="icon-mail-1"></i> {{ t('Conversations') }}&nbsp;
							<span class="badge badge-pill">
								{{ isset($countConversations) ? \App\Helpers\Number::short($countConversations) : 0 }}
							</span>&nbsp;
							<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
							</a>
						</li>
						@endif
					</ul>
				</div>
			</div>-->
			
			<!-- /.collapse-box  -->
			@if(auth()->user()->user_type_id!="2")
			<div class="collapse-box" >
				<!--<h5 class="collapse-title">
					{{ t('My Ads') }}
					<a href="#MyAds" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a>
				</h5>-->
				<div class="panel-collapse collapse show" id="MyAds">
					<ul class="acc-list">
                      <li>
							<a {!! ($pagePath=='') ? 'class="active"' : '' !!} href="{{ lurl('account') }}" >
								<i class="icon-home"></i> Company Profile
							</a>
						</li>
						<hr class="hrc">
						<li>
							<a{!! ($pagePath=='my-posts') ? ' class="active"' : '' !!} href="{{ lurl('account/my-posts') }}">
							<i class="icon-docs"></i> {{ t('My ads') }}&nbsp;
							<span class="badge badge-pill pull-right" style="background-color:#2e3192">
								{{ isset($countMyPosts) ? \App\Helpers\Number::short($countMyPosts) : 0 }}
							</span>
							</a>
						</li>
						<!--<hr class="hrc">
						<li>
							<a{!! ($pagePath=='divisions') ? ' class="active"' : '' !!} href="{{ lurl('account/divisions') }}">
							<i class="icon-docs"></i> Manage Divisions&nbsp;
							<span class="badge badge-pill">
								{{ isset($countDivisions) ? \App\Helpers\Number::short($countDivisions) : 0 }}
							</span>
							</a>
						</li>-->
						<hr class="hrc">
						<li>
							<a{!! ($pagePath=='my-groups') ? ' class="active"' : '' !!} href="{{ lurl('account/my-groups') }}">
							<i class="icon-docs"></i> Manage Product Categories&nbsp;
							<span class="badge badge-pill pull-right" style="background-color:#2e3192">
								{{ isset($countMyGroups) ? \App\Helpers\Number::short($countMyGroups) : 0 }}
							</span>
							</a>
						</li>
						<hr class="hrc">
						<li>
							<a{!! ($pagePath=='favourite') ? ' class="active"' : '' !!} href="{{ lurl('account/favourite') }}">
							<i class="icon-heart"></i>  {{ t('Favourite ads') }}&nbsp;
							
							<span class="badge badge-pill pull-right" style="background-color:#2e3192">
								{{ isset($countFavouritePosts) ? \App\Helpers\Number::short($countFavouritePosts) : 0 }}
							</span>
							</a>
						</li>
						<hr class="hrc">
						<!--<li>
							<a{!! ($pagePath=='saved-search') ? ' class="active"' : '' !!} href="{{ lurl('account/saved-search') }}">
							<i class="icon-star-circled"></i>{{ t('Saved searches') }}&nbsp;
							
							<span class="badge badge-pill">
								{{ isset($countSavedSearch) ? \App\Helpers\Number::short($countSavedSearch) : 0 }}
							</span>
							</a>
						</li>-->
						<hr class="hrc">
						<li>
							<a{!! ($pagePath=='pending-approval') ? ' class="active"' : '' !!} href="{{ lurl('account/pending-approval') }}">
							<i class="icon-hourglass"></i> {{ t('Pending approval') }}&nbsp;
							<span class="badge badge-pill pull-right" style="background-color:#2e3192">
								{{ isset($countPendingPosts) ? \App\Helpers\Number::short($countPendingPosts) : 0 }}
							</span>
							</a>
						</li>
						<hr class="hrc">
						<li>
							<a{!! ($pagePath=='archived') ? ' class="active"' : '' !!} href="{{ lurl('account/archived') }}" >
							<i class="icon-folder-close"></i> Archive Products &nbsp;
							
							<span class="badge badge-pill pull-right" style="background-color:#2e3192">
								{{ isset($countArchivedPosts) ? \App\Helpers\Number::short($countArchivedPosts) : 0 }}
							</span>
							</a> 
						</li>
						<hr class="hrc">
						<!--<li>
							<a{!! ($pagePath=='conversations') ? ' class="active"' : '' !!} href="{{ lurl('account/conversations') }}" >
							<i class="icon-mail-1"></i>{{ t('Conversations') }}&nbsp;
							<span class="badge badge-pill">
								{{ isset($countConversations) ? \App\Helpers\Number::short($countConversations) : 0 }}
							</span>&nbsp;
							<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
							</a>
						</li>-->

						 
						 <li>
							<a{!! ($pagePath=='buy-leads') ? ' class="active"' : '' !!} href="{{ lurl('account/conversations') }}" >
							<i class="icon-mail-2"></i> My Leads&nbsp;
							 
							</a>
								@if($pagePath=='buy-leads')
								<ul class="acc-list" style="margin-left:20px">
										<li><a{!! ($pagePathSub=='buy-new-leads') ? ' class="active"' : '' !!} href="{{ lurl('account/buy-leads') }}">New Buy Leads</a> </li>

										<li><a{!! ($pagePathSub=='buy-my-leads') ? ' class="active"' : '' !!} href="{{ lurl('account/purchased-leads') }}">Purchased Leads</a> </li>

								</ul>

								@endif
						</li> 
						<hr class="hrc">
						<li>
							<a{!! ($pagePath=='transactions') ? ' class="active"' : '' !!} href="{{ lurl('account/transactions') }}" >
							<i class="icon-money"></i> {{ t('Transactions') }}&nbsp;
							<span class="badge badge-pill pull-right" style="background-color:#2e3192">
								{{ isset($countTransactions) ? \App\Helpers\Number::short($countTransactions) : 0 }}
							</span>
							</a>
						</li>
						<!--<hr class="hrc">
						<li>
							<a{!! ($pagePath=='banners') ? ' class="active"' : '' !!} href="{{ lurl('account/banners') }}" >
							<i class="icon-photo"></i> Banners&nbsp;
							 
							</a>
						</li>-->
						@if (config('plugins.apilc.installed'))
							<li>
								<a{!! ($pagePath=='api-dashboard') ? ' class="active"' : '' !!} href="{{ lurl('account/api-dashboard') }}">
									<i class="icon-cog"></i> {{ trans('api::messages.Clients & Applications') }}&nbsp;
								</a>
							</li>
						@endif
					</ul>
				</div>
			</div>
			 

			@endif
			<!-- /.collapse-box  -->
			{{--
			<div class="collapse-box">
				<h5 class="collapse-title">
					{{ t('Terminate Account') }}&nbsp;
					<a href="#TerminateAccount" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a>
				</h5>
				<div class="panel-collapse collapse show" id="TerminateAccount">
					<ul class="acc-list">
						<li>
							<a {!! ($pagePath=='close') ? 'class="active"' : '' !!} href="{{ lurl('account/close') }}">
								<i class="icon-cancel-circled "></i> {{ t('Close account') }}
							</a>
						</li>
					</ul>
				</div>
			</div>
			<!-- /.collapse-box  -->
			--}}
		</div>
	</div>
	<!-- /.inner-box  -->
</aside>