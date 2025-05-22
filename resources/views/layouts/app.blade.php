<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Dashboard')</title>

    <!-- Tailwind is included -->
    <link rel="stylesheet" href="{{ asset('template/css/main.css') }}">
    {{--
    <link rel="stylesheet" href="{{ secure_asset('template/css/main.css') }}"> --}}
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('template/img/logo.jpg') }}" />
    {{--
    <link rel="shortcut icon" type="image/x-icon" href="{{ secure_asset('template/img/logo.jpg') }}" /> --}}

    <link rel="mask-icon" href="{{ secure_asset('template/img/logo.jpg') }}" color="#00b4b6" />

    <meta name="description" content="Admin One - free Tailwind dashboard">

    <meta property="og:url" content="https://justboil.github.io/admin-one-tailwind/">
    <meta property="og:site_name" content="JustBoil.me">
    <meta property="og:title" content="Admin One HTML">
    <meta property="og:description" content="Admin One - free Tailwind dashboard">
    <meta property="og:image" content="https://justboil.me/images/one-tailwind/repository-preview-hi-res.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1920">
    <meta property="og:image:height" content="960">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="Admin One HTML">
    <meta property="twitter:description" content="Admin One - free Tailwind dashboard">
    <meta property="twitter:image:src" content="https://justboil.me/images/one-tailwind/repository-preview-hi-res.png">
    <meta property="twitter:image:width" content="1920">
    <meta property="twitter:image:height" content="960">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-130795909-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());
    gtag('config', 'UA-130795909-1');
    </script>

    <style>
        body {
            background-color: #f0f2f5;
        }
        .is-title-bar {
            background: linear-gradient(to right, #f8faff, #f0f2f5);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            border-radius: 12px 12px 0 0;
            margin-top: 1rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.02);
            position: relative;
            z-index: 1;
        }
        .is-hero-bar {
            background: linear-gradient(to right, #ffffff, #f8faff);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.2rem;
            position: relative;
            z-index: 1;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
            background: #fff;
            position: relative;
            z-index: 2;
        }
        .card:hover {
            box-shadow: 0 12px 30px rgba(67, 90, 205, 0.15);
            transform: translateY(-5px);
        }
        .card-header {
            background: linear-gradient(to right, #f8f9fa, white);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            border-radius: 12px 12px 0 0;
            position: relative;
        }
        .card-header:after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 3px;
            width: 100%;
            background: linear-gradient(90deg, #4527A0 0%, #673AB7 100%);
        }
        .menu-item-label {
            transition: all 0.3s ease;
        }
        .menu-list a:hover .menu-item-label {
            transform: translateX(3px);
        }
        .main-section {
            padding: 1.5rem;
            background-color: #f0f2f5;
            min-height: calc(100vh - 200px);
        }
        .title {
            color: #333;
            position: relative;
        }
        .title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #4527A0 0%, #673AB7 100%);
            border-radius: 3px;
            z-index: 1; /* Ensure it doesn't interfere */
        }
        
        /* Button styles */
        .button {
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .button.is-primary {
            background: linear-gradient(90deg, #4527A0 0%, #673AB7 100%);
            box-shadow: 0 4px 15px rgba(69, 39, 160, 0.3);
            border: none;
        }
        .button.is-primary:hover {
            box-shadow: 0 6px 18px rgba(67, 90, 205, 0.5);
            transform: translateY(-2px);
        }
        
        /* Scroll bar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #4527A0 0%, #673AB7 100%);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #3f219a 0%, #5d33a5 100%);
        }
        
        /* Table styling */
        .table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            position: relative;
            z-index: 5;
            background-color: #fff;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        .table th {
            background: #fff !important;
            border-bottom: 2px solid rgba(0,0,0,0.05);
            position: relative;
            z-index: 5;
            padding: 0.75rem 1rem;
        }
        .table td {
            background: #fff;
            position: relative;
            z-index: 5;
        }

        /* Fix for table gradient issues */
        .section.main-section {
            position: relative;
            padding: 1.5rem;
            background-color: #f0f2f5;
            min-height: calc(100vh - 200px);
            z-index: 5; /* Higher z-index */
            margin-top: 20px; /* Increased gap */
            border-top: 1px solid #f0f2f5;
        }
        
        /* Table container with stronger isolation */
        .table-container {
            position: relative;
            z-index: 10; /* Higher z-index */
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 1px; /* Add small padding to prevent border bleed */
        }
        
        /* Complete separation between sections */
        .is-hero-bar {
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            border-bottom: none;
        }
        
        /* Ensure title bar doesn't interfere */
        .is-title-bar {
            margin-bottom: 5px;
        }
        
        /* Spacer to ensure separation */
        .section-separator {
            height: 20px;
            background-color: #f0f2f5;
            position: relative;
            z-index: 4;
        }
        
        /* Restore content wrapper styling */
        .content-wrapper {
            position: relative;
            z-index: 5;
            background-color: transparent;
        }
    </style>
</head>

<body>

    <div id="app">
        @include('partials.navbar')
        @include('partials.sidebar')
        <section class="is-title-bar">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0 py-4 px-6">
                <ul class="flex items-center space-x-2">
                    <li>
                        @if (Auth::user()->role == "admin")
                        <span class="text-indigo-600 font-bold">Admin</span>
                        @else
                        <span class="text-indigo-600 font-bold">Guru</span>
                        @endif
                    </li>
                    <li class="text-gray-600">
                        <span class="icon text-xs"><i class="mdi mdi-chevron-right"></i></span>
                    </li>
                    <li class="text-gray-800 font-medium">@yield('title','Dashboard')</li>
                </ul>
                @yield('addBtn')
            </div>
        </section>
        <section class="is-hero-bar">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0 px-6">
                <h1 class="title text-2xl font-bold flex items-center" style="position: relative; z-index: 1;">
                    <span class="icon mr-3" style="background: linear-gradient(45deg, #4527A0, #673AB7); padding: 8px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        <i class="mdi mdi-view-dashboard text-white"></i>
                    </span>
                    <span>@yield('titleHeader', "Dashboard")</span>
                </h1>
                @yield('btnNew')
            </div>
        </section>
        <div style="height: 10px; background-color: #f0f2f5; position: relative; z-index: 3;"></div>
        <div class="section-separator"></div>
        <section class="section main-section">
            <div class="content-wrapper">
                <!-- 
                    Important: For proper table display without gradient bleed issues,
                    always wrap tables in a div with class "table-container" like this:
                    
                    <div class="table-container">
                        <table class="table">...</table>
                    </div>
                -->
                @yield('content')
            </div>
        </section>
    </div>
    @include('partials.footer')

    @include('sweetalert::alert')
    @include('partials.scripts')
    @stack('extraScript')
</body>

</html>