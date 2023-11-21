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
				
				<div class="col-md-3 page-sidebar">
					@include('account.inc.sidebar')
				</div>
				<!--/.page-sidebar-->
				
				<div class="col-md-9 page-content">
					<div class="inner-box">
						<h2 class="title-2"><i class="icon-money"></i> {{ t('Transactions') }} </h2>
						
						<div style="clear:both"></div>
						
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
								<tr class="heading-sec" style="background: #2e3192;">
									<th><span>ID</span></th>
									<th>Type</th>
									<th>{{ t('Description') }}</th>
									
									<th>{{ t('Payment Method') }}</th>
									<th>{{ t('Value') }}</th>
									<th>{{ t('Date') }}</th>
									<th>Start Date</th>
									<th>End Date</th>
									<th>{{ t('Status') }}</th>
								</tr>
								</thead>
								<tbody>
								<?php

								if (isset($transactions) && $transactions->count() > 0):
									foreach($transactions as $key => $transaction):
										
										 
										if (empty($transaction->package)) continue;
								?>
								<tr>
									<td>{{ "RDM".sprintf("%08d",$transaction->id) }}</td>
									<td>{{ $transaction->payment_type }}</td>

									<td>
										
										<strong></strong> {{ $transaction->package->short_name }} <br>
										@if ($transaction->payment_type=="Subscriptions")
										<strong>{{ t('Duration') }}</strong> {{ $transaction->package->duration }} {{ t('days') }}
										@endif

										@if ($transaction->payment_type=="Buy-Leads")
										<strong>No of Leads</strong> {{ $transaction->no_leads }}  <br>
										<strong>Remaining Leads</strong> {{ $transaction->remaining }} 
										@endif
									</td>
									
									<td>
										@if ($transaction->active == 1 || $transaction->active == 3)
											@if (!empty($transaction->paymentMethod))
												{{ t('Paid by') }} {{ $transaction->paymentMethod->display_name }}<br>
												@if($transaction->transaction_id)
												<strong>Transaction ID</strong> {{ $transaction->transaction_id }} 
												@endif

											@else
												{{ t('Paid by') }} --
											@endif
										@else
											@if ($transaction->active == 2 && !empty($transaction->paymentMethod))
												{{ $transaction->paymentMethod->display_name }}
											@else
												{{ t('Pending payment') }}
											@endif
											
										@endif
									</td>
									<td>
										@if ($transaction->payment_type=="Subscription")
										--
										@else
										{!! ((!empty($transaction->package->currency)) ? $transaction->package->currency->symbol : '') . '' . $transaction->amount !!}</td>
										@endif
										
									<td>{{ $transaction->created_at->formatLocalized(config('settings.app.default_datetime_format')) }}</td>
									<td>@if($transaction->start_date)
											{{ $transaction->start_date->formatLocalized("%d %B %Y") }}
										@else
											NA
										@endif
									</td>
									<td>@if($transaction->end_date)
											{{ $transaction->end_date->formatLocalized("%d %B %Y") }}
										@else
											NA
										@endif</td>
									<td>
										@if ($transaction->active == 1)
											<span class="badge badge-success">Active</span>
												@if ($transaction->invoice)

													<a href="{{\Storage::url($transaction->invoice)}}" target="_blank"><span class="badge badge-info">Invoice</span></a>
												@endif


										@elseif ($transaction->active == 3)
											<span class="badge badge-danger">Expired</span>
										@else
											<span class="badge badge-info">{{ t('Pending') }}</span>
										@endif
									</td>
								</tr>
								<?php endforeach; ?>
								<?php endif; ?>
								</tbody>
							</table>
						</div>
						
						<nav aria-label="">
							{{ (isset($transactions)) ? $transactions->links() : '' }}
						</nav>
						
						<div style="clear:both"></div>
					
					</div>
				</div>
				<!--/.page-content-->
				
			</div>
			<!--/.row-->
		</div>
		<!--/.container-->
	</div>
	<!-- /.main-container -->
@endsection

@section('after_scripts')
@endsection