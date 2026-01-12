<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="w-10 h-10" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    {{-- 1. DASHBOARD (Wajib) --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard Statistik') }}
                    </x-nav-link>

                    {{-- 2. MENU ADMINISTRATOR ONLY --}}
                    @if (Auth::user()->role == 'Administrator')
                        {{-- Manajemen Data Tamu (CRUD) --}}
                        <x-nav-link :href="route('tamu.index')" :active="request()->routeIs('tamu.index')">
                            {{ __('Manajemen Tamu') }}
                        </x-nav-link>
                        {{-- Manajemen Acara (CRUD) --}}
                        <x-nav-link :href="route('acara.index')" :active="request()->routeIs('acara.index')">
                            {{ __('Manajemen Acara') }}
                        </x-nav-link>
                    @endif

                    {{-- 3. MENU PETUGAS ONLY --}}
                    @if (Auth::user()->role == 'Petugas')
                        {{-- Scan QR Code --}}
                        <x-nav-link :href="route('scan')" :active="request()->routeIs('scan')">
                            {{ __('Scan QR Code') }}
                        </x-nav-link>
                    @endif

                    {{-- 4. LAPORAN (Admin & Petugas) --}}
                    @if (Auth::user()->role == 'Administrator' || Auth::user()->role == 'Petugas')
                        <x-nav-link :href="route('laporan')" :active="request()->routeIs('laporan')">
                            {{ __('Laporan Kehadiran') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            {{-- UBAH: Pakai username & tampilkan role --}}
                            <div>{{ Auth::user()->username }} ({{ Auth::user()->role }})</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Bagian responsive di bawah juga perlu dicek/diubah --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard Statistik') }}
            </x-responsive-nav-link>

            {{-- 2. MENU ADMINISTRATOR ONLY (RESPONSIVE) --}}
            @if (Auth::user()->role == 'Administrator')
                <x-responsive-nav-link :href="route('tamu.index')" :active="request()->routeIs('tamu.index')">
                    {{ __('Manajemen Tamu') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('acara.index')" :active="request()->routeIs('acara.index')">
                    {{ __('Manajemen Acara') }}
                </x-responsive-nav-link>
            @endif

            {{-- 3. MENU PETUGAS ONLY (RESPONSIVE) --}}
            @if (Auth::user()->role == 'Petugas')
                <x-responsive-nav-link :href="route('scan')" :active="request()->routeIs('scan')">
                    {{ __('Scan QR Code') }}
                </x-responsive-nav-link>
            @endif

            {{-- 4. LAPORAN (Admin & Petugas) (RESPONSIVE) --}}
            @if (Auth::user()->role == 'Administrator' || Auth::user()->role == 'Petugas')
                <x-responsive-nav-link :href="route('laporan')" :active="request()->routeIs('laporan')">
                    {{ __('Laporan Kehadiran') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                {{-- UBAH: Pakai username & role, hapus email --}}
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->username }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->role }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
