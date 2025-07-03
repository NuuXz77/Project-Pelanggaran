<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\Siswa;

class DetailSiswa extends Component
{
    public $siswa;
    public $pelanggarans;

    public function mount($siswa)
    {
        $this->siswa = Siswa::with(['kelas', 'pelanggaran.peraturan'])
            ->findOrFail($siswa);

        $this->pelanggarans = $this->siswa->pelanggaran;
    }

    public function render()
    {
        return view('livewire.siswa.detail-siswa');
    }
}
