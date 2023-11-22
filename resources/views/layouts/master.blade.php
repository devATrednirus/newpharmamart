
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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

<!-- Hotjar Tracking Code for --> <script>(function(h,o,t,j,a,r){ h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};h._hjSettings={hjid:3439548,hjsv:6};a=o.getElementsByTagName('head')[0];r=o.createElement('script');r.async=1; r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;a.appendChild(r);})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');</script>



<?php } ?>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@include('common.meta-robots')
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="apple-mobile-web-app-title" content="{{ config('settings.app.app_name') }}">
        <link rel="icon" type="image/x-icon" href="{{ \Storage::url(config('settings.app.favicon')) . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ \Storage::url(config('settings.app.favicon')) . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ \Storage::url(config('settings.app.favicon')) . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ \Storage::url(config('settings.app.favicon')) . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" href="{{ \Storage::url(config('settings.app.favicon')) . getPictureVersion() }}">
          <link href="{{ asset('css/new.css') . getPictureVersion() }}" rel="stylesheet">



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
        <style type="text/css">
        @charset "utf-8";.alert,.dropdown,.dropup,.input-icon,.relative{position:relative}#msform .action-button,#msform .action-button-previous{width:100px;font-weight:700;padding:10px 5px;margin:10px 5px;cursor:pointer}.dropdown-menu,body{text-align:left}.modal-open,svg,svg:not(:root){overflow:hidden}.dropdown>.dropdown-toggle:active,.modal-dialog{pointer-events:none}html{-ms-overflow-style:scrollbar}a{-webkit-text-decoration-skip:objects}#msform .action-button,.btn:not(:disabled):not(.disabled),.close:not(:disabled):not(.disabled),.navbar-toggler:not(:disabled):not(.disabled),.page-link:not(:disabled):not(.disabled),a,button{cursor:pointer}.btn,.dtable-cell,svg{vertical-align:middle}.fa,.fab,h1,h2,h3,h4,h5{-webkit-font-smoothing:antialiased}.skin-blue button.btn-search,button.btn-search{text-shadow:0 2px 2px #4682b4;-webkit-text-shadow:0 2px 2px #4682b4}.btn,.dropdown-item,.fib a::before,.input-group-text,.navbar-brand,.sr-only{white-space:nowrap}.dropdown-menu,.list-inline,.nav,.navbar-nav,li,ul{list-style:none}.animated{-webkit-animation-duration:1s;animation-duration:1s;-webkit-animation-fill-mode:both;animation-fill-mode:both}.fa-th-large:before{content:"\F009"}.fa-home:before{content:"\F015"}.fa-heart:before{content:"\F004"}.fa-hourglass-half:before{content:"\F252"}.icon-folder:before{content:"\E8CB"}.fa-money:before{content:"\f0d6"}.fa-envelope:before{content:"\F0E0"}.fa-sign-in-alt:before{content:"\F2F6"}.fa-file:before{content:"\F15B"}.fa-sign-out:before{content:"\F2F5"}.fa-folder:before{content:"\F07B"}.dropdown:hover>.dropdown-menu,.navbar.navbar-light .navbar-nav>li a.nav-link i,footer,nav,section{display:block}.bottom-links li,.fib a:hover::before,.navbar-site.navbar .navbar-nav.navbar-right .dropdown .icon-down-open-big.fa{display:inline-block}.alert-success{color:#155724;background-color:#d4edda;border-color:#c3e6cb}.alert-success hr{border-top-color:#b1dfbb}.alert-success .alert-link{color:#0b2e13}.alert{padding:.75rem 1.25rem;margin-bottom:1rem;border:1px solid transparent;border-radius:.25rem}.page-nav .navbar.navbar-light .navbar-nav>li .nav-link:not(.btn),.page-nav .navbar.navbar-light .navbar-nav>li>a:not(.btn),.page-nav .navbar.navbar-site .navbar-nav>li .nav-link:not(.btn),.page-nav .navbar.navbar-site .navbar-nav>li>a:not(.btn){border-radius:3px;-moz-box-sizing:border-box;box-sizing:border-box;color:#444!important;font-size:15px!important;height:40px;line-height:1;padding:12px 10px}#msform fieldset,*,.selecter,.selecter *,.selecter :after,.selecter :before,::after,::before,input[type=checkbox]{box-sizing:border-box}.dropdown-menu,.navbar-expand .navbar-nav .dropdown-menu{position:absolute}.dropdown-toggle::after{display:inline-block;width:0;height:0;margin-left:.3em;vertical-align:middle;content:"";border-top:.3em solid;border-right:.3em solid transparent;border-left:.3em solid transparent}*,.dropdown-toggle:focus,:focus,:hover{outline:0}.dropup .dropdown-toggle::after{border-top:0;border-bottom:.3em solid}.dropdown-menu{top:94%;left:0;z-index:1000;display:none;float:left;min-width:10rem;padding:.5rem 0;margin:.125rem 0 0;font-size:.85rem;color:#292b2c;background-color:#fff;background-clip:padding-box;border:1px solid rgba(0,0,0,.15)}#msform,#msform fieldset,.card,.input-group,.input-group>.form-control,.navbar,.selecter{position:relative}.dropdown-menu.dropdown-line{width:-webkit-calc(100% - 10px);width:-moz-calc(100% - 10px);width:calc(100% - 10px)}.dropdown-menu.dropdown-line.has-form .dropdown-item,.dropdown-menu.dropdown-line.has-form .dropdown-item a{font-size:13px;padding:8px 12px}.dropdown-menu.dropdown-line.has-form .dropdown-item .custom-control{display:-webkit-flex;display:-moz-box;display:flex;-webkit-align-items:center;-moz-box-align:center;align-items:center}.dropdown-menu.dropdown-line>a{font-size:13px}.dropdown-item{display:block;width:100%;clear:both;font-weight:400;color:#292b2c;text-align:inherit;background-color:transparent;border:0}.dropdown-item:focus,.dropdown-item:hover{color:#1d1e1f;text-decoration:none;background-color:#f7f7f9}.dropdown-item.active,.dropdown-item:active{color:#fff;text-decoration:none;background-color:#16a085}.dropdown-item.disabled,.dropdown-item:disabled{color:#636c72;background-color:transparent}@media (max-width:991.98px){.navbar-expand-lg>.container,.navbar-expand-lg>.container-fluid{padding-right:0;padding-left:0}}@media (max-width:1199.98px){.navbar-expand-xl>.container,.navbar-expand-xl>.container-fluid{padding-right:0;padding-left:0}}.navbar-expand{-ms-flex-flow:row nowrap;flex-flow:row nowrap;-ms-flex-pack:start;justify-content:flex-start}.navbar-expand .navbar-nav{-ms-flex-direction:row;flex-direction:row}.navbar-nav .dropdown-menu{float:none}.navbar-expand .navbar-nav .nav-link{padding-right:.5rem;padding-left:.5rem}.navbar-expand>.container,.navbar-expand>.container-fluid{padding-right:0;padding-left:0;-ms-flex-wrap:nowrap;flex-wrap:nowrap}.navbar-expand .navbar-collapse{display:-ms-flexbox!important;display:flex!important;-ms-flex-preferred-size:auto;flex-basis:auto}#msform fieldset:not(:first-of-type),.collapse:not(.show),.navbar-expand .navbar-toggler{display:none}.fa,.fab,[class*=" icon-"]:before,[class^=icon-]:before{font-style:normal;display:inline-block;font-variant:normal}.navbar-light .navbar-nav .nav-link.disabled{color:rgba(0,0,0,.3)}.navbar-light .navbar-brand,.navbar-light .navbar-brand:focus,.navbar-light .navbar-brand:hover,.navbar-light .navbar-nav .active>.nav-link,.navbar-light .navbar-nav .nav-link.active,.navbar-light .navbar-nav .nav-link.show,.navbar-light .navbar-nav .show>.nav-link,.navbar-light .navbar-text a,.navbar-light .navbar-text a:focus,.navbar-light .navbar-text a:hover{color:rgba(0,0,0,.9)}.navbar-light .navbar-toggler-icon{background-image:url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(0, 0, 0, 0.5)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E")}.navbar-light .navbar-nav .nav-link,.navbar-light .navbar-text{color:rgba(0,0,0,.5)}.btn-primary:disabled,.btn-primary:not([href]):not([tabindex]):not(.btn-line),.btn-primary:not([href]):not([tabindex]):not(.btn-line):hover,.btn-success:disabled,.btn-success:not([href]):not([tabindex]),.btn-success:not([href]):not([tabindex]):hover,.footer-col ul li a:hover,.footer-services-sec h5,.iconbox-wrap-icon .icon,.navbar-dark .navbar-brand,.navbar-dark .navbar-brand:focus,.navbar-dark .navbar-brand:hover,.navbar-dark .navbar-nav .active>.nav-link,.navbar-dark .navbar-nav .nav-link.active,.navbar-dark .navbar-nav .nav-link.show,.navbar-dark .navbar-nav .show>.nav-link,.navbar-dark .navbar-text a,.navbar-dark .navbar-text a:focus,.navbar-dark .navbar-text a:hover,.skin-blue .btn-default,.skin-blue .btn-default:active,.skin-blue .btn-default:focus,.skin-blue .btn-default:hover,.skin-blue .btn-primary:active,.skin-blue .btn-primary:focus,.skin-blue .btn-primary:hover,.skin-blue .btn-success,.skin-blue .btn-success:active,.skin-blue .btn-success:focus,.skin-blue .btn-success:hover,.wide-intro .intro-title,.wide-intro p{color:#fff}.navbar-dark .navbar-nav .nav-link,.navbar-dark .navbar-text{color:rgba(255,255,255,.5)}.navbar-dark .navbar-nav .nav-link:focus,.navbar-dark .navbar-nav .nav-link:hover{color:rgba(255,255,255,.75)}.navbar-dark .navbar-nav .nav-link.disabled{color:rgba(255,255,255,.25)}.navbar-dark .navbar-toggler{color:rgba(255,255,255,.5);border-color:rgba(255,255,255,.1)}.navbar-dark .navbar-toggler-icon{background-image:url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255, 255, 255, 0.5)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E")}.dropdown-menu-right{right:0;left:auto}.modal,.modal-backdrop{right:0;left:0;bottom:0}@-webkit-keyframes bounce{0%,20%,50%,80%,to{transform:translateY(0)}40%{transform:translateY(-30px)}60%{transform:translateY(-15px)}}@keyframes bounce{0%,20%,50%,80%,to{transform:translateY(0)}40%{transform:translateY(-30px)}60%{transform:translateY(-15px)}}.bounce{-webkit-animation-name:bounce;animation-name:bounce}.fadeIn{-webkit-animation-name:fadeIn;animation-name:fadeIn}@-webkit-keyframes fadeInDown{0%{opacity:0;transform:translateY(-20px)}to{opacity:1;transform:translateY(0)}}@keyframes fadeInDown{0%{opacity:0;transform:translateY(-20px)}to{opacity:1;transform:translateY(0)}}.fadeInDown{-webkit-animation-name:fadeInDown;animation-name:fadeInDown}@-webkit-keyframes fadeInUp{0%{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}@keyframes fadeInUp{0%{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}.fadeInUp{-webkit-animation-name:fadeInUp;animation-name:fadeInUp}.fa,.fab{-moz-osx-font-smoothing:grayscale;text-rendering:auto;line-height:1}.fa-comment:before{content:"\F075"}.fa-facebook:before{content:"\F09A"}.fa-linkedin:before{content:"\F08C"}.fa-phone:before{content:"\F095"}.fa-pinterest-p:before{content:"\F231"}.fa-quora:before{content:"\F2C4"}.fa-reddit:before{content:"\F1A1"}.fa-search:before{content:"\F002"}.fa-shield-alt:before{content:"\F3ED"}.fa-star:before{content:"\F005"}.fa-twitter:before{content:"\F099"}.fa-user:before{content:"\F007"}.fa-user-plus:before{content:"\F234"}.fa-whatsapp:before{content:"\F232"}.fa-youtube:before{content:"\F167"}.sr-only{margin:-1px}.container,.container-fluid{margin-right:auto;margin-left:auto}.form-group,p,ul{margin-bottom:1rem}@font-face{font-family:Font Awesome\ 5 Brands;font-style:normal;font-weight:400;src:url(../assets/plugins/fontawesome/webfonts/fa-brands-400.eot);src:url(../assets/plugins/fontawesome/webfonts/fa-brands-400.eot?#iefix) format("embedded-opentype"),url(../assets/plugins/fontawesome/webfonts/fa-brands-400.woff2) format("woff2"),url(../assets/plugins/fontawesome/webfonts/fa-brands-400.woff) format("woff"),url(../assets/plugins/fontawesome/webfonts/fa-brands-400.ttf) format("truetype"),url(../assets/plugins/fontawesome/webfonts/fa-brands-400.svg#fontawesome) format("svg")}.fab{font-family:Font Awesome\ 5 Brands}@font-face{font-family:Font Awesome\ 5 Free;font-style:normal;font-weight:400;src:url(../assets/plugins/fontawesome/webfonts/fa-regular-400.eot);src:url(../assets/plugins/fontawesome/webfonts/fa-regular-400.eot?#iefix) format("embedded-opentype"),url(../assets/plugins/fontawesome/webfonts/fa-regular-400.woff2) format("woff2"),url(../assets/plugins/fontawesome/webfonts/fa-regular-400.woff) format("woff"),url(../assets/plugins/fontawesome/webfonts/fa-regular-400.ttf) format("truetype"),url(../assets/plugins/fontawesome/webfonts/fa-regular-400.svg#fontawesome) format("svg")}@font-face{font-family:Font Awesome\ 5 Free;font-style:normal;font-weight:900;src:url(../assets/plugins/fontawesome/webfonts/fa-solid-900.eot);src:url(../assets/plugins/fontawesome/webfonts/fa-solid-900.eot?#iefix) format("embedded-opentype"),url(../assets/plugins/fontawesome/webfonts/fa-solid-900.woff2) format("woff2"),url(../assets/plugins/fontawesome/webfonts/fa-solid-900.woff) format("woff"),url(../assets/plugins/fontawesome/webfonts/fa-solid-900.ttf) format("truetype"),url(../assets/plugins/fontawesome/webfonts/fa-solid-900.svg#fontawesome) format("svg")}.fa{font-family:Font Awesome\ 5 Free;font-weight:900}@font-face{font-family:fontello;src:url(../assets/fonts/fontello/fontello.eot?4089732);src:url(../assets/fonts/fontello/fontello.eot?4089732#iefix) format("embedded-opentype"),url(../assets/fonts/fontello/fontello.woff?4089732) format("woff"),url(../assets/fonts/fontello/fontello.ttf?4089732) format("truetype"),url(../assets/fonts/fontello/fontello.svg?4089732#fontello) format("svg");font-weight:400;font-style:normal}[class*=" icon-"]:before,[class^=icon-]:before{font-family:fontello;font-weight:400;speak:none;text-decoration:inherit;width:1em;margin-right:.2em;text-align:center;text-transform:none;line-height:1em;margin-left:.2em}.icon-search:before{content:"\E800"}.icon-user:before{content:"\E806"}.icon-lock:before{content:"\E812"}.icon-help:before{content:"\E818"}.icon-docs:before{content:"\E82C"}.icon-location:before{content:"\E833"}.icon-login:before{content:"\E841"}.icon-mail-2:before{content:"\E8BA"}.icon-phone-1:before{content:"\E8C8"}.icon-instagram-filled:before{content:"\E8F8"}.icon-group:before{content:"\E98E"}.nice-select .list:hover .option:not(:hover){background-color:transparent!important}.selecter{display:block;z-index:1}.input-group,.row{display:-ms-flexbox}.selecter:focus{box-shadow:none;outline:0}.selecter,.selecter *{-webkit-user-select:none!important;-moz-user-select:none!important;-ms-user-select:none!important;user-select:none!important}@media screen and (max-width:740px){.selecter{max-width:40%}}@media screen and (max-width:500px){.selecter{max-width:100%}}#msform{text-align:center;margin-top:30px}#msform fieldset{background:#fff;border:0;border-radius:0;box-shadow:0 0 15px 1px rgba(0,0,0,.4);padding:20px 30px;width:80%;margin:0 10%}#msform input,#msform select,#msform textarea{border:1px solid #ccc;border-radius:0;margin-bottom:10px;width:100%;box-sizing:border-box;font-family:montserrat;color:#2c3e50;font-size:13px}#msform input:focus,#msform select:focus,#msform textarea:focus{box-shadow:none!important;border:1px solid #ee0979;outline-width:0;transition:All .5s ease-in;-webkit-transition:All .5s ease-in;-moz-transition:All .5s ease-in;-o-transition:All .5s ease-in}#msform .action-button{background:#ee0979;color:#fff;border:0;border-radius:25px}#msform .action-button:focus,#msform .action-button:hover{box-shadow:0 0 0 2px #fff,0 0 0 3px #ee0979}#msform .action-button-previous{background:#c5c5f1;color:#fff;border:0;border-radius:25px}#msform .action-button-previous:focus,#msform .action-button-previous:hover{box-shadow:0 0 0 2px #fff,0 0 0 3px #c5c5f1}:root{--blue:#007bff;--indigo:#6610f2;--purple:#6f42c1;--pink:#e83e8c;--red:#dc3545;--orange:#fd7e14;--yellow:#ffc107;--green:#28a745;--teal:#20c997;--cyan:#17a2b8;--white:#fff;--gray:#6c757d;--gray-dark:#343a40;--primary:#007bff;--secondary:#6c757d;--success:#28a745;--info:#17a2b8;--warning:#ffc107;--danger:#dc3545;--light:#f8f9fa;--dark:#343a40;--breakpoint-xs:0;--breakpoint-sm:576px;--breakpoint-md:768px;--breakpoint-lg:992px;--breakpoint-xl:1200px;--font-family:'Roboto',sans-serif}@-ms-viewport{width:device-width}button{border-radius:0}input[type=checkbox]{padding:0}textarea{resize:vertical;overflow:auto;resize:vertical}::-webkit-file-upload-button{font:inherit;-webkit-appearance:button}button,html,input,select,textarea{font-family:sans-serif;line-height:1.15}h1,h2,h3,h4,h5{color:inherit;color:inherit}.list-inline,.list-unstyled{padding-left:0;list-style:none}.container{width:100%;padding-right:0;padding-left:0}.container-fluid{max-width:1460px;width:100%;padding-right:15px;padding-left:15px}.col,img{max-width:100%}.row{display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}.col,.col-3,.col-9,.col-lg-6,.col-md-1,.col-md-10,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-sm-10,.col-sm-12,.col-sm-2,.col-sm-4,.col-sm-6,.col-xl-1,.col-xl-2,.col-xl-7{position:relative;width:100%;min-height:1px;padding-right:15px;padding-left:15px}.col{-ms-flex-preferred-size:0;flex-basis:0;-ms-flex-positive:1;flex-grow:1}.col-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-9{-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.btn:focus,.form-control:focus{box-shadow:0 0 0 .2rem rgba(0,123,255,.25)}.form-control::-moz-placeholder{color:#6c757d;opacity:1}.form-control:-ms-input-placeholder{color:#6c757d;opacity:1}.form-control::-ms-input-placeholder{color:#6c757d;opacity:1}select.form-control:focus::-ms-value{color:#495057;background-color:#fff}textarea.form-control{height:auto}#seach,.form-check-label,.input-group-text,.nav,.navbar-nav,.pk-login p,.wide-intro #search{margin-bottom:0}.btn:disabled,input[type=checkbox]:disabled{cursor:not-allowed}.btn-outline-primary:not(:disabled):not(.disabled).active:focus,.btn-outline-primary:not(:disabled):not(.disabled):active:focus,.btn-primary:focus,.btn-primary:not(:disabled):not(.disabled).active:focus,.btn-primary:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(0,123,255,.5)}.btn-primary:not(:disabled):not(.disabled).active,.btn-primary:not(:disabled):not(.disabled):active{color:#fff;background-color:#0062cc;border-color:#005cbf}.btn-secondary:not(:disabled):not(.disabled).active,.btn-secondary:not(:disabled):not(.disabled):active{color:#fff;background-color:#545b62;border-color:#4e555b}.btn-outline-secondary:not(:disabled):not(.disabled).active:focus,.btn-outline-secondary:not(:disabled):not(.disabled):active:focus,.btn-secondary:not(:disabled):not(.disabled).active:focus,.btn-secondary:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(108,117,125,.5)}.btn-outline-success:not(:disabled):not(.disabled).active:focus,.btn-outline-success:not(:disabled):not(.disabled):active:focus,.btn-success:focus,.btn-success:not(:disabled):not(.disabled).active:focus,.btn-success:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(40,167,69,.5)}.btn-info:not(:disabled):not(.disabled).active,.btn-info:not(:disabled):not(.disabled):active{color:#fff;background-color:#117a8b;border-color:#10707f}.btn-info:not(:disabled):not(.disabled).active:focus,.btn-info:not(:disabled):not(.disabled):active:focus,.btn-outline-info:not(:disabled):not(.disabled).active:focus,.btn-outline-info:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(23,162,184,.5)}.btn-warning:not(:disabled):not(.disabled).active,.btn-warning:not(:disabled):not(.disabled):active{color:#212529;background-color:#d39e00;border-color:#c69500}.btn-outline-warning:not(:disabled):not(.disabled).active:focus,.btn-outline-warning:not(:disabled):not(.disabled):active:focus,.btn-warning:not(:disabled):not(.disabled).active:focus,.btn-warning:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(255,193,7,.5)}.btn-danger:not(:disabled):not(.disabled).active,.btn-danger:not(:disabled):not(.disabled):active{color:#fff;background-color:#bd2130;border-color:#b21f2d}.btn-danger:not(:disabled):not(.disabled).active:focus,.btn-danger:not(:disabled):not(.disabled):active:focus,.btn-outline-danger:not(:disabled):not(.disabled).active:focus,.btn-outline-danger:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(220,53,69,.5)}.btn-light:not(:disabled):not(.disabled).active,.btn-light:not(:disabled):not(.disabled):active{color:#212529;background-color:#dae0e5;border-color:#d3d9df}.btn-light:not(:disabled):not(.disabled).active:focus,.btn-light:not(:disabled):not(.disabled):active:focus,.btn-outline-light:not(:disabled):not(.disabled).active:focus,.btn-outline-light:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(248,249,250,.5)}.btn-dark:not(:disabled):not(.disabled).active,.btn-dark:not(:disabled):not(.disabled):active{color:#fff;background-color:#1d2124;border-color:#171a1d}.btn-dark:not(:disabled):not(.disabled).active:focus,.btn-dark:not(:disabled):not(.disabled):active:focus,.btn-outline-dark:not(:disabled):not(.disabled).active:focus,.btn-outline-dark:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(52,58,64,.5)}.btn-outline-primary:not(:disabled):not(.disabled).active,.btn-outline-primary:not(:disabled):not(.disabled):active{color:#fff;background-color:#007bff;border-color:#007bff}.btn-outline-secondary:not(:disabled):not(.disabled).active,.btn-outline-secondary:not(:disabled):not(.disabled):active{color:#fff;background-color:#6c757d;border-color:#6c757d}.btn-outline-success:not(:disabled):not(.disabled).active,.btn-outline-success:not(:disabled):not(.disabled):active{color:#fff;background-color:#28a745;border-color:#28a745}.btn-outline-info:not(:disabled):not(.disabled).active,.btn-outline-info:not(:disabled):not(.disabled):active{color:#fff;background-color:#17a2b8;border-color:#17a2b8}.btn-outline-warning:not(:disabled):not(.disabled).active,.btn-outline-warning:not(:disabled):not(.disabled):active{color:#212529;background-color:#ffc107;border-color:#ffc107}.btn-outline-danger:not(:disabled):not(.disabled).active,.btn-outline-danger:not(:disabled):not(.disabled):active{color:#fff;background-color:#dc3545;border-color:#dc3545}.btn-outline-light:not(:disabled):not(.disabled).active,.btn-outline-light:not(:disabled):not(.disabled):active{color:#212529;background-color:#f8f9fa;border-color:#f8f9fa}.btn-outline-dark:not(:disabled):not(.disabled).active,.btn-outline-dark:not(:disabled):not(.disabled):active{color:#fff;background-color:#343a40;border-color:#343a40}.fade{transition:opacity .15s linear}.fade:not(.show),.modal-backdrop.fade{opacity:0}.input-group{display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-ms-flex-align:stretch;align-items:stretch;width:100%}.input-group>.form-control{-ms-flex:1 1 auto;flex:1 1 auto;width:1%;margin-bottom:0}.input-group>.form-control:focus{z-index:3}.input-group>.form-control:not(:first-child){border-top-left-radius:0;border-bottom-left-radius:0}.input-group-prepend{display:-ms-flexbox;display:flex;margin-right:-1px}.input-group-text{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;padding:.375rem .75rem;font-size:1rem;font-weight:400;line-height:1.5;color:#495057;text-align:center;background-color:#e9ecef;border:1px solid #ced4da;border-radius:.25rem}.nav,.navbar{-ms-flex-wrap:wrap}.navbar-toggler,a,button.close{background-color:transparent}.input-group>.input-group-prepend>.input-group-text{border-top-right-radius:0;border-bottom-right-radius:0}.nav{display:-ms-flexbox;display:flex;flex-wrap:wrap;padding-left:0}.nav-link,.navbar{padding:.5rem 1rem}.nav-link{display:block}.btn:focus,.btn:hover,.nav-link:focus,.nav-link:hover,.navbar-brand:focus,.navbar-brand:hover,.navbar-toggler:focus,.navbar-toggler:hover,a:hover{text-decoration:none}.navbar{display:-ms-flexbox;display:flex;flex-wrap:wrap;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between}.navbar-brand,label{display:inline-block}.navbar>.container-fluid{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between}.navbar-brand{padding-top:.3125rem;padding-bottom:.3125rem;margin-right:1rem;font-size:1.25rem;line-height:inherit}.card,.navbar-nav{display:-ms-flexbox}.navbar-nav{display:flex;-ms-flex-direction:column;flex-direction:column;padding-left:0}.navbar-nav .nav-link{padding-right:0;padding-left:0}.navbar-collapse{-ms-flex-preferred-size:100%;flex-basis:100%;-ms-flex-positive:1;flex-grow:1;-ms-flex-align:center;align-items:center}.navbar-toggler{padding:.25rem .75rem;font-size:1.25rem;line-height:1;border:1px solid transparent;border-radius:.25rem}@media (max-width:767.98px){.navbar-expand-md>.container-fluid{padding-right:0;padding-left:0}}.navbar-light .navbar-nav .nav-link:focus,.navbar-light .navbar-nav .nav-link:hover{color:rgba(0,0,0,.7)}.navbar-light .navbar-toggler{color:rgba(0,0,0,.5);border-color:rgba(0,0,0,.1)}.card{display:flex;-ms-flex-direction:column;flex-direction:column;min-width:0;word-wrap:break-word;background-color:#fff;background-clip:border-box;border:1px solid rgba(0,0,0,.125);border-radius:.25rem}.close{float:right;font-size:1.5rem;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;opacity:.5}.close:not(:disabled):not(.disabled):focus,.close:not(:disabled):not(.disabled):hover{color:#000;text-decoration:none;opacity:.75}button.close{padding:0;border:0;-webkit-appearance:none}.modal-open .modal{overflow-x:hidden;overflow-y:auto}.modal.show .modal-dialog{-webkit-transform:translate(0,0);transform:translate(0,0)}.modal-dialog-centered{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;min-height:calc(100% - (.5rem * 2))}.modal-dialog-centered::before{display:block;height:calc(100vh - (.5rem * 2));content:""}.modal-backdrop{position:fixed;top:0;background-color:#000}.modal-backdrop.show{opacity:.5}.modal-scrollbar-measure{position:absolute;top:-9999px;width:50px;height:50px;overflow:scroll}.modal,.sr-only{overflow:hidden}@media (min-width:576px){.col-sm-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-sm-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}.col-sm-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-sm-10{-ms-flex:0 0 83.333333%;flex:0 0 83.333333%;max-width:83.333333%}.col-sm-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}.modal-dialog{max-width:500px;margin:1.75rem auto}.modal-dialog-centered{min-height:calc(100% - (1.75rem * 2))}.modal-dialog-centered::before{height:calc(100vh - (1.75rem * 2))}.modal-sm{max-width:300px}}.modal{position:fixed;top:0;display:none;outline:0}.modal-body,.modal-content,.modal-dialog,sup{position:relative}.modal-dialog{width:auto;margin:.5rem}.modal.fade .modal-dialog{transition:transform .3s ease-out;transition:transform .3s ease-out,-webkit-transform .3s ease-out;-webkit-transform:translate(0,-25%);transform:translate(0,-25%)}@media screen and (prefers-reduced-motion:reduce){.btn,.fade,.form-control,.modal.fade .modal-dialog{transition:none}}.modal-content{display:-ms-flexbox;display:flex;-ms-flex-direction:column;flex-direction:column;width:100%;pointer-events:auto;background-color:#fff;background-clip:padding-box;border:1px solid rgba(0,0,0,.2);border-radius:.3rem;outline:0}#userLogin{padding-top:77px}#quickLogin{padding-top:140px}.modal-header{display:-ms-flexbox;display:flex;-ms-flex-align:start;align-items:flex-start;-ms-flex-pack:justify;justify-content:space-between}.modal-header .close{padding:1rem;margin:-1rem -1rem -1rem auto}.modal-title{margin-bottom:0;line-height:1.5}.modal-body{-ms-flex:1 1 auto;flex:1 1 auto;padding:1rem}.icon-append,.input-icon i,.sr-only{position:absolute}.modal-footer{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;-ms-flex-pack:end;justify-content:flex-end;padding:1rem;border-top:1px solid #e9ecef}.modal-footer>:not(:first-child){margin-left:.25rem}.modal-footer>:not(:last-child){margin-right:.25rem}.bg-light{background-color:#f8f9fa!important}.rounded{border-radius:.25rem!important}.sr-only{width:1px;height:1px;padding:0;clip:rect(0,0,0,0);border:0}.m-0{margin:0!important}.mt-2{margin-top:.5rem!important}.ml-auto{margin-left:auto!important}.iconbox-wrap-icon .icon,.search-row{margin-left:auto;margin-right:auto}.text-center{text-align:center!important}@media print{*,::after,::before{text-shadow:none!important;box-shadow:none!important}a:not(.btn){text-decoration:underline}img{page-break-inside:avoid}h2,h3,p{orphans:3;widows:3}h2,h3{page-break-after:avoid}@page{size:a3}.container,body{min-width:992px!important}.navbar{display:none}}a,a:active,a:focus,a:hover{text-decoration:none;outline:0}html{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}strong{font-weight:bolder}sup{font-size:75%;line-height:0;vertical-align:baseline;top:-.5em}button,input,select,textarea{font-size:100%;margin:0}button,input{overflow:visible}.footer-nav li a,.iconbox-wrap-text,.intro-title,.logo,.logo-title,button,button.btn-search,select{text-transform:none}[type=submit],button,html [type=button]{-webkit-appearance:button}[type=button]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner{border-style:none;padding:0}[type=button]:-moz-focusring,[type=submit]:-moz-focusring,button:-moz-focusring{outline:ButtonText dotted 1px}[type=checkbox]{-moz-box-sizing:border-box;box-sizing:border-box;padding:0}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}.iconbox-wrap-text,.intro-title,.logo,.logo-title,.wide-intro .intro-title{font-family:Roboto,sans-serif}body{margin:0;font-weight:400;line-height:1.5}[tabindex="-1"]:focus{outline:0!important}p,ul{margin-top:0}a,button,input,label,select,textarea{touch-action:manipulation}label{margin-bottom:.5rem;font-weight:600}.btn,.header-search .search-row .col-xs-12 button strong,h1,h2,h3,h4,h5{font-weight:400}button:focus{outline:dotted 1px;outline:-webkit-focus-ring-color auto 5px}button,input,select,textarea{line-height:inherit}fieldset{min-width:0;border:0}.form-control{display:block;width:100%;height:48px;padding:.5rem .75rem;font-size:1rem;line-height:1.25;color:#464a4c;background-color:#fff;background-image:none;background-clip:padding-box;border-radius:.2rem;-webkit-transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;-moz-transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;-o-transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out}.hw100,body,html{height:100%}.form-control::-ms-expand{background-color:transparent;border:0}.form-control:focus{color:#464a4c;background-color:#fff;box-shadow:1px 0 #16a085,0 -1px 0 #16a085,-1px 0 0 #16a085,1px 0 0 #16a085}.form-control::-webkit-input-placeholder{color:#636c72;opacity:1}.form-control:-moz-placeholder{color:#636c72;opacity:1}.form-control:disabled{background-color:#eceeef;opacity:1;cursor:not-allowed}html{-moz-box-sizing:border-box;box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;font-size:100%;-webkit-tap-highlight-color:transparent}*,::after,::before{-moz-box-sizing:inherit;box-sizing:inherit}a{-webkit-transition:.2s linear,letter-spacing linear;-moz-transition:.2s linear,letter-spacing linear;-o-transition:.2s linear,letter-spacing linear;transition:.2s linear,letter-spacing linear;color:#4682b4}a:active,a:focus,a:hover{color:#0d5d4d}ul{padding:0;margin:0}*,ul li{margin:0;padding:0}p{padding:0}fieldset{border:none;margin:0;padding:0}.hw100{width:100%}.dtable{display:table}.dtable-cell{display:table-cell}@-webkit-keyframes fadeIn{0%,from{opacity:0}to{opacity:1}}@-moz-keyframes fadeIn{from{opacity:0}to{opacity:1}}@-o-keyframes fadeIn{from{opacity:0}to{opacity:1}}@keyframes fadeIn{0%,from{opacity:0}to{opacity:1}}.btn{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;display:inline-block;line-height:1.5;text-align:center;-webkit-user-select:none;-moz-user-select:none;user-select:none;border:1px solid transparent;padding:8px 12px;font-size:1rem;border-radius:.2rem;-webkit-transition:.2s ease-in-out;-moz-transition:.2s ease-in-out;-o-transition:.2s ease-in-out;transition:.2s ease-in-out}.btn:focus{box-shadow:0 0 0 2px rgba(22,160,133,.25)}.btn:disabled{opacity:.65}.btn:active{background-image:none}.btn-primary:focus{box-shadow:0 0 0 2px rgba(22,160,133,.5)}.btn-primary:disabled{background-color:#16a085;border-color:#16a085}.btn-primary:active,.btn-primary:active:focus{color:#fff;background-color:#107360;background-image:none;border-color:#0f6a58}.btn-success{color:#fff;box-shadow:1px 1px 20px 0 #65faa4}.btn-success:focus{box-shadow:0 0 0 2px rgba(46,204,113,.5)}.btn-success:disabled{background-color:#2ecc71;border-color:#2ecc71}.btn-success:active,.btn-success:active:focus{color:#fff;background-color:#25a25a;background-image:none;border-color:#239a55}.btn-block{display:block;width:100%}h1{font-size:30px}h4{font-size:17px}h5{font-size:14px}@media (max-width:979px){h3,h5{line-height:20px}h1{font-size:24px;line-height:26px}h2{font-size:20px;line-height:24px}h3{font-size:18px}h4{font-size:16px;line-height:18px}h5{font-size:14px}}.logo,.logo-title{font-size:28px;font-weight:700;text-transform:uppercase}@media (max-width:991px){.logo,.logo-title{font-size:20px}.hidden-sm{display:none!important}}.intro-title{font-size:2.5rem;line-height:normal;font-weight:700;letter-spacing:-.3px;margin-bottom:10px;padding-bottom:0;text-shadow:1px 1px 0 rgba(0,0,0,.1);text-transform:capitalize;-webkit-transition:font 350ms cubic-bezier(.25, .1, .25, 1);-moz-transition:font 350ms cubic-bezier(.25, .1, .25, 1);-o-transition:font 350ms cubic-bezier(.25, .1, .25, 1);transition:font 350ms cubic-bezier(.25, .1, .25, 1)}.search-row .search-col .form-control,.search-row button.btn-search{border-radius:0;border:0;margin-bottom:0}@media (min-width:992px){.navbar-expand-lg{-ms-flex-flow:row nowrap;flex-flow:row nowrap;-ms-flex-pack:start;justify-content:flex-start}.navbar-expand-lg .navbar-nav{-ms-flex-direction:row;flex-direction:row}.navbar-expand-lg .navbar-nav .dropdown-menu{position:absolute}.navbar-expand-lg .navbar-nav .nav-link{padding-right:.5rem;padding-left:.5rem}.navbar-expand-lg>.container,.navbar-expand-lg>.container-fluid{-ms-flex-wrap:nowrap;flex-wrap:nowrap}.navbar-expand-lg .navbar-collapse{display:-ms-flexbox!important;display:flex!important;-ms-flex-preferred-size:auto;flex-basis:auto}.navbar-expand-lg .navbar-toggler{display:none}.col-lg-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.modal-lg{max-width:800px}.logo-title{padding-top:20px}}.navbar-site.navbar{-webkit-transition:.25s ease-out;-moz-transition:.25s ease-out;-o-transition:.25s ease-out;transition:.25s ease-out;margin-top:0;z-index:100}@media (max-width:767px){body{font-size:.75rem}h1,h2{line-height:24px}h3,h4{line-height:18px}h1{font-size:22px}h2{font-size:18px}h3{font-size:16px}h4{font-size:14px}h5{font-size:12px;line-height:16px}.logo-title{padding-top:18px}.navbar-site.navbar{background:#fff;border-radius:0;height:auto;margin-bottom:0!important;padding:0}.navbar-site.navbar .navbar-collapse{padding:15px}.navbar-site.navbar .navbar-identity{display:block;height:80px;padding:0 15px;border-bottom:1px solid #ddd}.navbar-site.navbar .navbar-identity .navbar-toggler{margin-top:18px;padding:0 10px;height:40px}.navbar-site.navbar .nav>.nav-item,.navbar-site.navbar .nav>li{padding:3px 0}.navbar-site.navbar .nav>.nav-item a:not(.btn),.navbar-site.navbar .nav>li a:not(.btn){color:#333}}@media (max-width:479px){.logo,.logo-title{font-size:18px}.logo-title{height:auto}.navbar-site.navbar .navbar-identity{height:75px}.navbar-site.navbar .navbar-identity .navbar-toggler{margin-top:13px}.navbar-site.navbar .navbar-identity .logo-title{padding-top:22px}}@media (min-width:768px){.navbar-expand-md .navbar-nav .dropdown-menu{position:absolute}.navbar-expand-md>.container,.navbar-expand-md>.container-fluid{-ms-flex-wrap:nowrap;flex-wrap:nowrap}.lang-menu .dropdown-menu .flag-icon{margin-right:5px}li .user-menu{padding:0;-webkit-transition:.2s linear;-moz-transition:.2s linear;-o-transition:.2s linear;transition:.2s linear}li .user-menu li a{font-size:13px;letter-spacing:.55px;clear:both;display:block;font-weight:400;line-height:1.42857;padding:6px 24px;text-transform:inherit;white-space:nowrap}li .user-menu li i{margin-right:5px;color:#999;font-size:120%;-webkit-transition:.2s linear;-moz-transition:.2s linear;-o-transition:.2s linear;transition:.2s linear}li .user-menu li:hover i{color:#2a3744}li.show .user-menu{padding:0}.dropdown-menu.user-menu>.active,.dropdown-menu.user-menu>.active>a:focus,li .dropdown-menu.user-menu>.active>a,li.dropdown-item.active,li.dropdown-item:active{background:#eee;font-weight:700;color:#2a3744}.nav>li.active,.nav>li:hover>a:not(.btn),.nav>li>a:not(.btn):focus,.nav>li>a:not(.btn):hover,.navbar-default .navbar-nav>.open>a:not(.btn),.navbar-default .navbar-nav>.open>a:not(.btn):focus,.navbar-default .navbar-nav>.open>a:not(.btn):hover{background:0 0}.dropdown-menu>li{padding:4px 8px}.dropdown-menu>li a{color:#333}.col-md-1{-ms-flex:0 0 8.333333%;flex:0 0 8.333333%;max-width:8.333333%}.col-md-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-md-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-md-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}.col-md-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-md-7{-ms-flex:0 0 58.333333%;flex:0 0 58.333333%;max-width:58.333333%}.col-md-8{-ms-flex:0 0 66.666667%;flex:0 0 66.666667%;max-width:66.666667%}.col-md-9{-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-md-10{-ms-flex:0 0 83.333333%;flex:0 0 83.333333%;max-width:83.333333%}.col-md-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}.navbar-expand-md{-ms-flex-flow:row nowrap;flex-flow:row nowrap;-ms-flex-pack:start;justify-content:flex-start}.navbar-expand-md .navbar-nav{-ms-flex-direction:row;flex-direction:row}.navbar-expand-md .navbar-nav .nav-link{padding-right:.5rem;padding-left:.5rem}.navbar-expand-md .navbar-collapse{display:-ms-flexbox!important;display:flex!important;-ms-flex-preferred-size:auto;flex-basis:auto}.navbar-expand-md .navbar-toggler{display:none}.logo{height:80px;line-height:normal}.header,.navbar-site{height:auto;background:#fff}.navbar-site{margin-bottom:0!important;padding-top:0;padding-bottom:0;border-radius:0;border-bottom:1px solid #ddd}.navbar.navbar-light .navbar-nav>li,.navbar.navbar-site .navbar-nav>li{margin-top:10px;margin-bottom:10px}.navbar.navbar-light .navbar-nav>li .nav-link:not(.btn),.navbar.navbar-light .navbar-nav>li>a:not(.btn),.navbar.navbar-site .navbar-nav>li .nav-link:not(.btn),.navbar.navbar-site .navbar-nav>li>a:not(.btn){border-radius:3px;-moz-box-sizing:border-box;box-sizing:border-box;color:#333;font-size:12px;height:40px;line-height:1;padding:12px 10px}.navbar.navbar-light .navbar-nav>li .nav-link:not(.btn):hover,.navbar.navbar-light .navbar-nav>li>a:not(.btn):hover,.navbar.navbar-site .navbar-nav>li .nav-link:not(.btn):hover,.navbar.navbar-site .navbar-nav>li>a:not(.btn):hover{color:#000}}.footer-nav-inline li a{color:#333;font-size:16px}.footer-nav-inline.social-list-color li a{color:#fff;font-size:14px;width:28px;display:inline-block;text-align:center;padding:3px 0}.icon-color.fb{background:#3b5998}.icon-color.tw{background:#55acee}.icon-color.gp{background:#dd4b39}.icon-color.lin{background:#007bb5}.icon-color.pin{background:#cb2027}.footer-nav li a{border-left:1px solid #ddd;padding:0;color:#333;font-size:12px}.footer-nav li a:first-child{border-left:0}.search-row .search-col{padding:0;position:relative}.search-row .search-col .form-control{box-shadow:none!important}@media (min-width:576px){.modal-dialog{max-width:500px;margin:1.75rem auto}.modal-sm{max-width:300px}.search-row .search-col .form-control{border-radius:0!important}.search-row .search-col:first-child .form-control{border-radius:3px 0 0 3px!important}}.has-icon{padding-left:40px}.icon-append{color:#949494;font-size:18px;left:8px;top:11px}.main-container{padding:30px 0}.page-info{width:100%;min-height:94px;color:#fff}.page-info i{color:#fff;font-size:40px;display:block}.iconbox-wrap{border-collapse:separate;border-spacing:0;display:table;height:auto;min-height:40px;padding:15px;position:relative;text-align:center;width:100%}.iconbox{display:table-row}.iconbox-wrap-content,.iconbox-wrap-icon{display:table-cell;vertical-align:middle}.iconbox h5{font-size:36px;font-weight:700;line-height:40px;padding-bottom:0}.iconbox-wrap-icon{text-align:center;border-right:1px solid rgba(255,255,255,.2)}.iconbox-wrap-icon .icon{height:60px!important;line-height:60px!important;vertical-align:middle!important;width:60px!important}.input-icon i,.search-row-wrapper .btn-primary{line-height:22px}.iconbox-wrap-content{padding-left:10px;text-align:left}.iconbox-wrap-text{color:#aaa;font-size:16px;text-transform:uppercase}.modal-title{text-align:left;padding-bottom:0}.city-col,.featured-col .card,.fi,.navbar.navbar-light .navbar-nav>li a.nav-link,.service-iconb,.wide-intro h1{text-align:center}.search-row-wrapper{-webkit-transition:.4s cubic-bezier(.25, .1, .25, 1);-moz-transition:.4s cubic-bezier(.25, .1, .25, 1);-o-transition:.4s cubic-bezier(.25, .1, .25, 1)}@media screen and (min-width:1024px){.search-row-wrapper{height:140px;display:block}}.modal-header{border-bottom:1px solid #ddd;border-radius:3px 3px 0 0;font-weight:700;background:#f8f8f8;border-top:1px solid #ddd;padding:8px;position:relative}.f-icon,.fixed-top,.navbar-site{position:fixed;right:0}.input-icon input{padding-left:45px}.input-icon i{font-size:16px;left:6px;top:9px}.fi,.fi::before{line-height:52px}.pull-right{float:right}.main-logo,.pull-left{float:left}.list-inline{padding-left:0;margin-left:-5px}.list-inline>li{display:inline-block;padding-right:5px;padding-left:5px}@media (min-width:577px) and (max-width:980px){.container{max-width:100%}}#wrapper{-webkit-transition:padding .2s;-moz-transition:padding .2s;-o-transition:padding .2s;transition:padding .2s;padding-top:84px;background-color:#fff}@media (max-width:767px){.iconbox-wrap-icon{display:block;float:left;max-width:80px}.iconbox-wrap-content{display:block;float:left;padding-left:10px;text-align:left;vertical-align:middle}.intro-title{font-size:28px;line-height:32px}.search-col .form-control,.search-row .search-col .btn{border-radius:3px!important}.search-row .search-col:first-child .form-control{border-radius:3px}h1{font-size:24px;line-height:25px}h2{font-size:20px;line-height:24px}h3{font-size:18px;line-height:20px}h4{font-size:16px;line-height:18px}.search-row-wrapper .col-md-3{margin-bottom:10px;display:-webkit-inline-flex;display:-moz-inline-box;display:inline-flex;width:100%}.search-row-wrapper .selecter{max-width:100%}#wrapper{padding-top:81px}.navbar-site.navbar .navbar-identity{height:80px;border-bottom:0}.navbar-site.navbar .navbar-identity .navbar-toggler{margin-top:18px}}@media (max-width:480px){.col-xxs-12{width:100%}}@media (max-width:320px){.container,body,html{min-width:300px}.navbar .container{padding-left:5px}}@media (max-width:300px){.container,body,html{width:300px}}.navbar-site{top:0;left:0;z-index:1001}.fixed-top{top:0;left:0;z-index:1030}@media (min-width:576px){.container{max-width:540px}}@media (min-width:768px){.container{max-width:720px}.navbar.navbar-site .navbar-collapse,.navbar.navbar-site .navbar-identity{margin-top:0}}@media (min-width:992px){.container{max-width:960px}}::selection{color:#fff;background:#4682b4}::-moz-selection{color:#fff;background:#4682b4}h1,h2,h3,h4,h5{text-rendering:optimizeLegibility;font-family:Roboto,Helvetica,Arial,sans-serif;margin:0;padding:0;line-height:1.2}.main-header,body{font-family:Arial,"Helvetica Neue",Helvetica,sans-serif}a:focus,a:hover{color:#ff8c00}.btn-primary{background-color:#4682b4;border-color:#4682b4;color:#fff}.btn-primary:active,.btn-primary:focus,.btn-primary:hover{background-color:#628fb5;border-color:#628fb5;color:#fff}.btn-default{color:#292b2c;background-color:#fff;border-color:#ccc}.btn-default:hover{color:#292b2c;background-color:#e6e6e6;border-color:#adadad}.btn-default:focus{box-shadow:0 0 0 2px rgba(204,204,204,.5)}.btn-default:disabled{background-color:#fff;border-color:#ccc}.btn-default:active,.btn-default:active:focus{color:#292b2c;background-color:#e6e6e6;background-image:none;border-color:#adadad}.form-control:focus{border-color:#969696;box-shadow:0 1px 0 #969696,0 -1px 0 #969696,-1px 0 0 #969696,1px 0 0 #969696;outline:0}.logo,.logo-title,.skin-blue .logo,.skin-blue .logo-title{color:#4682b4}.main-logo{width:auto;margin:0 5px 5px 0}.modal-backdrop{z-index:1960!important}@media screen and (min-width:992px){.modal-lg{width:900px}#quickLogin .modal-lg{width:950px}.modal-dialog{width:600px;max-width:950px}}.modal{z-index:1979}.modal-dialog{z-index:2000}button.btn-search{border-radius:0!important;font-size:18px;height:48px;letter-spacing:-.5px;text-transform:uppercase}.navbar.navbar-site{position:fixed!important;z-index:1945;border:0 solid #e7e7e7;border-radius:0;height:auto;margin-bottom:0!important;background-color:#f8f8f8}@media screen and (min-width:768px){.modal-dialog{width:600px;max-width:950px}#quickLogin .modal-dialog{width:700px}#quickLogin .modal-sm{width:450px}.navbar.navbar-site ul.navbar-nav>li>a{padding:12px 10px}}@media screen and (max-width:767px){.btn-block{display:block;width:100%}}@media (max-width:479px){#wrapper{padding-top:81px}.navbar-site.navbar .navbar-identity{height:80px}}@media (min-width:768px) and (max-width:992px){.logo{height:auto}.navbar.navbar-site .navbar-identity a.logo{height:81px}.navbar.navbar-site .navbar-identity a.logo-title{padding-top:20px}}@media (min-width:768px) and (max-width:1200px){.navbar-right i{display:none}}.search-row{margin-top:0;max-width:900px}.search-row .btn-search{border-radius:0!important}.search-row-wrapper{background:#4682b4;height:auto;padding:5px;transition:.4s cubic-bezier(.25, .1, .25, 1);width:100%;margin-top:30px}.search-row-wrapper .container div{padding-left:1px;padding-right:1px}@media (min-width:768px){.search-row-wrapper .container{padding:0;width:100%}}@media (max-width:767px){.search-row-wrapper,.search-row-wrapper .container{padding:0}.search-row-wrapper{padding-top:5px}.search-row-wrapper .container{padding-right:5px;padding-left:5px}.search-row-wrapper .col-xs-12{margin-bottom:5px;display:-webkit-inline-flex;display:-moz-inline-box;display:inline-flex;width:100%}.wide-intro .dtable .dtable-cell .search-row .search-col .form-control{margin-bottom:0}.navbar-brand.logo.logo-title{padding-top:20px}}@media (max-width:575px){.wide-intro .dtable .dtable-cell .search-row .search-col .form-control{margin-bottom:5px}}.page-info{background-color:#3c3c3c;padding:10px}.page-info-lite{padding:5px}.intro-title{color:#dc143c}h2{font-size:24px}h3{font-size:20px}h1.intro-title{font-size:44px;text-transform:none}.main-container{min-height:220px}.form-control{border:1px solid #ddd;box-shadow:1px 1px 20px 0 #e8e8e8}div.rounded{-moz-border-radius:5px;border-radius:5px}.search-row .search-col .form-control,.search-row button.btn-search,.search-row-wrapper .form-control,.search-row-wrapper button.btn{font-size:16px;height:45px}.search-row .icon-append{color:#949494;font-size:28px;position:absolute;top:2px;left:8px}.search-row .has-icon{padding-left:46px}.wide-intro{min-height:50px;height:450px;max-height:450px;padding:5px 0;background:0 0/cover #444}.wide-intro .search-row{margin-top:0;padding:5px;background-color:#333}.wide-intro .intro-title{display:none}.wide-intro p{font-size:18px}.wide-intro h1{text-transform:uppercase;color:#fff;font-weight:700;font-size:40px}#homepage.main-container,.main-container{padding-top:0}.btn:focus{outline:0;box-shadow:0 0 0 2px rgba(155,155,155,.25)}.col-9.service-txt h3 a:hover,.form-group.required sup{color:red}.page-info-lite .iconbox-wrap-text,.skin-blue a,.skin-blue a:focus,.skin-blue a:hover,body{color:#000}.skin-blue .footer-nav li a:focus,.skin-blue .footer-nav li a:hover{color:#333;opacity:.6}.skin-blue .footer-nav-inline.social-list-color li a:focus,.skin-blue .footer-nav-inline.social-list-color li a:hover{color:#fff;opacity:.6}.skin-blue ::selection{color:#fff;background:#4682b4}.skin-blue ::-moz-selection{color:#fff;background:#4682b4}.skin-blue .search-row-wrapper{background:#4682b4}.skin-blue .wide-intro .search-row{background-color:#4682b4}.skin-blue button.btn-search{background-color:#4682b4;border-color:#4682b4}.skin-blue .btn-primary{background-color:#32b5ed;border-color:#32b2ed;color:#fff}.skin-blue .btn-primary:active,.skin-blue .btn-primary:focus,.skin-blue .btn-primary:hover{background-color:#628fb5;border-color:#628fb5}.skin-blue .form-control:focus{border-color:#4682b4;box-shadow:0 1px 0 #4682b4,0 -1px 0 #4682b4,-1px 0 0 #4682b4,1px 0 0 #4682b4;outline:0}.skin-blue .btn:focus,.skin-blue .btn:hover{color:#333}.skin-blue .btn-default,.skin-blue .btn-default:active,.skin-blue .btn-default:focus,.skin-blue .btn-default:hover{color:#292b2c}@font-face{font-family:slick;font-weight:400;font-style:normal;src:url(./fonts/slick.eot);src:url(./fonts/slick.eot?#iefix) format('embedded-opentype'),url(./fonts/slick.woff) format('woff'),url(./fonts/slick.ttf) format('truetype'),url(./fonts/slick.svg#slick) format('svg')}.main-logo{height:60px;margin-bottom:0}.navbar.navbar-site .navbar-identity .navbar-brand{padding-top:5px;padding-bottom:4px;height:auto}.navbar.navbar-light .navbar-nav>li,.navbar.navbar-site .navbar-nav>li{margin-top:0;margin-bottom:0}.main-footer .footer-nav li a{color:#fff;font-size:14px}.footer-nav li{line-height:29px}.footer-nav li a:hover{opacity:.6;color:#ccc!important}.footer-nav.social-list-footer li{line-height:23px}.page-info-lite{background-color:#e6e6e6}.page-info-lite h5{color:#fe5300}.page-info-lite .iconbox-wrap-icon .icon{color:#07b53e}#sidebar .sidebar-modern-inner .block-content. categories-list{padding:15px 5px}.header-search{transition:.3s ease-in-out;display:none}.fi,.fib a{display:inline-block;width:52px;height:52px}.main-header .header-search{transition:.3s ease-in-out;max-width:875px;width:100%;padding-left:5%!important}.header-search .search-row .col-xs-12 input[type=text]{height:38px;padding-top:0;padding-bottom:0;border:1px solid #e3e3e3;font-size:14px;color:#000;border-radius:0;box-shadow:inherit!important}.header-search .search-row .col-xs-12 button.btn-search.btn-block{padding-top:0;padding-bottom:0;height:38px;background:#039eb5;border-radius:0 4px 4px 0!important;max-width:72px}.main-header .search-row-wrapper.header-search{display:block;margin:0}.header.main-header .container{width:auto}.search-row-wrapper.header-search{background:0 0;padding:0}.search-row-wrapper.header-search .container div{padding-left:0;padding-right:0}.pk-login .modal-header{background:#039eb5;border-color:#039eb5;color:#fff}#userOTP .modal-dialog,.pk-login .modal-dialog{width:auto;max-width:366px}#userOTP .btn.btn-default,.pk-login .btn.btn-default{background:#dc0002;border-color:#dc0002;height:39px;padding-top:0;padding-bottom:0;line-height:normal;color:#fff}#userOTP .btn.btn-success.pull-right,.pk-login .btn.btn-success.pull-right{background:#039eb5;border-color:#039eb5;height:39px;padding-top:0;padding-bottom:0;line-height:normal;color:#fff}#userOTP .input-group-text,.pk-login .input-group-text{color:#fff;background-color:#039eb5;border:1px solid #039eb5}#userOTP .close,.pk-login .close{color:#fff!important;opacity:1}.wide-intro .search-row{max-width:640px}#userOTP .modal-header{background:#dc0002;border-color:#dc0002;color:#fff}.main-header .navbar.navbar-site{z-index:9999}.main-container .container{max-width:90%!important}.f-icon{top:320px;z-index:111}.fi{color:#fff;font-size:22px}.fib{display:flex;align-items:center}.f-c{background:#dc0002}.f-w{background:#16b853}.fib a{position:relative}.fib a.ic::before{content:"Call us"}.fib a.iw::before{content:"WhatsApp"}.fib a::before{position:absolute;display:none;background:#000;border-radius:4px;color:#fff;line-height:17px;padding:4px 15px;top:9px;height:25px;margin-left:4px;right:52px}@media(min-width:320px) and (max-width:800px){.navbar-light .navbar-toggler{position:absolute!important;top:24px!important;right:20px!important}}@media(min-width:320px) and (max-width:767px){.navbar-site.navbar .navbar-identity .navbar-toggler{margin-top:-15px!important;color:#fff!important;padding-left:0;padding-right:0;height:auto;border:none}html body #wrapper{padding-top:0!important}.navbar-light .navbar-toggler{position:absolute!important;top:24px!important;right:20px!important}#homepage .help-block .help-form,#homepage .help-block .listing-counter{flex:0 0 100%;max-width:100%}body #wrapper{padding-top:109px!important}.main-logo{height:31px}.page-info.page-info-lite.rounded{display:none}.listing-grid{padding-top:0}.navbar-site.navbar .navbar-identity{width:100%}.main-header .navbar-site.navbar .navbar-identity{height:46px!important}.main-header .btn.btn-primary.btn-search.btn-block{position:absolute;bottom:6px;right:0;max-width:72px}#userOTP .modal-dialog,.pk-login .modal-dialog{width:auto;max-width:298px;margin:1.75rem auto}}@media(min-width:768px) and (max-width:916px){.main-logo,.navbar.navbar-site .navbar-identity a.logo{height:auto!important}.main-logo{width:132px!important}.navbar.navbar-site ul.navbar-nav>li>a{padding:0 10px!important;height:auto!important}}@media(min-width:768px) and (max-width:900px){.help-form h3,.listing-counter h2{font-size:24px!important}.page-info.page-info-lite .iconbox-wrap{padding:5px!important}.iconbox h5{line-height:normal!important}#homepage .wide-intro{height:170px!important;max-height:170px!important}.wide-intro h1{font-size:21px!important}.help-block .listing-counter{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%;margin-bottom:20px}.help-block .help-form{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}}@media(min-width:901px) and (max-width:1023px){.help-form h3,.listing-counter h2{font-size:24px!important}.page-info.page-info-lite .iconbox-wrap{padding:5px!important}.iconbox h5{font-size:20px;line-height:normal!important}#homepage .wide-intro{height:232px!important;max-height:232px!important}.wide-intro h1{font-size:26px!important}}@media(min-width:1024px) and (max-width:1199px){.listing-counter h2{font-size:29px!important}.page-info.page-info-lite .iconbox-wrap{padding:5px!important}.iconbox h5{font-size:21px!important;line-height:normal!important}#homepage .wide-intro{height:232px!important;max-height:232px!important}.wide-intro h1{font-size:26px!important}}@media (min-width:1200px){.navbar-expand-xl{-ms-flex-flow:row nowrap;flex-flow:row nowrap;-ms-flex-pack:start;justify-content:flex-start}.navbar-expand-xl .navbar-nav{-ms-flex-direction:row;flex-direction:row}.navbar-expand-xl .navbar-nav .dropdown-menu{position:absolute}.navbar-expand-xl .navbar-nav .nav-link{padding-right:.5rem;padding-left:.5rem}.navbar-expand-xl>.container,.navbar-expand-xl>.container-fluid{-ms-flex-wrap:nowrap;flex-wrap:nowrap}.navbar-expand-xl .navbar-collapse{display:-ms-flexbox!important;display:flex!important;-ms-flex-preferred-size:auto;flex-basis:auto}.navbar-expand-xl .navbar-toggler{display:none}.col-xl-1{-ms-flex:0 0 8.333333%;flex:0 0 8.333333%;max-width:8.333333%}.col-xl-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-xl-7{-ms-flex:0 0 58.333333%;flex:0 0 58.333333%;max-width:58.333333%}.container{max-width:90%!important}}body{background:#fff;font-size:15px;scroll-behavior:smooth}img{vertical-align:middle;border:0;outline:0}.main-header{background:#41474e;transition:transform .2s ease-in-out;z-index:8;height:auto;position:sticky;width:100%;top:0;padding:0!important;font-size:12px;display:block!important}.section-bg{background:#fff;padding-top:15px;padding-bottom:15px;margin-top:3px;width:100%;display:inline-block}.category-thumbnail{width:100px;flex-shrink:0}.category-item{border-radius:3px;color:#333;display:flex;padding:12px 10px;border:1px solid #ebebeb;height:100%}.section-block{width:100%;display:inline-block;margin-bottom:15px}.requirement-form-wrap{margin-top:25px}.category-meta{padding-left:15px;width:100%}.category-item li{color:#666;font-size:14px;margin-bottom:6px}.category-item li a{color:#444;font-size:14px}.category-item li a:hover{text-decoration:underline}.category-item h3{font-size:16px;font-weight:700;margin-bottom:12px;padding-bottom:0}.category-item h3 a{color:#333;font-size:14px;margin-bottom:10px}.listing-grid .col-md-3{margin-top:15px}.listing-grid .col-md-4{padding-left:0;margin-top:15px}.category-item img{max-width:100px;max-height:100px;position:absolute;top:50%;left:15px;transform:translate(0,-50%);object-fit:contain}.listing-grid .section-bg{border-top:3px solid #091840}.listing-grid h2 a{color:#222}.requirement-form-inner{max-width:578px}.requirement-form-inner .form-control{margin-bottom:10px}.requirement-form-inner input.btn{background:#ed3237;color:#fff}.requirement-form-inner h2{font-size:26px;margin-bottom:10px}.city-col{-ms-flex-preferred-size:0;flex-basis:0;-webkit-box-flex:1;-ms-flex-positive:1;flex-grow:1;max-width:100%;margin:30px 0 15px;flex:0 0 16.5%}.featured-col h3,.footer-menu{margin-bottom:12px}.top-cities-home img{height:75px;width:auto}.top-cities-home h3{font-size:18px;margin-top:11px}.featured-pro-wrap .featured-col{flex:0 0 16.5%;margin-bottom:20px}.featured-col .card{padding:10px 10px 20px;background:0 0}.featured-col h3{font-size:15px;font-weight:700;color:#333;margin-top:11px;height:75px}.featured-pro-wrap h2,.service-wrap .footer-col{margin-bottom:20px}.banner-text-list li{display:inline-block;max-width:83px;text-align:center}.banner-text-list li i{font-size:27px;color:#ed3237;margin-bottom:10px}.banner-text h2{margin-bottom:14px;font-size:2rem}.banner-text-list{padding-top:10px}.banner-text{vertical-align:middle;align-content:center;align-items:center;display:grid}.cookie-consent__message,.footer-menu li,.footer-menu li a,.footer-services-sec,.main-footer,a.v-all-btn{display:inline-block}.main-footer{background:#ccc;padding:30px 0 0;width:100%}.service-txt{color:#333;font-size:12px}.footer-copy{background:#cecece}.service-txt h3{font-size:18px;color:#222;font-weight:700;margin-bottom:10px;margin-top:0}.footer-col ul li{margin-bottom:8px}.footer-col ul li a{color:#8a97bd}.copy-inner{padding:22px 0 13px;margin-top:20px}.copy-inner p{margin:0;color:#3b3b3b;text-align:left}.cookie-consent,a.v-all-btn{text-align:center;right:0;left:0}a.v-all-btn{background:#ed3237;position:absolute;bottom:20px;padding:11px 24px;color:#fff !important;margin:auto;max-width:131px;border-radius:5px}.cate-left-img-inner::before{content:"";background-image:linear-gradient(to bottom,rgba(255,0,0,0),#000);opacity:.6;position:absolute;width:100%;left:0;right:0;height:100%;top:0}.cate-left-img-inner{position:relative;height:100%}.footer-services-sec h5{margin-bottom:18px;padding-left:13px;font-size:18px}.footer-services-sec{width:100%;margin-top:24px}.footer-menu li a{color:#333;font-size:14px;font-weight:300}.footer-menu li:first-child{padding-left:0}.footer-menu li{padding:5px 19px 10px}#homepage.main-container{background-color:#f3f3f3;width:100%;display:inline-block}body #wrapper{padding-top:73.2px!important}.header.main-header .navbar.navbar-site{background:#00b5b7!important;border:none!important}.cate-left-img-inner img{height:100%}.navbar.navbar-light .navbar-nav>li .nav-link:not(.btn),.navbar.navbar-light .navbar-nav>li>a:not(.btn),.navbar.navbar-site .navbar-nav>li .nav-link:not(.btn),.navbar.navbar-site .navbar-nav>li>a:not(.btn){border-radius:3px;-moz-box-sizing:border-box;box-sizing:border-box;color:#fff!important;font-size:12px;height:40px;line-height:1;padding:12px 10px}.col-9.service-txt h3 a{color:#333;font-size:13px}@media (min-width:320px) and (max-width:767px){.featured-pro-wrap .featured-col{flex:inherit}.city-col{-ms-flex-preferred-size:inherit;flex-basis:inherit;-webkit-box-flex:inherit;-ms-flex-positive:inherit;flex-grow:inherit;max-width:100%;margin:30px 0 15px;text-align:center;width:100%}}.modal-content{border:none}.btn-success{background-color:#f2436d;border-color:#f2436d;box-shadow:1px 6px 20px #f2436d78;margin:14px 0;font-size:13px;padding:15px}.btn-success:hover,.btn-success:not(:disabled):not(.disabled).active,.btn-success:not(:disabled):not(.disabled):active{color:#fff;background-color:#4d5e6d;border-color:#4d5e6d}.h-spacer{padding-top: 0px!important;margin-top:-21px;}.skin-blue a:focus,.skin-blue a:hover{color:#ed1111}.cookie-consent{font-size:14px;padding:16px;background:#f0f2f1;position:fixed;width:100%;bottom:0;z-index:1000}.cookie-consent__agree,.skin-blue .cookie-consent__agree{background-color:#4682b4;box-shadow:0 2px 5px rgb(70 130 180 / 15%)}.cookie-consent__message{color:#555}.skin-blue .cookie-consent__agree:hover{background-color:#345676}.cookie-consent__agree{font-weight:700;margin:0 16px;padding:8px 16px;color:#fff2e0;border:0;border-radius:3px}@media(max-width:768px){.requirement-form-wrap{margin-top:160px}}.bottom-links li a::after{padding-left:6px;padding-right:3px;font-size:15px;content: '/';}.bottom-links li a{font-size: 13px;}
    </style>
        @elseif(Request::is('category/*') || (request()->city!='' && request()->catSlug!=''))
        <!-- <link href="{{ url('css/category.min.css') . getPictureVersion() }}" rel="stylesheet" media="screen and (max-width: 1800px)" > -->
        <style type="text/css">
        	@charset "utf-8";.fa,.fab{-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:inline-block;font-style:normal;font-variant:normal;text-rendering:auto;line-height:1}.fa-angle-left:before{content:"\F104"}.fa-camera:before{content:"\F030"}.fa-envelope:before{content:"\F0E0"}.fa-facebook:before{content:"\F09A"}.fa-file:before{content:"\F15B"}.fa-folder:before{content:"\F07B"}.fa-heart:before{content:"\F004"}.fa-home:before{content:"\F015"}.fa-hourglass-half:before{content:"\F252"}.fa-linkedin:before{content:"\F08C"}.fa-phone:before{content:"\F095"}.fa-pinterest-p:before{content:"\F231"}.fa-quora:before{content:"\F2C4"}.fa-reddit:before{content:"\F1A1"}.fa-search:before{content:"\F002"}.fa-th-large:before{content:"\F009"}.fa-twitter:before{content:"\F099"}.fa-user:before{content:"\F007"}.fa-whatsapp:before{content:"\F232"}.fa-youtube:before{content:"\F167"}.sr-only{border:0;clip:rect(0,0,0,0);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;width:1px}@font-face{font-family:Font Awesome\ 5 Brands;font-style:normal;font-weight:400;font-display: swap;src:url(../assets/plugins/fontawesome/webfonts/fa-brands-400.eot);src:url(../assets/plugins/fontawesome/webfonts/fa-brands-400.eot?#iefix) format("embedded-opentype"),url(../assets/plugins/fontawesome/webfonts/fa-brands-400.ttf) format("truetype"),url(../assets/plugins/fontawesome/webfonts/fa-brands-400.svg#fontawesome) format("svg")}.fab{font-family:Font Awesome\ 5 Brands}@font-face{font-family:Font Awesome\ 5 Free;font-style:normal;font-weight:900;font-display: swap;src:url(../assets/plugins/fontawesome/webfonts/fa-solid-900.eot);src:url(../assets/plugins/fontawesome/webfonts/fa-solid-900.eot?#iefix) format("embedded-opentype"),url(../assets/plugins/fontawesome/webfonts/fa-solid-900.woff2) format("woff2"),url(../assets/plugins/fontawesome/webfonts/fa-solid-900.woff) format("woff"),url(../assets/plugins/fontawesome/webfonts/fa-solid-900.ttf) format("truetype"),url(../assets/plugins/fontawesome/webfonts/fa-solid-900.svg#fontawesome) format("svg")}.fa{font-family:Font Awesome\ 5 Free}.fa{font-weight:900}[class^=icon-]:before{font-family:fontello;font-style:normal;font-weight:400;speak:none;display:inline-block;text-decoration:inherit;width:1em;margin-right:.2em;text-align:center;font-variant:normal;text-transform:none;line-height:1em;margin-left:.2em}.icon-user:before{content:"\E806"}.icon-home:before{content:"\E815"}.icon-help:before{content:"\E818"}.icon-map:before{content:"\E832"}.icon-down-open-big:before{content:"\E83A"}.icon-phone-1:before{content:"\E8C8"}.icon-instagram-filled:before{content:"\E8F8"}.nice-select .list:hover .option:not(:hover){background-color:transparent!important}.selecter{display:block}.selecter{position:relative;z-index:1}.selecter:focus{box-shadow:none;outline:0}.selecter,.selecter *{-webkit-user-select:none!important;-moz-user-select:none!important;-ms-user-select:none!important;user-select:none!important}.selecter,.selecter *,.selecter :after,.selecter :before{box-sizing:border-box}@media screen and (max-width:740px){.selecter{max-width:40%}}@media screen and (max-width:500px){.selecter{max-width:100%}}.row{}#msform{text-align:center;position:relative;margin-top:30px}#msform fieldset{background:#fff;border:0;border-radius:0;box-shadow:0 0 15px 1px rgba(0,0,0,.4);padding:20px 30px;box-sizing:border-box;width:80%;margin:0 10%;position:relative}#msform fieldset:not(:first-of-type){display:none}#msform input,#msform select,#msform textarea{border:1px solid #ccc;border-radius:0;margin-bottom:10px;width:100%;box-sizing:border-box;font-family:montserrat;color:#2c3e50;font-size:13px}#msform input:focus,#msform select:focus,#msform textarea:focus{box-shadow:none!important;border:1px solid #ee0979;outline-width:0;transition:All .5s ease-in;-webkit-transition:All .5s ease-in;-moz-transition:All .5s ease-in;-o-transition:All .5s ease-in}#msform .action-button{width:100px;background:#ee0979;font-weight:700;color:#fff;border:0;border-radius:25px;cursor:pointer;padding:10px 5px;margin:10px 5px}#msform .action-button:focus,#msform .action-button:hover{box-shadow:0 0 0 2px #fff,0 0 0 3px #ee0979}#msform .action-button-previous{width:100px;background:#c5c5f1;font-weight:700;color:#fff;border:0;border-radius:25px;cursor:pointer;padding:10px 5px;margin:10px 5px}#msform .action-button-previous:focus,#msform .action-button-previous:hover{box-shadow:0 0 0 2px #fff,0 0 0 3px #c5c5f1}:root{--blue: #007bff;--indigo: #6610f2;--purple: #6f42c1;--pink: #e83e8c;--red: #dc3545;--orange: #fd7e14;--yellow: #ffc107;--green: #28a745;--teal: #20c997;--cyan: #17a2b8;--white: #fff;--gray: #6c757d;--gray-dark: #343a40;--primary: #007bff;--secondary: #6c757d;--success: #28a745;--info: #17a2b8;--warning: #ffc107;--danger: #dc3545;--light: #f8f9fa;--dark: #343a40;--breakpoint-xs: 0;--breakpoint-sm: 576px;--breakpoint-md: 768px;--breakpoint-lg: 992px;--breakpoint-xl: 1200px;--font-family: 'Roboto', sans-serif}*,::after,::before{box-sizing:border-box}html{font-family:sans-serif;line-height:1.15;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;-ms-overflow-style:scrollbar;-webkit-tap-highlight-color:transparent}@-ms-viewport{width:device-width}aside,footer,nav,section{display:block}body{margin:0;font-family:roboto,sans-serif;font-size:1rem;font-weight:400;line-height:1.5;color:#212529;text-align:left;background-color:#fff}[tabindex="-1"]:focus{outline:0!important}hr{box-sizing:content-box;height:0;overflow:visible}h1,h2,h3,h4,h5{margin-top:0;margin-bottom:.5rem}p{margin-top:0;margin-bottom:1rem}ol,ul{margin-top:0;margin-bottom:1rem}ul ul{margin-bottom:0}strong{font-weight:bolder}a{color:#007bff;text-decoration:none;background-color:transparent;-webkit-text-decoration-skip:objects}a:hover{color:#0056b3;text-decoration:underline}img{vertical-align:middle;border-style:none}svg{overflow:hidden;vertical-align:middle}label{display:inline-block;margin-bottom:.5rem}button{border-radius:0}button:focus{outline:1px dotted;outline:5px auto -webkit-focus-ring-color}button,input,select,textarea{margin:0;font-family:inherit;font-size:inherit;line-height:inherit}button,input{overflow:visible}button,select{text-transform:none}[type=submit],button,html [type=button]{-webkit-appearance:button}[type=button]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner{padding:0;border-style:none}input[type=radio]{box-sizing:border-box;padding:0}textarea{overflow:auto;resize:vertical}fieldset{min-width:0;padding:0;margin:0;border:0}::-webkit-file-upload-button{font:inherit;-webkit-appearance:button}h1,h2,h3,h4,h5{margin-bottom:.5rem;font-family:inherit;font-weight:500;line-height:1.2;color:inherit}h1{font-size:2.5rem}h2{font-size:2rem}h3{font-size:1.75rem}h4{font-size:1.5rem}h5{font-size:1.25rem}hr{margin-top:1rem;margin-bottom:1rem;border:0;border-top:1px solid rgba(0,0,0,.1)}.list-unstyled{padding-left:0;list-style:none}.list-inline{padding-left:0;list-style:none}.img-thumbnail{background-color:#fff;border-radius:.25rem;max-width:100%;height:auto;box-shadow:0 1px 20px 0 #d2d2d2}.container{width:100%;padding-right:0;padding-left:0;margin-right:auto;margin-left:auto}@media(min-width:576px){.container{max-width:540px}}@media(min-width:768px){.container{max-width:720px}}@media(min-width:992px){.container{max-width:960px}}@media(min-width:1200px){.container{max-width:1140px}}.container-fluid{max-width:1460px;width:100%;padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}.row{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}.col-3,.col-9,.col-lg-2,.col-lg-3,.col-lg-4,.col-md-1,.col-md-10,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-6,.col-md-7,.col-md-9,.col-sm-12,.col-sm-3,.col-sm-4,.col-sm-6,.col-xl-1,.col-xl-12,.col-xl-2,.col-xl-7{position:relative;width:100%;min-height:1px;padding-right:15px;padding-left:15px}.col-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-9{-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}@media(min-width:576px){.col-sm-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-sm-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}.col-sm-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-sm-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}}@media(min-width:768px){.col-md-1{-ms-flex:0 0 8.333333%;flex:0 0 8.333333%;max-width:8.333333%}.col-md-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-md-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-md-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}.col-md-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-md-7{-ms-flex:0 0 58.333333%;flex:0 0 58.333333%;max-width:58.333333%}.col-md-9{-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-md-10{-ms-flex:0 0 83.333333%;flex:0 0 83.333333%;max-width:83.333333%}.col-md-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}}@media(min-width:992px){.col-lg-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-lg-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-lg-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}}@media(min-width:1200px){.col-xl-1{-ms-flex:0 0 8.333333%;flex:0 0 8.333333%;max-width:8.333333%}.col-xl-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-xl-7{-ms-flex:0 0 58.333333%;flex:0 0 58.333333%;max-width:58.333333%}.col-xl-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}}.form-control{display:block;width:100%;height:calc(2.25rem + 2px);padding:.375rem .75rem;font-size:1rem;line-height:1.5;color:#495057;background-color:#fff;background-clip:padding-box;border:1px solid #ced4da;border-radius:.25rem;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out}@media screen and (prefers-reduced-motion:reduce){.form-control{transition:none}}.form-control::-ms-expand{background-color:transparent;border:0}.form-control:focus{color:#495057;background-color:#fff;border-color:#80bdff;outline:0;box-shadow:0 0 0 .2rem rgba(0,123,255,.25)}.form-control::-webkit-input-placeholder{color:#6c757d;opacity:1}.form-control::-moz-placeholder{color:#6c757d;opacity:1}.form-control:-ms-input-placeholder{color:#6c757d;opacity:1}.form-control::-ms-input-placeholder{color:#6c757d;opacity:1}.form-control:disabled{background-color:#e9ecef;opacity:1}select.form-control:focus::-ms-value{color:#495057;background-color:#fff}textarea.form-control{height:auto}.form-group{margin-bottom:1rem}.form-check{position:relative;display:block;padding-left:1.25rem}.form-check-input{position:absolute;margin-top:.3rem;margin-left:-1.25rem}.form-check-input:disabled~.form-check-label{color:#6c757d}.form-check-label{margin-bottom:0}.btn{display:inline-block;font-weight:400;text-align:center;white-space:nowrap;vertical-align:middle;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;border:1px solid transparent;padding:.375rem .75rem;font-size:1rem;line-height:1.5;border-radius:.25rem;transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out}@media screen and (prefers-reduced-motion:reduce){.btn{transition:none}}.btn:focus,.btn:hover{text-decoration:none}.btn:focus{outline:0;box-shadow:0 0 0 .2rem rgba(0,123,255,.25)}.btn:disabled{opacity:.65}.btn:not(:disabled):not(.disabled){cursor:pointer}.btn-primary{color:#fff;background-color:#007bff;border-color:#007bff}.btn-primary:hover{color:#fff;background-color:#0069d9;border-color:#0062cc}.btn-primary:focus{box-shadow:0 0 0 .2rem rgba(0,123,255,.5)}.btn-primary:disabled{color:#fff;background-color:#007bff;border-color:#007bff}.btn-primary:not(:disabled):not(.disabled).active,.btn-primary:not(:disabled):not(.disabled):active{color:#fff;background-color:#0062cc;border-color:#005cbf}.btn-primary:not(:disabled):not(.disabled).active:focus,.btn-primary:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(0,123,255,.5)}.btn-secondary:not(:disabled):not(.disabled).active,.btn-secondary:not(:disabled):not(.disabled):active{color:#fff;background-color:#545b62;border-color:#4e555b}.btn-secondary:not(:disabled):not(.disabled).active:focus,.btn-secondary:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(108,117,125,.5)}.btn-success{color:#fff;background-color:#28a745;border-color:#28a745}.btn-success:hover{color:#fff;background-color:#218838;border-color:#1e7e34}.btn-success:focus{box-shadow:0 0 0 .2rem rgba(40,167,69,.5)}.btn-success:disabled{color:#fff;background-color:#28a745;border-color:#28a745}.btn-success:not(:disabled):not(.disabled).active,.btn-success:not(:disabled):not(.disabled):active{color:#fff;background-color:#1e7e34;border-color:#1c7430}.btn-success:not(:disabled):not(.disabled).active:focus,.btn-success:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(40,167,69,.5)}.btn-info:not(:disabled):not(.disabled).active,.btn-info:not(:disabled):not(.disabled):active{color:#fff;background-color:#117a8b;border-color:#10707f}.btn-info:not(:disabled):not(.disabled).active:focus,.btn-info:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(23,162,184,.5)}.btn-warning:not(:disabled):not(.disabled).active,.btn-warning:not(:disabled):not(.disabled):active{color:#212529;background-color:#d39e00;border-color:#c69500}.btn-warning:not(:disabled):not(.disabled).active:focus,.btn-warning:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(255,193,7,.5)}.btn-danger:not(:disabled):not(.disabled).active,.btn-danger:not(:disabled):not(.disabled):active{color:#fff;background-color:#bd2130;border-color:#b21f2d}.btn-danger:not(:disabled):not(.disabled).active:focus,.btn-danger:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(220,53,69,.5)}.btn-light:not(:disabled):not(.disabled).active,.btn-light:not(:disabled):not(.disabled):active{color:#212529;background-color:#dae0e5;border-color:#d3d9df}.btn-light:not(:disabled):not(.disabled).active:focus,.btn-light:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(248,249,250,.5)}.btn-dark:not(:disabled):not(.disabled).active,.btn-dark:not(:disabled):not(.disabled):active{color:#fff;background-color:#1d2124;border-color:#171a1d}.btn-dark:not(:disabled):not(.disabled).active:focus,.btn-dark:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(52,58,64,.5)}.btn-outline-primary:not(:disabled):not(.disabled).active,.btn-outline-primary:not(:disabled):not(.disabled):active{color:#fff;background-color:#007bff;border-color:#007bff}.btn-outline-primary:not(:disabled):not(.disabled).active:focus,.btn-outline-primary:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(0,123,255,.5)}.btn-outline-secondary:not(:disabled):not(.disabled).active,.btn-outline-secondary:not(:disabled):not(.disabled):active{color:#fff;background-color:#6c757d;border-color:#6c757d}.btn-outline-secondary:not(:disabled):not(.disabled).active:focus,.btn-outline-secondary:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(108,117,125,.5)}.btn-outline-success:not(:disabled):not(.disabled).active,.btn-outline-success:not(:disabled):not(.disabled):active{color:#fff;background-color:#28a745;border-color:#28a745}.btn-outline-success:not(:disabled):not(.disabled).active:focus,.btn-outline-success:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(40,167,69,.5)}.btn-outline-info:not(:disabled):not(.disabled).active,.btn-outline-info:not(:disabled):not(.disabled):active{color:#fff;background-color:#17a2b8;border-color:#17a2b8}.btn-outline-info:not(:disabled):not(.disabled).active:focus,.btn-outline-info:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(23,162,184,.5)}.btn-outline-warning:not(:disabled):not(.disabled).active,.btn-outline-warning:not(:disabled):not(.disabled):active{color:#212529;background-color:#ffc107;border-color:#ffc107}.btn-outline-warning:not(:disabled):not(.disabled).active:focus,.btn-outline-warning:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(255,193,7,.5)}.btn-outline-danger:not(:disabled):not(.disabled).active,.btn-outline-danger:not(:disabled):not(.disabled):active{color:#fff;background-color:#dc3545;border-color:#dc3545}.btn-outline-danger:not(:disabled):not(.disabled).active:focus,.btn-outline-danger:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(220,53,69,.5)}.btn-outline-light:not(:disabled):not(.disabled).active,.btn-outline-light:not(:disabled):not(.disabled):active{color:#212529;background-color:#f8f9fa;border-color:#f8f9fa}.btn-outline-light:not(:disabled):not(.disabled).active:focus,.btn-outline-light:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(248,249,250,.5)}.btn-outline-dark:not(:disabled):not(.disabled).active,.btn-outline-dark:not(:disabled):not(.disabled):active{color:#fff;background-color:#343a40;border-color:#343a40}.btn-outline-dark:not(:disabled):not(.disabled).active:focus,.btn-outline-dark:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(52,58,64,.5)}.btn-lg{padding:.5rem 1rem;font-size:1.25rem;line-height:1.5;border-radius:.3rem}.btn-block{display:block;width:100%}.fade{transition:opacity .15s linear}@media screen and (prefers-reduced-motion:reduce){.fade{transition:none}}.fade:not(.show){opacity:0}.collapse:not(.show){display:none}.dropdown{position:relative}.dropdown-toggle::after{display:inline-block;width:0;height:0;margin-left:.255em;vertical-align:.255em;content:"";border-top:.3em solid;border-right:.3em solid transparent;border-bottom:0;border-left:.3em solid transparent}.dropdown-menu{position:absolute;top:100%;left:0;z-index:1000;display:none;float:left;min-width:10rem;padding:.5rem 0;margin:.125rem 0 0;font-size:1rem;color:#212529;text-align:left;list-style:none;background-color:#fff;background-clip:padding-box;border:1px solid rgba(0,0,0,.15);border-radius:.25rem}.dropdown-menu-right{right:0;left:auto}.dropdown-item{display:block;width:100%;padding:.25rem 1.5rem;clear:both;font-weight:400;color:#212529;text-align:inherit;white-space:nowrap;background-color:transparent;border:0}.dropdown-item:focus,.dropdown-item:hover{color:#16181b;text-decoration:none;background-color:#f8f9fa}.dropdown-item.active,.dropdown-item:active{color:#fff;text-decoration:none;background-color:#007bff}.dropdown-item:disabled{color:#6c757d;background-color:transparent}.input-group{position:relative;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-ms-flex-align:stretch;align-items:stretch;width:100%}.input-group>.form-control{position:relative;-ms-flex:1 1 auto;flex:1 1 auto;width:1%;margin-bottom:0}.input-group>.form-control:focus{z-index:3}.input-group>.form-control:not(:first-child){border-top-left-radius:0;border-bottom-left-radius:0}.nav{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;padding-left:0;margin-bottom:0;list-style:none}.nav-link{display:block;padding:.5rem 1rem}.nav-link:focus,.nav-link:hover{text-decoration:none}.nav-tabs{border-bottom:1px solid #dee2e6}.nav-tabs .nav-item{margin-bottom:-1px}.nav-tabs .nav-link{border:1px solid transparent;border-top-left-radius:.25rem;border-top-right-radius:.25rem}.nav-tabs .nav-link:focus,.nav-tabs .nav-link:hover{box-shadow:1px 1px 20px 0 #2e31926e}.navbar{position:relative;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between;padding:.5rem 1rem}.navbar>.container-fluid{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between}.navbar-brand{display:inline-block;padding-top:.3125rem;padding-bottom:.3125rem;margin-right:1rem;font-size:1.25rem;line-height:inherit;white-space:nowrap}.navbar-brand:focus,.navbar-brand:hover{text-decoration:none}.navbar-nav{display:-ms-flexbox;display:flex;-ms-flex-direction:column;flex-direction:column;padding-left:0;margin-bottom:0;list-style:none}.navbar-nav .nav-link{padding-right:0;padding-left:0}.navbar-nav .dropdown-menu{position:static;float:none}.navbar-collapse{-ms-flex-preferred-size:100%;flex-basis:100%;-ms-flex-positive:1;flex-grow:1;-ms-flex-align:center;align-items:center}.navbar-toggler{padding:.25rem .75rem;font-size:1.25rem;line-height:1;background-color:transparent;border:1px solid transparent;border-radius:.25rem}.navbar-toggler:focus,.navbar-toggler:hover{text-decoration:none}.navbar-toggler:not(:disabled):not(.disabled){cursor:pointer}@media(max-width:767.98px){.navbar-expand-md>.container-fluid{padding-right:0;padding-left:0}}@media(min-width:768px){.navbar-expand-md{-ms-flex-flow:row nowrap;flex-flow:row nowrap;-ms-flex-pack:start;justify-content:flex-start}.navbar-expand-md .navbar-nav{-ms-flex-direction:row;flex-direction:row}.navbar-expand-md .navbar-nav .dropdown-menu{position:absolute}.navbar-expand-md .navbar-nav .nav-link{padding-right:.5rem;padding-left:.5rem}.navbar-expand-md>.container-fluid{-ms-flex-wrap:nowrap;flex-wrap:nowrap}.navbar-expand-md .navbar-collapse{display:-ms-flexbox!important;display:flex!important;-ms-flex-preferred-size:auto;flex-basis:auto}.navbar-expand-md .navbar-toggler{display:none}}.navbar-light .navbar-brand{color:rgba(0,0,0,.9)}.navbar-light .navbar-brand:focus,.navbar-light .navbar-brand:hover{color:rgba(0,0,0,.9)}.navbar-light .navbar-nav .nav-link{color:rgba(0,0,0,.5)}.navbar-light .navbar-nav .nav-link:focus,.navbar-light .navbar-nav .nav-link:hover{color:rgba(0,0,0,.7)}.navbar-light .navbar-toggler{color:rgba(0,0,0,.5);border-color:rgba(0,0,0,.1)}.breadcrumb{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;padding:.75rem 1rem;margin-bottom:1rem;list-style:none;background-color:#e9ecef;border-radius:.25rem}.breadcrumb-item+.breadcrumb-item{padding-left:.5rem}.breadcrumb-item+.breadcrumb-item::before{display:inline-block;padding-right:.5rem;color:#6c757d;content:"/"}.breadcrumb-item+.breadcrumb-item:hover::before{text-decoration:underline}.breadcrumb-item+.breadcrumb-item:hover::before{text-decoration:none}.breadcrumb-item.active{color:#6c757d}.page-link:not(:disabled):not(.disabled){cursor:pointer}.badge{display:inline-block;padding:.25em .4em;font-size:75%;font-weight:700;line-height:1;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25rem}.badge-pill{padding-right:.6em;padding-left:.6em;border-radius:10rem}.badge-danger{color:#fff;background-color:#dc3545}.close{float:right;font-size:1.5rem;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;opacity:.5}.close:not(:disabled):not(.disabled){cursor:pointer}.close:not(:disabled):not(.disabled):focus,.close:not(:disabled):not(.disabled):hover{color:#000;text-decoration:none;opacity:.75}button.close{padding:0;background-color:transparent;border:0;-webkit-appearance:none}.modal{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1050;display:none;overflow:hidden;outline:0}.modal-dialog{position:relative;width:auto;margin:.5rem;pointer-events:none}.modal.fade .modal-dialog{transition:-webkit-transform .3s ease-out;transition:transform .3s ease-out;transition:transform .3s ease-out,-webkit-transform .3s ease-out;-webkit-transform:translate(0,-25%);transform:translate(0,-25%)}@media screen and (prefers-reduced-motion:reduce){.modal.fade .modal-dialog{transition:none}}.modal-content{position:relative;display:-ms-flexbox;display:flex;-ms-flex-direction:column;flex-direction:column;width:100%;pointer-events:auto;background-color:#fff;background-clip:padding-box;border:1px solid rgba(0,0,0,.2);border-radius:.3rem;outline:0}.modal-header{display:-ms-flexbox;display:flex;-ms-flex-align:start;align-items:flex-start;-ms-flex-pack:justify;justify-content:space-between;padding:1rem;border-bottom:1px solid #e9ecef;border-top-left-radius:.3rem;border-top-right-radius:.3rem}.modal-header .close{padding:1rem;margin:-1rem -1rem -1rem auto}.modal-title{margin-bottom:0;line-height:1.5}.modal-body{position:relative;-ms-flex:1 1 auto;flex:1 1 auto;padding:1rem}@media(min-width:576px){.modal-dialog{max-width:500px;margin:1.75rem auto}}@supports((-webkit-transform-style:preserve-3d) or (transform-style:preserve-3d)){}@supports((-webkit-transform-style:preserve-3d) or (transform-style:preserve-3d)){}@supports((-webkit-transform-style:preserve-3d) or (transform-style:preserve-3d)){}@supports((-webkit-transform-style:preserve-3d) or (transform-style:preserve-3d)){}.bg-light{background-color:#f8f9fa!important}.fixed-top{position:fixed;top:0;right:0;left:0;z-index:1030}@supports((position:-webkit-sticky) or (position:sticky)){}.sr-only{position:absolute;width:1px;height:1px;padding:0;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}.shadow-sm{box-shadow:0 .125rem .25rem rgba(0,0,0,.075)!important}.m-0{margin:0!important}.mt-4{margin-top:1.5rem!important}.pb-4{padding-bottom:1.5rem!important}.ml-auto{margin-left:auto!important}.text-center{text-align:center!important}@media print{*,::after,::before{text-shadow:none!important;box-shadow:none!important}a:not(.btn){text-decoration:underline}img{page-break-inside:avoid}h2,h3,p{orphans:3;widows:3}h2,h3{page-break-after:avoid}@page{size:a3}body{min-width:992px!important}.container{min-width:992px!important}.navbar{display:none}.badge{border:1px solid #000}}html{font-family:sans-serif;line-height:1.15;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0;}aside,footer,nav,section{display:block}h1{font-size:2em;margin:.67em 0}hr{-moz-box-sizing:content-box;box-sizing:content-box;height:0;overflow:visible}a{background-color:transparent;-webkit-text-decoration-skip:objects}a:active,a:hover{outline-width:0}strong{font-weight:inherit}strong{font-weight:bolder}img{border-style:none}svg:not(:root){overflow:hidden}button,input,select,textarea{font-family:sans-serif;font-size:100%;line-height:1.15;margin:0}button,input{overflow:visible}button,select{text-transform:none}[type=submit],button,html [type=button]{-webkit-appearance:button}[type=button]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner{border-style:none;padding:0}[type=button]:-moz-focusring,[type=submit]:-moz-focusring,button:-moz-focusring{outline:1px dotted ButtonText}fieldset{border:1px solid silver;margin:0 2px;padding:.35em .625em .75em}textarea{overflow:auto}[type=radio]{-moz-box-sizing:border-box;box-sizing:border-box;padding:0}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}html{-moz-box-sizing:border-box;box-sizing:border-box}*,::after,::before{-moz-box-sizing:inherit;box-sizing:inherit}html{-ms-overflow-style:scrollbar;-webkit-tap-highlight-color:transparent}body{font-family:roboto,sans-serif;font-size:.85rem;font-weight:400;line-height:1.5;color:#292b2c;background-color:#eee}@media(max-width:767px){body{font-size:.75rem}}[tabindex="-1"]:focus{outline:0!important}h1,h2,h3,h4,h5{margin-top:0;margin-bottom:.5rem}p{margin-top:0;margin-bottom:1rem}ol,ul{margin-top:0;margin-bottom:1rem}ul ul{margin-bottom:0;list-style-type:none}a{color:#16a085;text-decoration:none;-webkit-transition:all .2s linear 0s,letter-spacing 0s linear 0s;-moz-transition:all .2s linear 0s,letter-spacing 0s linear 0s;-o-transition:all .2s linear 0s,letter-spacing 0s linear 0s;transition:all .2s linear 0s,letter-spacing 0s linear 0s}a:focus,a:hover{color:#0d5d4d;text-decoration:underline}img{vertical-align:middle}a,button,input,label,select,textarea{touch-action:manipulation}label{display:inline-block;margin-bottom:.5rem}button:focus{outline:1px dotted;outline:5px auto -webkit-focus-ring-color}button,input,select,textarea{line-height:inherit}input[type=radio]:disabled{cursor:not-allowed}textarea{resize:vertical}fieldset{min-width:0;padding:0;margin:0;border:0}h1,h2,h3,h4,h5{margin-bottom:.5rem;font-family:inherit;font-weight:500;line-height:1.1;color:inherit}h1{font-size:2.5rem}h2{font-size:2rem}h3{font-size:1.75rem}h4{font-size:1.5rem}h5{font-size:1.25rem}hr{margin-top:1rem;margin-bottom:1rem;border:0;border-top:1px solid rgba(0,0,0,.1)}.form-control{display:block;width:100%;height:48px;padding:.5rem .75rem;font-size:1rem;line-height:1.25;color:#464a4c;background-color:#fff;background-image:none;background-clip:padding-box;border:none;border-radius:.2rem;-webkit-transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s;-moz-transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s;-o-transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s;transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s}.form-control::-ms-expand{background-color:transparent;border:0}.form-control:focus{color:#464a4c;background-color:#fff;border-color:#16a085;outline:0;box-shadow:1px 0 #16a085,0 -1px 0 #16a085,-1px 0 0 #16a085,1px 0 0 #16a085}.form-control::-webkit-input-placeholder{color:#636c72;opacity:1}.form-control:-moz-placeholder{color:#636c72;opacity:1}.form-control:disabled{background-color:#eceeef;opacity:1}.form-control:disabled{cursor:not-allowed}html{-moz-box-sizing:border-box;box-sizing:border-box;font-size:100%}*,::after,::before{-moz-box-sizing:inherit;box-sizing:inherit}html{-ms-overflow-style:scrollbar;-webkit-tap-highlight-color:transparent}body,html{height:100%}li,ol,ul{list-style-type:none}a{color:#16a085;outline:0;cursor:pointer;-webkit-transition:all .2s linear 0s,letter-spacing 0s linear 0s;-moz-transition:all .2s linear 0s,letter-spacing 0s linear 0s;-o-transition:all .2s linear 0s,letter-spacing 0s linear 0s;transition:all .2s linear 0s,letter-spacing 0s linear 0s}a:active,a:focus,a:hover{outline:0;text-decoration:none;color:#0d5d4d}*{outline:0}:focus,:hover{outline:0}ul{list-style:none;margin:0;padding:0}ul li{margin:0;padding:0}ol{margin:0;padding:0}p{padding:0}fieldset{border:none;margin:0;padding:0}button{cursor:pointer}.no-margin{margin:0!important}.no-padding{padding:0!important}.btn{display:inline-block;font-weight:400;line-height:1.5;text-align:center;white-space:nowrap;vertical-align:middle;-webkit-user-select:none;-moz-user-select:none;user-select:none;border:1px solid transparent;padding:8px 12px;font-size:1rem;border-radius:.2rem;-webkit-transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;transition:all .2s ease-in-out}.btn:focus,.btn:hover{text-decoration:none}.btn:focus{outline:0;box-shadow:0 0 0 2px rgba(22,160,133,.25)}.btn:disabled{cursor:not-allowed;opacity:.65}.btn:active{background-image:none}.btn-primary{color:#fff;background-color:#16a085;border-color:#16a085}.btn-primary:hover{color:#fff;background-color:#107360;border-color:#0f6a58}.btn-primary:focus{box-shadow:0 0 0 2px rgba(22,160,133,.5)}.btn-primary:disabled{background-color:#16a085;border-color:#16a085}.btn-primary:active,.btn-primary:active:focus{color:#fff;background-color:#107360;background-image:none;border-color:#0f6a58}.btn-primary:not([href]):not([tabindex]):not(.btn-line){color:#fff}.btn-primary:not([href]):not([tabindex]):not(.btn-line):hover{color:#fff}.btn-default{color:#292b2c;background-color:#fff;border-color:#ccc}.btn-default:hover{color:#292b2c;background-color:#e6e5e5;border-color:#adadad}.btn-default:focus{box-shadow:0 0 0 2px rgba(204,204,204,.5)}.btn-default:disabled{background-color:#fff;border-color:#ccc}.btn-default:active,.btn-default:active:focus{color:#292b2c;background-color:#e6e5e5;background-image:none;border-color:#adadad}.btn-success{color:#fff;background-color:#1cc363;border-color:#1cc363;box-shadow:1px 1px 20px 0 #65faa4}.btn-success:hover{color:#fff}.btn-success:focus{box-shadow:0 0 0 2px rgba(46,204,113,.5)}.btn-success:disabled{background-color:#2ecc71;border-color:#2ecc71}.btn-success:active,.btn-success:active:focus{color:#fff;background-color:#25a25a;background-image:none;border-color:#239a55}.btn-success:not([href]):not([tabindex]){color:#fff}.btn-success:not([href]):not([tabindex]):hover{color:#fff}.btn-lg{padding:10px 18px;font-size:1rem;border-radius:.2 .5rem}.btn-block{display:block;width:100%}h1,h2,h3,h4,h5{font-family:roboto,sans-serif;font-weight:400;margin:0;padding-bottom:15px;text-rendering:optimizeLegibility;-webkit-font-smoothing:antialiased}h1{font-size:30px;line-height:35px}h2{font-size:24px;line-height:28px}h3{font-size:20px;line-height:24px}h4{font-size:17px;line-height:20px}h5{font-size:14px;line-height:20px}@media(max-width:979px){h1{font-size:24px;line-height:26px}h2{font-size:20px;line-height:24px}h3{font-size:18px;line-height:20px}h4{font-size:16px;line-height:18px}h5{font-size:14px;line-height:20px}}@media(max-width:767px){h1{font-size:22px;line-height:24px}h2{font-size:18px;line-height:24px}h3{font-size:16px;line-height:18px}h4{font-size:14px;line-height:18px}h5{font-size:12px;line-height:16px}}label{font-weight:600}.dropdown{position:relative}.dropdown-toggle::after{display:inline-block;width:0;height:0;margin-left:.3em;vertical-align:middle;content:"";border-top:.3em solid;border-right:.3em solid transparent;border-left:.3em solid transparent}.dropdown-toggle:focus{outline:0}.dropdown-menu{position:absolute;top:100%;left:0;z-index:1000;display:none;float:left;min-width:10rem;padding:.5rem 0;margin:.125rem 0 0;font-size:.85rem;color:#292b2c;text-align:left;list-style:none;background-color:#fff;background-clip:padding-box;border:1px solid rgba(0,0,0,.15)}.dropdown-item{display:block;width:100%;clear:both;font-weight:400;color:#292b2c;text-align:inherit;white-space:nowrap;background-color:transparent;border:0}.dropdown-item:focus,.dropdown-item:hover{color:#1d1e1f;text-decoration:none;background-color:#f7f7f9}.dropdown-item.active,.dropdown-item:active{color:#fff;text-decoration:none;background-color:#16a085}.dropdown-item:disabled{color:#636c72;background-color:transparent}.logo,.logo-title{font-family:roboto,sans-serif;font-size:28px;font-weight:700;text-transform:uppercase;color:#16a085}@media(max-width:991px){.logo,.logo-title{font-size:20px}}@media(max-width:479px){.logo,.logo-title{font-size:18px}}.search-row button.btn-search{border-radius:0;border:0;height:48px;margin-bottom:0}@media(min-width:992px){.logo-title{padding-top:20px}}@media(max-width:767px){.logo-title{padding-top:18px}}@media(max-width:479px){.logo-title{height:auto}}.navbar-site.navbar{-webkit-transition:all .25s ease-out 0s;-moz-transition:all .25s ease-out 0s;-o-transition:all .25s ease-out 0s;transition:all .25s ease-out 0s;margin-top:0;z-index:100}.navbar-site.navbar .navbar-nav.navbar-right .dropdown .icon-down-open-big.fa{display:inline-block}@media(max-width:767px){.navbar-site.navbar{background:#fff;border-radius:0;height:auto;margin-bottom:0!important;padding:0}.navbar-site.navbar .navbar-collapse{padding:15px}.navbar-site.navbar .navbar-identity{display:block;height:80px;padding:0 15px;border-bottom:solid 1px #ddd}.navbar-site.navbar .navbar-identity .navbar-toggler{margin-top:18px;padding:0 10px;height:40px}.navbar-site.navbar .nav>.nav-item,.navbar-site.navbar .nav>li{padding:3px 0}.navbar-site.navbar .nav>.nav-item a:not(.btn),.navbar-site.navbar .nav>li a:not(.btn){color:#333}.navbar-site.navbar .nav>.nav-item .dropdown-menu>li,.navbar-site.navbar .nav>li .dropdown-menu>li{font-size:15px;padding:10px}.navbar-site.navbar .nav>.nav-item .dropdown-menu>li.active,.navbar-site.navbar .nav>li .dropdown-menu>li.active{background:#eee;font-weight:700;color:#2a3744}}@media(max-width:479px){.navbar-site.navbar .navbar-identity{height:75px}.navbar-site.navbar .navbar-identity .navbar-toggler{margin-top:13px}.navbar-site.navbar .navbar-identity .logo-title{padding-top:22px}}@media(min-width:768px){.logo{height:80px;line-height:normal}.header{height:auto;background:#fff}.navbar-site{margin-bottom:0!important;padding-top:0;padding-bottom:0;height:auto;background:#fff;border-radius:0;border-bottom:solid 1px #ddd}.navbar.navbar-light .navbar-nav>li,.navbar.navbar-site .navbar-nav>li{margin-top:10px;margin-bottom:10px}.navbar.navbar-light .navbar-nav>li .nav-link:not(.btn),.navbar.navbar-light .navbar-nav>li>a:not(.btn),.navbar.navbar-site .navbar-nav>li .nav-link:not(.btn),.navbar.navbar-site .navbar-nav>li>a:not(.btn){border-radius:3px;-moz-box-sizing:border-box;box-sizing:border-box;color:#333;font-size:12px;height:40px;line-height:1;padding:12px 10px}.navbar.navbar-light .navbar-nav>li .nav-link:not(.btn):hover,.navbar.navbar-light .navbar-nav>li>a:not(.btn):hover,.navbar.navbar-site .navbar-nav>li .nav-link:not(.btn):hover,.navbar.navbar-site .navbar-nav>li>a:not(.btn):hover{color:#000}}@media(min-width:768px){li .user-menu{padding:0;-webkit-transition:all .2s linear 0s;-moz-transition:all .2s linear 0s;-o-transition:all .2s linear 0s;transition:all .2s linear 0s}li .user-menu li a{font-size:13px;letter-spacing:.55px;clear:both;display:block;font-weight:400;line-height:1.42857;padding:6px 24px;text-transform:inherit;white-space:nowrap}li .user-menu li i{margin-right:5px;color:#999;font-size:120%;-webkit-transition:all .2s linear 0s;-moz-transition:all .2s linear 0s;-o-transition:all .2s linear 0s;transition:all .2s linear 0s}li .user-menu li:hover i{color:#2a3744}li .dropdown-menu.user-menu>.active>a{background:#eee;font-weight:700;color:#2a3744}li.dropdown-item.active,li.dropdown-item:active{background:#eee;font-weight:700;color:#2a3744}.nav>li.active{background:0 0}.nav>li>a:not(.btn):hover{background:0 0}.nav>li>a:not(.btn):focus{background:0 0}.nav>li:hover>a:not(.btn){background:0 0}.dropdown-menu>li{padding:4px 8px}.dropdown-menu>li a{color:#333}.dropdown-menu.user-menu>.active{background:#eee;font-weight:700;color:#2a3744}.dropdown-menu.user-menu>.active>a:focus{background:#eee;font-weight:700;color:#2a3744}}@media(min-width:768px) and (max-width:992px){.logo{height:auto}}.dropdown.no-arrow>a:after{display:none!important}.footer-nav-inline li a{color:#333;font-size:16px}.footer-nav-inline.social-list-color li a{color:#fff;font-size:14px;width:28px;display:inline-block;text-align:center;padding:3px 0}.icon-color.fb{background:#3b5998}.icon-color.tw{background:#55acee}.icon-color.gp{background:#dd4b39}.icon-color.lin{background:#007bb5}.icon-color.pin{background:#cb2027}.footer-nav li a{border-left:solid 1px #ddd;padding:0;color:#333;font-size:12px}.footer-nav li a:first-child{border-left:0}.footer-nav li a:hover{opacity:.6}.search-row{max-width:800px;margin-left:auto;margin-right:auto;margin-top:30px}.search-row .btn-search{border-radius:0 3px 3px 0!important}.locinput{border-right:solid 1px #ddd!important}.has-icon{padding-left:40px}@media(max-width:767px){.locinput{border-right:none!important;border-bottom:solid 1px #ddd!important}}@media(max-width:767px){.col-thin-left{padding-left:15px;padding-right:15px}}.modal-title{text-align:left}.search-row-wrapper{background-position:center;background-size:cover;height:auto;-webkit-transition:all .4s cubic-bezier(.25,.1,.25,1) 0s;-moz-transition:all .4s cubic-bezier(.25,.1,.25,1) 0s;-o-transition:all .4s cubic-bezier(.25,.1,.25,1) 0s;transition:all .4s cubic-bezier(.25,.1,.25,1) 0s;width:100%}.search-row-wrapper .btn-primary{line-height:22px}@media screen and (min-width:1024px){.search-row-wrapper{height:140px;display:block}}.col-thin-left{padding-left:10px}.adds-wrapper{clear:both;height:auto;width:100%}.item-list{border-bottom:solid 1px #ddd;clear:both;padding:15px;height:auto;width:100%;display:block;position:relative;overflow:hidden;-moz-transition:background .25s ease-in;-o-transition:background .25s ease-in;transition:background .25s ease-in;-webkit-transition:background .25s ease-in}.item-list .row{}.item-list:hover{background:#f6f6f5}.category-list{}.add-image{position:relative}.photo-count{background:#ccc;border:0;border-radius:2px;font-size:12px;opacity:.75;padding:0 3px;position:absolute;right:4px;top:5px}.add-image a{display:block}.add-image a img{width:100%}.add-title{padding-bottom:7px;font-weight:700}.info-row{display:block;clear:both;font-size:12px;color:#9a9a9a}@media(max-width:767px){.item-list .ads-details{padding:10px 0 0}}@media(max-width:1199px){.ads-details{padding:0}}.cornerRibbons{box-shadow:0 0 2px rgba(0,0,0,.2);left:-8%;overflow:hidden;position:absolute;top:20%;-moz-transform:rotate(-45deg);-webkit-transform:rotate(-45deg);-o-transform:rotate(-45deg);transform:rotate(-45deg);width:200px;z-index:2;-webkit-transition:all .3s ease 0s;-moz-transition:all .3s ease 0s;-o-transition:all .3s ease 0s;transition:all .3s ease 0s}.cornerRibbons a{color:#fff;display:block;font-family:roboto,sans-serif;font-size:12px;font-weight:400;text-align:center;text-decoration:none;text-shadow:1px 1px 1px rgba(0,0,0,.2);text-transform:uppercase;border:1px solid rgba(255,255,255,.1);-webkit-transition:all .3s ease 0s;-moz-transition:all .3s ease 0s;-o-transition:all .3s ease 0s;transition:all .3s ease 0s}@media(max-width:991px){.cornerRibbons{left:-10%;top:19%;width:170px;z-index:2}.cornerRibbons{font-size:11px}}@media(max-width:767px){.cornerRibbons{left:-36px;top:24px;width:160px}.cornerRibbons{font-size:11px}}.tab-box{background:#f8f8f8;position:relative}.nav-tabs.add-tabs>li.active>a,.nav-tabs.add-tabs>li.active>a:focus,.nav-tabs.add-tabs>li.active>a:hover,.nav-tabs.add-tabs>li>a{border-radius:0;padding-bottom:15px;padding-top:15px;margin-right:0;font-size:13px}.nav-tabs.add-tabs>li:hover>a{background:#ededed}.nav-tabs.add-tabs>li.active>a{border-left:solid 1px #ddd;border-right:solid 1px #ddd;border-top:1px solid #e6e6e6;background:#fff}.nav-tabs.add-tabs>li.active>a:active,.nav-tabs.add-tabs>li.active>a:focus,.nav-tabs.add-tabs>li.active>a:hover{border-bottom:solid 1px #fff}@media(max-width:991px){.nav-tabs .badge{font-size:11px}}@media(max-width:767px){.nav-tabs.add-tabs>li>a{font-size:12px}.nav-tabs .badge{display:none}}.nav-tabs.add-tabs>li:first-child>a{border-left:1px solid transparent}.listing-filter{border-bottom:solid 1px #ddd;padding:15px}.breadcrumb-list{color:#888;font-size:12px;font-weight:400;line-height:1.5;margin-bottom:0}.breadcrumb-list a.current{color:#000;font-weight:700;margin-right:5px}.save-search-bar{border-top:solid 1px #ddd}.save-search-bar:hover{background:#ededed}.save-search-bar a:focus{box-shadow:0 3px 2px rgba(0,0,0,.05) inset}.save-search-bar a{display:block;font-weight:500;padding:10px 0}.list-filter{width:100%;display:block;clear:both}.list-filter ul li{list-style:none}.list-filter ul li a{border-radius:5px;display:block;padding:3px 15px 3px 5px;position:relative;color:#4e575d;font-size:13px}.list-filter ul ul{padding-left:15px}@media(max-width:991px){.list-filter ul ul{padding-left:10px}.list-filter ul li a{padding-right:0}}.save-search-bar{display:block;width:100%}@media(min-width:300px) and (max-width:768px){.category-list:not(.make-grid):not(.make-compact) .info-row{font-size:11px}}.sidebar-modern-inner{background:#fff;border:solid 1px #ddd}.sidebar-modern-inner .block-title{padding:15px;border-bottom:solid 1px #ddd;position:relative}.sidebar-modern-inner .block-title.has-arrow{margin-bottom:5px}.sidebar-modern-inner .block-title.has-arrow:before{border-color:#ddd transparent transparent;border-style:solid;border-width:7px;bottom:-14px;-moz-box-sizing:border-box;box-sizing:border-box;content:" ";display:block;left:34px;position:absolute;z-index:2}.sidebar-modern-inner .block-title.has-arrow:after{border-color:#fff transparent transparent;border-style:solid;border-width:6px;bottom:-12px;-moz-box-sizing:border-box;box-sizing:border-box;content:" ";display:block;left:35px;position:absolute;z-index:2}.sidebar-modern-inner .block-title.sidebar-header h5{line-height:30px}.sidebar-modern-inner .block-title h5{margin:0;padding:0;font-weight:700;color:#292b2c;font-size:16px;text-transform:uppercase}.sidebar-modern-inner .block-title h5 a{color:#292b2c}.sidebar-modern-inner .block-content{padding:15px}.sidebar-modern-inner .list-filter ul li a{font-size:14px;display:-webkit-flex;display:-moz-box;display:flex;-webkit-justify-content:space-between;-moz-box-pack:justify;justify-content:space-between}.sidebar-modern-inner .list-filter ul li a:hover{font-weight:700}.sidebar-modern-inner .list-filter ul li a:hover .title{font-weight:700}@media(max-width:479px){.breadcrumb{float:none!important;text-align:center!important;display:-webkit-inline-flex;display:-moz-inline-box;display:inline-flex;margin:0 auto;padding:0!important;font-size:11px;margin-bottom:0!important}nav[aria-label=breadcrumb]{float:none!important;text-align:center!important;width:100%}}.modal{z-index:1100}.modal-title{padding-bottom:0}.modal-header{border-bottom:solid 1px #ddd;border-radius:3px 3px 0 0;font-weight:700;background:#f8f8f8;border-top:solid 1px #ddd;padding:8px;position:relative}.breadcrumb{background:0 0;border-radius:0;list-style:outside none none;margin-bottom:8px;padding:8px 0}.jobs-s-tag{background:#f1f1f1;border-radius:4px;-moz-box-sizing:border-box;box-sizing:border-box;display:inline-block;font-size:.875em;font-weight:600;line-height:30px;padding:0 10px;margin-right:5px;margin-left:5px}.jobs-s-tag:hover{background:#eee}.jobs-s-tag:after{display:inline-block;font-family:fontello;content:'\e80e';margin-left:5px}@media(min-width:768px){.mobile-filter-sidebar{left:0!important}}@media(max-width:767px){.jobs-s-tag{margin-bottom:4px}.menu-overly-mask{background-color:#000;height:100%;left:0;opacity:.3;position:fixed;top:0;-webkit-transition:all .2s linear 0s;-moz-transition:all .2s linear 0s;-o-transition:all .2s linear 0s;transition:all .2s linear 0s;visibility:hidden;-webkit-backface-visibility:hidden;-moz-backface-visibility:hidden;backface-visibility:hidden;width:100%;z-index:1100}}.filter-content label{padding-left:5px}img{max-width:100%}.text-center{text-align:center}.pull-right{float:right}.pull-left{float:left}@media(max-width:991px){.hidden-sm{display:none!important}}.list-inline{padding-left:0;margin-left:-5px;list-style:none}.list-inline>li{display:inline-block;padding-right:5px;padding-left:5px}@media(min-width:577px) and (max-width:980px){.container{max-width:100%}}#wrapper{padding-top:68px;-webkit-transition:padding .2s ease;-moz-transition:padding .2s ease;-o-transition:padding .2s ease;transition:padding .2s ease}@media(max-width:767px){h1{font-size:24px;line-height:25px}h2{font-size:20px;line-height:24px}h3{font-size:18px;line-height:20px}h4{font-size:16px;line-height:18px}.search-row-wrapper .col-md-3{margin-bottom:10px;display:-webkit-inline-flex;display:-moz-inline-box;display:inline-flex;width:100%}.search-row-wrapper .selecter{max-width:100%}.icon-down-open-big{display:inherit;-webkit-transform:rotate(0);-moz-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0);-webkit-transition:all .3s ease-in;-moz-transition:all .3s ease-in;-o-transition:all .3s ease-in;transition:all .3s ease-in}}@media(max-width:480px){.col-xxs-12{width:100%}}@media(max-width:320px){.container{min-width:300px}body{min-width:300px}html{min-width:300px}.navbar .container{padding-left:5px}}@media(max-width:300px){.container{width:300px}body{width:300px}html{width:300px}}@media only screen and (max-width:400px){.nav-tabs.add-tabs>li>a{font-size:10px;padding-left:5px;padding-right:5px}}.navbar-site{position:fixed;top:0;left:0;right:0;z-index:1001}.fixed-top{position:fixed;top:0;right:0;left:0;z-index:1030}@media(min-width:576px){.container{max-width:540px}}@media(min-width:768px){.container{max-width:720px}}@media(min-width:992px){.container{max-width:960px}}@media(min-width:1200px){.container{max-width:1140px}}body{background:#fff none repeat scroll 0 0;font-size:16px}#wrapper{padding-top:84px;background-color:#fff}::selection{color:#fff;background:#4682b4}::-moz-selection{color:#fff;background:#4682b4}h1,h2,h3,h4,h5{font-family:Roboto,Helvetica,Arial,sans-serif}a{color:#4682b4}a:focus,a:hover{color:#ff8c00}.btn-primary{background-color:#4682b4;border-color:#4682b4;color:#fff}.btn-primary:active,.btn-primary:focus,.btn-primary:hover{background-color:#628fb5;border-color:#628fb5;color:#fff}.btn-default{color:#292b2c;background-color:#fff;border-color:#ccc}.btn-default:hover{color:#292b2c;background-color:#e6e6e6;border-color:#adadad}.btn-default:focus{box-shadow:0 0 0 2px rgba(204,204,204,.5)}.btn-default:disabled{background-color:#fff;border-color:#ccc}.btn-default:active,.btn-default:active:focus{color:#292b2c;background-color:#e6e6e6;background-image:none;border-color:#adadad}.form-control:focus{border-color:#969696;box-shadow:0 1px 0 #969696,0 -1px 0 #969696,-1px 0 0 #969696,1px 0 0 #969696;outline:0}.logo,.logo-title{color:#4682b4}.main-logo{width:auto;height:40px;float:left;margin:0 5px 5px 0}@media screen and (min-width:768px){.modal-dialog{width:600px;max-width:950px}}@media screen and (min-width:992px){.modal-dialog{width:600px;max-width:950px}}.modal{z-index:1979}.modal-dialog{z-index:2000}button.btn-search{border-radius:0!important;font-size:18px;height:48px;letter-spacing:-.5px;text-shadow:0 2px 2px #4682b4;-webkit-text-shadow:0 2px 2px #4682b4;text-transform:uppercase}.navbar.navbar-site{position:fixed!important;z-index:1945;border:0 solid #e7e7e7;border-radius:0;height:auto;margin-bottom:0!important;background-color:#f8f8f8}.navbar.navbar-site .navbar-identity .navbar-brand{height:80px;padding-top:20px;padding-bottom:20px}@media screen and (min-width:768px){.navbar.navbar-site ul.navbar-nav>li>a{padding:12px 10px}}@media screen and (max-width:767px){.btn-block{display:block;width:100%}}@media(max-width:767px){#wrapper{padding-top:81px}.navbar-site.navbar .navbar-identity{height:80px;border-bottom:0}.navbar-site.navbar .navbar-identity .navbar-toggler{margin-top:18px}}@media(max-width:479px){#wrapper{padding-top:81px}.navbar-site.navbar .navbar-identity{height:80px}}@media(min-width:768px) and (max-width:992px){.navbar.navbar-site .navbar-identity a.logo{height:81px}.navbar.navbar-site .navbar-identity a.logo-title{padding-top:20px}}@media(min-width:768px){.navbar.navbar-site .navbar-identity{margin-top:0}.navbar.navbar-site .navbar-collapse{margin-top:0}}.navbar.navbar-site ul.navbar-nav>li.nav-item>a.nav-link>.badge.count-conversations-with-new-messages{font-size:75%}@media(min-width:768px) and (max-width:1200px){.navbar-right i{display:none}}.search-row{margin-top:0;max-width:900px}.search-row .btn-search{border-radius:0!important}.search-row-wrapper{background:#4682b4;height:auto;padding:5px;transition:all .4s cubic-bezier(.25,.1,.25,1) 0s;width:100%;margin-top:30px}.search-row-wrapper .container div{padding-left:1px;padding-right:1px}@media(min-width:768px){.search-row-wrapper .container{padding:0;width:100%}}@media(max-width:767px){.search-row-wrapper,.search-row-wrapper .container{padding:0}.search-row-wrapper{padding-top:5px}.search-row-wrapper .container{padding-right:5px;padding-left:5px}.search-row-wrapper .col-xs-12{margin-bottom:5px;display:-webkit-inline-flex;display:-moz-inline-box;display:inline-flex;width:100%}}@media(max-width:767px){.menu-overly-mask{z-index:1950}}@media(max-width:767px){.navbar-brand.logo.logo-title{padding-top:20px}}h2{font-size:24px;line-height:24px}h3{font-size:20px;line-height:24px}a.info-link{font-weight:400;color:#9a9a9a;font-size:12px}a.info-link:hover{text-decoration:underline}.footer-nav li a,.logo,.logo-title,button.btn-search{text-transform:none}.form-control{border:1px solid #ddd;box-shadow:1px 1px 20px 0 #e8e8e8}nav.search-breadcrumb>ol.breadcrumb{border-bottom:1px solid #ddd;color:#333;font-size:14px;line-height:18px;list-style:none;padding:15px 0 10px!important;overflow:hidden;width:100%}.category-links{padding:0 5px;overflow:hidden;text-align:left;margin:10px 0}.category-links ul{color:#666;font-weight:700; display: -webkit-inline-box;overflow-x: scroll;}.category-links ul li{margin:0 2.06186% 7px 0;vertical-align:top;display: -webkit-box;}.category-links ul li a{font-weight:400}.badge{font-size:100%}.search-row button.btn-search,.search-row-wrapper .form-control,.search-row-wrapper button.btn{font-size:16px}.search-row button.btn-search,.search-row-wrapper .form-control,.search-row-wrapper button.btn{height:45px}.cornerRibbons.green{background:#16a085}.cornerRibbons.orange{background:#fb8d17}.cornerRibbons.orange a{color:#fff}.enable-long-words{display:block;max-width:100%;word-wrap:break-word}.ads-details h2{font-weight:700;margin:0;padding:5px}.filter-content label{font-weight:400}.locinput{border-right-width:0!important}.btn:focus{outline:0;box-shadow:0 0 0 2px rgba(155,155,155,.25)}.badge.badge-important{background-color:#d9534f;border-color:#d9534f;color:#fff}.dropdown-menu-right{right:0;left:auto}ul.dropdown-menu.user-menu>li{margin:0;padding:0}.adds-wrapper{display:block;overflow:auto}.skin-blue a{color:#000}.skin-blue a:focus,.skin-blue a:hover{color:#000}.skin-blue .footer-nav li a:focus,.skin-blue .footer-nav li a:hover{color:#333;opacity:.6}.skin-blue .footer-nav-inline.social-list-color li a:focus,.skin-blue .footer-nav-inline.social-list-color li a:hover{color:#fff;opacity:.6}.skin-blue ::selection{color:#fff;background:#4682b4}.skin-blue ::-moz-selection{color:#fff;background:#4682b4}.skin-blue .search-row-wrapper{background:#4682b4}.skin-blue button.btn-search{background-color:#4682b4;border-color:#4682b4}.skin-blue .btn-primary{background-color:#32b5ed;border-color:#32b2ed;color:#fff}.skin-blue .btn-primary:active,.skin-blue .btn-primary:focus,.skin-blue .btn-primary:hover{background-color:#628fb5;border-color:#628fb5;color:#fff}.skin-blue .form-control:focus{border-color:#4682b4;box-shadow:0 1px 0 #4682b4,0 -1px 0 #4682b4,-1px 0 0 #4682b4,1px 0 0 #4682b4;outline:0}.skin-blue .logo,.skin-blue .logo-title{color:#4682b4}.skin-blue button.btn-search{text-shadow:0 2px 2px #4682b4;-webkit-text-shadow:0 2px 2px #4682b4}.skin-blue .btn-default,.skin-blue .btn-success{color:#fff}.skin-blue .cornerRibbons a{color:#fff}.skin-blue .btn:focus,.skin-blue .btn:hover{color:#333}.skin-blue .btn-default:active,.skin-blue .btn-default:focus,.skin-blue .btn-default:hover,.skin-blue .btn-primary:active,.skin-blue .btn-primary:focus,.skin-blue .btn-primary:hover,.skin-blue .btn-success:active,.skin-blue .btn-success:focus,.skin-blue .btn-success:hover{color:#fff}.skin-blue .btn-default{color:#292b2c}.skin-blue .btn-default:active,.skin-blue .btn-default:focus,.skin-blue .btn-default:hover{color:#292b2c}.skin-blue .dropdown-menu>li a:focus,.skin-blue .dropdown-menu>li a:hover{color:#333}.company-name-top h1{font-size:35px;margin-top:13px;font-weight:400;font-family:oswald}.products-slider .slider{background:#f4f4f4}#seach{margin-bottom:0}.page-nav li a{color:#000}.sidebar-block-right li a{color:#000;padding-left:7px;font-size:15px;padding-top:3;padding-bottom:3;border-left:2px solid #06b5ee;background-color:#fafafa;transition:all .3s ease-in-out 0s;text-overflow:ellipsis;overflow:hidden;white-space:nowrap}.sidebar-block-right li a:hover{border-color:#000!important;transition:all .4s ease-in-out 0s}.sidebar-block-right li{margin:8px 0}.company-info{margin-top:15px}.company-info .table{margin-top:10px}.company-about-left p{font-size:16px;text-align:justify!important;line-height:27px}.company-btns a.btn{margin:4px 0;display:inline-block;width:100%;transition:all .3s ease-in-out 0s}.company-profile-block{background:#fafafa;padding-top:8px;padding-bottom:8px;transition:all .3s ease-in-out 0s}.company-logo img{border:1px solid #ccc;padding:3px;border-radius:10px;height:96px}.company-name-top .info-row{color:#000}.company-name-top .info-row i{color:#dc0002}.btn.send_company{background:#00ccea!important;border-color:#00ccea!important;color:#fff!important}.company-btns a.btn.btn-default{font-size:16px;line-height:1.5em;transition:background-color .2s ease 0s;padding:18px 16px;margin:4px;color:#fff!important;font-weight:400;background:#0056b3!important;border-radius:3px;border:2px solid #0056b3!important;border-color:#0056b3!important;display:inline-block;width:auto}.company-btns{text-align:right;padding-top:10px}.page-nav li a{font-size:16px}@font-face{font-family:slick;font-weight:400;font-style:normal;src:url(https://www.rednirusmart.com/css/fonts/slick.eot);src:url(https://www.rednirusmart.com/css/fonts/slick.eot?#iefix) format('embedded-opentype'),url(https://www.rednirusmart.com/css/fonts/slick.woff) format('woff'),url(https://www.rednirusmart.com/css/fonts/slick.ttf) format('truetype'),url(https://www.rednirusmart.com/css/fonts/slick.svg#slick) format('svg')}.slider{width:100%;margin:0 auto;text-align:center}.slider div img{width:100%;margin:0;display:block}.pro-title{display:block;width:100%;padding-left:15px;padding-right:15px;font-size:30px}.company-profile-footer{background:#334353;padding:10px 0;margin-top:31px;border-radius:0;width:100%;position:relative;overflow:hidden}.page-nav .nav-item.active a{border:1px dashed #dc0002}h5.footer-company-name{font-size:32px;color:#fff;padding-bottom:6px;font-weight:300}.m-footer .company-btns a.btn.btn-default{font-size:16px;line-height:1.5em;transition:background-color .2s ease 0s;padding:22px 16px;margin:4px;color:#fff!important;font-weight:400;background:#ed3237;border-radius:3px;border:2px solid #fff!important;border-color:#fff!important;display:inline-block;width:auto}.footer-col-info i{color:#f9bbbc}.footer-col.footer-col-info a{font-size:20px;letter-spacing:0;color:#fff}.footer-col.footer-col-info a:hover{color:#fff!important}.footer-col.footer-col-info p{color:#fff;font-size:14px}.company-profile-footer .footer-col{margin:16px 0}.company-profile-footer .footer-title{color:#fff}.company-profile-copy{margin-top:0;background:#ed3237;width:100%}.copy-txt-left{float:left;padding-left:0}.company-profile-copy p{font-size:14px;margin:20px 0 0;color:#fff}.company-profile-footer .company-btns a.btn{box-shadow:0 0 13px 0 #000}.company-profile-copy p a{text-decoration:underline;color:#fa7722}.company-profile-copy .com-name{font-weight:700}.listing-name table td,.listing-name table th{border:1px solid #ccc;padding:5px 8px}.listing-name table{width:100%}.profile-des .btn.send_message{float:left;margin-top:15px;background:#00ccea;border-color:#00ccea;color:#fff}.company-slider img{width:100%}.company-slider{margin-top:30px;margin-bottom:30px}.listing-inner{background:#fff;padding:15px}.listing-inner ul{list-style:none}.company-info a.btn.more-btn{font-size:16px;line-height:1.5em;transition:background-color .2s ease 0s;padding:15px 16px;margin:0 0 20px;color:#0056b3!important;font-weight:400;background:0 0!important;border-radius:3px;border:2px solid #0056b3!important;border-color:#0056b3!important;display:inline-block;width:auto;text-transform:capitalize;float:left}.company-info table tr td:nth-child(1){font-weight:700}.company-info h3{font-size:30px;font-weight:700}.company-about-left h1{font-weight:700;text-align:left}.c-address i{color:#00c1ea;margin-right:3px}.company-about-left h1 span{color:#0075dc}.company-info table{height:138px}.cate-img img{box-shadow:0 0 7px 0 #ccc;border:1px solid #eee;padding:1px}.price-box a.view-website{background:#07b53e!important;border-color:#07b53e!important;text-transform:uppercase;font-family:arial;font-size:14px!important;font-weight:700;max-width:173px!important;margin-top:20px;padding-top:10px!important;padding-bottom:9px}.btn.send_message{background:#fe5300!important;color:#fff!important;border-color:#fe5300!important;text-transform:uppercase;font-family:arial;font-size:14px!important;font-weight:700}.info-row .company-name a i{color:#00f}.company-icon{color:#950ce3}.company-name strong{font-size:16px;color:#337ab7}.c-address{margin-top:0;color:#222;font-weight:400;letter-spacing:.2px;font-size:14px;margin-bottom:10px}.listing-address a.info-link{font-weight:400;font-size:14px;color:gray}.card-address .card-body{font-weight:700;color:#000;letter-spacing:.5px;text-transform:capitalize;font-size:15px}.main-logo{height:60px;margin-bottom:0}.navbar.navbar-site .navbar-identity .navbar-brand{padding-top:5px;padding-bottom:4px;height:auto}.navbar.navbar-light .navbar-nav>li,.navbar.navbar-site .navbar-nav>li{margin-top:0;margin-bottom:0}.big-btn-c a.btn.send_message{max-width:300px;width:100%;height:55px;line-height:55px;padding:0;border-radius:50px;background:0 0!important;color:#0075dc!important;border:2px solid #0075dc!important}.big-btn-c{text-align:center}.detail-line-content{text-align:justify}.company-about p{text-align:justify;font-size:16px}.company-about h2{color:#0075dc;font-size:30px;font-weight:700}.why-us h2{color:#337ab7;font-weight:700}.why-us ul li{line-height:23px;list-style-type:circle;list-style-position:inside;font-size:16px}.contact-add{margin-bottom:10px}.other-listing-btn{display:inline-block;margin-top:15px;position:sticky;top:0}.why-us p{font-size:16px}.other-listing-btn .btn.send_message{background:#16a085;color:#fff;border-color:#16a085}.main-footer .footer-content{background:#ae0507!important}.main-footer .footer-nav li a{color:#fff;font-size:14px}.main-footer .footer-title{color:#fff}.copy-info{color:#fff;font-size:14px}.copy-info a{color:#fe5300}.main-footer hr{border-color:#eee}.footer-nav li{line-height:29px}.footer-nav li a:hover{color:#ccc!important}.footer-nav.social-list-footer li{line-height:23px}.category-links ul li a{font-weight:300;color:#18142b!important;margin-bottom: 10px;padding: 5px 10px !important;border-radius: 23px!important;border-radius: 19px;background: #00b5b726;padding:2px;display:inline-block;border-radius:0;line-height:normal;border:none;margin-top:1px;position:relative;font-size:15px}.category-links ul li strong a::after{content:"";background:#1c2657;height:2px;width:100%;position:absolute;bottom:-5px;left:0;right:0}.category-links ul li a:hover::after{content:"";background:#1c2657;height:2px;width:100%;position:absolute;bottom:-5px;left:0;right:0}.category-links ul li strong a{color:#000;font-weight:500}.category-links ul li a:hover{color:#000!important}.category-links ul li{margin:0 6px 0 0!important}.page-nav .navbar.navbar-expand-lg{padding-bottom:0}.company-name-top .info-row{font-size:17px}.cornerRibbons{left:-7%;top:8%;width:200px}.company-info .table-striped td:first-child{color:#ffaaab;font-weight:700}.page-info-lite{background-color:#e6e6e6}.page-info-lite h5{color:#fe5300}.page-info-lite .iconbox-wrap-text{color:#000}.page-info-lite .iconbox-wrap-icon .icon{color:#07b53e}.pro-thumb{max-width:80px;float:left}.pro-thumb img{max-width:100%;border:1px solid #ccc}.related-pro-des h4{font-size:12px;font-weight:400;color:#000;margin:0;padding:0;min-height:32px;line-height:normal}.related-pro-des h4 a{font-size:12px;font-weight:400;color:#000}.related-pro-des a.btn.get-quote{background:0 0!important;border-color:inherit!important;font-family:arial;font-size:13px!important;font-weight:700;margin-top:5px;padding-top:0;padding-bottom:0;letter-spacing:0;border:none!important;color:#233166!important;text-transform:capitalize;padding-left:0;padding-right:0;margin-top:10px;text-decoration:underline;transition:inherit!important}.related-pro-des a.btn.get-quote:hover{color:#fe5300!important;text-decoration:inherit!important;transition:inherit!important}.related-pro-bottom{width:100%;display:inline-block;margin-top:15px}.main-sidebar .related-pro-des{max-width:116px}.main-sidebar .pro-thumb{max-width:55px}.related-pro-des{float:left;padding-left:8px;max-width:162px}.related-pro-bottom{width:100%;display:inline-block;margin-top:15px;padding-top:15px}.cate-thumb{padding:20px;padding-bottom:0}.banners-ads{background:#f7f7f7;padding:2px;margin-bottom:10px}.listing-container{position:relative;background:#f3f3f3;padding-top:15px;padding-bottom:15px}.listing-container .listing-filter{background:#fff}.listing-container .category-list{background:0 0!important;box-shadow:inherit}.listing-container .adds-wrapper{background:0 0!important}.listing-container .item-list{background:#fff!important}.listing-container .list-filter .count{display:none}.sidebar-modern-inner .list-filter ul li a:hover{font-weight:400}.categories-list ul li a:hover{color:#dc0002!important}.price-box .btn.send_message{font-size:12px!important;max-width:120px!important;padding-left:8px}.price-box.btns-info a.view-website{font-size:12px!important;max-width:120px!important}.ads-details-info .detail-line.col-sm-6{width:100%;max-width:100%!important;flex:0 0 100%!important}.ads-details-info .detail-line div span:first-child,.ads-details-info .detail-line-lite div span:first-child{font-weight:400;color:#828282}.ads-details-info .detail-line-label::after{content:" :";color:#000}.ads-details-info .detail-line div span.detail-line-value{float:left;padding-left:10px}.ads-details .tab-content #tab-details .row{margin-bottom:0;margin-top:0}.detail-line div span:first-child{text-transform:capitalize}.company-profile-block .row{margin-top:0;margin-bottom:0}.page-content .sidebar-block-right .inner-box{min-height:447px;height:auto;padding:0}.col-lg-12.content-footer.text-left.footer-btns a.btn{font-size:12px!important;text-transform:uppercase}.col-lg-12.content-footer.text-left.footer-btns a.btn.price-list{background:#007bb5;border-color:#007bb5}.col-lg-12.content-footer.text-left.footer-btns a.btn img{height:12px;width:auto}#sidebar .list-filter.categories-list ul li a{border-radius:0;padding:3px 0;color:#4e575d;font-size:15px}#sidebar .list-filter.categories-list ul li a strong{color:#11215b}#sidebar .sidebar-modern-inner .block-content. categories-list{padding:15px 5px}.header-search{transition:all .3s ease-in-out 0s;display:none}.main-header.fixed .header-search{opacity:1;visibility:visible;transition:all .3s ease-in-out 0s}.main-header .header-search{transition:all .3s ease-in-out 0s;max-width:875px;width:100%;padding-left:5%!important}.header-search .search-row .col-xs-12 button strong{font-weight:400}.header-search #search{margin-bottom:0}.header-search .search-row .col-xs-12 input[type=text]{height:38px;padding-top:0;padding-bottom:0;border:1px solid #e3e3e3;font-size:14px;color:#000;border-radius:0;box-shadow:inherit!important}.header-search .search-row .col-xs-12 button.btn-search.btn-block{padding-top:0;padding-bottom:0;height:38px;background:#039eb5;border-radius:0 4px 4px 0!important;max-width:72px}.wide-intro #search{margin-bottom:0}.related-pro h3{font-size:14px;margin-top:10px;white-space:normal;line-height:normal;min-height:63px}.related-pro h3 a{color:#333;white-space:normal}.related-cate-block h2{padding:0;color:#1d0e48;margin-bottom:10px}.re-pic img{border:1px solid #fff;padding:2px;border-radius:3px}.main-header .search-row-wrapper.header-search{display:block;margin:0}.header.main-header .container{width:auto}.search-row-wrapper.header-search{background:0 0;padding:0}.search-row-wrapper.header-search .container div{padding-left:0;padding-right:0}.select2-container{z-index:0}.location-categories input#locSearch{border-radius:3px;border:1px solid #b7b7b7!important;box-shadow:inherit!important;background:#fdfdfd!important}.location-categories{background:#fff;padding:5px 5px 5px 15px;margin-bottom:12px;border-radius:4px}.location-categories .form-control{height:31px}.location-categories .category-links{margin-top:0;margin-bottom:0}.page-content .inner-box .title-2 a.pull-right{font-size:16px;text-transform:capitalize;color:#162e66}.category-list.make-list .listing-filter{display:none}.category-list.make-list .tab-box{display:none}.page-sidebar .sidebar-modern-inner .block-title{padding:4px 15px}.item-list .cornerRibbons{z-index:1}.field-col .error-message{color:#fff}.profile-form input{height:50px;margin-bottom:10px;padding-left:10px;padding-right:10px;width:100%;border-radius:20px;border:none;font-size:15px}.profile-form input[type=submit]{background:#dc0002;border:1px solid #dc0002;font-weight:700;color:#fff;font-size:16px}.profile-form textarea{height:90px;margin-bottom:10px;padding:15px;width:100%;border-radius:2px;border:none;font-size:15px}.profile-form.fixed-form h4{font-size:24px;margin:0;padding:10px 8px 10px 15px;background:#08407b;color:#fff}.profile-form{position:sticky!important;top:0}.listing-box-grid .pro-title{padding-bottom:0}.related-cate-inner{white-space:nowrap;overflow-y:auto;margin:0 0 15px;background:#fff;padding-top:5px;padding-bottom:5px;box-shadow:0 0 2px 0 #d2d2d2}.related-pro{max-width:189px;padding-left:5px;padding-right:5px;float:left}.related-pro-inner{border:solid 1px #e6e3e3;padding-left:5px;padding-right:5px}.help-block.listing-help-block{padding-top:0;background:#fff;padding-top:15px;padding-bottom:10px;margin-bottom:15px;border:solid 1px #f0f0f0;box-shadow:0 0 2px 0 #d2d2d2}.pk-login .modal-header{background:#039eb5;border-color:#039eb5;color:#fff}.pk-login .modal-dialog{width:auto;max-width:366px}.pk-login .btn.btn-default{background:#dc0002;border-color:#dc0002;height:39px;padding-top:0;padding-bottom:0;line-height:normal;color:#fff}.pk-login .btn.btn-success.pull-right{background:#039eb5;border-color:#039eb5;height:39px;padding-top:0;padding-bottom:0;line-height:normal;color:#fff}.pk-login .input-group-text{color:#fff;background-color:#039eb5;border:1px solid #039eb5}.pk-login .close{color:#fff!important;opacity:1}.pk-login p{margin-bottom:0}.wide-intro .search-row{max-width:640px}.categories-list ul li strong a{border-left:1px solid #dc0002;font-weight:400;padding-left:4px!important;color:#dc0002!important}#userOTP .modal-header{background:#039eb5;border-color:#039eb5;color:#fff}#userOTP .modal-dialog{width:auto;max-width:366px}#userOTP .btn.btn-default{background:#dc0002;border-color:#dc0002;height:39px;padding-top:0;padding-bottom:0;line-height:normal;color:#fff}#userOTP .btn.btn-success.pull-right{background:#039eb5;border-color:#039eb5;height:39px;padding-top:0;padding-bottom:0;line-height:normal;color:#fff}#userOTP .input-group-text{color:#fff;background-color:#039eb5;border:1px solid #039eb5}#userOTP .close{color:#fff!important;opacity:1}#userOTP p{margin-bottom:0}.header.main-header.fixed~#homepage .home-search .search-row{position:fixed;top:9px;z-index:11;max-width:48%;width:100%;left:0;right:0;box-shadow:0 0 3px 0 #ccc}.main-header .navbar.navbar-site{z-index:9999}.group-slider{margin-top:-20px;height:302px;overflow:hidden;width:100%}.f-p-slider{background:#f4f4f4;padding-left:15px;padding-right:15px;padding-top:30px;padding-bottom:30px}.f-p-slider h2{color:#333;margin-bottom:10px;font-size:30px}.group-slider .products-slider div img{width:100%;object-fit:cover;height:302px;filter:brightness(90%)}.pk-step-form .modal-content{border:none}.pk-step-form #msform .action-button{background:#dc0002!important;border-radius:3px;border-color:#dc0002!important;margin-left:0;margin-right:0}.company-name-top .item-location{background:#ed3237;color:#fff;padding:0 12px;display:block;border-radius:50px;font-size:16px}.company-profile-footer::before{height:500px;content:"";width:57%;background:#001d39;position:absolute;top:0;transform:rotate(67deg);left:-139px}.company-name-top .item-location i{color:#fff}.listing-box-grid .row{margin-top:0;margin-bottom:0}.fix-form-inner{padding:15px;background:#03bdd8;position:sticky;top:0;border-radius:3px}.profile-form.fixed-form .quick_query_form{position:sticky;top:0;background:#f5f5f5;padding:15px;margin-top:0}.pro-bottom .pro-title{padding-bottom:0}.pk-step-form .modal-header{background:#dc0002;border-color:#dc0002;color:#fff;margin-top:6%}.pk-step-form #msform fieldset{width:100%;margin:0 0;padding:20px 15px}.pk-step-form #msform{margin-top:0}.pk-step-form .row{margin-top:0}.pk-step-form .modal-dialog{width:450px;max-width:950px}.pk-step-form .form-group{margin-bottom:3px}.pk-step-form #msform input,.pk-step-form #msform select,.pk-step-form #msform textarea{border-radius:3px}.pk-step-form #msform{text-align:left}.pk-step-form #msform .col-md-12{padding-left:0;padding-right:0}.pk-step-form #msform .action-button-previous{background:#03b5c6}.no-result-search{font-size:17px}.main-container .container{max-width:90%!important}.logo-item{display:inline-block;width:23.5%;text-align:center;padding:15px;margin:0 .5% 1.2%;border-radius:5px;box-shadow:0 0 3px 0 #ccc}nav.search-breadcrumb>ol.breadcrumb{font-size:15px}.readmore+[data-readmore-toggle],.readmore[data-readmore]{font-size:15px}.detail-line-content{font-size:15px}.ads-details-wrapper .info-row{font-size:15px}.breadcrumb-item+.breadcrumb-item{font-size:15px}.f-icon{position:fixed;right:0;top:320px;z-index:111}.fi{height:52px;display:inline-block;width:52px;line-height:52px;text-align:center;color:#fff;font-size:22px}.fi::before{line-height:52px}.fib{display:flex;align-items:center}.f-c{background:#dc0002}.f-w{background:#16b853}.f-p{background:#5028e3}.fib a{display:inline-block;width:52px;height:52px;position:relative}.fib a.ic::before{content:"Call us"}.fib a.iw::before{content:"WhatsApp"}.fib a:hover::before{display:inline-block}.fib a::before{position:absolute;display:none;white-space:nowrap;background:#000;border-radius:4px;color:#fff;line-height:17px;padding:4px 15px;top:9px;height:25px;margin-left:4px;right:52px}@media (min-width:320px) and (max-width:800px){.page-nav .navbar.navbar-expand-lg{position:inherit}.col-sm-8.company-name-top{-ms-flex:0 0 85%;flex:0 0 85%;max-width:85%}.company-btns a.btn.btn-default{display:block}.page-nav{width:100%}.navbar-light .navbar-toggler{position:absolute!important;top:24px!important;right:20px!important}.col-sm-3.company-btns{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}label.t-menu{text-align:center;padding:12px 10px;cursor:pointer;font-size:15px;color:#444}input[type=checkbox]:checked#cssmenutoggle~.dropdown-menu{display:block!important}.hide-mobile{display:none}.company-about-left{padding-left:0;padding-right:0}}@media (min-width:320px) and (max-width:767px){.item-list .photobox .add-image img.img-thumbnail{width:100%}.col-sm-3.company-btns{-ms-flex:0 0 100%;flex:0 0 100%}label.t-menu{text-align:center;padding:12px 10px;cursor:pointer;font-size:15px;color:#444}input[type=checkbox]:checked#cssmenutoggle~.dropdown-menu{display:block!important}.hide-mobile{display:none}.company-about-left{padding-left:0;padding-right:0}.f-p-slider h2{font-size:23px}.m-footer.row .company-profile-copy .row{margin:0}.company-info{padding-left:0;padding-right:0}.page-nav{width:100%}.navbar-site.navbar .navbar-identity .navbar-toggler{margin-top:-15px!important;color:#fff!important}.listing-details-col{width:65%!important}.main-sidebar.mobile-filter-sidebar{display:none!important}.lis-contact-col a.btns{display:inline-block!important;max-width:133px!important;line-height:40px!important;height:40px!important}.listing-col-wrap{padding-right:0!important}.item-list .ads-details{padding:0 0 0}html body #wrapper{padding-top:0!important}.company-name-top{max-width:inherit!important;padding:15px 0 0 15px!important;position:inherit!important}.page-nav .navbar.navbar-expand-lg{position:inherit}.navbar-light .navbar-toggler{position:absolute!important;top:24px!important;right:20px!important}.company-btns{margin-top:0!important}.company-btns a.btn.btn-default{display:block}.h-left-b{border-right:none!important}#homepage .help-block .listing-counter{flex:0 0 100%;max-width:100%}#homepage .help-block .help-form{flex:0 0 100%;max-width:100%}.pk-step-form .modal-dialog{width:auto}
.category-links ul {
    color: #666;
    font-weight: 700;
    display: -webkit-box;
    overflow-x: scroll !important;
}


 .price-box.btns-info .btn.send_message{max-width:100%!important}.price-box.btns-info{padding-left:0;padding-right:0}.listing-container .item-list{padding:10px}.category-list .photobox{max-width:35%}.category-list:not(.make-grid):not(.make-compact) .col-md-3.price-box{width:100%;margin-top:5px;margin-bottom:10px}.category-list:not(.make-grid):not(.make-compact) .col-md-3.price-box{display:inline-block!important;-webkit-align-items:inherit!important;-moz-box-align:inherit!important;align-items:inherit!important;-webkit-flex-direction:inherit!important;-moz-box-orient:inherit!important;-moz-box-direction:inherit!important;flex-direction:inherit!important}body #wrapper{padding-top:109px!important}.name{width:auto;float:right}.price-box .name{width:49%}.price-box.btns-info .name a.btn{width:100%!important;max-width:100%!important}.price-box.btns-info a.view-website{margin-top:8px;padding-top:10px!important;padding-bottom:9px}.price-box .btn{padding:8px 10px}.main-logo{height:31px}.footer-btns a{display:block}.footer-btns{padding:15px!important}.content-footer .btn{margin-right:0}.content-footer .btn{margin-right:0;margin-bottom:10px}.company-name-top h1{line-height:normal!important;font-size:16px;margin-top:0;padding-bottom:4px;font-weight:400;color:#000}.company-about{margin-top:25px}.item-list{padding:0;margin-top:15px}.company-btns a.btn{width:auto}.company-btns{margin-top:12px}.related-pro-col{margin:0 0 16px}.c-address{display:none}.related-pro-bottom{display:none!important}body .price-box.btns-info .btn{padding:11px 10px!important;font-size:11px!important;width:49%!important;border-radius:50px;line-height:normal}.listing-name h5.add-title a{font-size:17px!important;color:#1e1e1e!important}.listing-name h5.add-title{font-weight:400}.company-name strong{font-size:13px;font-weight:400}.listing-name .info-row .item-location{display:block}.item-location a.info-link{font-size:13px;font-weight:400}.cornerRibbons{left:-16%;top:9%;width:198px}.company-logo{max-width:116px}.company-name-top{max-width:56%;padding:0}.company-name-top .info-row{font-size:13px}.company-profile-block{padding-top:10px;padding-bottom:10px}.company-btns a.btn.price-list{float:right}.company-btns a.btn{width:48%;margin-bottom:0}.company-btns{margin-top:12px;max-width:100%;width:100%}.page-info.page-info-lite.rounded{display:none}.listing-grid{padding-top:0}.company-logo img{height:auto}.location-categories .category-links ul{white-space:nowrap;overflow:scroll}.col-lg-12.content-footer.text-left.footer-btns a.btn{width:49%!important;display:inline-block;font-size:11px!important}.col-lg-12.content-footer.text-left.footer-btns a.btn img{height:10px;width:auto}.card-content.card-address a.btn{width:49%;font-size:11px!important;display:inline-block;margin:0}.card-content.card-address a.btn img{height:10px;width:auto}.card-address .card-body{display:none}.hidden-on-mobile{display:none}.navbar-site.navbar .navbar-identity{width:100%}.navbar-site.navbar .navbar-identity .btn,.navbar-site.navbar .navbar-identity .navbar-toggler{margin-top:7px;padding-left:0;padding-right:0;height:auto;border:none;color:#000}.location-categories{position:sticky;top:139px;z-index:6}.main-header .navbar-site.navbar .navbar-identity{height:46px!important}.main-header .btn.btn-primary.btn-search.btn-block{position:absolute;bottom:6px;right:0;max-width:72px}.related-pro{display:inline-flex!important;max-width:189px;padding-left:5px;padding-right:5px;float:none!important}.pk-login .modal-dialog{width:auto;max-width:298px;margin:1.75rem auto}#userOTP .modal-dialog{width:auto;max-width:298px;margin:1.75rem auto}.header.main-header.fixed~#homepage .home-search .search-row{position:inherit;top:inherit;z-index:inherit;max-width:100%;width:100%;left:inherit;right:inherit;box-shadow:inherit}.des-tab{border-right:none!important}.des-tab h2{margin-bottom:0!important}}@media (min-width:576px) and (max-width:767px){.categories-grid .col-md-3.col-sm-3.cate-col{flex:0 0 50%;max-width:50%}}@media (min-width:768px) and (max-width:916px){.main-logo{height:auto!important;width:132px!important}.navbar.navbar-site .navbar-identity a.logo{height:auto!important}.navbar.navbar-site ul.navbar-nav>li>a{padding:0 10px!important;height:auto!important}.requirement-form .field-col input[type=submit]{font-size:13px}}@media (min-width:768px) and (max-width:900px){.requirement-form h3{font-size:11px!important}.wide-intro h1{font-size:35px!important}.listing-counter h2{font-size:24px!important}.help-form h3{font-size:24px!important}.page-info.page-info-lite .iconbox-wrap{padding:5px!important}.iconbox h5{line-height:normal!important}.cate-box h3.main-cate{font-size:15px!important}.cate-sub ul li{line-height:20px!important}#homepage .wide-intro{height:170px!important;max-height:170px!important}.wide-intro h1{font-size:21px!important}.help-block .listing-counter{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%;margin-bottom:20px}.help-block .help-form{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}.categories-grid .cate-col{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}}@media (min-width:901px) and (max-width:1023px){.requirement-form h3{font-size:13px!important}.wide-intro h1{font-size:35px!important}.listing-counter h2{font-size:24px!important}.help-form h3{font-size:24px!important}.page-info.page-info-lite .iconbox-wrap{padding:5px!important}.iconbox h5{font-size:20px;line-height:normal!important}.cate-box h3.main-cate{font-size:15px!important}.cate-sub ul li a{font-size:13px!important}.cate-sub ul li{line-height:20px!important}#homepage .wide-intro{height:232px!important;max-height:232px!important}.wide-intro h1{font-size:26px!important}}@media (min-width:1024px) and (max-width:1199px){.col-md-3.col-sm-3.page-sidebar.mobile-filter-sidebar{-ms-flex:0 0 25%!important;flex:0 0 25%!important;max-width:25%!important}.col-md-9.col-sm-9.page-content.col-thin-left{-ms-flex:0 0 75%!important;flex:0 0 75%!important;max-width:75%!important}.side-banners.hidden-md{display:none}.requirement-form h3{margin-top:17px;font-size:14px}.related-pro{max-width:184px!important}.listing-counter h2{font-size:29px!important}.page-info.page-info-lite .iconbox-wrap{padding:5px!important}.iconbox h5{font-size:21px!important;line-height:normal!important}.requirement-form h3{font-size:14px!important}.cate-box h3.main-cate{font-size:15px!important}.cate-sub ul li a{font-size:13px!important}.cate-sub ul li{line-height:20px!important}#homepage .wide-intro{height:232px!important;max-height:232px!important}.wide-intro h1{font-size:26px!important}}@media (min-width:1200px){.container{max-width:90%!important}}.des-tab{border-right:1px solid #ccc}.des-tab table td{padding:4px 8px;vertical-align:top;background:#fff;border:1px solid #eee;font-size:13px}.des-tab h2{font-size:18px;font-weight:700;color:#000;line-height:1.22;margin-bottom:20px}.des-tab table{border:1px solid #ddd}.search-info{margin:8px 0 0;display:inline-block}.filter-cate-head img{width:auto;height:12px}.navbar.navbar-light .navbar-nav>li a.nav-link i{display:block}.navbar.navbar-light .navbar-nav>li a.nav-link{text-align:center}.page-nav .navbar.navbar-light .navbar-nav>li .nav-link:not(.btn),.page-nav .navbar.navbar-light .navbar-nav>li>a:not(.btn),.page-nav .navbar.navbar-site .navbar-nav>li .nav-link:not(.btn),.page-nav .navbar.navbar-site .navbar-nav>li>a:not(.btn){border-radius:3px;-moz-box-sizing:border-box;box-sizing:border-box;color:#444!important;font-size:15px!important;height:40px;line-height:1;padding:12px 10px}.h-left-b{float:left;border-right:1px solid;padding-right:14px}.page-nav{display:inline-block;margin-top:15px}body{background:#fff;color:#000;font-size:15px;font-family:Arial,helvetica neue,Helvetica,sans-serif;scroll-behavior:smooth}img,img a{border:0;outline:0}*{margin:0;padding:0}img{max-width:100%}a{text-decoration:none;outline:0}a:hover{text-decoration:none}li{list-style:none}ol,ul{list-style:none;margin:0}h1,h2,h3,h4,h5,h6{font-weight:400;margin:0;padding:0;line-height:1.2}.main-header{background:#41474e;transition:transform .2s ease-in-out;z-index:8;height:auto;position:sticky;width:100%;top:0;padding:0!important;font-family:Arial,helvetica neue,Helvetica,sans-serif;font-size:12px;display:block!important}.header-link{display:inline-block}.logo-header{margin-top:8px}.header-link i{display:block;line-height:normal;font-size:18px;padding-bottom:3px}.search-product-wrapper{text-align:center;background-color:#ccc;background-image:url(https://www.rednirusmart.com/images/bg-img.jpg);padding:45px;position:relative;height:230px;box-sizing:border-box;background-size:cover;background-position:100% 32%;overflow:hidden}.search-product-wrapper::after{background:#000;position:absolute;content:"";top:0;width:100%;height:230px;left:0;opacity:.3}.search-product h3{color:#f9f9f9;font-size:22px}.search-product{width:100%;margin:0 auto;position:relative;z-index:5}.search-area{margin-top:12px}.search-area>input[type=text]{max-width:472px;width:100%;padding:10px;font-size:15px;min-height:30px;border:none;margin:0 0 0 -4px;position:relative;top:0;height:55px;box-sizing:border-box}.main-content{background-color:#f3f3f3;width:100%;display:inline-block}.slider-inner img{width:100%}.section-bg{background:#fff;padding-top:15px;padding-bottom:15px;margin-top:3px;width:100%;display:inline-block}.category-thumbnail{width:100px;flex-shrink:0}.category-item{border-radius:3px;color:#333;display:flex;padding:12px 10px;border:1px solid #ebebeb;height:100%}.section-block{width:100%;display:inline-block;margin-bottom:15px}.requirement-form-wrap{margin-top:25px}.category-meta{padding-left:15px;width:100%}.category-item li{color:#666;font-size:14px;margin-bottom:6px}.category-item li a{color:#444;font-size:14px}.category-item li a:hover{text-decoration:underline}.category-item h3{font-size:16px;font-weight:700;margin-bottom:12px;padding-bottom:0}.category-item h3 a{color:#333;font-size:14px;margin-bottom:10px}.listing-grid .col-md-3{margin-top:15px}.listing-grid .col-md-4{padding-left:0;margin-top:15px}.category-item img{max-width:100px;max-height:100px;position:absolute;top:50%;left:15px;transform:translate(0,-50%);object-fit:contain}.listing-grid .section-bg{border-top:3px solid #091840}.listing-grid h2 a{color:#222}.requirement-form-inner{max-width:578px}.requirement-form-inner .form-control{margin-bottom:10px}.requirement-form-inner input.btn{background:#ed3237;color:#fff}.requirement-form-inner h2{font-size:26px;margin-bottom:10px}.city-col{-ms-flex-preferred-size:0;flex-basis:0;-webkit-box-flex:1;-ms-flex-positive:1;flex-grow:1;max-width:100%;margin:30px 0 15px;text-align:center;flex:0 0 16.5%}.top-cities-home img{height:75px;width:auto}.top-cities-home h3{font-size:18px;margin-top:11px}.listing-page-block .container-fluid{max-width:100%}.listing-details-col .listing-name h2{padding:0}.listing-img-block img.img-thumbnail{border:none}.listing-details-col .listing-name h2 a{color:#f12227;font-size:18px;font-weight:700;margin-bottom:0;line-height:normal}.listing-details-col .listing-name h2{line-height:21px}.item-list.listing-col-box:hover{background:#fff}.lis-contact-col a.btns.view-number{border:1px solid #dc0002;background:#dc0002;color:#fff}.lis-contact-col a.btns{display:block;margin:4px auto;border:1px solid #04b5c7;text-align:center;color:#fff;font-size:15px;line-height:45px;border-radius:5px;font-weight:400;cursor:pointer;bottom:10px;max-width:220px;width:100%;box-sizing:border-box;left:0;right:0;font-weight:400;outline:0;height:45px;outline:0;background:#0061ce;background:-webkit-gradient(linear,left top,left bottom,from(#3784da),to(#0061ce));background:#04b5c7}.listing-col-box{background:#fff;padding:0 15px;border:1px solid #b5b5b5;border-radius:5px;list-style:none;position:relative;overflow:hidden;margin-bottom:10px}.listing-img-block{padding-right:0;padding-top:10px;padding-bottom:10px}.listing-details-col{padding-top:10px;padding-bottom:10px}.lis-contact-col{padding-top:10px;background:#f3f3f3;padding-bottom:10px}.col-md-3.lis-contact-col h4{font-size:17px;font-weight:400}.col-md-3.lis-contact-col h4 a{color:#f12227;font-weight:700}.listing-address{color:#888;font-size:14px;margin-bottom:5px}.listing-img-inner{height:100%;border:1px solid #eee;display:flex;vertical-align:middle;align-content:center;align-items:center}.pro-thumb{max-width:72px;float:left;margin-right:10px}.pro-thumb img{max-width:100%;border:1px solid #ccc}.related-pro-des h4{font-size:14px;font-weight:400;color:#000;margin:0}.related-pro-des h4 a{font-size:13px;font-weight:700;color:#2c2c2c!important}.related-pro-des{padding-left:3px}.related-pro-des a.btn{background:#ed3237;border-color:#ed3237;font-family:arial;font-size:12px;font-weight:700;margin-top:5px;padding-top:3px;padding-bottom:3px;color:#fff;letter-spacing:1px}.related-pro-bottom{width:100%;display:inline-block;padding-top:5px;padding-bottom:5px;background:#ededed;margin-bottom:10px}.related-pro-col:last-child{border-right:none!important}.breadcrumb-wrap{margin:8px 0 4px;font-size:13px}.title-big h1{font-size:28px;margin-bottom:14px}.location-bar ul li{display:inline-block;padding-right:15px}.location-bar ul li a{display:inline-block;padding:5px;color:#333}.location-bar{background:#fff;padding:5px;margin-bottom:12px;border-radius:4px}.listing-col-wrap{padding-left:0}.location-bar{background:#fff;padding:5px 5px 5px 15px;margin-bottom:12px;border-radius:4px}.main-sidebar{padding-left:0}a.side-block-head{display:inline-block;width:100%;background:#4f6072;color:#fff;padding:7px 10px;border-radius:5px 5px 0 0}.location-bar input{height:31px;padding:0 12px;border:1px solid #ccc;margin-left:10px;border-radius:2px}.cate-img{float:left;margin-right:10px}.cate-col-inner h2{font-size:17px;font-weight:700;margin-bottom:7px}.cate-list ul li a{font-size:12px;color:#0061ce}.cate-list ul li a:hover{color:red}.cate-list ul li{margin-bottom:2px}.all-cate-wrap{background:#fff;padding:15px 0 0;display:inline-block;width:100%}.all-cate-wrap .row{margin-bottom:30px}.banner-text-list li{display:inline-block;max-width:83px;text-align:center}.banner-text-list li i{font-size:27px;color:#ed3237;margin-bottom:10px}.banner-text h2{margin-bottom:14px;font-size:2rem}.banner-text-list{padding-top:10px}.banner-text{vertical-align:middle;align-content:center;align-items:center;display:grid}.main-footer{background:#ccc;padding:30px 0 0;width:100%;display:inline-block}.service-txt{color:#333;font-size:12px}.service-iconb{text-align:center}.footer-services-sec h5{color:#fff}.footer-menu{margin-bottom:12px}.footer-copy{background:#cecece}.service-txt h3{font-size:18px;color:#222;font-weight:700;margin-bottom:10px;margin-top:0}.service-txt p{font-size:13px;color:#565656}.footer-col ul li{margin-bottom:8px}.footer-col ul li a{color:#8a97bd}.footer-col ul li a:hover{color:#fff}.copy-inner{padding:22px 0 13px;margin-top:20px}.bottom-links ul{margin:14px 0}.copy-inner p{margin:0;color:#3b3b3b;text-align:left}a.v-all-btn{background:#ed3237;position:absolute;bottom:20px;left:0;display:inline-block;padding:11px 24px;right:0;color:#fff;margin:auto;max-width:131px;text-align:center;border-radius:5px}.cate-left-img-inner::before{content:"";background-image:linear-gradient(to bottom,rgba(255,0,0,0),#000);opacity:.6;position:absolute;width:100%;left:0;right:0;height:100%;top:0}.cate-left-img-inner{position:relative;height:100%}.listing-details-block .container-fluid{max-width:100%}.listing-details-inner{background:#fff;margin-bottom:15px;margin-top:0;padding-top:15px;padding-bottom:15px}.listing-details-block .breadcrumb{margin-bottom:0}.listing-details-inner .price-item{margin-bottom:10px;display:inline-block;font-size:18px}.listing-details-table a.btn{max-width:200px;width:100%;height:55px;line-height:55px;padding:0;margin-right:10px;color:#fff}.listing-details-table .buttons-group{margin-top:20px;text-align:center}.listing-details-table table tr td{padding-top:6px;padding-bottom:6px}.small-logo img{border:1px solid #eee;padding:2px;background:#fff;box-shadow:0 0 5px 0 #d2d2d2;border-radius:3px;max-width:100%;height:100%;object-fit:scale-down}.small-logo{text-align:center;margin:-36px auto 0;max-width:80px;height:80px}.com-pro-inner{border:1px solid #eee}.name-com a{color:#222;font-size:19px;text-align:center;font-weight:700}.box-inner-padding{padding:10px;text-align:center}.add-com{font-size:13px;color:#959595}.com-pro-inner h2.name-com{margin-bottom:10px}a.view-mobile{padding:13px 27px;margin:14px 0 0!important}.cover-photo{height:70px;background:#eaf2fa}.box-inner-padding h5{font-size:13px;color:#aeaeae;margin-top:10px;margin-bottom:20px}.tab-item li a{font-size:22px;color:#222}.tab-item li{display:inline-block}.tab-item li a{font-size:22px;color:#222;display:inline-block;padding:8px 14px;line-height:normal}.tab-item{border-bottom:1px solid #ccc}.head-table{font-size:18px;font-weight:700;margin-bottom:10px;margin-top:25px}.pro-des-com-des{margin-top:35px}.listing-details-table ul li{list-style-type:circle;list-style-position:inside}.pro-des-com-des-left p{font-size:17px;line-height:28px}.pro-des-com-des-left ul li{font-size:17px;line-height:28px}.company-query .form-control{margin-bottom:10px}.company-query{position:sticky;top:60px;display:block;max-height:800px;z-index:3;border-left:30px solid #fff}.service-iconb i{font-size:63px;height:75px;line-height:75px;color:#8e8e8e}.footer-services-sec h5{margin-bottom:18px;padding-left:13px;font-size:18px}.service-wrap .footer-col{margin-bottom:20px}.bottom-links li{display:inline-block}.bottom-links li a{display:inline-block;color:#606060;font-size:13px;font-weight:200;padding:0 4px 0 0;text-decoration:none}.footer-services-sec{width:100%;display:inline-block;margin-top:24px}.bottom-links li a::after{content:"/";padding-left:6px;padding-right:3px;font-size:15px}.footer-text p{font-size:13px;font-weight:200;color:#606060;text-align:justify}.footer-text{clear:both;padding-top:20px}.footer-menu li a{display:inline-block;color:#333;font-size:14px;font-weight:300}.related-pro-col{border-right:3px solid #fff}.footer-menu li:first-child{padding-left:0}.footer-menu li{padding:5px 19px 10px;display:inline-block}#homepage.main-container{background-color:#f3f3f3;width:100%;display:inline-block}.wide-intro .intro-title{display:none}body #wrapper{padding-top:73.2px!important}.header.main-header .navbar.navbar-site{background:#00b5b7!important;border:none!important}.requirement-form{padding-top:10px;padding-bottom:10px}.requirement-form{display:none}.adds-wrapper{overflow:inherit!important}.cate-left-img-inner img{object-fit:cover;height:100%}.navbar.navbar-light .navbar-nav>li .nav-link:not(.btn),.navbar.navbar-light .navbar-nav>li>a:not(.btn),.navbar.navbar-site .navbar-nav>li .nav-link:not(.btn),.navbar.navbar-site .navbar-nav>li>a:not(.btn){border-radius:3px;-moz-box-sizing:border-box;box-sizing:border-box;color:#fff!important;font-size:12px;height:40px;line-height:1;padding:12px 10px}.box-inner-padding h5.title{margin-bottom:8px}.related-pro-col{display:flex}.readmore[data-readmore]{padding:6px 0 0}.col-9.service-txt h3 a{color:#333;font-size:13px}.col-9.service-txt h3 a:hover{color:red}.page-nav .navbar.navbar-light .navbar-nav>li{transition:all .3s ease-in-out 0s}@media (min-width:320px) and (max-width:767px){.header-right{display:none}.search-product-wrapper{height:auto}.city-col{-ms-flex-preferred-size:inherit;flex-basis:inherit;-webkit-box-flex:inherit;-ms-flex-positive:inherit;flex-grow:inherit;max-width:100%;margin:30px 0 15px;text-align:center;width:100%}.group-slider .products-slider div img{height:auto}.group-slider{height:auto}}@media (min-width:1700px) and (max-width:2400px){.cornerRibbons{left:-5%!important;top:10%!important;width:200px!important}}.listing-col-wrap ol,.listing-col-wrap ul{margin-left:10px}.listing-col-wrap ul li{list-style:disc}.listing-col-wrap ol li{list-style:auto}.listing-col-wrap .category-list.make-list table{width:100%!important}.modal-content{border:none}.left-logo{padding:30px 0}.left-logo a{font-size:25px;font-weight:700;padding:20px}.btn-success{background-color:#f2436d;border-color:#f2436d;box-shadow:1px 6px 20px #f2436d78;margin:14px 0;font-size:13px;padding:15px}.btn-success:hover{color:#fff;background-color:#4d5e6d;border-color:#4d5e6d}.btn-success2{background-color:#059543;border-color:#05ad4d;box-shadow:1px 6px 20px #2cd775ad;border-radius:30px;margin:14px 0;font-size:13px;padding:15px;color:#fff!important}.btn-success2:hover{box-shadow:1px 6px 20px #2cd775ad}.btn-success:not(:disabled):not(.disabled).active,.btn-success:not(:disabled):not(.disabled):active{color:#fff;background-color:#4d5e6d;border-color:#4d5e6d}.nav-tabs .nav-link{color:#000;background-color:#fff;border-radius:30px;padding:15px 5px;margin-bottom:10px;border:1px solid #2e3192}.bottom-links ul li a{color:#333}.box,.box2,.box3{background:#f2436d36;border-radius:30px;box-shadow:1px 0 20px #ebebeb;text-align:center;padding:49px 18px;height:200px;margin:10px 0}.box2{background:#4caf5059}.box3{background:#009ef730}.left-logo{padding:20px 0 0}.left-logo a{font-size:25px;font-weight:700;padding:20px}.header-data{margin-top:23px;text-align:center}.skin-blue a:focus,.skin-blue a:hover{color:#ed1111}.company-name{font-weight:600;vertical-align:middle;padding-top:20px}.navbar.navbar-site { position: fixed !important; }.navbar.navbar-site {border-bottom-width: 1px !important;border-bottom-style: solid !important;}.navbar.navbar-site { border-bottom-color: #e8e8e8 !important; }.make-grid .item-list { width: 25.00% !important; }@media (max-width: 767px) {.make-grid .item-list { width: 50% !important; }}.make-grid .item-list .cornerRibbons { left: -30.00%; top: 8%; }.make-grid.noSideBar .item-list .cornerRibbons { left: -22.00%; top: 8%; }@media (min-width: 992px) and (max-width: 1119px) {.make-grid .item-list .cornerRibbons { left: -36.00%; top: 8%; }.make-grid.noSideBar .item-list .cornerRibbons { left: -26.00%; top: 8%; }}@media (min-width: 768px) and (max-width: 991px) {.make-grid .item-list .cornerRibbons { left: -35.00%; top: 8%; }.make-grid.noSideBar .item-list .cornerRibbons { left: -25.00%; top: 8%; }}@media (max-width: 767px) {.make-grid .item-list { width: 50%; }}@media (max-width: 767px) {.make-grid .item-list .cornerRibbons, .make-grid.noSideBar .item-list .cornerRibbons { left: -10%; top: 8%; }}@media (max-width: 736px) {.make-grid .item-list .cornerRibbons, .make-grid.noSideBar .item-list .cornerRibbons { left: -12%; top: 8%; }}@media (max-width: 667px) {.make-grid .item-list .cornerRibbons, .make-grid.noSideBar .item-list .cornerRibbons { left: -13%; top: 8%; }}@media (max-width: 568px) {.make-grid .item-list .cornerRibbons, .make-grid.noSideBar .item-list .cornerRibbons { left: -14%; top: 8%; }}@media (max-width: 480px) {.make-grid .item-list .cornerRibbons, .make-grid.noSideBar .item-list .cornerRibbons { left: -22%; top: 8%; }}.adds-wrapper.make-grid .item-list:nth-child(4n+4),.category-list.make-grid .item-list:nth-child(4n+4) {border-right: solid 1px #ddd;}.adds-wrapper.make-grid .item-list:nth-child(3n+3),.category-list.make-grid .item-list:nth-child(3n+3) {border-right: solid 1px #ddd;}.adds-wrapper.make-grid .item-list:nth-child(4n+4),.category-list.make-grid .item-list:nth-child(4n+4) {border-right: none;}@media (max-width: 991px) {.adds-wrapper.make-grid .item-list:nth-child(4n+4),.category-list.make-grid .item-list:nth-child(4n+4) {border-right-style: solid;border-right-width: 1px;border-right-color: #ddd;}}.f-category h6 { color: #333; } .photo-count { color: #292b2c; } .page-info-lite h5 { color: #999999; } h4.item-price { color: #292b2c; } .skin-blue .pricetag { color: #fff; }.has-error .select2 { border: 1px solid #ee0979; }
.dropdown{position:relative;display:inline-block}.f-icon {display: none;}.dropdown-menu{display:none;position:absolute;background-color:#f1f1f1;min-width:160px;box-shadow:0 8px 16px 0 rgba(0,0,0,.2);z-index:1}.dropdown:hover .dropdown-menu{display:block}#userLogin{padding-top:77px}.input-group-text{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;padding:.675rem .75rem;font-size:1rem;font-weight:400;line-height:1.5;color:#495057;text-align:center;background-color:#e9ecef;border:1px solid #ced4da;border-radius:.25rem}.modal-footer .btn-default{margin-top:15px}.fa-user-plus:before{content: "\F234";}.select2-container--default .select2-selection--single,.select2-container--default .select2-selection--single .select2-selection__arrow{height:38px!important}.cookie-consent{font-size:14px;padding:16px;background:#f0f2f1;position:fixed;width:100%;bottom:0;z-index:1000}.cookie-consent,a.v-all-btn{text-align:center;right:0;left:0}.cookie-consent__message{color:#555}.cookie-consent__agree,.skin-blue .cookie-consent__agree{background-color:#4682b4;box-shadow:0 2px 5px rgb(70 130 180 / 15%)}.cookie-consent__agree{font-weight:700;margin:0 16px;padding:8px 16px;color:#fff2e0;border:0;border-radius:3px}.category-list .listing-filter,.category-list .tab-box{display:none}.search-row button.btn-search,.search-row-wrapper .form-control,.search-row-wrapper button.btn{font-size:16px}.form-control{border:1px solid #ddd;box-shadow:1px 1px 20px 0 #e8e8e8;display:block;width:100%;height:48px;padding:.5rem .75rem;font-size:1rem;line-height:1.25;color:#464a4c;background-color:#fff;background-image:none;background-clip:padding-box;border:none;border-radius:.2rem;-webkit-transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;-moz-transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;-o-transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out}.select2-container--default .select2-selection--single .select2-selection__rendered{line-height:38px!important}.modal-content{border:none;margin-top:150px}.modal-backdrop.show{opacity:.5}.modal-backdrop{z-index:1960!important;position:fixed;top:0;background-color:#000}.fade{transition:opacity .15s linear}.modal,.modal-backdrop{right:0;left:0;bottom:0}.catclose{padding:10px;margin-left:53%;float:left}.fa-caret-down::before { content: "\f0d7";}#browseAdminCities {  padding-top: 60px;}  .autocomplete-suggestions {	background: #fff;max-height: 30px;overflow-y: auto;border: solid 1px #ddd;border-radius: 3px;}.autocomplete-suggestions .autocomplete-suggestion {cursor: pointer;border-bottom: 1px solid #cccccc;padding: 10px 15px 10px 30px;position: relative;}.autocomplete-suggestions .autocomplete-suggestion:after {color: #949494;content: "\e8d4";font-family: fontello;	font-style: normal;font-weight: normal;left: 6px;margin: 0 0 0 10px;position: absolute;text-decoration: none;top: 10px;}.autocomplete-suggestions .autocomplete-suggestion:hover {background: #eeeeee;color: #222222;	cursor: pointer;display: block;	font-size: 13px;}
        </style>
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
<div class="f-icon">
	    <div class="fib"><a class="ic" href="tel:+919888885364"><i class="fi f-c fa fa-phone"></i></a></div>
	    <div class="fib"><a class="iw" href="https://wa.me/+919888885364/?text=Hello, How can Pharmafranchisemart help you?" target="_blank"><i class="fi f-w fab fa-whatsapp"></i></a></div>
	</div>
<?php
}
?>

<div id="wrapper">

	@section('header')
		@include('layouts.inc.header')
	@show



	@section('wizard')
	@show

	@if (isset($siteCountryInfo))
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
	@endif

	@yield('content')

	@section('info')
	@show

	@section('footer')
		@include('layouts.inc.footer')
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
		$('.share').ShareLink({
			title: '{{ addslashes(MetaTag::get('title')) }}',
			text: '{!! addslashes(MetaTag::get('title')) !!}',
			url: '{!! $fullUrl !!}',
			width: 640,
			height: 480
		});


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
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:3439548,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
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
