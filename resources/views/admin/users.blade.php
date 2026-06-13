<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Users - JOBLYNX</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>

<body class="bg-[#eaf3f0] min-h-screen text-[15px] text-gray-800 flex">

<!-- OVERLAY (mobile) -->
<div id="sidebarOverlay" onclick="toggleSidebar()"
     class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>

<!-- SIDEBAR -->
<aside id="sidebar"
       class="fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-100 shadow-lg z-50
              transform -translate-x-full transition-transform duration-300 flex flex-col">

    <!-- Logo -->
    <div class="px-6 py-5 border-b border-gray-100">
        <h1 class="text-xl font-extrabold text-[#1f4e5a] tracking-tight">
            <span class="text-[#2d7f6a]">
                <i class="fa-solid fa-shield-halved"></i> ADMIN
            </span>
            JOBLYNX
        </h1>
    </div>

    <!-- Menu -->
    <nav class="flex-1 px-4 py-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition
                  {{ request()->is('admin/dashboard') ? 'bg-[#dcfce7] text-[#2d7f6a]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a]' }}">
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
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition
                  {{ request()->is('admin/jobs') ? 'bg-[#dcfce7] text-[#2d7f6a]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a]' }}">
            <i class="fa-solid fa-briefcase w-4"></i> Job Postings
        </a>
        <a href="{{ url('admin/perusahaan') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition
                  {{ request()->is('admin/perusahaan') ? 'bg-[#dcfce7] text-[#2d7f6a]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a]' }}">
            <i class="fa-solid fa-building w-4"></i> Perusahaan
        </a>
        <a href="{{ route('admin.applications') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition
                  {{ request()->is('admin/applications') ? 'bg-[#dcfce7] text-[#2d7f6a]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a]' }}">
            <i class="fa-solid fa-file-signature w-4"></i> Lamaran
        </a>

        <a href="{{ route('admin.notifications.index') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border-l-4
                {{ request()->is('admin/notifications*')
                    ? 'bg-[#dcfce7] text-[#2d7f6a] border-[#2d7f6a] pl-3 shadow-sm'
                    : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a] border-transparent pl-3' }}">
            <i class="fa-solid fa-bell w-4"></i> Notifikasi
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
    <header class="bg-white/95 backdrop-blur-md border-b border-gray-100 sticky top-0 z-30 px-6 py-4 flex items-center justify-between shadow-sm">
        <!-- KIRI -->
        <div class="flex items-center gap-4">
            <button onclick="toggleSidebar()" class="text-[#1f4e5a] hover:text-[#2d7f6a] transition text-xl">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="flex items-center gap-1">
                <i class="fa-solid fa-arrow-trend-up text-[#2d7f6a] text-xl"></i>
                <span class="font-extrabold text-[#1f4e5a] text-xl tracking-tight">JOB<span class="text-[#2d7f6a]">LYNX</span></span>
            </div>
        </div>
        <!-- KANAN -->
        <div class="flex items-center gap-3 relative">
            <div class="relative">
                <div class="relative">
    <button onclick="toggleNotif()" class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-400 hover:text-[#2d7f6a] hover:bg-[#dcfce7] transition">
        <i class="fa-solid fa-bell text-sm"></i>
    </button>
    @if(isset($unread_count) && $unread_count > 0)
        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 border-2 border-white rounded-full flex items-center justify-center text-white text-[8px] font-black">
            {{ $unread_count > 9 ? '9+' : $unread_count }}
        </span>
    @endif
</div>
                <div id="notifDropdown" class="hidden absolute right-0 top-11 w-72 bg-white rounded-2xl shadow-2xl border border-gray-100 z-[999]">
    <div class="px-4 py-3 border-b border-gray-50 flex justify-between items-center">
    <span class="text-[13px] font-bold text-[#1a2e38]">Notifikasi</span>
    @if(isset($unread_count) && $unread_count > 0)
        <div class="flex flex-col items-end gap-1">
            <span class="text-[11px] bg-red-500 text-white px-2 py-0.5 rounded-full font-bold">{{ $unread_count }}</span>
            <a href="{{ route('notifications.readAll') }}"
                class="text-[9px] text-blue-600 hover:underline font-bold italic">Tandai semua dibaca</a>
        </div>
    @endif
</div>
    @forelse($notif_result ?? [] as $notif)
        <div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 text-sm text-gray-700">
            {{ $notif->message ?? $notif->pesan ?? '-' }}
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
            <button id="pbtn" onclick="togglePD()" class="flex items-center gap-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl px-3 py-2 transition-all">
                <div class="w-7 h-7 rounded-lg bg-[#2d7f6a] flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'A', 0, 1)) }}
                </div>
                <span class="text-[13px] font-semibold text-[#1a2e38]">{{ explode(' ', Auth::user()->nama_lengkap)[0] }}</span>
                <i class="fa-solid fa-chevron-down text-gray-400 text-[10px]"></i>
            </button>
            <div id="pdd" class="hidden absolute right-0 top-14 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-[999]">
                <div class="px-4 py-2 border-b border-gray-50 mb-1">
                    <div class="text-[12px] font-bold text-[#1a2e38]">{{ Auth::user()->nama_lengkap }}</div>
                    <div class="text-[11px] text-gray-400">{{ Auth::user()->email }}</div>
                </div>
                <button onclick="closePD(); openChangePasswordModal()" class="w-full flex items-center gap-3 px-4 py-2.5 text-[13px] text-gray-600 hover:bg-gray-50 transition">
                    <i class="fa-solid fa-key text-[#2d7f6a] text-xs w-4"></i> Ubah Password
                </button>
                <div class="border-t border-gray-50 mt-1 pt-1">
                    <a href="javascript:void(0)" onclick="konfirmasiLogout()" class="flex items-center gap-3 px-4 py-2.5 text-[13px] text-red-500 hover:bg-red-50 transition">
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
            <div class="flex items-center gap-3 mb-1">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm"
                     style="background: linear-gradient(135deg, #1a4450, #2d7f6a);">
                    <i class="fa-solid fa-users text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-[#1a4450] tracking-tight leading-tight">
                        Manajemen User
                    </h2>
                    <p class="text-[12px] text-[#2d7f6a] font-semibold tracking-wide uppercase">
                        Monitoring seluruh akun pengguna JOBLYNX
                    </p>
                </div>
            </div>
        </div>

        <!-- TABLE CARD -->
        <div class="max-w-6xl mx-auto px-6 mt-6">
            <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">

                <!-- HEADER CARD: gradien biar langsung keliatan beda -->
                <div class="px-6 py-4 flex justify-between items-center"
                     style="background: linear-gradient(135deg, #1a4450 0%, #2d7f6a 100%);">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-users text-white text-base"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-base">Daftar User</h3>
                            <p class="text-white/60 text-[11px]">Semua akun terdaftar di JOBLYNX</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="bg-white/20 text-white text-xs font-bold px-3 py-1.5 rounded-full">
                            Total: {{ $users->total() }} User
                        </div>
                        <button onclick="openExportModal('excel')"
                            class="flex items-center gap-1.5 bg-green-500 text-white font-bold text-sm px-4 py-2 rounded-xl hover:bg-green-600 transition shadow-sm">
                            <i class="fa-solid fa-file-excel text-xs"></i> Export Excel
                        </button>
                        <button onclick="openExportModal('pdf')"
                            class="flex items-center gap-1.5 bg-red-500 text-white font-bold text-sm px-4 py-2 rounded-xl hover:bg-red-600 transition shadow-sm">
                            <i class="fa-solid fa-file-pdf text-xs"></i> Export PDF
                        </button>
                    </div>
                </div>

                <!-- FILTER & SEARCH -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
                    <form method="GET" action="{{ url('admin/users') }}" class="flex flex-col sm:flex-row gap-3">
                        <!-- Search -->
                        <div class="flex-1 relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama atau email..."
                                class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2d7f6a]/30 focus:border-[#2d7f6a] bg-white">
                        </div>
                        <!-- Filter Role -->
                        <select name="role" class="border border-gray-200 rounded-xl text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#2d7f6a]/30 bg-white text-gray-600">
                            <option value="">Semua Role</option>
                            <option value="hr" {{ request('role') == 'hr' ? 'selected' : '' }}>HR</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                        <button type="submit" class="bg-[#2d7f6a] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#1a4450] transition">
                            <i class="fa-solid fa-filter mr-1"></i> Filter
                        </button>
                        @if(request('search') || request('role'))
                            <a href="{{ url('admin/users') }}" class="border border-gray-200 text-gray-500 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-100 transition text-center">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- TABLE -->
                <div class="overflow-x-auto">
                    <table class="w-full text-[13px]">

                        <!-- THEAD: warna hijau muda kontras -->
                        <thead>
                            <tr style="background-color: #f0faf7; border-bottom: 2px solid #c6ead9;">
                                <th class="px-5 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-[#2d7f6a]">Nama</th>
                                <th class="px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-[#2d7f6a]">Email</th>
                                <th class="px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-[#2d7f6a]">Role</th>
                                <th class="px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-[#2d7f6a]">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($users as $user)
                            <tr class="hover:bg-[#f0faf7] transition-colors duration-150 group">

                                <!-- NAMA -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-extrabold text-white text-sm shadow-sm"
                                             style="background: linear-gradient(135deg, #2d7f6a, #1a4450);">
                                            {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-[#1a4450] text-sm group-hover:text-[#2d7f6a] transition-colors">
                                                {{ $user->nama_lengkap }}
                                            </div>
                                            <div class="text-[11px] text-gray-400 mt-0.5">ID #{{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- EMAIL -->
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2 text-gray-600 text-sm">
                                        <i class="fa-regular fa-envelope text-gray-300 text-xs"></i>
                                        {{ $user->email }}
                                    </div>
                                </td>

                                <!-- ROLE -->
                                <td class="px-4 py-4">
                                    @if($user->role == 'admin')
                                        <span class="inline-flex items-center gap-1.5 bg-red-100 text-red-600 px-3 py-1.5 rounded-full text-[10px] font-extrabold border border-red-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span> ADMIN
                                        </span>
                                    @elseif($user->role == 'hr')
                                        <span class="inline-flex items-center gap-1.5 bg-[#dcfce7] text-[#2d7f6a] px-3 py-1.5 rounded-full text-[10px] font-extrabold border border-[#a7f3d0]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#2d7f6a] inline-block"></span> HR
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-600 px-3 py-1.5 rounded-full text-[10px] font-extrabold border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span> USER
                                        </span>
                                    @endif
                                </td>

                                <!-- AKSI -->
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($user->role == 'admin')
                                            <button onclick="openEditPasswordModal('{{ $user->id }}', '{{ $user->nama_lengkap }}')"
                                                class="inline-flex items-center gap-1.5 bg-amber-500 text-white px-3 py-1.5 rounded-lg text-[11px] font-bold hover:bg-amber-600 transition shadow-sm">
                                                <i class="fa-solid fa-key text-[10px]"></i> Edit Password
                                            </button>
                                        @else
                                            <button onclick="openDetailModal('{{ $user->nama_lengkap }}', '{{ $user->email }}', '{{ strtoupper($user->role) }}', '{{ $user->id }}')"
                                                class="inline-flex items-center gap-1.5 bg-[#2d7f6a] text-white px-3 py-1.5 rounded-lg text-[11px] font-bold hover:bg-[#1a4450] transition shadow-sm">
                                                <i class="fa-solid fa-eye text-[10px]"></i> Detail
                                            </button>
                                            <button onclick='openDeleteModal("{{ url("admin/users/delete/".$user->id) }}")'
                                                class="inline-flex items-center gap-1.5 border border-red-300 text-red-500 px-3 py-1.5 rounded-lg text-[11px] font-bold hover:bg-red-500 hover:text-white hover:border-red-500 transition">
                                                <i class="fa-solid fa-trash text-[10px]"></i> Hapus
                                            </button>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-20">
                                    <div class="inline-flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center">
                                            <i class="fa-solid fa-users text-3xl text-gray-300"></i>
                                        </div>
                                        <span class="text-gray-400 text-sm font-medium">Belum ada user tersedia</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex items-center justify-between">
                    <span class="text-xs text-gray-400">
                        Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user
                    </span>
                    <div class="text-sm">{{ $users->appends(request()->query())->links() }}</div>
                </div>

            </div>
        </div>

        <!-- MODAL: Detail User -->
        <div id="detailModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[999]">
            <div class="bg-white w-[420px] rounded-2xl shadow-xl p-6 relative">
                <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
                <div class="text-center mb-5">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-[#dcfce7] flex items-center justify-center text-[#2d7f6a] text-2xl mb-3">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#1a4450]">Detail User</h3>
                </div>
                <div class="space-y-4 text-sm">
                    <div><div class="text-gray-400 text-[11px] uppercase">Nama</div><div id="detailNama" class="font-bold text-[#1a4450]"></div></div>
                    <div><div class="text-gray-400 text-[11px] uppercase">Email</div><div id="detailEmail" class="font-medium text-gray-700"></div></div>
                    <div><div class="text-gray-400 text-[11px] uppercase">Role</div><div id="detailRole" class="font-medium text-gray-700"></div></div>
                    <div><div class="text-gray-400 text-[11px] uppercase">ID User</div><div id="detailId" class="font-medium text-gray-700"></div></div>
                </div>
            </div>
        </div>

        <!-- MODAL: Hapus User -->
        <div id="deleteModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[999]">
            <div class="bg-white w-[380px] rounded-2xl shadow-xl p-6">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-2xl bg-red-100 text-red-500 flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-trash text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[#1a4450] mb-2">Hapus User?</h3>
                    <p class="text-sm text-gray-500 mb-6">Data user akan dihapus permanen.</p>
                    <div class="flex gap-3">
                        <button onclick="closeDeleteModal()" class="flex-1 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold text-sm">Batal</button>
                        <a id="deleteLink" href="#" class="flex-1 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold text-sm text-center">Hapus</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: Edit Password Admin (dari tabel) -->
        <div id="editPasswordModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[999]">
            <div class="bg-white w-[420px] rounded-2xl shadow-xl p-6 relative">
                <button onclick="closeEditPasswordModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
                <div class="text-center mb-5">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-yellow-100 flex items-center justify-center text-yellow-600 text-2xl mb-3">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#1a4450]">Edit Password Admin</h3>
                </div>
                <form id="editPasswordForm" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="text-[12px] uppercase text-gray-400 font-semibold">Nama Admin</label>
                            <input type="text" id="adminName" disabled class="w-full mt-2 border rounded-xl px-4 py-3 bg-gray-100">
                        </div>
                        <div>
                            <label class="text-[12px] uppercase text-gray-400 font-semibold">Password Baru</label>
                            <input type="password" name="password" required minlength="6" placeholder="Masukkan password baru..."
                                class="w-full mt-2 border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        </div>
                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-3 rounded-xl font-bold transition">Simpan Password</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Ubah Password Sendiri (dari topbar profile) -->
        <div id="changePasswordModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[999]">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 relative">
                <button type="button" onclick="closeChangePasswordModal()" class="absolute top-4 right-4 text-gray-500 hover:text-[#1a4450]">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="text-center mb-5">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-yellow-100 flex items-center justify-center text-yellow-600 text-2xl mb-3">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#1a4450]">Ubah Password Admin</h3>
                    <p class="text-sm text-gray-400 mt-1">{{ Auth::user()->nama_lengkap }}</p>
                </div>
                <form id="changePasswordForm" method="POST" action="{{ route('admin.users.update.password', Auth::user()->id) }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                            <input type="password" name="password" required autocomplete="new-password"
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:border-[#2d7f6a] focus:ring-1 focus:ring-[#2d7f6a] bg-gray-50"
                                   placeholder="Masukkan password baru...">
                        </div>
                        <button type="submit" class="w-full bg-[#2d7f6a] hover:bg-[#1f5c4d] text-white px-5 py-3 rounded-2xl font-bold transition">
                            Simpan Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<!-- MODAL LOGOUT -->
<div id="logoutModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[999]">
    <div class="bg-white w-[340px] rounded-2xl shadow-xl p-6 mx-4">
        <div class="text-center">
            <div class="w-14 h-14 rounded-2xl bg-red-100 text-red-400 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-right-from-bracket text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Konfirmasi Logout</h3>
            <p class="text-sm text-gray-500 mb-5">Apakah Anda yakin ingin keluar?</p>
            <div class="flex gap-3">
                <button onclick="closeLogout()" class="flex-1 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold text-sm">Batal</button>
                <a href="{{ route('logout') }}" class="flex-1 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white font-bold text-sm text-center">Logout</a>
            </div>
        </div>
    </div>
</div>

<script>
    // SIDEBAR
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

    // PROFILE DROPDOWN
    function togglePD() { document.getElementById('pdd').classList.toggle('hidden'); }
    function closePD() { document.getElementById('pdd').classList.add('hidden'); }
    document.addEventListener('click', function(e) {
        const btn = document.getElementById('pbtn');
        const dd  = document.getElementById('pdd');
        if (btn && !btn.contains(e.target)) closePD();
    });

    // NOTIF DROPDOWN
    function toggleNotif() { document.getElementById('notifDropdown').classList.toggle('hidden'); }
    document.addEventListener('click', function(e) {
        const nd = document.getElementById('notifDropdown');
        if (nd && !nd.contains(e.target) && !e.target.closest('button[onclick="toggleNotif()"]')) {
            nd.classList.add('hidden');
        }
    });

    // LOGOUT KONFIRMASI
    function konfirmasiLogout() {
        document.getElementById('logoutModal').classList.replace('hidden', 'flex');
    }
    function closeLogout() {
        document.getElementById('logoutModal').classList.replace('flex', 'hidden');
    }

    // MODAL: Detail
    function openDetailModal(nama, email, role, id) {
        document.getElementById('detailNama').innerText = nama;
        document.getElementById('detailEmail').innerText = email;
        document.getElementById('detailRole').innerText = role;
        document.getElementById('detailId').innerText = id;
        document.getElementById('detailModal').classList.replace('hidden','flex');
    }
    function closeDetailModal() { document.getElementById('detailModal').classList.replace('flex','hidden'); }

    // MODAL: Delete
    function openDeleteModal(link) {
        document.getElementById('deleteLink').href = link;
        document.getElementById('deleteModal').classList.replace('hidden','flex');
    }
    function closeDeleteModal() { document.getElementById('deleteModal').classList.replace('flex','hidden'); }

    // MODAL: Edit Password Admin (dari tabel)
    function openEditPasswordModal(id, name) {
        document.getElementById('adminName').value = name;
        document.getElementById('editPasswordForm').action = '/admin/users/update-password/' + id;
        document.getElementById('editPasswordModal').classList.replace('hidden','flex');
    }
    function closeEditPasswordModal() { document.getElementById('editPasswordModal').classList.replace('flex','hidden'); }

    // MODAL: Ubah Password Sendiri (dari profile topbar)
    function openChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.replace('hidden','flex');
    }
    function closeChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.replace('flex','hidden');
    }

    // ─── EXPORT MODAL ────────────────────────────────────────────────────────
    let exportFormat = 'excel';

    function openExportModal(format) {
        exportFormat = format;
        const isExcel = format === 'excel';
        // Update judul & warna header
        document.getElementById('exportModalTitle').textContent = isExcel ? 'Export Excel – Data User' : 'Export PDF – Data User';
        document.getElementById('exportModalIcon').className = isExcel
            ? 'fa-solid fa-file-excel text-2xl text-green-600'
            : 'fa-solid fa-file-pdf text-2xl text-red-500';
        document.getElementById('exportModalIconBg').className = isExcel
            ? 'w-12 h-12 rounded-2xl flex items-center justify-center bg-green-100'
            : 'w-12 h-12 rounded-2xl flex items-center justify-center bg-red-100';
        document.getElementById('exportModalBtn').className = isExcel
            ? 'flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-green-500 hover:bg-green-600 transition'
            : 'flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-red-500 hover:bg-red-600 transition';
        document.getElementById('exportModal').classList.replace('hidden','flex');
    }

    function closeExportModal() {
        document.getElementById('exportModal').classList.replace('flex','hidden');
    }

    function doExportUsers() {
        const role  = document.getElementById('exportRole').value;
        const search = document.getElementById('exportSearch').value.trim();
        const params = new URLSearchParams();
        if (role)   params.set('role', role);
        if (search) params.set('search', search);
        const route = exportFormat === 'excel'
            ? '{{ route("admin.users.export.excel") }}'
            : '{{ route("admin.users.export.pdf") }}';
        window.location.href = route + (params.toString() ? '?' + params.toString() : '');
        closeExportModal();
    }
</script>

{{-- ═══════════════════════════════════════════════════════
     MODAL: CUSTOM EXPORT USER
══════════════════════════════════════════════════════════ --}}
<div id="exportModal"
     class="hidden fixed inset-0 z-[9999] bg-black/50 backdrop-blur-sm items-center justify-center px-4"
     onclick="if(event.target===this) closeExportModal()">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">

        {{-- Header --}}
        <div class="px-6 pt-6 pb-4 flex items-center gap-4 border-b border-gray-100">
            <div id="exportModalIconBg" class="w-12 h-12 rounded-2xl flex items-center justify-center bg-green-100">
                <i id="exportModalIcon" class="fa-solid fa-file-excel text-2xl text-green-600"></i>
            </div>
            <div class="flex-1">
                <h3 id="exportModalTitle" class="font-bold text-gray-800 text-base">Export Excel – Data User</h3>
                <p class="text-gray-400 text-[11px] mt-0.5">Pilih filter yang ingin diekspor</p>
            </div>
            <button onclick="closeExportModal()" class="text-gray-300 hover:text-gray-500 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5 flex flex-col gap-4">

            {{-- Filter Role --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">
                    <i class="fa-solid fa-user-tag mr-1 text-[#2d7f6a]"></i> Role User
                </label>
                <select id="exportRole"
                        class="w-full border border-gray-200 rounded-xl text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#2d7f6a]/30 bg-white text-gray-700">
                    <option value="">Semua Role (User & HR)</option>
                    <option value="user">User Saja</option>
                    <option value="hr">HR Saja</option>
                </select>
            </div>

            {{-- Pencarian --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">
                    <i class="fa-solid fa-magnifying-glass mr-1 text-[#2d7f6a]"></i> Kata Kunci (Opsional)
                </label>
                <input id="exportSearch" type="text" placeholder="Nama atau email..."
                       class="w-full border border-gray-200 rounded-xl text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#2d7f6a]/30 bg-white text-gray-700">
            </div>

            {{-- Info box --}}
            <div class="bg-[#f0faf7] border border-[#2d7f6a]/20 rounded-xl px-4 py-3 flex gap-3 items-start">
                <i class="fa-solid fa-circle-info text-[#2d7f6a] mt-0.5 text-sm"></i>
                <p class="text-xs text-[#2d7f6a]">Jika semua filter dikosongkan, seluruh data user akan diekspor.</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 pb-6 flex gap-3">
            <button onclick="closeExportModal()"
                    class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition">
                Batal
            </button>
            <button id="exportModalBtn" onclick="doExportUsers()"
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-green-500 hover:bg-green-600 transition">
                <i class="fa-solid fa-download"></i> Unduh Sekarang
            </button>
        </div>
    </div>
</div>

</body>
</html>