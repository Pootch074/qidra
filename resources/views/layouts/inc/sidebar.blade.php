<aside id="default-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-[#2e3192]">
        <div class="flex justify-center mb-6">
            <img src="{{ Vite::asset('resources/images/dswd-white.png') }}" alt="App Logo" class="h-12 w-auto">
            <img src="{{ Vite::asset('resources/images/qidra-logo3-white.png') }}" alt="App Logo"
                class="h-12 w-auto ml-4">
        </div>

        <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('admin') }}"
                    class="flex items-center p-2 text-white rounded-lg group
               {{ request()->routeIs('admin') ? 'bg-[#F03D46]' : 'hover:bg-[#5057c9]' }}">
                    <img src="{{ Vite::asset('resources/images/icons/bar-chart-big.png') }}" alt="Users"
                        class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
                    <span class="ms-3 text-white">Dashboard</span>
                </a>
            </li>

            <li x-data="{ open: @js(request()->routeIs('admin.*')) }" class="space-y-1">
                {{-- Parent --}}
                <button @click="open = !open" class="flex items-center w-full p-2 text-white rounded-lg transition">

                    <img src="{{ Vite::asset('resources/images/icons/group.png') }}" class="w-7 h-7 shrink-0">
                    <span class="flex-1 ms-3 text-left">Users</span>

                    {{-- Arrow --}}
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Children --}}
                <ul x-show="open" x-collapse class="pl-10 space-y-1 text-sm">
                    <li>
                        <a href="{{ route('admin.activeUsers') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-white transition
                {{ request()->routeIs('admin.activeUsers') ? 'bg-[#F03D46]' : 'hover:bg-[#5057c9]' }}">

                            {{-- Bullet --}}
                            <span
                                class="w-2 h-2 rounded-full
                    {{ request()->routeIs('admin.activeUsers') ? 'bg-white' : 'bg-gray-300' }}">
                            </span>

                            <span>Active Users</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.pendingUsers') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-white transition
                {{ request()->routeIs('admin.pendingUsers') ? 'bg-[#F03D46]' : 'hover:bg-[#5057c9]' }}">

                            {{-- Bullet --}}
                            <span
                                class="w-2 h-2 rounded-full
                    {{ request()->routeIs('admin.pendingUsers') ? 'bg-white' : 'bg-gray-300' }}">
                            </span>

                            <span>Pending Users</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ route('fetch.steps') }}"
                    class="flex items-center p-2 text-white rounded-lg group
               {{ request()->routeIs('fetch.steps') ? 'bg-[#F03D46]' : 'hover:bg-[#5057c9]' }}">
                    <img src="{{ Vite::asset('resources/images/icons/horizontal-align-right.png') }}" alt="Users"
                        class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
                    <span class="flex-1 ms-3 whitespace-nowrap text-white">Steps</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.windows') }}"
                    class="flex items-center p-2 text-white rounded-lg group
               {{ request()->routeIs('admin.windows') ? 'bg-[#F03D46]' : 'hover:bg-[#5057c9]' }}">
                    <img src="{{ Vite::asset('resources/images/icons/windows.png') }}" alt="Users"
                        class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
                    <span class="flex-1 ms-3 whitespace-nowrap text-white">Windows</span>
                </a>
            </li>

        </ul>
    </div>
</aside>
