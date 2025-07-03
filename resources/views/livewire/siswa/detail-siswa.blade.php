<div class="bg-base-100 p-6 rounded-lg shadow-sm breadcrumbs text-sm">
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="/"><x-icon name="o-home" /> Beranda</a></li>
            <li><a href="/data-siswa"><x-icon name="o-user-group" /> Data Siswa</a></li>
            <li><x-icon name="o-user" /> Detail Siswa</li>
        </ul>
    </div>
    <x-header title="Detail Siswa" subtitle="Informasi lengkap siswa dan pelanggaran" separator />

    <!-- Card Data Siswa -->
    <x-card class="mb-6 bg-base-300">
        <x-slot:title>
            <div class="flex justify-between items-center">
                <span>Data Siswa</span>
                <x-badge value="{{ $siswa->kelas->kelas }}" class="badge-primary badge-soft" />
            </div>
        </x-slot:title>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-stat title="NIS" value="{{ $siswa->nis }}" />
            <x-stat title="Nama Siswa" value="{{ $siswa->nama_siswa }}" />
            <x-stat title="Kelas" value="{{ $siswa->kelas->kelas . ' - ' . $siswa->kelas->jurusan}}" />
            <x-stat title="Total Pelanggaran" value="{{ $siswa->total_pelanggaran }}" />
        </div>
    </x-card>

    <!-- Daftar Pelanggaran -->
    <x-card class="bg-base-300">
        <x-slot:title>
            <div class="flex justify-between items-center">
                <span>Daftar Pelanggaran</span>
                <x-badge value="{{ count($pelanggarans) }} Pelanggaran" class="badge-error badge-soft" />
            </div>
        </x-slot:title>
        
        @if(count($pelanggarans) > 0)
            <x-table :headers="[
                ['key' => 'created_at', 'label' => 'Tanggal'],
                ['key' => 'tingkat_pelanggaran', 'label' => 'Tingkat'],
                ['key' => 'pelanggaran', 'label' => 'Pelanggaran'],
                ['key' => 'tindakan', 'label' => 'Tindakan'],
                ['key' => 'deskripsi_pelanggaran', 'label' => 'Deskripsi']
            ]" :rows="$pelanggarans" />
        @else
            <x-alert icon="o-exclamation-triangle" class="alert-info">
                Siswa ini belum memiliki catatan pelanggaran.
            </x-alert>
        @endif
    </x-card>

    <div class="mt-6">
        <x-button label="Kembali" icon="o-arrow-uturn-left" link="/data-siswa" class="btn-neutral" />
    </div>
</div>