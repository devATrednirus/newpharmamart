<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Blocked Locations</h3>
		
		 
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
							<th >Count</th>
							 
						</tr>
						</thead>
						<tbody>
						 
							@foreach($items as $state)
								 
									<tr>
										 
										<td ><a href="{{admin_url('blocked-locations/' . $state->subadmin1->id)}}"> {{ $state->subadmin1->name }}</a></td>
										<td ><a href="{{admin_url('blocked-locations/' . $state->subadmin1->id)}}">{{ $state->count }}</a></td>
								
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

 