<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!--  Title -->
    <title>{{ getDomain() }} - {{ DataSite('title') }}</title>
    <!--  Required Meta Tag -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="{{ DataSite('description') }}" />
    <meta name="keywords" content="{{ DataSite('keyword') }}" />
    <meta name="title" content="{{ DataSite('title') }}" />
    <meta property="og:image" content="{{ DataSite('image_seo') }}" />
    <meta name="robots" content="index, follow" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (Auth::check())
        <meta name="api-token" content="{{ Auth::user()->api_token }}" />
    @endif
    <!--  Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ DataSite('favicon') }}" />
    <!-- Owl Carousel  -->
    <link rel="stylesheet" href="/dist/libs/owl.carousel/dist/assets/owl.carousel.min.2.delay">
    {{-- <link rel="stylesheet" href="/dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min"> --}}
    <link href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-html5-2.3.6/r-2.4.1/datatables.min.css" rel="stylesheet" />
    @yield('css')
    <!-- Core Css -->
    <link id="themeColors" rel="stylesheet" href="/dist/css/style.min.css" />
    <link rel="stylesheet" href="/assets/css/lbd.css?time={{ time() }}">
    <link href=" https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    
    {!! DataSite('script_header') !!}
</head>

<body>

    <!-- Preloader -->
    <div class="preloader">
        <img src="{{ DataSite('favicon') }}" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!-- Preloader -->
    <div class="preloader">
        <img src="{{ DataSite('favicon') }}" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
