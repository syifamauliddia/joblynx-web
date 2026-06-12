<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - JOBLYNX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            scroll-behavior: smooth;
        }

        .notif-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .notif-scroll::-webkit-scrollbar-track {
            background: #f9fafb;
        }

        .notif-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 5px;
        }

        .notif-scroll::-webkit-scrollbar-thumb:hover {
            background: #2d7f6a;
        }
    </style>
</head>

<body class="bg-[#f8fbf9] min-h-screen text-gray-800">

    <nav
        class="bg-white/95 backdrop-blur-md border-b border-gray-100 sticky top-0 z-[100] px-16 py-2 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-2">
            <h1 class="text-2xl font-extrabold text-[#1f4e5a] tracking-tight"><span class="text-[#2d7f6a]"><i
                        class="fa-solid fa-arrow-trend-up"></i> JOB</span>LYNX</h1>
        </div>
        <div class="flex items-center gap-8 font-semibold text-sm text-[#1f4e5a]">
            <a href="{{ url('beranda') }}" class="text-[#2d7f6a]">Beranda</a>
            <a href="{{ url('dashboard') }}" class="hover:text-[#2d7f6a] transition">Dashboard</a>
            @if ($role == 'user')
                <a href="{{ url('skill') }}" class="hover:text-[#2d7f6a] transition">Pengalaman & Minat</a>
            @endif

            @if ($is_logged_in)
                <div class="flex items-center gap-4 border-l border-gray-200 pl-4">
                    <div class="relative">
                        <button onclick="toggleNotif()" id="btnNotif"
                        class="relative w-9 h-9 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-400 hover:text-[#2d7f6a] hover:bg-[#dcfce7] transition focus:outline-none">
                        <i class="fa-regular fa-bell text-sm"></i>
                            @if ($unread_count > 0)
    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 border-2 border-white rounded-full flex items-center justify-center text-white text-[8px] font-black">
        {{ $unread_count > 9 ? '9+' : $unread_count }}
    </span>
