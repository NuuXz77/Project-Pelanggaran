<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('image/favicon.svg') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script type="module" src="https://cdn.jsdelivr.net/gh/lekoala/formidable-elements@master/dist/count-up.min.js">
    </script>
    @livewireStyles

</head>

<body class="min-h-screen font-sans antialiased">

    <body class="font-sans antialiased">

        {{-- The navbar with `sticky` and `full-width` --}}
        <x-nav sticky full-width>

            <x-slot:brand>
                {{-- Drawer toggle for "main-drawer" --}}
                <label for="main-drawer" class="lg:hidden mr-3">
                    <x-icon name="o-bars-3" class="cursor-pointer" />
                </label>

                {{-- Brand --}}
                <div class="flex items-center gap-2">
                    <img src="{{ asset('image/logo_smea.jpg') }}" alt="Logo Sekolah" width="25" />
                    <span class="font-semibold">SiTertib</span>
                    {{-- <h1>{{ auth()->user()->ID_Akun }}</h1> --}}
                </div>

            </x-slot:brand>

            {{-- Right side actions --}}
            @if ($user = auth()->user())
                <x-slot:actions>
                    <label class="swap swap-rotate w-10">
                        <!-- this hidden checkbox controls the state -->
                        <input type="checkbox" class="theme-controller" value="light" />

                        <!-- sun icon -->
                        <svg class="swap-off h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24">
                            <path
                                d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z" />
                        </svg>

                        <!-- moon icon -->
                        <svg class="swap-on h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24">
                            <path
                                d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z" />
                        </svg>
                    </label>
                    {{-- <h1>Hello {{auth()->user()->name}}</h1> --}}
                    <x-avatar :placeholder="collect(explode(' ', auth()->user()->name))
                        ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                        ->take(2)
                        ->implode('')" title="{{ auth()->user()->name }}"
                        subtitle="{{ auth()->user()->email }}" class="!w-10" />
                    <x-dropdown no-x-anchor right>
                        <x-slot:trigger>
                            <x-button icon="o-user" class="btn-circle btn-ghost" />
                        </x-slot:trigger>
                        {{-- <x-menu-item title="Profile" icon="o-user" /> --}}
                        {{-- <x-menu-item title="Login" icon="" /> --}}
                        <livewire:auth.logout>
                    </x-dropdown>
                </x-slot:actions>
            @endif
        </x-nav>

        {{-- The main content with `full-width` --}}
        </div>
        <x-main with-nav full-width class="drawer-content w-full mx-auto p-5 lg:px-10 lg:py-5">

            {{-- This is a sidebar that works also as a drawer on small screens --}}
            {{-- Notice the `main-drawer` reference here --}}
            {{-- Sidebar hanya ditampilkan jika user login --}}
            @if ($user = auth()->user())
                <x-slot:sidebar drawer="main-drawer" collapsible>

                    {{-- Menu berdasarkan role --}}
                    <x-menu activate-by-route>

                        {{-- Untuk semua role --}}

                        @if ($user->isKesiswaan())
                            <x-menu-item title="Beranda" icon="o-home" link="/" />
                            <x-menu-item title="Tata Tertib" icon="o-scale" link="/tata-tertib" />
                            <x-menu-item title="Tindakan" icon="o-user" link="/tindakan" />
                            <x-menu-item title="Pelanggaran" icon="o-user" link="/pelanggaran" />
                            {{-- <x-menu-item title="Data Guru" icon="o-academic-cap" link="/data-guru" /> --}}
                            <x-menu-item title="Data Kelas" icon="o-academic-cap" link="/data-kelas" />
                            <x-menu-item title="Data Siswa/i" icon="o-user-group" link="/data-siswa" />
                            <x-menu-sub title="Manajemen Akun" icon="o-cog-6-tooth">
                                <x-menu-item title="PKS" icon="o-user" link="/akun-pks" />
                                <x-menu-item title="BK" icon="o-user" link="/akun-bk" />
                                <x-menu-item title="Guru" icon="o-user" link="/akun-guru" />
                            </x-menu-sub>
                            <x-menu-item title="Log Aktivitas" icon="o-archive-box" link="/log-aktivitas" />
                        @endif

                        @if ($user->isBK())
                            <x-menu-item title="Input Pelanggaran" icon="o-pencil-square" link="/input-pelanggar" />
                        @endif

                        @if ($user->isGuru())
                            <x-menu-item title="Input Pelanggaran" icon="o-pencil-square" link="/input-pelanggar" />
                            {{-- <x-menu-item title="Data Siswa/i" icon="o-user-group" link="/data-siswa" /> --}}
                        @endif

                        @if ($user->isPKS())
                            {{-- <x-menu-item title="Beranda" icon="o-home" link="/" /> --}}
                            <x-menu-item title="Input Pelanggaran" icon="o-pencil-square" link="/input-pelanggar" />
                            {{-- <x-menu-item title="Data Pelanggar" icon="o-archive-box" link="/pelanggar" /> --}}
                        @endif

                    </x-menu>
                </x-slot:sidebar>
            @endif

            {{-- The `$slot` goes here --}}
            <x-slot:content class="bg-base-300">
                {{ $slot }}
            </x-slot:content>
        </x-main>

        {{--  TOAST area --}}
        <x-toast />
        @livewireScripts
    </body>

</html>
