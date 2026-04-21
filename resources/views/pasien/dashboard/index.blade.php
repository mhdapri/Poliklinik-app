<x-layouts.app title="Dashboard Pasien">
    <div class="p-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Pasien</h1>
            <p class="text-gray-600 mt-2">Selamat datang, {{ Auth::user()->nama }}</p>
        </div>

        @if($activeRegistration)
            <div class="mb-8 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-xl p-6 text-white border-b-4 border-blue-800">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold mb-1">🎫 Antrian Aktif Anda</h2>
                        <p class="text-blue-100 opacity-90">Silahkan menuju ke poliklinik sesuai jadwal</p>
                    </div>
                    <div class="text-right">
                        <span class="block text-xs uppercase font-bold text-blue-200 mb-1">Nomor Antrian</span>
                        <span class="bg-yellow-400 text-blue-900 px-6 py-2 rounded-lg font-black text-3xl shadow-inner">
                            {{ $activeRegistration->no_antrian }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-lg border border-white/20">
                        <p class="text-xs font-bold uppercase text-blue-200">Poliklinik</p>
                        <p class="text-lg font-bold">{{ $activeRegistration->jadwalPeriksa->poli->nama_poli }}</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-lg border border-white/20">
                        <p class="text-xs font-bold uppercase text-blue-200">Dokter</p>
                        <p class="text-lg font-bold">{{ $activeRegistration->jadwalPeriksa->dokter->nama }}</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-lg border border-white/20">
                        <p class="text-xs font-bold uppercase text-blue-200">Waktu</p>
                        <p class="text-lg font-bold">{{ $activeRegistration->jadwalPeriksa->hari }}</p>
                        <p class="text-xs text-blue-100">{{ $activeRegistration->jadwalPeriksa->jam_mulai }} - {{ $activeRegistration->jadwalPeriksa->jam_selesai }}</p>
                    </div>

                    <div class="bg-emerald-500 p-4 rounded-lg border-2 border-emerald-300 shadow-lg animate-pulse-slow">
                        <p class="text-xs font-bold uppercase text-emerald-100">Sedang Dilayani</p>
                        <p class="text-4xl font-black text-white" id="live-queue-{{ $activeRegistration->id_jadwal }}">
                            {{ $activeRegistration->jadwalPeriksa->no_antrian_sekarang ?? '0' }}
                        </p>
                        <p class="text-[10px] text-emerald-100 mt-1 uppercase tracking-tighter">● Live Update Reverb</p>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-8 bg-white border-2 border-dashed border-gray-300 rounded-xl p-10 text-center">
                <div class="text-6xl mb-4">🏥</div>
                <h3 class="text-xl font-bold text-gray-800">Tidak Ada Antrian Aktif</h3>
                <p class="text-gray-500 mb-6 max-w-sm mx-auto">Anda belum terdaftar di poliklinik manapun hari ini. Silahkan pilih jadwal di bawah.</p>
                <a href="#jadwal" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-full font-bold transition transform hover:scale-105 inline-block">
                    Daftar Sekarang
                </a>
            </div>
        @endif

        <div id="jadwal" class="mt-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">📅 Jadwal Tersedia</h2>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Poli & Dokter</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">Waktu</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">Antrian Saat Ini</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($schedules as $schedule)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900">{{ $schedule->poli->nama_poli }}</p>
                                    <p class="text-sm text-gray-500">dr. {{ $schedule->dokter->nama }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $schedule->hari }}
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">{{ $schedule->jam_mulai }} - {{ $schedule->jam_selesai }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span id="table-queue-{{ $schedule->id }}" class="text-xl font-black text-gray-700">
                                        {{ $schedule->no_antrian_sekarang ?? '0' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($canRegister)
                                        <button onclick="openModal({{ $schedule->id }}, '{{ $schedule->dokter->nama }}', '{{ $schedule->poli->nama_poli }}')" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-md transition">
                                            Daftar
                                        </button>
                                    @else
                                        <button disabled class="bg-gray-200 text-gray-400 px-5 py-2 rounded-lg text-sm font-bold cursor-not-allowed">
                                            Terkunci
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalDaftar" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Form Pendaftaran</h3>
                    <p class="text-gray-500 text-sm mt-1">Silahkan isi keluhan Anda dengan jujur</p>
                </div>

                <form action="{{ route('pasien.dashboard.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_jadwal" id="modal_id_jadwal">
                    
                    <div class="space-y-4">
                        <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                            <p class="text-[10px] uppercase font-bold text-blue-400 mb-1">Tujuan Pemeriksaan</p>
                            <p id="modal_info_tujuan" class="text-sm font-bold text-blue-900"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Keluhan Utama</label>
                            <textarea name="keluhan" rows="4" required minlength="5"
                                class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm"
                                placeholder="Contoh: Demam tinggi sejak semalam dan pusing kepala..."></textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="closeModal()" class="flex-1 px-4 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition">
                            Kirim Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="module">
        // Inisialisasi Laravel Echo Listener (Sesuai Poin 4)
        // Gantikan Polling dengan Broadcast agar nilai Sedang Dilayani update otomatis
        @foreach($schedules as $schedule)
            window.Echo.channel('poli-updates.{{ $schedule->id }}')
                .listen('QueueUpdated', (e) => {
                    const bannerQueue = document.getElementById('live-queue-{{ $schedule->id }}');
                    const tableQueue = document.getElementById('table-queue-{{ $schedule->id }}');
                    
                    if(bannerQueue) bannerQueue.innerText = e.newQueueNumber;
                    if(tableQueue) tableQueue.innerText = e.newQueueNumber;
                });
        @endforeach

        // Modal Logic
        window.openModal = function(id, dokter, poli) {
            document.getElementById('modal_id_jadwal').value = id;
            document.getElementById('modal_info_tujuan').innerText = `${poli} - dr. ${dokter}`;
            document.getElementById('modalDaftar').classList.remove('hidden');
        }

        window.closeModal = function() {
            document.getElementById('modalDaftar').classList.add('hidden');
        }
    </script>

    <style>
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.9; transform: scale(1.02); }
        }
    </style>
</x-layouts.app>