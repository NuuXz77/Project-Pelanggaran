<div class="bg-base-100 p-6 rounded-lg shadow-sm breadcrumbs text-sm">
    <!-- Breadcrumbs -->
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="/"><x-icon name="o-home" /> Beranda</a></li>
            <li><a href="/tata-tertib"><x-icon name="o-scale" /> Tata Tertib</a></li>
        </ul>
    </div>

    <!-- Header dengan search -->
    <x-header title="Tata Tertib" subtitle="Daftar Peraturan" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" placeholder="Cari..." />
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-funnel" />
            <livewire:tatatertib.add-tatatertib>
        </x-slot:actions>
    </x-header>

    <!-- Table -->
    <x-table :headers="$headers" :rows="$peraturan" :sort-by="$sortBy" per-page="perPage" :per-page-values="[5, 10, 15]" with-pagination
        striped>
        @scope('cell_actions', $item)
            <x-dropdown>
                <x-slot:trigger>
                    {{-- <x-button icon="o-bell" class="btn-circle" /> --}}
                    <x-button icon="m-ellipsis-vertical" class="btn-circle" />
                </x-slot:trigger>
                <x-menu-item title="Edit" icon="o-pencil"
                    wire:click="$dispatch('showEditModal', { id: '{{ $item->ID_Peraturan }}' })" />

                <x-menu-item title="Hapus" icon="o-trash"
                    wire:click="$dispatch('showDeleteModal', { id: '{{ $item->ID_Peraturan }}' })" class="text-red-500" />
                {{-- <x-menu-item title="Hapus" icon="o-trash" wire:click="delete({{ $item->id }})"
                    onclick="return confirm('Yakin hapus data?')" /> --}}
            </x-dropdown>
        @endscope
        <x-slot:empty>
            <x-icon name="o-cube" label="Ups! Data tidak di temukan." />
        </x-slot:empty>
    </x-table>

    <livewire:tatatertib.edit-tatatertib>
        <livewire:tatatertib.delete-tatatertib>
</div>
