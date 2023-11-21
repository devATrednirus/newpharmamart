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
                            <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist" >
                                <a  class="nav-item nav-link " id="conversation-tab" href="{{ lurl('/account/conversations') }}" role="tab" aria-controls="conversation" aria-selected="true"><i class="icon-mail"></i> {{ t('Conversations') }}</a>

                                <a class="nav-item nav-link active" id="buylead-tabs" href="#" role="tab" aria-controls="buyleads" aria-selected="true" ><i class="icon-mail"></i> Buy Leads</a>

                                
                            </div>
                        </nav>
						
 
                    <form name="filetr-buylead" method="post">
                    	<input type="hidden" name="action" value="filter">
					<div class="inner-box2" style="clear:both; margin-top:20px">
							<div class="leads-filter-block">
								<div class="container-fluid">
									<div class="row">
									    <div class="col-md-12">
										    <div class="form-group row required">
											<label class="col-md-2 col-form-label">{{ t('Category') }} </label>
											<div class="col-md-10">
												<select name="category_id[]" multiple="multiple" id="category_id" class="form-control selecter">
													
													@foreach ($categories as $cat)
														<optgroup label="{{ $cat->name }}">
																@foreach ($cat->children as $scat)
														<option value="{{ $scat->tid }}" data-type="{{ $scat->type }}"
																@if (in_array($scat->tid, $category_id))
																	selected="selected"
																@endif
														> {{ $scat->name }} </option>

														
													@endforeach
														> 	
														</optgroup>
													@endforeach
												</select>
												
											</div>
										</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
										    {{-- <span class="filter-cate-head"><img src="/images/ic-filter.png"> <strong>Keywords</strong></span> --}}
										    

										    	<div class="form-group row required">
												<label class="col-md-4 col-form-label">Keywords </label>
												<div class="col-md-8">
													<input id="title" name="keyword" placeholder="Search Keywords" value="{{request('keyword')}}" class="form-control input-md"
														   type="text">
													
												</div>
											
											</div>
										</div>

										<div class="col-md-4">


												<div class="col-md-12">
													<button   class="btn btn-primary btn-lg"> Filter </button>
													{{-- <a href="{{lurl('/account/buy-leads')}}" class="btn btn-primary btn-lg">Reset</a> --}}
												</div>
											
										</div>
										 
									</div>
									
								</div>
							</div>
						</div>
					</form>
								<?php
							if (isset($conversations) && $conversations->count() > 0):

								$to = \Carbon\Carbon::now();
								foreach($conversations as $key => $conversation):

								$from = $conversation->created_at;
								$diff_in_days = $to->diffInDays($from);

							?>
							<div class="buy-leads-block-right">
							    <div class="container-fluid">
								    <div class="row">
								    <div class="col-md-6 col-sm-6 des-tab">
									    <h2>@if($conversation->category)
 

												{{$conversation->category->name}}
											@else
												 - 
											@endif</h2>
										<a class="ead-loc" href="#"><img src="/images/ic-loc.png"> <strong>{{$conversation->city}}</strong> </a>
										<span class="time-history"><img src="/images/ic-time.png"> {{\Carbon\Carbon::parse($conversation->sent_at)->diffForHumans()}}</span>
										<p>{{ $conversation->message }}</p>
										<table>
											<tbody>
												<tr>
													<td>Purchase Period</td>
													<td> :  <b>{{ $conversation->purchase_period ?? '--' }}</b></td>
												</tr>
												<tr>
													<td>Call Back Time</td>
													<td> :  <b>{{ $conversation->call_back_time ?? '--' }}</b></td>
												</tr>

												<tr>
													<td>Submitted at</td>
													<td> :  <b>{{ $conversation->created_at->format('d-M-Y H:i a') ?? '--' }}</b></td>
												</tr>
											</tbody>
										</table>
										
									</div>
									<div class="col-md-6 col-sm-6 des-tab buyer-des">
									    <h3>Buyer Details</h3>
										<span class="gst-verified-buyer">
											@if($conversation->verified_status)
												@if($conversation->verified_status=="By OTP")
													Verified {{ $conversation->verified_status ?? '--' }}
												@else
													{{ $conversation->verified_status ?? '--' }}
												@endif
											
											@else
											Verified By OTP
											@endif
										</span>
										<table>
											<tbody>
												<tr>
													<td>Drugs License</td>
													<td> :  <b>{{ $conversation->drugs_license ?? '--' }}</b></td>
												</tr>
												<tr>
													<td>Have GST Number</td>
													<td> :  <b>{{ $conversation->have_gst_number ?? '--' }}</b></td>
												</tr> 
												<tr>
													<td>Profession</td>
													<td> :  <b>{{ $conversation->profession ?? '--' }}</b></td>
												</tr> 

												
												@if($conversation->sender)
												<tr>
													<td>Member since</td>
													<td> :  <b>{{\Carbon\Carbon::parse($conversation->sender->created_at)->diffForHumans(null,true)}}</b></td>
												</tr> 
												@endif
											</tbody>
										</table>

										@if($buy_leads > 0 ||  ($old_buy_leads && $diff_in_days>=$old_buy_leads->package->duration))
										
									 	
									 	<a class="btn-lead-buy buyleads_modal" id="{{$conversation->id}}"  href="{{ lurl('account/buy-leads/' . $conversation->id . '/messages') }}" data-toggle="modal" data-target="#buyleads_container">
													
														<i class="icon-money"></i>  Buy Now
										</a>

										@else
											<div>You don't have credits to buy this lead</div>
											<a class="btn btn-success" href="{{ lurl('/user/buy-leads')}}"><i class="fa fa-pencil-square-o"></i> Get more Buy Leads</a>
										@endif

										
									</div>
									</div>
								</div>
							</div>
							<?php endforeach; ?>
							<?php endif; ?>
						
						<nav class="" aria-label="">
							{{ (isset($conversations)) ? $conversations->links() : '' }}

							showing {{($conversations->currentPage()>1?(($conversations->currentPage()-1)*$conversations->perPage()):'1')}} to  {{($conversations->currentPage()>1?((($conversations->currentPage()-1)*$conversations->perPage())+$conversations->count()):$conversations->count())}} from total {{ $conversations->total() }}
						</nav>
						
						<div style="clear:both"></div>
					
					</div>
				</div>
				<!--/.page-content-->

				<div id="buyleads_container" class="modal fade">
			        <div class="modal-dialog">
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


	<a class="buyleads_modal" id="show_buy_lead" href="#" data-toggle="modal" data-target="#buyleads_container"> </a>


@endsection

@section('after_scripts')
	<script src="{{ url('assets/js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
	<script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
	<script type="text/javascript">

		function 
(id){

			if(id){
		        $.get('{{ lurl('/account/buy-leads')}}/'+id, function(data) {
		                $('#buyleads_container .modal-content').html(data);
		        });

	        } 
		}
		$(function() {
        $('.buyleads_modal').click(function(e) {
	            e.preventDefault();
	            var href = $(this).attr('href');
	            
	            if(href){
		             $.get(href, function(data) {
		                    $('#buyleads_container .modal-content').html(data);
		                });

	            }
	            else{

	            	$('#buyleads_container .modal-content').html("");
	            }
	        });
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

		
		$( document ).ready(function(){


			var hash = window.location.hash;

	 
			if(hash){
				$('#show_buy_lead').attr('href','/account/buy-leads/'+hash.replace('#','')+'/messages');
				$('#show_buy_lead').click();
				/*$(hash).click();*/
			}
			$('.selecter').select2({ language: langLayout.select2, dropdownAutoWidth: 'true', width: '100%' });

		})

		//JS script
		$('.ls-modal').on('click', function(e){
  e.preventDefault();
  $('#myModal').modal('show').find('.modal-body').load($(this).attr('href'));
		});

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