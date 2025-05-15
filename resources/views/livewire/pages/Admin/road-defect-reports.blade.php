<x-Admin.admin-navigation page_title="Road Defect Reports" >

    <x-Admin.road-defect-reports-page-content-base>

        {{--Map View--}}
        <x-slot:map_container>
            <!-- Leaflet JS -->
            <livewire:admin.map-view-road-defect-reports/>
        </x-slot:map_container>

        {{--Table View--}}
        <x-slot:search_container>
            <!-- Search Bar -->
            <div class="relative flex flex-1 md:flex-none w-full md:w-auto pl-2 pt-2.5 pb-0">
                <svg class="pointer-events-none absolute inset-y-0 left-1 h-full w-4 text-gray-400 ml-3 mt-1 z-0"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#4AA76F">
                    <path d="M368 208A160 160 0 1 0 48 208a160 160 0 1 0 320 0zM337.1 371.1C301.7 399.2 256.8 416 208 416C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208c0 48.8-16.8 93.7-44.9 129.1l124 124 17 17L478.1 512l-17-17-124-124z" />
                </svg>
                <input
                    class="border border-gray-300 focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F]
                            shadow-[0px_1px_5px_rgba(0,0,0,0.2)] focus:bg-white bg-white rounded-[4px]
                            block w-full md:w-[250px] lg:w-[300px] py-2 pl-8 text-gray-900 placeholder:text-gray-400 text-xs"
                    wire:model.live="table_search"
                    placeholder="Search..."
                    type="search" />
            </div>
        </x-slot:search_container>

        <x-slot:dropdown_filters_container>
            <div x-data="{ activeFilter: 'all' }" class="flex space-x-2">

                <!-- All Road Defect Reports Option -->
                <div class="relative rounded-[4px] border transition-all duration-200 ease-in-out transform hover:scale-105 hover:shadow-md"
                     :class="{
                     'bg-green-200 bg-opacity-20 text-green-800 border-green-600': activeFilter === 'all',
                     'text-gray-600 border-gray-300 hover:border-[#4AA76F]': activeFilter !== 'all'
                 }"
                     @click="activeFilter = 'all'; $wire.resetFilterAndSearch()">
                    <span class="text-[12px] block appearance-none w-full text-center px-2 py-2 rounded">
                        All Reports
                    </span>
                </div>

                <!-- Defect Filter -->
                <div class="relative flex rounded-[4px] border hover:shadow-md custom-select"
                     :class="{
                             'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F]': activeFilter === 'selectedDefect',  /* Active state */
                             'text-gray-600 border-gray-300 hover:border-[#4AA76F]': activeFilter !== 'selectedDefect'  /* Default and hover state */
                         }">
                    <select wire:model.live="selectedDefect"
                            @change="activeFilter = 'selectedDefect'"
                            class="text-[12px] block appearance-none w-full bg-transparent border-none focus:ring-0 px-3 py-1 pr-8 rounded shadow-none focus:outline-none focus:scale-105">
                        <option value="" class="text-gray-400 text-[12px]">Road Defect</option>
                        @foreach($defectTypes as $defect)
                            <option value="{{ $defect }}">{{ $defect }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Location Filter -->
                <div class="relative flex rounded-[4px] border hover:shadow-md custom-select"
                     :class="{
                         'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F]': activeFilter === 'locationFilter',
                         'text-gray-600 border-gray-300 hover:border-[#4AA76F]': activeFilter !== 'locationFilter'
                     }">
                    <select wire:model.live="locationFilter"
                            @change="activeFilter = 'locationFilter'"
                            class="text-[12px] block appearance-none w-full bg-transparent border-none focus:ring-0 px-3 py-1 pr-8 rounded shadow-none focus:outline-none focus:scale-105">
                        <option value="" class="text-gray-400 text-[12px]">Location</option>
                        @foreach($locations as $location)
                            <option value="{{ $location }}">{{ $location }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Barangay Filter -->
                <div class="relative flex rounded-[4px] border hover:shadow-md custom-select"
                     :class="{
                             'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F]': activeFilter === 'barangayFilter',  /* Active state */
                             'text-gray-600 border-gray-300 hover:border-[#4AA76F]': activeFilter !== 'barangayFilter'  /* Default and hover state */
                         }">
                    <select wire:model.live="barangayFilter"
                            @change="activeFilter = 'barangayFilter'"
                            class="text-[12px] block appearance-none w-full bg-transparent border-none focus:ring-0 px-3 py-1 pr-8 rounded shadow-none focus:outline-none focus:scale-105">
                        <option value="" class="text-gray-400 text-[12px]">Barangay</option>
                        @foreach( $barangays as $barangay)
                            <option value="{{ $barangay}}">{{ $barangay }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="relative flex rounded-[4px] border hover:shadow-md custom-select"
                     :class="{
                             'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F]': activeFilter === 'statusFilter',  /* Active state */
                             'text-gray-600 border-gray-300 hover:border-[#4AA76F]': activeFilter !== 'statusFilter'  /* Default and hover state */
                         }">
                    <select wire:model.live="statusFilter"
                            @change="activeFilter = 'statusFilter'"
                            class="text-[12px] block appearance-none w-full bg-transparent border-none focus:ring-0 px-3 py-1 pr-8 rounded shadow-none focus:outline-none focus:scale-105">
                        <option value="" class="text-gray-400 text-[12px]">Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

