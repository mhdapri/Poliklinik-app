<x-layouts.app title="Daftar Antrian Pasien">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Daftar Antrian Pasien</h2>
        <p class="text-slate-500 text-sm">Berikut adalah daftar pasien yang perlu Anda periksa hari ini.</p>
    </div>

    <div class="bg-white shadow-md rounded-2xl border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">No. Antrian</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Nama Pasien</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Keluhan</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($antrians as $item)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary font-bold text-sm">
                            {{ $item->no_antrian }}
                        </span>
                    </td>
                    <!-- <td class="px-6 py-4 font-medium text-slate-700">{{ $item->pasien->nama }}</td> -->
                     <td class="px-6 py-4 font-medium text-slate-700">
                        {{-- Tambahkan tanda tanya (?) sebelum ->nama --}}
                        {{ $item->pasien?->nama ?? 'Pasien Tidak Ditemukan' }}
                    </td>
                    <td class="px-6 py-4 text-slate-600 italic text-sm">"{{ $item->keluhan }}"</td>
                    <td class="px-6 py-4 text-center">
                        @if($item->status == 'selesai')
                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">Sudah Diperiksa</span>
                        @else
                            <a href="{{ route('periksa-pasien.create', $item->id) }}" 
                               class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                                <i class="fas fa-stethoscope"></i> Periksa
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-slate-400 italic">
                        Tidak ada antrian pasien saat ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>