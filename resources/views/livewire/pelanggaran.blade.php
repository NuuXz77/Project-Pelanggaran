<div class="bg-base-100 p-6 rounded-lg shadow-sm breadcrumbs text-sm">
    <!-- Breadcrumbs -->
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="/"><x-icon name="o-home" /> Beranda</a></li>
            <li>
                <span class="inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                        <path
                            d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z" />
                        <path
                            d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                    </svg>
                    Pelanggaran
                </span>
            </li>
        </ul>
    </div>

    <x-header title="Pelanggaran" subtitle="Daftar Pelanggaran Siswa" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" placeholder="Cari..." />
        </x-slot:middle>
        <x-slot:actions>
            <livewire:pelanggaran.print-pelanggaran />
            <livewire:pelanggaran.filter-pelanggaran />
            <livewire:pelanggaran.add-pelanggaran />
        </x-slot:actions>
    </x-header>
    <x-table :headers="$headers" :rows="$pelanggaran" :sort-by="$sortBy" per-page="perPage" :per-page-values="[5, 10, 15]" with-pagination
        striped>
        @scope('cell_actions', $item)
            <x-dropdown>
                <x-slot:trigger>
                    <x-button icon="m-ellipsis-vertical" class="btn-circle" />
                </x-slot:trigger>
                <x-menu-item title="Edit" icon="o-pencil"
                    wire:click="$dispatch('showEditModal', { id: '{{ $item->ID_Pelanggaran }}' })" />

                <x-menu-item title="Hapus" icon="o-trash"
                    wire:click="$dispatch('showDeleteModal', { id: '{{ $item->ID_Pelanggaran }}' })" class="text-red-500" />
            </x-dropdown>
        @endscope

        <x-slot:empty>
            <x-icon name="o-cube" label="Ups! Data tidak di temukan." />
        </x-slot:empty>
    </x-table>

    <livewire:pelanggaran.edit-pelanggaran>
        <livewire:pelanggaran.delete-pelanggaran>
</div>
