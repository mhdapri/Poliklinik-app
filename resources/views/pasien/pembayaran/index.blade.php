<x-layouts.app>
<div class="container-fluid py-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Riwayat & Tagihan Pembayaran</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Poli & Dokter</th>
                    <th class="px-6 py-4">Total Biaya</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tagihan as $item)
               @php
                    $biayaJasa = 150000;
                    
                    // Gunakan nama relationship yang benar: periksa (hasOne)
                    $dataPeriksa = $item->periksa; 

                    $biayaObat = 0;
                    if ($dataPeriksa) {
                        // Akses detailPeriksas yang sudah di-eager load
                        $biayaObat = $dataPeriksa->detailPeriksas->sum(function($detail) {
                            return $detail->obat->harga ?? 0;
                        });
                    }

                    $total = $biayaJasa + $biayaObat;
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-gray-700">
                        {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-800">{{ $item->jadwalPeriksa->poli->nama_poli }}</div>
                        <div class="text-xs text-gray-500">{{ $item->jadwalPeriksa->dokter->nama }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm font-black text-blue-600">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        @if(!$item->pembayaran || $item->pembayaran->status == 'unpaid')
                            <span class="px-3 py-1 text-xs font-bold bg-red-100 text-red-600 rounded-full">Belum Bayar</span>
                        @elseif($item->pembayaran->status == 'pending_verification')
                            <span class="px-3 py-1 text-xs font-bold bg-amber-100 text-amber-600 rounded-full">Menunggu Verifikasi</span>
                        @elseif($item->pembayaran->status == 'verified')
                            <span class="px-3 py-1 text-xs font-bold bg-emerald-100 text-emerald-600 rounded-full">Lunas</span>
                        @else
                            <span class="px-3 py-1 text-xs font-bold bg-gray-100 text-gray-600 rounded-full">Ditolak</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if(!$item->pembayaran || $item->pembayaran->status == 'unpaid' || $item->pembayaran->status == 'rejected')
                            <a href="{{ route('pasien.pembayaran.show', $item->id) }}" 
                               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition">
                                Bayar Sekarang
                            </a>
                        @else
                            <button disabled class="bg-gray-100 text-gray-400 px-4 py-2 rounded-xl text-xs font-bold cursor-not-allowed">
                                Selesai
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Belum ada tagihan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $tagihan->links() }}
    </div>
</div>
</x-layouts.app>