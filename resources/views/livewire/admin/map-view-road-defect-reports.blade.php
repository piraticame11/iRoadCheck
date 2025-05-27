<x-admin.map-view-reports-page-content-base>

    <x-slot:page_description>
        This map view page allows staff to view the reports of the road defects within Tagum City.
    </x-slot:page_description>

    <x-slot:search_container>
        <svg class="pointer-events-none absolute inset-y-0 left-1 h-full w-4 text-gray-400 ml-3 mt-1 z-0"
             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#4AA76F">
            <path d="M368 208A160 160 0 1 0 48 208a160 160 0 1 0 320 0zM337.1 371.1C301.7 399.2 256.8 416 208 416C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208c0 48.8-16.8 93.7-44.9 129.1l124 124 17 17L478.1 512l-17-17-124-124z" />
        </svg>
        <input
            class="ml-2 border border-gray-300 focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] shadow-[0px_1px_5px_rgba(0,0,0,0.2)] focus:bg-white bg-white rounded-[4px] block w-full py-3 lg:py-1 pl-8 text-gray-900 placeholder:text-gray-400 text-sm md:text-xs lg:text-xs"
            x-on:input="
                filterMarkers($event.target.value);
                showFilters = $event.target.value.trim() !== '';
            "
            placeholder="Search..."
            type="search" />
    </x-slot:search_container>

    <x-slot:dropdown_filters_container>

        <!-- All Reports Button -->
        <!-- All Reports Button -->
        <div
            @click="
                activeFilter = 'all';
                searchQuery = '';
                dateFilterStart = '';
                dateFilterEnd = '';
                selectedBarangay = '';
                selectedStatus = '';
                selectedDefect = '';
                selectedSeverity = '';

                // Clear dropdowns
                document.querySelectorAll('select').forEach(select => select.value = '');

                // Clear date pickers
                if ($refs.start?._flatpickr) $refs.start._flatpickr.clear();
                if ($refs.end?._flatpickr) $refs.end._flatpickr.clear();

                // Trigger map marker reset
                filterMarkers('');
            "
                    class="relative rounded-[4px] border transition-all duration-200 ease-in-out transform hover:scale-105 hover:shadow-md"
                    :class="{
                'bg-green-200 bg-opacity-20 text-green-800 border-green-600': activeFilter === 'all',
                'text-gray-600 border-gray-300 hover:border-[#4AA76F]': activeFilter !== 'all'
            }"
        >
            <span class="text-[12px] block w-full text-center px-2 py-2">All Reports</span>
        </div>


        <!-- Barangay -->
        <div class="relative flex rounded-[4px] border hover:shadow-md custom-select"
             :class="{
             'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F]': activeFilter === 'barangayFilter',
             'text-gray-600 border-gray-300 hover:border-[#4AA76F]': activeFilter !== 'barangayFilter'
         }">
            <select
                x-on:input="
                filterMarkers($event.target.value);"
                @change="activeFilter = 'barangayFilter'"
                class="text-[12px] w-full bg-transparent border-none focus:ring-0 px-3 py-1 pr-8 rounded focus:outline-none">
                <option value="" class="text-gray-400 text-[12px]">Barangay</option>
                @foreach($barangays as $barangay)
                    <option value="{{ $barangay }}">{{ $barangay }}</option>
                @endforeach
            </select>
        </div>

        <!-- Defect Type -->
        <div class="relative flex rounded-[4px] border hover:shadow-md custom-select"
             :class="{
                 'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F]': activeFilter === 'selectedDefect',
                 'text-gray-600 border-gray-300 hover:border-[#4AA76F]': activeFilter !== 'selectedDefect'
             }">
            <select
                x-on:input="
                filterMarkers($event.target.value);"
                @change="activeFilter = 'selectedDefect'"
                class="text-[12px] w-full bg-transparent border-none focus:ring-0 px-3 py-1 pr-8 rounded focus:outline-none">
                <option value="" class="text-gray-400 text-[12px]">Road Defect Type</option>
                @foreach($defectTypes as $defect)
                    <option value="{{ $defect }}">{{ $defect }}</option>
                @endforeach
            </select>
        </div>

        <!-- Status -->
        <div class="relative flex rounded-[4px] border hover:shadow-md custom-select"
             :class="{
             'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F]': activeFilter === 'statusFilter',
             'text-gray-600 border-gray-300 hover:border-[#4AA76F]': activeFilter !== 'statusFilter'
         }">
            <select
                x-on:input="
                filterMarkers($event.target.value);"
                @change="activeFilter = 'statusFilter'"
                class="text-[12px] w-full bg-transparent border-none focus:ring-0 px-3 py-1 pr-8 rounded focus:outline-none">
                <option value="" class="text-gray-400 text-[12px]">Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}">{{ $status }}</option>
                @endforeach
            </select>
        </div>

        <!-- Severity -->
{{--        <div class="relative flex rounded-[4px] border hover:shadow-md custom-select"--}}
{{--             :class="{--}}
{{--             'bg-green-200 bg-opacity-20 text-green-800 border-[#4AA76F]': activeFilter === 'severityFilter',--}}
{{--             'text-gray-600 border-gray-300 hover:border-[#4AA76F]': activeFilter !== 'severityFilter'--}}
{{--         }">--}}
{{--            <select--}}
{{--                x-on:input="--}}
{{--                filterMarkers($event.target.value);"--}}
{{--                @change="activeFilter = 'severityFilter'"--}}
{{--                class="text-[12px] w-full bg-transparent border-none focus:ring-0 px-3 py-1 pr-8 rounded focus:outline-none">--}}
{{--                <option value="" disabled class="text-gray-400 text-[12px]">Severity</option>--}}
{{--                <option value="1">Shallow</option>--}}
{{--                <option value="2">Tolerable</option>--}}
{{--                <option value="3">Serious</option>--}}
{{--                <option value="4">Dangerous</option>--}}
{{--                <option value="5">None</option>--}}

{{--            </select>--}}
{{--        </div>--}}

        <!-- Date Range -->
        <div x-data="{
            initDatePickers() {
                const self = this;

                flatpickr(this.$refs.start, {
                    altInput: true,
                    altFormat: 'F j, Y',
                    dateFormat: 'Y-m-d',
                    onChange(selectedDates, dateStr) {
                        self.dateFilterStart = dateStr;
                        filterMarkers(document.querySelector('input[type=search]').value);
                    }
                });

                flatpickr(this.$refs.end, {
                    altInput: true,
                    altFormat: 'F j, Y',
                    dateFormat: 'Y-m-d',
                    onChange(selectedDates, dateStr) {
                        self.dateFilterEnd = dateStr;
                        filterMarkers(document.querySelector('input[type=search]').value);
                    }
                });
            }
        }" x-init="initDatePickers()" class="relative flex rounded-[4px] border hover:shadow-md custom-date-input"
             :class="{
                    'bg-green-200 bg-opacity-20 text-green-800 border-green-600': activeFilter === 'dateRange',
                    'text-gray-600 border-gray-300 hover:border-[#4AA76F]': activeFilter !== 'dateRange'
                }">

            <span class="text-[12px] bg-transparent border-none pl-2 py-2">From:</span>
            <input
                type="text"
                x-ref="start"
                placeholder="Start Date Reported"
                class="text-[12px] w-full bg-transparent border-none focus:ring-0 px-2 py-1 pr-2 rounded focus:outline-none"/>
            <span class="text-[12px] bg-transparent border-none py-2">To:</span>
            <input
                type="text"
                x-ref="end"
                placeholder="End Date Reported"
                class="text-[12px] w-full bg-transparent border-none focus:ring-0 px-2 py-1 pr-8 rounded focus:outline-none"/>
        </div>

    </x-slot:dropdown_filters_container>

    <x-slot:comprehensive_or_group_report_information>

        <!-- When No Report Is Selected -->
        <template x-if="(!selectedReport || !selectedReport.id) && !showingGroupReports">
            <div class="mb-4 w-full text-[12px] text-gray-500 text-center">
                <div class="flex flex-col items-center justify-center h-full">
                    <img src="{{ asset('storage/icons/marker-on-the-map-icon.png') }}" alt="markerIcon" loading="lazy"
                         class="xs:-my-2 md:mb-1 md:mt-4 w-28 sm:w-36 md:w-48 lg:w-56 max-w-[150px] mt-4 mb-0 drop-shadow-lg" />
                    <p class="text-sm font-medium">Click a marker on the map to view details of the road defect report.</p>
                </div>
            </div>
        </template>

        <!-- Show this content when no report is selected AND not showing group reports -->
        <template x-if="!selectedReport || !selectedReport.id && showingGroupReports">
            <div id="group-marker-details"
                 x-transition:leave="transition ease-in-out duration-500"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="bg-white transition-all transform origin-top">

                <ul id="group-report-list" class="space-y-2 text-sm text-gray-700"> </ul>
            </div>
        </template>
    </x-slot:comprehensive_or_group_report_information>

    <x-slot:individual_report_information>
        <!-- Show this content when a report is selected -->
        <template x-if="selectedReport && selectedReport.id">
            <div class="w-full">
                <div x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-90 translate-y-6"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="w-full transition-all transform origin-top">
                    <div class="justify-center items-center text-center font-semibold text-md mt-0 text-[#4D4F50] bg-white w-full py-3 z-[99999]">Road Defect Report Information</div>
                    <div class="flex flex-col md:flex-row text-xs mb-3 rounded-2">
                        <div class="flex flex-wrap w-full md:order-1">
                            <div class="w-full">
                                <div class="text-xs lg:text-sm flex justify-start items-start w-full">
                                    <div class="w-2/4 font-medium text-gray-600">Report ID:</div>
                                    <div class="w-2/4" x-text="selectedReport.id"></div>
                                </div>
                                <div class="text-xs lg:text-sm flex justify-start items-start w-full">
                                    <div class="w-2/4 font-medium text-gray-600">Type of Defect:</div>
                                    <div class="w-2/4" x-text="selectedReport.defect"></div>
                                </div>
                                <div class="text-xs lg:text-sm flex justify-start items-start w-full">
                                    <div class="w-2/4 font-medium text-gray-600">Report Count:</div>
                                    <div class="w-2/4" x-text="selectedReport.report_count"></div>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="text-xs lg:text-sm flex justify-start items-start w-full">
                                    <div class="w-2/4 font-medium text-gray-600">Status:</div>
                                    <div class="w-2/4 font-semibold"
                                         :class="{
                                             'text-green-600': selectedReport.status === 'Repaired',
                                             'text-yellow-500': selectedReport.status === 'Ongoing',
                                             'text-red-600': selectedReport.status === 'Unfixed'
                                         }" x-text="selectedReport.status"></div>
                                </div>
{{--                                <div class="text-xs lg:text-sm flex justify-start items-start w-full">--}}
{{--                                    <div class="w-2/4 font-medium text-gray-600">Severity:</div>--}}
{{--                                    <div class="w-2/4" x-text="selectedReport.severity_label"></div>--}}
{{--                                </div>--}}
                                <div class="text-xs lg:text-sm flex justify-start items-start w-full">
                                    <div class="w-2/4 font-medium text-gray-600">Location:</div>
                                    <div class="w-2/4" x-text="selectedReport.location"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4 justify-center rounded-2">
                        <div class="w-full shadow p-5 rounded-md">
                            <h2 class="font-semibold text-sm text-center mb-3">REPORTED <br/> Road Defect Information</h2>
                            <x-admin.admin-view-road-defect-report-image-modal>
                                <x-slot:image_title>
                                    Reported Captured Road Photo
                                </x-slot:image_title>
                                <x-slot:image>
                                    <img
                                        :src="selectedReport.image_annotated ? `/storage/${selectedReport.image_annotated}` : '/images/placeholder.png'"
                                        alt="Defect Image"
                                        class="w-full h-auto
                                         rounded-[10px] object-contain cursor-pointer"
                                        :style="`transform: scale(${scale}) translate(${offsetX}px, ${offsetY}px); transform-origin: center; transition: transform 0.1s ease-out;`"/>
                                </x-slot:image>
                            </x-admin.admin-view-road-defect-report-image-modal>
                            <div class="mb-2 text-xs lg:text-sm flex w-full">
                                <div class="w-2/4 text-gray-600">First Reporter Full Name: </div>
                                <div class="w-2/4" x-text="selectedReport.reporter_full_name"></div>


                            </div>
                            <div class="text-xs lg:text-sm flex w-full">
                                <div class="w-2/4 text-gray-600">Date Reported:</div>
                                <div class="w-2/4">
                                    <template x-if="selectedReport.date">
                                        <span x-text="new Date(selectedReport.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })"></span>
                                    </template>
                                    <template x-if="!selectedReport.date && selectedReport.created_at">
                                        <span x-text="new Date(selectedReport.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })"></span>
                                    </template>
                                    <template x-if="!selectedReport.date && !selectedReport.created_at">
                                        <span>N/A</span>
                                    </template>
                                </div>
                            </div>

                            <!-- Time Reported -->
                            <div class="mb-4 text-xs lg:text-sm flex w-full">
                                <div class="w-2/4 text-gray-600">Time Reported:</div>
                                <div class="w-2/4">
                                    <span x-text="
                                        selectedReport.time
                                            ? (() => {
                                                let [h, m, s] = selectedReport.time.split(':');
                                                h = parseInt(h);
                                                const period = h >= 12 ? 'PM' : 'AM';
                                                h = h % 12 || 12;
                                                return `${h.toString().padStart(2, '0')}:${m}:${s} ${period}`;
                                            })()
                                            : selectedReport.created_at
                                                ? new Date(selectedReport.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true })
                                                : 'N/A'
                                    "></span>
                                </div>
                            </div>


                            <!-- Coordinates -->
                            <template x-if="selectedReport.lng && selectedReport.lat">
                                <div>
                                    <div class="mb-1 font-semibold text-gray-600 text-xs lg:text-sm">Coordinates</div>
                                    <div class="mb-2 text-xs lg:text-sm flex w-full">
                                        <div class="w-2/4 text-gray-600">Latitude:</div>
                                        <div class="w-2/4" x-text="selectedReport.lat"></div>
                                    </div>
                                    <div class="mb-2 text-xs lg:text-sm flex w-full">
                                        <div class="w-2/4 text-gray-600">Longitude:</div>
                                        <div class="w-2/4" x-text="selectedReport.lng"></div>
                                    </div>
                                </div>
                            </template>

                        </div>
                        <div class="w-full shadow p-5 rounded-md" x-show="selectedReport.updated_image">
                            <h2 class="font-semibold text-green-500 text-sm text-center mb-3">UPDATED <br/> Road Defect Information</h2>
                            <x-admin.admin-view-road-defect-report-image-modal>
                                <x-slot:image_title>
                                    Updated Captured Road Photo
                                </x-slot:image_title>
                                <x-slot:image>
                                    <img
                                        :src="selectedReport.updated_image ? `/storage/${selectedReport.updated_image}` : '/images/placeholder.png'"
                                        alt="Updated Road Concern Image"
                                        class="-full h-auto rounded-[10px] object-contain cursor-pointer"
                                        :style="`transform: scale(${scale}) translate(${offsetX}px, ${offsetY}px); transform-origin: center; transition: transform 0.1s ease-out;`"/>
                                </x-slot:image>
                            </x-admin.admin-view-road-defect-report-image-modal>
                            <div class="mb-2 text-xs lg:text-sm flex w-full">
                                <div class="w-2/4 text-gray-600">Updated By: </div>
                                <div class="w-2/4" x-text="selectedReport.updater_full_name"></div>
                            </div>
                            <div class="text-xs lg:text-sm flex w-full">
                                <div class="w-2/4 text-gray-600">Date Reported:</div>
                                <div class="w-2/4">
                                    <template x-if="selectedReport.updated_on">
                                        <span x-text="selectedReport.updated_on"></span>
                                    </template>

                                </div>
                            </div>



                            <!-- Coordinates -->
                            <template x-if="selectedReport.lng && selectedReport.lat">
                                <div>
                                    <div class="mb-1 font-semibold text-gray-600 text-xs lg:text-sm">Coordinates</div>
                                    <div class="mb-2 text-xs lg:text-sm flex w-full">
                                        <div class="w-2/4 text-gray-600">Latitude:</div>
                                        <div class="w-2/4" x-text="selectedReport.lat"></div>
                                    </div>
                                    <div class="mb-2 text-xs lg:text-sm flex w-full">
                                        <div class="w-2/4 text-gray-600">Longitude:</div>
                                        <div class="w-2/4" x-text="selectedReport.lng"></div>
                                    </div>
                                </div>
                            </template>
                        </div>

                    </div>
                </div>
            </div>

        </template>
    </x-slot:individual_report_information>

</x-admin.map-view-reports-page-content-base>

<script>
    var geoJsonData = @json($geoJsonData);

    window.mapComponent = function() {
        return {
            reports: @json($reports),
            severityMap: {
                1: 'Shallow',
                2: 'Tolerable',
                3: 'Serious',
                4: 'Dangerous',
                5: 'None'
            },
            selectedMarker: null,
            selectedMarkerReportId: null,
            markerLayers: [],
            selectedReport: null,
            newStatus: '',
            statuses: ['Repaired', 'Ongoing', 'Unfixed'],
            pulseLocationIcon: null,
            pulseInterval: null,
            filteredReports: [],
            showingGroupReports: false,

            init() {
                this.reports.forEach(report => report.severity = this.severityMap[report.severity] || 'Unknown severity');
                this.filteredReports = [...this.reports]; // Initially show all reports
                this.loadGeoJSON("{{ asset('geoJSON/tagumCityRoad.json') }}");

                const bounds = [
                    [7.301174, 125.998883],
                    [7.547656, 126.003003],
                    [7.549017, 125.732412],
                    [7.275292, 125.693952]
                ];

                this.map = L.map('map', {
                    center: [7.448, 125.809],
                    zoom: 12,
                    minZoom: 12,
                    maxZoom: 22,
                    maxBounds: bounds,
                    maxBoundsViscosity: 1.0,
                    zoomControl: false
                });

                this.map.fitBounds(bounds);
                setTimeout(() => this.map.invalidateSize(), 0);

                L.control.zoom({ position: 'bottomleft' }).addTo(this.map);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 30 }).addTo(this.map);

                // Define Tagum City boundary coordinates
                this.tagumBoundary = [
                    [7.498994, 125.867658],
                    [7.498696, 125.867551],
                    [7.498414, 125.867497],
                    [7.497973, 125.867529],
                    [7.497244, 125.867841],
                    [7.49635, 125.868393],
                    [7.49559, 125.869321],
                    [7.494026, 125.871739],
                    [7.494005, 125.872684],
                    [7.494154, 125.873413],
                    [7.494175, 125.876139],
                    [7.491643, 125.876955],
                    [7.490665, 125.878672],
                    [7.49041, 125.879423],
                    [7.490324, 125.880367],
                    [7.490622, 125.881397],
                    [7.492197, 125.883286],
                    [7.492367, 125.884204],
                    [7.49192, 125.885148],
                    [7.491367, 125.885878],
                    [7.490133, 125.886651],
                    [7.488133, 125.887273],
                    [7.486878, 125.887208],
                    [7.486155, 125.886736],
                    [7.485453, 125.88577],
                    [7.484878, 125.885255],
                    [7.483751, 125.884826],
                    [7.483112, 125.884397],
                    [7.482559, 125.883581],
                    [7.482219, 125.882036],
                    [7.482006, 125.879932],
                    [7.481346, 125.877529],
                    [7.480793, 125.876863],
                    [7.479219, 125.874267],
                    [7.47907, 125.873773],
                    [7.478538, 125.873408],
                    [7.477921, 125.873344],
                    [7.477538, 125.872871],
                    [7.477177, 125.871176],
                    [7.476496, 125.869548],
                    [7.476349, 125.86882],
                    [7.477264, 125.866995],
                    [7.477795, 125.86648],
                    [7.478518, 125.866158],
                    [7.480943, 125.865235],
                    [7.481496, 125.86457],
                    [7.481645, 125.863797],
                    [7.481517, 125.86311],
                    [7.480113, 125.860599],
                    [7.479943, 125.859805],
                    [7.479731, 125.857916],
                    [7.47939, 125.857229],
                    [7.47888, 125.856435],
                    [7.47216, 125.851756],
                    [7.469544, 125.850361],
                    [7.468885, 125.84946],
                    [7.4688, 125.848794],
                    [7.468906, 125.848086],
                    [7.469374, 125.847742],
                    [7.470203, 125.847463],
                    [7.47065, 125.846948],
                    [7.471054, 125.84506],
                    [7.47065, 125.841218],
                    [7.470543, 125.840745],
                    [7.470243, 125.840431],
                    [7.469669, 125.840303],
                    [7.468308, 125.840882],
                    [7.466479, 125.841075],
                    [7.464607, 125.841505],
                    [7.463799, 125.841848],
                    [7.463161, 125.84247],
                    [7.462311, 125.843007],
                    [7.461715, 125.843007],
                    [7.461226, 125.842771],
                    [7.46046, 125.840066],
                    [7.46029, 125.838671],
                    [7.460758, 125.836718],
                    [7.462162, 125.835409],
                    [7.462587, 125.834765],
                    [7.462651, 125.834078],
                    [7.462438, 125.833391],
                    [7.461588, 125.832812],
                    [7.46029, 125.832812],
                    [7.456632, 125.832468],
                    [7.455867, 125.832619],
                    [7.455314, 125.832855],
                    [7.454846, 125.833413],
                    [7.45425, 125.834014],
                    [7.453527, 125.834314],
                    [7.45339, 125.834168],
                    [7.452885, 125.833502],
                    [7.45231, 125.83214],
                    [7.452204, 125.831829],
                    [7.452151, 125.831582],
                    [7.452044, 125.830509],
                    [7.451869, 125.829839],
                    [7.451688, 125.829586],
                    [7.451406, 125.829452],
                    [7.451199, 125.829527],
                    [7.451108, 125.829844],
                    [7.451029, 125.830026],
                    [7.449342, 125.831357],
                    [7.448661, 125.831416],
                    [7.448236, 125.831308],
                    [7.44764, 125.830182],
                    [7.447066, 125.829554],
                    [7.446763, 125.829495],
                    [7.446034, 125.829683],
                    [7.445231, 125.829565],
                    [7.443906, 125.829238],
                    [7.443268, 125.829179],
                    [7.441374, 125.829136],
                    [7.439959, 125.82934],
                    [7.439348, 125.829324],
                    [7.438385, 125.829039],
                    [7.436965, 125.828058],
                    [7.436199, 125.827907],
                    [7.433087, 125.827972],
                    [7.431033, 125.827902],
                    [7.430608, 125.828009],
                    [7.429778, 125.828127],
                    [7.429236, 125.828304],
                    [7.428884, 125.828519],
                    [7.428358, 125.829007],
                    [7.427932, 125.829581],
                    [7.427033, 125.831834],
                    [7.425879, 125.833336],
                    [7.425299, 125.835664],
                    [7.425751, 125.83752],
                    [7.425666, 125.838132],
                    [7.424804, 125.839044],
                    [7.423815, 125.839387],
                    [7.423251, 125.839409],
                    [7.422772, 125.839226],
                    [7.421549, 125.83825],
                    [7.421368, 125.838143],
                    [7.420666, 125.838325],
                    [7.419847, 125.838164],
                    [7.419389, 125.838422],
                    [7.419251, 125.838829],
                    [7.418634, 125.839323],
                    [7.4179, 125.839602],
                    [7.416283, 125.839849],
                    [7.415261, 125.840331],
                    [7.414708, 125.840771],
                    [7.414059, 125.841286],
                    [7.410442, 125.842906],
                    [7.409112, 125.843904],
                    [7.408399, 125.844258],
                    [7.406633, 125.84429],
                    [7.405877, 125.844151],
                    [7.405441, 125.844119],
                    [7.404601, 125.843947],
                    [7.403633, 125.843325],
                    [7.402856, 125.842005],
                    [7.402558, 125.840106],
                    [7.401994, 125.839537],
                    [7.400015, 125.838314],
                    [7.397313, 125.837198],
                    [7.396515, 125.836608],
                    [7.396047, 125.835975],
                    [7.395015, 125.835053],
                    [7.393887, 125.834227],
                    [7.39327, 125.833915],
                    [7.392823, 125.834001],
                    [7.392025, 125.83442],
                    [7.391397, 125.834817],
                    [7.39045, 125.834903],
                    [7.388492, 125.834645],
                    [7.38746, 125.834388],
                    [7.386567, 125.834033],
                    [7.386093, 125.833738],
                    [7.385066, 125.833572],
                    [7.383151, 125.832843],
                    [7.382422, 125.832853],
                    [7.382247, 125.832784],
                    [7.381672, 125.832719],
                    [7.381034, 125.832789],
                    [7.380523, 125.832923],
                    [7.379922, 125.833073],
                    [7.379465, 125.833057],
                    [7.378911, 125.83288],
                    [7.378619, 125.832623],
                    [7.378299, 125.832231],
                    [7.377837, 125.831759],
                    [7.377688, 125.8317],
                    [7.377544, 125.831727],
                    [7.377235, 125.831818],
                    [7.37681, 125.831898],
                    [7.37598, 125.832134],
                    [7.375581, 125.832258],
                    [7.375224, 125.832542],
                    [7.374719, 125.832746],
                    [7.373916, 125.833358],
                    [7.373378, 125.833578],
                    [7.372793, 125.833604],
                    [7.372293, 125.833336],
                    [7.371554, 125.831754],
                    [7.371016, 125.831196],
                    [7.370665, 125.831062],
                    [7.369665, 125.831115],
                    [7.369218, 125.831292],
                    [7.368665, 125.83126],
                    [7.368175, 125.831464],
                    [7.367633, 125.831979],
                    [7.366851, 125.833057],
                    [7.366436, 125.834092],
                    [7.366239, 125.835605],
                    [7.365861, 125.836227],
                    [7.365244, 125.836903],
                    [7.364302, 125.83766],
                    [7.3636, 125.837901],
                    [7.362669, 125.837901],
                    [7.360812, 125.837284],
                    [7.360232, 125.836952],
                    [7.36185, 125.833949],
                    [7.36236, 125.83146],
                    [7.363127, 125.83103],
                    [7.363212, 125.830494],
                    [7.363446, 125.830258],
                    [7.364403, 125.828369],
                    [7.364957, 125.826352],
                    [7.364978, 125.826266],
                    [7.365244, 125.826319],
                    [7.365808, 125.824377],
                    [7.365941, 125.824061],
                    [7.366574, 125.823835],
                    [7.366952, 125.823599],
                    [7.367175, 125.823497],
                    [7.367266, 125.823433],
                    [7.367239, 125.823095],
                    [7.367266, 125.822896],
                    [7.367356, 125.822515],
                    [7.367329, 125.822161],
                    [7.367202, 125.821721],
                    [7.367223, 125.821094],
                    [7.367138, 125.820396],
                    [7.367032, 125.820294],
                    [7.366606, 125.820251],
                    [7.366494, 125.819979],
                    [7.363174, 125.819974],
                    [7.362169, 125.819416],
                    [7.362871, 125.818187],
                    [7.363696, 125.817425],
                    [7.363733, 125.817409],
                    [7.363877, 125.817602],
                    [7.363802, 125.817688],
                    [7.366005, 125.817704],
                    [7.366063, 125.817511],
                    [7.365872, 125.815],
                    [7.365789, 125.814684],
                    [7.365717, 125.814448],
                    [7.365619, 125.814247],
                    [7.365401, 125.8137],
                    [7.365377, 125.813512],
                    [7.365252, 125.813284],
                    [7.365119, 125.813147],
                    [7.36531, 125.813008],
                    [7.36601, 125.812101],
                    [7.366077, 125.811905],
                    [7.366071, 125.811675],
                    [7.365901, 125.811345],
                    [7.365917, 125.811253],
                    [7.366082, 125.811221],
                    [7.366212, 125.810859],
                    [7.366013, 125.80995],
                    [7.365866, 125.80973],
                    [7.365497, 125.809394],
                    [7.365441, 125.809231],
                    [7.365106, 125.809801],
                    [7.364935, 125.811046],
                    [7.36416, 125.811161],
                    [7.363416, 125.810861],
                    [7.36265, 125.810549],
                    [7.360119, 125.807308],
                    [7.360012, 125.806997],
                    [7.358587, 125.805055],
                    [7.358148, 125.804027],
                    [7.357531, 125.802965],
                    [7.356669, 125.802139],
                    [7.354351, 125.799005],
                    [7.353415, 125.798447],
                    [7.350902, 125.79538],
                    [7.349604, 125.793374],
                    [7.34772, 125.791271],
                    [7.345784, 125.78817],
                    [7.345639, 125.787989],
                    [7.345336, 125.787897],
                    [7.345222, 125.787895],
                    [7.344918, 125.788082],
                    [7.344626, 125.788179],
                    [7.34445, 125.788184],
                    [7.344288, 125.788066],
                    [7.344248, 125.787994],
                    [7.344235, 125.787862],
                    [7.344293, 125.787766],
                    [7.344333, 125.787688],
                    [7.344461, 125.787629],
                    [7.344575, 125.787701],
                    [7.34465, 125.787854],
                    [7.344822, 125.7879],
                    [7.345011, 125.78782],
                    [7.345139, 125.787645],
                    [7.345163, 125.787409],
                    [7.345086, 125.787146],
                    [7.34495, 125.786784],
                    [7.344096, 125.785107],
                    [7.344072, 125.784987],
                    [7.34408, 125.784134],
                    [7.343963, 125.783822],
                    [7.343713, 125.783372],
                    [7.343463, 125.783249],
                    [7.343106, 125.783195],
                    [7.342564, 125.782894],
                    [7.341782, 125.781677],
                    [7.340968, 125.779928],
                    [7.340771, 125.77988],
                    [7.340404, 125.77885],
                    [7.340303, 125.778227],
                    [7.339414, 125.775497],
                    [7.339169, 125.775207],
                    [7.339185, 125.774939],
                    [7.339361, 125.77488],
                    [7.339276, 125.774204],
                    [7.339175, 125.774161],
                    [7.339185, 125.773796],
                    [7.339318, 125.773775],
                    [7.33926, 125.772842],
                    [7.33926, 125.772412],
                    [7.339212, 125.772246],
                    [7.339063, 125.772074],
                    [7.338962, 125.771989],
                    [7.338648, 125.77164],
                    [7.337578, 125.76943],
                    [7.336546, 125.766383],
                    [7.335972, 125.765101],
                    [7.334891, 125.762386],
                    [7.333886, 125.759361],
                    [7.33387, 125.758889],
                    [7.33395, 125.758567],
                    [7.333939, 125.758535],
                    [7.333673, 125.758004],
                    [7.333141, 125.757414],
                    [7.332024, 125.75523],
                    [7.330986, 125.753186],
                    [7.32963, 125.751593],
                    [7.328545, 125.749684],
                    [7.327459, 125.747806],
                    [7.327002, 125.747109],
                    [7.326799, 125.746744],
                    [7.326687, 125.746738],
                    [7.326549, 125.746765],
                    [7.326421, 125.746867],
                    [7.32641, 125.747007],
                    [7.326304, 125.747028],
                    [7.325783, 125.745945],
                    [7.325517, 125.745553],
                    [7.325277, 125.745408],
                    [7.325064, 125.745328],
                    [7.324756, 125.744995],
                    [7.324575, 125.744544],
                    [7.324516, 125.744212],
                    [7.32441, 125.743997],
                    [7.324532, 125.743831],
                    [7.324708, 125.743611],
                    [7.324729, 125.74331],
                    [7.324601, 125.743047],
                    [7.324548, 125.742811],
                    [7.324676, 125.742575],
                    [7.324708, 125.742393],
                    [7.324665, 125.742173],
                    [7.324192, 125.74241],
                    [7.324139, 125.742657],
                    [7.324, 125.742657],
                    [7.323489, 125.741949],
                    [7.322915, 125.741048],
                    [7.322851, 125.740812],
                    [7.322734, 125.740608],
                    [7.322042, 125.739535],
                    [7.321287, 125.738783],
                    [7.320776, 125.738268],
                    [7.320425, 125.737925],
                    [7.318626, 125.736638],
                    [7.316455, 125.737883],
                    [7.315689, 125.738012],
                    [7.315902, 125.737025],
                    [7.315008, 125.737669],
                    [7.313987, 125.737196],
                    [7.313604, 125.737626],
                    [7.311007, 125.737883],
                    [7.310113, 125.737068],
                    [7.310709, 125.735952],
                    [7.313476, 125.734664],
                    [7.318158, 125.734921],
                    [7.321138, 125.736939],
                    [7.325054, 125.738527],
                    [7.330076, 125.738182],
                    [7.334971, 125.738719],
                    [7.339696, 125.741465],
                    [7.341547, 125.742881],
                    [7.343654, 125.74198],
                    [7.34525, 125.740864],
                    [7.346421, 125.740671],
                    [7.349656, 125.742002],
                    [7.351826, 125.743847],
                    [7.354976, 125.743825],
                    [7.357743, 125.743911],
                    [7.359083, 125.741336],
                    [7.360743, 125.740607],
                    [7.363403, 125.741444],
                    [7.365425, 125.740607],
                    [7.367915, 125.739684],
                    [7.369234, 125.740414],
                    [7.370575, 125.743375],
                    [7.371894, 125.744233],
                    [7.375363, 125.746014],
                    [7.376661, 125.748417],
                    [7.377406, 125.749598],
                    [7.379938, 125.75052],
                    [7.391067, 125.749748],
                    [7.393216, 125.748246],
                    [7.394749, 125.747859],
                    [7.395834, 125.747902],
                    [7.39694, 125.748889],
                    [7.399473, 125.754962],
                    [7.40292, 125.75582],
                    [7.403771, 125.756464],
                    [7.406261, 125.75949],
                    [7.411687, 125.76112],
                    [7.414474, 125.761292],
                    [7.415985, 125.7623],
                    [7.416581, 125.761399],
                    [7.413389, 125.757837],
                    [7.412708, 125.755949],
                    [7.414282, 125.754254],
                    [7.416049, 125.753675],
                    [7.417091, 125.75597],
                    [7.418262, 125.757151],
                    [7.420134, 125.756485],
                    [7.422411, 125.752494],
                    [7.426049, 125.749748],
                    [7.432305, 125.750005],
                    [7.434007, 125.750134],
                    [7.435837, 125.750928],
                    [7.438603, 125.749576],
                    [7.439411, 125.748889],
                    [7.440326, 125.749447],
                    [7.440667, 125.750864],
                    [7.440816, 125.753009],
                    [7.442624, 125.755005],
                    [7.445497, 125.756872],
                    [7.447539, 125.755692],
                    [7.449007, 125.753953],
                    [7.450709, 125.752237],
                    [7.453156, 125.751035],
                    [7.453986, 125.751507],
                    [7.45339, 125.753717],
                    [7.454922, 125.755327],
                    [7.457986, 125.754876],
                    [7.461773, 125.755262],
                    [7.465815, 125.760798],
                    [7.467177, 125.760477],
                    [7.468347, 125.755756],
                    [7.469773, 125.753417],
                    [7.471368, 125.752666],
                    [7.47507, 125.75346],
                    [7.476708, 125.753417],
                    [7.4779, 125.752838],
                    [7.478283, 125.751014],
                    [7.478134, 125.749104],
                    [7.478559, 125.747066],
                    [7.478453, 125.745564],
                    [7.479325, 125.742581],
                    [7.482942, 125.740006],
                    [7.485218, 125.739577],
                    [7.488048, 125.739512],
                    [7.488665, 125.739233],
                    [7.510045, 125.768738],
                    [7.510216, 125.852766],
                    [7.498983, 125.852637],
                    [7.498919, 125.867679], // Back to start point
                ];

                // Draw the boundary polygon
                L.polygon(this.tagumBoundary, {
                    color: "red",         // Border color
                    weight: 2,          // Border thickness
                    opacity: 0.8,
                    fillColor: "#ffcccc", // Light fill color
                    fillOpacity: 0,
                    dashArray: '4, 4'     // Dashed line style
                }).addTo(this.map);


                this.addLegend();
                this.map.on('moveend', () => this.enforceBounds(bounds));

                this.map.on('zoomend', () => {
                    console.log("🔄 Map zoom changed, updating markers...");
                    this.updateMarkers();
                });

                // this.loadGeoJSON();
                this.updateMarkers();
                // ✅ Revalidate size every 5 seconds in case of dynamic changes
                setInterval(() => {
                    this.map.invalidateSize();
                }, 500); // Adjust timing as needed
            },

            loadGeoJSON() {
                fetch("{{ asset('geoJSON/tagumCityRoad.json') }}")
                    .then(response => response.json())
                    .then(data => {
                        if (this.geoJSONLayer) this.map.removeLayer(this.geoJSONLayer);

                        this.geoJSONLayer = L.geoJSON(data, {
                            style: function(feature) {
                                return {
                                    color: feature.properties.color || "#0000FF",  // Use blue from JSON
                                    weight: 5,
                                    opacity: 1
                                };
                            },
                            onEachFeature: (feature, layer) => {
                                if (feature.properties) {
                                    layer.bindPopup("Road Info: " + JSON.stringify(feature.properties));
                                }
                            }
                        }).addTo(this.map);

                        // ✅ Ensure we fit bounds properly
                        if (this.geoJSONLayer.getBounds().isValid()) {
                            this.map.fitBounds(this.geoJSONLayer.getBounds());
                        } else {
                            console.warn("🚨 GeoJSON Bounds are invalid!");
                        }

                        // ✅ Force a map refresh
                        setTimeout(() => {
                            this.map.invalidateSize();
                        }, 500);
                    })
                    .catch(error => console.error("Error loading GeoJSON:", error));
            },

            updateMarkers() {
                console.log("🔄 Updating markers...");

                // Remove all previous markers
                this.markerLayers.forEach(layer => this.map.removeLayer(layer));
                this.markerLayers = [];

                const zoomLevel = this.map.getZoom();
                console.log(`🔍 Current Zoom Level: ${zoomLevel}`);

                const selectedIcon = L.icon({
                    iconUrl: '/storage/images/IRoadCheck_Logo.png',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                    popupAnchor: [0, -32]
                });

                if (zoomLevel > 14) {
                    console.log("📌 Zoom level > 14: Displaying individual markers...");

                    this.filteredReports.forEach(report => {
                        if (!report.lat || !report.lng) {
                            console.warn(`⚠️ Invalid coordinates for Report ID ${report.id}:`, report);
                            return;
                        }

                        const lat = parseFloat(report.lat);
                        const lng = parseFloat(report.lng);

                        const defaultIcon = L.divIcon({
                            className: 'custom-circle-marker',
                            html: `<div style="
                                        width: 12px;
                                        height: 12px;
                                        background-color: ${this.getStatusColor(report.status)};
                                        border: 2px solid black;
                                        border-radius: 50%;
                                    "></div>`,
                            iconSize: [12, 12],
                            iconAnchor: [6, 6]
                        });

                        const isSelected = this.selectedMarkerReportId === report.id;
                        const iconToUse = isSelected ? selectedIcon : defaultIcon;

                        const marker = L.marker([lat, lng], { icon: iconToUse }).addTo(this.map);

                        const popupContent = document.createElement('div');
                        popupContent.className = "max-w-[400px] h-auto rounded-lg shadow-lg text-[12px] font-sans text-white grid grid-row-2";
                        popupContent.innerHTML = `
                            <div class="max-h-[300px] bg-white text-gray-700 rounded-t-xl leading-4">
                                <div class="px-3 py-2 flex items-center space-x-2 border-b border-gray-300">
                                    <span style="background-color: ${this.getStatusColor(report.status)}; width: 10px; height: 10px; border-radius: 50%; display: inline-block;"></span>
                                    <h3 class="font-semibold text-xs">Report ID: ${report.id}</h3>
                                </div>
                                <div class="px-3 py-2">
                                    <div>Type of Road Defect: ${report.defect}</div>
<div>Report count: ${report.report_count}</div>
                                        <div>Date Reported: ${report.date}</div>
                                    <div class="font-semibold">Location: ${report.location}</div>
                                </div>
                            </div>
                            <div class="max-h-[30px] text-white text-center rounded-b-lg cursor-pointer py-2" style="background-color: #f87171">
                                <button id="view-report-${report.id}" class="text-[12px] font-semibold">
                                    View Report
                                </button>
                            </div>
                        `;

                        const popup = L.popup({ autoClose: false, closeOnClick: false }).setContent(popupContent);
                        marker.bindPopup(popup);

                        popupContent.addEventListener("mouseover", () => {
                            popupContent.style.backgroundColor = '#f1f1f1';
                        });

                        popupContent.addEventListener("mouseout", () => {
                            popupContent.style.backgroundColor = '';
                        });

                        popupContent.querySelector(`#view-report-${report.id}`).addEventListener("click", (e) => {
                            e.stopPropagation();
                            this.viewReport(report.id);
                        });

                        marker.on('mouseover', () => {
                            marker.openPopup();
                            setTimeout(() => marker.closePopup(), 1500);
                        });

                        marker.on('mouseout', () => {
                            marker.closePopup();
                        });

                        marker.on('click', () => {
                            if (this.selectedMarker && this.selectedMarker !== marker) {
                                this.selectedMarker.setIcon(defaultIcon);
                            }

                            marker.setIcon(selectedIcon);
                            marker.openPopup();
                            this.viewReport(report.id);

                            this.selectedMarker = marker;
                            this.selectedMarkerReportId = report.id;
                        });

                        this.markerLayers.push(marker);
                    });

                } else {
                    console.log("📌 Zoom level <= 14: Grouping markers by barangay...");

                    let groupedByBarangay = {};

                    this.filteredReports.forEach(report => {
                        if (!groupedByBarangay[report.barangay]) {
                            groupedByBarangay[report.barangay] = [];
                        }
                        groupedByBarangay[report.barangay].push(report);
                    });

                    Object.entries(groupedByBarangay).forEach(([barangay, reports]) => {
                        let latSum = 0, lngSum = 0;

                        reports.forEach(r => {
                            latSum += parseFloat(r.lat);
                            lngSum += parseFloat(r.lng);
                        });

                        const avgLat = latSum / reports.length;
                        const avgLng = lngSum / reports.length;
                        const reportCount = reports.length;

                        const marker = L.circleMarker([avgLat, avgLng], {
                            color: 'red',
                            weight: 1,
                            radius: 8 + Math.log2(reportCount) * 2,
                            fillColor: 'blue',
                            fillOpacity: 0.7
                        }).addTo(this.map);

                        const label = L.divIcon({
                            className: 'label-icon hoverable-group-marker',
                            html: `<div class="marker-label">${reportCount}</div>`,
                            iconSize: [30, 30],
                            iconAnchor: [15, 15]
                        });

                        const labelMarker = L.marker([avgLat, avgLng], { icon: label }).addTo(this.map);

                        labelMarker.bindTooltip(`<strong>${barangay}</strong>`, {
                            permanent: false,
                            direction: 'top',
                            className: 'barangay-tooltip',
                            offset: [0, -25],
                            sticky: true,
                            opacity: 1
                        });

                        if (this.selectedBarangay === barangay) {
                            const el = labelMarker.getElement();
                            if (el) el.classList.add('selected-marker');
                            labelMarker.setZIndexOffset(1000);
                        }

                        labelMarker.on('click', () => {
                            this.showGroupedReports(barangay, reports, reportCount);
                            this.selectedBarangay = barangay;

                            this.markerLayers.forEach(m => {
                                const el = m.getElement?.();
                                if (el) el.classList.remove('selected-marker');
                                if (m.setZIndexOffset) m.setZIndexOffset(0);
                            });

                            const el = labelMarker.getElement();
                            if (el) el.classList.add('selected-marker');
                            labelMarker.setZIndexOffset(1000);
                        });

                        labelMarker.on('mouseover', function () {
                            const el = this.getElement();
                            if (el) el.classList.add('hovered-marker');
                        });

                        labelMarker.on('mouseout', function () {
                            const el = this.getElement();
                            if (el) el.classList.remove('hovered-marker');
                        });

                        this.markerLayers.push(marker, labelMarker);
                    });

                    console.log(`✅ Total barangay markers added: ${this.markerLayers.length}`);
                }
            },

            closeAllPopups() {
                this.markerLayers.forEach(marker => {
                    if (marker.closePopup) {
                        marker.closePopup();
                    }
                });
            },

            showGroupedReports(barangay, reports, reportCount) {
                this.selectedReport = null;
                this.showingGroupReports = true;

                const panel = document.getElementById("group-marker-details");
                const list = document.getElementById("group-report-list");
                list.innerHTML = "";

                if (Array.isArray(reports) && reports.length > 0) {
                    const groupBy = (array, key) => {
                        return array.reduce((result, item) => {
                            const groupKey = item[key] ?? 'Unknown';
                            result[groupKey] = (result[groupKey] || []);
                            result[groupKey].push(item);
                            return result;
                        }, {});
                    };

                    const groupedByDefect = groupBy(reports, "defect");
                    // const groupedByStatus = groupBy(reports, "status");
                    const groupedByLocation = groupBy(reports, "location");

                    const summary = document.createElement("div");
                    summary.className = "mb-4 text-sm text-gray-700";
                    summary.innerHTML = `
                        <div class="flex items-center justified-center mb-4">
                            <h2 class="flex justify-center items-center text-center font-semibold text-md mt-0 py-3 text-[#4D4F50] bg-white w-full py-3 z-[99999]">Barangay ${barangay} Road Defect Report</h2>
                        </div>
                        <p class="mb-1"><span class="font-semibold text-gray-800">Barangay:</span> ${barangay}</p>
                        <p class="mb-1"><span class="font-semibold">Total Reports:</span> <span class="text-orange-500 font-bold">${reportCount}</span></p>
                        <p class="mt-2 font-semibold">Grouped By:</p>
                        <ul class="ml-4 list-disc text-gray-600 text-sm">
                            <li>Defect Types: ${Object.keys(groupedByDefect).length}</li>
                            <li>Locations: ${Object.keys(groupedByLocation).length}</li>
                        </ul>
                    `;
                    list.appendChild(summary);

                    // Sort defect groups by earliest report date (optional)
                    Object.entries(groupedByDefect)
                        .sort(([, a], [, b]) => {
                            const getEarliestDate = (arr) => {
                                return Math.min(...arr.map(r => {
                                    if (r.date_reported) return new Date(r.date_reported).getTime();
                                    if (r.formatted_date) return new Date(r.formatted_date).getTime();
                                    return Infinity;
                                }));
                            };
                            return getEarliestDate(a) - getEarliestDate(b); // oldest to newest
                        })
                        .forEach(([defect, items], index) => {
                            const count = items.length;
                            const container = document.createElement("div");
                            container.className = "bg-white shadow-md p-4 rounded-lg border mb-3";

                            const header = document.createElement("div");
                            header.className = "flex justify-between items-center cursor-pointer";
                            header.innerHTML = `
                                <div>
                                    <h3 class="font-bold">${defect}</h3>
                                    <p class="text-sm text-gray-500">Total Reports: <span class="font-semibold text-orange-500">${count}</span></p>
                                </div>
                                <button id="toggle-${index}" class="text-sm text-blue-500 hover:underline focus:outline-none">
                                    View Details
                                </button>
                            `;
                            container.appendChild(header);

                            const detailPanel = document.createElement("div");
                            detailPanel.id = `details-${index}`;
                            detailPanel.className = "mt-3 space-y-2 hidden";

                            // ✅ Sort reports by actual date, oldest to newest
                            items.sort((a, b) => {
                                const getDate = (r) => {
                                    if (r.date_reported) return new Date(r.date_reported);
                                    if (r.formatted_date) return new Date(r.formatted_date);
                                    return new Date(0);
                                };
                                return getDate(a) - getDate(b);
                            });

                            items.forEach(report => {
                                const detail = document.createElement("div");
                                detail.className = "p-3 bg-gray-50 rounded-md border hover:bg-gray-100 cursor-pointer transition hover:scale-105";
                                detail.dataset.reportId = report.id;

                                function getDaysAgo(dateString) {
                                    const date = new Date(dateString);
                                    const now = new Date();
                                    const diffTime = Math.abs(now - date);
                                    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

                                    return diffDays === 0
                                        ? 'Today'
                                        : diffDays === 1
                                            ? '1 day ago'
                                            : `${diffDays} days ago`;
                                }

// Then:
                                detail.innerHTML = `
    <p><span class="font-semibold">Location:</span> ${report.location ?? 'Unknown Location'}</p>
    <p><span class="font-semibold">Date Reported:</span> ${report.formatted_date ?? 'Unknown Date'}</p>
    <p><span class="font-semibold">Status:</span> ${report.status ?? 'Unknown Status'}</p>
    <p><span class="font-semibold">Report count:</span> ${report.report_count ?? 'No count'}</p>
    <p><span class="font-semibold">Days Ago:</span> ${getDaysAgo(report.date)}</p>


                                `;

                                detail.addEventListener("click", () => {
                                    this.viewReport(report.id);
                                    document.getElementById("group-marker-details").classList.add("hidden");
                                });

                                detailPanel.appendChild(detail);
                            });

                            container.appendChild(detailPanel);
                            list.appendChild(container);

                            setTimeout(() => {
                                const toggleBtn = document.getElementById(`toggle-${index}`);
                                const panel = document.getElementById(`details-${index}`);
                                toggleBtn?.addEventListener("click", () => {
                                    panel.classList.toggle("hidden");
                                    toggleBtn.textContent = panel.classList.contains("hidden") ? "View Details" : "Hide Details";
                                });
                            }, 0);
                        });

                    panel.classList.remove("hidden");
                } else {
                    console.warn("No reports found for this barangay.");
                }
            },

            filterMarkers(query) {
                this.showingGroupReports = true;
                this.selectedReport = null;

                const searchQuery = query.toLowerCase().trim();

                const startDate = this.dateFilterStart ? new Date(this.dateFilterStart) : null;
                const endDate = this.dateFilterEnd ? new Date(this.dateFilterEnd) : null;

                this.filteredReports = this.reports.filter(report => {
                    console.log(query, typeof query)
                    console.log(report.severity, typeof report.severity)
                    const matchesQuery = [
                            report.defect?.toLowerCase(),
                            report.location?.toLowerCase(),
                            report.status?.toLowerCase(),
                            report.date?.toLowerCase(),
                        ].some(field => field?.includes(searchQuery))

                    const reportDateStr = report.date_reported || report.formatted_date || report.date;
                    const reportDate = reportDateStr ? new Date(reportDateStr) : null;

                    let passesDateFilter = true;

                    if (startDate && reportDate) {
                        passesDateFilter = passesDateFilter && reportDate >= startDate;
                    }

                    if (endDate && reportDate) {
                        passesDateFilter = passesDateFilter && reportDate <= endDate;
                    }

                    return matchesQuery && passesDateFilter;
                });

                this.updateMarkers();

                if (this.filteredReports.length === 1) {
                    const matchedReport = this.filteredReports[0];
                    const latLng = [matchedReport.lat, matchedReport.lng];

                    this.map.setView(latLng, 18, { animate: true });
                    matchedReport.marker.openPopup();

                    const markerElement = matchedReport.marker._icon;
                    if (markerElement) {
                        markerElement.classList.add('bounce-marker');
                        setTimeout(() => markerElement.classList.remove('bounce-marker'), 500);
                    }

                    if (this.pulseLocationIcon) {
                        this.map.removeLayer(this.pulseLocationIcon);
                        clearInterval(this.pulseInterval);
                    }

                    this.pulseLocationIcon = L.circle(latLng, {
                        color: 'blue',
                        fillColor: 'blue',
                        fillOpacity: 0.15,
                        radius: 10
                    }).addTo(this.map);
                    this.pulseLocationIcon.bringToBack();

                    let pulseSize = 10;
                    this.pulseInterval = setInterval(() => {
                        pulseSize = pulseSize === 10 ? 15 : 10;
                        this.pulseLocationIcon.setRadius(pulseSize);
                    }, 1000);
                }

                const list = document.getElementById("group-report-list");
                const panel = document.getElementById("group-marker-details");
                list.innerHTML = "";

                if (!this.filteredReports.length) {
                    list.innerHTML = `<p class="text-gray-500 italic text-sm">No reports match "${query}".</p>`;
                    panel.classList.remove("hidden");
                    return;
                }

                const groupedByBarangay = this.filteredReports.reduce((acc, report) => {
                    const barangay = report.barangay || 'Unknown Barangay';
                    if (!acc[barangay]) acc[barangay] = [];
                    acc[barangay].push(report);
                    return acc;
                }, {});

                let groupIndex = 0;

                Object.entries(groupedByBarangay).forEach(([barangay, reportsInBarangay]) => {
                    const barangayContainer = document.createElement("div");
                    barangayContainer.className = "mb-6";

                    const barangayHeading = document.createElement("h2");
                    barangayHeading.className = "text-md font-bold text-blue-700 mb-2";
                    barangayHeading.textContent = `Barangay: ${barangay}`;
                    barangayContainer.appendChild(barangayHeading);

                    const defectGroups = reportsInBarangay.reduce((acc, report) => {
                        const defect = report.defect || 'Unknown Defect';
                        if (!acc[defect]) acc[defect] = [];
                        acc[defect].push(report);
                        return acc;
                    }, {});

                    Object.entries(defectGroups).forEach(([defect, reports]) => {
                        const container = document.createElement("div");
                        container.className = "bg-white shadow-md p-4 rounded-lg border mb-3";

                        const header = document.createElement("div");
                        header.className = "flex justify-between items-center cursor-pointer";

                        const toggleId = `toggle-${groupIndex}`;
                        const detailsId = `details-${groupIndex}`;

                        header.innerHTML = `
                            <div>
                                <h3 class="font-bold">${defect}</h3>
                                <p class="text-sm text-gray-500">Total Reports: <span class="font-semibold text-orange-500">${reports.length}</span></p>
                            </div>
                            <button id="${toggleId}" class="text-sm text-blue-500 hover:underline focus:outline-none">
                                View Details
                            </button>
                        `;

                        container.appendChild(header);

                        const detailPanel = document.createElement("div");
                        detailPanel.id = detailsId;
                        detailPanel.className = "mt-3 space-y-2 hidden";

                        reports.sort((a, b) => {
                            const getDate = (r) => {
                                if (r.date_reported) return new Date(r.date_reported);
                                if (r.formatted_date) return new Date(r.formatted_date);
                                return new Date(0);
                            };
                            return getDate(a) - getDate(b);
                        });

                        reports.forEach(report => {
                            const detail = document.createElement("div");
                            detail.className = "p-3 bg-gray-50 rounded-md border hover:bg-gray-100 cursor-pointer transition hover:scale-105";
                            detail.dataset.reportId = report.id;

                            detail.innerHTML = `
                                <p><span class="font-semibold">Location:</span> ${report.location ?? 'Unknown Location'}</p>
                                <p><span class="font-semibold">Date Reported:</span> ${report.formatted_date ?? 'Unknown Date'}</p>
                                <p><span class="font-semibold">Status:</span> ${report.status ?? 'Unknown Status'}</p>
                                <p><span class="font-semibold">Severity:</span> ${report.severity_label ?? report.severity ?? 'Unknown Severity'}</p>
                                <p><span class="font-semibold">Days Ago:</span> ${report.days_ago ?? 'N/A'}</p>
                            `;

                            detail.addEventListener("click", () => {
                                this.viewReport(report.id);
                                panel.classList.add("hidden");
                            });

                            detailPanel.appendChild(detail);
                        });

                        container.appendChild(detailPanel);
                        barangayContainer.appendChild(container);

                        const toggleBtn = header.querySelector(`#${toggleId}`);
                        toggleBtn.addEventListener("click", () => {
                            detailPanel.classList.toggle("hidden");
                            toggleBtn.textContent = detailPanel.classList.contains("hidden") ? "View Details" : "Hide Details";
                        });

                        groupIndex++;
                    });

                    list.appendChild(barangayContainer);
                });

                panel.classList.remove("hidden");
            },

            addLegend() {
                const legend = L.control({ position: 'topleft' });

                legend.onAdd = () => {
                    const div = L.DomUtil.create('div', 'hidden lg:block legend absolute top-0 left-4 z-50 w-32');
                    div.innerHTML = `
                        <div class="bg-[#3F4243] bg-opacity-90 text-white px-3 py-1 mt-2 rounded shadow-lg text-[12px]">
                            <h3 class="font-semibold mb-2 text-center border-b border-b-white p-1">Legend</h3>
                            <ul>
                                <li class="leading-6"><span class="inline-block w-3 h-3 bg-green-500 mr-2 rounded-full"></span>Repaired</li>
                                <li class="leading-6"><span class="inline-block w-3 h-3 bg-yellow-500 mr-2 rounded-full"></span>Ongoing</li>
                                <li class="leading-6"><span class="inline-block w-3 h-3 bg-red-500 mr-2 rounded-full"></span>Unfixed</li>
                            </ul>
                        </div>
                    `;
                    return div;
                };

                legend.addTo(this.map);
            },

            enforceBounds(bounds) {
                if (!this.map.getBounds().intersects(bounds)) {
                    this.map.fitBounds(bounds, { animate: true });
                }
            },

            viewReport(id) {
                const newSelectedReport = this.reports.find(report => report.id === id);
                if (!newSelectedReport) return;

                const latLng = L.latLng(newSelectedReport.lat, newSelectedReport.lng);
                const currentZoom = this.map.getZoom();
                const currentCenter = this.map.getCenter();
                const isSameLocation = currentCenter.distanceTo(latLng) < 10;
                const isSameZoom = currentZoom === 16;

                const openPopupAfterMove = () => {
                    newSelectedReport.marker?.openPopup();
                    this.map.off('moveend', openPopupAfterMove);

                    // ✅ Only update markers after the move and popup open
                    this.selectedMarkerReportId = id;
                    this.updateMarkers();
                };

                // If moving the map is required
                if (!isSameLocation || !isSameZoom) {
                    this.map.on('moveend', openPopupAfterMove);
                    this.map.setView(latLng, 16, { animate: true });
                } else {
                    newSelectedReport.marker?.openPopup();

                    // ✅ Directly update markers here if no movement is needed
                    this.selectedMarkerReportId = id;
                    setTimeout(() => this.updateMarkers(), 100); // slight delay for smoothness
                }

                // Clear previous pulse
                if (this.pulseLocationIcon) {
                    this.map.removeLayer(this.pulseLocationIcon);
                    clearInterval(this.pulseInterval);
                    this.pulseLocationIcon = null;
                    this.pulseInterval = null;
                }

                this.selectedReport = newSelectedReport;
                this.showingGroupReports = true;
                this.selectedMarkerReportId = id; // Set selected report ID for marker icon change

                // ✅ Re-run updateMarkers to update the selected marker's icon
                this.updateMarkers();

                // Function to create pulse
                const createPulse = () => {
                    if (!this.selectedReport) return;

                    const pulseLatLng = L.latLng(this.selectedReport.lat, this.selectedReport.lng);
                    this.pulseLocationIcon = L.circle(pulseLatLng, {
                        color: 'blue',
                        fillColor: 'blue',
                        fillOpacity: 0.15,
                        radius: 40
                    }).addTo(this.map);
                    this.pulseLocationIcon.bringToBack();

                    let pulseSize = 40;
                    this.pulseInterval = setInterval(() => {
                        if (!this.pulseLocationIcon) return;
                        pulseSize = pulseSize === 40 ? 55 : 40;
                        this.pulseLocationIcon.setRadius(pulseSize);
                    }, 1000);
                };

                // Create pulse initially (if zoom > 14)
                if (this.map.getZoom() > 14) {
                    createPulse();
                }

                // Set up global zoomend listener once
                if (!this._zoomPulseHandlerInitialized) {
                    this.map.on('zoomend', () => {
                        const zoom = this.map.getZoom();

                        if (zoom <= 14 && this.pulseLocationIcon) {
                            this.map.removeLayer(this.pulseLocationIcon);
                            clearInterval(this.pulseInterval);
                            this.pulseLocationIcon = null;
                            this.pulseInterval = null;
                        } else if (zoom > 14 && !this.pulseLocationIcon && this.selectedReport) {
                            createPulse();
                        }
                    });

                    this._zoomPulseHandlerInitialized = true; // Prevent duplicate listeners
                }
            },

            updateReportStatus() {
                if (this.newStatus) {
                    // Update the status of the selected report
                    this.selectedReport.status = this.newStatus;
                    const statusColor = getStatusColor(this.newStatus);

                    // Update marker style to reflect the new status
                    this.selectedReport.marker.setStyle({
                        color: 'black',
                        weight: 1,
                        fillColor: statusColor,
                        fillOpacity: 1,
                    });

                    // Update popup content dynamically
                    const popupContent = `
                        <div class="max-w-[400px] h-auto rounded-lg shadow-lg text-[12px] font-sans text-white grid grid-row-2">
                            <div class="max-h-[300px] bg-white text-gray-700 rounded-t-xl leading-4">
                                <div class="px-3 py-2 flex items-center space-x-2 border-b border-gray-300">
                                    <span style="background-color: ${statusColor}; width: 10px; height: 10px; border-radius: 50%; display: inline-block;"></span>
                                    <h3 class="font-semibold text-xs">Report ID: ${this.selectedReport.id}</h3>
                                </div>
                                <div class="px-3 py-2">
                                    <div>Type of Road Defect: ${this.selectedReport.defect}</div>
                                    <div>Reported Date: ${this.selectedReport.date}</div>
                                    <div class="font-semibold">Location: ${this.selectedReport.location}</div>
                                </div>
                            </div>
                            <div class="max-h-[30px] text-white text-center rounded-b-lg cursor-pointer py-2">
                                <button class="text-[12px] font-semibold">
                                    View Report
                                </button>
                            </div>
                        </div>
                    `;

                    // Update the popup content
                    this.selectedReport.marker.setPopupContent(popupContent);

                    // Show a success notification
                    showNotification(`Status updated to: ${this.newStatus}`, 'success');

                    // Clear the input field for new status
                    this.newStatus = '';
                } else {
                    // Show an error notification
                    showNotification('Please select a status before updating.', 'error');
                }
            },

            getStatusColor(status) {
                return {
                    'Repaired': '#28a745',
                    'Ongoing': '#ffc107',
                    'Unfixed': '#dc3545'
                }[status] || '#dc3545';
            },
        };
    }
</script>


