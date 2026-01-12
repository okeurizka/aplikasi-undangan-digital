<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Tamu Undangan</h2>
            <a href="{{ route('tamu.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-bold">Tambah Tamu</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="bg-white mb-4 rounded-t-lg shadow-sm border-b">
                    <form action="{{ route('tamu.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama tamu..."
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="w-full md:w-64">
                            <select name="acara_id"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Semua Acara --</option>
                                @foreach ($acaras as $acara)
                                    <option value="{{ $acara->id }}"
                                        {{ request('acara_id') == $acara->id ? 'selected' : '' }}>
                                        {{ $acara->nama_mempelai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                            class="bg-gray-800 text-white px-6 py-2 rounded-md hover:bg-black transition">
                            Cari
                        </button>
                        @if (request('search') || request('acara_id'))
                            <a href="{{ route('tamu.index') }}"
                                class="text-red-500 text-sm flex items-center hover:underline">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b text-gray-600 text-sm">
                            <th class="py-3 px-2">Nama Tamu</th>
                            <th class="py-3 px-2">Status Undangan</th>
                            <th class="py-3 px-2">Link Undangan</th>
                            <th class="py-3 px-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($tamus as $t)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-4 px-2 italic font-bold text-gray-800">{{ $t->nama_tamu }}</td>
                                <td class="py-4 px-2">
                                    <span
                                        class="px-2 py-1 rounded text-xs {{ $t->status_undangan == 'Diundang' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $t->status_undangan }}
                                    </span>
                                </td>
                                <td class="py-4 px-2 text-blue-500 truncate max-w-xs">
                                    <a href="{{ route('undangan.show', $t->kode_unik) }}" target="_blank"
                                        class="hover:underline">
                                        {{ route('undangan.show', $t->kode_unik) }}
                                    </a>
                                </td>
                                <td class="py-4 px-2 text-center">
                                    <a href="{{ route('tamu.generateQr', $t->id) }}"
                                        class="bg-gray-100 p-2 rounded hover:bg-gray-200" title="Cetak QR">
                                        📷 QR
                                    </a>
                                    <a href="{{ route('tamu.edit', $t->id) }}"
                                        class="ml-2 text-blue-600 font-bold">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $tamus->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
