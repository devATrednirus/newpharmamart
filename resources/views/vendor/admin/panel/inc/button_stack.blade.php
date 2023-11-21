<?php

//dd($xPanel->buttons->where('stack', $stack));
 ?>

@if ($xPanel->buttons->where('stack', $stack)->count())
	@foreach ($xPanel->buttons->where('stack', $stack) as $button)
	  @if ($button->type == 'model_function')
		@if ($stack == 'line')
		{!! $entry->{$button->content}($entry); !!}
			{{-- !! $entry->{$button->content}($entry); !! --}}
		@else
			{!! $xPanel->model->{$button->content}($xPanel); !!}
		@endif
	  @else
		@include($button->content)
	  @endif
	@endforeach
@endif
<?php
if(Request::path()=='admin/categories')
{
?>
<button class="btn btn-success" data-toggle="modal" data-target="#">Import</button>
<button type="button" onclick="categoryexport()" class="btn btn-primary" >Export</button>
<?php
}
?>
<?php


if (Request::is('admin/countries/*/cities')) {
 ?>
 <a href="/admin/cityexport/<?php echo Request::route()->parameters['countryCode']?>" class="btn btn-primary" >Export</a>
 <button class="btn btn-success" data-toggle="modal" data-target="#ImportCity">Import</button>

<?php
}
?>
<?php
if(Request::path()=='admin/users')
{
?>
<button class="btn btn-success" data-toggle="modal" data-target="#myModal">Import</button>
<button type="button" onclick="userexport('<?=@$_GET['type']?>')"  class="btn btn-primary" >Export</button>
<?php
}
?>


<?php
if(Request::path()=='admin/posts')
{
?>
<!--<button class="btn btn-success" data-toggle="modal" data-target="#">Import</button>
<a href="{{url('admin/productexcelexport')}}" class="btn btn-primary" >Export</a>-->
<?php
}
?>
