<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            Scan QR Code Undangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div id="reader" class="w-full rounded-lg overflow-hidden border-4 border-indigo-100"></div>

                <div id="result" class="mt-6 text-center hidden">
                    <div id="status-box" class="p-4 rounded-lg font-bold text-lg mb-4"></div>
                    <button onclick="location.reload()" class="bg-indigo-600 text-white px-6 py-2 rounded-full">Scan
                        Lagi</button>
                </div>

                <div class="mt-4 text-center text-sm text-gray-500 italic">
                    Posisikan QR Code di dalam kotak untuk proses check-in otomatis.
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Berhenti scan sementara biar gak kirim request berkali-kali
            html5QrcodeScanner.clear();

            // Kirim data ke Controller pakai AJAX
            fetch("{{ route('checkin.process') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        kode_unik: decodedText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const box = document.getElementById('status-box');
                    const resDiv = document.getElementById('result');
                    const readerDiv = document.getElementById('reader');

                    readerDiv.classList.add('hidden');
                    resDiv.classList.remove('hidden');

                    if (data.success) {
                        box.className =
                            "p-4 rounded-lg font-bold text-lg mb-4 bg-green-100 text-green-700 border border-green-300";
                        box.innerText = data.message;
                    } else {
                        box.className =
                            "p-4 rounded-lg font-bold text-lg mb-4 bg-red-100 text-red-700 border border-red-300";
                        box.innerText = data.message;
                    }
                })
                .catch(err => {
                    alert("Error: Terjadi kesalahan koneksi!");
                    location.reload();
                });
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            }
        );
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</x-app-layout>
