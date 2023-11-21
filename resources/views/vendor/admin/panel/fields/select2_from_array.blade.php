<!-- select2 from array -->
<div @include('admin::panel.inc.field_wrapper_attributes') >
	<label>{!! $field['label'] !!}</label>
<?php //dd($field['options']); ?>
	<select
			name="{{ $field['name'] }}@if (isset($field['allows_multiple']) && $field['allows_multiple']==true)[]@endif"
			style="width: 100%"
			@include('admin::panel.inc.field_attributes', ['default_class' =>  'form-control select2_from_array'])
			@if (isset($field['allows_multiple']) && $field['allows_multiple']==true)multiple @endif
	>

		@if (isset($field['allows_null']) && $field['allows_null']==true)
			<option value="">default</option>
		@endif

		@if (isset($field['options']) && !empty($field['options']))
			@foreach ($field['options'] as $key => $value)
				@if (is_array($value))
					<optgroup label="{{$key}}">
					@foreach ($value as $key2 => $value2)
						<option value="{{ $key2 }}"
						@if (isset($field['value']) && ($key2==$field['value'] || (is_array($field['value']) && in_array($key2, $field['value'])))
									|| ( ! is_null( old($field['name']) ) && old($field['name']) == $key2))
								selected
								@endif
						>{!! $value2 !!}</option>
					@endforeach
					</optgroup>

				@else
					<option value="{{ $key }}"
						@if (isset($field['value']) && ($key==$field['value'] || (is_array($field['value']) && in_array($key, $field['value'])))
							|| ( ! is_null( old($field['name']) ) && old($field['name']) == $key))
						selected
						@endif
				>{!! $value !!}</option>

				@endif


			@endforeach
		@endif
	</select>

	{{-- HINT --}}
	@if (isset($field['hint']))
		<p class="help-block">{!! $field['hint'] !!}</p>
	@endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($xPanel->checkIfFieldIsFirstOfItsType($field, $fields))

	{{-- FIELD CSS - will be loaded in the after_styles section --}}
	@push('crud_fields_styles')
	<!-- include select2 css-->
	<link href="{{ asset('vendor/adminlte/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
	@endpush

	{{-- FIELD JS - will be loaded in the after_scripts section --}}
	@push('crud_fields_scripts')
	<!-- include select2 js-->
	<script src="{{ asset('vendor/adminlte/plugins/select2/select2.min.js') }}"></script>
	<script>
		jQuery(document).ready(function($) {
			// trigger select2 for each untriggered select2 box
			$('.select2_from_array').each(function (i, obj) {
				if (!$(obj).hasClass("select2-hidden-accessible"))
				{
					$(obj).select2({
						theme: "bootstrap"
					});
				}
			});
		});
	</script>
	@endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
