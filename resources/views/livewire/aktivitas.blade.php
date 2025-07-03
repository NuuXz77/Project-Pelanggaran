<div class="bg-base-100 p-6 rounded-lg shadow-sm">
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="/"><x-icon name="o-home" /> Beranda</a></li>
            <li><span><x-icon name="o-user-group" /> Log Aktivitas</span></li>
        </ul>
    </div>
    <x-header title="Aktivitas Pengguna" subtitle="Riwayat aktivitas" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" placeholder="Cari aktivitas..." />
        </x-slot:middle>
    </x-header>

    <x-table :headers="$headers" :rows="$aktivitas" :sort-by="$sortBy" per-page="perPage" :per-page-values="[10, 20, 30]" with-pagination
        striped>
        @scope('cell_keterangan', $item)
            @if ($item->keterangan === 'Login Berhasil!')
                <x-badge value="{{ $item->keterangan }}" class="badge-info badge-soft" />
            @else
                <x-badge value="{{ $item->keterangan }}" class="badge-error badge-soft" />
            @endif
            @endscope

            @scope('cell_user_name', $item)
                <span>{{ $item->user_name }}</span>
            @endscope

            @scope('cell_actions', $item)
                <x-dropdown>
                    <x-slot:trigger>
                        <x-button icon="m-ellipsis-vertical" class="btn-circle" />
                    </x-slot:trigger>
                    <x-menu-item title="Hapus" icon="o-trash"
                        wire:click="$dispatch('showDeleteAktivitasModal', { id: '{{ $item->ID_Aktivitas }}' })"
                        class="text-red-500" />
                </x-dropdown>
            @endscope

            <x-slot:empty>
                <x-icon name="o-archive-box" label="Tidak ada aktivitas ditemukan" />
            </x-slot:empty>
    </x-table>

    @livewire('aktivitas.delete-aktivitas')
</div>
