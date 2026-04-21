<x-layouts.app title="Detail Pemeriksaan">
    <div class="p-6 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 italic">Detail Hasil Pemeriksaan</h1>
            <a href="{{ route('pasien.riwayat-pendaftaran.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg font-bold text-xs">Kembali</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Info Dokter & Poli --}}
            <div class="md:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border">
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-4">Informasi Poli</p>
                    <h3 class="font-black text-blue-900 text-xl">{{ $periksa->pendaftaranPoli->jadwalPeriksa->poli->nama_poli }}</h3>
                    <p class="text-sm text-gray-600 mb-4">dr. {{ $periksa->pendaftaranPoli->jadwalPeriksa->dokter->nama }}</p>
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500">Tanggal Periksa:</p>
                        <p class="font-bold text-gray-800">{{ $periksa->tgl_periksa->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Catatan & Obat --}}
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-2xl shadow-sm border">
                    <div class="mb-8">
                        <h4 class="text-sm font-bold text-gray-400 uppercase mb-3 italic">Catatan Dokter:</h4>
                        <p class="text-gray-700 leading-relaxed">{{ $periksa->catatan }} </p>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-sm font-bold text-gray-400 uppercase mb-3 italic">Daftar Obat Resep:</h4>
                        <ul class="space-y-2">
                            @foreach($periksa->detailPeriksas as $detail)
                                <li class="flex justify-between items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
                                    <span class="text-sm font-bold text-gray-700">{{ $detail->obat->nama_obat }}</span>
                                    <span class="text-xs text-gray-400 italic">{{ $detail->obat->kemasan }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="pt-6 border-t border-dashed border-gray-200 flex justify-between items-center">
                        <span class="font-bold text-gray-500">Total Biaya Pemeriksaan:</span>
                        <span class="text-2xl font-black text-green-600">Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }} </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>