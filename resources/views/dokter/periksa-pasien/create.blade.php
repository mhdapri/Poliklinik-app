<x-layouts.app title="Input Hasil Periksa">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('periksa-pasien.index') }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-slate-600 text-sm"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">Periksa Pasien</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Info Pasien --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 sticky top-6">
                <h3 class="font-bold text-slate-800 mb-4 border-b pb-2">Data Pasien</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold">Nama Pasien</p>
                        <p class="text-slate-700 font-medium">{{ $antrian->pasien->nama }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold">Keluhan</p>
                        <p class="text-slate-600 text-sm italic">"{{ $antrian->keluhan }}"</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Input --}}
        <div class="lg:col-span-2">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
                <form action="{{ route('periksa-pasien.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_daftar_poli" value="{{ $antrian->id }}">

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Dokter / Diagnosa <span class="text-red-500">*</span></label>
                        <textarea name="catatan" rows="4" class="w-full border-2 rounded-xl p-4 focus:border-primary outline-none transition" 
                                  placeholder="Tuliskan hasil pemeriksaan dan diagnosa..." required></textarea>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Resep Obat</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($obats as $obat)
                            <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-slate-50 transition 
                                         @if($obat->stok <= 0) opacity-50 bg-slate-100 cursor-not-allowed @endif">
                                <input type="checkbox" name="id_obat[]" value="{{ $obat->id }}" class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary"
                                       @if($obat->stok <= 0) disabled @endif>
                                <div class="ml-3">
                                    <span class="block font-bold text-slate-700">{{ $obat->nama_obat }}</span>
                                    @if($obat->stok <= 0)
                                        <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold uppercase">Stok Habis</span>
                                    @elseif($obat->stok < 10)
                                        <span class="text-[10px] bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full font-bold uppercase">Sisa: {{ $obat->stok }}</span>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-medium">Tersedia: {{ $obat->stok }}</span>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-slate-400 mt-3 font-medium italic">*Obat yang stoknya habis otomatis dinonaktifkan (disabled).</p>
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-xl shadow-lg shadow-primary/20 transition">
                        <i class="fas fa-save mr-2"></i> Simpan Hasil Pemeriksaan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>