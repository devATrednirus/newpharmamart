
<?php
    $fullUrl = url(\Illuminate\Support\Facades\Request::getRequestUri());
	$plugins = array_keys((array)config('plugins'));
    $defer="";
    $ratingvalue="";
    $ratingcount="";
    if(Request::is('/') || Request::is('category/*') || (request()->city!='' && request()->catSlug!='')){
    $defer="defer";
    }
    $classskin='';
?>
<!DOCTYPE html>
<html lang="{{ ietfLangTag(config('app.locale')) }}"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
<head>
	@include('newlayouts.headtag')

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
    <?php
     if(!preg_match('/pharmafranchisemart.com/',$_SERVER['SERVER_NAME']) ){
        ?>
    <meta name='robots' content='noindex, nofollow' />
    <?php }else{ ?>
	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.defer=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PTWXJP3');</script>
<!-- End Google Tag Manager -->

<!-- Hotjar Tracking Code for<script>(function(h,o,t,j,a,r){ h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};h._hjSettings={hjid:3439548,hjsv:6};a=o.getElementsByTagName('head')[0];r=o.createElement('script');r.async=1; r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;a.appendChild(r);})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');</script> -->



<?php } ?>

	@include('common.meta-robots')

	<meta name="apple-mobile-web-app-title" content="{{ config('settings.app.app_name') }}">

	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ \Storage::url(config('settings.app.favicon')) . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ \Storage::url(config('settings.app.favicon')) . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ \Storage::url(config('settings.app.favicon')) . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" href="{{ \Storage::url(config('settings.app.favicon')) . getPictureVersion() }}">
          <!-- <link href="{{ asset('css/new.css') . getPictureVersion() }}" rel="stylesheet"> --->



         <link rel="preconnect" href="https://fonts.googleapis.com">
         <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
         <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200&display=swap" rel="stylesheet">




	<link rel="shortcut icon" href="{{ \Storage::url(config('settings.app.favicon')) . getPictureVersion() }}">
	<title>{!! MetaTag::get('title') !!}</title>
	{!! MetaTag::tag('description') !!}{!! MetaTag::tag('keywords') !!}
	<link rel="canonical" href="{{ $fullUrl }}"/>
	@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
		@if (strtolower($localeCode) != strtolower(config('app.locale')))
			<link rel="alternate" href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}" hreflang="{{ strtolower($localeCode) }}"/>
		@endif
	@endforeach
	{{--  @if (count($dnsPrefetch) > 0)
		@foreach($dnsPrefetch as $dns)
			<link rel="dns-prefetch" href="//{{ $dns }}">
		@endforeach
	@endif  --}}
	@if (isset($post))
		@if (isVerifiedPost($post))
			@if (config('services.facebook.client_id'))
				<meta property="fb:app_id" content="{{ config('services.facebook.client_id') }}" />
			@endif
			{{--!! $og->renderTags() !!--}}
			{!! MetaTag::twitterCard() !!}
		@endif
	@else
		@if (config('services.facebook.client_id'))
			<meta property="fb:app_id" content="{{ config('services.facebook.client_id') }}" />
		@endif
		{{--!! $og->renderTags() !!--}}
		{!! MetaTag::twitterCard() !!}
	@endif
	@include('feed::links')
	@if (config('settings.seo.google_site_verification'))
		<meta name="google-site-verification" content="{{ config('settings.seo.google_site_verification') }}" />
	@endif
	@if (config('settings.seo.msvalidate'))
		<meta name="msvalidate.01" content="{{ config('settings.seo.msvalidate') }}" />
	@endif
	@if (config('settings.seo.alexa_verify_id'))
		<meta name="alexaVerifyID" content="{{ config('settings.seo.alexa_verify_id') }}" />
	@endif

    @yield('before_styles')

	@if (config('lang.direction') == 'rtl')
		<link href="https://fonts.googleapis.com/css?family=Cairo|Changa" rel="stylesheet">
		<link href="{{ url(mix('css/app.rtl.css')) }}" rel="stylesheet">
	@else
        @if(Request::is('/'))
        <!-- <link href="{{ url('css/home.pure.css') }}" rel="stylesheet" type="text/css" preload media="screen"> -->

        @elseif(Request::is('category/*') || (request()->city!='' && request()->catSlug!=''))
        <!-- <link href="{{ url('css/category.min.css') . getPictureVersion() }}" rel="stylesheet" media="screen and (max-width: 1800px)" > -->
        @else
        <?php $classskin="skin-blue" ?>
		<link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">
        <link href="{{ url('css/custom.css') . getPictureVersion() }}" rel="stylesheet">

        @endif
	@endif
	@if (config('plugins.detectadsblocker.installed'))
		<link href="{{ url('assets/detectadsblocker/css/style.css') . getPictureVersion() }}" rel="stylesheet" preload>
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

	<script>
		paceOptions = {
			elements: true
		};
	</script>
	<script src="{{ url('assets/js/pace.min.js') }}" {{$defer}}></script>
	<script src="{{ url('assets/plugins/modernizr/modernizr-custom.js') }}" {{$defer}}></script>
	<?php if(Request::is('/')){
		$ratingvalue='5';
        //echo $ratingcount='37432';
    }elseif(Request::is('category/*') || (request()->city!='' && request()->catSlug!='')){
        $ratingvalue=5;
        $ratingcount='24312';
        if(request()->city!=''){
        $ratingvalue=5;
        $ratingcount='11580';
        }
    }elseif(Request::is('detail/*')){
    	$ratingvalue=5;
        $ratingcount='8021';

    }else{
    	$ratingvalue=4;
        $ratingcount='5609';
    } ?>
	<script type="application/ld+json"> {
            "@context": "http://schema.org",
            "@type": "Review",
            "author": {
                "@type": "Person",
                "name": "Pharmafranchisemart",
                "sameAs": "GOOGLE-PLUS-LINK"
            },
            "url": "<data:blog.canonicalUrl/>",
            "datePublished": "2022-01-06T20:00",
            "publisher": {
                "@type": "Organization",
                "name": "Pharmafranchisemart",
                "sameAs": "http://www.pharmafranchisemart.com/"
            },
            "description": "<data:blog.metaDescription/>",
            "inLanguage": "en",
            "itemReviewed": {
                "@type": "Product",
                "name": "<data:blog.pageName/>",
                "sameAs": "<data:blog.canonicalUrl/>",
                "image": "<data:blog.postImageThumbnailUrl/>",
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "{{ $ratingvalue }}",
                    "bestRating": "5",
                    "ratingCount": "{{ $ratingcount }}"
                }
            }
        }</script>
	@if(!preg_match('/account/', $fullUrl))
	<!-- <script id="Ym90cGVuZ3VpbkFwaQ" src="https://cdn.botpenguin.com/bot.js?apiKey=F%2Ah%7Cg%28-%3E%29VsCVCWo%7ED6X%3EI" async></script> -->
	@endif