{{--                <!-- Severity Filter -->--}}
{{--                <div class="relative flex rounded-[4px] border hover:shadow-md custom-select"--}}
{{--                     :class="{--}}
{{--                         'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F]': activeFilter === 'severityFilter',  /* Active state */--}}
{{--                         'text-gray-600 border-gray-300 hover:border-[#4AA76F]': activeFilter !== 'severityFilter'  /* Default and hover state */--}}
{{--                     }">--}}
{{--                    <select--}}
{{--                        wire:model.live="severityFilter"--}}
{{--                        @change="activeFilter = 'severityFilter'"--}}
{{--                        class="text-[12px] block appearance-none w-full bg-transparent border-none focus:ring-0 px-3 py-1 pr-8 rounded shadow-none focus:outline-none focus:scale-105"--}}
{{--                    >--}}
{{--                        <option value="" class="text-gray-400 text-[12px]">Severity</option>--}}
{{--                        @foreach($severities as $label)--}}
{{--                            <option value="{{ $label }}">{{ $label }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}

                <!-- Date Range Filter -->
                <div
                    x-data="{
                        startDate: $wire.start_date,
                        endDate: $wire.end_date,
                        init() {
                            flatpickr(this.$refs.picker, {
                                mode: 'range',
                                dateFormat: 'Y-m-d', // Backend format (ISO format for filtering)
                                defaultDate: [this.startDate, this.endDate],
                                onChange: (selectedDates, dateStr) => {
                                    const formattedDisplay = selectedDates.map(date =>
                                        date.toLocaleDateString('en-US', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: '2-digit'
                                        })
                                    ).join(' to ');

                                    // Display formatted date in the input
                                    this.$refs.picker.value = formattedDisplay;

                                    // Sync with Livewire for backend logic
                                    const [fromDate, toDate] = dateStr.split(' to ');
                                    $wire.set('start_date', fromDate || '');
                                    $wire.set('end_date', toDate || '');
                                }
                            });
                        }
                    }"
                    class="relative flex rounded-[4px] border transition-all duration-200 ease-in-out transform hover:scale-105 hover:shadow-md custom-date-input"
                    :class="{
                        'bg-green-200 bg-opacity-20 text-green-800 border-green-600': activeFilter === 'dateRange',
                        'text-gray-600 border-gray-300 hover:border-[#4AA76F]': activeFilter !== 'dateRange'
                    }"
                >
                    <input
                        class="text-[12px] block appearance-none w-full bg-transparent border-none focus:ring-0 px-3 py-1 pr-8 rounded shadow-none focus:outline-none"
                        x-ref="picker"
                        type="text"
                        placeholder="Select Date Range"
                        @focus="activeFilter = 'dateRange'"
                    />
                </div>
            </div>
        </x-slot:dropdown_filters_container>

        <x-slot:table_page_description>
            <p class="mt-0 lg:text-sm text-xs text-[#656565] pl-3">
                A table view for the geospatial mapping reports with all the reports occur in the iRoadCheck system.
            </p>
        </x-slot:table_page_description>

        <x-slot:action_buttons_container>
            <button type="button"
                    wire:click="exportRoadDefectReports"
                    class="mt-4 flex gap-x-[8px] w-auto text-xs px-[14px] py-[10px] font-normal tracking-wider text-[#FFFFFF] bg-gradient-to-b from-[#84D689] to-green-500 rounded-full hover:drop-shadow hover:bg-[#4AA76F] hover:scale-105 hover:ease-in-out hover:duration-300 transition-all duration-300 [transition-timing-function:cubic-bezier (0.175,0.885,0.32,1.275)] active:-translate-y-1 active:scale-x-90 active:scale-y-110">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="15" height="15" class="mt-0.5 mr-0">
                    <path fill="#ffffff" fill-opacity="1" fill-rule="nonzero" d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 128-168 0c-13.3 0-24 10.7-24 24s10.7 24 24 24l168 0 0 112c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 64zM384 336l0-48 110.1 0-39-39c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l80 80c9.4 9.4 9.4 24.6 0 33.9l-80 80c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l39-39L384 336zm0-208l-128 0L256 0 384 128z"/>
                </svg>
                <span class="ml-0 mt-0 text-[#FFFFFF] text-md">Export Reports</span>
            </button>
        </x-slot:action_buttons_container>

        <x-slot:table_container>
            <div class="inline-block w-full h-auto md:h-[52vh] lg:h-[55vh] xl:h-[62vh] xl:max-h-[100vh] overflow-y-auto align-middle drop-shadow rounded-b-md">
                <table class="w-full text-left divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="sticky top-0 z-10 bg-white py-3 px-4 text-xs font-semibold text-[#757575]">
                            <button class="flex items-end" wire:click="toggleSorting('id')">
                                Report ID
                                <div x-cloak x-show="$wire.sort_by === 'id'">
                                    <x-arrow-up x-cloak x-show="$wire.sort_direction === 'asc'" />
                                    <x-arrow-down x-cloak x-show="$wire.sort_direction === 'desc'" />
                                </div>
                            </button>
                        </th>
                        <th scope="col" class="sticky top-0 z-10 bg-white py-3 px-4 text-xs font-semibold text-[#757575]">
                            <button class="flex items-end" wire:click="toggleSorting('defect')">
                                Type of Road Defect
                                <div x-cloak x-show="$wire.sort_by === 'defect'">
                                    <x-arrow-up x-cloak x-show="$wire.sort_direction === 'asc'" />
                                    <x-arrow-down x-cloak x-show="$wire.sort_direction === 'desc'" />
                                </div>
                            </button>
                        </th>
                        <th scope="col" class="sticky top-0 z-10 bg-white py-3 px-4 text-xs font-semibold text-[#757575]">
                            <button class="flex items-end" wire:click="toggleSorting('location')">
                                Location
                                <div x-cloak x-show="$wire.sort_by === 'location'">
                                    <x-arrow-up x-cloak x-show="$wire.sort_direction === 'asc'" />
                                    <x-arrow-down x-cloak x-show="$wire.sort_direction === 'desc'" />
                                </div>
                            </button>
                        </th>
                        <th scope="col" class="sticky top-0 z-10 bg-white py-3 px-4 text-xs font-semibold text-[#757575]">
                            <button class="flex items-end" wire:click="toggleSorting('barangay')">
                                Barangay
                                <div x-cloak x-show="$wire.sort_by === 'barangay'">
                                    <x-arrow-up x-cloak x-show="$wire.sort_direction === 'asc'" />
                                    <x-arrow-down x-cloak x-show="$wire.sort_direction === 'desc'" />
                                </div>
                            </button>
                        </th>
                        <th scope="col" class="sticky top-0 z-10 bg-white py-3 px-4 text-xs font-semibold text-[#757575]">
                            <button class="flex items-end" wire:click="toggleSorting('date')">
                                Date Reported
                                <div x-cloak x-show="$wire.sort_by === 'date'">
                                    <x-arrow-up x-cloak x-show="$wire.sort_direction === 'asc'" />
                                    <x-arrow-down x-cloak x-show="$wire.sort_direction === 'desc'" />
                                </div>
                            </button>
                        </th>
                        <th scope="col" class="sticky top-0 z-10 bg-white py-3 px-2 text-xs font-semibold text-[#757575]">
                            <button class="flex items-end" wire:click="toggleSorting('status')">
                                Road Status
                                <div x-cloak x-show="$wire.sort_by === 'status'">
                                    <x-arrow-up x-cloak x-show="$wire.sort_direction === 'asc'" />
                                    <x-arrow-down x-cloak x-show="$wire.sort_direction === 'desc'" />
                                </div>
                            </button>
                        </th>
                        <th scope="col" class="sticky top-0 z-10 bg-white py-3 px-4 text-xs font-semibold text-[#757575]">
                            <button class="flex items-end" wire:click="toggleSorting('report_count')">
                                Report Count
                                <div x-cloak x-show="$wire.sort_by === 'report_count'">
                                    <x-arrow-up x-cloak x-show="$wire.sort_direction === 'asc'" />
                                    <x-arrow-down x-cloak x-show="$wire.sort_direction === 'desc'" />
                                </div>
                            </button>
                        </th>
{{--                        <th scope="col" class="sticky top-0 z-10 bg-white py-3 px-4 text-xs font-semibold text-[#757575]">--}}
{{--                            <button class="flex items-end" wire:click="toggleSorting('severity.label')">--}}
{{--                                Level--}}
{{--                                <div x-cloak x-show="$wire.sort_by === 'severity.label'">--}}
{{--                                    <x-arrow-up x-cloak x-show="$wire.sort_direction === 'asc'" />--}}
{{--                                    <x-arrow-down x-cloak x-show="$wire.sort_direction === 'desc'" />--}}
{{--                                </div>--}}
{{--                            </button>--}}
{{--                        </th>--}}
                        <th scope="col" class="sticky top-0 z-10 bg-white py-3 px-4 text-xs font-semibold text-[#757575]">
                            <button class="flex items-end">
                                Updated Report By
                                <div x-cloak>
                                    <x-arrow-up x-cloak x-show="$wire.sort_direction === 'asc'" />
                                    <x-arrow-down x-cloak x-show="$wire.sort_direction === 'desc'" />
                                </div>
                            </button>
                        </th>
                        <th class="sticky top-0 z-10 bg-white py-3 px-2 text-xs font-semibold text-[#757575]">Action</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 bg-white relative">
                    @forelse ($roadDefectReports as $report)
                        @php
                            $statusColors = [
                                'Repaired' => '#28a745',
                                'Ongoing' => '#ffc209',
                                'Unfixed' => '#dc3545',
                            ];
                            $color = $statusColors[$report->status] ?? '#dc3545';
                        @endphp
                        <tr class="{{ $loop->iteration % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100">
                            <td class="px-4 py-3 text-xs">{{ $report->id }}</td>
                            <td class="px-4 py-3 text-xs">{{ $report->defect }}</td>
                            <td class="px-4 py-3 text-xs truncate max-w-[200px]" title="{{ $report->street }}, {{ $report->purok }} ">
                                {{ $report->location }}
                            </td>
                            <td class="px-4 py-3 text-xs truncate max-w-[200px]" title="{{ $report->barangay }}">
                                {{ $report->barangay }}
                            </td>
                            <td class="px-4 py-3 text-xs">{{ $report->date ? \Carbon\Carbon::parse($report->date)->format('F j, Y') : 'N/A' }}</td>
                            <td class="px-4 py-3 text-xs font-semibold" style="color: {{ $color }};">{{ $report->status }}</td>
                            <td class="px-4 py-3 text-xs">{{ $report->report_count }}</td>
{{--                            <td class="px-4 py-3 text-xs font-medium italic">{{ $report->severity->label ?? 'N/A' }}</td>--}}
                            <td class="px-4 py-3 text-xs">{{ $report->updated_by }}</td>
                            <td class="px-2 py-3 text-[11px] lg:text-xs">
                                <button class="flex items-center text-[#3251FF] hover:text-[#1d3fcc] font-medium text-xs transition active:scale-95 hover:bg-blue-100 hover:shadow py-1 px-3 rounded-md"
                                        wire:click="viewRoadDefectReports({{ $report->id }})"
                                        wire:loading.attr="disabled"
                                        x-data="{ loading: false }"
                                        x-on:click="loading = true"
                                        x-on:view-road-defect-reports-modal-shown.window="loading = false">
                                    <img src="{{ asset('storage/icons/view-icon.png') }}" alt="View Icon" class="hidden md:block w-5 h-5 mr-2" />
                                    <span x-cloak x-show="!loading">View</span>
                                    <x-loading-indicator class="text-blue-500 w-4 h-4" x-cloak x-show="loading"/>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-2 text-center text-gray-500">No road defect reports found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </x-slot:table_container>

        <x-slot:pagination_container >
            {{ $roadDefectReports->links('vendor.pagination.custom') }}
        </x-slot:pagination_container>

        <!-- View Resident Modal -->
        <x-slot:modal_container>
            <livewire:modals.admin.road-defect-reports-modal.view-road-defect-reports-modal
                wire:model="road_defect_reports_to_viewed" />
        </x-slot:modal_container>

        <x-slot:wire_loading_container>
            <!-- Loading indicator for pagination -->
            <div wire:loading.class.remove="opacity-0 pointer-events-none" x-cloak x-transition
                 class="absolute inset-0 w-full min-h-full bg-black/50 flex justify-center items-center transition-all pointer-events-none opacity-0 z-[9999]">
                <x-loading-indicator class="h-[50px] w-[50px] text-white" wire:loading/>
            </div>
        </x-slot:wire_loading_container>

    </x-admin.road-defect-reports-page-content-base>

</x-admin.admin-navigation>



