<div>
    <x-header title="Beranda" separator progress-indicator>
        {{-- <x-slot:actions>
            <x-datetime wire:model.live="myDate" />
        </x-slot:actions> --}}
    </x-header>
    <livewire:beranda.statiska/>
    <livewire:beranda.chart_pie :myDate="$myDate" />
    <livewire:beranda.table :myDate="$myDate" />
</div>
