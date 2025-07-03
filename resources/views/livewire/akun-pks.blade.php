<div class="bg-base-100 p-6 rounded-lg shadow-sm breadcrumbs text-sm">
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="/"><x-icon name="o-home" /> Beranda</a></li>
            <li><span><x-icon name="o-user-group" /> Akun PKS</span></li>
        </ul>
    </div>

    <x-header title="Data Akun BK" subtitle="Daftar akun pengguna dengan role BK" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass"
                placeholder="Cari nama atau email..." />
        </x-slot:middle>
        <x-slot:actions>
            <livewire:pks.add-pks />
        </x-slot:actions>
    </x-header>

    <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy" per-page="perPage" :per-page-values="[5, 10, 15]" with-pagination
        striped>
        @scope('cell_status', $item)
            @if ($item->status === 'Aktif')
                <div class="inline-grid *:[grid-area:1/1]">
                    <div class="status status-primary animate-ping"></div>
                    <div class="status status-primary"></div>
                </div> Online
            @else
                <div class="inline-grid *:[grid-area:1/1]">
                    <div class="status status-error animate-ping"></div>
                    <div class="status status-error"></div>
                </div> Offline
            @endif
        @endscope

        @scope('cell_actions', $item)
            <x-dropdown>
                <x-slot:trigger>
                    <x-button icon="m-ellipsis-vertical" class="btn-circle" />
                </x-slot:trigger>
                <x-menu-item title="Edit" icon="o-pencil"
                    wire:click="$dispatch('showEditModal', { id: '{{ $item->ID_Akun }}' })" />
                <x-menu-item title="Hapus" icon="o-trash"
                    wire:click="$dispatch('showDeleteModal', { id: '{{ $item->ID_Akun }}' })" class="text-red-500" />
                <x-menu-item title="Reset Password" icon="o-key"
                    wire:click="$dispatch('showResetModal', { id: '{{ $item->ID_Akun }}'})" class="text-yellow-500" />

            </x-dropdown>
        @endscope

        <x-slot:empty>
            <x-icon name="o-archive-box" label="Tidak ada data akun PKS" />
        </x-slot:empty>
    </x-table>

    <livewire:pks.edit-pks />
    <livewire:pks.delete-pks />
    <livewire:pks.reset-pks />
</div>
