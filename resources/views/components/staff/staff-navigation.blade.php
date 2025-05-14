@php use Illuminate\Support\Facades\Route; @endphp
@props(['page_title' => '','action' => '', 'placeholder' => '', 'name' => '', 'main_class' => ''])

<div x-cloak
     class=" bg-none font-pop mx-0 px-0"
     x-data="{
        open: false,
        expanded: JSON.parse(localStorage.getItem('sidebarExpanded')) ?? true,
        mobileOpen: false,
        toggleSidebar() {
            this.expanded = !this.expanded;
            localStorage.setItem('sidebarExpanded', JSON.stringify(this.expanded));
        },
        toggleMobileSidebar() {
            this.mobileOpen = !this.mobileOpen;
        },
        isOpen: JSON.parse(localStorage.getItem('dropdownState')) || false,

        toggle() {
            // Toggle the dropdown only if the sidebar is expanded
            if (this.expanded) {
                this.isOpen = !this.isOpen;
                localStorage.setItem('dropdownState', JSON.stringify(this.isOpen));
            }
        },

        expandSidebar() {
            this.expanded = true;
            localStorage.setItem('sidebarExpanded', JSON.stringify(this.expanded));
        },

        openLogs() {
            // Check if the sidebar is already expanded
            if (!this.expanded) {
                // Expand the sidebar and open the dropdown
                this.expandSidebar();
                this.isOpen = true;
            } else {
                // If the sidebar is already expanded, toggle the dropdown
                this.isOpen = !this.isOpen;
            }
            localStorage.setItem('dropdownState', JSON.stringify(this.isOpen));
        }
     }">

    <div class="flex">

        <!-- Web screens Sidebar -->
        <x-Staff.staff-web-sidebar>
            <x-slot:navbar_links>
                <!-- Dashboard -->
                <a href="{{ route('staff.dashboard') }}" wire:navigate loading="lazy" class="group flex items-center block py-2.5 px-5 rounded font-medium
                    {{ request()->routeIs('staff.dashboard') ? 'bg-[#4AA76F] text-white shadow-md font-bold' : 'text-[#4D4F50] hover:text-[#4AA76F] hover:bg-gray-50 hover:shadow-md' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="lazyload w-5 h-5 fill-current
                        {{ request()->routeIs('staff.dashboard') ? 'text-white' : 'text-[#4D4F50] group-hover:text-[#4AA76F] group-hover:scale-105 duration-200' }}">
                        <path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" />
                    </svg>
                    <p x-show="expanded" class="ml-2
                        {{ request()->routeIs('staff.dashboard') ? 'text-white' : 'text-[#4D4F50] group-hover:text-[#4AA76F] group-hover:font-semibold' }}">
                        Dashboards
                    </p>
                </a>

                <!-- Update Road Defect -->
                <a href="{{ route('staff.capture-road-defect') }}" wire:navigate loading="lazy" class="lazyload group flex items-center block py-2.5 px-5 rounded font-medium
                    {{ request()->routeIs('staff.capture-road-defect') ? 'bg-[#4AA76F] text-white shadow-md font-bold' : 'text-[#4D4F50] hover:text-[#4AA76F] hover:bg-gray-50 hover:shadow-md' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                         class="lazyload w-5 h-5 fill-current
                        {{ request()->routeIs('staff.capture-road-defect') ? 'text-white' : 'text-[#4D4F50] group-hover:text-[#4AA76F] group-hover:scale-105 duration-200' }}">
                        <path fill="currentColor"  fill-rule="nonzero" d="M9 7.6875C9.31065 7.6875 9.5625 7.93935 9.5625 8.25V9.1875H10.5C10.8107 9.1875 11.0625 9.43935 11.0625 9.75C11.0625 10.0607 10.8107 10.3125 10.5 10.3125H9.5625V11.25C9.5625 11.5607 9.31065 11.8125 9 11.8125C8.68935 11.8125 8.4375 11.5607 8.4375 11.25V10.3125H7.5C7.18934 10.3125 6.9375 10.0607 6.9375 9.75C6.9375 9.43935 7.18934 9.1875 7.5 9.1875H8.4375V8.25C8.4375 7.93935 8.68935 7.6875 9 7.6875Z"/>
                        <path fill="currentColor" fill-rule="nonzero" d="M7.33333 15.75H10.6666C13.0075 15.75 14.1778 15.75 15.0186 15.1985C15.3825 14.9597 15.695 14.6528 15.9382 14.2955C16.5 13.4701 16.5 12.3209 16.5 10.0227C16.5 7.72455 16.5 6.57541 15.9382 5.74995C15.695 5.39261 15.3825 5.08578 15.0186 4.84701C14.4783 4.4926 13.802 4.36592 12.7665 4.32064C12.2723 4.32064 11.8469 3.95301 11.75 3.47727C11.6046 2.76367 10.9664 2.25 10.2253 2.25H7.77472C7.03354 2.25 6.39536 2.76367 6.25 3.47727C6.15309 3.95301 5.72764 4.32064 5.2335 4.32064C4.198 4.36592 3.52166 4.4926 2.98143 4.84701C2.61746 5.08578 2.30496 5.39261 2.06177 5.74995C1.5 6.57541 1.5 7.72455 1.5 10.0227C1.5 12.3209 1.5 13.4701 2.06177 14.2955C2.30496 14.6528 2.61746 14.9597 2.98143 15.1985C3.82218 15.75 4.99256 15.75 7.33333 15.75ZM12 9.75C12 11.4068 10.6568 12.75 9 12.75C7.34314 12.75 6 11.4068 6 9.75C6 8.09318 7.34314 6.75 9 6.75C10.6568 6.75 12 8.09318 12 9.75ZM13.5 6.9375C13.1893 6.9375 12.9375 7.18934 12.9375 7.5C12.9375 7.81065 13.1893 8.0625 13.5 8.0625H14.25C14.5606 8.0625 14.8125 7.81065 14.8125 7.5C14.8125 7.18934 14.5606 6.9375 14.25 6.9375H13.5Z" />
                    </svg>
                    <p x-show="expanded" class="ml-2
                        {{ request()->routeIs('staff.capture-road-defect') ? 'text-white' : 'text-[#4D4F50] group-hover:text-[#4AA76F] group-hover:font-semibold' }}">
                        Update Road Defect
                    </p>
                </a>

                <!-- Road Defect Reports -->
                <a href="{{ route('staff.road-defect-reports') }}" wire:navigate loading="lazy" class="group flex items-center block py-2.5 px-5 rounded font-medium
                    {{ request()->routeIs('staff.road-defect-reports') ? 'bg-[#4AA76F] text-white shadow-md font-bold' : 'text-[#4D4F50] hover:text-[#4AA76F] hover:bg-gray-50 hover:shadow-md' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 527 455"
                         class="lazyload w-5 h-5 fill-current
                        {{ request()->routeIs('staff.road-defect-reports') ? 'text-white' : 'text-[#4D4F50] group-hover:text-[#4AA76F] group-hover:scale-105 duration-200' }}">
                        <path d="M164.541 227.5H109.499C101.414 227.5 94.8604 233.159 94.8604 240.139V360.461C94.8604 367.441 101.414 373.1 109.499 373.1H164.541C172.626 373.1 179.18 367.441 179.18 360.461V240.139C179.18 233.159 172.626 227.5 164.541 227.5Z"/>
                        <path d="M291.021 81.9004H235.979C227.894 81.9004 221.34 87.559 221.34 94.5393V360.462C221.34 367.442 227.894 373.1 235.979 373.1H291.021C299.106 373.1 305.66 367.442 305.66 360.462V94.5393C305.66 87.559 299.106 81.9004 291.021 81.9004Z"/>
                        <path d="M417.501 178.967H362.459C354.374 178.967 347.82 184.625 347.82 191.606V360.461C347.82 367.442 354.374 373.1 362.459 373.1H417.501C425.586 373.1 432.14 367.442 432.14 360.461V191.606C432.14 184.625 425.586 178.967 417.501 178.967Z"/>
                    </svg>
                    <p x-show="expanded" class="ml-2
                        {{ request()->routeIs('staff.road-defect-reports') ? 'text-white' : 'text-[#4D4F50] group-hover:text-[#4AA76F] group-hover:font-semibold' }}">
                        Road Defect Reports
                    </p>
                </a>

                <!-- Update History -->
                <a href="{{ route('staff.report-history') }}" wire:navigate loading="lazy" class="lazyload group flex items-center block py-2.5 px-5 rounded font-medium
                    {{ request()->routeIs('staff.report-history') ? 'bg-[#4AA76F] text-white shadow-md font-bold' : 'text-[#4D4F50] hover:text-[#4AA76F] hover:bg-gray-50 hover:shadow-md' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                         class="lazyload w-5 h-5 fill-current mt-1.5
                        {{ request()->routeIs('staff.report-history') ? 'text-white' : 'text-[#4D4F50] group-hover:text-[#4AA76F] group-hover:scale-105 duration-200' }}">
                        <path fill="currentColor" d="M7.5 0C9.48912 0 11.3968 0.790176 12.8033 2.1967C14.2098 3.60322 15 5.51088 15 7.5C15 9.48912 14.2098 11.3968 12.8033 12.8033C11.3968 14.2098 9.48912 15 7.5 15C5.51088 15 3.60322 14.2098 2.1967 12.8033C0.790176 11.3968 0 9.48912 0 7.5C0 5.51088 0.790176 3.60322 2.1967 2.1967C3.60322 0.790176 5.51088 0 7.5 0ZM6.79688 3.51562V7.28613L5.03906 9.92285L4.64941 10.5088L5.81836 11.2881L6.20801 10.7021L8.08301 7.88965L8.2002 7.71387V7.5V3.51562V2.8125H6.79395V3.51562H6.79688Z"/>
                    </svg>
                    <p x-show="expanded" class="ml-2
                        {{ request()->routeIs('staff.report-history') ? 'text-white' : 'text-[#4D4F50] group-hover:text-[#4AA76F] group-hover:font-semibold' }}">
                        Update History
                    </p>
                </a>

                <!-- Logout Button -->
                <a href="javascript:void(0);"
                   @click="logoutStaff"
                   class="group flex items-center block py-2.5 px-5 rounded hover:text-[#4AA76F] hover:shadow-md font-medium text-[#4D4F50]">

                    <!-- Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                         class="w-5 h-5 fill-current">
                        <path
                            d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/>
                    </svg>

                    <!-- Text -->
                    <p  x-show="expanded"
                        class="ml-2">Logout</p>
                </a>
            </x-slot:navbar_links>
        </x-Staff.staff-web-sidebar>

        <!-- Mobile screens Sidebar -->
        <x-Staff.staff-mobile-sidebar/>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">

            <x-Staff.staff-header>

                <x-slot:logo>
                    <!-- iRoadCheck Logo -->
                    <a href="{{ route('staff.dashboard') }}" wire:navigate title="Go to Dashboard">
                        <div class="flex justify-start items-center">
                            <img src="{{ asset('storage/images/IRoadCheck_Logo.png') }}" alt="graphicsLogo"
                                 class="w-8 max-w-10 mr-1" />
                            <div class="mt-0 text-[#4D4F50] font-bold text-[15px]">iRoadCheck</div>
                        </div>
                    </a>
                </x-slot:logo>

                <x-slot:page_title_and_search_bar>
                    <h1 class="hidden md:block lg:text-[22px] text-md md:text-lg mt-0 font-semibold text-[#4AA76F] md:mt-3 md:mr-3 lg:mr-1 ml-auto ">{{$page_title}}</h1>

                    @if (!request()->routeIs('staff.profile-edit') && !request()->routeIs('staff.dashboard') && !request()->routeIs('staff.road-defect-reports') && !request()->routeIs('staff.capture-road-defect') && !session('hideSearchBar'))
                    <!-- Search Bar -->
                        <div class="hidden md:block flex mt-2 lg:mt-3 w-48 lg:w-80 items-center px-0 lg:px-5">
                            <div class="relative flex flex-1 h-8 rounded-full">
                                <label for="search-field" class="sr-only">Search</label>
                                <svg   class="pointer-events-none absolute inset-y-0 left-1 h-full w-4 text-gray-400 ml-2 z-0"
                                       xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#4AA76F">
                                    <path d="M368 208A160 160 0 1 0 48 208a160 160 0 1 0 320 0zM337.1 371.1C301.7 399.2 256.8 416 208 416C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208c0 48.8-16.8 93.7-44.9 129.1l124 124 17 17L478.1 512l-17-17-124-124z"/>
                                </svg>
                                <input
                                    id="search-field"
                                    class="border border-gray-300 focus:outline-none focus:ring-[0.5px] focus:ring-[#4AA76F] focus:border-[#4AA76F] shadow-[0px_1px_5px_rgba(0,0,0,0.2)] focus:bg-white bg-white rounded-full block h-full w-full py-0 pl-8 text-gray-900 placeholder:text-gray-400 text-xs lg:text-[14px]"
                                    wire:model.live="search"
                                    placeholder="{{ $placeholder ?? 'Search...' }}"
                                    type="search"
                                />
                            </div>
                        </div>
                    @endif
                </x-slot:page_title_and_search_bar>

                <x-slot:notification_dropdown>
                    <livewire:staff.notification-dropdown/>
                </x-slot:notification_dropdown>

                <x-slot:staff_profile_name>
                    <!-- Profile Icon with Click and Bounce Microinteraction -->
                    <a x-ref="content"  wire:navigate
                       href="{{ route('staff.profile-edit') }}"
                       @click="handleClick()"
                       :class="{ 'scale-105 animate-bounce-once': isClicked }"
                       class="lazyload -my-1.5 flex items-center p-1.5 transition-transform duration-150 ease-in-out transform"
                       id="profile">
                        <img src="{{ Auth::user()->profile_picture_url }}" alt="Profile Picture"
                             class="h-8 w-8 rounded-full bg-gray-50 mr-2 border border-[#4AA76F] object-cover"/>
                        <span class="flex flex-col items-left justify-left">
                            <span class="text-sm font-semibold leading-4 text-[#202020] hidden lg:block">
                                {{ Auth::user()->name }}</span>
                            <span class="text-xs leading-4 text-[#202020] hidden lg:block">
                                {{ ucwords(Auth::user()->userTypes->type) }}
                            </span>
                        </span>
                    </a>
                </x-slot:staff_profile_name>


                <x-slot:mobile_notification_and_profile_header>
                    <!-- Notifications and Profile Icon -->
                    <div class="block md:hidden flex items-center space-x-3 pr-2">

                        <livewire:staff.notification-dropdown/>

                        <div x-data="{ openDropdown: false }" class="relative">
                            <!-- Profile Icon -->
                            <a @click="openDropdown = !openDropdown; activeLink = ''"
                               class="cursor-pointer " >
                                <div :class="{
                                    'border-2 border-customGreen rounded-full': openDropdown
                                 }">
                                    <img src="{{ Auth::user()->profile_picture_url }}" alt="Profile Image"
                                         class="w-8 h-8 rounded-full hover:bg-customGreen object-cover
                                        {{ request()->routeIs('profile-info') ? 'border-2 border-customGreen bg-customGreen' : 'border border-gray-400' }}">
                                </div>
                            </a>

                            <!-- Dropdown Menu -->
                            <div x-show="openDropdown"
                                 x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white shadow-[0px_5px_40px_rgba(0,0,0,0.2)] rounded-lg z-50">

                                <ul class="space-y-2">
                                    <!-- Profile Info Link -->
                                    <li>
                                        <a href="{{ route('staff.profile-edit') }}"
                                           class="block px-4 py-2 text-gray-800 hover:bg-gray-100 rounded-t-lg border-b border-gray-300 text-sm">
                                            Profile Settings
                                        </a>
                                    </li>
                                    <!-- Logout Option -->
                                    <li>
                                        <a href="javascript:void(0);"
                                           @click="logoutStaff">
                                            <div method="POST" class="block rounded-b-lg px-4 py-2 text-gray-800 hover:bg-gray-100 text-sm">
                                                @csrf
                                                <button type="submit" class="w-full text-left">Logout</button>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </x-slot:mobile_notification_and_profile_header>
            </x-Staff.staff-header>




            <!-- Content Area -->
            <main
                class="mt-24 md:pt-2 md:mt-0 flex-1 rounded-md mb-4 mx-0 p-0 bg-none w-full pr-0 md:pr-3 {{ ' '.$main_class }}">
                <!-- Main content here -->
                {{ $slot }}
            </main>

        </div>

        <script>
            function logoutStaff() {
                // Create the logout form dynamically
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('logoutStaff') }}'; // Laravel logout route

                // Add CSRF token for security
                let csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}'; // Laravel CSRF token
                form.appendChild(csrfToken);

                // Append the form to the body and submit it
                document.body.appendChild(form);
                form.submit();
            }
        </script>
    </div>
</div>
