<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 w-full mt-5">
    <!-- Chart Line: Lebih besar, span 2 kolom -->
    <div class="shadow bg-white p-4 rounded-xl flex flex-col sm:col-span-2">
        {{-- <h2 class="text-lg font-semibold mb-2">Grafik Pelanggaran</h2> --}}
        <div id="chart" class="flex-grow w-full"></div>
    </div>

    <!-- Chart Pie: Lebih kecil, hanya 1 kolom -->
    <div class="shadow bg-white p-4 rounded-xl flex flex-col">
        <h2 class="text-lg font-semibold mb-2">Persentase Pelanggaran</h2>
        <div id="pie" class="flex-grow w-full"></div>
    </div>

    <script>
        var optionsLine = {
            chart: {
                height: 328,
                type: 'line',
                zoom: {
                    enabled: false
                },
                dropShadow: {
                    enabled: true,
                    top: 3,
                    left: 2,
                    blur: 4,
                    opacity: 1,
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            //colors: ["#3F51B5", '#2196F3'],
            series: [{
                    name: "Kelas 10",
                    data: [1, 15, 26, 20, 33, 27]
                },
                {
                    name: "Kelas 11",
                    data: [3, 33, 21, 42, 19, 32]
                },
                {
                    name: "Kelas 12",
                    data: [0, 39, 52, 11, 29, 43]
                }
            ],
            title: {
                text: 'Grafik Pelanggaran',
                align: 'left',
                offsetY: 25,
                offsetX: 20
            },
            subtitle: {
                text: 'Statiska',
                offsetY: 55,
                offsetX: 20
            },
            markers: {
                size: 6,
                strokeWidth: 0,
                hover: {
                    size: 9
                }
            },
            grid: {
                show: true,
                padding: {
                    bottom: 0
                }
            },
            labels: ['01/15/2002', '01/16/2002', '01/17/2002', '01/18/2002', '01/19/2002', '01/20/2002'],
            xaxis: {
                tooltip: {
                    enabled: false
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                offsetY: -20
            }
        }
        var chartLine = new ApexCharts(document.querySelector('#chart'), optionsLine);
        chartLine.render();

        var options = {
            series: [44, 33, 23],
            chart: {
                type: 'donut',
                height: '100%'
            },
            labels: ['Kelas 10', 'Kelas 11', 'Kelas 12'],
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '14px',
                markers: {
                    width: 12,
                    height: 12
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
            }]
        };

        var chart = new ApexCharts(document.querySelector("#pie"), options);
        chart.render();
    </script>
</div>
