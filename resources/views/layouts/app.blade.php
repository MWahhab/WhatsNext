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

    <!-- Styles -->
    <style>
        body {
            font-family: 'figtree', sans-serif;
            background-color: #1a202c; /* Dark blue background */
            color: #e2e8f0; /* Light text color */
        }

        .bg-white {
            background-color: #2d3748; /* Darker blue for headers */
            color: #cbd5e0; /* Light text color for headers */
        }

        .bg-gray-100 {
            background-color: #2d3748; /* Darker blue for content background */
            color: #e2e8f0; /* Light text color for content */
        }

        .dark\:bg-gray-900 {
            background-color: #1a202c; /* Dark blue background for dark mode */
            color: #e2e8f0; /* Light text color for dark mode */
        }

        .dark\:bg-gray-800 {
            background-color: #2d3748; /* Darker blue for dark mode headers */
            color: #cbd5e0; /* Light text color for dark mode headers */
        }

        .dark\:text-gray-400 {
            color: #a0aec0; /* Light gray text color for dark mode */
        }

        .dark\:border-gray-600 {
            border-color: #4a5568; /* Dark gray border color for dark mode */
        }

        /* Specific styles for links in dark mode */
        .dark\:text-gray-400 {
            color: #a0aec0; /* Light gray text color */
        }

        .dark\:text-gray-400:hover {
            color: #cbd5e0; /* Light text color on hover */
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased dark:bg-gray-900">
<div class="min-h-screen">
    @include('layouts.navigation')

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="fixed bottom-0 left-0 z-20 w-full p-4 bg-white dark:bg-gray-800 border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:border-gray-600">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
                © 2023 <a href="https://flowbite.com/" class="hover:underline">Flowbite™</a>. All Rights Reserved.
            </span>
        <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
            <li>
                <a href="#" class="hover:underline me-4 md:me-6 dark:hover:text-gray-200">About</a>
            </li>
            <li>
                <a href="#" class="hover:underline me-4 md:me-6 dark:hover:text-gray-200">Privacy Policy</a>
            </li>
            <li>
                <a href="#" class="hover:underline me-4 md:me-6 dark:hover:text-gray-200">Licensing</a>
            </li>
            <li>
                <a href="#" class="hover:underline dark:hover:text-gray-200">Contact</a>
            </li>
        </ul>
    </footer>
</div>
</body>
</html>
