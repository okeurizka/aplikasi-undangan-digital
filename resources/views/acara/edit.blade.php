<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Data Acara</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('acara.update', $acara->id) }}" method="POST">
                    @csrf
                    @method('PUT') <div class="grid grid-cols-1 gap-6">
                        <div>
                            <x-input-label for="nama_mempelai" :value="__('Nama Mempelai')" />
                            <x-text-input id="nama_mempelai" name="nama_mempelai" type="text"
                                class="block mt-1 w-full" :value="old('nama_mempelai', $acara->nama_mempelai)" required />
                            <x-input-error :messages="$errors->get('nama_mempelai')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="waktu_acara" :value="__('Waktu Acara')" />
                            <x-text-input id="waktu_acara" name="waktu_acara" type="datetime-local"
                                class="block mt-1 w-full" :value="old('waktu_acara', date('Y-m-d\TH:i', strtotime($acara->waktu_acara)))" required />
                        </div>

                        <div>
                            <x-input-label for="lokasi" :value="__('Lokasi')" />
                            <x-text-input id="lokasi" name="lokasi" type="text" class="block mt-1 w-full"
                                :value="old('lokasi', $acara->lokasi)" required />
                        </div>

                        <div>
                            <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                            <textarea id="deskripsi" name="deskripsi" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('deskripsi', $acara->deskripsi) }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('acara.index') }}"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md">Batal</a>
                            <x-primary-button>Update Acara</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
