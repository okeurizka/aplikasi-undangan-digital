<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Petugas Check-in') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm text-gray-500 font-bold uppercase">Target Tamu</div>
                    <div class="text-3xl font-bold">{{ $totalTamu }}</div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-sm text-gray-500 font-bold uppercase">RSVP Hadir</div>
                    <div class="text-3xl font-bold">{{ $totalRsvpConfirmed }}</div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-sm text-gray-500 font-bold uppercase">Sudah Check-in</div>
                    <div class="text-3xl font-bold">{{ $totalCheckin }}</div>
                </div>
            </div>

            <div class="bg-indigo-600 overflow-hidden shadow-sm sm:rounded-lg p-8 text-white text-center">
                <h3 class="text-2xl font-bold mb-4">Siap Scan QR Code Tamu?</h3>
                <p class="mb-6 opacity-90">Pastiin kamera lo aktif buat mulai validasi undangan tamu.</p>
                <a href="{{ route('scan') }}"
                    class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-bold hover:bg-gray-100 transition">
                    Mulai Scan Sekarang
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
