<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center italic">
            ID Card & QR Code Undangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto bg-white p-8 border shadow-lg text-center rounded-xl">
            <h1 class="text-2xl font-bold mb-2">{{ $tamu->nama_tamu }}</h1>
            <p class="text-gray-500 mb-6 italic">{{ $tamu->acara->nama_mempelai }}</p>

            <div class="flex justify-center mb-6">
                {!! QrCode::size(250)->generate($tamu->kode_unik) !!}
            </div>

            <p class="text-xs text-gray-400 font-mono mb-6">{{ $tamu->kode_unik }}</p>

            <button onclick="window.print()" class="bg-black text-white px-6 py-2 rounded-full no-print">
                Cetak QR Code
            </button>
        </div>
    </div>

    <style>
        @media print {

            .no-print,
            nav {
                display: none !important;
            }

            body {
                background: white;
            }
        }
    </style>
</x-app-layout>
