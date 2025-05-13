<div class="z-50"
    x-data="{
        tabs: @js($tabs),
        activeTab: @entangle('activeTab'),
        visitedTabs: @entangle('visitedTabs'),
        isPasswordVisible: false,
        form: {
            password: @entangle('form.password'),
        },
        generatePassword() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
            const length = 12;
            let password = '';
            for (let i = 0; i < length; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            this.form.password = password;
        },
        isOpen: false,
    }">
    <x-admin.staff-modal-content-base modal_name="edit-user-account-modal">

        <x-slot:trigger>
            <div class="hidden"
                x-on:edit-user-account-modal-shown.window="open = true"></div>
        </x-slot:trigger>

        <x-slot:header>
            Edit Staff Information
        </x-slot:header>

        <x-slot:body>
            @if($staff)
            <div>
                <div class="relative  w-full">
                    <!--image bg-->
                    <div class="relative overflow-hidden shadow w-full h-[75px]">
                        <img src="{{ asset('storage/images/bg-profileName.png') }}" alt="profile name background"
                            class="absolute top-0 left-0 w-full h-full object-cover animate-wipe-right">
                    </div>

                    <!-- Profile Picture Section -->

                    <div class="flex items-center space-x-4 -mt-16 pl-6 mb-6">
                        <div x-data="{
                            isHovered: false,
                            preview: null,
                            showLoading: false,
                            loadingTimer: null
                        }"
                            class="relative">

                            <!-- Loading Indicator -->
                            <div x-show="showLoading" class="absolute top-0 left-0 w-full h-1 bg-green-500 z-50 animate-pulse"
                                x-transition:enter="transition-all duration-300"
                                x-transition:leave="transition-all duration-300"></div>

                            <!-- Profile Picture Container -->
                            <div class="relative w-16 h-16 overflow-hidden rounded-full border-2 border-green-500 shadow-md -mt-1">

                                <!-- Image Preview -->
                                <template x-if="preview">
                                    <img :src="URL.createObjectURL(preview)" alt="Profile Picture Preview"
                                        class="w-full h-full object-cover" />
                                </template>

                                <!-- Default/Current Picture -->
                                <template x-if="!preview">
                                    <img src="{{ $photo ? $photo->temporaryUrl() : ($currentPhoto ?? asset('storage/icons/profile-graphics.png')) }}"
                                        alt="Profile Picture" class="w-full h-full object-cover" />
                                </template>

                                <!-- Upload Overlay -->
                                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white text-xs opacity-0 hover:opacity-100 transition-opacity">
                                    <button @click="$refs.fileInput.click()" class="flex items-center space-x-2">
                                        <span>Upload</span>
                                    </button>
                                </div>
                            </div>

                            <!-- File Input -->
                            <input type="file" class="hidden" x-ref="fileInput" @change="preview = $event.target.files[0]; showLoading = true; clearTimeout(loadingTimer); loadingTimer = setTimeout(() => { showLoading = false; }, 2000);" accept="image/*" wire:model="photo">

                            <!-- Error Message -->
                            @error('photo')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>


                        <!-- Full Name and Email Preview -->
                        <div class="pl-2 z-50 -mt-4 capitalize">
                            <div class="block text-lg font-medium text-gray-700">
                                {{ trim(($form['first_name'] ?? '') . ' ' . ($form['middle_name'] ?? '') . ' ' . ($form['last_name'] ?? '')) ?: $staff->user->first_name . ' ' . $staff->user->last_name }}
                            </div>
                            <div class="block text-xs font-normal text-gray-700 italic">
                                {{ !empty($form['username']) ? $form['username'] : $staff->user->username }}
                            </div>
                        </div>
                    </div>

                    <!-- Tab Navigation -->
                    <div class="flex text-xs mb-4">
                        <template x-for="(tab, index) in tabs" :key="index">
                            <div class="flex items-center space-x-1 mr-6">
                                <!-- Tab Indicator (Number or Check Icon) -->
                                <div
                                    class="flex items-center justify-center h-6 w-6 rounded-full p-[1px] font-medium"
                                    :class="visitedTabs.includes(tab.key)
                                    ? 'bg-gradient-to-r from-blue-500 to-green-500'
                                    : 'bg-gray-400'">
                                    <span class="text-white">
                                        <template x-if="visitedTabs.includes(tab.key)">
                                            <span class="text-sm">&#10003;</span>
                                        </template>
                                        <template x-if="!visitedTabs.includes(tab.key)">
                                            <span x-text="index + 1"></span>
                                        </template>
                                    </span>
                                </div>
                                <!-- Tab Label -->
                                <div class="relative">
                                    <button
                                        class="text-xs font-medium relative z-10"
                                        :class="activeTab === tab.key
                                        ? 'text-[#676767] font-semibold'
                                        : 'text-gray-400' && visitedTabs.includes(tab.key)
                                        ? 'text-green-600'
                                        : 'text-gray-400'"
                                        @click="$wire.activateTab(tab.key)">
                                        <span x-text="tab.label"></span>
                                    </button>
                                    <!-- Highlight Line -->
                                    <span
                                        class="absolute left-0 right-0 bottom-[-2px] h-[3px] bg-[#4AA76F] rounded-full transition-all duration-300"
                                        x-show="activeTab === tab.key"
                                        x-transition:enter="transition ease-out duration-300 transform opacity-0 translate-y-1"
                                        x-transition:enter-start="opacity-0 transform translate-y-1"
                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                        x-transition:leave="transition ease-in duration-200 transform opacity-100 translate-y-0"
                                        x-transition:leave-start="opacity-100 transform translate-y-0"
                                        x-transition:leave-end="opacity-0 transform translate-y-1">
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-6">

                        <!-- Basic Info Tab -->
                        @if($activeTab === 'basic-info')
                        <div wire:submit.prevent="save" class="space-y-6">
                            <!-- Name Fields -->
                            <div class="grid grid-cols-3 gap-4">

                                <div>
                                    <label class="block font-medium text-gray-700">First Name</label>
                                    <input wire:model.live="form.first_name"
                                        placeholder="First name" ft6cf7
                                        type="text"
                                        oninput="capitalizeInput(this)"
                                        class="border-gray-300 focus:ring-[#4AA76F] focus:border-[#4AA76F] block w-full rounded-sm shadow-sm capitalize">
                                    @error('form.first_name') <span class="text-red-600 text-xs flex justify-center text-center">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block font-medium text-gray-700">Middle Name</label>
                                    <input wire:model.live="form.middle_name"
                                        placeholder="Middle name"
                                        type="text"
                                        oninput="capitalizeInput(this)"
                                        class="border-gray-300 focus:ring-[#4AA76F] focus:border-[#4AA76F] block w-full rounded-sm shadow-sm capitalize">
                                </div>
                                <div>
                                    <label class="block font-medium text-gray-700">Last Name</label>
                                    <input wire:model.live="form.last_name"
                                        placeholder="Last name"
                                        type="text"
                                        oninput="capitalizeInput(this)"
                                        class="border-gray-300 focus:ring-[#4AA76F] focus:border-[#4AA76F] block w-full rounded-sm shadow-sm capitalize">
                                    @error('form.last_name') <span class="text-red-600 text-xs flex justify-center text-center">{{ $message }}</span> @enderror
                                </div>

                            </div>

                            <!-- Sex and Birthdate -->
                            <div class="space-x-4 grid grid-cols-2">

                                <!-- Sex -->
                                <div>
                                    <label class="block font-medium text-gray-700">Sex</label>
                                    <select wire:model="form.sex"
                                        class="border-gray-300 focus:ring-[#4AA76F] focus:border-[#4AA76F] block w-full rounded-sm shadow-sm capitalize"
                                        aria-describedby="sexError">
                                        <option value="">Select Sex</option>
                                        @foreach($sexes as $sex)
                                        <option value="{{ $sex->id }}">{{ $sex->value }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.sex')
                                    <span id="sexError" class="text-red-600 text-xs flex justify-center text-center">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Birthdate -->
                                <div class="relative">
                                    <div x-data="{ date_of_birth: @entangle('form.date_of_birth'), fp: null }" x-init="
                                    // Initialize Flatpickr only after the component is ready
                                    $nextTick(() => {
                                        fp = flatpickr($refs.input, {
                                            dateFormat: 'F j, Y',
                                            defaultDate: @js($this->form['date_of_birth'] ?? null), // Initialize with F j, Y format
                                            maxDate: new Date(),
                                            minDate: new Date(new Date().setFullYear(new Date().getFullYear() - 100)),
                                            allowInput: true,
                                            onChange: (_, dateStr) => date_of_birth = dateStr // Update Alpine.js value on date change
                                        });
                                    });

                                    // Real-time input handler to update the calendar immediately while typing
                                    $refs.input.addEventListener('input', function () {
                                        // Check if the input is a valid date format and if so, update the calendar
                                        if (fp.isValidDate(this.value)) {
                                            fp.setDate(this.value, true); // Update the calendar immediately but don't trigger Livewire onChange
                                        }
                                        date_of_birth = this.value; // Sync Alpine.js model to Livewire
                                    });
                                ">
                                        <label for="date_of_birth" class="block font-medium text-gray-700 mb-1">Date of Birth</label>
                                        <div class="relative">
                                            <input
                                                id="date_of_birth"
                                                type="text"
                                                x-ref="input"
                                                x-model="date_of_birth"
                                                placeholder="e.g. April 18, 2000"
                                                autocomplete="off"
                                                class="border-gray-300 focus:ring-[#4AA76F] focus:border-[#4AA76F] block w-full rounded-md shadow-sm px-3 py-2"
                                            />
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 10h10m2-7H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    @error('form.date_of_birth')
                                    <span id="dobError" class="text-red-600 text-xs flex justify-center text-center">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Access Control Tab -->
                        @if($activeTab === 'access-info')
                            <div>
                                <label class="block font-medium text-gray-700">User Role</label>
                                <select wire:model.live="form.user_role"
                                    class="border-gray-300 focus:ring-[#4AA76F] focus:border-[#4AA76F] text-gray-700 block w-full rounded-sm shadow-sm">
                                    <option value="" selected>Select User Role</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                        @if($role->id == $form['user_role']) selected @endif>
                                        {{ $role->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            @error('form.user_role')
                            <span id="dobError" class="text-red-600 text-xs flex justify-center text-center">{{ $message }}</span>
                            @enderror

                            <label class="block font-medium text-gray-700 mb-2 border-b border-gray-300 mt-4">
                                Permissions
                            </label>

                            @if(!empty($selectedPermissions))
                                <div class="min-h-[25vh] max-h-[25vh] overflow-y-auto grid grid-cols-1 sm:grid-cols-2 gap-1 mt-2 py-1.5 px-5 text-[13px]">
                                    @foreach($selectedPermissions as $permission)
                                    <ul class="list-disc flex items-center">
                                        <li>{{ ucwords(str_replace('_', ' ', $permission)) }}</li>
                                    </ul>
                                    @endforeach
                                </div>
                            @else
                                <li>No permissions assigned to this role.</li>
                            @endif
                        @endif

                        <!-- Account Info Tab -->
                        @if($activeTab === 'account-info')
                        <div class="min-h-[35vh] max-h-[35vh] overflow-y-auto bg-[#FBFBFB] shadow px-3 py-4 text-xs">
                            <!-- Account Status -->
                            <div class="mb-6 border-b border-gray-300">
                                <label class="block font-medium text-gray-700 mb-1">Account Status</label>
                                <div x-data="{ isDisabled: @entangle('form.is_disabled') }" class="flex items-center justify-between w-full sm:text-sm p-2 space-x-24">
                                    <!-- Status Text -->
                                    <div class="text-sm font-semibold" :class="isDisabled ? 'text-red-500' : 'text-green-500'">
                                        <span x-text="isDisabled ? 'Inactive' : 'Active'"></span>
                                    </div>

                                    <!-- Toggle Switch -->
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input
                                            type="checkbox"
                                            x-model="isDisabled"
                                            class="sr-only">
                                        <!-- Toggle Background -->
                                        <div class="w-10 h-5 rounded-full transition-colors duration-300"
                                            :class="isDisabled ? 'bg-red-500' : 'bg-green-500'">
                                        </div>
                                        <!-- Toggle Thumb -->
                                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-300"
                                            :class="isDisabled ? 'translate-x-5' : ''">
                                        </div>
                                    </label>
                                </div>

                            </div>

                            <div>
                                <label class="block font-medium text-gray-700">Username</label>
                                <input wire:model.live="form.username"
                                    placeholder="Username"
                                    type="text"
                                    class="border-gray-300 focus:ring-[#4AA76F] focus:border-[#4AA76F] block w-full rounded-sm shadow-sm">
                                @error('form.username') <span class="text-red-600 text-xs flex justify-center text-center">{{ $message }}</span> @enderror
                            </div>

                            <!-- Password Field with Toggle Visibility -->
                            <div class="relative mt-6">
                                <label class="block font-medium text-gray-700">New Password</label>
                                <div class="flex space-x-4">
                                    <div class="relative flex-1">
                                        <input
                                            :type="isPasswordVisible ? 'text' : 'password'"
                                            wire:model.live="form.password"
                                            placeholder="Generated password will appear here"
                                            class="border border-gray-300 focus:ring-[#4AA76F] focus:border-[#4AA76F] mt-1 block w-full rounded-sm shadow-sm sm:text-sm bg-gray-100">
                                        <!-- Eye Icon for Toggling -->
                                        <button
                                            type="button"
                                            @click="isPasswordVisible = !isPasswordVisible"
                                            class="float-right -mt-7 mr-2 text-gray-500 hover:text-gray-700">
                                        <span x-show="!isPasswordVisible">
                                            <!-- Closed Eye Icon -->
                                            <svg class="w-5 h-5 text-gray-600 hover:text-gray-700 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" zoomAndPan="magnify" viewBox="0 0 375 374.999991" preserveAspectRatio="xMidYMid meet" version="1.0">
                                                <defs>
                                                    <clipPath id="f7a7acdade">
                                                        <path d="M 11 71 L 364 71 L 364 324.042969 L 11 324.042969 Z M 11 71 " clip-rule="nonzero" />
                                                    </clipPath>
                                                </defs>
                                                <g clip-path="url(#f7a7acdade)">
                                                    <path fill="currentColor" d="M 359.273438 184.5 C 348.402344 167.085938 325.5 136.277344 289.753906 112.980469 L 309.460938 93.25 C 314.371094 88.332031 314.371094 80.371094 309.460938 75.457031 C 304.554688 70.539062 296.597656 70.539062 291.691406 75.457031 L 64.847656 302.566406 C 59.9375 307.484375 59.9375 315.445312 64.847656 320.359375 C 67.300781 322.820312 70.515625 324.046875 73.734375 324.046875 C 76.949219 324.046875 80.164062 322.820312 82.621094 320.359375 L 107.253906 295.699219 C 129.800781 306.417969 156.328125 313.589844 187.15625 313.589844 C 285.046875 313.589844 340.257812 241.777344 359.273438 211.3125 C 364.390625 203.125 364.390625 192.691406 359.273438 184.5 Z M 187.15625 273.40625 C 170.867188 273.40625 155.851562 268.167969 143.539062 259.367188 L 170.953125 231.921875 C 175.855469 234.292969 181.332031 235.65625 187.15625 235.65625 C 207.980469 235.65625 224.859375 218.757812 224.859375 197.910156 C 224.859375 192.078125 223.496094 186.597656 221.128906 181.6875 L 248.542969 154.242188 C 257.332031 166.566406 262.5625 181.601562 262.5625 197.910156 C 262.566406 239.605469 228.800781 273.40625 187.15625 273.40625 Z M 114.425781 217.597656 C 112.730469 211.3125 111.746094 204.730469 111.746094 197.910156 C 111.746094 156.210938 145.507812 122.410156 187.15625 122.410156 C 193.96875 122.410156 200.542969 123.398438 206.820312 125.09375 L 241.574219 90.296875 C 225.136719 85.304688 207.074219 82.226562 187.15625 82.226562 C 89.261719 82.226562 34.050781 154.039062 15.035156 184.5 C 9.921875 192.691406 9.921875 203.125 15.035156 211.3125 C 23.6875 225.175781 39.929688 247.574219 64.183594 267.898438 Z M 114.425781 217.597656 " />
                                                </g>
                                            </svg>
                                        </span>
                                            <span x-show="isPasswordVisible" style="display: none;">
                                            <!-- Open Eye Icon -->
                                            <svg class="w-5 h-5 text-gray-600 hover:text-gray-700 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" zoomAndPan="magnify" viewBox="0 0 375 374.999991" preserveAspectRatio="xMidYMid meet" version="1.0">
                                                <defs>
                                                    <clipPath id="1f8a6b186c">
                                                        <path d="M 6 78.503906 L 369 78.503906 L 369 315.503906 L 6 315.503906 Z M 6 78.503906 " clip-rule="nonzero" />
                                                    </clipPath>
                                                </defs>
                                                <g clip-path="url(#1f8a6b186c)">
                                                    <path fill="currentColor" d="M 364.394531 183.273438 C 344.859375 152.066406 288.148438 78.507812 187.597656 78.507812 C 87.042969 78.507812 30.332031 152.066406 10.800781 183.273438 C 5.546875 191.660156 5.546875 202.347656 10.800781 210.734375 C 30.332031 241.941406 87.042969 315.5 187.597656 315.5 C 288.148438 315.5 344.859375 241.941406 364.394531 210.734375 C 369.648438 202.347656 369.648438 191.660156 364.394531 183.273438 Z M 187.597656 274.339844 C 144.816406 274.339844 110.136719 239.714844 110.136719 197.003906 C 110.136719 154.292969 144.816406 119.667969 187.597656 119.667969 C 230.375 119.667969 265.054688 154.292969 265.054688 197.003906 C 265.054688 239.714844 230.375 274.339844 187.597656 274.339844 Z M 213.417969 184.113281 C 206.289062 184.113281 200.507812 178.34375 200.507812 171.226562 C 200.507812 167.710938 201.933594 164.539062 204.21875 162.214844 C 199.164062 159.800781 193.574219 158.335938 187.597656 158.335938 C 166.207031 158.335938 148.867188 175.648438 148.867188 197.003906 C 148.867188 218.359375 166.207031 235.671875 187.597656 235.671875 C 208.988281 235.671875 226.328125 218.359375 226.328125 197.003906 C 226.328125 191.039062 224.859375 185.453125 222.445312 180.410156 C 220.113281 182.691406 216.9375 184.113281 213.417969 184.113281 Z M 213.417969 184.113281 " />
                                                </g>
                                            </svg>
                                        </span>
                                        </button>
                                    </div>
                                    <button
                                        @click="generatePassword()"
                                        class="px-4 py-2 text-[#4AA76F] rounded-full border border-[#4AA76F]  bg-[#4AA76F] bg-opacity-5 hover:bg-[#3AA76F] hover:bg-opacity-10 text-xs transition-all active:translate-y-[2px] active:shadow-none">
                                        Generate Password
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            @endif
        </x-slot:body>


        <x-slot:footer>
            <!-- Buttons -->
            <div class="flex justify-between items-center w-full mt-6">
                <!-- Left Side Buttons -->
                <div class="flex justify-between items-center w-full">
                    <!-- Back Button -->
                    <button
                        type="button"
                        wire:click="previousTab"
                        class="flex items-center px-4 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200 transition active:scale-95"
                        x-show="tabs.findIndex(tab => tab.key === activeTab) > 0">
                        <!-- Back Arrow Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="ml-2">Back</span>
                    </button>

                    <!-- Edit user Button -->
                    <button
                        type="button"
                        wire:click="save"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 bg-gradient-to-b from-[#84D689] to-green-500 text-white text-sm rounded hover:bg-[#4AA76F] shadow-lg shadow-neutral-500/20 transition active:scale-95 hover:scale-105"
                        x-show="tabs.findIndex(tab => tab.key === activeTab) === tabs.length - 1">
                        Update Account
                        <x-loading-indicator wire:loading class="h-6 w-6" x-show="false" />
                    </button>
                </div>

                <!-- Next Button -->
                <button
                    type="button"
                    wire:click="nextTab"
                    wire:loading.attr="disabled"
                    class="flex items-center px-4 py-2 bg-[#3AA76F] text-white text-sm rounded hover:bg-[#4AA76F] transition active:scale-95"
                    x-show="tabs.findIndex(tab => tab.key === activeTab) < tabs.length - 1">
                    <span class="mr-2">Next</span>
                    <!-- Next Arrow Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!loading">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>

                    <x-loading-indicator wire:loading class="h-6 w-6" x-show="false" />
                </button>
            </div>

            <script>
                function capitalizeInput(input) {
                    input.value = input.value.toLowerCase().replace(/\b\w/g, function(char) {
                        return char.toUpperCase();
                    });
                }
            </script>
        </x-slot:footer>

    </x-admin.staff-modal-content-base>

</div>
