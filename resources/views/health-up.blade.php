<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} &dash; @yield('title')</title>
    @vite(['resources/css/app.css'])
</head>

<body class="antialiased">
    <div
        class="sm:flex sm:justify-center sm:items-center selection:bg-red-500 selection:text-white relative min-h-screen bg-gray-100">
        <div class="sm:w-3/4 xl:w-1/2 w-full p-6 mx-auto">
            <div
                class="from-gray-700/50 via-transparent shadow-gray-500/20 focus:outline focus:outline-2 focus:outline-red-500 flex items-center px-6 py-4 bg-white rounded-lg shadow-2xl">
                <div class="relative flex w-3 h-3">
                    <span
                        class="animate-ping absolute inline-flex w-full h-full bg-green-400 rounded-full opacity-75"></span>
                    <span class="relative inline-flex w-3 h-3 bg-green-400 rounded-full"></span>
                </div>

                <div class="ml-6">
                    <h2 class="text-xl font-semibold text-gray-900">Application up</h2>

                    <p class="dark:text-gray-400 mt-2 text-sm leading-relaxed text-gray-500">
                        HTTP request received.

                        @if (defined('LARAVEL_START'))
                            Response successfully rendered in {{ round((microtime(true) - LARAVEL_START) * 1000) }}ms.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
