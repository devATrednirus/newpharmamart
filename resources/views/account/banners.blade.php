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
						<h2 class="title-2"><i class="icon-money"></i> Banners </h2>
						
						<div style="clear:both"></div>
						
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
								<tr>
								    <th>Image</th>
									<th>Location</th>
									<th>Category</th>
									<th>Added on</th>
								</tr>
								</thead>
								<tbody>
								<?php

								if (isset($banners) && $banners->count() > 0):
									foreach($banners as $key => $banner):
								
								?>
								<tr>
									<td align="center"><img src="/storage/{{$banner->filename}}" style="max-width: 600px"></td>
									<td>{{$banner->location}}</td>
									<td>
										@if($banner->category)
											{{ $banner->category->name }}
										@else
											All
										@endif
										</td>

									<td>{{ $banner->created_at->formatLocalized(config('settings.app.default_datetime_format')) }}</td>
								</tr>
								<?php endforeach; ?>
								<?php endif; ?>
								</tbody>
							</table>
						</div>
						
						<nav aria-label="">
							{{ (isset($banners)) ? $banners->links() : '' }}
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