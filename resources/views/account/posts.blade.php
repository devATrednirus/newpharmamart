{{--
 * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from Codecanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
--}}
@extends('layouts.master')
<?php
$appurl = url('/');
?>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
	function removefavourite() {
		if ($(".checked").prop('checked') == true) {
			$('#listForm').attr('action', '<?= $appurl ?>/account/removefavourite');
			$('#listForm').submit();
		} else {
			alert('Please select any record');
		}
	}

	function addfavourite() {
		if ($(".checked").prop('checked') == true) {
			$('#listForm').attr('action', '<?= $appurl ?>/account/addfavourite');
			$('#listForm').submit();
		} else {
			alert('Please select any record');
		}
	}

	function deleteproduct() {
		$('#listForm').attr('action', '<?= $appurl ?>/account/archived/delete');
		$('#listForm').submit();
	}

	function productexport() {
		$('#listForm').attr('action', '<?= $appurl ?>/account/productexcelexport');
		$('#listForm').submit();
	}

	function repostproduct() {
		if ($(".checked").prop('checked') == true) {
			$('#listForm').attr('action', '<?= $appurl ?>/account/repostproduct');
			$('#listForm').submit();
		} else {
			alert('Please select any record');
		}
	}
	$(document).ready(function(e) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$('#laravel-ajax-file-upload').submit(function(e) {
			e.preventDefault();
			$('#importing').text('Uploading...');
			var formData = new FormData(this);
			$.ajax({
				type: 'POST',
				url: "{{ url('account/productimport')}}",
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: (data1) => {
					this.reset();
					alert(data1);
					$('#importing').text('Import');
					console.log(data1);
				},
				error: function(data1) {
					alert('File Not Uploaded');
					$('#importing').text('Import');
					console.log(data1);
				}
			});
		});

	});
