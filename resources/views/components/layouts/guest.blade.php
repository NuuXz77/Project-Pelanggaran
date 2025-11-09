<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('image/favicon.svg') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="overflow-hidden">
    <!-- Enhanced Background with Multiple Layers -->
    <div class="fixed inset-0 -z-10 w-full h-full overflow-hidden">
        <!-- Base Gradient -->
        <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-gray-950 via-slate-900 to-gray-950"></div>
        
        <!-- Animated Gradient Orbs -->
        <div class="absolute top-0 -left-4 w-72 h-72 bg-primary rounded-full mix-blend-multiply filter blur-xl opacity-50 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-slate-600 rounded-full mix-blend-multiply filter blur-xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-gray-700 rounded-full mix-blend-multiply filter blur-xl opacity-50 animate-blob animation-delay-4000"></div>
        
        <!-- Subtle Grid Pattern -->
        <div class="absolute inset-0 w-full h-full opacity-5"
            style="background-image: 
                linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
                background-size: 50px 50px;">
        </div>
        
        <!-- Radial Overlay for Depth -->
        <div class="absolute inset-0 w-full h-full bg-gradient-radial from-transparent via-gray-950/60 to-black"></div>
        
        <!-- Dark Vignette Effect -->
        <div class="absolute inset-0 w-full h-full bg-black/30"></div>
    </div>

    <style>
        @keyframes blob {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            25% {
                transform: translate(20px, -50px) scale(1.1);
            }
            50% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            75% {
                transform: translate(50px, 50px) scale(1.05);
            }
        }
        
        .animate-blob {
            animation: blob 7s infinite;
        }
        
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        
        .bg-gradient-radial {
            background: radial-gradient(circle at center, var(--tw-gradient-stops));
        }
    </style>


    {{-- NAVBAR mobile only --}}
    {{-- <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-app-brand />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav> --}}

    {{-- MAIN --}}
    <x-main class="z-0">

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />

</body>

</html>
