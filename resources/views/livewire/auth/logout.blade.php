<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public function logout()
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login'); // Ganti dengan route login-mu jika berbeda
    }
};
?>
<div>
    <x-menu-item title="Logout" wire:click.stop="logout" spinner="logout" icon="" />
</div>
