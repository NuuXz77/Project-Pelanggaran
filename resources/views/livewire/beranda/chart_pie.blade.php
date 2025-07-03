<?php

use Livewire\Volt\Component;
use App\Models\Pelanggaran;
use Carbon\Carbon;

new class extends Component {
    public $chartData = [];
    public $pieData = [];
    public $loading = true;

    public function mount()
    {
        $this->loadChartData();
    }

    public function loadChartData()
    {
        // Data untuk Line Chart (Pelanggaran per bulan per tingkat kelas)
        $startDate = Carbon::now()->subMonths(6);
        $endDate = Carbon::now();

        // Format data untuk Line Chart
        $lineSeries = [];
        $categories = [];

        // Generate bulan untuk kategori
        for ($i = 0; $i <= 6; $i++) {
            $month = $startDate->copy()->addMonths($i);
            $categories[] = $month->translatedFormat('M Y');
        }

        // Tingkat kelas yang ingin ditampilkan
        $tingkatKelas = ['10', '11', '12'];

        foreach ($tingkatKelas as $tingkat) {
            $data = [];

            for ($i = 0; $i <= 6; $i++) {
                $monthStart = $startDate->copy()->addMonths($i)->startOfMonth();
                $monthEnd = $startDate->copy()->addMonths($i)->endOfMonth();

                $count = Pelanggaran::whereHas('siswa.kelas', function ($query) use ($tingkat) {
                    $query->where('kelas', $tingkat);
                })
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count();

                $data[] = $count;
            }

            $lineSeries[] = [
                'name' => 'Kelas ' . $tingkat,
                'data' => $data,
            ];
        }

        // Data untuk Pie Chart (Total pelanggaran per tingkat kelas)
        $pieSeries = [];
        $pieLabels = [];

        foreach ($tingkatKelas as $tingkat) {
            $total = Pelanggaran::whereHas('siswa.kelas', function ($query) use ($tingkat) {
                $query->where('kelas', $tingkat);
            })->count();

            $pieSeries[] = $total;
            $pieLabels[] = 'Kelas ' . $tingkat;
        }

        $this->chartData = [
            'line' => [
                'series' => $lineSeries,
                'categories' => $categories,
            ],
            'pie' => [
                'series' => $pieSeries,
                'labels' => $pieLabels,
            ],
        ];

        $this->loading = false;
    }

    public $filter = [
        ['id' => 1, 'name' => 'Perhari'],
        ['id' => 2, 'name' => 'Perm'],
        ['id' => 3, 'name' => 'Perbulan'],
        ['id' => 4, 'name' => 'Pertahun'], // <-- this
    ];
}; ?>

