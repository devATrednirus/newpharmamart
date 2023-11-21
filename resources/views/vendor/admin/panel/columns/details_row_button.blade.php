<!-- expand/minimize button column -->
<td class="details-control text-center cursor-pointer">

	@if($display==true)
	<i class="fa fa-plus-square-o details-row-button cursor-pointer" data-entry-id="{{ $entry->getKey() }}"></i>
	@endif
</td>