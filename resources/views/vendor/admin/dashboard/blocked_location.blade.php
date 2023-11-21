@extends('admin::layout')

@section('header')
	<section class="content-header">
		<h1>
			{{ trans('admin::messages.dashboard') }}
			<small>{{ trans('admin::messages.first_page_you_see', ['app_name' => config('app.name'), 'app_version' => config('app.version')]) }}</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ admin_url() }}">{{ config('app.name') }}</a></li>
			<li class="active">{{ trans('admin::messages.dashboard') }}</li>
		</ol>
	</section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

        	
        
	 
			<div class="row">

			 

				<section class="col-lg-12 connectedSortable">

						@if($type=="cities")
							@include('admin::dashboard.blocked.cities')
						@elseif($type=="users")
							@include('admin::dashboard.blocked.users')
						@endif
				</section>
 
			</div>
			
        </div>
    </div>
@endsection
 
