<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- <title>@if(View::hasSection('meta_title')) @yield('meta_title') @else Top Pharma Franchise Company India | PCD Pharma Franchise Price List @endif</title>

<meta name="title" content="@if(View::hasSection('meta_title')) @yield('meta_title') @else Top Pharma Franchise Company India | PCD Pharma Franchise Price List @endif">
<meta name="keywords" content="@if(View::hasSection('meta_keywords')) @yield('meta_keywords') @else Top Pharma Franchise Company India,PCD Pharma Franchise Price List @endif">
<meta name="description" content="@if(View::hasSection('meta_description')) @yield('meta_description') @else Are you Looking for Best Pharma Franchise Company? Get in touch with us for 50+ PCD Pharma Franchise Company as well as third party mfg companies in India. @endif"> --}}

<meta property="og:title" content="@yield('meta_title')" />
<meta property="og:description" content="@yield('meta_description')" />
<link rel="icon" type="image/x-icon" href="{{ \Storage::url(config('settings.app.favicon')) . getPictureVersion() }}">
<link rel="icon" type="image/x-icon" href="/assets/img/jenus.jpg">

<meta property="og:url" content="{{ Request::fullUrl() }}" />

<meta property="og:type" content="website" />
<meta property="og:locale" content="en_GB" />
<meta name="google-site-verification" content="nnqCRSzVzF82-n3JEq7p4APu2qrddmykGXiJq_Qc0bI" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:description" content="@yield('meta_description')" />
<meta name="twitter:title" content="@yield('meta_title')" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Gentium+Book+Plus:ital@1&family=Montserrat&family=Poppins&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com/">
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link
  href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap"
  rel="stylesheet">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

      <link href="/assets/css/font-awesome-all.css" rel="stylesheet">
      <link href="/assets/css/flaticon.css" rel="stylesheet">
      <link href="/assets/css/owl.css" rel="stylesheet">
      <link href="/assets/css/bootstrap.css" rel="stylesheet">

      <link href="assets/js/owl.carousel.min.js" rel="stylesheet">

      <link href="/assets/css/jquery.fancybox.min.css" rel="stylesheet">
      <link href="/assets/css/animate.css" rel="stylesheet">
      <link href="/assets/css/color.css" rel="stylesheet">
      <link href="/assets/css/jquery-ui.css" rel="stylesheet">

      <link href="/assets/css/responsive.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />


      <link rel="stylesheet" href="/home/plugins/font-awesome/css/font-awesome.min.css">
  <!--<link rel="stylesheet" href="fonts/Linearicons/Font/demo-files/demo.css">--->
  <link rel="preconnect" href="https://fonts.gstatic.com/">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Jost:400,500,600,700&amp;display=swap&amp;ver=1607580870">
  <link rel="stylesheet" href="/home/plugins/bootstrap4/css/bootstrap.min.css">
  <link rel="stylesheet" href="/home/plugins/owl-carousel/assets/owl.carousel.css">
  <link rel="stylesheet" href="/home/plugins/slick/slick/slick.css">
  <link rel="stylesheet" href="/home/plugins/lightGallery/dist/css/lightgallery.min.css">
  <link rel="stylesheet" href="/home/plugins/jquery-bar-rating/dist/themes/fontawesome-stars.css">
  <link rel="stylesheet" href="/home/plugins/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="/home/plugins/lightGallery/dist/css/lightgallery.min.css">
  <link rel="stylesheet" href="/home/plugins/noUiSlider/nouislider.css">
  <link rel="stylesheet" href="/home/css/style.css">

  <link rel="stylesheet" href="/home/css/home-1.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.3.0/animate.css">






      <style>

      /* .container-fluid {
          visibility: hidden;
        } */

        .requirement-form {
          visibility: hidden;
          max-height: 0px !important;
        }

        .all-states ul li {

            white-space: nowrap;
          }

      /* The Modal (background) */
     .tmodal {
       display: none; /* Hidden by default */
       position: fixed; /* Stay in place */
       z-index: 1; /* Sit on top */
       left: 0;
       top: 0;
       width: 100%; /* Full width */
       overflow: auto; /* Enable scroll if needed */
       background-color: rgb(0,0,0); /* Fallback color */
       background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
     }

     /* Modal Content/Box */
     .tmodal-content {

       margin: 15% auto; /* 15% from the top and centered */
       padding: 20px;
       border: 1px solid #888;
       width: 60%; /* Could be more or less, depending on screen size */
     }

     /* The Close Button */
     .tcloseModal {
       color: #aaa;
       float: right;
       font-size: 28px;
       font-weight: bold;
     }

     .tcloseModal:hover,
     .tcloseModal:focus {
       color: black;
       text-decoration: none;
       cursor: pointer;
     }


      </style>
