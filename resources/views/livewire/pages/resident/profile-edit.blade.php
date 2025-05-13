<x-residents.residents-navigation page_title="Profile Settings">

    <x-residents.profile-page-content-base>

        <x-slot:profile_picture>
            <livewire:pages.resident.profile-picture-upload />
        </x-slot:profile_picture>

        <x-slot:preview_names_and_user_type>
            <h2 class="text-[22px] font-semibold drop-shadow capitalize">
                {{-- Displaying the user's name with the first letter of the middle name in uppercase --}}
                {{ ucwords(strtolower("$first_name " . ($middle_name ? strtoupper(substr($middle_name, 0, 1)) . '. ' : '') . "$last_name")) }}

            </h2>
            <p class="text-[14px]">
                {{-- Displaying the user type name in uppercase --}}
                {{ ucfirst($userTypeName) }}
            </p>
        </x-slot:preview_names_and_user_type>

        {{-- Basic Information --}}
        <x-slot:first_name>
            <div class="flex flex-col lg:flex-row lg:items-center w-full">
                <label for="first_name" class="w-full lg:w-2/10 mb-2 lg:mt-3 block text-[13px] font-medium text-gray-900">
                    First Name
                </label>
                <input type="text" name="first_name" id="first_name" autocomplete="off" wire:model.defer="first_name"
                       class="w-full lg:w-7/10 border border-gray-300 focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] block rounded-md bg-none text-[14px] font-normal text-gray-900 shadow @error('first_name') border-red-500 @enderror"
                       placeholder="First Name">
            </div>
            @error('first_name')
            <div class="text-xs text-red-500 mt-1 lg:ml-[20%]">{{ $message }}</div>
            @enderror
        </x-slot:first_name>

        <x-slot:middle_name>
            <div class="flex flex-col lg:flex-row lg:items-center w-full">
                <label for="middle_name" class="w-full lg:w-2/10 mb-2 lg:mt-3 block text-[13px] font-medium text-gray-900">
                    Middle Name
                </label>
                <input type="text" name="middle_name" id="middle_name" autocomplete="off" wire:model.defer="middle_name"
                       class="w-full lg:w-7/10 border border-gray-300 focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] block rounded-md bg-none text-[14px] font-normal text-gray-900 shadow @error('middle_name') border-red-500 @enderror"
                       placeholder="Middle Name">
            </div>
            @error('middle_name')
            <div class="text-xs text-red-500 mt-1 lg:ml-[20%]">{{ $message }}</div>
            @enderror
        </x-slot:middle_name>

        <x-slot:last_name>
            <div class="flex flex-col lg:flex-row lg:items-center w-full">
                <label for="last_name" class="w-full lg:w-2/10 mb-2 lg:mt-3 block text-[13px] font-medium text-gray-900">
                    Last Name
                </label>
                <input type="text" name="last_name" id="last_name" autocomplete="off" wire:model.defer="last_name"
                       class="w-full lg:w-7/10 border border-gray-300 focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] block rounded-md bg-none text-[14px] font-normal text-gray-900 shadow @error('last_name') border-red-500 @enderror"
                       placeholder="Last Name">
            </div>
            @error('last_name')
            <div class="text-xs text-red-500 mt-1 lg:ml-[20%]">{{ $message }}</div>
            @enderror
        </x-slot:last_name>

        <x-slot:sex>
            <div class="flex flex-col lg:flex-row lg:items-center w-full">
                <label for="sex_id" class="w-full lg:w-2/10 mb-2 lg:mt-3 block text-[13px] font-medium text-gray-900">
                    Sex
                </label>
                <div class="relative w-full lg:w-7/10">
                    <select name="sex_id" id="sex_id" wire:model.defer="sex"
                            class="appearance-none border border-gray-300 focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] block w-full rounded-md bg-white text-[14px] font-normal text-gray-900 shadow @error('sex') border-red-500 @enderror">
                        <option value="male" class="text-[14px] font-normal text-gray-900 hover:bg-[#4AA76F]">
                            Male
                        </option>
                        <option value="female" class="text-[14px] font-normal text-gray-900 hover:bg-[#4AA76F]">
                            Female
                        </option>
                    </select>
                </div>
            </div>
            @error('sex')
            <div class="text-xs text-red-500 mt-1 lg:ml-[20%]">{{ $message }}</div>
            @enderror
        </x-slot:sex>

        <x-slot:user_type>
            <div class="flex flex-col lg:flex-row lg:items-center w-full">
                <label for="user_type" class="w-full lg:w-2/10 mb-2 lg:mt-3 block text-[13px] font-medium text-gray-900">
                    User Type
                </label>
                <input type="text" name="user_type" id="user_type" value="{{ ucfirst($userTypeName) }}"
                       class="w-full lg:w-7/10 border border-gray-300 focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] block rounded-md bg-none text-[14px] font-medium text-gray-500 shadow"
                       disabled>
            </div>
        </x-slot:user_type>

        <x-slot:birthdate>
            <div class="flex flex-col lg:flex-row lg:items-center w-full">
                <label for="date_of_birth" class="w-full lg:w-2/10 mb-2 lg:mt-3 block text-[13px] font-medium text-gray-900">
                    Birthdate
                </label>
                <div x-data="{
                 init() {
                        flatpickr($refs.input, {
                            dateFormat: 'F j, Y',
                            defaultDate: @js($this->date_of_birth) ?? null,
                            allowInput: true, // 
                            onChange: (_, dateStr) => {
                                @this.set('date_of_birth', dateStr);
                            },
                            onClose: function(selectedDates, dateStr) {
                                // If the user typed a valid date manually and tabs out, sync it too
                                @this.set('date_of_birth', dateStr);
                            }
                        });
                    }
                }" x-init="init" class="relative w-full lg:w-7/10 custom-date-input"
                    wire:ignore>
                    <input id="date_of_birth" type="text" x-ref="input"
                        value="{{ old('date_of_birth', $this->date_of_birth) }}"
                        placeholder="Select or type your birthdate"
                        class="appearance-none border border-gray-300 focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] w-full rounded-md bg-white text-[14px] text-gray-900 shadow">
                </div>
            </div>
        </x-slot:birthdate>

        <x-slot:save_button_container>
            <button type="button" wire:click="updateBasicInfo"
                    class="w-auto gap-x-[8px] text-[14px] bg-[#4AA76F] px-[12px] py-[8px] font-normal tracking-wider text-white rounded-full hover:ease-in-out hover:duration-300 transition-all duration-300 [transition-timing-function:cubic-bezier (0.175,0.885,0.32,1.275)] active:-translate-y-1 active:scale-x-90 active:scale-y-110">
                <span class="mt-[2px] px-2">Save Changes</span>
            </button>
        </x-slot:save_button_container>

        {{--Account Settings Information--}}
        <x-slot:phone>
            <div class="flex flex-col md:flex-row md:items-center w-full">
                <label for="phone" class="block text-[13px] font-medium text-gray-900 mb-2 md:mb-0 md:w-1/4">
                    Phone Number
                </label>
                <div class="w-full md:w-3/4 max-w-md">
                    <input type="text" name="phone" id="phone" autocomplete="off" wire:model.defer="phone" readonly
                        class="w-full border border-gray-300 focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] block rounded-md bg-none text-[14px] font-normal text-gray-900 shadow
                @error('phone') border-red-500 @enderror"
                           placeholder="Enter Phone Number">
                    @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </x-slot:phone>

        <x-slot:current_password>
            <div class="flex flex-col md:flex-row md:items-center w-full">
                <label for="current_password" class="block text-[13px] font-medium text-gray-900 mb-2 md:mb-0 md:w-1/4">
                    Current Password
                </label>
                <div class="flex flex-col w-full md:w-3/4 max-w-md">
                    <div class="relative flex-1">
                        <input :type="showCurrentPassword ? 'text' : 'password'" id="current_password" wire:model.defer="current_password"
                               class="mb-2 w-full border border-gray-300 focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] block rounded-md bg-none text-[14px] font-normal text-gray-900 shadow
                    @error('current_password') border-red-500 @enderror"
                               placeholder="Enter Current Password">
                        <!-- Toggle SVG for show/hide password -->
                        <div @click="showCurrentPassword = !showCurrentPassword"
                             class="absolute inset-y-0 right-0 -top-2 pr-3 flex items-center cursor-pointer">
                            <svg x-show="showCurrentPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 375 375"
                                 class="fill-current text-gray-500">
                                <path fill="gray" d="M 364.394531 183.273438 C 344.859375 152.066406 288.148438 78.507812 187.597656 78.507812 C 87.042969 78.507812 30.332031 152.066406 10.800781 183.273438 C 5.546875 191.660156 5.546875 202.347656 10.800781 210.734375 C 30.332031 241.941406 87.042969 315.5 187.597656 315.5 C 288.148438 315.5 344.859375 241.941406 364.394531 210.734375 C 369.648438 202.347656 369.648438 191.660156 364.394531 183.273438 Z M 187.597656 274.339844 C 144.816406 274.339844 110.136719 239.714844 110.136719 197.003906 C 110.136719 154.292969 144.816406 119.667969 187.597656 119.667969 C 230.375 119.667969 265.054688 154.292969 265.054688 197.003906 C 265.054688 239.714844 230.375 274.339844 187.597656 274.339844 Z M 213.417969 184.113281 C 206.289062 184.113281 200.507812 178.34375 200.507812 171.226562 C 200.507812 167.710938 201.933594 164.539062 204.21875 162.214844 C 199.164062 159.800781 193.574219 158.335938 187.597656 158.335938 C 166.207031 158.335938 148.867188 175.648438 148.867188 197.003906 C 148.867188 218.359375 166.207031 235.671875 187.597656 235.671875 C 208.988281 235.671875 226.328125 218.359375 226.328125 197.003906 C 226.328125 191.039062 224.859375 185.453125 222.445312 180.410156 C 220.113281 182.691406 216.9375 184.113281 213.417969 184.113281 Z M 213.417969 184.113281 " />
                            </svg>
                            <svg x-show="!showCurrentPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 375 375"
                                 class="fill-current text-gray-600">
                                <path fill="gray" d="M 359.273438 184.5 C 348.402344 167.085938 325.5 136.277344 289.753906 112.980469 L 309.460938 93.25 C 314.371094 88.332031 314.371094 80.371094 309.460938 75.457031 C 304.554688 70.539062 296.597656 70.539062 291.691406 75.457031 L 64.847656 302.566406 C 59.9375 307.484375 59.9375 315.445312 64.847656 320.359375 C 67.300781 322.820312 70.515625 324.046875 73.734375 324.046875 C 76.949219 324.046875 80.164062 322.820312 82.621094 320.359375 L 107.253906 295.699219 C 129.800781 306.417969 156.328125 313.589844 187.15625 313.589844 C 285.046875 313.589844 340.257812 241.777344 359.273438 211.3125 C 364.390625 203.125 364.390625 192.691406 359.273438 184.5 Z M 187.15625 273.40625 C 170.867188 273.40625 155.851562 268.167969 143.539062 259.367188 L 170.953125 231.921875 C 175.855469 234.292969 181.332031 235.65625 187.15625 235.65625 C 207.980469 235.65625 224.859375 218.757812 224.859375 197.910156 C 224.859375 192.078125 223.496094 186.597656 221.128906 181.6875 L 248.542969 154.242188 C 257.332031 166.566406 262.5625 181.601562 262.5625 197.910156 C 262.566406 239.605469 228.800781 273.40625 187.15625 273.40625 Z M 114.425781 217.597656 C 112.730469 211.3125 111.746094 204.730469 111.746094 197.910156 C 111.746094 156.210938 145.507812 122.410156 187.15625 122.410156 C 193.96875 122.410156 200.542969 123.398438 206.820312 125.09375 L 241.574219 90.296875 C 225.136719 85.304688 207.074219 82.226562 187.15625 82.226562 C 89.261719 82.226562 34.050781 154.039062 15.035156 184.5 C 9.921875 192.691406 9.921875 203.125 15.035156 211.3125 C 23.6875 225.175781 39.929688 247.574219 64.183594 267.898438 Z M 114.425781 217.597656 " />
                            </svg>
                        </div>
                    </div>
                    <div>
                        @error('current_password')
                        <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </x-slot:current_password>

        <x-slot:new_password>
            <div class="flex flex-col md:flex-row md:items-center w-full">
                <label for="password" class="block text-[13px] font-medium text-gray-900 mb-2 md:mb-0 md:w-1/4">
                    New Password
                </label>
                <div class="flex flex-col w-full md:w-3/4 max-w-md">
                    <div class="relative flex-1">
                        <input :type="showNewPassword ? 'text' : 'password'" id="password" wire:model.defer="password"
                               class="mb-2 w-full border border-gray-300 focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] block rounded-md bg-none text-[14px] font-normal text-gray-900 shadow
                    @error('password') border-red-500 @enderror @error('password_confirmation') border-red-500 @enderror"
                               placeholder="Enter New Password">
                        <div @click="showNewPassword = !showNewPassword"
                             class="absolute inset-y-0 right-0 -top-2 pr-3 flex items-center cursor-pointer">
                            <svg x-show="showNewPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 375 375"
                                 class="fill-current text-gray-500">
                                <path fill="gray" d="M 364.394531 183.273438 C 344.859375 152.066406 288.148438 78.507812 187.597656 78.507812 C 87.042969 78.507812 30.332031 152.066406 10.800781 183.273438 C 5.546875 191.660156 5.546875 202.347656 10.800781 210.734375 C 30.332031 241.941406 87.042969 315.5 187.597656 315.5 C 288.148438 315.5 344.859375 241.941406 364.394531 210.734375 C 369.648438 202.347656 369.648438 191.660156 364.394531 183.273438 Z M 187.597656 274.339844 C 144.816406 274.339844 110.136719 239.714844 110.136719 197.003906 C 110.136719 154.292969 144.816406 119.667969 187.597656 119.667969 C 230.375 119.667969 265.054688 154.292969 265.054688 197.003906 C 265.054688 239.714844 230.375 274.339844 187.597656 274.339844 Z M 213.417969 184.113281 C 206.289062 184.113281 200.507812 178.34375 200.507812 171.226562 C 200.507812 167.710938 201.933594 164.539062 204.21875 162.214844 C 199.164062 159.800781 193.574219 158.335938 187.597656 158.335938 C 166.207031 158.335938 148.867188 175.648438 148.867188 197.003906 C 148.867188 218.359375 166.207031 235.671875 187.597656 235.671875 C 208.988281 235.671875 226.328125 218.359375 226.328125 197.003906 C 226.328125 191.039062 224.859375 185.453125 222.445312 180.410156 C 220.113281 182.691406 216.9375 184.113281 213.417969 184.113281 Z M 213.417969 184.113281 " />
                            </svg>
                            <svg x-show="!showNewPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 375 375"
                                 class="fill-current text-gray-600">
                                <path fill="gray" d="M 359.273438 184.5 C 348.402344 167.085938 325.5 136.277344 289.753906 112.980469 L 309.460938 93.25 C 314.371094 88.332031 314.371094 80.371094 309.460938 75.457031 C 304.554688 70.539062 296.597656 70.539062 291.691406 75.457031 L 64.847656 302.566406 C 59.9375 307.484375 59.9375 315.445312 64.847656 320.359375 C 67.300781 322.820312 70.515625 324.046875 73.734375 324.046875 C 76.949219 324.046875 80.164062 322.820312 82.621094 320.359375 L 107.253906 295.699219 C 129.800781 306.417969 156.328125 313.589844 187.15625 313.589844 C 285.046875 313.589844 340.257812 241.777344 359.273438 211.3125 C 364.390625 203.125 364.390625 192.691406 359.273438 184.5 Z M 187.15625 273.40625 C 170.867188 273.40625 155.851562 268.167969 143.539062 259.367188 L 170.953125 231.921875 C 175.855469 234.292969 181.332031 235.65625 187.15625 235.65625 C 207.980469 235.65625 224.859375 218.757812 224.859375 197.910156 C 224.859375 192.078125 223.496094 186.597656 221.128906 181.6875 L 248.542969 154.242188 C 257.332031 166.566406 262.5625 181.601562 262.5625 197.910156 C 262.566406 239.605469 228.800781 273.40625 187.15625 273.40625 Z M 114.425781 217.597656 C 112.730469 211.3125 111.746094 204.730469 111.746094 197.910156 C 111.746094 156.210938 145.507812 122.410156 187.15625 122.410156 C 193.96875 122.410156 200.542969 123.398438 206.820312 125.09375 L 241.574219 90.296875 C 225.136719 85.304688 207.074219 82.226562 187.15625 82.226562 C 89.261719 82.226562 34.050781 154.039062 15.035156 184.5 C 9.921875 192.691406 9.921875 203.125 15.035156 211.3125 C 23.6875 225.175781 39.929688 247.574219 64.183594 267.898438 Z M 114.425781 217.597656  " />
                            </svg>
                        </div>
                    </div>
                    <div>
                        @error('password') <p class="text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </x-slot:new_password>

        <x-slot:confirm_password>
            <div class="flex flex-col md:flex-row md:items-center w-full">
                <label for="confirmPassword" class="block text-[13px] font-medium text-gray-900 mb-2 md:mb-0 md:w-1/4">
                    Confirm Password
                </label>
                <div class="flex flex-col w-full md:w-3/4 max-w-md">
                    <div class="relative flex-1">
                        <input :type="showConfirmPassword ? 'text' : 'password'" id="password_confirmation" wire:model.defer="password_confirmation"
                               class="mb-2 w-full border border-gray-300 focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] block rounded-md bg-none text-[14px] font-normal text-gray-900 shadow
                    @error('password_confirmation') border-red-500 @enderror @error('password') border-red-500 @enderror"
                               placeholder="Confirm New Password">
                        <div @click="showConfirmPassword = !showConfirmPassword"
                             class="absolute inset-y-0 right-0 -top-2 pr-3 flex items-center cursor-pointer">
                            <svg x-show="showConfirmPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 375 375"
                                 class="fill-current text-gray-500">
                                <path fill="gray" d="M 364.394531 183.273438 C 344.859375 152.066406 288.148438 78.507812 187.597656 78.507812 C 87.042969 78.507812 30.332031 152.066406 10.800781 183.273438 C 5.546875 191.660156 5.546875 202.347656 10.800781 210.734375 C 30.332031 241.941406 87.042969 315.5 187.597656 315.5 C 288.148438 315.5 344.859375 241.941406 364.394531 210.734375 C 369.648438 202.347656 369.648438 191.660156 364.394531 183.273438 Z M 187.597656 274.339844 C 144.816406 274.339844 110.136719 239.714844 110.136719 197.003906 C 110.136719 154.292969 144.816406 119.667969 187.597656 119.667969 C 230.375 119.667969 265.054688 154.292969 265.054688 197.003906 C 265.054688 239.714844 230.375 274.339844 187.597656 274.339844 Z M 213.417969 184.113281 C 206.289062 184.113281 200.507812 178.34375 200.507812 171.226562 C 200.507812 167.710938 201.933594 164.539062 204.21875 162.214844 C 199.164062 159.800781 193.574219 158.335938 187.597656 158.335938 C 166.207031 158.335938 148.867188 175.648438 148.867188 197.003906 C 148.867188 218.359375 166.207031 235.671875 187.597656 235.671875 C 208.988281 235.671875 226.328125 218.359375 226.328125 197.003906 C 226.328125 191.039062 224.859375 185.453125 222.445312 180.410156 C 220.113281 182.691406 216.9375 184.113281 213.417969 184.113281 Z M 213.417969 184.113281 " />
                            </svg>
                            <svg x-show="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 375 375"
                                 class="fill-current text-gray-600">
                                <path fill="gray" d="M 359.273438 184.5 C 348.402344 167.085938 325.5 136.277344 289.753906 112.980469 L 309.460938 93.25 C 314.371094 88.332031 314.371094 80.371094 309.460938 75.457031 C 304.554688 70.539062 296.597656 70.539062 291.691406 75.457031 L 64.847656 302.566406 C 59.9375 307.484375 59.9375 315.445312 64.847656 320.359375 C 67.300781 322.820312 70.515625 324.046875 73.734375 324.046875 C 76.949219 324.046875 80.164062 322.820312 82.621094 320.359375 L 107.253906 295.699219 C 129.800781 306.417969 156.328125 313.589844 187.15625 313.589844 C 285.046875 313.589844 340.257812 241.777344 359.273438 211.3125 C 364.390625 203.125 364.390625 192.691406 359.273438 184.5 Z M 187.15625 273.40625 C 170.867188 273.40625 155.851562 268.167969 143.539062 259.367188 L 170.953125 231.921875 C 175.855469 234.292969 181.332031 235.65625 187.15625 235.65625 C 207.980469 235.65625 224.859375 218.757812 224.859375 197.910156 C 224.859375 192.078125 223.496094 186.597656 221.128906 181.6875 L 248.542969 154.242188 C 257.332031 166.566406 262.5625 181.601562 262.5625 197.910156 C 262.566406 239.605469 228.800781 273.40625 187.15625 273.40625 Z M 114.425781 217.597656 C 112.730469 211.3125 111.746094 204.730469 111.746094 197.910156 C 111.746094 156.210938 145.507812 122.410156 187.15625 122.410156 C 193.96875 122.410156 200.542969 123.398438 206.820312 125.09375 L 241.574219 90.296875 C 225.136719 85.304688 207.074219 82.226562 187.15625 82.226562 C 89.261719 82.226562 34.050781 154.039062 15.035156 184.5 C 9.921875 192.691406 9.921875 203.125 15.035156 211.3125 C 23.6875 225.175781 39.929688 247.574219 64.183594 267.898438 Z M 114.425781 217.597656  " />
                            </svg>
                        </div>
                    </div>
                    <div>
                        @error('password_confirmation') <p class="text-red-500 text-12px">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </x-slot:confirm_password>

        <x-slot:update_button_container>
            <button type="button" wire:click="updateAccountInfo"
                    class="w-auto gap-x-[8px] text-[14px] bg-[#4AA76F] px-[12px] py-[8px] font-normal tracking-wider text-white rounded-full hover:drop-shadow hover:scale-105 hover:ease-in-out hover:transition-transform hover:bg-[#4D9B62] hover:shadow-xl">
                <span class="mt-[2px] px-2">Update Account</span>
            </button>
        </x-slot:update_button_container>

    </x-residents.profile-page-content-base>

    <!--Feedback Messages-->
    @if (session()->has('feedback'))
        <div
            x-data="{ openModal: true }"
            x-init="
            setTimeout(() => {
                openModal = false;
                setTimeout(() => location.reload(), 100); // Reload the page after the notification disappears
            }, 1000); // Auto-hide after 1 second

            @if (session('feedback_type') === 'success')
                lottie.loadAnimation({
                    container: $refs.lottieAnimation,
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    path: '{{ asset('animations/Animation - 1732372548058.json') }}',
                }).setSpeed(2); // Set speed multiplier (1 is normal, 2 is twice as fast)
            @elseif (session('feedback_type') === 'info')
                lottie.loadAnimation({
                    container: $refs.lottieAnimation,
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    path: '{{ asset('animations/Animation - 1737008068327.json') }}'
                });
            @elseif (session('feedback_type') === 'error')
                lottie.loadAnimation({
                    container: $refs.lottieAnimation,
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    path: '{{ asset('animations/Animation - 1732451860692.json') }}'
                });
            @endif"
            x-cloak>
            <!-- Notifications -->
            <div
                x-show="openModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="fixed top-5 left-1/2 transform -translate-x-1/2 z-50 bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-md border-l-4"
                :class="{
                'border-green-500': '{{ session('feedback_type') }}' === 'success',
                'border-blue-500': '{{ session('feedback_type') }}' === 'info',
                'border-red-500': '{{ session('feedback_type') }}' === 'error',
            }">
                <!-- Content -->
                <div class="p-4 flex items-center space-x-4">
                    <!-- Lottie Animation -->
                    <div class="flex-shrink-0">
                        <div x-ref="lottieAnimation" class="w-12 h-12"></div>
                    </div>

                    <!-- Message -->
                    <div>
                        <p class="font-bold text-lg"
                           :class="{
                            'text-green-600': '{{ session('feedback_type') }}' === 'success',
                            'text-blue-600': '{{ session('feedback_type') }}' === 'info',
                            'text-red-600': '{{ session('feedback_type') }}' === 'error',
                       }">
                            {{ strtoupper(session('feedback_type')) }}
                        </p>
                        <p class="text-sm text-gray-700">
                            {!! session('feedback') !!}
                        </p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mx-5 mb-3 relative h-1 bg-gray-200">
                    <div
                        class="absolute top-0 left-0 h-full"
                        :class="{
                        'bg-green-500': '{{ session('feedback_type') }}' === 'success',
                        'bg-blue-500': '{{ session('feedback_type') }}' === 'info',
                        'bg-red-500': '{{ session('feedback_type') }}' === 'error',
                    }"
                        style="animation: progress 4s linear;"></div>
                </div>
            </div>
        </div>
    @endif

    <!-- Progress Bar Animation -->
    <style>
        @keyframes progress {
            from {
                width: 100%;
            }

            to {
                width: 0;
            }
        }
    </style>

    <!-- Loading indicator for pagination -->
    <div wire:loading.class.remove="opacity-0 pointer-events-none" x-cloak x-transition
         class="z-50 absolute inset-0 w-full min-h-full bg-black/50 flex justify-center items-center transition-all pointer-events-none opacity-0">
        <x-loading-indicator class="h-[50px] w-[50px] text-white" wire:loading />
    </div>

</x-residents.residents-navigation>
