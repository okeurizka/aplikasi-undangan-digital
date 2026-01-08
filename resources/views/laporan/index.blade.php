<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Kehadiran Tamu
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <form action="{{ route('laporan') }}" method="GET" class="flex items-end gap-4">
                    <div class="flex-1">
                        <x-input-label for="acara_id" value="Pilih Acara" />
                        <select name="acara_id" id="acara_id"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            @foreach ($acaras as $acara)
                                <option value="{{ $acara->id }}"
                                    {{ $selectedAcaraId == $acara->id ? 'selected' : '' }}>
                                    {{ $acara->nama_mempelai }}
                                    ({{ \Carbon\Carbon::parse($acara->waktu_acara)->format('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <x-primary-button>Filter</x-primary-button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <p class="text-sm text-blue-600 font-bold uppercase">Total Undangan</p>
                    <p class="text-2xl font-black">{{ $rekap['total_tamu'] }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <p class="text-sm text-green-600 font-bold uppercase">RSVP Hadir</p>
                    <p class="text-2xl font-black">{{ $rekap['rsvp_hadir'] }}</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <p class="text-sm text-purple-600 font-bold uppercase">Sudah Check-in</p>
                    <p class="text-2xl font-black">{{ $rekap['tamu_checkin'] }}</p>
                </div>
                <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                    <p class="text-sm text-amber-600 font-bold uppercase">Kehadiran (%)</p>
                    <p class="text-2xl font-black">{{ $rekap['persentase_checkin'] }}%</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 uppercase text-gray-600">
                            <tr>
                                <th class="p-3">Nama Tamu</th>
                                <th class="p-3">Status RSVP</th>
                                <th class="p-3">Waktu Check-in</th>
                                <th class="p-3">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tamus as $tamu)
                                <tr class="hover:bg-gray-50">
                                    {{-- 1. NAMA TAMU --}}
                                    <td class="p-3 font-medium">{{ $tamu->nama_tamu }}</td>

                                    {{-- 2. STATUS RSVP (Nangkep data dari tabel rsvp) --}}
                                    <td class="p-3">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs {{ optional($tamu->rsvp)->status_kehadiran == 'Hadir' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ optional($tamu->rsvp)->status_kehadiran ?? 'Belum Respon' }}
                                        </span>
                                    </td>

                                    {{-- 3. WAKTU CHECK-IN (Data dari log_checkin kolom waktu_scan) --}}
                                    <td class="p-3 text-gray-500 font-mono text-xs">
                                        @if ($tamu->logCheckins && $tamu->logCheckins->isNotEmpty())
                                            {{ $tamu->logCheckins->first()->waktu_scan->format('H:i:s') }}
                                            <span
                                                class="text-[10px] block text-gray-400">{{ $tamu->logCheckins->first()->waktu_scan->format('d M Y') }}</span>
                                        @else
                                            <span class="text-red-400 text-xs">Belum Datang</span>
                                        @endif
                                    </td>

                                    {{-- 4. STATUS KEHADIRAN (Badge Scan) --}}
                                    <td class="p-3">
                                        @if ($tamu->logCheckins && $tamu->logCheckins->isNotEmpty())
                                            <span
                                                class="text-green-600 font-bold text-xs bg-green-50 px-2 py-1 rounded border border-green-200">
                                                ✓ HADIR DI LOKASI
                                            </span>
                                        @else
                                            <span class="text-red-400 text-xs italic">Belum Datang</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-10 text-center text-gray-400 italic">Belum ada data tamu
                                        untuk acara ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $tamus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
