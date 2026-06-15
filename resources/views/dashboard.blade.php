<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - JOBLYNX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-[#f8fbf9] min-h-screen text-gray-800 pb-20">

    <nav
        class="bg-white/95 backdrop-blur-md border-b border-gray-100 sticky top-0 z-[100] px-16 py-2 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-2">
            <h1 class="text-2xl font-extrabold text-[#1f4e5a] tracking-tight"><span class="text-[#2d7f6a]"><i
                        class="fa-solid fa-arrow-trend-up"></i> JOB</span>LYNX</h1>
        </div>
        <div class="flex items-center gap-8 font-semibold text-sm text-[#1f4e5a]">
            <a href="{{ url('beranda') }}" class="hover:text-[#2d7f6a] transition">Beranda</a>
            <a href="{{ url('dashboard') }}" class="text-[#2d7f6a]">Dashboard</a>
            @if ($role == 'user')
                <a href="{{ url('skill') }}" class="hover:text-[#2d7f6a] transition">Pengalaman & Minat</a>
            @endif
            <div class="flex items-center gap-4 border-l border-gray-200 pl-4">

                <!-- NOTIFIKASI BELL -->
                <div class="relative">
                    <button onclick="toggleNotif()" id="btnNotif"
                        class="relative w-9 h-9 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-400 hover:text-[#2d7f6a] hover:bg-[#dcfce7] transition focus:outline-none">
                        <i class="fa-regular fa-bell text-sm"></i>
                        @if (isset($unread_count) && $unread_count > 0)
                            <span
                                class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 border-2 border-white rounded-full flex items-center justify-center text-white text-[8px] font-black">
                                {{ $unread_count > 9 ? '9+' : $unread_count }}
                            </span>
                        @endif
                    </button>
                    <div id="dropdownNotif"
                        class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">
                        <div class="p-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                            <h3 class="font-bold text-[#1a4450]">Notifikasi</h3>
                            @if (isset($unread_count) && $unread_count > 0)
                                <div class="flex flex-col items-end gap-1">
                                    <span
                                        class="text-[10px] bg-[#dcfce7] text-[#1f5c4d] px-2 py-0.5 rounded-md font-bold">{{ $unread_count }}
                                        Baru</span>
                                    <a href="{{ route('notifications.readAll') }}"
                                        class="text-[9px] text-blue-600 hover:underline font-bold italic">Tandai semua
                                        dibaca</a>
                                </div>
                            @endif
                        </div>
                        <div class="max-h-80 overflow-y-auto font-normal">
                            @forelse($notif_result ?? [] as $notif)
                                <div
                                    class="p-4 border-b border-gray-50 hover:bg-gray-50 transition {{ $notif->is_read ? 'opacity-60' : 'bg-white' }}">
                                    <p class="text-sm text-gray-700 mb-1">{{ $notif->pesan ?? ($notif->message ?? '-') }}</p>

                                    {{-- Tombol lihat detail hanya muncul jika ada pesan_custom --}}
                                    @if (!empty($notif->pesan_custom))
                                        <button
                                            onclick="toggleDetailNotif('notif{{ $notif->id }}')"
                                            class="mt-1 text-[10px] text-[#59a896] font-bold hover:underline flex items-center gap-1">
                                            <i class="fa-solid fa-circle-info"></i> Lihat Detail
                                        </button>
                                        <div id="notif{{ $notif->id }}"
                                            class="hidden mt-2 bg-blue-50 border border-blue-100 rounded-xl p-3 text-xs text-gray-600 leading-relaxed whitespace-pre-line">
                                            {{ $notif->pesan_custom }}
                                        </div>
                                    @endif

                                    <span class="text-xs text-gray-400 block mt-1">
                                        {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-400 text-sm">Belum ada notifikasi.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <!-- END NOTIFIKASI -->

                <a href="{{ url('profil') }}" class="flex items-center gap-2 group">
                    <div
                        class="w-9 h-9 bg-[#dcfce7] text-[#2d7f6a] rounded-full flex items-center justify-center font-bold border border-[#2d7f6a]/30 overflow-hidden shadow-sm group-hover:ring-2 group-hover:ring-[#2d7f6a] transition-all">
                        @if (!empty($nav_foto))
                            <img src="{{ asset('uploads/' . $nav_foto) }}?t={{ time() }}" alt="Profil"
                                class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($nama_user, 0, 1)) }}
                        @endif
                    </div>
                    <span class="text-gray-600 text-sm hidden lg:inline">Halo, <span
                            class="font-bold text-[#1a4450]">{{ explode(' ', $nama_user)[0] }}</span>!</span>
                </a>
                <a href="javascript:void(0)" onclick="konfirmasiLogout()"
                    class="ml-2 bg-red-50 text-red-500 px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-500 hover:text-white transition-all shadow-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-8 pt-6">
        <div class="mb-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-4xl font-extrabold text-[#1a4450] mb-2">Halo, {{ explode(' ', $nama_user)[0] }}! 👋
                </h2>
                <p class="text-gray-500 italic">
                    {{ $role == 'user' ? 'Lacak status lamaran kerjamu di sini.' : 'Kelola para pelamar yang tertarik bergabung.' }}
                </p>
            </div>
            @if ($role == 'hr')
                <a href="{{ route('create.loker') }}"
                    class="bg-[#2d7f6a] text-white px-6 py-3 rounded-2xl font-bold hover:bg-[#1f5c4d] transition-all shadow-lg flex items-center gap-2 hover:-translate-y-1">
                    <i class="fa-solid fa-plus"></i> Pasang Lowongan Baru
                </a>
            @endif
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-10">
            @php
                $card_configs = [
                    ['label' => 'Perlu Review', 'key' => 'Dikirim',  'color' => 'text-orange-500', 'icon' => 'fa-envelope-open-text'],
                    ['label' => 'Diproses',     'key' => 'Diproses', 'color' => 'text-yellow-500', 'icon' => 'fa-spinner'],
                    ['label' => 'Interview',    'key' => 'Interview','color' => 'text-blue-500',   'icon' => 'fa-calendar-check'],
                    ['label' => 'Diterima',     'key' => 'Diterima', 'color' => 'text-green-500',  'icon' => 'fa-circle-check'],
                    ['label' => 'Ditolak',      'key' => 'Ditolak',  'color' => 'text-red-500',    'icon' => 'fa-circle-xmark'],
                ];
            @endphp

            @foreach ($card_configs as $card)
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-all group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="text-3xl font-black {{ $card['color'] }}">{{ $display_stats[$card['key']] ?? 0 }}</div>
                        <i class="fa-solid {{ $card['icon'] }} opacity-10 text-2xl group-hover:opacity-30 transition-opacity"></i>
                    </div>
                    <div class="text-gray-400 font-bold text-[10px] uppercase tracking-wider">{{ $card['label'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 bg-gray-50/50 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <h3 class="text-xl font-bold text-[#1a4450]">
                    <i class="fa-solid fa-list-check mr-2 text-[#2d7f6a]"></i>
                    {{ $role == 'user' ? 'Riwayat Lamaran' : 'Manajemen Pelamar' }}
                </h3>

                @if ($role == 'hr')
                    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                        <a href="{{ url('export_pelamar') }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 shadow-md transition-all hover:-translate-y-0.5">
                            <i class="fa-solid fa-file-excel"></i> Export Excel
                        </a>

                        <form method="GET" action="{{ url('dashboard') }}"
                            class="flex items-center gap-2 bg-white p-2 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex items-center px-3 gap-2">
                                <i class="fa-solid fa-magnifying-glass text-[#2d7f6a] text-xs"></i>
                                <input type="text" name="search_p" value="{{ htmlspecialchars($search_p ?? '') }}"
                                    placeholder="Cari nama..." class="text-xs outline-none w-32 md:w-40 font-semibold">
                            </div>
                            <div class="w-px h-6 bg-gray-200"></div>
                            <select name="filter_status"
                                class="text-xs bg-transparent outline-none cursor-pointer font-bold text-gray-500 px-2">
                                <option value="">Semua Status</option>
                                <option value="Dikirim"   {{ ($filter_status ?? '') == 'Dikirim'   ? 'selected' : '' }}>Dikirim</option>
                                <option value="Diproses"  {{ ($filter_status ?? '') == 'Diproses'  ? 'selected' : '' }}>Diproses</option>
                                <option value="Interview" {{ ($filter_status ?? '') == 'Interview' ? 'selected' : '' }}>Interview</option>
                                <option value="Diterima"  {{ ($filter_status ?? '') == 'Diterima'  ? 'selected' : '' }}>Diterima</option>
                                <option value="Ditolak"   {{ ($filter_status ?? '') == 'Ditolak'   ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            <button type="submit"
                                class="bg-[#1a4450] hover:bg-[#2d7f6a] text-white px-5 py-2.5 rounded-xl text-xs font-bold transition-all">Cari</button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[#1a4450] font-bold text-xs uppercase tracking-widest bg-white border-b border-gray-100">
                            <th class="p-6">{{ $role == 'user' ? 'Perusahaan' : 'Nama Pelamar' }}</th>
                            <th class="p-6">Posisi Lowongan</th>
                            <th class="p-6">Tanggal</th>
                            <th class="p-6">Status & Kontak</th>
                            @if ($role == 'hr')
                                <th class="p-6">Aksi Pelamar</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @if ($q_history && $q_history->count() > 0)
                            @foreach ($q_history as $row)
                                <tr class="hover:bg-[#f8fbf9] transition border-b border-gray-50 last:border-0">
                                    <td class="p-6">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-[#1a4450]">
                                                {{ $role == 'user' ? $row->perusahaan : $row->nama_lengkap }}
                                            </span>
                                            @if ($role == 'user' && !empty($row->hr_email))
                                                <a href="mailto:{{ $row->hr_email }}"
                                                    class="text-[10px] text-[#2d7f6a] font-bold hover:underline">
                                                    <i class="fa-solid fa-envelope mr-1"></i> {{ $row->hr_email }}
                                                </a>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="p-6 font-semibold text-[#2d7f6a]">{{ $row->posisi }}</td>

                                    <td class="p-6 text-gray-400 text-xs">
                                        {{ \Carbon\Carbon::parse($row->tanggal_lamar)->format('d M Y') }}
                                    </td>

                                    <td class="p-6">
                                        <div class="flex flex-col gap-2 items-start">
                                            @php
                                                $st = $row->status;
                                                $bg = 'bg-gray-100 text-gray-500 border-gray-200';
                                                if ($st == 'Diproses')   $bg = 'bg-yellow-50 text-yellow-600 border-yellow-200';
                                                if ($st == 'Interview')  $bg = 'bg-blue-50 text-blue-600 border-blue-200';
                                                if ($st == 'Diterima')   $bg = 'bg-green-50 text-green-600 border-green-200';
                                                if ($st == 'Ditolak')    $bg = 'bg-red-50 text-red-600 border-red-200';
                                                if ($st == 'Dibatalkan') $bg = 'bg-gray-100 text-gray-400 border-gray-200 opacity-70';
                                            @endphp

                                            <span class="px-3 py-1 rounded-full text-[10px] font-black border uppercase {{ $bg }}">
                                                {{ $st }}
                                            </span>

                                            @if ($role == 'user' && $st == 'Dikirim')
                                                <a href="{{ route('batal.lamar', $row->app_id) }}"
                                                    onclick="return confirm('Yakin ingin membatalkan lamaran ini?');"
                                                    class="bg-red-50 text-red-500 hover:bg-red-500 hover:text-white border border-red-200 px-3 py-1 rounded-lg text-[10px] font-bold transition shadow-sm w-full text-center">
                                                    <i class="fa-solid fa-xmark mr-1"></i> Batalkan
                                                </a>
                                            @endif

                                            @if ($role == 'user' && $st == 'Diterima')
                                                <a href="mailto:{{ $row->hr_email ?? '' }}?subject=Tindak Lanjut: {{ $row->posisi }}"
                                                    class="bg-[#dcfce7] text-[#2d7f6a] hover:bg-[#2d7f6a] hover:text-white border border-[#2d7f6a]/30 px-3 py-1 rounded-lg text-[10px] font-bold transition shadow-sm text-center">
                                                    <i class="fa-solid fa-envelope mr-1"></i> Email HRD
                                                </a>
                                            @endif

                                            @if ($role == 'hr' && in_array($st, ['Diterima', 'Interview']))
                                                <a href="mailto:{{ $row->user_email ?? '' }}?subject=Undangan JOBLYNX: {{ $row->posisi }}"
                                                    class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white border border-blue-200 px-3 py-1 rounded-lg text-[10px] font-bold transition shadow-sm text-center">
                                                    <i class="fa-solid fa-envelope mr-1"></i> Email
                                                </a>
                                            @endif
                                        </div>
                                    </td>

                                    @if ($role == 'hr')
                                        <td class="p-6">
                                            @if ($st != 'Dibatalkan')
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('detail.pelamar', $row->app_id) }}"
                                                        class="bg-[#dcfce7] text-[#2d7f6a] hover:bg-[#2d7f6a] hover:text-white border border-[#2d7f6a]/30 px-3 py-1.5 rounded-lg text-[10px] font-bold transition shadow-sm">
                                                        <i class="fa-solid fa-eye"></i> Detail
                                                    </a>

                                                    <select onchange="handleStatusChange(this, '{{ $row->app_id }}')"
                                                        class="bg-white border border-gray-200 text-[10px] font-bold text-gray-500 rounded-lg px-2 py-1.5 outline-none focus:border-[#2d7f6a] cursor-pointer shadow-sm">
                                                        <option value="" disabled selected>Ubah Status</option>
                                                        <option value="Diproses">Review</option>
                                                        <option value="Interview">Interview</option>
                                                        <option value="Diterima">Terima</option>
                                                        <option value="Ditolak">Tolak</option>
                                                    </select>
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-300 italic font-medium">
                                                    <i class="fa-solid fa-ban"></i> Lamaran Dibatalkan
                                                </span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="{{ $role == 'hr' ? '5' : '4' }}"
                                    class="p-20 text-center text-gray-400 font-medium">
                                    <i class="fa-solid fa-folder-open mb-3 text-3xl opacity-50 block"></i> Belum ada data lamaran.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TONG SAMPAH LOWONGAN KHUSUS HR --}}
        @if ($role == 'hr')
            <div class="mt-10 mb-10">
                <h3 class="text-xl font-extrabold text-[#1a4450] mb-4">
                    <i class="fa-solid fa-trash-can-arrow-up text-red-500 mr-2"></i> Riwayat Lowongan Dihapus
                </h3>

                @if (isset($deleted_jobs) && $deleted_jobs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($deleted_jobs as $hapus)
                            <div class="bg-red-50/50 border border-red-100 p-5 rounded-2xl flex justify-between items-center shadow-sm">
                                <div>
                                    <h4 class="font-bold text-gray-700">{{ $hapus->posisi }}</h4>
                                    <p class="text-xs text-gray-400 mt-1">Dihapus pada:
                                        {{ \Carbon\Carbon::parse($hapus->deleted_at)->format('d M Y') }}</p>
                                </div>
                                <a href="{{ route('restore.loker', $hapus->id) }}"
                                    onclick="return confirm('Yakin ingin memunculkan kembali lowongan ini ke Beranda?');"
                                    class="bg-white border border-green-200 text-green-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-green-600 hover:text-white transition-all shadow-sm flex items-center gap-2">
                                    <i class="fa-solid fa-rotate-left"></i> Pulihkan
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-3xl shadow-sm border border-dashed border-gray-200 p-8 text-center">
                        <i class="fa-solid fa-trash-can-arrow-up text-3xl text-gray-300 mb-3 block"></i>
                        <p class="text-gray-400 text-sm font-medium italic">Belum ada lowongan yang dihapus.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- ===== MODAL POPUP CUSTOM PESAN STATUS (DASHBOARD HR) ===== --}}
    <div id="modalStatusCustom" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 p-8">
            <div class="flex items-center gap-3 mb-6">
                <div id="modalIcon" class="w-10 h-10 rounded-full flex items-center justify-center text-white text-lg"></div>
                <h3 id="modalTitle" class="text-lg font-extrabold text-[#1a4450]"></h3>
            </div>

            <form id="formStatusCustom" action="{{ route('update.status') }}" method="POST">
                @csrf
                <input type="hidden" name="app_id" id="modalAppId">
                <input type="hidden" name="new_status" id="modalNewStatus">

                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">📍 Lokasi</label>
                        <input type="text" name="lokasi_interview" id="modalLokasi"
                            class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#59a896] transition"
                            placeholder="Contoh: Kantor Pusat, Jl. Sudirman No.10">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">📅 Tanggal</label>
                            <input type="date" name="tanggal_interview" id="modalTanggal"
                                class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#59a896] transition">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">🕐 Jam</label>
                            <input type="time" name="jam_interview" id="modalJam"
                                class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#59a896] transition">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">📝 Pesan Tambahan</label>
                        <textarea name="pesan_tambahan" id="modalPesan" rows="3"
                            class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#59a896] transition resize-none"
                            placeholder="Tulis instruksi tambahan untuk pelamar..."></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="tutupModal()"
                        class="flex-1 border border-gray-200 text-gray-500 hover:bg-gray-50 py-2.5 rounded-xl font-bold text-sm transition">
                        Batal
                    </button>
                    <button type="submit" id="modalSubmitBtn"
                        class="flex-1 bg-[#59a896] hover:bg-[#2d7f6a] text-white py-2.5 rounded-xl font-bold text-sm transition shadow-lg">
                        Kirim Notifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ===== NOTIFIKASI DROPDOWN =====
        function toggleNotif() {
            document.getElementById('dropdownNotif').classList.toggle('hidden');
        }

        window.addEventListener('click', function(e) {
            const btn = document.getElementById('btnNotif');
            const dd = document.getElementById('dropdownNotif');
            if (btn && dd && !btn.contains(e.target) && !dd.contains(e.target)) {
                dd.classList.add('hidden');
            }
        });

        // ===== TOGGLE DETAIL NOTIFIKASI PELAMAR =====
        function toggleDetailNotif(id) {
            document.getElementById(id).classList.toggle('hidden');
        }

        // ===== LOGOUT =====
        function konfirmasiLogout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                window.location.href = '{{ url('logout') }}';
            }
        }

        // ===== MODAL STATUS HR =====
        function handleStatusChange(selectEl, appId) {
            const status = selectEl.value;

            if (status === 'Interview' || status === 'Diterima') {
                document.getElementById('modalAppId').value = appId;
                document.getElementById('modalNewStatus').value = status;
                document.getElementById('modalLokasi').value = '';
                document.getElementById('modalTanggal').value = '';
                document.getElementById('modalJam').value = '';
                document.getElementById('modalPesan').value = '';

                const icon = document.getElementById('modalIcon');
                const title = document.getElementById('modalTitle');
                const btn = document.getElementById('modalSubmitBtn');

                if (status === 'Interview') {
                    icon.className = 'w-10 h-10 rounded-full flex items-center justify-center text-white text-lg bg-blue-500';
                    icon.innerHTML = '<i class="fa-solid fa-calendar-check"></i>';
                    title.textContent = 'Undang Interview';
                    document.getElementById('modalLokasi').placeholder = 'Contoh: Kantor Pusat, Jl. Sudirman No.10';
                    document.getElementById('modalPesan').placeholder = 'Contoh: Harap membawa dokumen asli dan berpakaian rapi.';
                    btn.className = 'flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2.5 rounded-xl font-bold text-sm transition shadow-lg';
                } else {
                    icon.className = 'w-10 h-10 rounded-full flex items-center justify-center text-white text-lg bg-[#59a896]';
                    icon.innerHTML = '<i class="fa-solid fa-check-circle"></i>';
                    title.textContent = 'Terima Pelamar';
                    document.getElementById('modalLokasi').placeholder = 'Contoh: HRD Lt.2, Gedung A';
                    document.getElementById('modalPesan').placeholder = 'Contoh: Harap konfirmasi kehadiran maksimal H-1.';
                    btn.className = 'flex-1 bg-[#59a896] hover:bg-[#2d7f6a] text-white py-2.5 rounded-xl font-bold text-sm transition shadow-lg';
                }

                const modal = document.getElementById('modalStatusCustom');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                selectEl.selectedIndex = 0;

            } else {
                // Diproses / Ditolak — langsung submit tanpa modal
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("update.status") }}';
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                const inputApp = document.createElement('input');
                inputApp.type = 'hidden';
                inputApp.name = 'app_id';
                inputApp.value = appId;
                const inputStatus = document.createElement('input');
                inputStatus.type = 'hidden';
                inputStatus.name = 'new_status';
                inputStatus.value = status;
                form.appendChild(csrf);
                form.appendChild(inputApp);
                form.appendChild(inputStatus);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function tutupModal() {
            const modal = document.getElementById('modalStatusCustom');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('modalStatusCustom').addEventListener('click', function(e) {
            if (e.target === this) tutupModal();
        });
    </script>
</body>

</html>