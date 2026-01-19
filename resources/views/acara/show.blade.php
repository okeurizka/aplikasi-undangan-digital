<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Acara: {{ $acara->nama_mempelai }}
            </h2>
            <a href="{{ route('acara.index') }}" class="text-sm text-gray-600 hover:underline">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- GRID ATAS: INFO & STATS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- Kartu Info Utama --}}
                <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-sm border">
                    <h3 class="text-lg font-bold text-indigo-600 border-b pb-2 mb-4">Informasi Acara</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 uppercase text-[10px] font-bold">Waktu Pelaksanaan</p>
                            <p class="font-medium text-gray-800">{{ $acara->waktu_acara->format('d F Y') }}</p>
                            <p class="text-gray-400 italic">Pukul {{ $acara->waktu_acara->format('H:i') }} WIB</p>
                        </div>
                        <div>
                            <p class="text-gray-500 uppercase text-[10px] font-bold">Lokasi</p>
                            <p class="font-medium text-gray-800">{{ $acara->lokasi ?? 'Lokasi belum diatur' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Kartu Statistik Cepat (Sudah disamain warnanya) --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border flex flex-col justify-between">
                    <div>
                        <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-widest">Progress Kehadiran
                        </h3>
                        <div class="flex items-baseline gap-1 mt-2">
                            <p class="text-4xl font-black text-indigo-600">{{ $stats['hadir'] }}</p>
                            <p class="text-gray-400 font-bold">/ {{ $stats['total_tamu'] }}</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex justify-between text-[10px] font-bold mb-1">
                            <span class="text-gray-400 uppercase tracking-tighter">Check-in Rate</span>
                            <span
                                class="text-indigo-600">{{ $stats['total_tamu'] > 0 ? round(($stats['hadir'] / $stats['total_tamu']) * 100) : 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2 rounded-full">
                            <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500"
                                style="width: {{ $stats['total_tamu'] > 0 ? ($stats['hadir'] / $stats['total_tamu']) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORM TAMBAH TAMU CEPAT --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border mb-6">
                <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider italic">Tambah Tamu Baru</h3>
                <form action="{{ route('tamu.store') }}" method="POST" class="flex flex-col md:flex-row gap-4">
                    @csrf
                    {{-- Hidden input buat ngiket tamu ke acara ini --}}
                    <input type="hidden" name="acara_id" value="{{ $acara->id }}">
                    <input type="hidden" name="status_undangan" value="Diundang">

                    <div class="flex-1">
                        <x-text-input name="nama_tamu" placeholder="Nama Lengkap Tamu..." class="w-full" required />
                    </div>
                    <div class="flex-1">
                        <x-text-input name="alamat" placeholder="Alamat/Instansi (Opsional)" class="w-full" />
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-md font-bold hover:bg-indigo-700 transition">
                        + Simpan Tamu
                    </button>
                </form>
            </div>

            {{-- TABEL TAMU --}}
            {{-- TABEL TAMU --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden border">
                <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                    <h3 class="font-bold text-gray-700 uppercase text-xs">Daftar Undangan</h3>
                    <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full font-bold">
                        {{ $stats['total_tamu'] }} Orang
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b">
                            <tr>
                                <th class="p-4">Nama Tamu</th>
                                <th class="p-4">Link Undangan</th> {{-- Kolom Baru --}}
                                <th class="p-4 text-center">RSVP</th>
                                <th class="p-4 text-center">Hadir</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($acara->tamu as $tamu)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4">
                                        <div class="font-bold text-gray-800">{{ $tamu->nama_tamu }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase">
                                            {{ $tamu->alamat ?? 'Tanpa Alamat' }}</div>
                                        <div class="text-[10px] text-gray-400 mt-1">
                                            {{ optional($tamu->rsvp)->jumlah_orang }}
                                        </div>
                                    </td>

                                    {{-- GENERATE LINK UNDANGAN --}}
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('undangan.show', $tamu->kode_unik) }}" target="_blank"
                                                class="text-blue-500 hover:text-blue-700 text-xs font-mono truncate max-w-[200px] block">
                                                {{ route('undangan.show', $tamu->kode_unik) }}
                                            </a>
                                            {{-- Icon copy kecil buat gaya-gayaan (opsional) --}}
                                            <button
                                                onclick="navigator.clipboard.writeText('{{ route('undangan.show', $tamu->kode_unik) }}')"
                                                class="text-gray-400 hover:text-indigo-600" title="Salin Link">
                                                📋
                                            </button>
                                        </div>
                                    </td>

                                    <td class="p-4 text-center">
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-bold {{ optional($tamu->rsvp)->status_kehadiran == 'Hadir' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ optional($tamu->rsvp)->status_kehadiran ?? 'Belum Respon' }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        @if ($tamu->logCheckins && $tamu->logCheckins->isNotEmpty())
                                            <span
                                                class="text-green-600 font-bold text-xs bg-green-50 px-2 py-1 rounded border border-green-200">
                                                ✓ HADIR DI LOKASI
                                            </span>
                                        @else
                                            <span class="text-red-400 text-xs italic">Belum Datang</span>
                                        @endif
                                        <br>
                                        @if ($tamu->logCheckins && $tamu->logCheckins->isNotEmpty())
                                            {{ $tamu->logCheckins->first()->waktu_scan->format('H:i:s') }}
                                            <span
                                                class="text-[10px] block text-gray-400">{{ $tamu->logCheckins->first()->waktu_scan->format('d M Y') }}</span>
                                        @else
                                        @endif
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex justify-end gap-3 items-center">
                                            <a href="{{ route('tamu.generateQr', $tamu->id) }}"
                                                class="text-gray-400 hover:text-gray-600 transition" title="Cetak QR">
                                                📷 <span class="text-[10px] font-bold">QR</span>
                                            </a>
                                            <a href="{{ route('tamu.edit', $tamu->id) }}"
                                                class="text-blue-500 hover:text-blue-700 font-bold text-xs">Edit</a>
                                            <form action="{{ route('tamu.destroy', $tamu->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus tamu ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-400 hover:text-red-600 font-bold text-xs">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-10 text-center text-gray-400 italic font-mono">
                                        Data tamu masih kosong. Silahkan tambah di atas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
