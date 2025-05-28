<div>
    <div
        x-data="{ isOpen: @entangle('isOpen') }"
        x-show="isOpen"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
        x-cloak
        style="display: none;"
    >
        <div class="bg-white w-11/12 max-w-md p-8 rounded-2xl shadow-2xl relative text-center border-t-8 border-red-600">
            <!-- Close Button -->
            <button @click="isOpen = false"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold transition">
                &times;
            </button>

            <!-- Icon + Message -->
            <div class="flex flex-col items-center space-y-3">
                <!-- Warning Icon -->
                <div class="bg-red-100 text-red-600 w-16 h-16 flex items-center justify-center rounded-full shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v2m0 4h.01M5.07 19H18.93a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z" />
                    </svg>
                </div>

                <!-- Heading -->
                <h2 class="text-2xl font-extrabold text-red-700 tracking-wide uppercase">Road is still not fixed!</h2>

                <!-- Optional message -->
                <p class="text-sm text-gray-600 px-4">Please ensure the road condition has changed before retrying the capture.</p>

                <!-- Buttons -->
                <div class="flex justify-center gap-4 pt-4">
                    <button @click="isOpen = false, retryCapture()"
                            class="px-5 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-all font-medium shadow">
                        Retry
                    </button>
                    <button @click="isOpen = false"
                            class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-all font-medium shadow">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

