<x-admin.admin-navigation page_title="Dashboard" action="{{ route('admin.dashboard') }}" placeholder="Search..." name="search">

    <!-- Main Content -->
    <main class="flex -mt-2 overflow-y-scroll h-[85vh] pb-5">

        <div class="flex flex-col gap-4 w-full">
            <div class="bg-[#FBFBFB] px-4 pb-2 rounded-lg drop-shadow mb-2">
                <div class="flex flex-col lg:gap-2 gap-1 mr-auto mb-2 pt-2">
                    <!-- Page Description -->
                    <div class="flex px-0 border-b border-b-gray-300 py-2">
                        <div class="mt-2 mr-auto">
                            <div class="flex flex-col">
                                <!-- Card Title -->
                                <div class="text-[#4D4F50] font-semibold text-lg sm:text-xl">
                                    Number of Road Defects Reports
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Container for search and filters -->
                    <div class="flex flex-wrap md:flex-nowrap items-center gap-2 w-full">
                        <!-- Search Bar -->
                        <div class="relative flex flex-1 md:flex-none w-full md:w-auto">
                            <svg class="pointer-events-none absolute inset-y-0 left-1 h-full w-4 text-gray-400 ml-2 z-0"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#4AA76F">
                                <path d="M368 208A160 160 0 1 0 48 208a160 160 0 1 0 320 0zM337.1 371.1C301.7 399.2 256.8 416 208 416C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208c0 48.8-16.8 93.7-44.9 129.1l124 124 17 17L478.1 512l-17-17-124-124z" />
                            </svg>
                            <input
                                class="border border-gray-300 focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F]
                            shadow-[0px_1px_5px_rgba(0,0,0,0.2)] focus:bg-white bg-white rounded-[4px]
                            block w-full md:w-[250px] lg:w-[300px] py-2 pl-8 text-gray-900 placeholder:text-gray-400 text-xs"
                                wire:model.live="search"
                                placeholder="{{ $placeholder ?? 'Search...' }}"
                                type="search" />
                        </div>

                        <!-- Filters -->
                        <div class="flex flex-wrap md:flex-nowrap gap-2">
                            <!-- All Reports Filter -->
                            <div class="relative rounded-[4px] border transition-all duration-200 ease-in-out transform hover:scale-105 hover:shadow-md flex justify-center items-center"
                                :class="{
                                    'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F] ': filters.sort === '' && filters.status === ''  && filters.userType === '',
                                    'text-gray-600 border-gray-300 hover:border-[#4AA76F]': filters.sort !== '' || filters.status !== '' || filters.userType !== ''
                                }"
                                @click="$wire.resetFilter();">
                                <span class="text-[12px] block appearance-none w-full text-center px-2 py-2 rounded">
                                    All Reports
                                </span>
                            </div>

                            <!-- Barangay Filter -->
                            <div class="relative flex rounded-[4px] border hover:shadow-md  custom-select "
                                :class="{
                                        'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F] active': filters.selectedBarangay !== '',
                                        'text-gray-600 border-gray-300 hover:border-[#4AA76F]': filters.selectedBarangay === ''
                                    }">
                                <select wire:model.live="selectedBarangay"
                                    @change="activeFilter = 'selectedBarangay'"
                                    class="text-[12px] block appearance-none w-full bg-transparent border-none focus:ring-0 px-3 py-1 pr-8 rounded shadow-none focus:outline-none focus:scale-105">
                                    <option value="" default class="text-gray-400 text-[12px]">Barangay</option>
                                    @foreach($barangays as $barangay)
                                    <option value="{{ $barangay }}">{{ $barangay }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date Range Filter -->
                            <div class="relative flex rounded-[4px] border hover:shadow-md  custom-select "
                                :class="{
                                'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F] active': filters.selectedYear !== '',  /* Active state */
                                'text-gray-600 border-gray-300 hover:border-[#4AA76F]': filters !== selectedYear === ''  /* Default and hover state */
                             }">
                                <select
                                    wire:model.live="selectedYear"
                                    class="text-[12px] block appearance-none w-full bg-transparent border-none focus:ring-0 px-3 py-1 pr-8 rounded shadow-none focus:outline-none focus:scale-105">
                                    @foreach($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class=" flex items-center justify-start lg:justify-end md:justify-end">
                        <div class="font-semibold text-[#7E91FF] pl-2 pr-3 text-lg opacity-90 transform group-hover:scale-110 group-hover:translate-y-1 group-hover:translate-x-4 transition-all duration-500 ease-in-out">
                            Total Reports : {{ $totalReport }}
                        </div>
                    </div>
                </div>

                <div class="flex flex-col xl:flex-row">
                    <!-- Left Section -->
                    <div class="flex flex-col text-[#202020] bg-[#FBFBFB] px-4 pb-2 pt-2 rounded-lg drop-shadow mb-4 xl:w-8/10">
                        <div class="xl:flex-row px-3 gap-2">
                            <div class="w-full max-h-[450px] pt-2 overflow-hidden">
                                <div id="chart" wire.model.live="chartDataUpdated"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="flex px-4">
                        <!-- Stats Cards -->
                        <div class="flex flex-col gap-3 mb-5">

                            <!-- Unfixed Reports Card -->
                            <a href="{{ route('admin.road-defect-reports', ['status' => 'Unfixed']) }}"
                               class="relative bg-white rounded-lg shadow-md py-0 px-2 overflow-hidden min-w-auto w-[300px] max-w-[340px] h-[115px] hover:drop-shadow-lg transition-all duration-500 ease-out drop-shadow transform-gpu group">

                                <!-- Background graphic -->
                                <div class="absolute inset-x-0 bottom-0 opacity-90 transform group-hover:scale-125 transition-transform group-hover:translate-x-1 duration-700 ease-in-out">
                                    <img src="{{ asset('storage/images/bg-cardGraphics-red.png') }}"
                                         class="w-full h-auto rounded-b-lg object-cover"
                                         alt="Card background graphic">
                                </div>

                                <div class="flex flex-col text-[#E26161] mt-6 pl-2 pr-3 pt-4 relative z-10">
                                    <!-- Card Title -->
                                    <div class="font-semibold text-md opacity-90 transform group-hover:scale-110 group-hover:translate-y-1 group-hover:translate-x-3 transition-all duration-500 ease-in-out">
                                        Unfixed Reports
                                    </div>
                                    <!-- Card Counts with gentle scale on hover -->
                                    <div class="px-5 py-1 mb-3 ml-auto text-lg text-[#E26161] rounded-full bg-[#FBFBFB] font-bold transform group-hover:scale-105 group-hover:translate-x-1 duration-500 ease-in-out">
                                        {{ $unfixedReportCount }}
                                    </div>
                                </div>
                            </a>

                            <!-- Ongoing Reports Card -->
                            <a href="{{ route('admin.road-defect-reports', ['status' => 'Ongoing']) }}"

                               class="relative bg-white rounded-lg shadow-md p-0 overflow-hidden w-auto h-[115px] hover:drop-shadow-lg transition-all duration-500 ease-out drop-shadow transform-gpu group ">

                                <!-- Background graphic -->
                                <div class="absolute inset-x-0 bottom-0 opacity-90 transform group-hover:scale-125 transition-transform group-hover:translate-x-1 duration-700 ease-in-out">
                                    <img src="{{ asset('storage/images/bg-cardGraphics-orange.png') }}"
                                         class="w-full h-auto rounded-b-lg object-cover"
                                         alt="Card background graphic">
                                </div>

                                <div class="flex flex-col text-[#FFAD00] mt-6 px-5 pt-4 relative z-10">
                                    <!-- Card Title -->
                                    <div class="font-semibold text-md opacity-90 transform group-hover:scale-105 group-hover:translate-y-1 group-hover:translate-x-1 transition-all duration-500 ease-in-out">
                                        Ongoing Reports
                                    </div>

                                    <!-- Card Counts -->
                                    <div class="px-5 py-1 mb-3 ml-auto text-lg text-[#FFAD00] rounded-full bg-[#FBFBFB] font-bold transform group-hover:scale-105 group-hover:translate-x-1 duration-500 ease-in-out">
                                        {{ $ongoingReportCount }}
                                    </div>
                                </div>

                            </a>

                            <!-- Inactive Accounts Card -->
                            <a href="{{ route('admin.road-defect-reports', ['status' => 'Repaired']) }}"
                               class="relative bg-white rounded-lg shadow-md p-0 overflow-hidden w-auto h-[115px] hover:drop-shadow-lg transition-all duration-500 ease-out drop-shadow transform-gpu group ">

                                <!-- Background graphic -->
                                <div class="absolute inset-x-0 bottom-0 opacity-90 transform group-hover:scale-125 transition-transform group-hover:translate-x-1 duration-700 ease-in-out">
                                    <img src="{{ asset('storage/images/bg-cardGraphics-green.png') }}"
                                         class="w-full h-auto rounded-b-lg object-cover"
                                         alt="Card background graphic">
                                </div>

                                <div class="flex flex-col text-[#4AA76F] mt-6 px-5 pt-4 relative z-10">
                                    <!-- Card Title -->
                                    <div class="font-semibold text-md opacity-90 transform group-hover:scale-105 group-hover:translate-y-1 group-hover:translate-x-1 transition-all duration-500 ease-in-out">
                                        Repaired Reports
                                    </div>

                                    <!-- Card Counts -->
                                    <div class="px-5 py-1  mb-3 ml-auto text-lg text-[#4AA76F] rounded-full bg-[#FBFBFB] font-bold transform group-hover:scale-105 group-hover:translate-x-1 duration-500 ease-in-out">
                                        {{ $fixedReportCount }}
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div>
                </div>
            </div>



            <!-- Road Maintenance Worker -->
            <div class="flex flex-col text-[#202020] bg-[#FBFBFB] px-4 pb-4 rounded-lg drop-shadow">

                <!-- Page Description -->
                <div class="flex px-0 border-b border-b-gray-300 py-2">
                    <div class="mt-4 mr-auto">
                        <div class="flex flex-col">
                            <!-- Card Title -->
                            <div class="text-[#4D4F50] font-semibold text-lg sm:text-xl">
                                Road Maintenance Workers
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="mt-2 mb-2 overflow-hidden h-auto xl:h-[55vh]">

                    <div x-data="{
                    staffData: @entangle('staffRolesData'),
                    filters: @entangle('filters'),
                }" class="m-0 rounded-lg inset-0 p-0">

                        <!-- Dropdown Filters -->
                        <div class="flex flex-wrap gap-2 mb-4 mt-4">
                            <!-- All Users Option -->
                            <div class="relative inline-flex rounded-[4px] border text-center transition-all duration-200 transform hover:scale-105 hover:shadow-md"
                                :class="{
                            'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F]': filters.sort === '' && filters.status === ''  && filters.staffRole === '',
                            'text-gray-600 border-gray-300 hover:border-[#4AA76F]': filters.sort !== '' || filters.status !== '' || filters.staffRole !== ''
                        }"
                                @click="$wire.call('resetFilters')">
                                <span class="text-[12px] block w-full px-2 py-2 rounded">
                                    All Staffs
                                </span>
                            </div>

                            <!-- Sort Filter -->
                            <div class="relative inline-flex rounded-[4px] border hover:shadow-md custom-select"
                                :class="{
                            'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F]': filters.sort !== '',
                            'text-gray-600 border-gray-300 hover:border-[#4AA76F]': filters.sort === ''
                        }">
                                <select x-model="filters.sort"
                                    @change="$wire.set('filters.sort', $event.target.value)"
                                    class="text-[12px] block w-full bg-transparent border-none focus:ring-0 px-3 py-1 pr-6 rounded">
                                    <option value="" class="text-gray-400">Sort by</option>
                                    <option value="asc" class="text-gray-700">Ascending</option>
                                    <option value="desc" class="text-gray-700">Descending</option>
                                </select>
                            </div>

                            <!-- Staff Type Filter -->
                            <div class="relative inline-flex rounded-[4px] border hover:shadow-md custom-select"
                                :class="{
                            'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F]': filters.staffRole !== '',
                            'text-gray-600 border-gray-300 hover:border-[#4AA76F]': filters.staffRole === ''
                        }">
                                <select model="filters.staffRole"
                                    @change="$wire.set('filters.staffRole', $event.target.value)"
                                    class="text-[12px] block w-full bg-transparent border-none focus:ring-0 px-3 py-1 pr-6 rounded">
                                    <option value="" class="text-gray-400">Staff Roles</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <!-- Road Maintenance Workers Data -->
                        <!-- Bar Graph Section -->
                        <div class="flex flex-col xl:flex-row mb-2 px-3 pb-3 gap-2" x-data="{
                        selectedRole: null,
                        staffData: @entangle('staffRolesData'),
                        maxCount: function() {
                            return Math.max(...this.staffData.map(role => role.count));
                        }
                    }">
                            <div class="w-full xl:w-7/10 max-h-[330px] overflow-auto">
                                <div class="rounded-lg">
                                    <div class="relative h-auto space-y-3">
                                        <!-- Bar Graph -->
                                        <template x-for="role in staffData" :key="role.name">
                                            <div class="relative">
                                                <div class="flex relative items-center space-x-2">
                                                    <div class="flex-1">
                                                        <div @click="selectedRole = role"
                                                            class="bg-[#5F994D] h-14 rounded cursor-pointer transition-all duration-300 hover:opacity-80 rounded-r-3xl animate-wipe-right"
                                                            :class="{'bg-[#426e35]': selectedRole === role}"
                                                            :style="`width: ${(role.count / maxCount()) * 100}%`">
                                                            <span class="absolute left-0 text-sm text-white px-2 py-4"
                                                                x-text="role.name"></span>
                                                            <span class="absolute right-0 text-sm text-white px-4 py-4"
                                                                x-text="role.count"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>


                            <!-- staff List Section -->
                            <div class="w-full xl:w-3/10 mx-0 xl:mx-4 bg-[#FBFBFB] h-[50vh] drop-shadow p-4 mt-4 xl:-mt-8">
                                <div class="inline-block w-full min-h-[48vh] max-h-[48vh] overflow-y-auto px-2 align-middle z-0">
                                    <h3 class="font-semibold mb-2 text-gray-700" x-text="selectedRole ? selectedRole.name : 'All Staffs'"></h3>
                                    <div class="space-y-2 border-t border-gray-600 pt-2">
                                        <template x-for="member in (selectedRole ? selectedRole.members : staffData.flatMap(role => role.members))" :key="member.name">
                                            <div class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded">
                                                <img :src="member.avatar ? '/storage/' + member.avatar : '/storage/icons/profile-graphics.png'"
                                                    class="w-8 h-8 rounded-full border-2 border-[#4e8e3a] object-cover  mr-2"
                                                    alt="Avatar">
                                                <span x-text="member.name" class="text-gray-600 text-sm"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var chart;

            function initializeChart(chartData) {

                // Custom CSV export function to filter out zero values
                function customCSVExport() {
                    // Get the series data
                    const series = chartData.series;
                    const categories = chartData.categories;

                    // Create CSV header
                    let csvContent = "Defect Type,Month,Count\n";

                    // Loop through series and add only non-zero values
                    series.forEach(s => {
                        s.data.forEach((value, index) => {
                            if (value > 0) {
                                // Add to CSV only if count > 0
                                csvContent += `${s.name},${categories[index]},${value}\n`;
                            }
                        });
                    });

                    // Create a blob and trigger download
                    const blob = new Blob([csvContent], {
                        type: 'text/csv;charset=utf-8;'
                    });
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.setAttribute('href', url);
                    link.setAttribute('download', `defects-report-${chartData.selectedYear}.csv`);
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }

                var options = {
                    chart: {
                        type: 'area',
                        height: 350,
                        toolbar: {
                            show: true,
                            tools: {
                                zoom: false,
                                zoomin: false,
                                zoomout: false,
                                pan: false,
                                reset: false,
                                download: true,
                            },
                            export: {
                                csv: {
                                    filename: `defects-report-${chartData.selectedYear}`,
                                    headerCategory: 'Month',
                                    headerValue: 'Count',
                                },
                                png: {
                                    filename: "Defects_Report_Summary",
                                    background: '#ffffff',
                                    scale: 2,
                                },
                                svg: {
                                    filename: "Defects_Report_Summary",
                                    width: 800,
                                    height: 600,
                                }
                            }
                        },
                        events: {
                            beforeMount: function(chart, options) {
                                // Override the default export button action
                                chart.exports.handleCSVExport = function() {
                                    customCSVExport();
                                };
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                        horizontalAlign: 'center',
                        markers: {
                            width: 12,
                            height: 12,
                            radius: 12
                        }
                    },
                    series: chartData.series,
                    xaxis: {
                        categories: chartData.categories,
                        labels: {
                            offsetX: 4
                        }
                    },
                    yaxis: {},
                    colors: chartData.series.map(series => series.color),
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.8,
                            opacityTo: 0,
                            stops: [0, 100]
                        }
                    },
                    markers: {
                        colors: chartData.series.map(series => series.color),
                        strokeColor: '#fff',
                        strokeWidth: 2,
                        shape: 'circle',
                        hover: {
                            size: 10,
                            sizeOffset: 3
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    title: {
                        text: "Monthly Defects Report",
                        align: 'center',
                        style: {
                            fontSize: '16px',
                            fontWeight: 'bold',
                            fontFamily: 'Arial, sans-serif',
                            color: '#000000'
                        },
                        offsetY: 10,
                        margin: 10,
                        floating: false
                    },
                    subtitle: {
                        text: `Year: ${chartData.selectedYear || @json($selectedYear)}`,
                        align: 'center',
                        offsetY: 30,
                        style: {
                            fontSize: '12px',
                            fontFamily: 'Arial, sans-serif',
                            color: '#666666'
                        }
                    },
                };

                chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            }

            // Initial chart render
            initializeChart(@json($chartData));

            // Listen for chart data updates from Livewire
            Livewire.on('chartDataUpdated', (event) => {
                // Handle both formats for backward compatibility
                const data = event.data || event[0];
                initializeChart(data);
            });
        });
    </script>


</x-admin.admin-navigation>
