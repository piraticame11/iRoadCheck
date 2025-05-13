<div>
    <div x-data="{
        step: @entangle('step'),
        errors: {
            first_name: false,
            last_name: false,
            sex: false,
            phone: false,
            password: false,
            confirmPassword: false
        },
        validateField(field) {
            if (field === 'first_name') {
                this.errors.first_name = !document.getElementById('first_name').value.trim();
            } else if (field === 'last_name') {
                this.errors.last_name = !document.getElementById('last_name').value.trim();
            } else if (field === 'sex') {
                this.errors.sex = !document.getElementById('sex').value;
            } else if (field === 'phone') {
                const phoneInput = document.getElementById('phone');
                this.errors.phone = !phoneInput.value.match(/^0[0-9]{10}$/);

                if (!this.errors.phone) {
                    $wire.set('phone', phoneInput.value);
                    $wire.checkPhoneExists();
                }
            } else if (field === 'password') {
                this.validatePassword();
                this.errors.password = this.requirements.some(req => !req.isValid);
            } else if (field === 'confirmPassword') {
                this.errors.confirmPassword = this.password !== this.confirmPassword;
            }
        },
        validateStep1() {
            this.validateField('first_name');
            this.validateField('last_name');
            this.validateField('sex');

            if (!this.errors.first_name && !this.errors.last_name && !this.errors.sex) {
                this.step = 2;
            }
        },
        validateStep2() {
            this.validateField('phone');
            this.validateField('password');
            this.validateField('confirmPassword');

            const isValid = !(this.errors.phone || this.errors.password || this.errors.confirmPassword || @js($phoneError));

            console.log('Form validation result:', isValid);

            return isValid;
        },
        showPassword: false,
        showConfirmPassword: false,
        password: '',
        confirmPassword: '',
        requirements: [
            { text: 'At least 1 uppercase letter', isValid: false },
            { text: 'At least 1 lowercase letter', isValid: false },
            { text: 'At least 1 number', isValid: false },
            { text: 'At least 1 special character (!@#$%^&*)', isValid: false },
            { text: 'At least 8 characters', isValid: false },
        ],
        validatePassword() {
            this.requirements[0].isValid = /[A-Z]/.test(this.password);
            this.requirements[1].isValid = /[a-z]/.test(this.password);
            this.requirements[2].isValid = /[0-9]/.test(this.password);
            this.requirements[3].isValid = /[!@#$%^&*]/.test(this.password);
            this.requirements[4].isValid = this.password.length >= 8;

            if (this.confirmPassword) {
                this.validateField('confirmPassword');
            }
        },
        checkConfirmPassword() {
            return this.password === this.confirmPassword;
        },
        firstInvalidRequirementIndex() {
            return this.requirements.findIndex(req => !req.isValid);
        },
        capitalizeInput(el) {
            const words = el.value.split(' ').map(word => {
                return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
            });
            el.value = words.join(' ');
        }
    }"
         x-cloak
         class="relative h-screen font-poppins flex items-center justify-center">


        <form wire:submit.prevent="submitForm" class="flex flex-col items-center w-full px-5" id="signupForm">
            @csrf

            <div x-show="step === 1"
                class="bg-white shadow-lg rounded-2xl px-10 py-8 w-full max-w-xs sm:max-w-sm text-center relative z-10">
                <img src="{{ asset('/storage/images/IRoadCheck_Logo.png') }}" alt="iRoadCheck"
                    class="mx-auto mb-1 xs:-my-2 md:mb-1 md:mt-4 w-28 sm:w-36 md:w-48 lg:w-56 max-w-[40px]">
                <div class="text-[#4D4F50] mb-4 font-semibold text-[18px]">iRoadCheck</div>

                <h2 class="text-[20px] font-bold text-[#5A915E]">Sign Up</h2>
                <h2 class="text-sm mb-4 font-sm text-[#F8A15E]">Personal Information</h2>

                <div class="flex w-1/2 justify-center mx-auto">
                    <hr class="border-t-4 border-[#F8A15E] border-text-[24px] rounded-md w-1/3 mr-2">
                    <hr class="border-t-4 border-text-[24px] rounded-md w-1/3 ml-2">
                </div>

                <div class="space-y-2 my-6">
                    <!--First Name-->
                    <div class="relative">
                        <input type="text" name="first_name" id="first_name" autocomplete="off" required wire:model="first_name"
                            class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] peer"
                            placeholder=" " :class="{ 'border-red-500': errors.first_name }"
                            @input="validateField('first_name'); capitalizeInput($el)" />
                        <label for="first_name"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">First
                            Name</label>
                    </div>
                    <p x-show="errors.first_name" class="text-red-500 text-[12px] -mt-4">First name is required</p>

                    <!--Middle Name-->
                    <div class="relative">
                        <input type="text" name="middle_name" id="middle_name" wire:model="middle_name"  autocomplete="off"
                            class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] peer"
                            placeholder=" " @input="capitalizeInput($el)" />
                        <label for="middle_name"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Middle
                            Name</label>
                    </div>

                    <!--Last Name-->
                    <div class="relative">
                        <input type="text" name="last_name" id="last_name" autocomplete="off" required wire:model="last_name"
                            class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] peer"
                            placeholder=" " :class="{ 'border-red-500': errors.last_name }"
                            @input="validateField('last_name'); capitalizeInput($el);" />
                        <label for="last_name"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Last
                            Name</label>
                    </div>
                    <p x-show="errors.last_name" class="text-red-500 text-[12px] mt-1">Last name is required</p>

                    <!-- Sex -->
                    <div class="relative mb-5 custom-select w-full border-2 border-gray-300 rounded-lg">
                        <select id="sex" required name="sex" wire:model="sex"
                            class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 rounded-lg bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-[#4AA76F] focus:border-[#4AA76F] peer invalid:border-red-500 transition-all"
                            :class="{ 'border-red-500': errors.sex }" @change="validateField('sex')">
                            <option value="" disabled selected class="text-gray-500 hover:bg-gray-100">Select Sex
                            </option>
                            <option value="male" class="text-gray-700 hover:bg-gray-100">Male</option>
                            <option value="female" class="text-gray-700 hover:bg-gray-100">Female</option>
                        </select>
                        <label for="sex"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-x-28 md:-translate-x-36 -translate-y-4 scale-75 top-1 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-4">
                            Sex
                        </label>
                    </div>
                    <p x-show="errors.sex" class="text-red-500 text-[12px] mt-1">Please select a sex</p>
                </div>

                <button @click="validateStep1()" type="button"
                    class="w-full group relative inline-flex h-[calc(48px+8px)] items-center justify-center rounded-full text-[14px] bg-[#4AA76F] py-1 pl-6 pr-14 font-medium text-neutral-50">
                    <span class="z-10 ml-6">Next</span>
                    <div
                        class="absolute right-1 inline-flex h-12 w-12 items-center justify-end rounded-full bg-white bg-opacity-10 transition-[width] group-hover:w-[calc(100%-8px)]">
                        <div class="mr-3.5 flex items-center justify-center">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-50">
                                <path
                                    d="M8.14645 3.14645C8.34171 2.95118 8.65829 2.95118 8.85355 3.14645L12.8536 7.14645C13.0488 7.34171 13.0488 7.65829 12.8536 7.85355L8.85355 11.8536C8.65829 12.0488 8.34171 12.0488 8.14645 11.8536C7.95118 11.6583 7.95118 11.3417 8.14645 11.1464L11.2929 8H2.5C2.22386 8 2 7.77614 2 7.5C2 7.22386 2.22386 7 2.5 7H11.2929L8.14645 3.85355C7.95118 3.65829 7.95118 3.34171 8.14645 3.14645Z"
                                    fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </button>

                <!-- Go to Log In Page Button -->
                <button type="button" @click="window.location.href = '{{ route('residents-login') }}'"
                    class="mt-2 relative overflow-hidden w-full p-3 text-[14px] rounded-full font-normal hover:font-semibold text-[#F8A15E] border border-transparent bg-transparent group">
                    <span class="relative z-10 transition-[width] group-hover:text-white">
                        Go to Log in
                    </span>
                    <span
                        class="absolute inset-0 bg-[#F8A15E] transform rounded-full scale-x-0 origin-center transition-transform duration-200 group-hover:scale-x-100"></span>
                </button>
            </div>

            <div x-show="step === 2"
                class="bg-white shadow-lg rounded-2xl px-10 py-8 w-full max-w-xs sm:max-w-sm text-center relative z-10">
                <!-- Back Button Icon, visible when step is 2 -->
                <div class="absolute top-4 flex left-4 cursor-pointer text-gray-600 hover:text-green-600 hover:scale-110"
                    @click="step = 1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Back</span>
                </div>

                <img src="{{ asset('/storage/images/IRoadCheck_Logo.png') }}" alt="iRoadCheck"
                    class="mx-auto mb-1 xs:-my-2 md:mb-1 md:mt-4 w-28 sm:w-36 md:w-48 lg:w-56 max-w-[40px]">
                <div class="text-[#4D4F50] mb-4 font-semibold text-[18px]">iRoadCheck</div>

                <h2 class="text-[20px] font-bold text-[#5A915E]">Sign Up</h2>
                <h2 class="text-sm mb-4 font-sm text-[#F8A15E]">iRoadCheck Account</h2>

                <div class="flex w-1/2 justify-center mx-auto">
                    <hr class="border-t-4 border-[#F8A15E] border-text-[24px] rounded-md w-1/3 mr-2">
                    <hr class="border-t-4 border-[#F8A15E] border-text-[24px] rounded-md w-1/3 ml-2">
                </div>

                <!-- Account Information -->
                <div class="space-y-4 my-6">

                    <!--Phone Number-->
                    <div class="relative">
                        <input name="phone" type="tel" id="phone" required pattern="0[0-9]{10}"  autocomplete="off"
                            maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);"
                            wire:model.debounce.500ms="phone" @input="validateField('phone')"
                            class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] peer"
                            placeholder=" " :class="{ 'border-red-500': errors.phone || @this.phoneError }" />
                        <label for="phone"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                            Phone number
                        </label>
                    </div>
                    <!-- Show format error OR Livewire error -->
                    <p x-show="errors.phone" class="text-red-500 text-[12px] mt-1">Please enter a valid 11-digit phone
                        number starting with 09</p>
                    <p x-show="!errors.phone && @this.phoneError" class="text-red-500 text-[12px] mt-1">
                        {{ $phoneError }}</p>

                    <!-- Create Password Input -->
                    <div class="relative">
                        <input name="password" :type="showPassword ? 'text' : 'password'" x-model="password"
                            wire:model="password" @input="validateField('password')" id="password" required
                            class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] peer"
                            placeholder=" " :class="{ 'border-red-500': errors.password }" />
                        <label for="password" required
                            class="required absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Create
                            Password</label>

                        <!-- Show/Hide Password Toggle -->
                        <img src="{{ asset('storage/icons/show-password.png') }}"
                            class="absolute right-2.5 top-[10px] h-5 w-5 cursor-pointer" x-show="showPassword"
                            @click="showPassword = false" alt="Show Password">
                        <img src="{{ asset('storage/icons/hide-password.png') }}"
                            class="absolute right-2.5 top-[10px] h-5 w-5 cursor-pointer" x-show="!showPassword"
                            @click="showPassword = true" alt="Hide Password">
                    </div>

                    <!-- Password Requirements Message -->
                    <div class="text-sm mb-2" x-show="requirements.some(req => !req.isValid)">
                        <template x-for="(req, index) in requirements" :key="index">
                            <div x-show="!req.isValid">
                                <span class="text-red-500" x-text="req.text"></span>
                                <br x-show="index < requirements.length - 1">
                            </div>
                        </template>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="relative">
                        <input name="confirmPassword" :type="showConfirmPassword ? 'text' : 'password'"
                            x-model="confirmPassword" wire:model="confirmPassword"
                            @input="validateField('confirmPassword')" id="confirmPassword" required
                            class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] peer"
                            placeholder=" " :class="{ 'border-red-500': errors.confirmPassword }" />
                        <label for="confirmPassword"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Confirm
                            Password</label>

                        <!-- Show/Hide Confirm Password Toggle -->
                        <img src="{{ asset('storage/icons/show-password.png') }}"
                            class="absolute right-2.5 top-[10px] h-5 w-5 cursor-pointer" x-show="showConfirmPassword"
                            @click="showConfirmPassword = false" alt="Show Confirm Password">
                        <img src="{{ asset('storage/icons/hide-password.png') }}"
                            class="absolute right-2.5 top-[10px] h-5 w-5 cursor-pointer" x-show="!showConfirmPassword"
                            @click="showConfirmPassword = true" alt="Hide Confirm Password">
                    </div>

                    <!-- Confirm Password Error Message -->
                    <p x-show="confirmPassword && errors.confirmPassword" class="text-red-500 text-[12px] mb-2">
                        Passwords do not match.</p>
                </div>

                <button type="submit"
                    class="w-full hover:scale-105 text-[14px] bg-gradient-to-r from-[#5A915E] to-[#F8A15E] shadow-lg text-white p-3 font-medium mb-1 mt-2 rounded-full transition-all duration-300 hover:ease-in-out hover:duration-300 active:-translate-y-1 active:scale-x-90 active:scale-y-110">
                    Sign Up
                </button>


                <!-- Go to Log In Page Button -->
                <button type="button" @click.prevent="if(validateStep2()) $wire.submitForm()" wire:load="submitForm"
                    class="relative overflow-hidden w-full p-3 text-[14px] rounded-full font-normal hover:font-semibold text-[#F8A15E] border border-transparent bg-transparent group">
                    <span class="relative z-10 transition-[width] group-hover:text-white">
                        Go to Log in
                    </span>
                    <span
                        class="absolute inset-0 bg-[#F8A15E] transform rounded-full scale-x-0 origin-center transition-transform duration-200 group-hover:scale-x-100"></span>
                </button>
            </div>
        </form>
    </div>

    <!-- Loading indicator -->
    <div wire:loading.class.remove="opacity-0 pointer-events-none" x-cloak x-transition loading="lazy"
         class="z-50 absolute inset-0 w-full min-h-full bg-black/50 flex justify-center items-center transition-all pointer-events-none opacity-0">
        <x-loading-indicator class="h-[50px] w-[50px] text-white" wire:loading/>
    </div>
</div>
