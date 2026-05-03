<!-- <x-layouts.app title="Admin Dashboard">
    <h1 class="ml-4">hallo selamat datang admin</h1>
</x-layouts.app> -->
<x-layouts.app title="Admin Dashboard">
    <div class="p-6">
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, Admin 👋</h1>
                    <p class="text-gray-500 mt-1">{{ now()->format('l, d F Y') }} — Berikut ringkasan data sistem poliklinik.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-blue-500 hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-7h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <a href="{{ route('polis.index') }}" class="text-xs font-bold text-blue-500 hover:underline">Lihat</a>
                </div>
                <h3 class="text-3xl font-black text-gray-800">{{ $totalPoli }}</h3>
                <p class="text-sm text-gray-500 font-medium">Total Poli</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-emerald-500 hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <a href="{{ route('dokter.index') }}" class="text-xs font-bold text-emerald-500 hover:underline">Lihat</a>
                </div>
                <h3 class="text-3xl font-black text-gray-800">{{ $totalDokter }}</h3>
                <p class="text-sm text-gray-500 font-medium">Total Dokter</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-amber-500 hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <a href="{{ route('pasien.index') }}" class="text-xs font-bold text-amber-500 hover:underline">Lihat</a>
                </div>
                <h3 class="text-3xl font-black text-gray-800">{{ $totalPasien }}</h3>
                <p class="text-sm text-gray-500 font-medium">Total Pasien</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-rose-500 hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <div class="p-3 bg-rose-50 rounded-xl text-rose-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <a href="{{ route('obat.index') }}" class="text-xs font-bold text-rose-500 hover:underline">Lihat</a>
                </div>
                <h3 class="text-3xl font-black text-gray-800">{{ $totalObat }}</h3>
                <p class="text-sm text-gray-500 font-medium">Total Obat</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Daftar Poli</h2>
                    <a href="{{ route('polis.index') }}" class="text-blue-600 text-sm font-semibold hover:underline">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                            <tr>
                                <th class="px-6 py-4">Nama Poli</th>
                                <th class="px-6 py-4">Keterangan</th>
                                <th class="px-6 py-4 text-center">Dokter</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($polis as $poli)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $poli->nama_poli }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 leading-relaxed">
                                        {{ Str::limit($poli->keterangan, 80) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-cyan-100 text-cyan-700 px-3 py-1 rounded-full text-xs font-black">
                                            {{ $poli->dokter_count }} Dokter
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-gray-400">Belum ada data poli.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Akses Cepat</h2>
                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('polis.create') }}" class="flex items-center p-3 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-100 transition font-bold text-sm">
                            <span class="mr-3 bg-white p-2 rounded-lg shadow-sm text-blue-600">+</span>
                            Tambah Poli Baru
                        </a>
                        <a href="{{ route('dokter.create') }}" class="flex items-center p-3 bg-emerald-50 text-emerald-700 rounded-xl hover:bg-emerald-100 transition font-bold text-sm">
                            <span class="mr-3 bg-white p-2 rounded-lg shadow-sm text-emerald-600">+</span>
                            Daftarkan Dokter
                        </a>
                        <a href="{{ route('pasien.create') }}" class="flex items-center p-3 bg-amber-50 text-amber-700 rounded-xl hover:bg-amber-100 transition font-bold text-sm">
                            <span class="mr-3 bg-white p-2 rounded-lg shadow-sm text-amber-600">+</span>
                            Tambah Pasien
                        </a>
                        <a href="{{ route('obat.create') }}" class="flex items-center p-3 bg-rose-50 text-rose-700 rounded-xl hover:bg-rose-100 transition font-bold text-sm">
                            <span class="mr-3 bg-white p-2 rounded-lg shadow-sm text-rose-600">+</span>
                            Input Data Obat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
