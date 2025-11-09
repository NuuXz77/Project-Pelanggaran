<?php

use Livewire\Volt\Component;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use App\Models\Aktivitas;

new #[Layout('layouts.guest')] class extends Component {
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public bool $isLoading = true; // Add loading state

    public function mount(): void
    {
        // Simulate loading for 1.5 seconds
        $this->isLoading = true;
        $this->dispatch('page-loaded');
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['required', 'boolean'],
        ];
    }

    public function login(): void
    {
        $this->validate();

        if (
            !Auth::attempt(
                [
                    'email' => $this->email,
                    'password' => $this->password,
                ],
                $this->remember,
            )
        ) {
            Session::flash('error', 'Email atau password yang Anda masukkan salah.');

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
                'password' => ' ',
            ]);
        }

        request()->session()->regenerate();
        $user = Auth::user();
        $user->status = 'Aktif';
        $user->save();

        Aktivitas::create([
            'ID_Akun' => $user->ID_Akun,
            'keterangan' => 'Login Berhasil!',
            'tanggal' => now()->toDateString(),
            'time' => now()->format('H:i:s'),
        ]);

        if ($user->role === 'kesiswaan') {
            $this->redirect(route('beranda'), navigate: true);
        } else {
            $this->redirect(route('input_pelanggar'), navigate: true);
        }
    }
}; ?>

<div>
    <!-- Loading Screen -->
    @if ($isLoading)
        <div
            class="fixed inset-0 bg-base-100 flex flex-col items-center justify-center z-50 transition-opacity duration-500">
            <div class="text-center">
                <x-loading class="loading-infinity loading-xl text-primary" />
                <p class="mt-4 text-lg font-medium">Memuat Sistem...</p>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div
        class="fixed inset-0 flex items-center justify-center bg-cover bg-center px-4 sm:px-6 transition-opacity duration-300 {{ $isLoading ? 'opacity-0' : 'opacity-100' }}">
        <div
            class="bg-neutral-400/20 backdrop-blur-[1px] border border-neutral-400/20 p-4 sm:p-6 rounded-lg shadow-lg w-full max-w-sm transform transition-all duration-300 hover:shadow-xl my-auto">

            <!-- Logo & Title -->
            <div class="flex flex-col items-center mb-4">
                <div class="mb-3">
                    <img src="{{ asset('image/logo_smea.jpg') }}" alt="Logo Sekolah"
                        class="h-16 sm:h-20 transition-transform duration-300 hover:scale-105">
                </div>
                <h1
                    class="text-xl sm:text-2xl font-bold text-center bg-gradient-to-r from-primary to-primary bg-clip-text text-transparent">
                    SISKA
                </h1>
                <p class="text-xs text-gray-500 mt-1">Sistem Informasi Kesiswaan</p>
            </div>

            <!-- Alert -->
            @if (session('error'))
                <x-alert title="Login Gagal" description="{{ session('error') }}"
                    class="alert-error mb-3 animate-fade-in" icon="o-exclamation-triangle" dismissible />
            @endif

            <!-- Form -->
            <x-form wire:submit="login" class="space-y-2 ">
                @csrf

                <x-input label="Email" wire:model="email" icon-right="o-envelope" placeholder="Masukkan email Anda"
                    class="input-bordered focus:input-primary" autofocus required />

                <x-password label="Password" wire:model="password" icon-right="o-lock-closed"
                    placeholder="Masukkan password Anda" class="input-bordered focus:input-primary" required />

                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <x-checkbox label="Ingat Saya" wire:model="remember" class="checkbox-primary" />
                </div>

                <x-button label="MASUK"
                    class="btn-primary w-full mt-2 py-2 sm:py-3 text-sm sm:text-lg font-semibold hover:bg-opacity-90 transition-all"
                    type="submit" spinner="login" icon-right="o-arrow-right" />
            </x-form>

            <!-- Footer -->
            <div class="mt-3 text-center text-xs text-gray-500">
                <p>© {{ date('Y') }} SMKN 1 CIAMIS. All rights reserved. | v0.3</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('page-loaded', () => {
                setTimeout(() => {
                    @this.set('isLoading', false);
                }, 1500);
            });
        });
    </script>
</div>
