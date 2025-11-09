<div>
    <x-header title="Beranda" separator progress-indicator>
        <x-slot:actions>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <x-select 
                        wire:model.live="filterType"
                        :options="[
                            ['id' => 'day', 'name' => 'Per Hari'],
                            ['id' => 'week', 'name' => 'Per Minggu'],
                            ['id' => 'month', 'name' => 'Per Bulan'],
                        ]"
                        option-value="id"
                        option-label="name"
                        class="select-sm"
                    />
                    <x-datetime 
                        wire:model.live="filterDate"
                        type="date"
                        class="input-sm"
                    />
                </div>
            </div>
            {{-- //tambahkan aksi delete disini --}}
            <livewire:beranda.delete-data/>
        </x-slot:actions>
    </x-header>
    
        <livewire:beranda.statiska 
            :filterType="$filterType"
            :filterDate="$filterDate"
            :key="'statiska-'.$filterType.'-'.$filterDate"
        />
        
        <!-- Pie Chart -->
        <livewire:beranda.chart_pie 
            :filterType="$filterType"
            :filterDate="$filterDate"
            :key="'chart-'.$filterType.'-'.$filterDate"
        />
    
    <livewire:beranda.table 
        :filterType="$filterType"
        :filterDate="$filterDate"
        :key="'table-'.$filterType.'-'.$filterDate"
    />
</div>