<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mt-5">
    <!-- Chart Line: Lebih besar, span 2 kolom -->
    <div class="shadow bg-base-100 p-4 rounded-xl flex flex-col sm:col-span-2">
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-lg font-semibold">
                Grafik Pelanggaran
                {{-- {{ ['1' => 'Per Hari', '2' => 'Per Minggu', '3' => 'Per Bulan', '4' => 'Per Tahun'][$selectedFilter] ?? '' }} --}}
            </h2>
            <x-select wire:model.live="$filter" :options="$filter" placeholder="Pilih Filter" class="w-40" />
        </div>
        @if ($loading)
            <div class="flex-grow flex items-center justify-center">
                <span class="loading loading-spinner loading-lg"></span>
            </div>
        @else
            <div id="chart" class="flex-grow w-full" style="height: 300px"></div>
        @endif
    </div>

    <!-- Chart Pie: Lebih kecil, hanya 1 kolom -->
    <div class="shadow bg-base-100 p-4 rounded-xl flex flex-col">
        <h2 class="text-lg font-semibold mb-2">Persentase Pelanggaran per Tingkat Kelas</h2>
        @if ($loading)
            <div class="flex-grow flex items-center justify-center">
                <span class="loading loading-spinner loading-lg"></span>
            </div>
        @else
            <div id="pie" class="flex-grow w-full" style="height: 300px"></div>
        @endif
    </div>

    @if (!$loading)
        <script>
            // Inisialisasi Chart Line
            var lineOptions = {
                series: @json($chartData['line']['series']),
                chart: {
                    type: 'line',
                    height: '300px',
                    width: '100%',
                    animations: {
                        enabled: true
                    },
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                colors: ['#3b82f6', '#ef4444', '#10b981'], // Hanya 3 warna untuk 3 kelas
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    dashArray: [0, 0, 0]
                },
                markers: {
                    size: 5,
                    colors: '#000',
                    hover: {
                        size: 7
                    }
                },
                xaxis: {
                    categories: @json($chartData['line']['categories']),
                    tooltip: {
                        enabled: false
                    },
                    labels: {
                        style: {
                            colors: '#6b7280',
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Pelanggaran',
                        style: {
                            fontSize: '12px'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#6b7280',
                            fontSize: '12px'
                        }
                    },
                    min: 0
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    markers: {
                        radius: 4,
                        width: 12,
                        height: 12
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 5
                    },
                    fontSize: '12px'
                },
                grid: {
                    padding: {
                        bottom: 0
                    },
                    borderColor: '#e5e7eb'
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + ' pelanggaran';
                        }
                    },
                    style: {
                        fontSize: '12px'
                    }
                }
            };

            // Inisialisasi Chart Pie
            var pieOptions = {
                series: @json($chartData['pie']['series']),
                chart: {
                    type: 'donut',
                    height: '300px',
                    width: '100%',
                    animations: {
                        enabled: true
                    }
                },
                colors: ['#3b82f6', '#ef4444', '#10b981'], // Hanya 3 warna untuk 3 kelas
                labels: @json($chartData['pie']['labels']),
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    markers: {
                        radius: 4,
                        width: 12,
                        height: 12
                    },
                    fontSize: '12px',
                    formatter: function(seriesName, opts) {
                        return seriesName + ': ' + opts.w.globals.series[opts.seriesIndex] + ' (' +
                            Math.round(opts.w.globals.seriesPercent[opts.seriesIndex], 0) + '%)';
                    }
                },
                responsive: [{
                    breakpoint: 768,
                    options: {
                        chart: {
                            height: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    },
                                    fontSize: '16px'
                                },
                                value: {
                                    fontSize: '16px',
                                    fontWeight: 'bold'
                                },
                                name: {
                                    fontSize: '12px'
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + ' pelanggaran';
                        }
                    },
                    style: {
                        fontSize: '12px'
                    }
                }
            };

            // Render charts setelah DOM siap
            document.addEventListener('DOMContentLoaded', function() {
                // Hapus chart sebelumnya jika ada
                if (window.lineChart) window.lineChart.destroy();
                if (window.pieChart) window.pieChart.destroy();

                // Buat chart baru
                window.lineChart = new ApexCharts(document.querySelector("#chart"), lineOptions);
                window.pieChart = new ApexCharts(document.querySelector("#pie"), pieOptions);

                lineChart.render();
                pieChart.render();
            });

            // Handle livewire update
            document.addEventListener('livewire:update', function() {
                if (window.lineChart && @this.chartData.line) {
                    window.lineChart.updateOptions({
                        series: @this.chartData.line.series,
                        xaxis: {
                            categories: @this.chartData.line.categories
                        }
                    });
                }

                if (window.pieChart && @this.chartData.pie) {
                    window.pieChart.updateOptions({
                        series: @this.chartData.pie.series,
                        labels: @this.chartData.pie.labels
                    });
                }
            });

            // Handle resize dengan debounce
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.lineChart) window.lineChart.updateOptions(lineOptions);
                    if (window.pieChart) window.pieChart.updateOptions(pieOptions);
                }, 200);
            });
        </script>
    @endif
</div>
