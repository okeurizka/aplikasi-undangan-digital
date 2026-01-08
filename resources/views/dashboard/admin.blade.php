<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Administrator') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Total Acara</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalAcara }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Tamu Diundang</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalTamu }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Konfirmasi RSVP</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalRsvpConfirmed }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Tamu Check-in</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalCheckin }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <h3 class="text-lg font-medium">Halo, {{ Auth::user()->username }}! 👋</h3>
                <p class="mt-2 text-gray-600">Lo punya akses penuh buat kelola data acara, tamu, dan pantau laporan
                    kehadiran secara real-time.</p>
            </div>
        </div>
    </div>
</x-app-layout>
