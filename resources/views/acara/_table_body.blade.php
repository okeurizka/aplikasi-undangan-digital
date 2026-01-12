@forelse($tamus as $tamu)
    <tr class="hover:bg-gray-50">
        <td class="p-3 font-medium">{{ $tamu->nama_tamu }}</td>
        <td class="p-3">
            <span
                class="px-2 py-1 rounded-full text-xs {{ optional($tamu->rsvp)->status_kehadiran == 'Hadir' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                {{ optional($tamu->rsvp)->status_kehadiran ?? 'Belum Respon' }}
            </span>
        </td>
        <td class="p-3 text-gray-500 font-mono text-xs">
            @if ($tamu->logCheckins && $tamu->logCheckins->isNotEmpty())
                {{ $tamu->logCheckins->first()->waktu_scan->format('H:i:s') }}
            @else
                -
            @endif
        </td>
        <td class="p-3">
            @if ($tamu->logCheckins && $tamu->logCheckins->isNotEmpty())
                <span class="text-green-600 font-bold text-xs bg-green-50 px-2 py-1 rounded border border-green-200">✓
                    HADIR</span>
            @else
                <span class="text-red-400 text-xs italic">Belum Datang</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="p-10 text-center text-gray-400 italic">Data tidak ditemukan.</td>
    </tr>
@endforelse