@endif
                        </button>
                        <div id="dropdownNotif"
                            class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">
                            <div class="p-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                                <h3 class="font-bold text-[#1a4450]">Notifikasi</h3>
                                @if ($unread_count > 0)
                                    <div class="flex flex-col items-end gap-1">
                                        <span
                                            class="text-[10px] bg-[#dcfce7] text-[#1f5c4d] px-2 py-0.5 rounded-md font-bold">{{ $unread_count }}
                                            Baru</span>
                                        <a href="{{ route('notifications.readAll') }}"
                                        class="text-[9px] text-blue-600 hover:underline font-bold italic">Tandai
                                        semua dibaca</a>
                                    </div>
                                @endif
                            </div>
                            <div class="max-h-80 overflow-y-auto font-normal notif-scroll">
                                @if ($notif_result->count() > 0)
                                    @foreach ($notif_result as $notif)
                                        <div
                                            class="p-4 border-b border-gray-50 hover:bg-gray-50 transition {{ $notif->is_read ? 'opacity-60' : 'bg-white' }}">
                                            <p class="text-sm text-gray-700 mb-1">{{ $notif->pesan }}</p>
                                            <span class="text-xs text-gray-400">
    {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 text-center text-gray-400 text-sm">Belum ada notifikasi.</div>
                                @endif
                            </div>
                        </div>
                    </div>
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
                        <span class="text-gray-600 text-sm hidden lg:inline max-w-[140px] truncate">Halo, <span
                                class="font-bold text-[#1a4450]">{{ explode(' ', $nama_user ?? 'User')[0] }}</span>!</span>
                    </a>
                    <a href="javascript:void(0)" onclick="konfirmasiLogout()"
                        class="ml-2 bg-red-50 text-red-500 px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-500 hover:text-white transition-all shadow-sm">Logout</a>
                </div>
            @else
                <div class="flex items-center gap-4 border-l border-gray-200 pl-4">
                    <a href="{{ url('login') }}" class="text-[#1a4450]">Login</a>
                    <a href="{{ url('register') }}"
                        class="bg-[#2d7f6a] text-white px-5 py-2 rounded-xl hover:bg-[#1f5c4d] transition-all">Daftar</a>
                </div>
            @endif
        </div>
    </nav>

    <header class="px-16 py-14 flex justify-between items-center relative overflow-hidden bg-white">
        <div class="max-w-2xl z-10">
            <h1 class="text-5xl font-extrabold text-[#1a4450] mb-6 leading-tight">Bangun Karir Hebat,<br>Mulai dari
                Sini.</h1>
            <p class="text-gray-500 mb-10 text-lg leading-relaxed max-w-xl">Algoritma cerdas kami mencocokkan profil dan
                keahlianmu dengan ribuan lowongan pekerjaan terbaik secara instan.</p>
            <div class="flex gap-4">
                @if ($role == 'hr')
                    <a href="{{ route('create.loker') }}"
                        class="bg-[#2d7f6a] text-white px-6 py-3 rounded-2xl font-bold hover:bg-[#1f5c4d] transition-all shadow-lg flex items-center gap-2 hover:-translate-y-1">
                        <i class="fa-solid fa-plus"></i> Pasang Lowongan Baru
                    </a>
                    <a href="{{ url('dashboard') }}"
                        class="bg-[#1a4450] text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:bg-[#13323b] transition flex items-center gap-2 border border-[#1a4450]">
                        <i class="fa-solid fa-users"></i> Kelola Pelamar
                    </a>
                @else
                    <a href="#daftar-lowongan"
                        class="bg-[#1a4450] text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:bg-[#13323b] transition border border-[#1a4450]">Jelajahi Lowongan</a>
                    <button onclick="bukaModalCara()"
                        class="text-[#2d7f6a] px-6 py-4 rounded-xl font-bold hover:bg-white border border-gray-200 hover:border-gray-300 transition flex items-center gap-3 bg-white/50 backdrop-blur-sm text-sm">
                        <div
                            class="w-8 h-8 rounded-full bg-[#dcfce7] text-[#2d7f6a] flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-play text-[10px] pr-0.5"></i>
                        </div>
                        Cara Kerjanya
                    </button>
                @endif
            </div>
        </div>
        <div class="hidden md:flex absolute right-10 top-0 w-1/2 h-full items-center justify-end pr-10">
            <img src="{{ asset('image/joblynx.png') }}" alt="Logo Joblynx"
                class="relative z-10 w-[450px] object-contain drop-shadow-xl">
        </div>
    </header>

    <section class="px-16 -mt-7 relative z-30 mb-12">
        @if (!$is_logged_in || $role == 'user')
            <form method="GET" action="{{ url('beranda') }}"
                class="max-w-5xl mx-auto bg-white/95 backdrop-blur-xl p-3 rounded-2xl shadow-xl border border-gray-200 flex flex-col md:flex-row gap-2 items-center">
                <div
                    class="flex-[2] w-full flex items-center bg-gray-50 hover:bg-white rounded-xl px-4 py-3.5 border border-transparent hover:border-[#2d7f6a] focus-within:border-[#2d7f6a] focus-within:bg-white transition-all shadow-sm">
                    <i class="fa-solid fa-magnifying-glass text-[#2d7f6a] mr-3"></i>
                    <input type="text" name="keyword" value="{{ $keyword }}"
                        placeholder="Cari posisi, perusahaan, skill..."
                        class="bg-transparent w-full outline-none text-sm font-semibold text-gray-700 placeholder:text-gray-400">
                </div>

                <div
                    class="flex-1 w-full flex items-center bg-gray-50 hover:bg-white rounded-xl px-4 py-3.5 border border-transparent hover:border-[#2d7f6a] focus-within:border-[#2d7f6a] focus-within:bg-white transition-all shadow-sm relative">
                    <i class="fa-solid fa-location-dot text-[#2d7f6a] mr-3"></i>
                    <input type="text" name="lokasi" value="{{ $lokasi == 'Semua' ? '' : $lokasi }}"
                        placeholder="Lokasi (Kota/Daerah)"
                        class="bg-transparent w-full outline-none text-sm font-semibold text-gray-700 placeholder:text-gray-400">
                </div>

                <button type="submit"
                    class="w-full md:w-auto bg-[#1a4450] hover:bg-[#13323b] text-white font-extrabold py-3.5 px-10 rounded-xl transition-all shadow-lg hover:shadow-2xl active:scale-95">
                    Cari Loker
                </button>
            </form>
        @elseif ($role == 'hr')
            <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-4">
                <div
                    class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 flex items-center gap-5 hover:scale-105 transition-all">
                    <div
                        class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-xl">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Lowongan</p>
                        <h4 class="text-2xl font-black text-[#1a4450]">{{ $total_loker }}</h4>
                    </div>
                </div>
                <div
                    class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 flex items-center gap-5 hover:scale-105 transition-all">
                    <div
                        class="w-12 h-12 bg-green-50 text-green-500 rounded-xl flex items-center justify-center text-xl">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Pelamar</p>
                        <h4 class="text-2xl font-black text-[#1a4450]">{{ $total_pelamar }}</h4>
                    </div>
                </div>
                <div
                    class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 flex items-center gap-5 hover:scale-105 transition-all">
    <div
        class="w-12 h-12 bg-green-50 text-green-500 rounded-xl flex items-center justify-center text-xl">
        <i class="fa-solid fa-bell"></i>
    </div>
    <div>
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Perlu Review
        </p>
                        <h4 class="text-2xl font-black text-[#1a4450]">{{ $pelamar_baru }}</h4>
                    </div>
                </div>
            </div>
        @endif
    </section>

    @if ($is_logged_in && $role == 'user')
        <section class="px-16 mb-12">
            @if ($persen_profil < 100)
                <div
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 p-6 rounded-3xl flex justify-between items-center mb-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="relative w-14 h-14 flex items-center justify-center">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                <path class="text-blue-100" stroke-width="3" stroke="currentColor" fill="none"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <path class="text-blue-500" stroke-dasharray="{{ $persen_profil }}, 100"
                                    stroke-width="3" stroke-linecap="round" stroke="currentColor" fill="none"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            </svg>
                            <span class="absolute text-xs font-extrabold text-blue-700">{{ $persen_profil }}%</span>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-blue-900 text-lg">Profilmu belum 100% lengkap!</h3>
                            <p class="text-sm text-blue-600 mt-0.5 font-medium">Lengkapi CV dan foto profil agar
                                peluang dilirik HRD makin besar.</p>
                        </div>
                    </div>
                    <a href="{{ url('profil') }}"
                        class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-700 hover:shadow-lg transition-all">Lengkapi
                        Sekarang</a>
                </div>
            @endif

            @if (!empty($user_skills_raw))
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-8 relative z-10">
                        <h2 class="text-2xl font-extrabold text-[#1a4450]">Grafik Keahlianmu</h2>
                        <a href="{{ url('skill') }}"
                            class="text-sm bg-[#dcfce7] text-[#1f5c4d] px-4 py-2 rounded-lg font-bold hover:bg-[#2d7f6a] hover:text-white transition">Edit
                            Skill</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 relative z-10">
                        @foreach ($user_skills_raw as $skill_name => $percentage)
                            @php $opacity = max(30, $percentage); @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-2"><span
                                        class="font-bold text-gray-700">{{ $skill_name }}</span><span
                                        class="text-[#2d7f6a] font-extrabold">{{ $percentage }}%</span></div>
                                <div class="w-full bg-gray-100 rounded-full h-3">
                                    <div class="bg-[#2d7f6a] h-3 rounded-full transition-all duration-500"
                                        style="width: {{ $percentage }}%; opacity: {{ $opacity / 100 }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    @endif

    <section id="daftar-lowongan" class="px-16 py-5 mb-16">
        <h2 class="text-3xl font-extrabold text-[#1a4450] mb-8">Lowongan Terbaru</h2>
        @if ($result->isEmpty())
            <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-200">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4"><i
                        class="fa-solid fa-magnifying-glass text-gray-300 text-2xl"></i></div>
                <h3 class="text-lg font-bold text-[#1a4450]">Wah, belum ada lowongan nih..</h3>
                <p class="text-gray-400 text-sm italic">Coba cari dengan kata kunci lain atau kembali lagi nanti ya!
                </p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($result as $row)
                @php
                    $match_percentage = 0;
                    $matched_str = '';
                    $missing_str = '';
                    if ($is_logged_in && $role == 'user' && !empty($row->syarat_skill)) {
                        $job_skills = array_map('trim', explode(',', $row->syarat_skill));
                        $matched_arr = [];
                        $missing_arr = [];
                        foreach ($job_skills as $js) {
                            $js_clean = strtolower(str_replace(' ', '', $js));
                            if (in_array($js_clean, $user_skills_array)) {
                                $matched_arr[] = $js;
                            } else {
                                $missing_arr[] = $js;
                            }
                        }
                        if (count($job_skills) > 0) {
                            $match_percentage = round((count($matched_arr) / count($job_skills)) * 100);
                        }
                        $matched_str = implode(',', $matched_arr);
                        $missing_str = implode(',', $missing_arr);
                    }

                    $cur_job_id = $row->id;
                    $is_applied = in_array($cur_job_id, $applied_jobs);
                    $tgl_posting = isset($row->created_at) ? strtotime($row->created_at) : null;
                    $is_baru = $tgl_posting ? (time() - $tgl_posting) / 3600 < 24 : false;
                    $is_baru = (time() - $tgl_posting) / 3600 < 24;

                    $tampil_gaji = 'Gaji Dirahasiakan';
                    if (!empty($row->gaji_min) && !empty($row->gaji_max)) {
                        $tampil_gaji =
                            'Rp ' .
                            number_format($row->gaji_min, 0, ',', '.') .
                            ' - ' .
                            number_format($row->gaji_max, 0, ',', '.');
                    }

                    $teks_bio = trim($row->bio_perusahaan ?? '');
                    if (empty($teks_bio)) {
                        $teks_bio =
                            'Perusahaan inovatif yang berfokus pada pengembangan talenta digital terbaik di Indonesia.';
                    }
                @endphp

                <div onclick="bukaModalDetail(this)" data-id="{{ $row->id }}"
                    data-applied="{{ $is_applied ? 'true' : 'false' }}" data-matched="{{ $matched_str }}"
                    data-missing="{{ $missing_str }}" data-posisi="{{ $row->posisi }}"
                    data-perusahaan="{{ $row->nama_perusahaan }}" data-deskripsi="{{ $row->deskripsi }}"
                    data-syarat="{{ $row->syarat_skill }}" data-lokasi="{{ $row->lokasi }}"
                    data-tipe="{{ $row->tipe_pekerjaan }}" data-gaji="{{ $tampil_gaji }}"
                    data-profil-pt="{{ $teks_bio }}"
                    class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition-all border border-gray-100 flex flex-col justify-between group cursor-pointer relative overflow-hidden">

                    <div
                        class="absolute top-4 right-4 flex flex-col gap-1.5 items-end z-20 pointer-events-none text-[9px]">
                        @if ($row->status_loker == 'Tutup')
                            <span
                                class="bg-gray-500 text-white font-black px-2.5 py-1 rounded-lg shadow-md uppercase tracking-wider whitespace-nowrap">🔒
                                Ditutup</span>
                        @endif
                        @if ($is_applied)
                            <span
                                class="bg-[#1a4450] text-white font-black px-2.5 py-1 rounded-lg shadow-md uppercase tracking-wider flex items-center gap-1"><i
                                    class="fa-solid fa-check-double text-[10px]"></i> Terkirim</span>
                        @endif
                        @if ($is_logged_in && $role == 'user' && $match_percentage > 0)
                            <span
                                class="bg-green-100 text-[#1f5c4d] font-black px-2.5 py-1 rounded-lg border border-green-200 shadow-sm uppercase tracking-wider">{{ $match_percentage }}%
                                Match</span>
                        @endif
                        @if ($match_percentage >= 80 && !$is_applied)
                            <span
                                class="bg-[#2d7f6a] text-white font-black px-2.5 py-1 rounded-lg shadow-md uppercase tracking-wider animate-bounce flex items-center gap-1"><i
                                    class="fa-solid fa-star text-[10px]"></i> High Match!</span>
                        @endif
                        @if ($is_baru && !$is_applied && !($match_percentage >= 80))
                            <span
                                class="bg-orange-500 text-white font-black px-2.5 py-1 rounded-lg shadow-md uppercase tracking-wider whitespace-nowrap">🔥
                                Baru</span>
                        @endif
                    </div>

                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500 text-xl font-bold shadow-sm">
                                @if (!empty($row->logo_perusahaan))
                                    <img src="{{ asset('uploads/' . $row->logo_perusahaan) }}"
                                        class="w-full h-full object-cover rounded-lg">
                                @else
                                    {{ strtoupper(substr($row->nama_perusahaan, 0, 1)) }}
                                @endif
                            </div>
                        </div>
                        <h3 class="font-bold text-[#1a4450] text-lg leading-tight mb-1">{{ $row->posisi }}</h3>
                        <p class="text-[#2d7f6a] font-semibold text-sm mb-3">{{ $row->nama_perusahaan }}</p>
                        <p class="text-gray-500 text-sm mb-4 line-clamp-2 text-justify">{{ $row->deskripsi }}</p>
                    </div>

                    <div>
                        <div
                            class="flex flex-wrap gap-2 text-[11px] text-gray-500 mb-5 font-bold uppercase tracking-wide">
                            <span class="bg-gray-50 px-2 py-1.5 rounded-md border border-gray-100 flex items-center"><i
                                    class="fa-solid fa-location-dot mr-1.5 text-[#2d7f6a]"></i>
                                {{ $row->lokasi }}</span>
                            <span class="bg-gray-50 px-2 py-1.5 rounded-md border border-gray-100 flex items-center"><i
                                    class="fa-solid fa-clock mr-1.5 text-[#2d7f6a]"></i>
                                {{ $row->tipe_pekerjaan }}</span>
                            <span
                                class="bg-[#dcfce7] px-2 py-1.5 rounded-md border border-[#2d7f6a]/20 text-[#2d7f6a] flex items-center"><i
                                    class="fa-solid fa-money-bill-wave mr-1.5"></i> {{ $tampil_gaji }}</span>
                        </div>

                        <div class="w-full">
                            @if ($role == 'user' || !$is_logged_in)
                                @if ($is_applied)
                                    <div
                                        class="w-full bg-gray-50 text-gray-400 py-3 rounded-xl font-bold flex items-center justify-center gap-2 border border-gray-100 shadow-sm cursor-default">
                                        <i class="fa-solid fa-circle-check text-[#2d7f6a]"></i>
                                        <span class="text-xs uppercase whitespace-nowrap">Sudah Dilamar</span>
                                    </div>
                                @else
                                    <button
                                        onclick="event.stopPropagation(); bukaModalLamar({{ $row->id }}, '{{ addslashes($row->posisi) }}', '{{ addslashes($row->nama_perusahaan) }}')"
                                        class="w-full bg-[#dcfce7] text-[#2d7f6a] border border-[#2d7f6a]/30 py-2.5 rounded-xl font-bold group-hover:bg-[#2d7f6a] group-hover:text-white transition-all text-sm shadow-sm hover:shadow-lg">
                                        Lamar Pekerjaan
                                    </button>
                                @endif
                            @elseif($role == 'hr' && $row->perusahaan_id == $perusahaan_hr_id)
                                <div class="flex items-center gap-2 pt-4 mt-2 border-t border-gray-50">
                                    <a href="{{ route('edit.loker', $row->id) }}" onclick="event.stopPropagation();"
                                        class="flex-1 bg-blue-50 text-blue-600 py-2.5 rounded-xl text-[10px] font-bold text-center border border-blue-100 hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow-md">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                    </a>

                                    @if ($row->status_loker == 'Aktif')
                                        {{-- Tombol Tutup --}}
                                        <a href="{{ route('toggle.status.loker', $row->id) }}?aksi=tutup"
                                            onclick="event.stopPropagation(); return confirm('Yakin ingin menutup lowongan ini?');"
                                            class="flex-1 bg-orange-50 text-orange-500 py-2.5 rounded-xl text-[10px] font-bold text-center border border-orange-100 hover:bg-orange-500 hover:text-white transition-all shadow-sm hover:shadow-md">
                                            <i class="fa-solid fa-power-off mr-1"></i> Tutup
                                        </a>
                                    @else
                                        {{-- Tombol Buka Lagi --}}
                                        <a href="{{ route('toggle.status.loker', $row->id) }}?aksi=buka"
                                            onclick="event.stopPropagation();"
                                            class="flex-1 bg-green-50 text-[#2d7f6a] py-2.5 rounded-xl text-[10px] font-bold text-center border border-green-200 hover:bg-[#2d7f6a] hover:text-white transition-all shadow-sm hover:shadow-md">
                                            <i class="fa-solid fa-power-off mr-1"></i> Buka Lagi
                                        </a>
                                    @endif

                                    <a href="{{ route('hapus.loker', $row->id) }}"
                                        onclick="event.stopPropagation(); return confirm('Hapus lowongan ini permanen?');"
                                        class="flex-1 bg-red-50 text-red-500 py-2.5 rounded-xl text-[10px] font-bold text-center border border-red-100 hover:bg-red-500 hover:text-white transition-all shadow-sm hover:shadow-md">
                                        <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div id="modalDetail"
        class="hidden fixed inset-0 bg-black/60 z-[100] flex items-center justify-center backdrop-blur-sm px-4">
        <div
            class="bg-white w-full max-w-3xl p-8 rounded-3xl shadow-2xl relative max-h-[90vh] overflow-y-auto detail-scroll">
            <button onclick="tutupModalDetail()"
                class="absolute top-6 right-6 text-gray-400 hover:text-red-500 transition text-2xl z-10"><i
                    class="fa-solid fa-xmark"></i></button>
            <div class="flex items-center gap-5 mb-8 border-b pb-6 relative z-0">
                <div id="dInisial"
                    class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-3xl font-bold shadow-inner">
                </div>
                <div>
                    <h2 id="dPosisi" class="text-2xl font-extrabold text-[#1a4450]"></h2>
                    <p id="dPerusahaan" class="text-[#2d7f6a] font-bold text-lg"></p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-0">
                <div>
                    <h3 class="font-bold text-[#1a4450] mb-2 uppercase text-xs tracking-widest text-gray-400">Deskripsi
                        Pekerjaan</h3>
                    <p id="dDesc"
                        class="text-sm text-gray-600 leading-relaxed mb-6 whitespace-pre-line text-justify"></p>

                    <div id="dMatchBox"
                        class="hidden mb-6 bg-[#f0fdf4] p-5 rounded-2xl border border-[#2d7f6a]/20 shadow-inner">
                        <h4 class="font-bold text-[#1a4450] text-sm mb-3 flex items-center gap-2"><i
                                class="fa-solid fa-brain text-[#2d7f6a]"></i> Analisis Kecocokan Skill</h4>
                        <ul id="dMatchedList" class="text-sm text-[#1f5c4d] mb-2 space-y-1.5 font-medium"></ul>
                        <ul id="dMissingList" class="text-sm text-gray-500 space-y-1.5 italic"></ul>
                    </div>

                    <h3 class="font-bold text-[#1a4450] mb-2 uppercase text-xs tracking-widest text-gray-400">Syarat
                        Skill</h3>
                    <div id="dSyarat"
                        class="inline-block bg-[#dcfce7] text-[#2d7f6a] px-3 py-1.5 rounded-lg text-sm font-bold shadow-sm border border-[#2d7f6a]/10">
                    </div>
                </div>
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 h-fit shadow-inner relative z-0">
                    <h3 class="font-bold text-[#1a4450] mb-3 flex items-center gap-2"><i
                            class="fa-solid fa-building text-[#2d7f6a]"></i> Tentang Perusahaan</h3>
                    <p id="dProfilPT" class="text-xs text-gray-500 leading-relaxed italic text-justify mb-4"></p>
                    <div
                        class="mt-4 pt-4 border-t border-gray-200 font-bold uppercase text-[10px] text-gray-400 space-y-1 relative z-0">
                        <p>Lokasi: <span id="dLokasi" class="text-gray-700"></span></p>
                        <p>Tipe: <span id="dTipeVal" class="text-gray-700"></span></p>
                        <p>Gaji: <span id="dGaji" class="text-[#2d7f6a] font-extrabold text-xs"></span></p>
                    </div>
                </div>
            </div>
            <div class="mt-10 relative z-0">
                @if ($role == 'user' || !$is_logged_in)
                    <button id="btnLamarFix"
                        class="w-full py-4 bg-[#1a4450] text-white rounded-2xl font-bold hover:bg-[#13323b] shadow-lg transition flex items-center justify-center gap-2 hover:shadow-2xl"></button>
                @endif
            </div>
        </div>
    </div>

    <div id="modalLamar"
        class="hidden fixed inset-0 bg-black/60 z-[100] flex items-center justify-center backdrop-blur-sm px-4">
        <div class="bg-white w-full max-w-md p-8 rounded-3xl shadow-2xl relative">
            <button onclick="tutupModalLamar()"
                class="absolute top-6 right-6 text-gray-400 hover:text-red-500 transition text-2xl"><i
                    class="fa-solid fa-xmark"></i></button>
            <h2 class="text-2xl font-bold text-[#1a4450] mb-2 relative z-0">Kirim Lamaran</h2>
            <p class="text-gray-500 text-sm mb-6 italic z-0">Melamar posisi <span id="modalPosisi"
                    class="font-bold text-[#2d7f6a]"></span> di <span id="modalPerusahaan"
                    class="font-bold text-gray-700"></span>.</p>
            <form action="{{ url('proses_lamar') }}" method="POST" class="z-0 relative">
                @csrf
                <input type="hidden" name="job_id" id="modalJobId">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pesan Tambahan (Opsional)</label>
                    <textarea name="pesan" rows="3"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#2d7f6a] focus:bg-white transition"
                        placeholder="Kenapa kamu cocok untuk posisi ini?"></textarea>
                </div>
                <div
                    class="mb-8 flex items-center gap-3 bg-[#dcfce7] p-4 rounded-xl border border-[#2d7f6a]/20 shadow-inner">
                    <i class="fa-solid fa-file-pdf text-red-500 text-3xl"></i>
                    <p class="text-xs font-bold text-[#1a4450]">CV & Profil Skill Otomatis<br><span
                            class="font-normal text-[#2d7f6a]">Data profilmu akan dikirim ke HRD</span></p>
                </div>
                <button type="submit" name="kirim_lamaran"
                    class="w-full bg-[#1a4450] text-white py-4 rounded-xl font-bold shadow-lg hover:bg-[#13323b] transition-all hover:shadow-xl active:scale-95">Kirim
                    Lamaran Sekarang</button>
            </form>
        </div>
    </div>

    <div id="modalCara"
        class="fixed inset-0 bg-[#1a4450]/80 backdrop-blur-md z-[100] hidden items-center justify-center p-6 text-gray-800">
        <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden relative border border-white/20">
            <button onclick="tutupModalCara()"
                class="absolute top-6 right-6 text-gray-400 hover:text-red-500 transition-all text-2xl z-20">
                <i class="fa-solid fa-circle-xmark"></i>
            </button>
            <div class="p-12 text-center relative">
                <h2 class="text-3xl font-black text-[#1a4450] mb-2">Gimana Sih Cara Kerjanya? 🤔</h2>
                <p class="text-gray-400 font-medium mb-12">Hanya butuh 4 langkah mudah untuk mendapatkan pekerjaan
                    impianmu.</p>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="space-y-4">
                        <div
                            class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-2xl mx-auto shadow-md italic font-black">
                            1</div>
                        <h4 class="font-bold text-sm text-[#1a4450]">Lengkapi Profil</h4>
                        <p class="text-[10px] text-gray-400">Isi data diri dan upload foto profil terbaikmu.</p>
                    </div>
                    <div class="space-y-4 pt-8 md:pt-12">
                        <div
                            class="w-16 h-16 bg-[#dcfce7] text-[#2d7f6a] rounded-2xl flex items-center justify-center text-2xl mx-auto shadow-md italic font-black">
                            2</div>
                        <h4 class="font-bold text-sm text-[#1a4450]">Matching Skill</h4>
                        <p class="text-[10px] text-gray-400">Sistem mencocokkan skill-mu dengan syarat loker.</p>
                    </div>
                    <div class="space-y-4">
                        <div
                            class="w-16 h-16 bg-yellow-50 text-yellow-500 rounded-2xl flex items-center justify-center text-2xl mx-auto shadow-md italic font-black">
                            3</div>
                        <h4 class="font-bold text-sm text-[#1a4450]">Interview</h4>
                        <p class="text-[10px] text-gray-400">Tunggu undangan interview langsung dari HRD.</p>
                    </div>
                    <div class="space-y-4 pt-8 md:pt-12">
                        <div
                            class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-2xl mx-auto shadow-md italic font-black">
                            4</div>
                        <h4 class="font-bold text-sm text-[#1a4450]">Hired!</h4>
                        <p class="text-[10px] text-gray-400">Dapatkan pekerjaan dan mulai bangun karirmu.</p>
                    </div>
                </div>
                <button onclick="tutupModalCara()"
                    class="mt-12 bg-[#1a4450] text-white px-10 py-3.5 rounded-xl font-bold hover:bg-[#2d7f6a] transition-all shadow-lg hover:shadow-2xl">Siap,
                    Saya Paham!</button>
            </div>
        </div>
    </div>

    <script>
        function toggleNotif() {
            document.getElementById('dropdownNotif').classList.toggle('hidden');
        }
        window.onclick = function(e) {
            const btnNotif = document.getElementById('btnNotif');
            if (btnNotif && !btnNotif.contains(e.target)) {
                const dropdown = document.getElementById('dropdownNotif');
                if (dropdown) dropdown.classList.add('hidden');
            }
        }

        function konfirmasiLogout() {
            if (confirm('Apakah Anda yakin ingin keluar dari JOBLYNX?')) {
                window.location.href = '{{ url('logout') }}';
            }
        }

        function bukaModalCara() {
            document.getElementById('modalCara').classList.remove('hidden');
            document.getElementById('modalCara').classList.add('flex');
        }

        function tutupModalCara() {
            document.getElementById('modalCara').classList.add('hidden');
            document.getElementById('modalCara').classList.remove('flex');
        }

        function tutupModalLamar() {
            document.getElementById('modalLamar').classList.add('hidden');
            document.getElementById('modalLamar').classList.remove('flex');
        }

        function bukaModalLamar(id, pos, per) {
            document.getElementById('modalJobId').value = id;
            document.getElementById('modalPosisi').innerText = pos;
            document.getElementById('modalPerusahaan').innerText = per;
            document.getElementById('modalLamar').classList.remove('hidden');
            document.getElementById('modalLamar').classList.add('flex');
        }

        function bukaModalDetail(el) {
            const d = el.dataset;
            document.getElementById('dPosisi').innerText = d.posisi;
            document.getElementById('dPerusahaan').innerText = d.perusahaan;
            document.getElementById('dInisial').innerText = d.perusahaan.charAt(0).toUpperCase();
            document.getElementById('dDesc').innerText = d.deskripsi;
            document.getElementById('dSyarat').innerText = d.syarat;
            document.getElementById('dLokasi').innerText = d.lokasi;
            document.getElementById('dTipeVal').innerText = d.tipe;
            document.getElementById('dGaji').innerText = d.gaji;
            document.getElementById('dProfilPT').innerText = d.profilPt;

            const btn = document.getElementById('btnLamarFix');
            if (btn) {
                if (d.applied === 'true') {
                    btn.innerHTML = "<i class='fa-solid fa-circle-check text-green-400'></i> Lamaran Sudah Terkirim";
                    btn.className =
                        "w-full py-4 bg-gray-400 text-white rounded-2xl font-bold cursor-default flex items-center justify-center gap-2";
                    btn.onclick = null;
                } else {
                    btn.innerHTML = "Lamar Pekerjaan Sekarang";
                    btn.className =
                        "w-full py-4 bg-[#1a4450] text-white rounded-2xl font-bold hover:bg-[#13323b] shadow-lg transition flex items-center justify-center gap-2 hover:shadow-2xl";
                    btn.onclick = function() {
                        tutupModalDetail();
                        bukaModalLamar(d.id, d.posisi, d.perusahaan);
                    };
                }
            }

            const matchBox = document.getElementById('dMatchBox');
            if (matchBox) {
                if (d.matched !== "" || d.missing !== "") {
                    matchBox.classList.remove('hidden');
                    let matchedHTML = '';
                    if (d.matched !== "") {
                        d.matched.split(',').forEach(s => matchedHTML +=
                            `<li class="flex items-center text-[#1f5c4d] font-semibold mb-1"><i class="fa-solid fa-circle-check text-[#2d7f6a] mr-2 text-[10px]"></i> Kamu menguasai: ${s}</li>`
                        );
                    }
                    document.getElementById('dMatchedList').innerHTML = matchedHTML;
                    let missingHTML = '';
                    if (d.missing !== "") {
                        d.missing.split(',').forEach(s => missingHTML +=
                            `<li class="flex items-center text-gray-500 mb-1"><i class="fa-solid fa-circle-xmark text-red-400 mr-2 text-[10px]"></i> Perlu dipelajari: ${s}</li>`
                        );
                    }
                    document.getElementById('dMissingList').innerHTML = missingHTML;
                } else {
                    matchBox.classList.add('hidden');
                }
            }
            document.getElementById('modalDetail').classList.remove('hidden');
            document.getElementById('modalDetail').classList.add('flex');
        }

        function tutupModalDetail() {
            document.getElementById('modalDetail').classList.add('hidden');
            document.getElementById('modalDetail').classList.remove('flex');
        }
    </script>
</body>

</html>
