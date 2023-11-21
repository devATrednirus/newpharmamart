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
 
				<div class="col-md-12" style="padding: 20px ">
		 
					 
						<?php
						if (isset($conversation) && !empty($conversation) > 0):
						
							// Conversation URLs
							$consUrl = lurl('account/buy-leads');

							$to = \Carbon\Carbon::now();
							$from = $conversation->created_at;
							$diff_in_days = $to->diffInDays($from);
						 	
						?>
						<div class="table-responsive">
							 
								 
 

								
								<table id="addManageTable" class="table table-striped table-bordered add-manage-table table demo" data-filter="#filter" data-filter-text-only="true">
									<thead>
									<tr>
										<th data-sort-ignore="true" colspan="3">
										{{-- 	<a href="{{ $consUrl }}"><i class="icon-level-up"></i> {{ t('Back') }}</a>&nbsp;|&nbsp; --}}
											{{ t("Conversation") }} #{{ $conversation->id }} 
										</th>
									</tr>
									</thead>
									<tbody>
									<!-- Main Conversation -->
									<tr>
										<td colspan="3">
										 	
											<strong>Location:</strong> {{ $conversation->location ?? '--' }}<br>
											<strong>address:</strong> {{ $conversation->address ?? '--' }}<br>
											<strong>city:</strong> {{ $conversation->city ?? '--' }}<br>
											 
											<strong>drugs_license:</strong> {{ $conversation->drugs_license ?? '--' }}<br>
											<strong>have_gst_number:</strong> {{ $conversation->have_gst_number ?? '--' }}<br>
											<strong>purchase_period:</strong> {{ $conversation->purchase_period ?? '--' }}<br>
											<strong>call_back_time:</strong> {{ $conversation->call_back_time ?? '--' }}<br>
											<strong>profession:</strong> {{ $conversation->profession ?? '--' }}<br>
											<strong>Looking For:</strong> {{ $conversation->looking_for ?? '--' }}<br>
											<strong>Submitted at:</strong> {{ $conversation->created_at->format('d-M-Y H:i a') ?? '--' }}<br>
											@if($conversation->verified_status)
											<strong>Verified By:</strong> {{ $conversation->verified_status ?? '--' }}<br>
											@else
											<strong>Verified By:</strong> By OTP<br>
											@endif
											<hr>
											{!! nl2br($conversation->message) !!}
											


											{{--
											<hr>
											<a class="btn btn-primary" href="#" data-toggle="modal" data-target="#replyTo{{ $conversation->id }}">
												<i class="icon-reply"></i> {{ t('Reply') }}
											</a>
											--}}
										</td>
									</tr>
									
									</tbody>
								</table>
								
								 
								<div class="table-action">
										@if($buy_leads > 0 || ($old_buy_leads && $diff_in_days>=$old_buy_leads->package->duration))

										@if(($old_buy_leads && $diff_in_days>=$old_buy_leads->package->duration))													
											<div>You have <strong>{{$old_buy_leads->remaining}}</strong> credits remaining </div>
										@elseif($buy_leads > 0)
											<div>You have <strong>{{$buy_leads}}</strong> credits remaining </div>
										@endif	
									 	
										<a class="btn btn-success" href="javascript:void(0)" onClick="buyNow({{$conversation->id}})"><i class="icon-money"></i>  Buy Now</a>
										@else
											<div>You don't have credits to buy this lead</div>
											<a class="btn btn-success" href="{{ lurl('/user/buy-leads')}}"><i class="fa fa-pencil-square-o"></i> Get more Buy Leads</a>
										@endif
									
								</div>
							 
							

						</div>
						
						<nav>
							{{ (isset($messages)) ? $messages->links() : '' }}
						</nav>
						<?php else: ?>
						<div class="table-responsive" style="padding: 100px 0; text-align: center; min-height: 200px">

							<h2>Sorry!! This lead is not available for Buy	</h2>
						</div>

						<?php endif; ?>
						
					 
				</div>
 