<!-- Main Content -->
<div class="-mt-5 bg-none overflow-y-auto h-auto py-2">

    <div class="text-[#202020] bg-[#FBFBFB] mt-1 pt-0 pb-2 rounded-lg drop-shadow"
         x-data="{
            tileCount: 4,
            view: localStorage.getItem('mapReportView') || 'grid',

            setView(newView) {
                this.view = newView;
                localStorage.setItem('mapReportView', newView);
            },

            resetViewIfNeeded() {
                // If this page is NOT 'manage-tagging', remove stored view preference
                if (!window.location.pathname.includes('admin.road-defect-reports')) {
                    localStorage.removeItem('mapReportView');
                }
            }
        }"
         x-init="resetViewIfNeeded()">

        <div class="pl-2">
            <!-- View Toggle Buttons -->
            <div class="absolute mb-5 flex items-end justify-start top-3 right-7 z-10">
                <button @click="setView('grid')"
                        x-data="{
                                tooltipVisible: false,
                                tooltipText: 'Map View',
                                tooltipArrow: true,
                                tooltipPosition: 'top'
                            }"
                        x-on:mouseenter="tooltipVisible = true"
                        x-on:mouseleave="tooltipVisible = false"
                        :class="{ 'bg-[#4AA76F] text-white': view === 'grid', 'hover:bg-[#4AA76F] hover:text-white text-[#4AA76F]': view !== 'grid' }"
                        class="px-2 py-2 mr-2 text-white rounded flex items-center relative">
                    <!-- Tooltip -->
                    <div x-show="tooltipVisible"
                         :class="{ 'top-0 left-1/2 -translate-x-1/2 -mt-0.5 -translate-y-full' : tooltipPosition == 'top' }"
                         class="absolute w-auto text-sm"
                         x-cloak>
                        <div x-show="tooltipVisible" x-transition
                             class="px-2 py-1 rounded bg-white text-[#4AA76F] border border-[#4AA76F] shadow-lg transform transition-transform duration-150 ease-in-out">
                            <p x-text="tooltipText"
                               class="flex-shrink-0 block text-xs whitespace-nowrap text-[#4AA76F]"></p>
                            <div x-show="tooltipArrow"
                                 class="absolute inline-flex items-center justify-center overflow-hidden"
                                 :class="{ 'bottom-0 -translate-x-1/2 left-1/2 w-2.5 translate-y-full' : tooltipPosition == 'top' }">
                                <div class="w-1.5 h-1.5 transform bg-white border border-[#4AA76F] -rotate-45 origin-top-left"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="20" height="20" class="fill-current">
                        <path d="M302.8 312C334.9 271.9 408 174.6 408 120C408 53.7 354.3 0 288 0S168 53.7 168 120c0 54.6 73.1 151.9 105.2 192c7.7 9.6 22 9.6 29.6 0zM416 503l144.9-58c9.1-3.6 15.1-12.5 15.1-22.3L576 152c0-17-17.1-28.6-32.9-22.3l-116 46.4c-.5 1.2-1 2.5-1.5 3.7c-2.9 6.8-6.1 13.7-9.6 20.6L416 503zM15.1 187.3C6 191 0 199.8 0 209.6L0 480.4c0 17 17.1 28.6 32.9 22.3L160 451.8l0-251.4c-3.5-6.9-6.7-13.8-9.6-20.6c-5.6-13.2-10.4-27.4-12.8-41.5l-122.6 49zM384 255c-20.5 31.3-42.3 59.6-56.2 77c-20.5 25.6-59.1 25.6-79.6 0c-13.9-17.4-35.7-45.7-56.2-77l0 194.4 192 54.9L384 255z"/>
                    </svg>
                </button>

                <button @click="setView('table')"
                        x-data="{
                            tooltipVisible: false,
                            tooltipText: 'Table View',
                            tooltipArrow: true,
                            tooltipPosition: 'top'
                        }"
                        x-on:mouseenter="tooltipVisible = true"
                        x-on:mouseleave="tooltipVisible = false"
                        :class="{ 'bg-[#4AA76F] text-white': view === 'table', 'hover:bg-[#4AA76F] hover:text-white text-[#4AA76F]': view !== 'table' }"
                        class="px-2 py-2 text-white rounded flex items-center relative">
                    <!-- Tooltip -->
                    <div x-show="tooltipVisible"
                         :class="{ 'top-0 left-1/2 -translate-x-1/2 -mt-0.5 -translate-y-full' : tooltipPosition == 'top' }"
                         class="absolute w-auto text-sm"
                         x-cloak>
                        <div x-show="tooltipVisible" x-transition
                             class="px-2 py-1 rounded bg-white text-[#4AA76F] border border-[#4AA76F] shadow-lg transform transition-transform duration-150 ease-in-out">
                            <p x-text="tooltipText"
                               class="flex-shrink-0 block text-xs whitespace-nowrap text-[#4AA76F]"></p>
                            <div x-show="tooltipArrow"
                                 class="absolute inline-flex items-center justify-center overflow-hidden"
                                 :class="{ 'bottom-0 -translate-x-1/2 left-1/2 w-2.5 translate-y-full' : tooltipPosition == 'top' }">
                                <div class="w-1.5 h-1.5 transform bg-white border border-[#4AA76F] -rotate-45 origin-top-left"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20" class="fill-current">
                        <path d="M16 96a48 48 0 1 1 96 0A48 48 0 1 1 16 96zM64 208a48 48 0 1 1 0 96 48 48 0 1 1 0-96zm0 160a48 48 0 1 1 0 96 48 48 0 1 1 0-96zM191.5 54.4c5.5-4.2 12.3-6.4 19.2-6.4L424 48c13.3 0 24 10.7 24 24l0 48c0 13.3-10.7 24-24 24l-213.3 0c-6.9 0-13.7-2.2-19.2-6.4l-38.4-28.8c-8.5-6.4-8.5-19.2 0-25.6l38.4-28.8zM153.1 243.2l38.4-28.8c5.5-4.2 12.3-6.4 19.2-6.4L488 208c13.3 0 24 10.7 24 24l0 48c0 13.3-10.7 24-24 24l-277.3 0c-6.9 0-13.7-2.2-19.2-6.4l-38.4-28.8c-8.5-6.4-8.5-19.2 0-25.6zm0 160l38.4-28.8c5.5-4.2 12.3-6.4 19.2-6.4L424 368c13.3 0 24 10.7 24 24l0 48c0 13.3-10.7 24-24 24l-213.3 0c-6.9 0-13.7-2.2-19.2-6.4l-38.4-28.8c-8.5-6.4-8.5-19.2 0-25.6z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Grid View -->
        <div x-show="view === 'grid'" class="scroll-hidden w-full relative px-2">

            {{ $map_container }}

        </div>

        <!--Table View-->
        <div x-show="view === 'table'" class="text-[#202020] rounded-[6px] mt-2 pt-2 h-auto w-[46vh] md:w-full">

            {{-- Header --}}
            <div class="px-2 text-center md:text-start"
            x-data = "{showFilters: false}"
            >
                <div class="flex">
                    {{ $search_container }}

                    <div class="mt-4 pl-4">
                        <!-- Toggle Filters Button -->
                        <button
                            @click="showFilters = !showFilters"
                            class="ml-auto bg-white text-[#F59E0B] px-4 py-2 rounded-md flex items-center shadow-md hover:text-[#D97706] hover:shadow-lg transition-all border border-[#F59E0B]">
                            <span class="text-xs" x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
                            <svg class="ml-2 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"
                                      x-show="!showFilters" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 15l-7-7-7 7"
                                      x-show="showFilters" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex">
                    <!-- Dropdown Filters container -->
                    <div x-show="showFilters"
                         class="mt-4 md:pl-2 flex justify-start items-start lg:gap-2 gap-1 mb-0 mr-auto">
                        {{ $dropdown_filters_container }}
                    </div>

                    <!--Page description-->
                    <div x-show="!showFilters" class="pl-6 md:pl-0 flex justify-center items-center md:justified-start md:items-start lg:gap-2 gap-1 md:mb-0 mt-7 mr-auto">
                        {{ $table_page_description }}
                    </div>

                    {{-- Action Buttons container --}}
                    {{ $action_buttons_container }}
                </div>
            </div>

            {{-- Body --}}
            <div class="mt-2 px-4 mb-2">

                {{-- Table Container --}}
                <div>
                    {{ $table_container }}
                </div>

                {{-- Pagination Container --}}
                <div class="mt-4 px-6">
                    {{ $pagination_container }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Container --}}
    <div>
        {{ $modal_container }}
    </div>

    {{ $wire_loading_container }}

</div>




