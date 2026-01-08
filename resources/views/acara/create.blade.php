<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Acara Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('acara.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <x-input-label for="nama_mempelai" :value="__('Nama Mempelai')" />
                            <x-text-input id="nama_mempelai" class="block mt-1 w-full" type="text"
                                name="nama_mempelai" placeholder="Contoh: Abdul & Sayu" required autofocus />
                            <x-input-error :messages="$errors->get('nama_mempelai')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="waktu_acara" :value="__('Waktu & Tanggal Acara')" />
                            <x-text-input id="waktu_acara" class="block mt-1 w-full" type="datetime-local"
                                name="waktu_acara" required />
                            <x-input-error :messages="$errors->get('waktu_acara')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="lokasi" :value="__('Lokasi Acara')" />
                            <x-text-input id="lokasi" class="block mt-1 w-full" type="text" name="lokasi"
                                placeholder="Nama Gedung / Alamat Lengkap" required />
                            <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="deskripsi" :value="__('Deskripsi Singkat')" />
                            <textarea id="deskripsi" name="deskripsi" rows="3"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                placeholder="Contoh: Mohon doa restu pada acara pernikahan kami..."></textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-2 mt-4">
                            <a href="{{ route('acara.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <x-primary-button>
                                Simpan Acara
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
