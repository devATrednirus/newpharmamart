	<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Shared Stats {{$date_range}}</h3>


	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table no-margin">
				<thead>
				<tr>
					<th >Compnay Name</th>
					<th >Package</th>
					<th >Promise</th>
					<th>Shared</th>
					<th>Direct</th>
					<th>Total</th>

				</tr>
				</thead>
				<tbody>
				@if ($sharedCounts->count() > 0)
					@foreach($sharedCounts as $user)
						@if($user->package &&  $user->package->monthly_leads >0 || (($user->direct_count+$user->shared_count)>0) )
							<tr title="Created at {{ $user->created_at->formatLocalized('%d %B %Y') }}">
								@if($user->canBeImpersonated())
								<td >{{ $user->name }} {{!! $user->impersonateBtn() !!}}</td>
								@else
								<td>{{ $user->name }}<br></td>
								@endif
								<td >{{ $user->package->name }}</td>
								<td >
									@if($user->subscription)

										@if($user->subscription->monthly_leads > 0)


					                        {{$user->subscription->monthly_leads}}
					                        @php($total_promise += $user->subscription->monthly_leads)
					                    @else
					                        {{$user->subscription->package->monthly_leads}}
					                        @php($total_promise += $user->subscription->package->monthly_leads)
					                    @endif
					                @else
										{{ $user->package->monthly_leads }}
										@php($total_promise += $user->package->monthly_leads)
									@endif

								</td>
								<td>{{$user->shared_count}}</td>
								<td>{{$user->direct_count}}</td>

								<td>{{$user->direct_count+$user->shared_count}}</td>

								@php($total_shared += $user->shared_count)
								@php($total_direct += $user->direct_count)


							</tr>
						@endif
					@endforeach

				@else
					<tr>
						<td colspan="4">
							{{ trans('admin::messages.No ads found') }}
						</td>
					</tr>
				@endif
				</tbody>
				<thead>
				<tr>
					<th ></th>
					<th ></th>
					<th >{{$total_promise}}</th>
					<th>{{$total_shared}}</th>
					<th>{{$total_direct}}</th>
					<th>{{$total_shared+$total_direct}}</th>

				</tr>
				</thead>
			</table>
		</div>
		<!-- /.table-responsive -->
	</div>

	<!-- /.box-footer -->
</div>

@push('dashboard_styles')
	<style>
		.td-nowrap {
			width: 10px;
			white-space: nowrap;
		}
	</style>
@endpush

@push('dashboard_scripts')
@endpush
