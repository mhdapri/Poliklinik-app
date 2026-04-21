<x-layouts.app title="Upload Bukti Bayar">
    <div class="p-6 max-w-lg mx-auto">
        <h2 class="text-2xl font-bold mb-6">Unggah Bukti Pembayaran</h2>

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <div class="mb-6 pb-6 border-b border-dashed">
                <div class="flex justify-between items-center text-sm mb-2">
                    <span class="text-gray-500">Total Tagihan:</span>
                    <span class="font-black text-xl text-blue-600">Rp {{ number_format($pembayaran->biaya_periksa, 0, ',', '.') }}</span>
                </div>
                <p class="text-[10px] text-gray-400">Pemeriksaan di {{ $pembayaran->pendaftaranPoli->jadwalPeriksa->poli->nama_poli }} [cite: 101]</p>
            </div>

            <form action="{{ route('pasien.pembayaran.upload', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Foto Bukti Transfer (.jpg/png) <span class="text-red-500">*</span></label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:bg-gray-50 transition relative">
                        <input type="file" name="bukti_bayar" id="bukti_bayar" class="absolute inset-0 opacity-0 cursor-pointer" required accept="image/*">
                        <div id="preview-container">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 mb-2"></i>
                            <p class="text-xs text-gray-500">Klik atau seret file ke sini untuk mengunggah</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                    Kirim untuk Verifikasi Admin
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>