<x-layouts.app title="Dokter Dashboard">
    <div class="p-6">
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Halo, dr. {{ Auth::user()->nama }} 👋</h1>
                    <p class="text-gray-500 mt-1">{{ now()->format('l, d F Y') }} — Semangat melayani pasien hari ini!</p>
                </div>
            </div>
        </div>

        {{-- Statistik Ringkas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            {{-- Pasien Menunggu --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-amber-500 hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold bg-amber-100 text-amber-700 px-2 py-1 rounded-md uppercase tracking-wider">Perlu Tindakan</span>
                </div>
                <h3 class="text-3xl font-black text-gray-800">{{ $pasienMenunggu }}</h3>
                <p class="text-sm text-gray-500 font-medium">Pasien Menunggu</p>
            </div>

            {{-- Total Riwayat Periksa --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-emerald-500 hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <a href="{{ route('riwayat-pasien.index') }}" class="text-xs font-bold text-emerald-500 hover:underline">Riwayat</a>
                </div>
                <h3 class="text-3xl font-black text-gray-800">{{ $totalRiwayat }}</h3>
                <p class="text-sm text-gray-500 font-medium">Pasien Selesai</p>
            </div>

            {{-- Total Jadwal Periksa --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-blue-500 hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <a href="{{ route('jadwal-periksa.index') }}" class="text-xs font-bold text-blue-500 hover:underline">Kelola</a>
                </div>
                <h3 class="text-3xl font-black text-gray-800">{{ $totalJadwal }}</h3>
                <p class="text-sm text-gray-500 font-medium">Jadwal Aktif</p>
            </div>

            {{-- Jam Praktek (Info Status) --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-indigo-500 hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-black text-gray-800">Sesi Aktif</h3>
                <p class="text-sm text-gray-500 font-medium">Poli {{ Auth::user()->poli->nama_poli ?? '-' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Antrean Hari Ini --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Antrean Pasien Terbaru</h2>
                    <a href="{{ route('periksa-pasien.index') }}" class="text-blue-600 text-sm font-semibold hover:underline">Lihat Semua Antrean →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                            <tr>
                                <th class="px-6 py-4">No. Antrean</th>
                                <th class="px-6 py-4">Nama Pasien</th>
                                <th class="px-6 py-4">Keluhan</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($antreanTerkini as $antrean)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-black text-blue-600">{{ $antrean->no_antrian }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $antrean->pasien->nama }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 italic">"{{ Str::limit($antrean->keluhan, 40) }}"</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('periksa-pasien.create', $antrean->id) }}" class="bg-primary text-white px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-primary/90 transition">
                                            Periksa
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-400">Tidak ada antrean untuk saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Akses Cepat Dokter --}}
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Menu Cepat</h2>
                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('periksa-pasien.index') }}" class="flex items-center p-3 bg-amber-50 text-amber-700 rounded-xl hover:bg-amber-100 transition font-bold text-sm">
                            <span class="mr-3 bg-white p-2 rounded-lg shadow-sm text-amber-600">🩺</span>
                            Panggil Antrean Berikutnya
                        </a>
                        <a href="{{ route('jadwal-periksa.create') }}" class="flex items-center p-3 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-100 transition font-bold text-sm">
                            <span class="mr-3 bg-white p-2 rounded-lg shadow-sm text-blue-600">📅</span>
                            Atur Jadwal Praktek
                        </a>
                        <a href="{{ route('riwayat-pasien.index') }}" class="flex items-center p-3 bg-emerald-50 text-emerald-700 rounded-xl hover:bg-emerald-100 transition font-bold text-sm">
                            <span class="mr-3 bg-white p-2 rounded-lg shadow-sm text-emerald-600">📝</span>
                            Lihat Riwayat Pasien
                        </a>
                    </div>
                </div>

                {{-- Status Profil --}}
                <div class="bg-gradient-to-br from-primary to-blue-700 p-6 rounded-2xl shadow-lg text-white">
                    <h4 class="font-bold opacity-80 mb-1">Status Keaktifan</h4>
                    <p class="text-2xl font-black mb-4">Praktek Aktif</p>
                    <div class="text-sm opacity-90">
                        Pastikan Anda telah mengisi jadwal periksa agar pasien dapat mendaftar.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>