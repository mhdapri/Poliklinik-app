<x-layouts.app title="Pembayaran">
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4">💳 Pembayaran & Tagihan</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($pendingPayments as $p)
                <div class="bg-white border-2 border-dashed border-gray-200 rounded-2xl p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-[10px] font-bold uppercase text-gray-400">Tagihan Untuk</p>
                            <p class="font-bold">{{ $p->pendaftaranPoli->jadwalPeriksa->poli->nama_poli }}</p>
                        </div>
                        <p class="text-xl font-black text-blue-600">Rp {{ number_format($p->biaya_periksa, 0, ',', '.') }}</p>
                    </div>

                    {{-- ALUR UPLOAD BUKTI - POIN 6 [cite: 101] --}}
                    <form action="{{ route('pasien.pembayaran.upload', $p->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs font-bold mb-1 italic">Unggah Bukti Transfer (.jpg/.png) [cite: 101]</label>
                            <input type="file" name="bukti_bayar" class="w-full text-xs border rounded-lg p-2" required>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-xl text-sm font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition">Kirim Bukti Pembayaran</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>