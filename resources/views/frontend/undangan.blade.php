<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Pernikahan - {{ $tamu->nama_tamu }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Poppins:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body class="bg-stone-100 text-stone-800">

    <div class="min-h-screen flex flex-col items-center justify-center text-center p-6 bg-white shadow-inner">
        <p class="uppercase tracking-widest text-sm mb-4">The Wedding of</p>
        <h1 class="font-serif text-5xl md:text-7xl text-amber-700 mb-6">{{ $tamu->acara->nama_mempelai }}</h1>
        <div class="mb-8">
            <p class="text-lg">Kepada Yth. Bapak/Ibu/Saudara/i</p>
            <h2 class="text-2xl font-bold mt-2">{{ $tamu->nama_tamu }}</h2>
        </div>
        <p class="max-w-md italic text-stone-500 mb-10">"Tanpa mengurangi rasa hormat, kami mengundang
            Bapak/Ibu/Saudara/i untuk hadir di hari bahagia kami."</p>
        <a href="#acara" class="bg-amber-700 text-white px-8 py-3 rounded-full shadow-lg">Buka Undangan</a>
    </div>

    <div id="acara" class="py-20 px-6 max-w-2xl mx-auto text-center">
        <h2 class="font-serif text-3xl mb-10 border-b-2 border-amber-200 pb-4">Waktu & Lokasi</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <h3 class="font-bold uppercase text-amber-700 mb-2">Akad & Resepsi</h3>
                <p>{{ \Carbon\Carbon::parse($tamu->acara->waktu_acara)->format('d F Y') }}</p>
                <p>{{ \Carbon\Carbon::parse($tamu->acara->waktu_acara)->format('H:i') }} WIB - Selesai</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <h3 class="font-bold uppercase text-amber-700 mb-2">Lokasi</h3>
                <p>{{ $tamu->acara->lokasi }}</p>
                <p class="text-sm text-stone-500 mt-2">{{ $tamu->acara->deskripsi }}</p>
            </div>
        </div>

        <div class="bg-amber-50 p-8 rounded-3xl border-2 border-dashed border-amber-300 mb-20">
            <h3 class="font-bold mb-4 uppercase tracking-widest text-amber-900">Tiket Masuk Digital</h3>

            {{-- Area yang akan di-capture --}}
            <div id="qr-area" class="bg-white p-6 rounded-2xl shadow-sm inline-block mx-auto mb-4">
                <div class="mb-2 text-center">
                    <p class="text-[10px] font-bold text-amber-700 uppercase leading-none">
                        {{ $tamu->acara->nama_mempelai }}</p>
                    <p class="text-[8px] text-gray-400">Digital Invitation</p>
                </div>

                {!! QrCode::size(200)->format('svg')->generate($tamu->kode_unik) !!}

                <div class="mt-2 text-center">
                    <p class="font-bold text-sm text-stone-800">{{ $tamu->nama_tamu }}</p>
                    <p class="text-[10px] text-stone-400 font-mono">{{ $tamu->kode_unik }}</p>
                </div>
            </div>

            <div class="flex flex-col gap-3 items-center">
                <p class="text-xs text-stone-500 uppercase tracking-widest">Tunjukkan QR ini ke Petugas di lokasi acara
                </p>

                {{-- Tombol Download --}}
                <button onclick="downloadQR()"
                    class="bg-amber-100 text-amber-800 text-[10px] font-bold px-4 py-2 rounded-full border border-amber-300 hover:bg-amber-200 transition">
                    📥 Simpan Tiket ke Galeri (JPG)
                </button>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-xl text-left">
            <h3 class="font-serif text-2xl mb-6 text-center">Konfirmasi Kehadiran & Doa</h3>
            <form action="{{ route('rsvp.submit', $tamu->id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Status Kehadiran</label>
                        <select name="status_kehadiran" class="w-full rounded-xl border-stone-200 focus:ring-amber-500"
                            required>
                            <option value="Hadir">Saya Akan Hadir</option>
                            <option value="Tidak Hadir">Maaf, Tidak Bisa Hadir</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Jumlah Orang</label>
                        <input type="number" name="jumlah_orang" value="1" min="1" max="5"
                            class="w-full rounded-xl border-stone-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Ucapan & Doa (Wishes)</label>
                        <textarea name="ucapan_doa" rows="3" class="w-full rounded-xl border-stone-200"
                            placeholder="Tulis ucapan manis lo di sini..."></textarea>
                    </div>
                    <button type="submit"
                        class="w-full bg-amber-800 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-amber-900 transition">
                        Kirim Konfirmasi
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-20">
            <h3 class="font-serif text-2xl mb-8">Wishes dari Tamu Lain</h3>
            <div class="space-y-4 text-left">
                @foreach ($wishes as $wish)
                    <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-amber-500">
                        <p class="font-bold text-sm">{{ $wish->tamu->nama_tamu }}</p>
                        <p class="text-stone-600 text-sm mt-1">"{{ $wish->ucapan_doa }}"</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <footer class="py-10 text-center text-stone-400 text-xs">
        &copy; 2026 {{ $tamu->acara->nama_mempelai }} - Undangan Digital by Makani
    </footer>

    {{-- Library html2canvas --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function downloadQR() {
            const qrArea = document.getElementById('qr-area');

            // Kita kasih sedikit style loading biar user gak bingung
            const btn = event.target;
            const originalText = btn.innerText;
            btn.innerText = "⏳ Sedang Menyimpan...";
            btn.disabled = true;

            html2canvas(qrArea, {
                scale: 3, // Skala tinggi biar gambarnya HD pas di-zoom petugas
                backgroundColor: "#ffffff",
                logging: false,
                useCORS: true
            }).then(canvas => {
                const link = document.createElement('a');
                // Nama file pake nama tamu biar gampang nyarinya di folder download
                link.download = 'Tiket-{{ Str::slug($tamu->nama_tamu) }}.jpg';
                link.href = canvas.toDataURL('image/jpeg', 0.9);
                link.click();

                // Balikin tombol ke semula
                btn.innerText = originalText;
                btn.disabled = false;
            }).catch(err => {
                console.error("Gagal download gambar:", err);
                btn.innerText = "❌ Gagal Download";
                btn.disabled = false;
            });
        }
    </script>
</body>

</html>
