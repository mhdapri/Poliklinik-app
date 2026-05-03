<x-layouts.app title="Dashboard Pasien">
    <div class="p-6" x-data="{ currentServing: {} }" x-init="
        {{-- Simulasi Live Update: Ambil data setiap 5 detik --}}
        setInterval(() => {
            fetch('/api/get-current-serving')
                .then(res => res.json())
                .then(data => currentServing = data)
        }, 5000)
    ">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->nama }} 👋</h1>
            <p class="text-gray-500 mt-1">Silakan pilih poli untuk melakukan pendaftaran pemeriksaan.</p>
        </div>

        {{-- BANNER ANTREAN AKTIF --}}
        @if($antrianAktif)
        <div class="mb-10 bg-gradient-to-r from-indigo-600 to-blue-500 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
            <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <span class="bg-white/20 px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest">Antrean Anda Sedang Berjalan</span>
                    <h2 class="text-4xl font-black mt-4">{{ $antrianAktif->jadwalPeriksa->poli->nama_poli }}</h2>
                    <p class="text-lg opacity-90 mt-2">dr. {{ $antrianAktif->jadwalPeriksa->dokter->nama }}</p>
                    <div class="flex gap-4 mt-6">
                        <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-md border border-white/20">
                            <p class="text-xs opacity-70 uppercase font-bold">Nomor Anda</p>
                            <p class="text-3xl font-black">{{ $antrianAktif->no_antrian }}</p>
                        </div>
                        <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-md border border-white/20">
                            <p class="text-xs opacity-70 uppercase font-bold">Sedang Dilayani</p>
                            <p class="text-3xl font-black" x-text="currentServing[{{ $antrianAktif->id_jadwal }}] || '-'">...</p>
                        </div>
                    </div>
                </div>
                <div class="hidden md:flex justify-end">
                    <div class="bg-white text-indigo-600 p-6 rounded-full w-32 h-32 flex flex-col items-center justify-center shadow-2xl">
                        <span class="text-xs font-bold">STATUS</span>
                        <span class="text-sm font-black uppercase">{{ $antrianAktif->status }}</span>
                    </div>
                </div>
            </div>
            {{-- Dekorasi --}}
            <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        </div>
        @endif

        {{-- TABEL JADWAL --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Jadwal Poliklinik & Dokter</h2>
                @if($antrianAktif)
                    <span class="text-rose-500 text-sm font-bold bg-rose-50 px-4 py-2 rounded-xl italic">
                        ⚠ Selesaikan pemeriksaan Anda untuk mendaftar poli lain.
                    </span>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Poli</th>
                            <th class="px-6 py-4">Dokter</th>
                            <th class="px-6 py-4">Hari / Jam</th>
                            <th class="px-6 py-4 text-center">Dilayani</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($jadwalPolis as $index => $jadwal)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $jadwal->poli->nama_poli }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-700">dr. {{ $jadwal->dokter->nama }}</td>
                            <td class="px-6 py-4">
                                <span class="block text-sm font-bold text-gray-800">{{ $jadwal->hari }}</span>
                                <span class="text-xs text-gray-500">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg font-black text-sm" 
                                      x-text="currentServing[{{ $jadwal->id }}] || '0'">
                                    0
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($antrianAktif)
                                    <button disabled class="bg-gray-100 text-gray-400 px-4 py-2 rounded-xl text-xs font-bold cursor-not-allowed">
                                        Sudah Antre
                                    </button>
                                @else
                                    <a href="{{ route('pasien.daftar', $jadwal->id) }}" class="bg-primary text-white px-6 py-2 rounded-xl text-xs font-bold hover:shadow-lg hover:bg-primary/90 transition">
                                        Daftar Poli
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>