<style>
#wrapper {
    padding-top: 45px!important;
}
.header {
    height: auto;
    background: #fff;
    padding: 7px 0;
    display: inline-block;
    width: 100%
}
	/** top banner css **/
	.wide-intro p {
    font-size: 22px;
    font-family: 'Roboto', sans-serif;
    font-weight: 300
}

.f-icon {display: none;}


#homepage .wide-intro .container.text-center {
    position: relative
}

#homepage .wide-intro {
    background-repeat: no-repeat;
    position: relative;
    background-position: center center
}

#homepage .wide-intro button.btn-search {
    background-color: #07a2b0!important;
    border-color: #07a2b0!important;
    border-radius: 3px 3px 3px 3px!important
}

#homepage .wide-intro .search-row {
    background-color: #fff!important;
    border-radius: 4px!important
}

#homepage .search-row .icon-append {
    color: #07b53e
}

/** end **/

/**   homepage categories css **/

.listing-grid {
    background: #f5f5f5;
    padding: 0 0
}
.cate-sub {
	min-height: 193px;
}
.cate-sub .row {
    margin-left: -5px;
    margin-right: -5px
}
.cate-col {
    margin: 13px 0
}
.categories-list ul {
    padding: 0
}
.cate-sub ul {
    margin: 0
}
.cate-box {
    padding-left: 5px;
    padding-right: 5px;
    background: #fff;
    position: relative;
    overflow: hidden;
    border-radius: 6px;
    padding-top: 15px;
    padding-bottom: 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .12), 0 1px 2px rgba(0, 0, 0, .24);
    transition: all .3s cubic-bezier(.25, .8, .25, 1);
    transform: translatey(0px)
}
.cate-box:hover {
    box-shadow: 0 14px 28px rgba(0, 0, 0, .25), 0 10px 10px rgba(0, 0, 0, .22);
    transform: translatey(-4px)
}
.cate-img {
    margin: 0 0 16px
}
.cate-img img {
    max-width: 100%;
    padding: 0
}
.cate-box h3.main-cate {
    font-size: 19px;
    font-weight: 700;
    color: #242424;
    margin-top: 0;
    margin-bottom: 12px;
    padding-bottom: 0;
    font-family: 'Roboto', sans-serif;
    text-transform: uppercase;
    min-height: 48px
}
.cate-box h3.main-cate a {
    color: #242424;
}
.cate-sub ul {
    padding: 0;
    list-style: none
}
.cate-sub ul li a {
    font-size: 16px;
    color: #595959;
    font-family: 'Roboto', sans-serif;
    text-transform: capitalize
}
.cate-sub ul li a:hover {
    text-decoration: underline;
    color: #595959
}
.cate-sub ul li {
    line-height: 24px;
	margin-bottom: 4px;
}
.cate-col:nth-child(2) a.view-all-cate {
    background: #d01717
}
.cate-col:nth-child(3) a.view-all-cate {
    background: #2b6ac6
}
.cate-col:nth-child(4) a.view-all-cate {
    background: #ee8209
}
.cate-col:nth-child(5) a.view-all-cate {
    background: #f0c300
}
.cate-col:nth-child(6) a.view-all-cate {
    background: #8933c4
}
.cate-col:nth-child(7) a.view-all-cate {
    background: #67bf11
}
.cate-col:nth-child(8) a.view-all-cate {
    background: #ec1b70
}
a.view-all-cate:hover {
    background: #fe5300!important
}
a.view-all-cate {
    background: #07b997;
    color: #fff;
    padding: 12px 11px;
    border-radius: 0 0 0 0;
    font-size: 16px;
    margin-top: 10px;
    display: inline-block;
    width: 100%;
    text-align: center;
    font-weight: 700;
    position: absolute;
    bottom: 0;
    line-height: normal
}
a.view-all-cate:hover {
    color: #fff;
    text-decoration: none
}