</script>
@section('content')
@include('common.spacer')
<div class="main-container">
	<div class="container">
		<div class="row">

			@if (Session::has('flash_notification'))
			<div class="col-xl-12">
				<div class="row">
					<div class="col-xl-12">
						@include('flash::message')
					</div>
				</div>
			</div>
			@endif

			<div class="col-md-3 page-sidebar">
				@include('account.inc.sidebar')
			</div>
			<!--/.page-sidebar-->

			<div class="col-md-9 page-content">

				<div class="inner-box">
					<div class="row">
						<div class="col-md-9">

						</div>
						<div class="col-md-3">
							<a class="btn btn-block btn-border btn-post btn-add-listing" href="{{ lurl('posts/create') }}">
								<i class="fa fa-plus-circle"></i> {{ t('Add Listing') }}
							</a>
						</div>

					</div>

					@if ($pagePath=='my-posts')
					<h2 class="title-2"><i class="icon-docs"></i> {{ t('My Ads') }} </h2>
					@elseif ($pagePath=='archived')
					<h2 class="title-2"><i class="icon-folder-close"></i> {{ t('Archived ads') }} </h2>
					@elseif ($pagePath=='favourite')
					<h2 class="title-2"><i class="icon-heart-1"></i> {{ t('Favourite ads') }} </h2>
					@elseif ($pagePath=='pending-approval')
					<h2 class="title-2"><i class="icon-hourglass"></i> {{ t('Pending approval') }} </h2>
					@else
					<h2 class="title-2"><i class="icon-docs"></i> {{ t('Posts') }} </h2>
					@endif

					<div class="table-responsive">

						<form name="rowForm" id="rowForm" method="POST" action="{{ lurl('account/changerows') }}">
							<input type="hidden" name="numberofrows" id="numberofrowsselected" value="" >
							<input type="hidden" name="redirect_page"  value="{{$pagePath}}" >

						</form>
						<form name="listForm" id="listForm" method="POST" action="{{ lurl('account/archiveproduct') }}">
							{!! csrf_field() !!}
							<div class="table-action ">
								<label for="checkAll" class="col-lg-8">


									<input type="checkbox" id="checkAll">
									{{ t('Select') }}: {{ t('All') }} |
									<select  style="border: 1px solid lightgray;min-height: 38px;color: #636c72;" id="numberofrows" name="numberofrows" onchange="changeRow(this);">
													<option value="">Select rows</option>
													<option <?php echo $numberofrows==10?'selected':''; ?> value="10">10</option>
													<option <?php echo $numberofrows==25?'selected':''; ?> value="25">25</option>
													<option <?php echo $numberofrows==50?'selected':''; ?> value="50">50</option>
													<option <?php echo $numberofrows==100?'selected':''; ?> value="100">100</option>

												</select>
									<!--<button type="submit" class="btn btn-danger delete-action">
											<i class="fa fa-trash"></i> {{ t('Delete') }}
										</button>-->
									@if ($pagePath=='my-posts')
									<button style="background-color:#760000;border-color:#760000" type="submit" class="btn btn-danger delete-action"><i class="icon-eye-off"></i> Offline</button>
									@endif
									@if(session::get('login_type')=='')
									@if ($pagePath=='my-posts')
									<button style="background-color:#f9003c;border-color:#f9003c" type="button" class="btn btn-black" data-toggle="modal" data-target="#myModal">Import</button>
									&nbsp;&nbsp;
									<button style="background-color:#1a4750;border-color:#1a4750" onclick="productexport()" class="btn btn-warning">Export</button>
									@endif
									@endif
									@if ($pagePath=='archived')
									<button type="button" onclick="deleteproduct()" class="btn btn-danger delete-action">
										<i class="fa fa-trash"></i> Delete
									</button>
									<button onclick="repostproduct()" type="button" class="btn btn-black"><i class="fa fa-recycle"></i> Repost</button>
									@endif
									@if($pagePath=='my-posts')
									<button style="background-color:#760000;border-color:#760000" type="button" onclick="addfavourite()" class="btn btn-danger">
										<i class="fa fa-heart"></i> Favourite
									</button>
									@endif

									@if($pagePath=='favourite')
									<button type="button" onclick="removefavourite()" class="btn btn-danger">
										<i class="fa fa-heart"></i> Remove Favourite Product
									</button>
									@endif

								</label>
								<div class="table-search pull-right col-sm-4">
									<div class="form-group">
										<div class="row pull-right">
												<div class="col-sm-12 searchpan">
												<input type="text" class="form-control" id="filter" placeholder="Search...">
											</div>

										</div>
									</div>
								</div>
							</div>

							<table id="addManageTable" class="table table-striped table-bordered add-manage-table table demo" data-filter="#filter" data-filter-text-only="true">
								<thead>
									<tr style="background:#2e3192;color:#fff;font-size:13px;">
										<th data-type="numeric" data-sort-initial="true"></th>
										<th >{{ t('Photo') }}</th>
										<th data-sort-ignore="true" >{{ t('Ads Details') }}</th>
										<th data-sort-ignore="true" >Product Group</th>
										<th data-sort-ignore="true" >Pharmafranchisemart Category</th>
										<th data-sort-ignore="true" >Pharmafranchisemart Sub Category</th>

										<th>{{ t('Option') }}</th>
									</tr>
								</thead>
								<tbody>

									<?php
									if (isset($posts) && $posts->count() > 0) :
										foreach ($posts as $key => $post) :
											// Fixed 1
											if ($pagePath == 'favourite') {
												if (isset($post->post)) {
													if (!empty($post->post)) {
														$post = $post->post;
													} else {
														continue;
													}
												} else {
													continue;
												}
											}

											// Fixed 2
											if (!$countries->has($post->country_code)) continue;

											// Get Post's URL
											$attr = ['slug' => slugify($post->title), 'id' => $post->id];
											$postUrl = lurl($post->uri, $attr);
											if (in_array($pagePath, ['pending-approval', 'archived'])) {
												$postUrl = $postUrl . '?preview=1';
											}

											// Get Post's Pictures
											if ($post->pictures->count() > 0) {
												$postImg = resize($post->pictures->get(0)->filename, 'medium');
											} else {
												$postImg = resize(config('larapen.core.picture.default'));
											}

											// Get country flag
											$countryFlagPath = 'images/flags/16/' . strtolower($post->country_code) . '.png';
									?>
											<tr onMouseOver="this.style.backgroundColor='#2e319238'" onMouseOut="this.style.backgroundColor='transparent'">
												<td style="width:2%" class="add-img-selector">
													<div class="checkbox">
														<label><input type="checkbox" name="entries[]" class="checked" value="{{ $post->id }}"></label>
													</div>
												</td>
												<td style="width:14%" class="add-img-td">
													<a href="{{ $postUrl }}"><img class="img-thumbnail img-fluid" src="{{ $postImg }}" alt="img"></a>
												</td>
												<td style="width:30%" class="ads-details-td">
													<div>
														<p>
															<strong>
																<a href="{{ $postUrl }}" title="{{ $post->title }}">{{ str_limit($post->title, 40) }}</a>
															</strong>
															@if (in_array($pagePath, ['my-posts', 'archived', 'pending-approval']))
															@if (isset($post->latestPayment) and !empty($post->latestPayment))
															@if (isset($post->latestPayment->package) and !empty($post->latestPayment->package))
															<?php
															if ($post->featured == 1) {
																$color = $post->latestPayment->package->ribbon;
																$packageInfo = '';
															} else {
																$color = '#ddd';
																$packageInfo = ' (' . t('Expired') . ')';
															}
															?>
															<i class="fa fa-check-circle tooltipHere" style="color: {{ $color }};" title="" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $post->latestPayment->package->short_name . $packageInfo }}"></i>
															@endif
															@endif
															@endif
														</p>
														<p>
															<strong><i class="icon-clock" title="{{ t('Posted On') }}"></i></strong>&nbsp;
															{{ $post->created_at->formatLocalized(config('settings.app.default_datetime_format')) }}
														</p>
														<p>
															<strong><i class="icon-eye" title="{{ t('Visitors') }}"></i></strong> {{ $post->visits ?? 0 }}
															<strong><i class="icon-location-2" title="{{ t('Located In') }}"></i></strong> {{ !empty($post->city) ? $post->city->name : '-' }}
															@if (file_exists(public_path($countryFlagPath)))
															<img src="{{ url($countryFlagPath) }}" data-toggle="tooltip" title="{{ $post->country->name }}">
															@endif
														</p>
													</div>
												</td>

												<td style="width:16%">


													@if ($post->group )

													{{$post->group->name}}

													@endif

												</td>
												<td style="width:16%">


													@if ($post->category && $post->category->parent)

													<?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $post->category->parent->slug]; ?>
													<a href="{{ lurl(trans('routes.v-search-subCat', $attr), $attr) }}">
														{{$post->category->parent->name}}
													</a>


													@endif

												</td>
												<td style="width:16%">


													@if ($post->category && $post->category->parent)
													<?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $post->category->parent->slug, 'subCatSlug' => $post->category->slug]; ?>
													<a href="{{ lurl(trans('routes.v-search-subCat', $attr), $attr) }}">
														{{$post->category->name}}
													</a>

													@endif

												</td>
												<td style="width:10%" class="action-td">
													<div>
														@if ($post->user_id==$user->id and $post->archived==0)
														<p>
															<a class="btn btn-primary btn-sm" href="{{ lurl('posts/' . $post->id . '/edit') }}">
																<i class="fa fa-edit"></i> {{ t('Edit') }}
															</a>
														</p>
														@endif
														@if (isVerifiedPost($post) and $post->archived==0)
														<p>
															<a class="btn btn-warning btn-sm confirm-action" href="{{ lurl('account/'.$pagePath.'/'.$post->id.'/offline') }}">
																<i class="icon-eye-off"></i> {{ t('Offline') }}
															</a>
														</p>
														@endif
														@if ($post->user_id==$user->id and $post->archived==1)
														<p>
															<a class="btn btn-info btn-sm confirm-action" href="{{ lurl('account/'.$pagePath.'/'.$post->id.'/repost') }}">
																<i class="fa fa-recycle"></i> {{ t('Repost') }}
															</a>
														</p>
														@endif
														<p>
															<a class="btn btn-danger btn-sm delete-action" href="{{ lurl('account/'.$pagePath.'/'.$post->id.'/delete') }}">
																<i class="fa fa-trash"></i> {{ t('Delete') }}
															</a>
														</p>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php endif; ?>
								</tbody>
							</table>
						</form>
					</div>

					<nav>
						{{ (isset($posts)) ? $posts->links() : '' }}
					</nav>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('after_styles')
