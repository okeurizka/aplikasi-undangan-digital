<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Data Tamu</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('tamu.update', $tamu->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <x-input-label for="nama_tamu" :value="__('Nama Tamu')" />
                            <x-text-input id="nama_tamu" name="nama_tamu" type="text" class="block mt-1 w-full"
                                :value="old('nama_tamu', $tamu->nama_tamu)" required />
                        </div>

                        <div>
                            <x-input-label for="alamat" :value="__('Alamat')" />
                            <x-text-input id="alamat" name="alamat" type="text" class="block mt-1 w-full"
                                :value="old('alamat', $tamu->alamat)" />
                        </div>

                        <div>
                            <x-input-label for="status_undangan" :value="__('Status Undangan')" />
                            <select name="status_undangan" id="status_undangan"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="Diundang" {{ $tamu->status_undangan == 'Diundang' ? 'selected' : '' }}>
                                    Diundang</option>
                                <option value="RSVP Confirmed"
                                    {{ $tamu->status_undangan == 'RSVP Confirmed' ? 'selected' : '' }}>RSVP Confirmed
                                </option>
                                <option value="RSVP Declined"
                                    {{ $tamu->status_undangan == 'RSVP Declined' ? 'selected' : '' }}>RSVP Declined
                                </option>
                                <option value="Canceled" {{ $tamu->status_undangan == 'Canceled' ? 'selected' : '' }}>
                                    Canceled</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="acara_id" :value="__('Pindah ke Acara Lain?')" />
                            <select name="acara_id" id="acara_id"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                @foreach ($acaras as $acara)
                                    <option value="{{ $acara->id }}"
                                        {{ $tamu->acara_id == $acara->id ? 'selected' : '' }}>
                                        {{ $acara->nama_mempelai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('tamu.index') }}"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md">Batal</a>
                            <x-primary-button>Simpan Perubahan</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
