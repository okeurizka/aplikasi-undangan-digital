<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Tamu Undangan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('tamu.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <x-input-label for="acara_id" :value="__('Pilih Acara')" />
                            <select name="acara_id" id="acara_id"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                @foreach ($acaras as $acara)
                                    <option value="{{ $acara->id }}">{{ $acara->nama_mempelai }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="nama_tamu" :value="__('Nama Tamu')" />
                            <x-text-input id="nama_tamu" class="block mt-1 w-full" type="text" name="nama_tamu"
                                required />
                        </div>

                        <div>
                            <x-input-label for="alamat" :value="__('Alamat')" />
                            <x-text-input id="alamat" class="block mt-1 w-full" type="text" name="alamat" />
                        </div>

                        <input type="hidden" name="status_undangan" value="Diundang">

                        <div class="flex justify-end">
                            <x-primary-button>Simpan Data Tamu</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