/** homepage categories end   **/

/** home Verified Suppliers and help form css **/

.field-box input[type=text] {
    height: 40px;
    width: 100%;
    padding-left: 45px;
    padding-right: 12px;
    border-radius: 0;
    border: none;
    overflow: hidden;
	font-size:15px;
    border: none;
    background: #d1e4e6
}

.field-box .input-group i {
    position: absolute;
    bottom: 0;
    top: 0;
    margin: auto;
    left: 0;
    height: 40px;
    line-height: 40px;
    width: 37px;
    text-align: center;
    background: #dc0002;
    color: #fff;
    border-radius: 0;
    display: inline-block!important;
    z-index: 1
}

.field-box input[type=submit] {
    height: 40px;
    width: 100%;
    border: none;
    background: #dc0002;
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    border-radius: 3px;
    max-width: 129px;
    margin-bottom: 0
}

.field-box .input-group {
    position: relative;
    margin-bottom: 10px
}

.help-block {
    padding-top: 18px;
    padding-bottom: 0;
    margin-bottom: 0
}

.listing-counter h2 {
    font-size: 32px;
    color: #222;
    font-family: 'Roboto', sans-serif;
    font-weight: 300;
    text-transform: capitalize
}

.help-form h3 {
    font-size: 32px;
    color: #222;
    font-family: 'Roboto', sans-serif;
    font-weight: 300;
    text-transform: capitalize
}

.listing-counter .page-info.page-info-lite.rounded {
    background: #09cee1;
    padding-top: 24px;
    padding-bottom: 24px
}

.listing-counter .page-info-lite .iconbox-wrap-icon .icon {
    color: #fff
}

.listing-counter .page-info-lite h5 {
    color: #d22426
}

.listing-counter .iconbox-wrap-text {
    font-size: 14px;
    color: #333
}

.listing-counter .iconbox-wrap-icon {
    border-right: 1px solid rgba(255, 255, 255, .6)
}

.help-form-inner .row {
    margin-top: 0;
    margin-bottom: 0
}

.listing-counter .row {
    margin-top: 0;
    margin-bottom: 0
}

/** end **/


/** top sticky requirement form css **/

.requirement-form {
    background: #3b2f82
}
.requirement-form {
    position: sticky;
    top: 75px;
    z-index: 2;
}
.requirement-form h3 {
    color: #fff;
    margin-top: 16px;
    padding: 0;
    font-weight: 300;
    font-size: 17px
}

.field-col input[type=text] {
    height: 37px;
    width: 100%;
    padding-left: 45px;
    padding-right: 12px;
    border-radius: 3px!important;
    border: none;
    overflow: hidden
}

.field-col input[type=submit] {
    height: 37px;
    width: 100%;
    border: none;
    background: #03b5c6;
    color: #fff;
    font-size: 17px;
    font-weight: 600;
    cursor: pointer;
    border-radius: 3px
}

.field-col .input-group {
    position: relative
}

.field-col .input-group i {
    position: absolute;
    bottom: 0;
    top: 0;
    margin: auto;
    left: 0;
    height: 37px;
    line-height: 37px;
    width: 37px;
    text-align: center;
    background: #dc0002;
    color: #fff;
    border-radius: 3px 0 0 3px;
    display: inline-block!important;
    z-index: 11
}
.quick_query_form .field-col input[type="text"] {
	font-size: 15px;
}
.field-col {
    padding-left: 0
}
/** end **/

@media(max-width:767px){
 .f-icon {display: block !important;}
}

