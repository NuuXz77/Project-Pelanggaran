<div class="bg-base-100 p-6 rounded-lg shadow-sm breadcrumbs text-sm">
    <!-- Breadcrumbs -->
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="/"><x-icon name="o-home" /> Beranda</a></li>
            <li><span class="inline-flex items-center gap-2"><x-icon name="o-academic-cap" />Data Kelas</span></li>
        </ul>
    </div>

    <!-- Header dengan search -->
    <x-header title="Data Kelas" subtitle="Daftar Kelas dan Wali Kelas" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" placeholder="Cari..." />
        </x-slot:middle>
        <x-slot:actions>
            {{-- <x-button icon="o-funnel" /> --}}
            {{-- <livewire:kelas.add-kelas> --}}
        </x-slot:actions>
    </x-header>

    <!-- Table -->
    <x-table :headers="$headers" :rows="$kelas" :sort-by="$sortBy" per-page="perPage" :per-page-values="[5, 10, 15]" with-pagination
        striped>
        @scope('cell_total_pelanggaran', $item)
            {{-- <span class="badge badge-primary">{{ $ite/m->jumlah_siswa }}</span> --}}
            <x-badge value="{{ $item->total_pelanggaran }}" class="badge-error badge-soft" />
        @endscope
        @scope('cell_jumlah_siswa', $item)
            {{-- <span class="badge badge-primary">{{ $ite/m->jumlah_siswa }}</span> --}}
            <x-badge value="{{ $item->jumlah_siswa }}" class="badge-primary badge-soft" />
        @endscope

        @scope('cell_actions', $item)
            <x-dropdown>
                <x-slot:trigger>
                    <x-button icon="m-ellipsis-vertical" class="btn-circle" />
                </x-slot:trigger>
                <x-menu-item title="Edit" icon="o-pencil"
                    wire:click="$dispatch('showEditModal', { id: '{{ $item->ID_Kelas }}' })" />

                <x-menu-item title="Hapus" icon="o-trash"
                    wire:click="$dispatch('showDeleteModal', { id: '{{ $item->ID_Kelas }}' })" class="text-red-500" />
            </x-dropdown>
        @endscope

        <x-slot:empty>
            <x-icon name="o-archive-box" label="Tidak ada data kelas" />
        </x-slot:empty>
    </x-table>
    {{-- 
    <livewire:kelas.edit-kelas>
    <livewire:kelas.delete-kelas> --}}
</div>
