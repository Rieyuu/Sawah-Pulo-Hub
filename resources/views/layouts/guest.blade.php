<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div class="flex flex-col items-center gap-2">
                <a href="/" class="flex flex-col items-center group">
                    <x-application-logo class="w-20 h-20 shadow-lg shadow-emerald-500/10 rounded-3xl p-2 bg-white dark:bg-slate-900 transition-transform group-hover:scale-105" />
                    <span class="text-2xl font-black tracking-wider bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent mt-3 uppercase">
                        Sawah Pulo
                    </span>
                    <span class="text-xs px-2.5 py-0.5 font-semibold bg-emerald-100 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 rounded-full mt-1">HUB</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
