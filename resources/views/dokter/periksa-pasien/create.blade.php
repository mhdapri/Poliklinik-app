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
                                  placeholder="Tuliskan hasil pemeriksaan dan diagnosa..." required>{{ old('catatan') }}</textarea>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Resep Obat</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($obats as $obat)
                            <div class="p-4 border-2 rounded-xl obat-card transition-all duration-200 @if($obat->stok <= 0) opacity-50 bg-slate-100 border-slate-200 @elseif($obat->stok < 10) bg-amber-50/20 border-amber-200 @else bg-white border-slate-200 @endif" data-stok="{{ $obat->stok }}" data-nama="{{ $obat->nama_obat }}">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" name="id_obat[]" value="{{ $obat->id }}" class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary mt-1"
                                           @if($obat->stok <= 0) disabled @endif
                                           @if(is_array(old('id_obat')) && in_array($obat->id, old('id_obat'))) checked @endif>
                                    <div class="flex-1">
                                        <span class="block font-bold text-slate-700">{{ $obat->nama_obat }}</span>
                                        @if($obat->stok <= 0)
                                            <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold uppercase">Stok Habis</span>
                                        @elseif($obat->stok < 10)
                                            <span class="text-[10px] bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full font-bold uppercase">Stok Menipis (Sisa: {{ $obat->stok }})</span>
                                        @else
                                            <span class="text-[10px] bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded-full font-bold uppercase">Tersedia: {{ $obat->stok }}</span>
                                        @endif
                                    </div>
                                </label>
                                <div class="mt-3 ml-8">
                                    <label class="text-[11px] text-slate-500 font-semibold">Jumlah</label>
                                    <input type="number" name="jumlah[{{ $obat->id }}]" min="1" max="{{ $obat->stok }}" value="{{ old('jumlah.'.$obat->id, 1) }}" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" @if($obat->stok <= 0) disabled @endif>
                                    <span class="error-msg text-[11px] text-red-600 font-bold hidden mt-1 block">
                                        <i class="fas fa-circle-xmark mr-1"></i> Jumlah melebihi stok! (Maks: {{ $obat->stok }})
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-slate-400 mt-3 font-medium italic">*Obat yang stoknya habis otomatis dinonaktifkan. Dokter dapat mengatur jumlah resep sesuai kebutuhan.</p>
                    </div>

                    {{-- Peringatan Stok --}}
                    <div id="stock-warning-box" class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 text-sm font-semibold rounded-xl hidden flex items-center gap-3">
                        <i class="fas fa-triangle-exclamation text-lg"></i>
                        <span>Jumlah obat yang dimasukkan melebihi batas stok tersedia. Mohon periksa kembali.</span>
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-xl shadow-lg shadow-primary/20 transition">
                        <i class="fas fa-save mr-2"></i> Simpan Hasil Pemeriksaan
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action="{{ route('periksa-pasien.store') }}"]');
            const submitBtn = form.querySelector('button[type="submit"]');
            const warningBox = document.getElementById('stock-warning-box');
            
            function validateStock() {
                let hasError = false;
                const cards = document.querySelectorAll('.obat-card');
                
                cards.forEach(card => {
                    const checkbox = card.querySelector('input[type="checkbox"]');
                    const quantityInput = card.querySelector('input[type="number"]');
                    const errorMsg = card.querySelector('.error-msg');
                    const maxStock = parseInt(card.getAttribute('data-stok')) || 0;
                    
                    if (checkbox && checkbox.checked) {
                        const quantity = parseInt(quantityInput.value) || 0;
                        if (quantity > maxStock) {
                            hasError = true;
                            errorMsg.classList.remove('hidden');
                            card.classList.add('border-red-400', 'bg-red-50/50');
                            card.classList.remove('border-slate-200', 'border-amber-200', 'bg-white', 'bg-amber-50/20');
                        } else {
                            errorMsg.classList.add('hidden');
                            card.classList.remove('border-red-400', 'bg-red-50/50');
                            
                            // Restore original style class based on stock level
                            if (maxStock < 10) {
                                card.classList.add('border-amber-200', 'bg-amber-50/20');
                                card.classList.remove('border-slate-200', 'bg-white');
                            } else {
                                card.classList.add('border-slate-200', 'bg-white');
                                card.classList.remove('border-amber-200', 'bg-amber-50/20');
                            }
                        }
                    } else {
                        // If unchecked, clean up error states
                        errorMsg.classList.add('hidden');
                        card.classList.remove('border-red-400', 'bg-red-50/50');
                        if (maxStock <= 0) {
                            // keeps standard disabled class
                        } else if (maxStock < 10) {
                            card.classList.add('border-amber-200', 'bg-amber-50/20');
                            card.classList.remove('border-slate-200', 'bg-white');
                        } else {
                            card.classList.add('border-slate-200', 'bg-white');
                            card.classList.remove('border-amber-200', 'bg-amber-50/20');
                        }
                    }
                });
                
                if (hasError) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    warningBox.classList.remove('hidden');
                } else {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    warningBox.classList.add('hidden');
                }
            }

            // Attach listeners to checkbox changes and input changes
            form.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.addEventListener('change', validateStock);
            });

            form.querySelectorAll('input[type="number"]').forEach(input => {
                input.addEventListener('input', validateStock);
                input.addEventListener('change', validateStock);
            });
            
            // Run initially (for back inputs from old validation)
            validateStock();
        });
    </script>
    @endpush
</x-layouts.app>