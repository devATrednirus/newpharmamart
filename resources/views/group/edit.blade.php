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
				@include('post.inc.notification')
	@include('common.spacer')
	<div class="main-container">
		<div class="container">
			<div class="row">
				

				<div class="col-md-3 page-sidebar">
					@include('account.inc.sidebar')
				</div>
				
				<div class="col-md-9 page-content">
					<div class="inner-box category-content">
						<h2 class="title-2">
							<strong> <i class="icon-list"></i>Manage Product Categories</strong> -&nbsp;
							 
						</h2>
						
						<div class="row">
							<div class="col-12">
								
								<form class="form-horizontal" id="postForm" method="POST" action="{{ url()->current() }}" enctype="multipart/form-data">
									{!! csrf_field() !!}
									<input name="_method" type="hidden" value="PUT">
									<input type="hidden" name="post_id" value="{{ $group->id }}">
									<fieldset>
										 
										<!-- title -->
										<?php $titleError = (isset($errors) and $errors->has('name')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required">
											<label class="col-md-3 col-form-label" for="title">{{ t('Title') }} <sup>*</sup></label>
											<div class="col-md-8">
												<input id="title" name="name" placeholder="Name" class="form-control input-md{{ $titleError }}"
													   type="text" value="{{ old('name', $group->name) }}">
												<small id="" class="form-text text-muted">{{ t('A great title needs at least 60 characters.') }}</small>
											</div>
										</div>
										@if (app('impersonate')->isImpersonating())
										<!-- title -->
										<?php $slugError = (isset($errors) and $errors->has('slug')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required">
											<label class="col-md-3 col-form-label" for="title">Slug</label>
											<div class="col-md-8">
												<input id="title" name="slug" placeholder="Slug" class="form-control input-md{{ $titleError }}"
													   type="text" value="{{ old('slug', $group->slug) }}">
												 
											</div>
										</div>
										@endif

										<!-- description -->
										<?php $descriptionError = (isset($errors) and $errors->has('description')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required">
											<?php
												$descriptionErrorLabel = '';
												$descriptionColClass = 'col-md-8';
												if (config('settings.single.simditor_wysiwyg') or config('settings.single.ckeditor_wysiwyg')) {
													$descriptionColClass = 'col-md-12';
													$descriptionErrorLabel = $descriptionError;
												}
												$ckeditorClass = (config('settings.single.ckeditor_wysiwyg')) ? ' ckeditor' : '';
											?>
											<label class="col-md-3 col-form-label{{ $descriptionErrorLabel }}" for="description">
												{{ t('Description') }}  
											</label>
											<div class="{{ $descriptionColClass }}">
                                                <?php $ckeditorClass = (config('settings.single.ckeditor_wysiwyg')) ? ' ckeditor' : ''; ?>
												<textarea
														class="form-control{{ $ckeditorClass . $descriptionError }}"
														id="description"
														name="description"
														rows="10"
												>{{ old('description', $group->description) }}</textarea>
												<small id="" class="form-text text-muted">{{ t('Describe what makes your ad unique') }}</small>
                                            </div>
										</div>
										
										 
 
										<?php $filenameError = (isset($errors) and $errors->has('filename')) ? ' is-invalid' : ''; ?>

										<div class="form-group row">
											<label class="col-md-3 col-form-label{{ $filenameError }}" for="tags">Image</label>
											
											<div class="col-md-8">
												<input id="filename" name="filename" type="file" class="file{{ $filenameError }}">
												<small id="" class="form-text text-muted">
													{{ t('File types: :file_types', ['file_types' => showValidFileTypes('image')]) }} 
													<br>Max file size : {{ (int)config('settings.upload.max_file_size', 1000)/1000 }} MB 
												</small>
												@if($group->image)
											<label ><img src="{{ lurl('storage/' . $group->image) }}"></label>
											@endif
											</div>
										</div>
										 
										
										<!-- tags -->
										 
										<!-- Button  -->
										<div class="form-group row pt-3">
											<div class="col-md-12 text-center">
												<?php $attr = ['slug' => slugify($group->name), 'id' => $group->id]; ?>
												<a href="{{ url('/account/my-groups')}}" class="btn btn-default btn-lg"> {{ t('Back') }}</a>
												<button id="nextStepBtn" class="btn btn-primary btn-lg"> {{ t('Update') }} </button>
											</div>
										</div>

									</fieldset>
								</form>
								
							</div>
						</div>
					</div>
				</div>
		 
			</div>
		</div>
	</div>
@endsection

@section('after_styles')
	@include('layouts.inc.tools.wysiwyg.css')
	<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
@endsection

@section('after_scripts')
    @include('layouts.inc.tools.wysiwyg.js')
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
	@if (file_exists(public_path() . '/assets/plugins/bootstrap-fileinput/js/locales/'.ietfLangTag(config('app.locale')).'.js'))
		<script src="{{ url('assets/plugins/bootstrap-fileinput/js/locales/'.ietfLangTag(config('app.locale')).'.js') }}" type="text/javascript"></script>
	@endif
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.2.3/jquery.payment.min.js"></script>
	@if (file_exists(public_path() . '/assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js'))
		<script src="{{ url('assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js') }}" type="text/javascript"></script>
	@endif
	
	<script>
		/* Translation */
		var lang = {
			'select': {
				'category': "{{ t('Select a category') }}",
				'subCategory': "{{ t('Select a sub-category') }}",
				'country': "{{ t('Select a country') }}",
				'admin': "{{ t('Select a location') }}",
				'city': "{{ t('Select a city') }}"
			},
			'price': "{{ t('Price') }}",
			'salary': "{{ t('Salary') }}",
            'nextStepBtnLabel': {
                'next': "{{ t('Next') }}",
                'submit': "{{ t('Update') }}"
            }
		};
		
	 	
	 	$('#filename').fileinput(
		{
			theme: "fa",
            language: '{{ config('app.locale') }}',
			@if (config('lang.direction') == 'rtl')
				rtl: true,
			@endif
			showPreview: false,
			allowedFileExtensions:  {!! getUploadFileTypes('image', true) !!},
			showUpload: false,
			showRemove: false,
			maxFileSize: {{ (int)config('settings.upload.max_file_size', 1000) }}
		});
	</script>
	    
@endsection
