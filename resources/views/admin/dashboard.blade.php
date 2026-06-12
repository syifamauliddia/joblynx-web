<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - JOBLYNX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-[#eaf3f0] min-h-screen text-[15px] text-gray-800 flex">

    <!-- OVERLAY -->
    <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-100 shadow-lg z-50 transform -translate-x-full transition-transform duration-300 flex flex-col">
        <div class="px-6 py-5 border-b border-gray-100">
            <h1 class="text-xl font-extrabold text-[#1f4e5a] tracking-tight">
                <span class="text-[#2d7f6a]"><i class="fa-solid fa-shield-halved"></i> ADMIN</span> JOBLYNX
            </h1>
        </div>
        <nav class="flex-1 px-4 py-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition relative overflow-hidden
                  {{ request()->is('admin/dashboard')
                      ? 'bg-[#dcfce7] text-[#2d7f6a] border-l-4 border-[#2d7f6a] pl-3 shadow-sm'
                      : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a] border-l-4 border-transparent pl-3' }}">
                <i class="fa-solid fa-gauge-high w-4"></i> Dashboard
            </a>
            <a href="{{ url('admin/users') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition relative overflow-hidden
                  {{ request()->is('admin/users')
                      ? 'bg-[#dcfce7] text-[#2d7f6a] border-l-4 border-[#2d7f6a] pl-3 shadow-sm'
                      : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a] border-l-4 border-transparent pl-3' }}">
                <i class="fa-solid fa-users w-4"></i> Users
            </a>
            <a href="{{ route('admin.jobs') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition relative overflow-hidden
                  {{ request()->is('admin/jobs')
                      ? 'bg-[#dcfce7] text-[#2d7f6a] border-l-4 border-[#2d7f6a] pl-3 shadow-sm'
                      : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a] border-l-4 border-transparent pl-3' }}">
                <i class="fa-solid fa-briefcase w-4"></i> Job Postings
            </a>
            <a href="{{ url('admin/perusahaan') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition relative overflow-hidden
                  {{ request()->is('admin/perusahaan')
                      ? 'bg-[#dcfce7] text-[#2d7f6a] border-l-4 border-[#2d7f6a] pl-3 shadow-sm'
                      : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a] border-l-4 border-transparent pl-3' }}">
                <i class="fa-solid fa-building w-4"></i> Perusahaan
            </a>
            <a href="{{ route('admin.applications') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition relative overflow-hidden
                  {{ request()->is('admin/applications')
                      ? 'bg-[#dcfce7] text-[#2d7f6a] border-l-4 border-[#2d7f6a] pl-3 shadow-sm'
                      : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a] border-l-4 border-transparent pl-3' }}">
                <i class="fa-solid fa-file-signature w-4"></i> Lamaran
            </a>
        </nav>
        <div class="px-4 py-4 border-t border-gray-100">
            <a href="javascript:void(0)" onclick="konfirmasiLogout()"
                class="w-full block text-center bg-red-50 text-red-500 px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-500 hover:text-white transition">
                <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
            </a>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="flex-1 flex flex-col min-h-screen">

        <!-- TOPBAR -->
        <header
            class="bg-white border-b border-gray-200 sticky top-0 z-30 px-6 py-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="text-[#1f4e5a] hover:text-[#2d7f6a] transition text-xl">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-up text-[#2d7f6a] text-xl"></i>
                    <span class="font-extrabold text-[#1f4e5a] text-xl tracking-tight">JOB<span
                            class="text-[#2d7f6a]">LYNX</span></span>
                </div>
            </div>
            <div class="flex items-center gap-3 relative">
                <div class="relative">
                    <div class="relative">
                        <button onclick="toggleNotif()"
                            class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-400 hover:text-[#2d7f6a] hover:bg-[#dcfce7] transition">
                            <i class="fa-solid fa-bell text-sm"></i>
                        </button>
                        @if (isset($unread_count) && $unread_count > 0)
                            <span
                                class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 border-2 border-white rounded-full flex items-center justify-center text-white text-[8px] font-black">
                                {{ $unread_count > 9 ? '9+' : $unread_count }}
                            </span>
                        @endif
                    </div>
                    <div id="notifDropdown"
                        class="hidden absolute right-0 top-11 w-72 bg-white rounded-2xl shadow-2xl border border-gray-100 z-[999]">
                        <div class="px-4 py-3 border-b border-gray-50 flex justify-between items-center">
                            <span class="text-[13px] font-bold text-[#1a2e38]">Notifikasi</span>
                            @if (isset($unread_count) && $unread_count > 0)
                                <div class="flex flex-col items-end gap-1">
                                    <span
                                        class="text-[11px] bg-red-500 text-white px-2 py-0.5 rounded-full font-bold">{{ $unread_count }}</span>
                                    <a href="{{ route('notifications.readAll') }}"
                                        class="text-[9px] text-blue-600 hover:underline font-bold italic">Tandai semua
                                        dibaca</a>
                                </div>
                            @endif
                        </div>
                        @forelse($notif_result ?? [] as $notif)
                            <div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 text-sm text-gray-700">
                                {{ $notif->message ?? ($notif->pesan ?? '-') }}
                                <div class="text-[10px] text-gray-400 mt-1">
                                    {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-6 text-center text-gray-400 text-sm">
                                <i class="fa-solid fa-bell-slash text-2xl mb-2 block opacity-30"></i>
                                Belum ada notifikasi.
                            </div>
                        @endforelse
                    </div>
                </div>
                <button id="pbtn" onclick="togglePD()"
                    class="flex items-center gap-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl px-3 py-2 transition-all">
                    <div
                        class="w-7 h-7 rounded-lg bg-[#2d7f6a] flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'A', 0, 1)) }}
                    </div>
                    <span
                        class="text-[13px] font-semibold text-[#1a2e38]">{{ explode(' ', Auth::user()->nama_lengkap)[0] }}</span>
                    <i class="fa-solid fa-chevron-down text-gray-400 text-[10px]"></i>
                </button>
                <div id="pdd"
                    class="hidden absolute right-0 top-14 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-[999]">
                    <div class="px-4 py-2 border-b border-gray-50 mb-1">
                        <div class="text-[12px] font-bold text-[#1a2e38]">{{ Auth::user()->nama_lengkap }}</div>
                        <div class="text-[11px] text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                    <button onclick="closePD(); openChangePasswordModal()"
                        class="w-full flex items-center gap-3 px-4 py-2.5 text-[13px] text-gray-600 hover:bg-gray-50 transition">
                        <i class="fa-solid fa-key text-[#2d7f6a] text-xs w-4"></i> Ubah Password
                    </button>
                    <div class="border-t border-gray-50 mt-1 pt-1">
                        <a href="javascript:void(0)" onclick="konfirmasiLogout()"
                            class="flex items-center gap-3 px-4 py-2.5 text-[13px] text-red-500 hover:bg-red-50 transition">
                            <i class="fa-solid fa-right-from-bracket text-xs w-4"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- KONTEN -->
        <main class="flex-1 px-6 py-5">

            <!-- HEADER -->
            <div class="max-w-6xl mx-auto px-6 pt-2">

                <!-- JUDUL -->
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm"
                        style="background: linear-gradient(135deg, #1a4450, #2d7f6a);">
                        <i class="fa-solid fa-gauge-high text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-[#1a4450] tracking-tight leading-tight">Dashboard Admin</h2>
                        <p class="text-[12px] text-[#2d7f6a] font-semibold tracking-wide uppercase">Monitoring seluruh
                            aktivitas JOBLYNX</p>
                    </div>
                </div>

                <!-- GREETING: di bawah judul -->
                @php
                    $hour = now()->timezone('Asia/Jakarta')->hour;
                    $greeting =
                        $hour < 11
                            ? 'Selamat Pagi'
                            : ($hour < 15
                                ? 'Selamat Siang'
                                : ($hour < 18
                                    ? 'Selamat Sore'
                                    : 'Selamat Malam'));
                    $greetIcon = $hour < 11 ? '🌤️' : ($hour < 15 ? '☀️' : ($hour < 18 ? '🌇' : '🌙'));
                    $firstName = explode(' ', Auth::user()->nama_lengkap)[0];
                    $dateNow = now()->timezone('Asia/Jakarta')->translatedFormat('l, d F Y');
                @endphp
                <div
                    class="flex items-center justify-between bg-white border border-gray-200 rounded-2xl px-5 py-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-[#dcfce7] flex items-center justify-center text-xl shrink-0">
                            {{ $greetIcon }}
                        </div>
                        <div>
                            <div class="font-bold text-[#1a4450] text-sm">
                                {{ $greeting }}, <span class="text-[#2d7f6a]">{{ $firstName }}!</span>
                            </div>
                            <div class="text-[11px] text-gray-400 mt-0.5">Semua sistem berjalan normal. Selamat bekerja!
                            </div>
                        </div>
                    </div>
                    <div class="text-right hidden sm:block shrink-0">
                        <div class="text-sm font-black text-[#1a4450]" id="jamSekarang"></div>
                        <div class="text-[11px] text-gray-400 mt-0.5">{{ $dateNow }}</div>
                    </div>
                </div>

            </div>

            <!-- STAT CARDS -->
            @php
                $maxVal = max($total_user, $total_hr, $total_jobs, $total_applications, 1);
                $pctUser = $total_user > 0 ? max(4, round(($total_user / $maxVal) * 100)) : 0;
                $pctHr = $total_hr > 0 ? max(4, round(($total_hr / $maxVal) * 100)) : 0;
                $pctJobs = $total_jobs > 0 ? max(4, round(($total_jobs / $maxVal) * 100)) : 0;
                $pctApps = $total_applications > 0 ? max(4, round(($total_applications / $maxVal) * 100)) : 0;
            @endphp
            <div class="max-w-6xl mx-auto px-6 mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">

                <!-- Total User -->
                <div
                    class="bg-white rounded-2xl shadow-md border border-gray-200 p-5 hover:shadow-lg transition hover:-translate-y-0.5 duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-3xl font-black text-[#1a4450]">{{ $total_user }}</div>
                            <div class="text-[11px] text-gray-400 font-bold uppercase mt-1 tracking-wide">Total User
                            </div>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-users text-blue-500 text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 rounded-full bg-blue-100 overflow-hidden">
                        @if ($total_user > 0)
                            <div class="h-1.5 rounded-full bg-blue-400 transition-all duration-700"
                                style="width: {{ $pctUser }}%"></div>
                        @else
                            <div class="h-1.5 rounded-full bg-blue-200 opacity-40 w-full"></div>
                        @endif
                    </div>
                </div>

                <!-- HR Company -->
                <div
                    class="bg-white rounded-2xl shadow-md border border-gray-200 p-5 hover:shadow-lg transition hover:-translate-y-0.5 duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-3xl font-black text-[#2d7f6a]">{{ $total_hr }}</div>
                            <div class="text-[11px] text-gray-400 font-bold uppercase mt-1 tracking-wide">HR Company
                            </div>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-building text-[#2d7f6a] text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 rounded-full bg-emerald-100 overflow-hidden">
                        @if ($total_hr > 0)
                            <div class="h-1.5 rounded-full bg-[#2d7f6a] transition-all duration-700"
                                style="width: {{ $pctHr }}%"></div>
                        @else
                            <div class="h-1.5 rounded-full bg-emerald-200 opacity-40 w-full"></div>
                        @endif
                    </div>
                </div>

                <!-- Lowongan -->
                <div
                    class="bg-white rounded-2xl shadow-md border border-gray-200 p-5 hover:shadow-lg transition hover:-translate-y-0.5 duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-3xl font-black text-indigo-500">{{ $total_jobs }}</div>
                            <div class="text-[11px] text-gray-400 font-bold uppercase mt-1 tracking-wide">Lowongan
                            </div>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-briefcase text-indigo-500 text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 rounded-full bg-indigo-100 overflow-hidden">
                        @if ($total_jobs > 0)
                            <div class="h-1.5 rounded-full bg-indigo-400 transition-all duration-700"
                                style="width: {{ $pctJobs }}%"></div>
                        @else
                            <div class="h-1.5 rounded-full bg-indigo-200 opacity-40 w-full"></div>
                        @endif
                    </div>
                </div>

                <!-- Lamaran -->
                <div
                    class="bg-white rounded-2xl shadow-md border border-gray-200 p-5 hover:shadow-lg transition hover:-translate-y-0.5 duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-3xl font-black text-orange-500">{{ $total_applications }}</div>
                            <div class="text-[11px] text-gray-400 font-bold uppercase mt-1 tracking-wide">Lamaran</div>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-file-signature text-orange-500 text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 rounded-full bg-orange-100 overflow-hidden">
                        @if ($total_applications > 0)
                            <div class="h-1.5 rounded-full bg-orange-400 transition-all duration-700"
                                style="width: {{ $pctApps }}%"></div>
                        @else
                            <div class="h-1.5 rounded-full bg-orange-200 opacity-40 w-full"></div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- CHARTS ROW 1 -->
            <div class="max-w-6xl mx-auto px-6 mt-5 grid md:grid-cols-2 gap-5">

                <!-- Bar Chart -->
                <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-5">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 rounded-lg bg-[#dcfce7] flex items-center justify-center">
                            <i class="fa-solid fa-chart-column text-[#2d7f6a] text-sm"></i>
                        </div>
                        <h3 class="font-bold text-[#1a4450] text-base">Grafik Status Lamaran</h3>
                    </div>
                    <canvas id="statusChart"></canvas>
                </div>

                <!-- Detail Status -->
                <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-5">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 rounded-lg bg-[#dcfce7] flex items-center justify-center">
                            <i class="fa-solid fa-list text-[#2d7f6a] text-sm"></i>
                        </div>
                        <h3 class="font-bold text-[#1a4450] text-base">Detail Status</h3>
                    </div>
                    <div class="space-y-2">
                        <div
                            class="flex justify-between items-center bg-yellow-50 border border-yellow-100 px-4 py-3 rounded-xl">
                            <div class="flex items-center gap-2"><span
                                    class="w-2 h-2 rounded-full bg-yellow-400 inline-block"></span><span
                                    class="font-semibold text-sm text-gray-700">Dikirim</span></div>
                            <b class="text-yellow-600">{{ $status['Dikirim'] }}</b>
                        </div>
                        <div
                            class="flex justify-between items-center bg-blue-50 border border-blue-100 px-4 py-3 rounded-xl">
                            <div class="flex items-center gap-2"><span
                                    class="w-2 h-2 rounded-full bg-blue-400 inline-block"></span><span
                                    class="font-semibold text-sm text-gray-700">Diproses</span></div>
                            <b class="text-blue-600">{{ $status['Diproses'] }}</b>
                        </div>
                        <div
                            class="flex justify-between items-center bg-indigo-50 border border-indigo-100 px-4 py-3 rounded-xl">
                            <div class="flex items-center gap-2"><span
                                    class="w-2 h-2 rounded-full bg-indigo-400 inline-block"></span><span
                                    class="font-semibold text-sm text-gray-700">Interview</span></div>
                            <b class="text-indigo-600">{{ $status['Interview'] }}</b>
                        </div>
                        <div
                            class="flex justify-between items-center bg-green-50 border border-green-100 px-4 py-3 rounded-xl">
                            <div class="flex items-center gap-2"><span
                                    class="w-2 h-2 rounded-full bg-green-500 inline-block"></span><span
                                    class="font-semibold text-sm text-gray-700">Diterima</span></div>
                            <b class="text-green-600">{{ $status['Diterima'] }}</b>
                        </div>
                        <div
                            class="flex justify-between items-center bg-red-50 border border-red-100 px-4 py-3 rounded-xl">
                            <div class="flex items-center gap-2"><span
                                    class="w-2 h-2 rounded-full bg-red-400 inline-block"></span><span
                                    class="font-semibold text-sm text-gray-700">Ditolak</span></div>
                            <b class="text-red-500">{{ $status['Ditolak'] }}</b>
                        </div>
                        <div
                            class="flex justify-between items-center bg-gray-100 border border-gray-200 px-4 py-3 rounded-xl">
                            <div class="flex items-center gap-2"><span
                                    class="w-2 h-2 rounded-full bg-gray-400 inline-block"></span><span
                                    class="font-semibold text-sm text-gray-700">Dibatalkan</span></div>
                            <b class="text-gray-500">{{ $status['Dibatalkan'] }}</b>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARTS ROW 2: Pie + Statistik -->
            <div class="max-w-6xl mx-auto px-6 mt-5 grid md:grid-cols-2 gap-5">

                <!-- Pie Chart -->
                <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-5 flex flex-col items-center">
                    <div class="flex items-center gap-2 mb-5 w-full">
                        <div class="w-8 h-8 rounded-lg bg-[#dcfce7] flex items-center justify-center">
                            <i class="fa-solid fa-circle-half-stroke text-[#2d7f6a] text-sm"></i>
                        </div>
                        <h3 class="font-bold text-[#1a4450] text-base">Distribusi User & HR</h3>
                    </div>
                    <div style="position:relative; width:260px; height:260px;">
                        <canvas id="userRolePieChart"></canvas>
                    </div>
                </div>

                <!-- Statistik Pengguna -->
                <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-5">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 rounded-lg bg-[#dcfce7] flex items-center justify-center">
                            <i class="fa-solid fa-chart-pie text-[#2d7f6a] text-sm"></i>
                        </div>
                        <h3 class="font-bold text-[#1a4450] text-base">Statistik Pengguna</h3>
                    </div>
                    <div class="space-y-3">
                        <div
                            class="flex justify-between items-center bg-blue-50 border border-blue-100 p-4 rounded-xl">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>
                                <span class="font-semibold text-sm text-gray-700">Job Seeker</span>
                            </div>
                            <b class="text-blue-500 text-lg">{{ $total_user }}</b>
                        </div>
                        <div
                            class="flex justify-between items-center bg-emerald-50 border border-emerald-100 p-4 rounded-xl">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-[#2d7f6a] inline-block"></span>
                                <span class="font-semibold text-sm text-gray-700">HR Company</span>
                            </div>
                            <b class="text-[#2d7f6a] text-lg">{{ $total_hr }}</b>
                        </div>
                        <div
                            class="flex justify-between items-center bg-gray-100 border border-gray-200 p-4 rounded-xl">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-gray-400 inline-block"></span>
                                <span class="font-semibold text-sm text-gray-700">Total Users</span>
                            </div>
                            <b class="text-gray-700 text-lg">{{ $total_user + $total_hr }}</b>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AKTIVITAS TERBARU -->
            <div class="max-w-6xl mx-auto px-6 mt-5 mb-8">
                <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-5">
                    <div class="flex justify-between items-center mb-5">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-[#dcfce7] flex items-center justify-center">
                                <i class="fa-solid fa-bolt text-[#2d7f6a] text-sm"></i>
                            </div>
                            <h3 class="font-bold text-[#1a4450] text-base">Aktivitas Terbaru</h3>
                        </div>
                        <span class="text-[11px] bg-gray-100 text-gray-500 font-bold px-3 py-1 rounded-full">
                            {{ count($latest_activity) }} Aktivitas
                        </span>
                    </div>
                    <div class="space-y-3">
                        @forelse ($latest_activity as $act)
                            <div
                                class="border border-gray-100 rounded-xl p-4 hover:bg-[#f0faf7] hover:border-[#c6ead9] transition">
                                <div class="flex gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-[#dcfce7] flex items-center justify-center text-[#2d7f6a] shrink-0">
                                        @if ($act->tipe == 'lamaran')
                                            <i class="fa-solid fa-paper-plane text-sm"></i>
                                        @else
                                            <i class="fa-solid fa-briefcase text-sm"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        @if ($act->tipe == 'lamaran')
                                            <p class="text-sm leading-relaxed text-gray-700">
                                                <span class="font-bold text-[#1a4450]">{{ $act->nama_lengkap }}</span>
                                                melamar posisi
                                                <span class="font-bold text-[#2d7f6a]">{{ $act->posisi }}</span> di
                                                perusahaan
                                                <span
                                                    class="font-bold text-blue-500">{{ $act->nama_perusahaan }}</span>
                                            </p>
                                        @else
                                            <p class="text-sm leading-relaxed text-gray-700">
                                                HR dari perusahaan <span
                                                    class="font-bold text-[#1a4450]">{{ $act->nama_perusahaan }}</span>
                                                memposting lowongan baru untuk posisi <span
                                                    class="font-bold text-[#2d7f6a]">{{ $act->posisi }}</span>
                                            </p>
                                        @endif
                                        <div class="text-[11px] text-gray-400 mt-2 flex items-center gap-2">
                                            <i class="fa-regular fa-clock"></i>
                                            {{ \Carbon\Carbon::parse($act->created_at)->format('d M Y | H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-14">
                                <div
                                    class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-inbox text-3xl text-gray-300"></i>
                                </div>
                                <span class="text-gray-400 text-sm font-medium">Belum ada aktivitas</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- CHART SCRIPTS -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    new Chart(document.getElementById('statusChart'), {
                        type: 'bar',
                        data: {
                            labels: ['Dikirim', 'Diproses', 'Interview', 'Diterima', 'Ditolak', 'Dibatalkan'],
                            datasets: [{
                                label: 'Jumlah Lamaran',
                                data: @json(array_values($status)),
                                backgroundColor: ['#fbbf24', '#60a5fa', '#818cf8', '#34d399', '#f87171',
                                    '#9ca3af'
                                ],
                                borderRadius: 10,
                                minBarLength: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    },
                                    grid: {
                                        color: '#f3f4f6'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });

                    new Chart(document.getElementById('userRolePieChart'), {
                        type: 'pie',
                        data: {
                            labels: ['User', 'HR Company'],
                            datasets: [{
                                data: [
                                    {{ $total_user > 0 ? $total_user : 0.001 }},
                                    {{ $total_hr > 0 ? $total_hr : 0.001 }}
                                ],
                                backgroundColor: ['#2d7f6a', '#3b82f6'],
                                borderColor: '#ffffff',
                                borderWidth: {{ $total_user == 0 && $total_hr == 0 ? 0 : 3 }}
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 12,
                                        padding: 16
                                    }
                                },
                                datalabels: {
                                    color: '#ffffff',
                                    formatter: (value, ctx) => {
                                        const real = [{{ $total_user }}, {{ $total_hr }}];
                                        const total = real.reduce((a, b) => a + b, 0);
                                        if (total === 0) return '0%';
                                        const pct = Math.round((real[ctx.dataIndex] / total) * 100);
                                        return pct + '%';
                                    },
                                    font: {
                                        weight: '700',
                                        size: 13
                                    }
                                }
                            }
                        },
                        plugins: [ChartDataLabels]
                    });
                });
            </script>

            <!-- MODAL UBAH PASSWORD -->
            <!-- MODAL UBAH PASSWORD -->
            <div id="changePasswordModal" style="display:none"
                class="fixed inset-0 bg-black/40 items-center justify-center z-[999]">
                <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 relative">
                    <button type="button" onclick="closeChangePasswordModal()"
                        class="absolute top-4 right-4 text-gray-500 hover:text-[#1a4450]">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div class="text-center mb-5">
                        <div
                            class="w-16 h-16 mx-auto rounded-2xl bg-yellow-100 flex items-center justify-center text-yellow-600 text-2xl mb-3">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <h3 class="text-xl font-bold text-[#1a4450]">Ubah Password Admin</h3>
                        <p class="text-sm text-gray-400 mt-1">{{ Auth::user()->nama_lengkap }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.users.update.password', Auth::user()->id) }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                                <input type="password" name="password" required autocomplete="new-password"
                                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:border-[#2d7f6a] focus:ring-1 focus:ring-[#2d7f6a] bg-gray-50"
                                    placeholder="Masukkan password baru...">
                            </div>
                            <button type="submit"
                                class="w-full bg-[#2d7f6a] hover:bg-[#1f5c4d] text-white px-5 py-3 rounded-2xl font-bold transition">
                                Simpan Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <script>
        // JAM REAL-TIME
        function updateJam() {
            const el = document.getElementById('jamSekarang');
            if (!el) return;
            const now = new Date();
            const wib = new Date(now.toLocaleString('en-US', {
                timeZone: 'Asia/Jakarta'
            }));
            const h = String(wib.getHours()).padStart(2, '0');
            const m = String(wib.getMinutes()).padStart(2, '0');
            const s = String(wib.getSeconds()).padStart(2, '0');
            el.innerText = h + ':' + m + ':' + s + ' WIB';
        }
        updateJam();
        setInterval(updateJam, 1000);

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }
        }

        function openChangePasswordModal() {
            closePD();
            document.getElementById('changePasswordModal').style.display = 'flex';
        }

        function closeChangePasswordModal() {
            document.getElementById('changePasswordModal').style.display = 'none';
        }

        function togglePD() {
            document.getElementById('pdd').classList.toggle('hidden');
        }

        function closePD() {
            document.getElementById('pdd').classList.add('hidden');
        }

        function toggleNotif() {
            document.getElementById('notifDropdown').classList.toggle('hidden');
        }

        function konfirmasiLogout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                window.location.href = '{{ route('logout') }}';
            }
        }
        document.addEventListener('click', function(e) {
            const pbtn = document.getElementById('pbtn');
            const pdd = document.getElementById('pdd');
            if (pbtn && pdd && !pbtn.contains(e.target) && !pdd.contains(e.target)) closePD();
            const notifDD = document.getElementById('notifDropdown');
            if (notifDD && !e.target.closest('[onclick="toggleNotif()"]') && !notifDD.contains(e.target)) {
                notifDD.classList.add('hidden');
            }
        });
    </script>

</body>

</html>
