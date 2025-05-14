<div
    x-data="{
        isOpen: @entangle('isOpen'),
        selectedStatus: '',
        canSubmit() {
            return $wire.selectedReports.length > 0 && this.selectedStatus !== '';
        },
        showAlert: @entangle('showAlert'),
        alertMessage: @entangle('alertMessage'),
    }"
    x-show="isOpen"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
    x-cloak
    style="display: none;"
>
    <div @click.away="isOpen = false" class="bg-[#F5F5F5] w-9/10 max-w-3xl p-4 rounded-lg shadow-lg relative overflow-y-auto max-h-[90vh]">
        <!-- Close Button -->
        <button @click="isOpen = false" class="absolute z-[99999] top-2 right-2 rounded-full text-gray-700 hover:text-red-500 focus:outline-none hover:bg-gray-200 hover:rounded-full p-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- ✅ Alert Message -->
        <div
            x-show="showAlert"
            class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg"
            x-transition
        >
            <span x-text="alertMessage"></span>
            <button @click="showAlert = false" class="ml-2 text-white font-bold">&times;</button>
        </div>

        <!-- Check if there are nearby reports -->
        @if ($nearbyReports && count($nearbyReports) > 0)
            <!-- Modal Title -->
            <h1 class="text-lg font-bold text-center text-black sticky top-[-20px] shadow-md bg-white p-4 z-10">
                Nearby Reports Found!
            </h1>
            <div class="grid grid-cols-2 justify-center">
                @foreach ($nearbyReports as $report)
                    <div
                        class="border rounded-md  p-2 flex flex-col items-center cursor-pointer transition-transform transform hover:scale-100 w-40 h-65"
                        :class="$wire.selectedReports.includes({{ $report->id }}) ? 'border-green-600 bg-green-100' : 'border-gray-300'"
                        @click="$wire.selectedReports.includes({{ $report->id }})
                    ? $wire.selectedReports = $wire.selectedReports.filter(id => id !== {{ $report->id }})
                    : $wire.selectedReports.push({{ $report->id }})"
                    >
                        <img src="{{ asset('storage/' . $report->image_annotated) }}"
                             alt="Report Image"
                             class="w-full max-h-64 h-auto object-contain rounded-md mb-1">

                        <p class="text-black text-center text-xs"><strong>Defect:</strong> {{ $report->defect }}</p>
                        <p class="text-black text-center text-xs">
                            <strong>Date:</strong>
                            {{ \Carbon\Carbon::parse($report->date)->format('F d, Y') }}
                            {{ \Carbon\Carbon::parse($report->time)->format('h:i A') }}
                        </p>

                        <p class="text-xs text-center text-black">
                            <strong>Status:</strong>
                            <span class="
        @if ($report->status === 'Ongoing') text-yellow-500
        @elseif ($report->status === 'Unfixed') text-red-500
        @else text-black
        @endif
    ">
        {{ $report->status }}
    </span>
                        </p>

                        <p class="text-xs text-gray-500 text-center">Click to select</p>
                    </div>
                @endforeach
            </div>
            <!-- Action Buttons with Status Dropdown -->
            <div class="mt-4 flex flex-col items-center gap-3 sticky bottom-[-21px] bg-white p-4 shadow-md">
                <!-- Status Dropdown -->
                <div class="flex items-center space-x-2">
                    <label for="status" class="text-sm font-semibold text-gray-700">Status:</label>
                    <select
                        x-model="selectedStatus"
                        id="status"
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-green-500 focus:outline-none"
                    >
                        <option value="" disabled hidden selected>Select Status</option>
                        <option value="Repaired">Repaired</option>
                        <option value="Ongoing">Ongoing</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <!-- Update Button -->
                    <button
                        wire:click="updateDefects(selectedStatus)"
                        class="px-4 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center justify-center"
                        :disabled="!canSubmit()"
                    >
                        <span wire:loading.remove class="flex items-center">Update Defects</span>

                        <span wire:loading class="flex items-center">
{{--            <svg class="animate-spin h-2 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">--}}
                            {{--                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>--}}
                            {{--                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3.5-3.5L12 0v4a8 8 0 018 8h-4l3.5 3.5L20 12h-4a8 8 0 01-8 8v-4l-3.5 3.5L12 20v-4a8 8 0 01-8-8h4z"></path>--}}
                            {{--            </svg>--}}
            Processing...
        </span>
                    </button>

                    <!-- Close Button -->
                    <button wire:click="closeModal" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700">
                        No
                    </button>
                </div>

            </div>
        @else
            <div class="text-center">
                <!-- add image here using asset -->
                <img src="{{ asset('storage/icons/no-nearby-reports-icon.png') }}" alt="No nearby reports" loading="lazy" class="mx-auto pt-4 mb-2 w-28 h-auto">

                <h1 class="text-[20px] font-bold text-center mb-4 text-black sticky top-[-20px] bg-none p-4 z-10">
                    No nearby reports found!
                </h1>

                <div class="flex justify-center items-center w-full">
                    <button @click="retryCapture()" wire:click="closeModal" class="px-10 py-2 bg-orange-400 bg-opacity-90 hover:bg-orange-400 text-sm text-white rounded-lg shadow-md">
                        Retry
                    </button>
                </div>
            </div>
        @endif

    </div>

</div>
