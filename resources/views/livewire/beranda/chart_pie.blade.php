<?php

use Livewire\Volt\Component;
use App\Models\Pelanggaran;
use App\Models\Peraturan;
use App\Models\Kelas;
use Livewire\Attributes\On;
use Carbon\Carbon;

new class extends Component {
    public $filterType;
    public $filterDate;
    public $chartData = [];
    public $pieData = [];
    public $topPeraturan = [];
    public $peraturanHeaders = [];

    public function mount($filterType = 'day', $filterDate = null)
    {
        $this->filterType = $filterType;
        $this->filterDate = $filterDate ?: Carbon::now()->format('Y-m-d');

        $this->peraturanHeaders = [
            ['key' => 'number', 'label' => '#', 'class' => 'text-center', 'sortable' => false], 
            ['key' => 'kode', 'label' => 'Kode'], 
            ['key' => 'larangan', 'label' => 'Larangan'], 
            ['key' => 'total_pelanggaran', 'label' => 'Total Pelanggaran', 'class' => 'text-center']
        ];

        $this->loadAllData();
    }

    #[On('filter-updated')]
    public function updateFilter($type, $date)
    {
        $this->filterType = $type;
        $this->filterDate = $date;
        $this->loadAllData();
    }

    protected function loadAllData()
    {
        $this->generateChartData();
        $this->generatePieData();
        $this->generateTopPeraturan();
    }

    protected function generateTopPeraturan()
    {
        $date = Carbon::parse($this->filterDate);
        $dateRange = $this->getDateRange($date);

        $this->topPeraturan = Peraturan::withCount(['pelanggaran' => function ($query) use ($dateRange) {
                if ($dateRange) {
                    $query->whereBetween('tb_pelanggaran.created_at', $dateRange);
                }
            }])
            ->having('pelanggaran_count', '>', 0)
            ->orderByDesc('pelanggaran_count')
            ->take(5)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'number' => $index + 1,
                    'kode' => $item->kode_peraturan,
                    'larangan' => $item->larangan,
                    'total_pelanggaran' => $item->pelanggaran_count,
                ];
            });
    }

    protected function getDateRange($date)
    {
        switch ($this->filterType) {
            case 'day':
                return [$date->copy()->startOfDay(), $date->copy()->endOfDay()];
            case 'week':
                return [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()];
            case 'month':
                return [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()];
            default:
                return null;
        }
    }

    protected function generateChartData()
    {
        $date = Carbon::parse($this->filterDate);
        $labels = [];
        $data = [];

        switch ($this->filterType) {
            case 'day':
                // Data per hari (7 hari terakhir termasuk hari ini)
                $startDate = $date->copy()->subDays(6); // 7 hari terakhir
                for ($i = 0; $i < 7; $i++) {
                    $currentDate = $startDate->copy()->addDays($i);
                    $labels[] = $currentDate->isoFormat('dddd, D MMM'); // Format: Senin, 1 Jan
                    $count = Pelanggaran::whereDate('created_at', $currentDate)->count();
                    $data[] = $count;
                }
                break;

            case 'week':
                // Data per minggu (4 minggu terakhir)
                $startDate = $date->copy()->subWeeks(3)->startOfWeek();
                for ($i = 0; $i < 4; $i++) {
                    $weekStart = $startDate->copy()->addWeeks($i);
                    $weekEnd = $weekStart->copy()->endOfWeek();
                    $labels[] = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M');
                    $count = Pelanggaran::whereBetween('created_at', [
                        $weekStart->copy()->startOfDay(), 
                        $weekEnd->copy()->endOfDay()
                    ])->count();
                    $data[] = $count;
                }
                break;

            case 'month':
                // Data per bulan (6 bulan terakhir)
                $startDate = $date->copy()->subMonths(5)->startOfMonth();
                for ($i = 0; $i < 6; $i++) {
                    $monthStart = $startDate->copy()->addMonths($i);
                    $labels[] = $monthStart->isoFormat('MMM YYYY'); // Format: Jan 2023
                    $count = Pelanggaran::whereBetween('created_at', [
                        $monthStart->copy()->startOfMonth()->startOfDay(), 
                        $monthStart->copy()->endOfMonth()->endOfDay()
                    ])->count();
                    $data[] = $count;
                }
                break;
        }

        $this->chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Pelanggaran',
                    'data' => $data,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'tension' => 0.1,
                    'fill' => false,
                    'pointBackgroundColor' => 'rgb(75, 192, 192)',
                    'pointRadius' => 4,
                ],
            ],
        ];
    }

    protected function generatePieData()
    {
        $date = Carbon::parse($this->filterDate);

        $query = Kelas::selectRaw('tb_kelas.kelas, COUNT(tb_pelanggaran.ID_Pelanggaran) as total')
            ->leftJoin('tb_siswa', 'tb_kelas.ID_Kelas', '=', 'tb_siswa.kelas_id')
            ->leftJoin('tb_pelanggaran', 'tb_siswa.ID_Siswa', '=', 'tb_pelanggaran.siswa_id');

        switch ($this->filterType) {
            case 'day':
                $query->whereDate('tb_pelanggaran.created_at', $date);
                break;
            case 'week':
                $query->whereBetween('tb_pelanggaran.created_at', [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()]);
                break;
            case 'month':
                $query->whereBetween('tb_pelanggaran.created_at', [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()]);
                break;
        }

        $data = $query->groupBy('tb_kelas.kelas')->orderBy('tb_kelas.kelas')->get();

        $labels = [];
        $values = [];
        $backgroundColors = ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)'];

        foreach ($data as $item) {
            $labels[] = 'Kelas ' . $item->kelas;
            $values[] = $item->total;
        }

        // Ensure we have data for all 3 grades (10, 11, 12)
        for ($i = 10; $i <= 12; $i++) {
            if (!in_array("Kelas $i", $labels)) {
                $labels[] = "Kelas $i";
                $values[] = 0;
            }
        }

        // Sort by class
        array_multisort($labels, $values);

        $this->pieData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Pelanggaran per Kelas',
                    'data' => $values,
                    'backgroundColor' => $backgroundColors,
                    'borderWidth' => 1,
                ],
            ],
        ];
    }


}; ?>

