<x-admin.road-defect-reports-modal-content-base modal_name="view-road-defect-reports-modal">

    <x-slot:trigger>
        <div class="hidden">
            <div x-on:view-road-defect-reports-modal-shown.window="open = true"></div>
        </div>
    </x-slot:trigger>

    <x-slot:header>
        View Report Details
    </x-slot:header>

    <x-slot:body>
        @if($report)
            <!-- Body -->
            <div class="h-[70vh] overflow-y-auto pl-2">
                <div class="p-2 mx-2">
                    <div class="flex flex-col md:flex-row text-xs mb-2 bg-[#FBFBFB] p-3 rounded-2 drop-shadow">
                        <!-- General Road Defect Information -->
                        <div class="flex flex-wrap w-full md:w-6/10 lg:w-8/10 order-2 md:order-1">
                            <div class="w-full lg:w-2/5">
                                <div class="text-xs lg:text-sm flex justify-start items-start w-full">
                                    <div class="w-2/4 md:w-1/4 lg:w-2/5 font-medium text-gray-600">Report ID:</div>
                                    <div class="w-2/4 md:w-3/4 lg:w-3/5">{{ $report->id }}</div>
                                </div>

                                <div class="text-xs lg:text-sm flex justify-start items-start w-full">
                                    <div class="w-2/4 md:w-1/4 lg:w-2/5 font-medium text-gray-600">Type of Defect:</div>
                                    <div class="w-2/4 md:w-3/4 lg:w-3/5">{{ $report->defect }}</div>
                                </div>

                                <div class="text-xs lg:text-sm flex justify-start items-start w-full">
                                    <div class="w-2/4 md:w-1/4 lg:w-2/5 font-medium text-gray-600">Report Count:</div>
                                    <div class="w-2/4 md:w-3/4 lg:w-3/5">{{ $report->report_count }}</div>
                                </div>
                            </div>

                            <div class="w-full lg:w-3/5">
                                <div class="text-xs lg:text-sm flex justify-start items-start w-full">
                                    <div class="w-2/4 md:w-1/4 lg:w-1/5 font-medium text-gray-600">Status:</div>
                                    <div class="w-2/4 md:w-3/4 lg:w-4/5 font-semibold
                                        {{ $report->status === 'Repaired' ? 'text-green-600' : '' }}
                                        {{ $report->status === 'Ongoing' ? 'text-yellow-500' : '' }}
                                        {{ $report->status === 'Unfixed' ? 'text-red-600' : '' }}">
                                        {{ $report->status }}
                                    </div>
                                </div>

                                <div class="text-xs lg:text-sm flex justify-start items-start w-full">
{{--                                    <div class="w-2/4 md:w-1/4 lg:w-1/5 font-medium text-gray-600">Severity:</div>--}}
{{--                                    <div class="w-2/4 md:w-3/4 lg:w-4/5">--}}
{{--                                        {{--}}
{{--                                            DB::table('severities')--}}
{{--                                                ->where('id', $report->severity)--}}
{{--                                                ->value('label')--}}
{{--                                        }}--}}
{{--                                    </div>--}}

                                </div>

                                <div class="text-xs lg:text-sm flex justify-start items-start w-full">
                                    <div class="w-2/4 md:w-1/4 lg:w-1/5 font-medium text-gray-600">Location:</div>
                                    <div class="w-2/4 md:w-3/4 lg:w-4/5">{{ $report->location }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Export Button -->
                        <x-admin.view-report-modal-export-button>
                            <x-slot:dropdown_buttons>
                                <button
                                    loading="lazy"
                                    type="button"
                                    wire:click="exportToExcel()"
                                    wire:loading.attr="disabled"
                                    x-data="{ loading: false }"
                                    x-on:click="loading = true"
                                    x-on:export-to-excel-finished.window="loading = false"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                    <span x-cloak x-show="!loading">Export to Excel</span>
                                    <span class="animate-pulse italic text-green-600" x-cloak x-show="loading">Exporting...</span>
                                    <x-loading-indicator class="lazyload text-green-600 w-4 h-4 ml-2" x-cloak x-show="loading"/>
                                </button>

                                <button
                                    loading="lazy"
                                    type="button"
                                    wire:click="exportToPDF()"
                                    wire:loading.attr="disabled"
                                    x-data="{ loading: false }"
                                    x-on:click="loading = true"
                                    x-on:export-to-pdf-finished.window="loading = false"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                    <span x-cloak x-show="!loading">Export to PDF</span>
                                    <span class="animate-pulse italic text-green-600" x-cloak x-show="loading">Exporting...</span>
                                    <x-loading-indicator class="lazyload text-green-600 w-4 h-4 ml-2" x-cloak x-show="loading"/>
                                </button>
                            </x-slot:dropdown_buttons>
                        </x-admin.view-report-modal-export-button>
                    </div>

                    <div class="flex flex-wrap gap-4 justify-center rounded-2">

                        {{-- Reported Road Defect Information --}}
                        <div class="w-full lg:w-[48%] shadow p-5 rounded-md">
                            <h2 class="font-semibold text-sm text-center mb-3">REPORTED <br/> Road Defect Information</h2>

                            <x-admin.admin-view-road-defect-report-image-modal>
                                <x-slot:image_title>
                                    Reported Captured Road Photo
                                </x-slot:image_title>
                                <x-slot:image>
                                    <img
                                        src="{{ $image }}"
                                        alt="Road Defect"
                                        class="w-full h-full object-contain pointer-events-none"
                                        :style="`
                                            transform: scale(${scale}) translate(${offsetX}px, ${offsetY}px);
                                            transform-origin: center;
                                            transition: transform 0.1s ease-out;
                                        `"
                                    />
                                </x-slot:image>
                            </x-admin.admin-view-road-defect-report-image-modal>

                            <!-- First Road Defect Reporter Full Name -->
                            <div class="mb-2 text-xs lg:text-sm flex w-full">
                                <div class="w-2/4 text-gray-600">First Reporter Full Name:</div>
                                @php

                                    $reporter = DB::table('users')->where('id', $report->reporter_id)->first();
                                    $firstName = $reporter ? Crypt::decryptString($reporter->first_name) : 'Unknown';
                                    $lastName = $reporter ? Crypt::decryptString($reporter->last_name) : '';
                                @endphp

                                <div class="w-2/4">
                                    {{ $firstName }} {{ $lastName }}
                                </div>

                            </div>

                            <!-- Date Reported -->
                            <div class="text-xs lg:text-sm flex w-full">
                                <div class="w-2/4 text-gray-600">Date Reported:</div>
                                <div class="w-2/4">
                                    @if($report && $report->date)
                                        {{ \Carbon\Carbon::parse($report->date)->format('F j, Y') }}
                                    @elseif($report && $report->created_at)
                                        {{ $report->created_at->format('F j, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>

                            <!-- Time Reported -->
                            <div class="mb-4 text-xs lg:text-sm flex w-full">
                                <div class="w-2/4 text-gray-600">Time Reported:</div>
                                <div class="w-2/4">
                                    @if($report && $report->time)
                                        {{ \Carbon\Carbon::parse($report->time)->format('h:i:s A') }}
                                    @elseif($report && $report->created_at)
                                        {{ $report->created_at->format('h:i:s A') }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>

                            <!-- Coordinates -->
                            @if($report->lng && $report->lat)
                                <div class="mb-1 font-semibold text-gray-600 text-xs lg:text-sm">Coordinates</div>
                                <div class="mb-2 text-xs lg:text-sm flex w-full">
                                    <div class="w-2/4 text-gray-600">Latitude:</div>
                                    <div class="w-2/4">{{ $report->lat }}</div>
                                </div>
                                <div class="mb-2 text-xs lg:text-sm flex w-full">
                                    <div class="w-2/4 text-gray-600">Longitude:</div>
                                    <div class="w-2/4">{{ $report->lng }}</div>
                                </div>
                            @endif
                        </div>

                        {{-- Updated Road Defect Information --}}
                        <div class="w-full lg:w-[48%] shadow p-5 rounded-md">

                            @if($report->updated_on)
                                <!-- Title -->
                                <h2 class="font-semibold text-green-500 text-sm text-center mb-3">
                                    UPDATED <br/> Road Defect Information
                                </h2>

                                <x-admin.admin-view-road-defect-report-image-modal>
                                    <x-slot:image_title>
                                        Updated Captured Road Photo
                                    </x-slot:image_title>
                                    <x-slot:image>
                                        <img
                                            src="{{ $image2 }}"
                                            alt="Road Defect"
                                            class="w-full h-full object-contain pointer-events-none"
                                            :style="`
                                            transform: scale(${scale}) translate(${offsetX}px, ${offsetY}px);
                                            transform-origin: center;
                                            transition: transform 0.1s ease-out;
                                        `"
                                        />
                                    </x-slot:image>
                                </x-admin.admin-view-road-defect-report-image-modal>

                                <!-- Staff Information -->
                                <div class="mb-2 text-xs lg:text-sm flex w-full">
                                    <div class="w-2/4 text-gray-600">Updated By Staff:</div>
                                    @php
                                        $updater = DB::table('users')->where('id', $report->updater_id)->first();
                                        $updaterFirst = $updater ? Crypt::decryptString($updater->first_name) : 'Unknown';
                                        $updaterLast = $updater ? Crypt::decryptString($updater->last_name) : '';
                                    @endphp

                                    <div class="w-2/4">
                                        {{ $updaterFirst }} {{ $updaterLast }}
                                    </div>

                                </div>

                                <!-- Date Updated -->
                                <div class="mb-2 text-xs lg:text-sm flex w-full">
                                    <div class="w-2/4 text-gray-600">Date Updated:</div>
                                    <div class="w-2/4">
                                        {{ \Carbon\Carbon::parse($report->updated_at)->format('F j, Y') }}
                                    </div>
                                </div>

                                <!-- Time Updated -->
                                <div class="mb-4 text-xs lg:text-sm flex w-full">
                                    <div class="w-2/4 text-gray-600">Time Updated:</div>
                                    <div class="w-2/4">
                                        {{ \Carbon\Carbon::parse($report->updated_at)->format('h:i:s A') }}
                                    </div>
                                </div>

                                <!-- Coordinates -->
                                @if($report->lng && $report->lat)
                                    <div class="mb-1 font-semibold text-gray-600 text-xs lg:text-sm">Coordinates</div>
                                    <div class="mb-2 text-xs lg:text-sm flex w-full">
                                        <div class="w-2/4 text-gray-600">Latitude:</div>
                                        <div class="w-2/4">{{ $report->lat }}</div>
                                    </div>
                                    <div class="mb-2 text-xs lg:text-sm flex w-full">
                                        <div class="w-2/4 text-gray-600">Longitude:</div>
                                        <div class="w-2/4">{{ $report->lng }}</div>
                                    </div>
                                @endif
                                {{--                            @endif--}}
                            @else
                                <h2 class="font-semibold text-gray-500 text-sm text-center mb-3">
                                    UPDATED <br/> Road Defect Information
                                </h2>

                                <!-- Not Updated Yet Notice -->
                                <div class="flex flex-col items-center justify-center h-[70vh] min-h-[20vh] max-h-[70vh] border-2 border-dashed border-gray-400 bg-gray-100 rounded-md p-6 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                                    </svg>
                                    <p class="text-sm text-gray-600">No updated information available yet.</p>
                                </div>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
        @endif
    </x-slot:body>

    <x-slot:footer>
    </x-slot:footer>

</x-admin.road-defect-reports-modal-content-base>

