<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ url(config('app.favicon', 'favicon.ico')) }}" type="image/x-icon">
    <link rel="icon" href="{{ url(config('app.favicon', 'favicon.ico')) }}" type="image/x-icon">
    @stack('title')
    @stack('before_styles')
    {{--    {!! SEO::generate(true) !!} --}}

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <link href="{{ asset('assets_v4/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_v4/css/themes/default.css') }}" rel="stylesheet">
    @stack('after_styles')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--    {!! \App\Colombo\Ads\AdsManager::getAutoAds() !!} --}}
    <script src="{{ asset('assets_v4/js/app.js') }}" defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap');

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            font-size: 16px;
            color: var(--color-default);
        {{ \Illuminate\Support\Facades\Route::is('document.detail') ? 'background-color: var(--color-main-background);' : 'background-color: white;'}}



        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-SQQQSRDV4E"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-SQQQSRDV4E');
    </script>
</head>

<body x-data="globals" class="flex flex-col w-screen h-screen">

@include('frontend_v4.inc.main_header')

@if(session()->has('common_success'))
    <div class="text-sm md:text-base font-normal bg-primary-100 text-primary-darker px-4 py-2 text-center container mx-auto my-3 rounded-1.5lg" onclick="this.remove()">
        {{ session()->get('common_success') }}
    </div>
@endif

@if(session()->has('common_error'))
    <div class="text-sm md:text-base font-normal bg-red-100 text-red-500 px-4 py-2 text-center container mx-auto my-3 rounded-1.5lg" onclick="this.remove()">
        {{ session()->get('common_error') }}
    </div>
@endif

@include('frontend_v4.inc.sidebar')


<div :class="open_comment_responsive && 'bg-white'"
     class="flex-1 overflow-x-hidden lg:scrollbar scrollbar-w-3 scrollbar-thumb-rounded-lg scrollbar-thumb-gray-500 scrollbar-track-gray-300 scroll-smooth"
     id="content">
    <main class="w-full" :class="open_comment_responsive && 'h-full'">
        @yield('content')
    </main>
    @include('frontend_v4.inc.footer')
</div>
<div id="back-to-top" @click="backToTop"
     class="hidden md:flex cursor-pointer fixed bottom-10 right-10 h-12 w-12 rounded-full border border-solid border-slate-300 bg-primary items-center justify-center hover:bg-primary-darker shadow-around">
    <i class="fa-solid fa-up-long text-white"></i>
</div>

@stack('before_scripts')

@stack('after_scripts')

<!-- Client IP {{ \Request::ip() }} -->

{{--<script>--}}
{{--    var msg = '{{Session::get('common_error')}}';--}}
{{--    var exist = '{{Session::has('common_error')}}';--}}
{{--    if (exist) {--}}
{{--        window.setTimeout(function() {--}}
{{--            alert(msg);--}}
{{--        }, 500);--}}
{{--    }--}}
{{--</script>--}}
</body>

</html>
