<?php
$urlnotfound=str_replace('%20', '-', $_SERVER['REQUEST_URI']);
$arr =explode('/',$urlnotfound);
$search_index_php=in_array("index.php", $arr);

	if($search_index_php){
	$urlcount=count($arr)-1;
	$last_value=slugify($arr[$urlcount]);

		$data=DB::table('users')->select('username','blocked','closed')->where('username','=',$last_value)->get();
		$resultCount = $data->count();

		if($resultCount>0){
		foreach ($data as $data) {
if($data->blocked==0 && $data->closed==0){
	//return \Redirect::to(url("/").'/category/'.$data->slug, 301);
	$lurl=str_replace('/index.php','',lurl('/'));
	header("HTTP/1.1 301 Moved Permanently");
	header("location: ".$lurl."/".$data->username);
	exit();
}else{
	//return \Redirect::to(url("/"), 301);
	header("HTTP/1.1 301 Moved Permanently");
	header("location: /");
	exit();
}
		}
	}else{
	 //Redirect::to(url("/").'/test', 301);
	 header("HTTP/1.1 301 Moved Permanently");
	header("location: /");
	exit();

	//header("location:test"); exit;
	}}

	$fullUrl = url(\Illuminate\Support\Facades\Request::getRequestUri());
	$plugins = array_keys((array)config('plugins'));

?>
<!DOCTYPE html>
<html lang="{{ ietfLangTag(config('app.locale')) }}"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
<head>
<style>
table > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
   background-color: #efefef;

}
td{padding:10px;}
.card-header {
    padding: 0.75rem 1.25rem;
    margin-bottom: 0;
    background-color:#fff;
    border-bottom:0px;
	border-radius: 0px ! important;
}
.white-block{
	border-bottom: solid 1px #ccc;
    padding-bottom: 27px;
    padding-top: 27px;
}

</style>
	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PTWXJP3');</script>
<!-- End Google Tag Manager -->
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@include('common.meta-robots')
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="apple-mobile-web-app-title" content="{{ config('settings.app.app_name') }}">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ \Storage::url('app/default/ico/apple-touch-icon-144-precomposed.png') . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ \Storage::url('app/default/ico/apple-touch-icon-114-precomposed.png') . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ \Storage::url('app/default/ico/apple-touch-icon-72-precomposed.png') . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" href="{{ \Storage::url('app/default/ico/apple-touch-icon-57-precomposed.png') . getPictureVersion() }}">
	<link rel="shortcut icon" href="{{ \Storage::url(config('settings.app.favicon')) . getPictureVersion() }}">
	<title>{!! MetaTag::get('title') !!}</title>
	{!! MetaTag::tag('description') !!}{!! MetaTag::tag('keywords') !!}
	<link rel="canonical" href="{{ $fullUrl }}"/>
	@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
		@if (strtolower($localeCode) != strtolower(config('app.locale')))
			<link rel="alternate" href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}" hreflang="{{ strtolower($localeCode) }}"/>
		@endif
	@endforeach
	@if (count($dnsPrefetch) > 0)
		@foreach($dnsPrefetch as $dns)
			<link rel="dns-prefetch" href="//{{ $dns }}">
		@endforeach
	@endif

	@include('layouts.inc.tools.style')


	@yield('after_styles')

	@if (isset($plugins) and !empty($plugins))
		@foreach($plugins as $plugin)
			@yield($plugin . '_styles')
		@endforeach
	@endif

	@if (config('settings.style.custom_css'))
		{!! printCss(config('settings.style.custom_css')) . "\n" !!}
	@endif

	@if (config('settings.other.js_code'))
		{!! printJs(config('settings.other.js_code')) . "\n" !!}
	@endif

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->

	@if($sUser->package=="Free")

	@endif

	<script>
		paceOptions = {
			elements: true
		};
	</script>
	<style>
	.f-icon {
    position: fixed;
    right: 16px;
    top: 357px;
    z-index: 111;
}
.fib {
    display: flex;
    align-items: center;
}
.fib a {
    display: inline-block;
    width: 52px;
    height: 52px;
    position: relative;
}
.f-c {
    background: #dc0002;
}
.f-w {
    background: #16b853;
}
.fi {
    height: 52px;
    display: inline-block;
    width: 52px;
    line-height: 52px;
    text-align: center;
    color: #fff;
    font-size: 22px;
	border-radius: 27px;
}
.blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
.activemenu{background-color: #bf2626;
    color: #fff!important;
    padding: 20px;}

	</style>
	<script src="{{ url('assets/js/pace.min.js') }}"></script>
	<script src="{{ url('assets/plugins/modernizr/modernizr-custom.js') }}"></script>
	<!-- <script id="Ym90cGVuZ3VpbkFwaQ" src="https://cdn.botpenguin.com/bot.js?apiKey=F%2Ah%7Cg%28-%3E%29VsCVCWo%7ED6X%3EI" async></script> -->
