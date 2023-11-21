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

							<h2 class="title-2"><i class="icon-docs"></i> Manage Product Categories <a href="{{ lurl('groups/create') }}" class="pull-right">Create Product Category</a></h2>
							<form name="rowForm" id="rowForm" method="POST" action="{{ lurl('account/changerows') }}">
							<input type="hidden" name="numberofrows" id="numberofrowsselected" value="" >
							<input type="hidden" name="redirect_page"  value="{{$pagePath}}" >
						</form
						<div class="table-responsive">
							<form name="listForm" method="POST">
								{!! csrf_field() !!}
								<div class="table-action">
									{{--<label for="checkAll">
										<input type="checkbox" id="checkAll">
										{{ t('Select') }}: {{ t('All') }} |

										<button type="submit" class="btn btn-sm btn-default delete-action">
											<i class="fa fa-trash"></i> {{ t('Delete') }}
										</button>
									</label>
									--}}
									<select   id="numberofrows" name="numberofrows" onchange="changeRow(this);">
													<option value="">Select rows</option>
													<option <?php echo $numberofrows==10?'selected':''; ?> value="10">10</option>
													<option <?php echo $numberofrows==25?'selected':''; ?> value="25">25</option>
													<option <?php echo $numberofrows==50?'selected':''; ?> value="50">50</option>
													<option <?php echo $numberofrows==100?'selected':''; ?> value="100">100</option>

												</select>
									<div class="table-search pull-right col-sm-7">
										<div class="form-group">
											<div class="row">
												<label class="col-sm-5 control-label text-right">{{ t('Search') }} <br>
													<a title="clear filter" class="clear-filter" href="#clear">[{{ t('clear') }}]</a>
												</label>
												<div class="col-sm-7 searchpan">
													<input type="text" class="form-control" id="filter">
												</div>
											</div>
										</div>
									</div>
								</div>

								<table id="addManageTable" class="table table-striped table-bordered add-manage-table table demo" data-filter="#filter" data-filter-text-only="true">
									<thead>
									<tr>
										{{--<th data-type="numeric" data-sort-initial="true"></th>--}}
										<th>Image</th>
										<th>Name</th>

										<th>{{ t('Option') }}</th>
									</tr>
									</thead>
									<tbody>

									<?php
									if (isset($groups) && $groups->count() > 0):
									foreach($groups as $key => $post):


									?>
									<tr>
									{{--	<td style="width:2%" class="add-img-selector">
											<div class="checkbox">
												<label><input type="checkbox" name="entries[]" value="{{ $post->id }}"></label>
											</div>
										</td>--}}
										<td style="width:14%" class="add-img-td">
											@if($post->image=='')

										<img src="https://dev.pharmafranchisemart.com/storage/app/default/picture.jpg" data-toggle="tooltip" style="width:auto; max-height:90px;" data-original-title="" title="">
										    @else
												<img src="{{$post->image}}" >
											@endif
										</td>

										<td style="width:14%" class="add-img-td">
											{{$post->name}}
										</td>

										<td style="width:10%" class="action-td">
											<div>
												@if ($post->user_id==$user->id and $post->archived==0)
													<p>
                                                        <a class="btn btn-primary btn-sm" href="{{ lurl('groups/' . $post->id . '/edit') }}">
                                                            <i class="fa fa-edit"></i> {{ t('Edit') }}
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

@section('after_scripts')
	<script src="{{ url('assets/js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
	<script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
	<script type="text/javascript">
		$(function () {
			$('#addManageTable').footable().bind('footable_filtering', function (e) {
				var selected = $('.filter-status').find(':selected').text();
				if (selected && selected.length > 0) {
					e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
					e.clear = !e.filter;
				}
			});

			$('.clear-filter').click(function (e) {
				e.preventDefault();
				$('.filter-status').val('');
				$('table.demo').trigger('footable_clear_filter');
			});

			$('#checkAll').click(function () {
				checkAll(this);
			});

			$('a.delete-action, button.delete-action, a.confirm-action').click(function(e)
			{
				e.preventDefault(); /* prevents the submit or reload */
				var confirmation = confirm("{{ t('Are you sure you want to perform this action?') }}");

				if (confirmation) {
					if( $(this).is('a') ){
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
