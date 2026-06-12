<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Perusahaan - JOBLYNX</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
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
            <i class="fa-solid fa-gauge-high w-4"></i>
            Dashboard
        </a>

        <a href="{{ url('admin/users') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition
                  {{ request()->is('admin/users') ? 'bg-[#dcfce7] text-[#2d7f6a]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a]' }}">
            <i class="fa-solid fa-users w-4"></i>
            Users
        </a>

        <a href="{{ route('admin.jobs') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition
                  {{ request()->is('admin/jobs') ? 'bg-[#dcfce7] text-[#2d7f6a]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a]' }}">
            <i class="fa-solid fa-briefcase w-4"></i>
            Job Postings
        </a>

        <a href="{{ url('admin/perusahaan') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition relative overflow-hidden
          {{ request()->is('admin/perusahaan')
             ? 'bg-[#dcfce7] text-[#2d7f6a] border-l-4 border-[#2d7f6a] pl-3 shadow-sm'
             : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a] border-l-4 border-transparent pl-3' }}">
    <i class="fa-solid fa-building w-4"></i> Perusahaan
        </a>

        <a href="{{ route('admin.applications') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition
                  {{ request()->is('admin/applications') ? 'bg-[#dcfce7] text-[#2d7f6a]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a]' }}">
            <i class="fa-solid fa-file-signature w-4"></i>
            Lamaran
        </a>

    </nav>

    <div class="px-4 py-4 border-t border-gray-100">
        <a href="javascript:void(0)" onclick="konfirmasiLogout()" class="w-full block text-center bg-red-50 text-red-500 px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-500 hover:text-white transition">
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
                <i class="fa-solid fa-building text-white text-sm"></i>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-[#1a4450] tracking-tight leading-tight">
                    Manajemen Perusahaan
                </h2>

                <p class="text-[12px] text-[#2d7f6a] font-semibold tracking-wide uppercase">
                    Monitoring seluruh perusahaan partner JOBLYNX
                </p>
            </div>

        </div>

    </div>

    <!-- TABLE CARD -->
    <div class="max-w-6xl mx-auto px-6 mt-6">

        <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">

            <!-- HEADER CARD -->
            <div class="px-6 py-4 flex justify-between items-center"
                 style="background: linear-gradient(135deg, #1a4450 0%, #2d7f6a 100%);">

                <div class="flex items-center gap-3">

                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-building text-white text-base"></i>
                    </div>

                    <div>
                        <h3 class="font-bold text-white text-base">
                            Daftar Perusahaan
                        </h3>

                        <p class="text-white/60 text-[11px]">
                            Semua perusahaan partner JOBLYNX
                        </p>
                    </div>

                </div>

                <div class="bg-white/20 text-white text-xs font-bold px-3 py-1.5 rounded-full">
                    Total: {{ count($perusahaan) }} Perusahaan
                </div>

            </div>

            <!-- TABLE -->
            <div class="overflow-x-auto">

                <table class="w-full text-[13px]">

                    <!-- THEAD -->
                    <thead>
                        <tr style="background-color: #f0faf7; border-bottom: 2px solid #c6ead9;">

                            <th class="px-5 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-[#2d7f6a]">
                                Nama Perusahaan
                            </th>

                            <th class="px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-[#2d7f6a]">
                                Email
                            </th>

                            <th class="px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-[#2d7f6a]">
                                Status
                            </th>

                            <th class="px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-[#2d7f6a]">
                                Aksi
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($perusahaan as $item)

                        <tr class="hover:bg-[#f0faf7] transition-colors duration-150 group">

                            <!-- NAMA -->
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm shrink-0"
                                         style="background: linear-gradient(135deg, #2d7f6a, #1a4450);">

                                        <i class="fa-solid fa-building text-white text-sm"></i>

                                    </div>

                                    <div>

                                        <div class="font-bold text-[#1a4450] text-sm group-hover:text-[#2d7f6a] transition-colors">
                                            {{ $item->nama_perusahaan }}
                                        </div>

                                        <div class="text-[11px] text-gray-400 mt-0.5">
                                            ID #{{ $item->id }}
                                        </div>

                                    </div>

                                </div>

                            </td>

                            <!-- EMAIL -->
                            <td class="px-4 py-4">

                                <div class="flex items-center gap-2 text-gray-600 text-sm">
                                    <i class="fa-regular fa-envelope text-gray-300 text-xs"></i>
                                    {{ $item->email }}
                                </div>

                            </td>

                            <!-- STATUS -->
                            <td class="px-4 py-4">

                                @if($item->status == 'Aktif')

                                <span class="inline-flex items-center gap-1.5 bg-[#dcfce7] text-[#2d7f6a] px-3 py-1.5 rounded-full text-[10px] font-extrabold border border-[#a7f3d0]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#2d7f6a] inline-block"></span>
                                    AKTIF
                                </span>

                                @else

                                <span class="inline-flex items-center gap-1.5 bg-red-100 text-red-500 px-3 py-1.5 rounded-full text-[10px] font-extrabold border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                                    NONAKTIF
                                </span>

                                @endif

                            </td>

                            <!-- AKSI -->
                            <td class="px-4 py-4">

                                <div class="flex items-center gap-2">

                                    <!-- DETAIL -->
                                    <button
                                        onclick="openDetail(
                                            '{{ $item->nama_perusahaan }}',
                                            '{{ $item->email }}',
                                            '{{ $item->telepon }}',
                                            '{{ $item->alamat }}',
                                            '{{ $item->status }}'
                                        )"

                                        class="inline-flex items-center gap-1.5 bg-[#2d7f6a] text-white px-3 py-1.5 rounded-lg text-[11px] font-bold hover:bg-[#1a4450] transition shadow-sm">

                                        <i class="fa-solid fa-eye text-[10px]"></i>
                                        Detail

                                    </button>

                                    <!-- STATUS -->
                                    <button
                                        onclick="openStatus('{{ url('admin/perusahaan/status/'.$item->id) }}')"

                                        class="inline-flex items-center gap-1.5 bg-amber-500 text-white px-3 py-1.5 rounded-lg text-[11px] font-bold hover:bg-amber-600 transition shadow-sm">

                                        <i class="fa-solid fa-toggle-on text-[10px]"></i>
                                        Toggle

                                    </button>

                                    <!-- DELETE -->
                                    <button
                                        onclick="openDelete('{{ url('admin/perusahaan/delete/'.$item->id) }}')"

                                        class="inline-flex items-center gap-1.5 border border-red-300 text-red-500 px-3 py-1.5 rounded-lg text-[11px] font-bold hover:bg-red-500 hover:text-white hover:border-red-500 transition">

                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                        Hapus

                                    </button>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="4" class="text-center py-20">

                                <div class="inline-flex flex-col items-center gap-3">

                                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center">
                                        <i class="fa-solid fa-building text-3xl text-gray-300"></i>
                                    </div>

                                    <span class="text-gray-400 text-sm font-medium">
                                        Belum ada perusahaan tersedia
                                    </span>

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <!-- FOOTER -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80">
                <span class="text-xs text-gray-400">
                    Total {{ count($perusahaan) }} perusahaan terdaftar
                </span>
            </div>

        </div>

    </div>

    <!-- MODAL DETAIL -->
    <div id="detailModal"
        class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[999]">

        <div class="bg-white w-[430px] rounded-2xl shadow-xl p-6 relative">

            <!-- CLOSE -->
            <button onclick="closeDetail()"
                class="absolute top-3 right-4 text-gray-400 hover:text-red-500">

                <i class="fa-solid fa-xmark text-lg"></i>

            </button>

            <h3 class="text-xl font-bold text-[#1a4450] mb-5">
                Detail Perusahaan
            </h3>

            <div class="space-y-4 text-sm">

                <div>
                    <div class="text-gray-400 text-xs mb-1">Nama Perusahaan</div>
                    <div class="font-semibold text-[#1a4450]" id="detailNama"></div>
                </div>

                <div>
                    <div class="text-gray-400 text-xs mb-1">Email</div>
                    <div class="font-semibold text-[#1a4450]" id="detailEmail"></div>
                </div>

                <div>
                    <div class="text-gray-400 text-xs mb-1">Telepon</div>
                    <div class="font-semibold text-[#1a4450]" id="detailTelepon"></div>
                </div>

                <div>
                    <div class="text-gray-400 text-xs mb-1">Alamat</div>
                    <div class="font-semibold text-[#1a4450]" id="detailAlamat"></div>
                </div>

                <div>
                    <div class="text-gray-400 text-xs mb-1">Status</div>
                    <div class="font-semibold text-[#1a4450]" id="detailStatus"></div>
                </div>

            </div>

        </div>

    </div>

    <!-- MODAL DELETE -->
    <div id="deleteModal"
        class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[999]">

        <div class="bg-white w-[360px] rounded-2xl shadow-xl p-6">

            <h3 class="text-lg font-bold text-[#1a4450] mb-2">
                Konfirmasi Hapus
            </h3>

            <p class="text-sm text-gray-500 mb-5">
                Yakin ingin menghapus perusahaan ini?
            </p>

            <div class="flex justify-end gap-3">

                <button onclick="closeDelete()"
                    class="px-4 py-2 rounded-xl bg-gray-100 text-gray-600 text-sm font-bold">

                    Batal

                </button>

                <a href=""
                    id="deleteLink"
                    class="px-4 py-2 rounded-xl bg-red-500 text-white text-sm font-bold hover:bg-red-600 transition">

                    Ya, Hapus

                </a>

            </div>

        </div>

    </div>

    <!-- MODAL STATUS -->
    <div id="statusModal"
        class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[999]">

        <div class="bg-white w-[360px] rounded-2xl shadow-xl p-6">

            <h3 class="text-lg font-bold text-[#1a4450] mb-2">
                Ubah Status Perusahaan
            </h3>

            <p class="text-sm text-gray-500 mb-5">
                Yakin ingin mengubah status perusahaan ini?
            </p>

            <div class="flex justify-end gap-3">

                <button onclick="closeStatus()"
                    class="px-4 py-2 rounded-xl bg-gray-100 text-gray-600 text-sm font-bold">

                    Batal

                </button>

                <a href=""
                    id="statusLink"
                    class="px-4 py-2 rounded-xl bg-[#2d7f6a] text-white text-sm font-bold hover:opacity-90 transition">

                    Ya, Ubah

                </a>

            </div>

        </div>

    </div>

    <!-- SCRIPT -->
    <script>

        // DETAIL
        function openDetail(nama, email, telepon, alamat, status)
        {
            document.getElementById('detailNama').innerText = nama;
            document.getElementById('detailEmail').innerText = email;
            document.getElementById('detailTelepon').innerText = telepon;
            document.getElementById('detailAlamat').innerText = alamat;
            document.getElementById('detailStatus').innerText = status;

            document.getElementById('detailModal')
                .classList.remove('hidden');

            document.getElementById('detailModal')
                .classList.add('flex');
        }

        function closeDetail()
        {
            document.getElementById('detailModal')
                .classList.remove('flex');

            document.getElementById('detailModal')
                .classList.add('hidden');
        }

        // DELETE
        function openDelete(url)
        {
            document.getElementById('deleteLink').href = url;

            document.getElementById('deleteModal')
                .classList.remove('hidden');

            document.getElementById('deleteModal')
                .classList.add('flex');
        }

        function closeDelete()
        {
            document.getElementById('deleteModal')
                .classList.remove('flex');

            document.getElementById('deleteModal')
                .classList.add('hidden');
        }

        // STATUS
        function openStatus(url)
        {
            document.getElementById('statusLink').href = url;

            document.getElementById('statusModal')
                .classList.remove('hidden');

            document.getElementById('statusModal')
                .classList.add('flex');
        }

        function closeStatus()
        {
            document.getElementById('statusModal')
                .classList.remove('flex');

            document.getElementById('statusModal')
                .classList.add('hidden');
        }

    </script>

    </main>
</div>

<!-- SIDEBAR SCRIPT -->
<script>
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
</script>

<!-- MODAL UBAH PASSWORD -->
<div id="changePasswordModal" style="display:none" class="fixed inset-0 bg-black/40 items-center justify-center z-[999]">
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
        <form method="POST" action="{{ route('admin.users.update.password', Auth::user()->id) }}">
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

<script>
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

function toggleNotif() { document.getElementById('notifDropdown').classList.toggle('hidden'); }
function togglePD() { document.getElementById('pdd').classList.toggle('hidden'); }
function closePD() { document.getElementById('pdd').classList.add('hidden'); }

function openChangePasswordModal() {
    closePD();
    document.getElementById('changePasswordModal').style.display = 'flex';
}
function closeChangePasswordModal() {
    document.getElementById('changePasswordModal').style.display = 'none';
}

function konfirmasiLogout() {
    if (confirm('Apakah Anda yakin ingin keluar?')) {
        window.location.href = '{{ route("logout") }}';
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

