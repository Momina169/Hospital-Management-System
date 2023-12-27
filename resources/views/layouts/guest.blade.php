<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    Scripts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preload" as="style" href="{{ secure_asset('build/assets/app-e802707b.css') }}" />
    <link rel="stylesheet" href="{{secure_asset('/css_bootstrap.min.css')}}">
    <link rel="manifest" href="{{ secure_asset('build/manifest.json') }}">
    <script rel="jquery" src="{{ secure_asset('/jquery_3.6.4_jquery.min.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    Secure Links
    <link rel="preload" as="style" href="{{ secure_asset('build/assets/app-e802707b.css') }}" />
    <link rel="modulepreload" href="{{ secure_asset('build/assets/app-4a08c204.js') }}" />
    <link rel="stylesheet" href="{{ secure_asset('build/assets/app-e802707b.css') }}" />
    <script type="module" src="{{ secure_asset('build/assets/app-4a08c204.js') }}"></script>
    <link rel="stylesheet" href="{{ secure_asset('css_bootstrap.min.css') }}"> -->

</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div>
            <a href="{{route('welcome')}}">
                <img src="{{secure_asset('images/logo.png')}}" width="150px" height="auto">

            </a>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>