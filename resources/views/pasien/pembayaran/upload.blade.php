<x-layouts.app>
<div class="container py-4 max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="bg-blue-600 p-8 text-white">
            <h3 class="text-2xl font-black">Upload Bukti Pembayaran</h3>
            <p class="opacity-80">Silakan selesaikan pembayaran untuk pendaftaran #{{ $daftarPoli->id }}</p>
        </div>

        <div class="p-8 grid md:grid-cols-2 gap-10">
            <div>
                <h4 class="font-bold text-gray-800 mb-4 border-b pb-2">Rincian Layanan</h4>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Jasa Dokter</span>
                        <span class="font-bold text-gray-700">Rp 150.000</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Obat</span>
                        <span class="font-bold text-gray-700">
                            Rp {{ number_format($biayaTotal - 150000, 0, ',', '.') }}
                        </span>
                    </div>
                    <hr>
                    <div class="flex justify-between text-lg">
                        <span class="font-black text-gray-800">Total Tagihan</span>
                        <span class="font-black text-blue-600">Rp {{ number_format($biayaTotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-8 p-4 bg-blue-50 rounded-2xl">
                    <p class="text-xs text-blue-700 font-bold mb-2">Metode Transfer:</p>
                    <p class="text-sm text-blue-900 font-black">BCA: 123-456-7890 (A/N Poliklinik-BK)</p>
                </div>
            </div>

            <form action="{{ route('pasien.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_daftar_poli" value="{{ $daftarPoli->id }}">
                <input type="hidden" name="biaya_total" value="{{ $biayaTotal }}">

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Foto Bukti Transfer</label>
                    <input type="file" name="bukti_pembayaran" required
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                    <p class="text-[10px] text-gray-400 mt-2">* Format: JPG/PNG, Maksimal 2MB</p>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition transform hover:-translate-y-1">
                    Konfirmasi Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>
</x-layouts.app>