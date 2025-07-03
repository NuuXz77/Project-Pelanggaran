<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Aktivitas;


new class extends Component {
    public function logout()
    {
        $user = Auth::user();
        $user->status = 'Tidak Aktif';
        $user->save();

        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        //untuk tb_log
        Aktivitas::create([
            'ID_Akun' => $user->ID_Akun,
            'keterangan' => 'Logout Berhasil!',
            'tanggal' => now()->toDateString(),
            'time' => now()->format('H:i:s'),
        ]);
        return redirect('/login');
    }
};
?>
<div>
    <x-menu-item title="Logout" wire:click.stop="logout" spinner="logout" icon="" />
</div>
