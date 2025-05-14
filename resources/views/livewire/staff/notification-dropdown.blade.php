<div x-data="{
        open: false,
        dropdownVisible: false,
        notificationCount: @entangle('notifications_count').live,
        showRedDot() {
            return this.notificationCount > 0;
        },
        redirectTo(url) {
            window.location.href = url;
        }
    }"
     class="relative">

    <a href="{{ route('staff.notifications') }}">
        <!-- Notifications Icon -->
        <svg
            x-ref="content"
            :class="'cursor-pointer rounded-[4px] text-[#6AA76F]'"
            class="lazyload cursor-pointer w-6 h-6 hover:text-[#4AA76F] lg:mt-[5px] {{ request()->routeIs('staff.notifications') ? 'text-[#4AA76F]' : 'text-gray-400' }}"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 448 512"
            fill="{{ request()->routeIs('staff.notifications') ? '#4AA76F' : 'currentColor' }}"
            stroke="{{ request()->routeIs('staff.notifications') ? '#4AA76F' : 'currentColor' }}">
            <path
                fill="fillCurrent"
                d="M224 0c-17.7 0-32 14.3-32 32l0 19.2C119 66 64 130.6 64 208l0 18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416l384 0c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8l0-18.8c0-77.4-55-142-128-156.8L256 32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3l-64 0-64 0c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z" />
        </svg>
    </a>

    <!-- Notifications Count (Red Dot) -->
    <span x-cloak
          x-show="showRedDot()"
          class="lazyload absolute scale-90 -top-0.5 -right-0.5 grid min-h-[24px] min-w-[24px] max-h-[34px] max-w-[34px] translate-x-2/4 -translate-y-2/4 place-items-center rounded-full border-1 border-[#202020] bg-red-600 py-0.5 px-0.5 text-xs text-white pointer-events-none">
        {{ $notifications_count }}
    </span>
</div>
{{--    <!-- Dropdown Notifications -->--}}
{{--    <div x-show="dropdownVisible" x-cloak--}}
{{--         @click.away="dropdownVisible = false"--}}
{{--         x-transition:enter="transition ease-out duration-200"--}}
{{--         x-transition:enter-start="transform opacity-0 scale-100"--}}
{{--         x-transition:enter-end="transform opacity-100 scale-100"--}}
{{--         x-transition:leave="transition ease-in duration-75"--}}
{{--         x-transition:leave-start="transform opacity-100 scale-100"--}}
{{--         x-transition:leave-end="transform opacity-0 scale-95"--}}
{{--         class="lazyload absolute right-1 mt-2 w-[350px] rounded-md shadow-[0px_5px_40px_rgba(0,0,0,0.3)] bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-[1000]">--}}

{{--        <div class="py-1 px-4">--}}
{{--            <h1 class="text-[14px] pt-4 pb-2 px-1 border-b-[#4AA76F] border-b-[2px] text-[#4AA76F] font-semibold">--}}
{{--                Notifications--}}
{{--            </h1>--}}

{{--            <!-- Notifications List -->--}}
{{--            <ul class="overflow-y-auto overflow-x-hidden max-h-[320px] mt-2 mb-10 space-y-2 px-2 py-2">--}}
{{--                @if ($notifications->isEmpty())--}}
{{--                    <!-- Empty State -->--}}
{{--                    <div class="flex flex-col items-center justify-center h-full">--}}
{{--                        <img src="{{ asset('storage/icons/no-content-notification.gif') }}" loading="lazy" alt="no-content-notification" class="lazyload w-16 h-16 mb-2 opacity-35" />--}}
{{--                        <p class="text-[13px] font-light italic text-gray-400">No new notifications available.</p>--}}
{{--                    </div>--}}
{{--                @else--}}
{{--                    @foreach ($notifications as $notification)--}}
{{--                        <li class="relative block text-sm text-[#4D4F50]"--}}
{{--                            @click="$wire.markAsRead('{{ $notification->id }}').then(() => redirectTo('{{ route('staff.road-defect-reports', ['id' => $notification->id]) }}'))">--}}
{{--                            <div class="hover:bg-gray-200 flex justify-between py-4 rounded-md bg-gray-100">--}}
{{--                                <div class="flex pl-3 items-center space-x-3">--}}
{{--                                    <!-- Notifications Message Icon -->--}}
{{--                                    <div class="w-8 h-8 border rounded-full border-[#FFAD20] flex items-center justify-center">--}}
{{--                                        <img src="{{ asset('storage/icons/notification-message-icon.png') }}"--}}
{{--                                             alt="message-icon" class="lazyload w-4 h-4">--}}
{{--                                    </div>--}}
{{--                                    <!-- Notifications Message -->--}}
{{--                                    <div class="text-[12px] text-[#474747] font-semibold">--}}
{{--                                        <span>{{ $notification->title }}</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <!-- Time of Notifications Message -->--}}
{{--                                <div x-data="{--}}
{{--                                        now: new Date(),--}}
{{--                                        notificationDate: new Date('{{ $notification->created_at }}'),--}}
{{--                                        timeSince: ''--}}
{{--                                    }" x-init="--}}
{{--                                        const diffTime = now - notificationDate;--}}
{{--                                        const diffSeconds = Math.floor(diffTime / 1000);--}}
{{--                                        const diffMinutes = Math.floor(diffSeconds / 60);--}}
{{--                                        const diffHours = Math.floor(diffMinutes / 60);--}}
{{--                                        const diffDays = Math.floor(diffHours / 24);--}}

{{--                                        if (diffDays > 0) {--}}
{{--                                            timeSince = diffDays + ' day' + (diffDays > 1 ? 's' : '') + ' ago';--}}
{{--                                        } else if (diffHours > 0) {--}}
{{--                                            timeSince = diffHours + ' hour' + (diffHours > 1 ? 's' : '') + ' ago';--}}
{{--                                        } else if (diffMinutes > 0) {--}}
{{--                                            timeSince = diffMinutes + ' minute' + (diffMinutes > 1 ? 's' : '') + ' ago';--}}
{{--                                        } else {--}}
{{--                                            timeSince = diffSeconds + ' second' + (diffSeconds > 1 ? 's' : '') + ' ago';--}}
{{--                                        }--}}
{{--                                    ">--}}
{{--                                    <p x-text="timeSince" class="text-[10px] text-[#5E5E5E] px-3"></p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    @endforeach--}}
{{--                @endif--}}
{{--            </ul>--}}

{{--            <!-- View All Notifications Link -->--}}
{{--            <a href="{{ route('staff.notifications') }}"--}}
{{--               class="flex justify-center items-center">--}}
{{--                <div class="fixed -mt-10 px-24 py-1 bg-[#4AA76F] text-sm text-white hover:bg-[#2AA76F] font-medium rounded-[4px]">--}}
{{--                    <p class="text-[12px]">View All Notifications</p>--}}
{{--                </div>--}}
{{--            </a>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
