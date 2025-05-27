<x-app-layout>
     <x-residents.residents-navigation>
         <div class="flex flex-col items-center justify-center w-full"
              x-cloak
              x-data="{
                openSuccessModal: false,
                openErrorModal: false,
                step: 1,
                showCurrentPassword: false,
                showNewPassword: false,
                showConfirmPassword: false,
                currentPassword: '',
                newPassword: '',
                confirmPassword: '',
                requirements: [
                    { text: 'At least 1 uppercase letter', isValid: false },
                    { text: 'At least 1 lowercase letter', isValid: false },
                    { text: 'At least 1 number', isValid: false },
                    { text: 'At least 1 special character (!@#$%^&*)', isValid: false },
                    { text: 'At least 8 characters', isValid: false },
                ],
                validatePassword() {
                    this.requirements[0].isValid = /[A-Z]/.test(this.newPassword);
                    this.requirements[1].isValid = /[a-z]/.test(this.newPassword);
                    this.requirements[2].isValid = /[0-9]/.test(this.newPassword);
                    this.requirements[3].isValid = /[!@#$%^&*]/.test(this.newPassword);
                    this.requirements[4].isValid = this.newPassword.length >= 8;
                },
                checkConfirmPassword() {
                    return this.newPassword === this.confirmPassword;
                },
                firstInvalidRequirementIndex() {
                    return this.requirements.findIndex(req => !req.isValid);
                }
            }" >
             <div class="w-full md:w-[85%] lg:w-[90%]flex flex-col md:flex-row justify-center pb-20">
                 <form>
                     <!-- Profile Name and Role Preview -->
                     <div class="rounded-lg lg:-ml-22 bg-gradient-to-r from-[#2C8B4A] via-[#4AA76F] to-[#4AA76F] pl-2 md:px-4 lg:pl-8 py-4 lg:py-6 mb-4 shadow">
                            <div class="flex justify-start items-center">
                                <!-- Profile Picture -->
                                <div class="relative mr-2 lg:mr-3">
                                    <!-- Profile Picture -->
                                    <img id="profileImage"
                                         src="{{ asset('storage/icons/profile-graphics.png') }}"
                                         alt="Profile Image"
                                         class="w-16 h-16 md:w-20 md:h-20 rounded-full border border-customGreen bg-[#F5F5F5] object-cover">

                                    <!-- Edit Button -->
                                    <label title="Edit Profile" for="profileUpload" class="absolute bottom-0 right-0 w-5 h-5 md:w-6 md:h-6 bg-gray-500 border border-gray-300 text-white flex justify-center items-center rounded-full cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 013.536 3.536L9 19l-4 1 1-4L16.732 3.732z" />
                                        </svg>
                                    </label>

                                    <!-- Hidden File Input -->
                                    <input id="profileUpload" type="file" accept="image/*" class="hidden"
                                           onchange="uploadProfilePicture(event)">
                                </div>

                                <!-- Staff Name -->
                                <div class="text-left">
                                    <p class="text-lg md:text-xl lg:text-2xl text-[F5F5F5] font-semibold">Sheena Mariz Pagas</p>
                                    <p class="text-xs md:text-sm font-normal italic text-gray-100">Resident</p>
                                </div>
                            </div>
                        </div>

                     <!-- Profile Navigation Tabs -->
                     <div class="w-full bg-none mx-auto">
                        <div id="scrollableTabs" class="custom-scroll-hidden py-6 px-1 flex overflow-x-auto whitespace-nowrap lg:w-full scrollbar-hide">

                            <!-- Personal Info -->
                            <button
                                @click="scrollToButton(1); step = 1" :class="step === 1 ? 'bg-green-50 font-medium text-[#4AA76F] border border-[#4AA76F] shadow shadow-green-200' : 'bg-white  hover:bg-gray-100 text-customGreen shadow'"
                                type="button" class="p-2.5 border text-xs lg:text-sm rounded-full flex-shrink-0 md:px-4 mr-2">
                                Personal Info
                            </button>

                            <!-- Contact Info -->
                            <button
                                @click="scrollToButton(2); step = 2" :class="step === 2 ? 'bg-green-50 font-medium text-[#4AA76F] border border-[#4AA76F] shadow shadow-green-200' : 'bg-white  hover:bg-gray-100 text-customGreen shadow'"
                                type="button" class="p-2.5 border text-xs lg:text-sm rounded-full flex-shrink-0 md:px-4 mr-2">
                                Contact Info
                            </button>

                            <!-- Change Password -->
                            <button
                                @click="scrollToButton(3); step = 3" :class="step === 3 ? 'bg-green-50 font-medium text-[#4AA76F] border border-[#4AA76F] shadow shadow-green-200' : 'bg-white  hover:bg-gray-100 text-customGreen shadow'"
                                type="button" class="p-2.5 border text-xs lg:text-sm rounded-full flex-shrink-0 md:px-4 mr-2">
                                Change Password
                            </button>
                        </div>
                     </div>

                     <!-- Step 1 -->
                    <template x-if="step === 1">
                        <!-- Personal information Section -->
                        <div class="mt-2 bg-white w-full shadow rounded-lg h-auto p-4 md:px-6 border border-gray-100 mx-auto">

                            <!-- Header -->
                            <div class="w-full bg-transparent h-10 py-2 border-b-2 border-[#F8A15E] items-center">
                                <p class="text-[#F8A15E] font-semibold text-center md:text-start">Personal Information</p>
                            </div>

                            <!-- Body/Input Fields-->
                            <div class="mt-6 flex flex-col items-center justify-center md:flex-row md:flex-wrap md:grid md:grid-cols-2 lg:justify-center">

                                <!--First Name-->
                                <div class="relative md:mr-3 lg:mr-5 mb-3.5">
                                    <input type="text" id="first_name" required class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none  focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F]  peer" placeholder=" " />
                                    <label for="first_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">First Name</label>
                                </div>

                                <!--Middle Name-->
                                <div class="relative md:mr-3 lg:mr-5 mb-3.5">
                                    <input type="text" id="middle_name" required class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none  focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F]  peer" placeholder=" " />
                                    <label for="middle_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Middle Name</label>
                                </div>

                                <!--Last Name-->
                                <div class="relative md:mr-3 lg:mr-5 mb-3.5">
                                    <input type="text" id="last_name" required class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none  focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F]  peer" placeholder=" " />
                                    <label for="last_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Last Name</label>
                                </div>

                                <!-- Sex -->
{{--                                <div class="relative mb-5 lg:mr-5 custom-select w-[195px] sm:w-[290px] md:w-auto border-2 border-gray-300 rounded-lg md:mr-3">--}}
{{--                                    <select id="sex" required--}}
{{--                                            class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 rounded-lg bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-[#4AA76F] focus:border-[#4AA76F] peer invalid:border-red-500 transition-all">--}}
{{--                                        <option value="" disabled selected class="text-gray-500 hover:bg-gray-100">Select Sex</option>--}}
{{--                                        <option value="male" class="text-gray-700 hover:bg-gray-100">Male</option>--}}
{{--                                        <option value="female" class="text-gray-700 hover:bg-gray-100">Female</option>--}}
{{--                                    </select>--}}
{{--                                    <label for="sex"--}}
{{--                                           class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4">--}}
{{--                                        Sex--}}
{{--                                    </label>--}}
{{--                                </div>--}}

                            </div>

                            <!-- Footer/Button -->
                            <div class="my-4 w-[75%] mx-auto lg:my-2 lg:mx-0 lg:ml-auto md:w-[30%] lg:w-[25%]">
                                <!-- Save Button -->
                                <button x-on:click="openSuccessModal = true; $event.preventDefault();" class="px-4 py-3 w-full bg-gradient-to-r from-[#5A915E] to-[#F8A15E] text-sm font-medium lg:text-[15px] lg:py-3 lg:mb-2 text-white shadow-md rounded-full transition-all duration-300">
                                    Save Changes
                                </button>

                                <!-- Success Modal -->
                                <x-success-modal successMessage="Your changes have been successfully saved." x-show="openSuccessModal"></x-success-modal>

                                <!-- Error Modal | This should be pop up if the user inputs is not valid-->
                                {{--<x-error-modal errorMessage='Oops! Something went wrong. Please try again.' x-show="openErrorModal"></x-error-modal>--}}
                            </div>
                        </div>
                    </template>

                    <!-- Step 2 -->
                    <template x-if="step === 2">
                        <!-- Contact information Section -->
                        <div class="mt-2 bg-white w-full shadow rounded-lg h-auto p-4 md:px-6 border border-gray-100 mx-auto">

                            <!-- Header -->
                            <div class="w-full bg-transparent h-10 py-2 border-b-2 border-[#F8A15E] items-center">
                                <p class="text-[#F8A15E] font-semibold text-center md:text-start">Contact Information</p>
                            </div>

                            <!-- Body/Input Fields-->
                            <div class="mt-6 flex flex-col items-center justify-center md:flex-row md:flex-wrap md:grid md:grid-cols-2 lg:justify-center">

                                <!--Phone Number-->
                                <div class="relative md:mr-3 lg:mr-5 mb-3.5">
                                    <input type="number" id="phone_number" required class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none  focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F]  peer" placeholder=" " />
                                    <label for="phone_number" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Phone Number</label>
                                </div>

                                <!--Email Address-->
                                <div class="relative md:mr-3 mb-3.5">
                                    <input type="email" id="email_address" required class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none  focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F]  peer" placeholder=" " />
                                    <label for="email_address" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Email Address</label>
                                </div>

                            </div>

                            <!-- Footer/Button -->
                            <div class="my-4 w-[75%] mx-auto lg:my-3 lg:mx-0 lg:ml-auto md:w-[30%] lg:w-[25%]">
                                <!-- Save Button -->
                                <button x-on:click="openSuccessModal = true; $event.preventDefault();" class="px-4 py-3 w-full bg-gradient-to-r from-[#5A915E] to-[#F8A15E] text-sm font-medium lg:text-[15px] lg:py-3 lg:mb-2 text-white shadow-md rounded-full transition-all duration-300 [transition-timing-function:cubic-bezier(0.175,0.885,0.32,1.275)] active:-translate-y-1 active:scale-x-90 active:scale-y-110">
                                    Save Changes
                                </button>

                                <!-- Success Modal -->
                                <x-success-modal successMessage="Your contact information changes have been successfully saved." x-show="openSuccessModal"></x-success-modal>

                                <!-- Error Modal | This should be pop up if the user inputs is not valid-->
                                {{--<x-error-modal errorMessage='Oops! Something went wrong. Please try again.' x-show="openErrorModal"></x-error-modal>--}}
                            </div>
                        </div>
                    </template>

                    <!-- Step 3 -->
                    <template x-if="step === 3">
                        <!-- Change Password Section -->
                        <div class="mt-2 bg-white w-full shadow rounded-lg h-auto p-4 md:px-6 border border-gray-100 mx-auto">

                            <!-- Header -->
                            <div class="w-full bg-transparent h-10 py-2 border-b-2 border-[#F8A15E] items-center">
                                <p class="text-[#F8A15E] font-semibold text-center md:text-start">Change Password</p>
                            </div>

                            <!-- Body/Input Fields-->
                            <div class="w-full mt-4 flex flex-col items-center justify-between md:flex-row md:flex-wrap">

                                <div class="md:w-4/10">

                                    <!-- Current Password Input -->
                                    <div class="relative w-full md:mr-3 lg:mr-5 lg:mb-6 mb-4 border-2 border-gray-300 rounded-lg">
                                        <input :type="showCurrentPassword ? 'text' : 'password'" id="currentPassword" class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] peer" placeholder=" " />
                                        <label for="currentPassword" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Current Password</label>

                                        <!-- Show/Hide Password Toggle -->
                                        <img src="{{ asset('storage/icons/show-password.png') }}" class="absolute right-2.5 top-[10px] h-5 w-5 cursor-pointer" x-show="showCurrentPassword" @click="showCurrentPassword = false" alt="Show Password">
                                        <img src="{{ asset('storage/icons/hide-password.png') }}" class="absolute right-2.5 top-[10px] h-5 w-5 cursor-pointer" x-show="!showCurrentPassword" @click="showCurrentPassword = true" alt="Hide Password">
                                    </div>

                                    <!-- New Password Input -->
                                    <div class="relative w-full md:mr-3 lg:mr-5 lg:mb-6 mb-4">
                                        <input :type="showNewPassword ? 'text' : 'password'" x-model="newPassword" @input="validatePassword()" id="newPassword" required class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] peer" placeholder=" " />
                                        <label for="newPassword" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">New Password</label>

                                        <!-- Show/Hide Password Toggle -->
                                        <img src="{{ asset('storage/icons/show-password.png') }}" class="absolute right-2.5 top-[10px] h-5 w-5 cursor-pointer" x-show="showNewPassword" @click="showNewPassword = false" alt="Show Password">
                                        <img src="{{ asset('storage/icons/hide-password.png') }}" class="absolute right-2.5 top-[10px] h-5 w-5 cursor-pointer" x-show="!showNewPassword" @click="showNewPassword = true" alt="Hide Password">
                                    </div>

                                    <!-- Create Password - Password Requirements Message -->
                                    <div class="text-sm mb-4 text-center block md:hidden">
                                        <div x-show="newPassword.length > 0 && firstInvalidRequirementIndex() !== -1">
                                            <span :class="{
                                                    'text-red-500 text-xs': !requirements[firstInvalidRequirementIndex()].isValid,
                                                    'text-green-500 text-xs': requirements[firstInvalidRequirementIndex()].isValid
                                                  }"
                                                  x-text="requirements[firstInvalidRequirementIndex()].text">
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Confirm Password Input -->
                                    <div class="flex flex-col">
                                        <div class="relative w-full md:mr-3 lg:mr-5 md:mb-0 mb-4">
                                            <input :type="showConfirmPassword ? 'text' : 'password'" x-model="confirmPassword" id="confirmPassword" required class="block px-2.5 pb-2 pt-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none  focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F]  peer" placeholder=" " />
                                            <label  for="confirmPassword"  class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Confirm Password</label>

                                            <!-- Show/Hide Confirm Password Toggle -->
                                            <img src="{{ asset('storage/icons/show-password.png') }}"
                                                 class="absolute right-2.5 top-[10px] h-5 w-5 cursor-pointer"
                                                 x-show="showConfirmPassword"
                                                 @click="showConfirmPassword = false"
                                                 alt="Show Confirm Password">
                                            <img src="{{ asset('storage/icons/hide-password.png') }}"
                                                 class="absolute right-2.5 top-[10px] h-5 w-5 cursor-pointer"
                                                 x-show="!showConfirmPassword"
                                                 @click="showConfirmPassword = true"
                                                 alt="Hide Confirm Password">
                                        </div>

                                        <!-- Confirm Password Error Message -->
                                        <p x-show="confirmPassword && !checkConfirmPassword()" class="text-red-500 text-xs mb-2 text-center md:text-start">Passwords do not match.</p>
                                    </div>

                                </div>

                                <!-- Create Password - Password Requirements Message -->
                                <div class="relative shadow md:w-5/10 hidden md:block md:mr-3 mb-3.5 mt-6">
                                    <div class="w-full">
                                        <!-- Password requirements -->
                                        <div class="w-full pb-6 pl-6 pt-2 rounded">
                                            <div class="text-gray-600 text-[14px] font-semibold mb-4">Password Requirements</div>
                                            <div class="text-[13px] text-gray-500 mb-1">Please follow this guide for a strong password:</div>
                                            <ul class="list-disc pl-10 leading-6">
                                                <li :class="{'text-green-500': requirements[0].isValid, 'text-gray-500': !requirements[0].isValid}" class="text-[13px]">At least one uppercase letter</li>
                                                <li :class="{'text-green-500': requirements[1].isValid, 'text-gray-500': !requirements[1].isValid}" class="text-[13px]">At least one lowercase letter</li>
                                                <li :class="{'text-green-500': requirements[2].isValid, 'text-gray-500': !requirements[2].isValid}" class="text-[13px]">At least one number</li>
                                                <li :class="{'text-green-500': requirements[3].isValid, 'text-gray-500': !requirements[3].isValid}" class="text-[13px]">At least one special character</li>
                                                <li :class="{'text-green-500': requirements[4].isValid, 'text-gray-500': !requirements[4].isValid}" class="text-[13px]">Minimum 8 characters</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer/Button -->
                            <div class="my-4 w-[75%] mx-auto lg:my-3 lg:mx-0 lg:ml-auto md:w-[30%] lg:w-[25%]">
                                <!-- Save Button -->
                                <button x-on:click="openSuccessModal = true; $event.preventDefault();" class="px-4 py-3 w-full bg-gradient-to-r from-[#5A915E] to-[#F8A15E] text-sm font-medium lg:text-[15px] lg:py-3 lg:mb-2 text-white shadow-md rounded-full transition-all duration-300 [transition-timing-function:cubic-bezier(0.175,0.885,0.32,1.275)] active:-translate-y-1 active:scale-x-90 active:scale-y-110">
                                    Update Account
                                </button>

                                <!-- Success Modal -->
                                <x-success-modal successMessage="Your password changes have been successfully saved." x-show="openSuccessModal"></x-success-modal>

                                <!-- Error Modal | This should be pop up if the user inputs is not valid-->
                                {{--<x-error-modal errorMessage='Oops! Something went wrong. Please try again.' x-show="openErrorModal"></x-error-modal>--}}
                            </div>
                        </div>
                    </template>
                </form>
             </div>
         </div>

         <script>
             function uploadProfilePicture(event) {
                 const file = event.target.files[0];
                 if (file) {
                     const reader = new FileReader();
                     reader.onload = function (e) {
                         const profileImage = document.getElementById('profileImage');
                         profileImage.src = e.target.result;
                         profileImage.classList.add('object-cover'); // Maintain aspect ratio

                         // Ensure the image is displayed at its highest quality
                         profileImage.style.imageRendering = 'auto';
                     };
                     reader.readAsDataURL(file);
                 }
             }
             function scrollToButton(step) {
                 const tabsContainer = document.getElementById('scrollableTabs');
                 const button = tabsContainer.querySelector(`button:nth-child(${step})`);

                 if (button) {
                     tabsContainer.scrollTo({
                         left: button.offsetLeft - 16, // Offset for spacing
                         behavior: 'smooth', // Smooth scrolling effect
                     });
                 }
             }
         </script>

     </x-residents.residents-navigation>
</x-app-layout>
