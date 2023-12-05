<?php
$keywords = rawurldecode(request()->get('q'));


if(isset($subCat)){
	$display_cat_name = $subCat->name;
}
else if(isset($cat)){
	$display_cat_name = $cat->name;
}
else{
	$display_cat_name = "";
}


if(!$keywords){

	$keywords = "Looking for ".$display_cat_name;


}


if($keywords!="" && isset($city)){

		$keywords.=" in ".$city->name;
}

$enableQueryForm = true;

if($enableQueryForm==true) {
?>
<?php $sty = '';
if(!empty($_GET['debu'])) {
  if($_GET['debu'] == 1)  {
    echo "search.inc.breadcrumbs";
    $sty = ' style="border: 1px solid;" ';
  }
} ?>



<div class="requirement-form">
    <div class="container-fluid">
    <div class="row">
	    <div class="col-md-2">
		    <h3>Your Requirements?</h3>
		</div>
		<div class="col-md-10">
		    <form role="form" method="POST" action="{{ lurl('quick_query') }}" class="quick_query_form" onSubmit="return submitQuery(this)">
		    <div class="row">
		    	<?php $quickQueryError = (isset($errors) and $errors->has('quick_query')) ? ' is-invalid' : ''; ?>
				<div class="col-lg-4 col-md-4 field-col">
				    <div class="input-group">
					    <i class="icon-help"></i>
					    <input type="text" name="quick_query" class="form-control{{ $quickQueryError }}"  value="{{ old('quick_query',$keywords)}}" />
					</div>
					@if($quickQueryError)
					<div class="error-message">{{$errors->get('quick_query')[0]}}</div>
					@endif
				</div>

				<?php

					$quickQueryNameError = (isset($errors) and $errors->has('quick_query_name')) ? ' is-invalid' : '';
					$quickQueryPhoneError = (isset($errors) and $errors->has('quick_query_phone')) ? ' is-invalid' : '';


					if(auth()->check()){
						if(auth()->user()->user_type_id!="2"){

							$name = old('quick_query_name', auth()->user()->name);
						}
						else{

							$name = old('quick_query_name', auth()->user()->first_name);
						}

					}
					else{

							$name = old('quick_query_name');;


					}

				?>
				<div class="col-lg-3 col-md-3 field-col">
				    <div class="input-group">
					<i class="icon-user fa hidden-sm"></i>
					<input type="text" name="quick_query_name" class="form-control{{ $quickQueryNameError }}" value="{{$name}}" placeholder="Full Name"/>
					</div>
					@if($quickQueryNameError)
					<div class="error-message">{{$errors->get('quick_query_name')[0]}}</div>
					@endif
				</div>
				<div class="col-lg-3 col-md-3 field-col">
				    <div class="input-group">
					    <i class="icon-phone-1"></i>
					    <input type="text" name="quick_query_phone" class="form-control{{ $quickQueryPhoneError }}" value="{{ old('quick_query_phone', (auth()->check()) ? auth()->user()->phone : '') }}"  placeholder="Mobile Number"/>
					</div>
					@if($quickQueryPhoneError)
					<div class="error-message">{{$errors->get('quick_query_phone')[0]}}</div>
					@endif
				</div>
				<div class="col-lg-2 col-md-2 field-col">

					 <input type="hidden" name="l" value="{{old('l',(isset($city)?$city->id:''))}}">
					 <input type="hidden" name="c" value="{{old('c',(isset($cat)?$cat->id:''))}}">
					 <input type="hidden" name="sc" value="{{old('sc',(isset($subCat)?$subCat->id:''))}}">

					 <input type="hidden" name="messageQuickQueryForm" value="1">
				     <div class="input-group"><input type="submit" value="Get a Quote"></div>
				</div>

			</div>
			</form>
		</div>
	</div>
	</div>
</div>

<?php

}

?>


<!-- <div class="container-fluid">
   <div class="row">
      <div class="col-lg-12">
         <div class="ttil">
            <h4>Catname</h4>
            <h6>Home / path</h6>
         </div>
      </div>
   </div>
</div> -->

@if( getSegment(1)!=null )
<div class="container-fluid">
   <div class="row">
      <div class="col-lg-12">
         <div class="ttil">
            <h4>
							@if (isset($bcTab) and count($bcTab) > 0)
								@foreach($bcTab as $key => $value)
									<?php $value = collect($value); ?>
									@if($loop->last)
										{!! $value->get('name') !!}
									@endif
								@endforeach
							@endif
						</h4>

			  <h6><a href="{{ lurl('/') }}">Home</a> /
				<?php $attr = ['countryCode' => config('country.icode')]; ?>
				<a href="{{ lurl(trans('routes.v-search', $attr), $attr) }}">
					{{ config('country.name') }}
				</a>

			@if (isset($bcTab) and count($bcTab) > 0)
				@foreach($bcTab as $key => $value)
					<?php $value = collect($value); ?>
					@if ($value->has('position') and $value->get('position') > count($bcTab)+1)
							 /
							{!! $value->get('name') !!}
							&nbsp;
							@if (isset($city) or isset($admin))
								<a href="#browseAdminCities" id="dropdownMenu1" data-toggle="modal"> <i class="fa fa-caret-down" aria-hidden="true"></i></a>
							@endif

					@else
						 / <a href="{{ lurl($value->get('url')) }}">{!! $value->get('name') !!}</a>
					@endif
				@endforeach
			@endif

		</div>
 </div>
</div>
</div>
@endif
{{-- @if(isset($display_cat_name) )
<div class="top-breadcrumb title-big">

	<h1>{{$display_cat_name}} @if(isset($city) ) in {{$city->name}}  @endif</h1>
</div>


<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="loc-name">

            <h4 class="lk">{{$display_cat_name}} @if(isset($city) ) in {{$city->name}}  @endif</h4>
</div>
</div>
</div>
</div>

@endif  --}}
