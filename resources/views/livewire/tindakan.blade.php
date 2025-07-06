<div class="bg-base-100 p-6 rounded-lg shadow-sm breadcrumbs text-sm">
    <!-- Breadcrumbs -->
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="/"><x-icon name="o-home" /> Beranda</a></li>
            <li>
                <span class="inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-clipboard-check" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                        <path
                            d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z" />
                        <path
                            d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z" />
                    </svg>
                    </svg>
                    Tindakan
                </span>
            </li>
        </ul>
    </div>
    <x-header title="Tindakan" subtitle="Tentukan Tindakan Yang Berlaku" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" placeholder="Cari..." />
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-funnel" />
            {{-- <x-button icon="o-plus" class="btn-primary" /> --}}
            <livewire:tindakan.add-tindakan>
        </x-slot:actions>
    </x-header>
    <x-table :headers="$headers" :rows="$tindakan" :sort-by="$sortBy" per-page="perPage" :per-page-values="[5, 10, 15]" with-pagination
        striped>
        @scope('cell_actions', $tindakan)
            <x-dropdown>
                <x-slot:trigger>
                    {{-- <x-button icon="o-bell" class="btn-circle" /> --}}
                    <x-button icon="m-ellipsis-vertical" class="btn-circle" />
                </x-slot:trigger>
                <x-menu-item title="Edit" icon="o-pencil"
                    wire:click="$dispatch('showEditModal', { id: '{{ $tindakan->ID_Tindakan }}' })" />

                <x-menu-item title="Hapus" icon="o-trash"
                    wire:click="$dispatch('showDeleteModal', { id: '{{ $tindakan->ID_Tindakan }}' })"
                    class="text-red-500" />
                {{-- <x-menu-item title="Hapus" icon="o-trash" wire:click="delete({{ $item->id }})"
                onclick="return confirm('Yakin hapus data?')" /> --}}
            </x-dropdown>
        @endscope
        <x-slot:empty>
            <x-icon name="o-cube" label="Ups! Data tidak di temukan." />
        </x-slot:empty>
    </x-table>

    <livewire:tindakan.edit-tindakan>
        <livewire:tindakan.delete-tindakan>
</div>
