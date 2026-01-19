<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center italic">
            ID Card & QR Code Undangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto text-center">

            {{-- Bagian yang bakal di-capture jadi Gambar --}}
            <div id="id-card" class="bg-white p-8 border shadow-lg rounded-xl mb-6">
                <h1 class="text-2xl font-bold mb-2">{{ $tamu->nama_tamu }}</h1>
                <p class="text-gray-500 mb-6 italic">{{ $tamu->acara->nama_mempelai }}</p>

                <div class="flex justify-center mb-6 p-4 bg-white inline-block">
                    {!! QrCode::size(250)->format('svg')->generate($tamu->kode_unik) !!}
                </div>

                <p class="text-xs text-gray-400 font-mono">{{ $tamu->kode_unik }}</p>
            </div>

            {{-- Tombol-tombol --}}
            <div class="flex justify-center gap-4 no-print">
                <button onclick="downloadQR()"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-full font-bold shadow-md hover:bg-indigo-700 transition">
                    Download JPG
                </button>
                <button onclick="window.print()"
                    class="bg-gray-800 text-white px-6 py-2 rounded-full font-bold shadow-md hover:bg-black transition">
                    Cetak Printer
                </button>
            </div>

            <a href="{{ route('acara.show', $tamu->acara_id) }}"
                class="block mt-6 text-sm text-gray-500 hover:underline no-print">
                ← Kembali ke Detail Acara
            </a>
        </div>
    </div>

    {{-- Script html2canvas --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function downloadQR() {
            const card = document.getElementById('id-card');

            // Sembunyikan bayangan/border saat di-capture biar bersih (opsional)
            html2canvas(card, {
                scale: 2, // Biar gambar tajam (HD)
                useCORS: true,
                backgroundColor: "#ffffff"
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'QR-{{ Str::slug($tamu->nama_tamu) }}.jpg';
                link.href = canvas.toDataURL('image/jpeg', 0.9); // Format JPG kualitas 90%
                link.click();
            });
        }
    </script>

    <style>
        @media print {

            .no-print,
            nav {
                display: none !important;
            }

            body {
                background: white;
            }

            .py-12 {
                padding: 0 !important;
            }
        }
    </style>
</x-app-layout>