<div class="grid gap-6 lg:grid-cols-2 mt-5 h-auto">
    <!-- Line Chart Container - Lebih besar dan responsif -->
    <div class="bg-base-100 p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4">Statistik Pelanggaran -
            @if ($filterType === 'day')
                7 Hari Terakhir
            @elseif($filterType === 'week')
                4 Minggu Terakhir
            @else
                6 Bulan Terakhir
            @endif
        </h2>
        <div class="relative h-96 w-full" wire:ignore> <!-- Tinggi 24rem (384px) -->
            <canvas id="lineChart-{{ $filterType }}-{{ $filterDate }}"></canvas>
        </div>
    </div>

    <!-- Pie Chart Container - Lebih besar dan responsif -->
    <div class="bg-base-100 p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4">Persentase Pelanggaran per Kelas</h2>
        <div class="relative h-96 w-full" wire:ignore> <!-- Tinggi 24rem (384px) -->
            <canvas id="pieChart-{{ $filterType }}-{{ $filterDate }}"></canvas>
        </div>
    </div>
</div>

@script
<script>
    let lineChart = null;
    let pieChart = null;

    const updateCharts = () => {
        // Jika chart sudah ada, update saja (smooth transition)
        if (lineChart && pieChart) {
            lineChart.data.labels = $wire.chartData.labels;
            lineChart.data.datasets[0].data = $wire.chartData.datasets[0].data;
            lineChart.options.scales.x.title.text = $wire.filterType === 'day' ? 'Hari' : ($wire.filterType === 'week' ? 'Minggu' : 'Bulan');
            lineChart.update('active');

            pieChart.data.labels = $wire.pieData.labels;
            pieChart.data.datasets[0].data = $wire.pieData.datasets[0].data;
            pieChart.update('active');
            return;
        }

        // Initialize charts jika belum ada
        initCharts();
    };

    const initCharts = () => {
        // Destroy existing charts if they exist
        if (lineChart) {
            lineChart.destroy();
            lineChart = null;
        }
        if (pieChart) {
            pieChart.destroy();
            pieChart = null;
        }

        // Get canvas elements with dynamic IDs
        const lineCanvas = document.querySelector('[id^="lineChart-"]');
        const pieCanvas = document.querySelector('[id^="pieChart-"]');

        if (!lineCanvas || !pieCanvas) return;

        const lineCtx = lineCanvas.getContext('2d');
        const pieCtx = pieCanvas.getContext('2d');

        // PENTING: Assign ke variable yang sudah di-declare di luar
        lineChart = new Chart(lineCtx, {
            type: 'line',
            data: $wire.chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 500
                },
                plugins: {
                    title: {
                        display: false,
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        bodyFont: {
                            size: 14
                        },
                        titleFont: {
                            size: 16
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            },
                            font: {
                                size: 12
                            }
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Pelanggaran',
                            font: {
                                size: 14
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: $wire.filterType === 'day' ? 'Hari' : ($wire.filterType === 'week' ? 'Minggu' : 'Bulan'),
                            font: {
                                size: 14
                            }
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                },
                elements: {
                    line: {
                        borderWidth: 3,
                        tension: 0.3
                    },
                    point: {
                        radius: 4,
                        hoverRadius: 7
                    }
                }
            }
        });

        // PENTING: Assign ke variable yang sudah di-declare di luar
        pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: $wire.pieData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 500
                },
                plugins: {
                    title: {
                        display: false,
                    },
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 14
                            },
                            padding: 20
                        }
                    },
                    tooltip: {
                        bodyFont: {
                            size: 14
                        },
                        titleFont: {
                            size: 16
                        },
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    };

    // Initialize charts on component load
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            initCharts();
        }, 100);
    });

    // Re-initialize charts when Livewire updates the component
    Livewire.hook('morph.updated', ({ el, component }) => {
        setTimeout(() => {
            updateCharts();
        }, 50);
    });
</script>
@endscript
