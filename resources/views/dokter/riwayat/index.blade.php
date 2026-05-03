<x-layouts.app>
    <div class="container-fluid py-4">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Riwayat Pasien</h2>
                <p class="text-gray-500 text-sm">Daftar pasien yang pernah Anda periksa.</p>
            </div>
            
            {{-- Tombol Export Excel sesuai permintaan soal --}}
            <a href="{{ route('riwayat-pasien.export') }}" 
               class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 transition">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Tanggal Periksa</th>
                        <th class="px-6 py-4">Nama Pasien</th>
                        <th class="px-6 py-4">Keluhan</th>
                        <th class="px-6 py-4">Catatan Dokter</th>
                        <th class="px-6 py-4">Biaya</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayat as $index => $item)
                        <tr class="hover:bg-gray-50 transition text-sm">
                            <td class="px-6 py-4 text-gray-500">
                                {{ $riwayat->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 font-medium">
                                {{ \Carbon\Carbon::parse($item->periksa->tgl_periksa)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-800">{{ $item->pasien->nama }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 italic">
                                "{{ $item->keluhan }}"
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->periksa->catatan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-emerald-600">
                                Rp {{ number_format($item->periksa->biaya_periksa, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">
                                Belum ada riwayat pemeriksaan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $riwayat->links() }}
        </div>
    </div>
</x-layouts.app>