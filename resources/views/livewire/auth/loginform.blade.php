<?php

use Livewire\Volt\Component;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

new #[Layout('layouts.guest')] class extends Component {
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

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

        if (!Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            // Tambahkan session flash untuk error
            Session::flash('error', 'Email atau password yang Anda masukkan salah.');
            
            // Tetap lempar ValidationException untuk validasi form
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
                'password' => ' ' // Spasi untuk memicu error pada field password
            ]);
        }

        request()->session()->regenerate();

        $this->redirectIntended(default: route('beranda'), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-cover bg-center px-4 sm:px-6">
    <div class="bg-white p-6 sm:p-10 rounded-md shadow-lg w-full max-w-md">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('image/logo_smea.jpg') }}" alt="Logo Sekolah" class="h-24 sm:h-28">
        </div>
        <h1 class="text-xl sm:text-2xl font-semibold text-center mb-6">Kesiswaan</h1>

        <!-- Tambahkan tampilan untuk session flash message -->
        @if(session('error'))
            <x-alert title="Login Gagal" description="{{ session('error') }}" class="alert-error" icon="o-exclamation-triangle" dismissible />
        @endif

        <x-form wire:submit="login">
            @csrf

            <x-input label="Email" wire:model="email" icon-right="o-user" autofocus required />
            <x-password label="Password" wire:model="password" right required/>
            <x-checkbox label="Ingat Saya" wire:model="remember" />

            <x-button label="LOGIN" class="btn-primary w-full" type="submit" spinner="login" />
        </x-form>
    </div>
</div>