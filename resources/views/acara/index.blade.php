<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Acara</h2>
            <a href="{{ route('acara.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-bold shadow-sm hover:bg-indigo-700 transition">
                + Tambah Acara
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- SEARCH BAR --}}
            <div class="bg-white p-6 rounded-t-lg shadow-sm border-b">
                <form action="{{ route('acara.index') }}" method="GET" class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama mempelai atau lokasi..."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <button type="submit"
                        class="bg-gray-800 text-white px-6 py-2 rounded-md hover:bg-black transition">
                        Cari
                    </button>
                    @if (request('search'))
                        <a href="{{ route('acara.index') }}"
                            class="text-red-500 text-sm flex items-center hover:underline">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-b-lg p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50 uppercase text-gray-600 text-xs tracking-wider">
                            <th class="py-3 px-4">Nama Mempelai</th>
                            <th class="py-3 px-4">Waktu</th>
                            <th class="py-3 px-4">Lokasi</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($acaras as $a)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="py-4 px-4 font-bold text-gray-800">{{ $a->nama_mempelai }}</td>
                                <td class="py-4 px-4 text-gray-600">
                                    {{ $a->waktu_acara->format('d M Y') }}
                                    <span class="block text-[10px] text-gray-400">Jam
                                        {{ $a->waktu_acara->format('H:i') }}</span>
                                </td>
                                <td class="py-4 px-4 text-gray-600">{{ $a->lokasi }}</td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex justify-center gap-3">

                                        <a href="{{ route('acara.show', $a->id) }}"
                                            class="text-green-600 font-bold hover:underline">Detail</a>

                                        <a href="{{ route('acara.edit', $a->id) }}"
                                            class="text-indigo-600 font-bold hover:underline">Edit</a>

                                        <form action="{{ route('acara.destroy', $a->id) }}" method="POST"
                                            class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 font-bold hover:underline"
                                                onclick="return confirm('Menghapus acara akan berisiko menghapus data tamu terkait. Yakin?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-gray-400 italic">
                                    Belum ada data acara yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- PAGINATION LINKS --}}
                <div class="mt-6">
                    {{ $acaras->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