<style>
	.action-td p {
		margin-bottom: 5px;
	}
</style>
@endsection
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Import File</h4>
			</div>
			<div class="modal-body">



				<form method="post" id="laravel-ajax-file-upload" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-12">

							@csrf
							<input type="file" required name="csv_file" class="form-control">
						</div>
						<div class="col-md-12">
							<br>

							<center><button class="btn btn-primary" id="importing">Import</button> <a href="{{url('account/downloadproductcsvformat')}}" class="btn btn-danger">Download Sample Format</a></center>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>

@section('after_scripts')
<script src="{{ url('assets/js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
<script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(function() {
		$('#addManageTable').footable().bind('footable_filtering', function(e) {
			var selected = $('.filter-status').find(':selected').text();
			if (selected && selected.length > 0) {
				e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
				e.clear = !e.filter;
			}
		});

		$('.clear-filter').click(function(e) {
			e.preventDefault();
			$('.filter-status').val('');
			$('table.demo').trigger('footable_clear_filter');
		});

		$('#checkAll').click(function() {
			checkAll(this);
		});

		$('a.delete-action, button.delete-action, a.confirm-action').click(function(e) {
			e.preventDefault(); /* prevents the submit or reload */
			var confirmation = confirm("{{ t('Are you sure you want to perform this action?') }}");

			if (confirmation) {
				if ($(this).is('a')) {
					var url = $(this).attr('href');
					if (url !== 'undefined') {
						redirect(url);
					}
				} else {
					$('form[name=listForm]').submit();
				}

			}

			return false;
		});
	});
</script>
<!-- include custom script for ads table [select all checkbox]  -->
<script>
	function checkAll(bx) {
		var chkinput = document.getElementsByTagName('input');
		for (var i = 0; i < chkinput.length; i++) {
			if (chkinput[i].type == 'checkbox') {
				chkinput[i].checked = bx.checked;
			}
		}
	}
	function changeRow(obj){
		$("#numberofrowsselected").val($(obj).val());
		$("#rowForm").submit();
	}
</script>

@endsection
