<div>
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
                        class="bi bi-clipboard-check" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                        <path
                            d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z" />
                        <path
                            d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z" />
                    </svg>
                    </svg>
                    Tata Tertib
                </span>
            </li>
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
    </x-table>

    <x-modal wire:model="myModal2" title="Hello" subtitle="Livewire example">
        <x-form no-separator>
            <x-input label="Name" icon="o-user" placeholder="The full name" />
            <x-input label="Email" icon="o-envelope" placeholder="The e-mail" />

            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.myModal2 = false" />
                <x-button label="Confirm" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <livewire:tatatertib.edit-tatatertib>
    <livewire:tatatertib.delete-tatatertib>
</div>
