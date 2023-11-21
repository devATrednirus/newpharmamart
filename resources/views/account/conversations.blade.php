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

						<div class="row">
							<div class="col-md-12">
								@include('account.inc.latest-messages')
							</div>
						</div>

						<nav>
                            <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="conversation-tab" data-toggle="tab" href="#conversation" role="tab" aria-controls="conversation" aria-selected="true"><i class="icon-mail"></i> {{ t('Conversations') }}</a>

                                <a class="nav-item nav-link" id="buylead-tabs" href="{{ lurl('/account/buy-leads') }}" role="tab" aria-controls="buyleads" aria-selected="true"><i class="icon-mail"></i> Buy Leads</a>

                                
                            </div>
                        </nav>
						

					
						 <div class="tab-content card inner-box" id="nav-tabContent" style="clear:both">
	                        <div class="tab-pane fade  show active " id="conversation" role="tabpanel" aria-labelledby="conversation-tab">
			 
								<div id="reloadBtn" class="mb30" style="display: none;">
									<a href="" class="btn btn-primary" class="tooltipHere" title="" data-placement="{{ (config('lang.direction')=='rtl') ? 'left' : 'right' }}"
									   data-toggle="tooltip"
									   data-original-title="{{ t('Reload to see New Messages') }}"><i class="icon-arrows-cw"></i> {{ t('Reload') }}</a>
									<br><br>
								</div>
								
								<div style="clear:both"></div>


								<div class="table-responsive">
									

									<form name="listForm" method="POST" action="{{ lurl('account/'.$pagePath.'/delete') }}">
										{!! csrf_field() !!}
										<div class="table-action">
											{{-- <label for="checkAll">
												<input type="checkbox" id="checkAll">
												{{ t('Select') }}: {{ t('All') }} |
												<button type="submit" class="btn btn-sm btn-default delete-action">
													<i class="fa fa-trash"></i> {{ t('Delete') }}
												</button>
											</label> --}}
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
												{{-- <th style="width:2%" data-type="numeric" data-sort-initial="true"></th> --}}
												<th style="width:10%">Type </th>
												<th style="width:60%" data-sort-ignore="true">{{ t('Conversations') }}</th>
												<th style="width:20%"> </th>
												<th style="width:10%">{{ t('Option') }}</th>
											</tr>
											</thead>
											<tbody>
											<?php
											if (isset($conversations) && $conversations->count() > 0):
												foreach($conversations as $key => $conversation):


												if($conversation->is_read=="0"){
													$classname = "new-messaage";
												}	
												else{
													$classname = "";
												}
												
											?>
											<tr >
												<td class="add-img-selector">
													@if($conversation->type=="buy")
														Buy Lead
													@else
														Lead
													@endif





													{{-- <div class="checkbox">
														<label><input type="checkbox" name="entries[]" value="{{ $conversation->id }}"></label>
													</div> --}}


												</td>
												<td>
													<div style="word-break:break-all;">
														 
														@if($conversation->type=="buy")
														<strong>Purchased at:</strong> 
														@else
														<strong>{{ t('Received at') }}:</strong>
														@endif


														{{ $conversation->sent_at}}
														@if (\App\Models\Message::conversationHasNewMessages($conversation))
															<i class="icon-flag text-primary"></i>
														@endif
														<br>
														<strong>{{ t('Subject') }}:</strong>&nbsp;{{ $conversation->subject }}<br>
														<strong>{{ t('Started by') }}:</strong>&nbsp;{{ str_limit($conversation->from_name, 50) }}
														{!! (!empty($conversation->filename) and \Storage::exists($conversation->filename)) ? ' <i class="icon-attach-2"></i> ' : '' !!}&nbsp;|&nbsp;
														<a class="conversation_modal" href="{{ lurl('account/conversations/' . $conversation->id . '/messages?type='.($conversation->type=='message'?'message':'query')) }}" data-toggle="modal" data-target="#conversation_container">
															{{ t('Click here to read the messages') }}
														</a>
													</div>
												</td>
												<td>
													@if($conversation->post)

														<?php
															
															$attr = ['slug' => slugify($conversation->post->title), 'id' => $conversation->post->id];
															$postUrl = lurl($conversation->post->uri, $attr);
		 
														?>

														<a href="{{ $postUrl }}" target="_blank">Post: {{$conversation->post->title}}</a>
													@elseif($conversation->category)


														<a href="{{ lurl('category/'.$conversation->category->slug) }}" target="_blank"> Category: {{$conversation->category->name}}</a>
													@elseif($conversation->type=="direct")

														<a href="{{ lurl($user->username) }}" target="_blank">Company  Website</a>

													@endif
												</td>
												<td class="action-td">
													<div>
														<p>  
														 	 
																<a class="btn btn-primary conversation_modal <?=$classname?>" id="{{$conversation->id}}" href="{{ lurl('account/conversations/' . $conversation->id . '/messages?type='.($conversation->type=='message'?'message':'query')) }}" data-toggle="modal" data-target="#conversation_container">
																<i class="icon-eye"></i> 

																@if($conversation->is_read=="0")
																	
																	New
																@else
																	{{ t('View') }}
																@endif

																
															</a>
														</p>
														{{--<p>
															<a class="btn btn-danger btn-sm delete-action" href="{{ lurl('account/conversations/' . $conversation->id . '/delete') }}">
																<i class="fa fa-trash"></i> {{ t('Delete') }}
															</a>
														</p>
														--}}
													</div>
												</td>
											</tr>
											<?php endforeach; ?>
											<?php endif; ?>
											</tbody>
										</table>
									</form>
								</div>
								
								<nav class="" aria-label="">
									{{ (isset($conversations)) ? $conversations->links() : '' }}

									showing {{($conversations->currentPage()>1?(($conversations->currentPage()-1)*$conversations->perPage()):'1')}} to  {{($conversations->currentPage()>1?((($conversations->currentPage()-1)*$conversations->perPage())+$conversations->count()):$conversations->count())}} from total {{ $conversations->total() }}
								</nav>
								
								<div style="clear:both"></div>
							</div>

							<div class="tab-pane fade  " id="buyleads" role="tabpanel" aria-labelledby="buylead-tabs">

							</div>
					
					</div>
				</div>
				<!--/.page-content-->
				<div id="conversation_container" class="modal fade">
			        <div class="modal-dialog ">
			            <div class="modal-content">
			                <!-- Content will be loaded here from "remote.php" file -->
			            </div>
			        </div>
			    </div>


			</div>
			<!--/.row-->
		</div>
		<!--/.container-->
	</div>
	<!-- /.main-container -->

@endsection

@section('after_scripts')
	<style type="text/css">
		.new-messaage{

			background-color: #d9534f !important;
    		border-color: #d9534f !important;
		}

	</style>
	<script src="{{ url('assets/js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
	<script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
	<script type="text/javascript">
		$(function() {
        	


        	$('.conversation_modal').click(function(e) {
	            e.preventDefault();
	            var href = $(this).attr('href');
	            console.log(href);
	            if(href){
		             $.get(href, function(data) {
		                    $('#conversation_container .modal-content').html(data);
		                });

	            }
	            else{

	            	$('#conversation_container .modal-content').html("");
	            }
	        });



	        var id = location.hash;

        	if(id){
				$(id).trigger('click');        		
        	}

	    });
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
			
			$('a.delete-action, button.delete-action').click(function(e)
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
	</script>
@endsection