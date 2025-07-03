<div class="bg-base-100 p-6 rounded-lg shadow-sm breadcrumbs text-sm">
    <!-- Breadcrumbs -->
    <div class="breadcrumbs text-sm">
        <ul>
            <li>
                <a href="/">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-house-door" viewBox="0 0 16 16">
                        <path
                            d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z" />
                    </svg>
                    Beranda
                </a>
            </li>
            <li>
                <span class="inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-people-fill" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                    </svg>
                    Kelas
                </span>
            </li>
        </ul>
    </div>

    <!-- Header dengan search -->
    <x-header title="Data Kelas" subtitle="Daftar Kelas dan Wali Kelas" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" placeholder="Cari..." />
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-funnel" />
            <livewire:kelas.add-kelas>
        </x-slot:actions>
    </x-header>

    <!-- Table -->
    <x-table :headers="$headers" :rows="$kelas" :sort-by="$sortBy" per-page="perPage" :per-page-values="[5, 10, 15]" with-pagination striped>
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

    <livewire:kelas.edit-kelas>
    <livewire:kelas.delete-kelas>
</div>