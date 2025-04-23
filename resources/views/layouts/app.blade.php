<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Dashboard')</title>

    <!-- Tailwind is included -->
    <link rel="stylesheet" href="{{ asset('template/css/main.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('template/img/logo.jpg') }}" />

    {{--
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset(' template/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(' template/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset( 'template/apple-touch-icon.png') }}" /> --}}
    <link rel="mask-icon" href="{{ asset('template/img/logo.jpg') }}" color="#00b4b6" />

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

</head>

<body>

    <div id="app">
        @include('partials.navbar')
        @include('partials.sidebar')
        <section class="is-title-bar">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
                <ul>
                    <li>
                        @if (Auth::user()->role == "admin")
                        Admin
                        @else
                        Guru
                        @endif
                    </li>
                    <li>@yield('title','Dashboard')</li>
                </ul>
                @yield('addBtn')
            </div>
        </section>
        <section class="is-hero-bar">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
                <h1 class="title">
                    @yield('titleHeader', "Dashboard")
                </h1>
                @yield('btnNew')
            </div>
        </section>
        <section class="section main-section">
            @yield('content')
        </section>
    </div>
    @include('partials.footer')

    @include('sweetalert::alert')
    @include('partials.scripts')
    @stack('extraScript')
</body>

</html>