<x-layouts.app>
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

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="stats shadow bg-white p-4 rounded-lg">
            <div class="stat-figure text-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="inline-block h-8 w-8 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-title">Downloads</div>
            <div class="stat-value">31K</div>
            <div class="stat-desc">Jan 1st - Feb 1st</div>
        </div>
        <div class="stats shadow bg-white p-4 rounded-lg">
            <div class="stat-figure text-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="inline-block h-8 w-8 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-title">Downloads</div>
            <div class="stat-value">31K</div>
            <div class="stat-desc">Jan 1st - Feb 1st</div>
        </div>
        <div class="stats shadow bg-white p-4 rounded-lg">
            <div class="stat-figure text-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="inline-block h-8 w-8 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-title">Downloads</div>
            <div class="stat-value">31K</div>
            <div class="stat-desc">Jan 1st - Feb 1st</div>
        </div>
        <div class="stats shadow bg-white p-4 rounded-lg">
            <div class="stat-figure text-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="inline-block h-8 w-8 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-title">Downloads</div>
            <div class="stat-value">31K</div>
            <div class="stat-desc">Jan 1st - Feb 1st</div>
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mt-5">
        <!-- Chart Line: Lebih besar, span 2 kolom -->
        <div class="shadow bg-white p-4 rounded-xl h-auto flex flex-col sm:col-span-2">
            <h2 class="text-lg font-semibold mb-2">Siswa Pelanggar Terbanyak</h2>
            <div class="overflow-x-auto">
                <table class="table">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Total Pelanggaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- row 1 -->
                        <tr>
                            <th>1</th>
                            <td>Cy Ganderton</td>
                            <td>Quality Control Specialist</td>
                            <td>Blue</td>
                        </tr>
                        <!-- row 2 -->
                        <tr>
                            <th>2</th>
                            <td>Hart Hagerty</td>
                            <td>Desktop Support Technician</td>
                            <td>Purple</td>
                        </tr>
                        <!-- row 3 -->
                        <tr>
                            <th>3</th>
                            <td>Brice Swyre</td>
                            <td>Tax Accountant</td>
                            <td>Red</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="shadow bg-white p-4 rounded-xl h-auto flex flex-col">
            <h2 class="text-lg font-semibold mb-2">Persentase Pelanggaran</h2>
            <div class="overflow-x-auto">
                <table class="table">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- row 1 -->
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle h-12 w-12">
                                            <img src="https://img.daisyui.com/images/profile/demo/2@94.webp"
                                                alt="Avatar Tailwind CSS Component" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">Hart Hagerty</div>
                                        <div class="text-sm opacity-50">United States</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- row 2 -->
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle h-12 w-12">
                                            <img src="https://img.daisyui.com/images/profile/demo/3@94.webp"
                                                alt="Avatar Tailwind CSS Component" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">Brice Swyre</div>
                                        <div class="text-sm opacity-50">China</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- row 3 -->
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle h-12 w-12">
                                            <img src="https://img.daisyui.com/images/profile/demo/4@94.webp"
                                                alt="Avatar Tailwind CSS Component" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">Marjy Ferencz</div>
                                        <div class="text-sm opacity-50">Russia</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
