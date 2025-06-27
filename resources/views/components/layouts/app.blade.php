<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
</head>

<body class="min-h-screen font-sans antialiased bg-white-3">

    <body class="font-sans antialiased">

        {{-- The navbar with `sticky` and `full-width` --}}
        <x-nav sticky full-width class="bg-white">

            <x-slot:brand>
                {{-- Drawer toggle for "main-drawer" --}}
                <label for="main-drawer" class="lg:hidden mr-3">
                    <x-icon name="o-bars-3" class="cursor-pointer" />
                </label>

                {{-- Brand --}}
                <div>App</div>
            </x-slot:brand>

            {{-- Right side actions --}}
            @if ($user = auth()->user())
                <x-slot:actions>
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
                        <x-menu-item title="Profile" icon="o-user" />
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
                <x-slot:sidebar drawer="main-drawer" collapsible class="bg-white">

                    {{-- Menu berdasarkan role --}}
                    <x-menu activate-by-route>

                        {{-- Untuk semua role --}}
                        <x-menu-item title="Beranda" icon="o-home" link="/" />

                        @if ($user->isKesiswaan())
                            <x-menu-item title="Tata Tertib" icon="o-document" link="/tata-tertib" />
                            <x-menu-item title="Tata Tertib" icon="o-document" link="/tindakan" />
                            <x-menu-item title="Data Kelas" icon="o-academic-cap" link="/data-kelas" />
                            <x-menu-item title="Siswa/i" icon="o-user-group" link="/siswa-melanggar" />
                            <x-menu-sub title="Manajemen Akun" icon="o-cog-6-tooth">
                                <x-menu-item title="BK" icon="o-user" link="/bk-area" />
                                <x-menu-item title="PKS" icon="o-user" link="/input-pelanggaran" />
                                <x-menu-item title="Guru" icon="o-user" link="/data-kelas" />
                            </x-menu-sub>
                            <x-menu-item title="Log Aktivitas" icon="o-archive-box" link="/log-aktivitas" />
                        @endif

                        @if ($user->isBK())
                            <x-menu-item title="Tata Tertib" icon="o-document" link="/tata-tertib" />
                            <x-menu-item title="BK Area" icon="o-user-group" link="/bk-area" />
                        @endif

                        @if ($user->isGuru())
                            <x-menu-item title="Data Kelas" icon="o-academic-cap" link="/data-kelas" />
                            <x-menu-item title="Siswa Melanggar" icon="o-user" link="/siswa-melanggar" />
                        @endif

                        @if ($user->isPKS())
                            <x-menu-item title="Input Pelanggaran" icon="o-pencil-square" link="/input-pelanggar" />
                            {{-- <x-menu-item title="Data Pelanggar" icon="o-archive-box" link="/pelanggar" /> --}}
                        @endif

                    </x-menu>
                </x-slot:sidebar>
            @endif

            {{-- The `$slot` goes here --}}
            <x-slot:content>
                {{ $slot }}
            </x-slot:content>
        </x-main>

        {{--  TOAST area --}}
        <x-toast />
    </body>

</html>
