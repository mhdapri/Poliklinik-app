<x-layouts.app title="Riwayat Pendaftaran">
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-6 italic">📜 Riwayat Pemeriksaan Anda</h2>
        
        <div class="bg-white rounded-xl shadow border overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Poli & Dokter [cite: 66]</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">No. Antrian [cite: 67]</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">Status [cite: 67]</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">Aksi [cite: 68]</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $r)
                        <tr class="border-b">
                            <td class="px-6 py-4">
                                <p class="font-bold">{{ $r->jadwalPeriksa->poli->nama_poli }}</p>
                                <p class="text-xs">dr. {{ $r->jadwalPeriksa->dokter->nama }}</p>
                                <p class="text-[10px] text-gray-400 italic">{{ $r->created_at->format('d M Y') }} [cite: 66]</p>
                            </td>
                            <td class="px-6 py-4 text-center font-bold">{{ $r->no_antrian }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $r->status == 'selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $r->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($r->status == 'selesai')
                                    <a href="{{ route('pasien.riwayat.show', $r->id) }}" class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-xs font-bold hover:bg-indigo-600 hover:text-white transition">Detail [cite: 68]</a>
                                @else
                                    <span class="text-gray-300 text-xs italic">Menunggu...</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>