<div class="bg-base-100 p-6 rounded-lg shadow-sm breadcrumbs text-sm">
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="/"><x-icon name="o-home" /> Beranda</a></li>
            <li><span class="inline-flex items-center gap-2"><x-icon name="o-user" /> Siswa</span></li>
        </ul>
    </div>

    <x-header title="Data Siswa" subtitle="Daftar Siswa dan Total Pelanggaran" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" placeholder="Cari..." />
        </x-slot:middle>
        <x-slot:actions>
            <livewire:siswa.filter-siswa />
            <livewire:siswa.add-siswa />
            <livewire:siswa.add-excel-siswa />
        </x-slot:actions>
    </x-header>

    <x-table :headers="$headers" :rows="$siswa" :sort-by="$sortBy" per-page="perPage" :per-page-values="[5, 10, 15]" with-pagination
        striped>
        @scope('cell_total_pelanggaran', $item)
            <x-badge value="{{ $item->total_pelanggaran }}" class="badge-warning badge-soft" />
        @endscope

        @scope('cell_actions', $item)
            <x-dropdown>
                <x-slot:trigger>
                    <x-button icon="m-ellipsis-vertical" class="btn-circle" />
                </x-slot:trigger>
                <x-menu-item title="Detail" icon="o-eye"
                    link="{{ route('detail-siswa', ['siswa' => $item->ID_Siswa]) }}" />
                <x-menu-item title="Edit" icon="o-pencil"
                    wire:click="$dispatch('showEditModal', { id: '{{ $item->ID_Siswa }}' })" />
                <x-menu-item title="Hapus" icon="o-trash"
                    wire:click="$dispatch('showDeleteModal', { id: '{{ $item->ID_Siswa }}' })" class="text-red-500" />
            </x-dropdown>
        @endscope

        <x-slot:empty>
            <x-icon name="o-archive-box" label="Tidak ada data siswa" />
        </x-slot:empty>
    </x-table>

    <livewire:siswa.edit-siswa />
    <livewire:siswa.delete-siswa />
    {{-- <livewire:siswa.detail-siswa /> --}}
</div>
