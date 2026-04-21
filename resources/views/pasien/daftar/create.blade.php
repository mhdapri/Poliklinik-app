<x-layouts.app title="Daftar Poliklinik">
    <div class="p-6 max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('pasien.dashboard') }}" class="text-blue-600 text-sm font-bold flex items-center gap-2 mb-4">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Form Pendaftaran Poli</h1>
            <p class="text-gray-600 mt-1">Silahkan lengkapi data keluhan Anda untuk mendaftar.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-8">
            <form action="{{ route('pasien.dashboard.store') }}" method="POST">
                @csrf
                {{-- Data Jadwal yang Dipilih --}}
                <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <p class="text-xs font-bold text-blue-400 uppercase mb-2">Informasi Jadwal</p>
                    <p class="font-bold text-blue-900 text-lg">{{ $schedule->poli->nama_poli }}</p>
                    <p class="text-blue-700">dr. {{ $schedule->dokter->nama }}</p>
                    <p class="text-sm text-blue-600 mt-1">{{ $schedule->hari }} ({{ $schedule->jam_mulai }} - {{ $schedule->jam_selesai }})</p>
                    <input type="hidden" name="id_jadwal" value="{{ $schedule->id }}">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Keluhan Utama <span class="text-red-500">*</span></label>
                    <textarea name="keluhan" rows="5" required minlength="5"
                        class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Contoh: Merasa pusing dan mual sejak dua hari yang lalu..."></textarea>
                    <p class="text-[10px] text-gray-400 mt-2 italic">Pastikan keluhan dijelaskan secara singkat dan jelas.</p>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition">
                    Konfirmasi Pendaftaran
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>