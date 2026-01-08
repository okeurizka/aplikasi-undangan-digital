<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Acara</h2>
            <a href="{{ route('acara.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-bold">Tambah Acara</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-3 px-4">Nama Mempelai</th>
                            <th class="py-3 px-4">Waktu</th>
                            <th class="py-3 px-4">Lokasi</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($acaras as $a)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 font-medium">{{ $a->nama_mempelai }}</td>
                                <td class="py-3 px-4 text-sm">{{ $a->waktu_acara->format('d M Y, H:i') }}</td>
                                <td class="py-3 px-4 text-sm">{{ $a->lokasi }}</td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('acara.edit', $a->id) }}"
                                        class="text-blue-600 hover:underline mr-2">Edit</a>
                                    <form action="{{ route('acara.destroy', $a->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline"
                                            onclick="return confirm('Yakin hapus acara ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
