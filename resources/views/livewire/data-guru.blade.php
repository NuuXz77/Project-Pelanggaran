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
                        class="bi bi-person-vcard" viewBox="0 0 16 16">
                        <path d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4m4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5M9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8m1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5"/>
                        <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96q.04-.245.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 1 1 12z"/>
                    </svg>
                    Data Guru
                </span>
            </li>
        </ul>
    </div>

    <!-- Header dengan search -->
    <x-header title="Data Guru" subtitle="Daftar Guru dan Kelas Wali" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" placeholder="Cari..." />
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-funnel" />
            <livewire:guru.add-guru>
        </x-slot:actions>
    </x-header>

    <!-- Table -->
    <x-table :headers="$headers" :rows="$gurus" :sort-by="$sortBy" per-page="perPage" :per-page-values="[5, 10, 15]" with-pagination striped>
        @scope('cell_kelas_wali', $guru)
            <x-badge :value="$guru->kelas_wali" class="badge-primary badge-soft" />
        @endscope

        @scope('cell_actions', $guru)
            <x-dropdown>
                <x-slot:trigger>
                    <x-button icon="m-ellipsis-vertical" class="btn-circle" />
                </x-slot:trigger>
                <x-menu-item title="Edit" icon="o-pencil"
                    wire:click="$dispatch('showEditModal', { id: '{{ $guru->ID_Guru }}' })" />

                <x-menu-item title="Hapus" icon="o-trash"
                    wire:click="$dispatch('showDeleteModal', { id: '{{ $guru->ID_Guru }}' })" class="text-red-500" />
            </x-dropdown>
        @endscope

        <x-slot:empty>
            <x-icon name="o-archive-box" label="Tidak ada data guru" />
        </x-slot:empty>
    </x-table>

    <livewire:guru.edit-guru>
    <livewire:guru.delete-guru>
</div>