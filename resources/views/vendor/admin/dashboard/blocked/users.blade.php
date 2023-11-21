<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Blocked Locations - ><a href="{{admin_url('blocked-locations/' . $state->id)}}">{{$state->name}}</a> - {{$city->name}}</h3>
							
							 
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="table-responsive">

								@if ($blockedLocations->count() > 0)
									@foreach($blockedLocations as $items)
										<div class="col-md-6">
										<table class="table no-margin">
											<thead>
											<tr>
												<th >Name</th>
												
											</tr>
											</thead>
											<tbody>
											 
												@foreach($items as $user)
													 
														<tr>
															 
															<td >  {{ $user->name }} {!! $user->impersonateBtn() !!}</td>
															
														</tr>
													
												@endforeach

											
											</tbody>
							 
										</table>
									</div>
									@endforeach

								@else
									
									No location found
										
								@endif
							</div>
							<!-- /.table-responsive -->
						</div>
						 
						<!-- /.box-footer -->
					</div>