</head>
<body class="home_simple_shop {{ config('app.skin') }}" >
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PTWXJP3"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php
$segments = explode('/', $_SERVER['REQUEST_URI']);
$segments[1];
$data=DB::table('users')->where(['username'=>$segments[1]])->first();
if(@$data->phone_hidden=='1')
{
	$phone='9888885364';
}
else
{
	$phone=$data->phone;
}

?>
<div class="f-icon">
	    <div class="fib"><a class="ic" href="tel:+91<?=@$phone;?>"><i class="fi f-c fa fa-phone"></i></a></div>
	    <div class="fib"><a class="iw" href="https://wa.me/+91<?=@$phone;?>/?text=Hello, How can Pharmafranchisemart help you?" target="_blank"><i class="fi f-w fab fa-whatsapp"></i></a></div>
	</div>
<div id="compnay_wrapper" >




	@yield('content')

	@section('info')
	@show



</div>

@section('modal_location')
@show
@section('modal_abuse')
@show
@section('modal_message')
@show

@includeWhen(!auth()->check(), 'layouts.inc.modal.login')
@include('layouts.inc.modal.change-country')
@include('cookieConsent::index')

@if (config('plugins.detectadsblocker.installed'))
	@if (view()->exists('detectadsblocker::modal'))
		@include('detectadsblocker::modal')
	@endif
@endif

<script>
	{{-- Init. Root Vars --}}
	var siteUrl = '<?php echo url((!currentLocaleShouldBeHiddenInUrl() ? config('app.locale') : '' ) . '/'); ?>';
	var siteUrl = '<?php echo url('/'); ?>';
	var languageCode = '<?php echo config('app.locale'); ?>';
	var countryCode = '<?php echo config('country.code', 0); ?>';
	var timerNewMessagesChecking = <?php echo (int)config('settings.other.timer_new_messages_checking', 0); ?>;

	{{-- Init. Translation Vars --}}
	var langLayout = {
		'hideMaxListItems': {
			'moreText': "{{ t('View More') }}",
			'lessText': "{{ t('View Less') }}"
		},
		'select2': {
			errorLoading: function(){
				return "{!! t('The results could not be loaded.') !!}"
			},
			inputTooLong: function(e){
				var t = e.input.length - e.maximum, n = {!! t('Please delete #t character') !!};
				return t != 1 && (n += 's'),n
			},
			inputTooShort: function(e){
				var t = e.minimum - e.input.length, n = {!! t('Please enter #t or more characters') !!};
				return n
			},
			loadingMore: function(){
				return "{!! t('Loading more results…') !!}"
			},
			maximumSelected: function(e){
				var t = {!! t('You can only select #max item') !!};
				return e.maximum != 1 && (t += 's'),t
			},
			noResults: function(){
				return "{!! t('No results found') !!}"
			},
			searching: function(){
				return "{!! t('Searching…') !!}"
			}
		}
	};
</script>

@yield('before_scripts')

<script src="{{ url(mix('js/app.js')) }}"></script>
@if (file_exists(public_path() . '/assets/plugins/select2/js/i18n/'.config('app.locale').'.js'))
	<script src="{{ url('assets/plugins/select2/js/i18n/'.config('app.locale').'.js') }}"></script>
@endif
@if (config('plugins.detectadsblocker.installed'))
	<script src="{{ url('assets/detectadsblocker/js/script.js') . getPictureVersion() }}"></script>
@endif
<script>
	$(document).ready(function () {
		{{-- Select Boxes --}}
		$('.selecter').select2({
			language: langLayout.select2,
			dropdownAutoWidth: 'true',
			minimumResultsForSearch: Infinity,
			width: '100%'
		});

		{{-- Searchable Select Boxes --}}
		$('.sselecter').select2({
			language: langLayout.select2,
			dropdownAutoWidth: 'true',
			width: '100%'
		});

		{{-- Social Share --}}
		$('.share').ShareLink({
			title: '{{ addslashes(MetaTag::get('title')) }}',
			text: '{!! addslashes(MetaTag::get('title')) !!}',
			url: '{!! $fullUrl !!}',
			width: 640,
			height: 480
		});

		{{-- Modal Login --}}
		@if (isset($errors) and $errors->any())
			@if ($errors->any() and old('quickLoginForm')=='1')
				$('#quickLogin').modal();
			@endif
		@endif
	});
</script>
@yield('after_scripts')

@if (isset($plugins) and !empty($plugins))
	@foreach($plugins as $plugin)
		@yield($plugin . '_scripts')
	@endforeach
@endif

@if (config('settings.footer.tracking_code'))
	{!! printJs(config('settings.footer.tracking_code')) . "\n" !!}
@endif
</body>
</html>
