<x-staff.staff-navigation page_title="Update History" action="{{ route('staff.report-history') }}" placeholder="Search..."
    name="search" wire:model.live="search">

    <div class="flex justify-center items-center mx-auto     sm:justify-start sm:items-start">
        <x-staff.crud-page-content-base>

            <x-slot:page_description>
                A list of all your road defect reports history in the iRoadCheck System.
            </x-slot:page_description>

            <x-slot:dropdown_filters_container>
                @include('livewire.modals.staff.filters-report-history')
            </x-slot:dropdown_filters_container>

            <x-slot:action_buttons_container>
                <button type="button" wire:click="exportHistoryReports"
                    class="mt-5 flex gap-x-[8px] w-auto text-xs px-[14px] py-[10px] font-normal tracking-wider text-[#FFFFFF] bg-gradient-to-b from-[#84D689] to-green-500 rounded-full hover:drop-shadow hover:bg-[#4AA76F] hover:scale-105 hover:ease-in-out hover:duration-300 transition-all duration-300 [transition-timing-function:cubic-bezier (0.175,0.885,0.32,1.275)] active:-translate-y-1 active:scale-x-90 active:scale-y-110 hidden lg:flex md:flex">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="15" height="15"
                        class="mt-0.5 mr-0">
                        <path fill="#ffffff" fill-opacity="1" fill-rule="nonzero"
                            d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 128-168 0c-13.3 0-24 10.7-24 24s10.7 24 24 24l168 0 0 112c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 64zM384 336l0-48 110.1 0-39-39c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l80 80c9.4 9.4 9.4 24.6 0 33.9l-80 80c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l39-39L384 336zm0-208l-128 0L256 0 384 128z" />
                    </svg>
                    <span class="ml-0 mt-0 text-[#FFFFFF] text-md">Export History</span>
                </button>
            </x-slot:action_buttons_container>


            <x-slot:table_container wire:key="reports-table-{{ now() }}">
                <div
                   class="inline-block w-[40vh] md:w-full lg:w-full h-[50vh] md:h-[52vh] lg:h-[55vh] xl:h-[62vh] xl:max-h-[100vh] overflow-y-auto align-middle drop-shadow rounded-b-md">
                    <table class="w-[100px] md:w-full text-left divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="sticky top-0 z-10 bg-white py-3 pl-4 text-xs font-semibold text-[#757575]">
                                    <button class="flex items-end" wire:click="toggleSorting('id')">
                                        No.
                                        <div x-cloak x-show="$wire.sort_by === 'id'">
                                            <x-arrow-up x-cloak x-show="$wire.sort_direction === 'asc'" />
                                            <x-arrow-down x-cloak x-show="$wire.sort_direction === 'desc'" />
                                        </div>
                                    </button>
                                </th>
                                <th scope="col"
                                    class="sticky top-0 z-10 bg-white py-3 px-4 text-xs font-semibold text-[#757575]">
                                    <button class="flex items-end" wire:click="toggleSorting('defect')">
                                        Type of Defect
                                        <div x-cloak x-show="$wire.sort_by === 'defect'">
                                            <x-arrow-up x-cloak x-show="$wire.sort_direction === 'asc'" />
                                            <x-arrow-down x-cloak x-show="$wire.sort_direction === 'desc'" />
                                        </div>
                                    </button>
                                </th>
                                <th scope="col"
                                    class="sticky top-0 z-10 bg-white py-3 px-4 text-xs font-semibold text-[#757575]">
                                    <button class="flex items-end" wire:click="toggleSorting('barangay')">
                                        Barangay
                                        <div x-cloak x-show="$wire.sort_by === 'barangay'">
                                            <x-arrow-up x-cloak x-show="$wire.sort_direction === 'asc'" />
                                            <x-arrow-down x-cloak x-show="$wire.sort_direction === 'desc'" />
                                        </div>
                                    </button>
                                </th>
                                <th scope="col"
                                    class="sticky top-0 z-10 bg-white py-3 px-4 text-xs font-semibold text-[#757575]">
                                    <button class="flex items-end" wire:click="toggleSorting('date')">
                                        Date
                                        <div x-cloak x-show="$wire.sort_by === 'date'">
                                            <x-arrow-up x-cloak x-show="$wire.sort_direction === 'asc'" />
                                            <x-arrow-down x-cloak x-show="$wire.sort_direction === 'desc'" />
                                        </div>
                                    </button>
                                </th>
                                <th scope="col"
                                    class="sticky top-0 z-10 bg-white py-3 px-4 text-xs font-semibold text-[#757575]">
                                    <button class="flex items-end" wire:click="toggleSorting('status')">
                                        Status
                                        <div x-cloak x-show="$wire.sort_by === 'status'">
                                            <x-arrow-up x-cloak x-show="$wire.sort_direction === 'asc'" />
                                            <x-arrow-down x-cloak x-show="$wire.sort_direction === 'desc'" />
                                        </div>
                                    </button>
                                </th>
                                <th class="sticky top-0 z-10 bg-white py-3 px-4 text-xs font-semibold text-[#757575] hidden lg:block md:block">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300 bg-white relative">
                            @forelse ($reports as $report)
                                <tr x-data
                                    @click=" if (window.innerWidth < 768) { $wire.viewHistoryReports({{ $report->id }}) }"
                                    class="cursor-pointer md:cursor-default {{ $loop->iteration % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100">

                                    <td class="pl-4 py-3 text-[11px] lg:text-xs">
                                        {{ $sort_direction === 'desc'
                                            ? $reports->firstItem() + $loop->index
                                            : $reports->total() - $reports->firstItem() - $loop->index + 1 }}
                                    </td>
                                    <td class="px-2 py-3 text-[11px] lg:text-xs">{{ $report->defect ?? 'N/A' }}</td>
                                    <td class="px-2 py-3 text-[11px] lg:text-xs">{{ $report->street }},
                                        {{ $report->purok }}, {{ $report->barangay }}</td>
                                    <td class="px-2 py-3 text-[11px] lg:text-xs">
                                        {{ $report->date ? \Carbon\Carbon::parse($report->date)->format('F j, Y') : 'N/A' }}
                                    </td>
                                    <td
                                        class="px-2 py-3 text-[11px] lg:text-xs font-medium
                                    {{ $report->status === 'Repaired' ? 'text-green-600' : '' }}
                                    {{ $report->status === 'Ongoing' ? 'text-yellow-500' : '' }}
                                    {{ $report->status === 'Unfixed' ? 'text-red-600' : '' }}">
                                        {{ ucfirst($report->status ?? 'N/A') }}
                                    </td>
                                    <td class="pr-5 py-3 text-[11px] lg:text-xs hidden lg:block md:block">
                                        <div class="flex">
                                            <button
                                                class="flex items-center text-[#3251FF] hover:text-[#1d3fcc] font-medium text-xs transition active:scale-95 hover:bg-blue-100 hover:shadow py-1 px-3 rounded-md"
                                                wire:click="viewHistoryReports({{ $report->id }})"
                                                wire:loading.attr="disabled"
                                                x-data="{ loading: false }"
                                                x-on:click="loading = true"
                                                x-on:view-report-history-modal-shown.window="loading = false">
                                                <img src="{{ asset('storage/icons/view-icon.png') }}" alt="View Icon"
                                                    class="hidden md:block w-5 h-5 mr-2" />
                                                <span x-cloak x-show="! loading">View</span>
                                                <x-loading-indicator class="text-blue-500 w-4 h-4" x-cloak
                                                    x-show="loading" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-2 text-center text-gray-500 text-[11px]">
                                        No reports found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Loading indicator for pagination -->
                <div wire:loading.class.remove="opacity-0 pointer-events-none" x-cloak x-transition
                    class="absolute inset-0 w-full min-h-full bg-black/50 flex justify-center items-center transition-all pointer-events-none opacity-0">
                    <x-loading-indicator class="h-[50px] w-[50px] text-white" wire:loading />
                </div>

            </x-slot:table_container>

            <x-slot:pagination_container wire:key="{{ now() }}">
                {{ $reports->links('vendor.pagination.custom') }}
            </x-slot:pagination_container>

            <x-slot:modal_container>

                <!-- View Staff Modal -->
                <livewire:modals.staff.view-report-history-modal wire:model="history_report_to_viewed" />

            </x-slot:modal_container>


        </x-staff.crud-page-content-base>
    </div>


</x-staff.staff-navigation>
