<x-layouts.app>
    <div class="container-fluid py-4">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Riwayat Pasien</h2>
                <p class="text-gray-500 text-sm">Informasi lengkap pemeriksaan pasien</p>
            </div>
            <a href="{{ route('riwayat-pasien.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition">
                ← Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Detail Pasien -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Pasien</h3>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Nama Pasien</label>
                            <p class="text-gray-800 font-bold mt-1">{{ $riwayat->pasien->nama }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">No. Rekam Medis</label>
                            <p class="text-gray-800 font-bold mt-1">{{ $riwayat->pasien->no_rm ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Tanggal Periksa</label>
                            <p class="text-gray-800 font-bold mt-1">
                                {{ \Carbon\Carbon::parse($riwayat->periksa->tgl_periksa)->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">No. Antrian</label>
                            <p class="text-gray-800 font-bold mt-1">{{ $riwayat->no_antrian }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Keluhan</label>
                        <p class="text-gray-800 mt-1">{{ $riwayat->keluhan }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Catatan Dokter</label>
                        <p class="text-gray-800 mt-1 bg-gray-50 p-3 rounded">{{ $riwayat->periksa->catatan ?? '-' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Biaya Periksa</label>
                            <p class="text-lg font-bold text-emerald-600 mt-1">
                                Rp {{ number_format($riwayat->periksa->biaya_periksa, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Status</label>
                            <p class="text-gray-800 font-bold mt-1 capitalize">{{ $riwayat->status }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Obat -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Obat yang Diberikan</h3>
                
                @if($riwayat->periksa->detailPeriksas->count() > 0)
                    <div class="space-y-3">
                        @foreach($riwayat->periksa->detailPeriksas as $detail)
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="font-bold text-gray-800">{{ $detail->obat->nama_obat }}</p>
                                <p class="text-sm text-gray-600">{{ $detail->jumlah }} {{ $detail->obat->satuan }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 italic text-center py-8">Tidak ada obat yang diberikan</p>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
