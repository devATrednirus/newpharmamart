<?php
$advertising = \App\Models\Advertising::where('slug', 'bottom')->first();
?>
@if (!empty($advertising))
	@include('home.inc.spacer')
	<div class="container">
		{!! $advertising->tracking_code_large !!}
	</div>
@endif