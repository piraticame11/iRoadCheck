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
     class="relative z-0">

    <a href="{{ route('admin.notifications') }}">
        <!-- Notifications Icon -->
        <svg x-ref="content"
             @click="dropdownVisible = !dropdownVisible"
             :class="'cursor-pointer rounded-[4px] text-[#6AA76F]'"
             class="lazyload cursor-pointer fill-current text-customGreen hover:text-[#6AA76F] mt-[9px]"
             xmlns="http://www.w3.org/2000/svg"
             width="22"
             height="22"
             viewBox="0 0 24 24">
            <path fill="fill-current"
                  d="M4.068,18H19.724a3,3,0,0,0,2.821-4.021L19.693,6.094A8.323,8.323,0,0,0,11.675,0h0A8.321,8.321,0,0,0,3.552,6.516l-2.35,7.6A3,3,0,0,0,4.068,18Z"/>
            <path fill="fill-current" d="M7.1,20a5,5,0,0,0,9.8,0Z"/>
        </svg>
    </a>

    <!-- Notifications Count (Red Dot) -->
    @if ($notifications_count > 0)
        <span x-cloak
              x-show="showRedDot()"
            class="lazyload absolute scale-90 -top-0.5 -right-0.5 grid min-h-[24px] min-w-[24px] max-h-[34px] max-w-[34px] translate-x-2/4 -translate-y-2/4 place-items-center rounded-full border-1 border-[#202020] bg-red-600 py-0.5 px-0.5 text-xs text-white pointer-events-none">
        {{ $notifications_count }}
    </span>
    @endif

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
{{--         class="lazyload absolute right-1 mt-2 w-[350px] rounded-md shadow-[0px_5px_40px_rgba(0,0,0,0.3)] bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-[999999]">--}}

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
{{--                            @click="$wire.markAsRead('{{ $notification->id }}').then(() => redirectTo('{{ route('admin.road-defect-reports', ['id' => $notification->id]) }}'))">--}}
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
{{--            <a href="{{ route('admin.notifications') }}"--}}
{{--               class="flex justify-center items-center">--}}
{{--                <div class="fixed -mt-10 px-24 py-1 bg-[#4AA76F] text-sm text-white hover:bg-[#2AA76F] font-medium rounded-[4px]">--}}
{{--                    <p class="text-[12px]">View All Notifications</p>--}}
{{--                </div>--}}
{{--            </a>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