@media(min-width:320px) and (max-width:767px) {
	 #homepage .wide-intro .search-row form .search-col.locationicon {
        display: none
    }
      #homepage .wide-intro h1.intro-title {
        display: none
    }
    #homepage .wide-intro p.sub {
        display: none
    }
    #homepage .wide-intro {
        height: 138px;
        max-height: 138px;
        background-image: inherit;
		display: none;
    }
	#homepage .requirement-form {
	display: none;
}

	.help-form-inner .row .field-box {
        padding-left: 15px!important;
        padding-right: 15px!important
    }
    .listing-counter h2 {
        font-size: 18px;
        font-weight: 300;
        line-height: normal
    }
	.requirement-form {
        position: inherit;
        top: inherit;
        z-index: inherit
    }
    .field-col {
        padding-left: 15px!important;
        margin-top: 10px
    }
	.requirement-form h3 {
        margin-top: 16px;
        text-align: center
    }
	.field-col .input-group i {
        z-index: 1
    }

}

	</style>

	<style>
	.f-icon {
    position: fixed;
    right: 0;
    top: 320px;
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
.main-footer .footer-nav li a {
   color: #fff;
    font-size: 14px;
    width: 25px;
    height: 25px;
    padding-top: 8px;
}
.footer-copy {
    background: #cecece;
    margin-bottom: 82px;
}
.blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
	</style>
</head>

<?php $sty = '';
if(!empty($_GET['debu'])) {
  if($_GET['debu'] == 1)  {
    echo "Me master.blade.php";
    $sty = ' style="border: 1px solid;" ';
  }
} ?>

<body class="{{ $classskin }}">

<?php if(preg_match('/pharmafranchisemart.com/',$_SERVER['SERVER_NAME'])){ ?>
	<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PTWXJP3"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php }
$segments = explode('/', $_SERVER['REQUEST_URI']);
if($segments[1]!='account' AND $segments[1]!='posts')
{
?>
<div class="f-icon" >
	    <div class="fib"><a class="ic" href="tel:+919888885364"><i class="fi f-c fa fa-phone"></i></a></div>
	    <div class="fib"><a class="iw" href="https://wa.me/+919888885364/?text=Hello, How can Pharmafranchisemart help you?" target="_blank"><i class="fi f-w fab fa-whatsapp"></i></a></div>
	</div>
<?php
}
?>

<div {{$sty}}>

	@section('header')
		@include('layouts.inc.header')
	@show



	@section('wizard')
	@show

	{{-- @if (isset($siteCountryInfo))
		<div class="h-spacer"></div>
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="alert alert-warning">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						{!! $siteCountryInfo !!}
					</div>
				</div>
			</div>
		</div>
	@endif --}}

	@yield('content')

	@section('info')
	@show

	@section('footer')
@include('newlayouts.footer')
	@show

</div>

@section('modal_location')
@show
@section('modal_abuse')
@show
@section('modal_message')
@show

@includeWhen(!auth()->check(), 'layouts.inc.modal.login')
@includeWhen(!auth()->check(),'search.inc.user_login')
@includeWhen(!auth()->check(),'search.inc.user_login_otp')

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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" preload />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
@if(Request::is('/') )

@elseif(Request::is('category/*') || (request()->city!='' && request()->catSlug!=''))

    <script>

!function(a){"use strict";"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports&&"function"==typeof require?require("jquery"):jQuery)}(function(a){"use strict";function b(c,d){var e=function(){},f=this,g={ajaxSettings:{},autoSelectFirst:!1,appendTo:document.body,serviceUrl:null,lookup:null,onSelect:null,width:"auto",minChars:1,maxHeight:300,deferRequestBy:0,params:{},formatResult:b.formatResult,delimiter:null,zIndex:9999,type:"GET",noCache:!1,onSearchStart:e,onSearchComplete:e,onSearchError:e,preserveInput:!1,containerClass:"autocomplete-suggestions",tabDisabled:!1,dataType:"text",currentRequest:null,triggerSelectOnValidInput:!0,preventBadQueries:!0,lookupFilter:function(a,b,c){return-1!==a.value.toLowerCase().indexOf(c)},paramName:"query",transformResult:function(b){return"string"==typeof b?a.parseJSON(b):b},showNoSuggestionNotice:!1,noSuggestionNotice:"No results",orientation:"bottom",forceFixPosition:!1};f.element=c,f.el=a(c),f.suggestions=[],f.badQueries=[],f.selectedIndex=-1,f.currentValue=f.element.value,f.intervalId=0,f.cachedResponse={},f.onChangeInterval=null,f.onChange=null,f.isLocal=!1,f.suggestionsContainer=null,f.noSuggestionsContainer=null,f.options=a.extend({},g,d),f.classes={selected:"autocomplete-selected",suggestion:"autocomplete-suggestion"},f.hint=null,f.hintValue="",f.selection=null,f.initialize(),f.setOptions(d)}var c=function(){return{escapeRegExChars:function(a){return a.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g,"\\$&")},createNode:function(a){var b=document.createElement("div");return b.className=a,b.style.position="absolute",b.style.display="none",b}}}(),d={ESC:27,TAB:9,RETURN:13,LEFT:37,UP:38,RIGHT:39,DOWN:40};b.utils=c,a.Autocomplete=b,b.formatResult=function(a,b){var d="("+c.escapeRegExChars(b)+")";return a.value.replace(new RegExp(d,"gi"),"<strong>$1</strong>")},b.prototype={killerFn:null,initialize:function(){var c,d=this,e="."+d.classes.suggestion,f=d.classes.selected,g=d.options;d.element.setAttribute("autocomplete","off"),d.killerFn=function(b){0===a(b.target).closest("."+d.options.containerClass).length&&(d.killSuggestions(),d.disableKillerFn())},d.noSuggestionsContainer=a('<div class="autocomplete-no-suggestion"></div>').html(this.options.noSuggestionNotice).get(0),d.suggestionsContainer=b.utils.createNode(g.containerClass),c=a(d.suggestionsContainer),c.appendTo(g.appendTo),"auto"!==g.width&&c.width(g.width),c.on("mouseover.autocomplete",e,function(){d.activate(a(this).data("index"))}),c.on("mouseout.autocomplete",function(){d.selectedIndex=-1,c.children("."+f).removeClass(f)}),c.on("click.autocomplete",e,function(){d.select(a(this).data("index"))}),d.fixPositionCapture=function(){d.visible&&d.fixPosition()},a(window).on("resize.autocomplete",d.fixPositionCapture),d.el.on("keydown.autocomplete",function(a){d.onKeyPress(a)}),d.el.on("keyup.autocomplete",function(a){d.onKeyUp(a)}),d.el.on("blur.autocomplete",function(){d.onBlur()}),d.el.on("focus.autocomplete",function(){d.onFocus()}),d.el.on("change.autocomplete",function(a){d.onKeyUp(a)}),d.el.on("input.autocomplete",function(a){d.onKeyUp(a)})},onFocus:function(){var a=this;a.fixPosition(),a.options.minChars<=a.el.val().length&&a.onValueChange()},onBlur:function(){this.enableKillerFn()},setOptions:function(b){var c=this,d=c.options;a.extend(d,b),c.isLocal=a.isArray(d.lookup),c.isLocal&&(d.lookup=c.verifySuggestionsFormat(d.lookup)),d.orientation=c.validateOrientation(d.orientation,"bottom"),a(c.suggestionsContainer).css({"max-height":d.maxHeight+"px",width:d.width+"px","z-index":d.zIndex})},clearCache:function(){this.cachedResponse={},this.badQueries=[]},clear:function(){this.clearCache(),this.currentValue="",this.suggestions=[]},disable:function(){var a=this;a.disabled=!0,clearInterval(a.onChangeInterval),a.currentRequest&&a.currentRequest.abort()},enable:function(){this.disabled=!1},fixPosition:function(){var b=this,c=a(b.suggestionsContainer),d=c.parent().get(0);if(d===document.body||b.options.forceFixPosition){var e=b.options.orientation,f=c.outerHeight(),g=b.el.outerHeight(),h=b.el.offset(),i={top:h.top,left:h.left};if("auto"===e){var j=a(window).height(),k=a(window).scrollTop(),l=-k+h.top-f,m=k+j-(h.top+g+f);e=Math.max(l,m)===l?"top":"bottom"}if(i.top+="top"===e?-f:g,d!==document.body){var n,o=c.css("opacity");b.visible||c.css("opacity",0).show(),n=c.offsetParent().offset(),i.top-=n.top,i.left-=n.left,b.visible||c.css("opacity",o).hide()}"auto"===b.options.width&&(i.width=b.el.outerWidth()-2+"px"),c.css(i)}},enableKillerFn:function(){var b=this;a(document).on("click.autocomplete",b.killerFn)},disableKillerFn:function(){var b=this;a(document).off("click.autocomplete",b.killerFn)},killSuggestions:function(){var a=this;a.stopKillSuggestions(),a.intervalId=window.setInterval(function(){a.hide(),a.stopKillSuggestions()},50)},stopKillSuggestions:function(){window.clearInterval(this.intervalId)},isCursorAtEnd:function(){var a,b=this,c=b.el.val().length,d=b.element.selectionStart;return"number"==typeof d?d===c:document.selection?(a=document.selection.createRange(),a.moveStart("character",-c),c===a.text.length):!0},onKeyPress:function(a){var b=this;if(!b.disabled&&!b.visible&&a.which===d.DOWN&&b.currentValue)return void b.suggest();if(!b.disabled&&b.visible){switch(a.which){case d.ESC:b.el.val(b.currentValue),b.hide();break;case d.RIGHT:if(b.hint&&b.options.onHint&&b.isCursorAtEnd()){b.selectHint();break}return;case d.TAB:if(b.hint&&b.options.onHint)return void b.selectHint();if(-1===b.selectedIndex)return void b.hide();if(b.select(b.selectedIndex),b.options.tabDisabled===!1)return;break;case d.RETURN:if(-1===b.selectedIndex)return void b.hide();b.select(b.selectedIndex);break;case d.UP:b.moveUp();break;case d.DOWN:b.moveDown();break;default:return}a.stopImmediatePropagation(),a.preventDefault()}},onKeyUp:function(a){var b=this;if(!b.disabled){switch(a.which){case d.UP:case d.DOWN:return}clearInterval(b.onChangeInterval),b.currentValue!==b.el.val()&&(b.findBestHint(),b.options.deferRequestBy>0?b.onChangeInterval=setInterval(function(){b.onValueChange()},b.options.deferRequestBy):b.onValueChange())}},onValueChange:function(){var b,c=this,d=c.options,e=c.el.val(),f=c.getQuery(e);return c.selection&&c.currentValue!==f&&(c.selection=null,(d.onInvalidateSelection||a.noop).call(c.element)),clearInterval(c.onChangeInterval),c.currentValue=e,c.selectedIndex=-1,d.triggerSelectOnValidInput&&(b=c.findSuggestionIndex(f),-1!==b)?void c.select(b):void(f.length<d.minChars?c.hide():c.getSuggestions(f))},findSuggestionIndex:function(b){var c=this,d=-1,e=b.toLowerCase();return a.each(c.suggestions,function(a,b){return b.value.toLowerCase()===e?(d=a,!1):void 0}),d},getQuery:function(b){var c,d=this.options.delimiter;return d?(c=b.split(d),a.trim(c[c.length-1])):b},getSuggestionsLocal:function(b){var c,d=this,e=d.options,f=b.toLowerCase(),g=e.lookupFilter,h=parseInt(e.lookupLimit,10);return c={suggestions:a.grep(e.lookup,function(a){return g(a,b,f)})},h&&c.suggestions.length>h&&(c.suggestions=c.suggestions.slice(0,h)),c},getSuggestions:function(b){var c,d,e,f,g=this,h=g.options,i=h.serviceUrl;if(h.params[h.paramName]=b,d=h.ignoreParams?null:h.params,h.onSearchStart.call(g.element,h.params)!==!1){if(a.isFunction(h.lookup))return void h.lookup(b,function(a){g.suggestions=a.suggestions,g.suggest(),h.onSearchComplete.call(g.element,b,a.suggestions)});g.isLocal?c=g.getSuggestionsLocal(b):(a.isFunction(i)&&(i=i.call(g.element,b)),e=i+"?"+a.param(d||{}),c=g.cachedResponse[e]),c&&a.isArray(c.suggestions)?(g.suggestions=c.suggestions,g.suggest(),h.onSearchComplete.call(g.element,b,c.suggestions)):g.isBadQuery(b)?h.onSearchComplete.call(g.element,b,[]):(g.currentRequest&&g.currentRequest.abort(),f={url:i,data:d,type:h.type,dataType:h.dataType},a.extend(f,h.ajaxSettings),g.currentRequest=a.ajax(f).done(function(a){var c;g.currentRequest=null,c=h.transformResult(a),g.processResponse(c,b,e),h.onSearchComplete.call(g.element,b,c.suggestions)}).fail(function(a,c,d){h.onSearchError.call(g.element,b,a,c,d)}))}},isBadQuery:function(a){if(!this.options.preventBadQueries)return!1;for(var b=this.badQueries,c=b.length;c--;)if(0===a.indexOf(b[c]))return!0;return!1},hide:function(){var b=this;b.visible=!1,b.selectedIndex=-1,clearInterval(b.onChangeInterval),a(b.suggestionsContainer).hide(),b.signalHint(null)},suggest:function(){if(0===this.suggestions.length)return void(this.options.showNoSuggestionNotice?this.noSuggestions():this.hide());var b,c,d=this,e=d.options,f=e.groupBy,g=e.formatResult,h=d.getQuery(d.currentValue),i=d.classes.suggestion,j=d.classes.selected,k=a(d.suggestionsContainer),l=a(d.noSuggestionsContainer),m=e.beforeRender,n="",o=function(a){var c=a.data[f];return b===c?"":(b=c,'<div class="autocomplete-group"><strong>'+b+"</strong></div>")};return e.triggerSelectOnValidInput&&(c=d.findSuggestionIndex(h),-1!==c)?void d.select(c):(a.each(d.suggestions,function(a,b){f&&(n+=o(b,h,a)),n+='<div class="'+i+'" data-index="'+a+'">'+g(b,h)+"</div>"}),this.adjustContainerWidth(),l.detach(),k.html(n),a.isFunction(m)&&m.call(d.element,k),d.fixPosition(),k.show(),e.autoSelectFirst&&(d.selectedIndex=0,k.scrollTop(0),k.children().first().addClass(j)),d.visible=!0,void d.findBestHint())},noSuggestions:function(){var b=this,c=a(b.suggestionsContainer),d=a(b.noSuggestionsContainer);this.adjustContainerWidth(),d.detach(),c.empty(),c.append(d),b.fixPosition(),c.show(),b.visible=!0},adjustContainerWidth:function(){var b,c=this,d=c.options,e=a(c.suggestionsContainer);"auto"===d.width&&(b=c.el.outerWidth()-2,e.width(b>0?b:300))},findBestHint:function(){var b=this,c=b.el.val().toLowerCase(),d=null;c&&(a.each(b.suggestions,function(a,b){var e=0===b.value.toLowerCase().indexOf(c);return e&&(d=b),!e}),b.signalHint(d))},signalHint:function(b){var c="",d=this;b&&(c=d.currentValue+b.value.substr(d.currentValue.length)),d.hintValue!==c&&(d.hintValue=c,d.hint=b,(this.options.onHint||a.noop)(c))},verifySuggestionsFormat:function(b){return b.length&&"string"==typeof b[0]?a.map(b,function(a){return{value:a,data:null}}):b},validateOrientation:function(b,c){return b=a.trim(b||"").toLowerCase(),-1===a.inArray(b,["auto","bottom","top"])&&(b=c),b},processResponse:function(a,b,c){var d=this,e=d.options;a.suggestions=d.verifySuggestionsFormat(a.suggestions),e.noCache||(d.cachedResponse[c]=a,e.preventBadQueries&&0===a.suggestions.length&&d.badQueries.push(b)),b===d.getQuery(d.currentValue)&&(d.suggestions=a.suggestions,d.suggest())},activate:function(b){var c,d=this,e=d.classes.selected,f=a(d.suggestionsContainer),g=f.find("."+d.classes.suggestion);return f.find("."+e).removeClass(e),d.selectedIndex=b,-1!==d.selectedIndex&&g.length>d.selectedIndex?(c=g.get(d.selectedIndex),a(c).addClass(e),c):null},selectHint:function(){var b=this,c=a.inArray(b.hint,b.suggestions);b.select(c)},select:function(a){var b=this;b.hide(),b.onSelect(a)},moveUp:function(){var b=this;if(-1!==b.selectedIndex)return 0===b.selectedIndex?(a(b.suggestionsContainer).children().first().removeClass(b.classes.selected),b.selectedIndex=-1,b.el.val(b.currentValue),void b.findBestHint()):void b.adjustScroll(b.selectedIndex-1)},moveDown:function(){var a=this;a.selectedIndex!==a.suggestions.length-1&&a.adjustScroll(a.selectedIndex+1)},adjustScroll:function(b){var c=this,d=c.activate(b);if(d){var e,f,g,h=a(d).outerHeight();e=d.offsetTop,f=a(c.suggestionsContainer).scrollTop(),g=f+c.options.maxHeight-h,f>e?a(c.suggestionsContainer).scrollTop(e):e>g&&a(c.suggestionsContainer).scrollTop(e-c.options.maxHeight+h),c.options.preserveInput||c.el.val(c.getValue(c.suggestions[b].value)),c.signalHint(null)}},onSelect:function(b){var c=this,d=c.options.onSelect,e=c.suggestions[b];c.currentValue=c.getValue(e.value),c.currentValue===c.el.val()||c.options.preserveInput||c.el.val(c.currentValue),c.signalHint(null),c.suggestions=[],c.selection=e,a.isFunction(d)&&d.call(c.element,e)},getValue:function(a){var b,c,d=this,e=d.options.delimiter;return e?(b=d.currentValue,c=b.split(e),1===c.length?a:b.substr(0,b.length-c[c.length-1].length)+a):a},dispose:function(){var b=this;b.el.off(".autocomplete").removeData("autocomplete"),b.disableKillerFn(),a(window).off("resize.autocomplete",b.fixPositionCapture),a(b.suggestionsContainer).remove()}},a.fn.autocomplete=a.fn.devbridgeAutocomplete=function(c,d){var e="autocomplete";return 0===arguments.length?this.first().data(e):this.each(function(){var f=a(this),g=f.data(e);"string"==typeof c?g&&"function"==typeof g[c]&&g[c](d):(g&&g.dispose&&g.dispose(),g=new b(this,c),f.data(e,g))})}});
            $('#dropdownMenu1').click(function(){
                 $('#browseAdminCities').addClass('show');
                $("body").append('<div class="modal-backdrop fade show"></div>');
                $('#browseAdminCities').show();

            });
            $('.send_message').click(function(){
                 $('#contactUser').addClass('show');
                $("body").append('<div class="modal-backdrop fade show"></div>');
                $('#contactUser').show();

            });
            $('.resend_message').click(function(){
                 $('#contactUser').addClass('show');
                $("body").append('<div class="modal-backdrop fade show"></div>');
                $('#contactUser').show();
                $('#userOTP').removeClass('show');

                $('#userOTP').hide();
            });
            $(".close-btn").click(function(){
                    $('#contactUser').removeClass('show');
                    $(".modal-backdrop").remove();
                    $('#contactUser').hide();
                    $('#userOTP').removeClass('show');
                    $('#userOTP').hide();

                    $('#browseAdminCities').removeClass('show');
                    $('#browseAdminCities').hide();

            });
            $(document).ready(function(){
                var token = $('meta[name="csrf-token"]').attr('content');
                if (token) {
                    $.ajaxSetup({
                        headers: {'X-CSRF-TOKEN': token}
                    });
                }

                $('input#locSearch').devbridgeAutocomplete({
                    zIndex: 1492,
                    serviceUrl: siteUrl + '/ajax/countries/' + countryCode + '/cities/autocomplete',
                    type: 'post',
                    data: {
                        'city': $(this).val(),
                        '_token': $('input[name=_token]').val()
                    },
                    minChars: 1,
                    onSelect: function(suggestion) {
                        $('#lSearch').val(suggestion.data);
                        $('#seach').submit();
                    }
                });
            });
    </script>
@else
<script src="{{ url(mix('js/app.js')) }}" {{$defer}}></script>
@if (file_exists(public_path() . '/assets/plugins/select2/js/i18n/'.config('app.locale').'.js'))
	<script src="{{ url('assets/plugins/select2/js/i18n/'.config('app.locale').'.js') }}" {{$defer}}></script>
@endif
@if (config('plugins.detectadsblocker.installed'))
	<script src="{{ url('assets/detectadsblocker/js/script.js') . getPictureVersion() }}" {{$defer}}></script>
@endif
@endif

@if(Request::is('/') || Request::is('category/*') || (request()->city!='' && request()->catSlug!=''))
    <script>
            $(document).ready(function () {
                $('.navbar-toggler.pull-right').addClass('collapsed');

            $('.login-nav').click(function(){
                $('#quickLogin').hide();
                $('#userLogin').addClass('show');
                $("body").append('<div class="modal-backdrop fade show"></div>');
                $('#userLogin').show();

            });
            $('.quickLogin').click(function(){
                $('#userLogin').hide();
                $('#quickLogin').addClass('show');
                $("body").append('<div class="modal-backdrop fade show"></div>');
                $('#quickLogin').show();
            });
            $('.close').click(function(){
                $('#userLogin').removeClass('show');
                $(".modal-backdrop").remove();
                $('#userLogin').hide();
                $('#quickLogin').hide();

            });
            $('.navbar-toggler').click(function(){

                if($(this).hasClass('opennav')){
                $('.navbar-collapse.collapse').removeClass('show');
                $('.navbar-toggler.pull-right').addClass('collapsed');
                $('.navbar-toggler.pull-right').removeClass('opennav');
                }else{
                $('.navbar-collapse.collapse').addClass('show');
                $('.navbar-toggler.pull-right').removeClass('collapsed');
                $('.navbar-toggler.pull-right').addClass('opennav');
                }

            });
            $('.send_message').click(function(){
                 $('#contactUser').addClass('show');
                $("body").append('<div class="modal-backdrop fade show"></div>');
                $('#contactUser').show();

            });
            $('.send_message_prev').click(function(){
                 $('#contactUser').addClass('show');
                $("body").append('<div class="modal-backdrop fade show"></div>');
                $('#userOTP').hide();
                $('#userOTP').removeClass('show');

            });
       })
        </script>
@endif

<script>

	function setCookie(key, value, expiry) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
        document.cookie = key + '=' + value + ';path=/;expires=' + expires.toUTCString();
    }

    function getCookie(key) {
        var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
        return keyValue ? keyValue[2] : null;
    }

    function eraseCookie(key) {
        var keyValue = getCookie(key);
        setCookie(key, keyValue, '-1');
    }

	$(document).ready(function () {



		$('#userLogin').on('shown.bs.modal', function (e) {
		   if(!e.relatedTarget){
		   		$('#userLogin').addClass('auto');
		   		$('#userLogin .with_username').hide();
		   }
		   else{
		   		$('#userLogin').removeClass('auto');
		   		$('#userLogin .with_username').show();
		   }
		});

		$('#userLogin').on('hidden.bs.modal', function (e) {
		    if($('#userLogin').hasClass('auto')){

		    	var previous = getCookie('userLogin');

		    	previous++;
		    	setCookie('userLogin',previous,'365');

		    }
		});

		{{-- Select Boxes --}}


		{{-- Searchable Select Boxes --}}
		$('.sselecter,#modalAdminField').select2({
			language: langLayout.select2,
			dropdownAutoWidth: 'true',
			width: '100%'
		});

		{{-- Social Share --}}
		/* $('.share').ShareLink({
			title: '{{ addslashes(MetaTag::get('title')) }}',
			text: '{!! addslashes(MetaTag::get('title')) !!}',
			url: '{!! $fullUrl !!}',
			width: 640,
			height: 480
		}); */


		@if (!auth()->check())

			{{-- Modal Login --}}
			@if (isset($errors) and $errors->any())
				@if ($errors->any() and old('quickLoginForm')=='1')
					$('#quickLogin').modal();
				@endif
			@endif

			@if(!Cookie::get('user'))

			if(getCookie('userLogin')<=3){

				var userLogin = setInterval(function(){

				 	if(getCookie('userLogin')>2){
						clearInterval(userLogin);
					}
					if(!$('.modal').hasClass('show')){

						$("#userLogin").modal();


					}

				}, 120000);
			}


			@endif




			@if ($errors->any())
				@if ($errors->any() and old('quickSignForm')=='1')

					$('#userLogin').modal();
				@endif
			@endif

			@if (\Session::has('otp') || ($errors->any() and old('otp_phone')!=''))
					$('#userOTP').modal();
			@endif

		@endif
	});
</script>

<!--  Hotjar Tracking Code for  -->
<script>
  /*  (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:3439548,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');  */
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
