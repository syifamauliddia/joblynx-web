<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Admin - JOBLYNX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#f8fbf9] min-h-screen pb-20">

    <nav class="bg-white/90 backdrop-blur-md border-b border-gray-100 sticky top-0 z-[100] px-8 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-2">
            <h1 class="text-2xl font-extrabold text-[#1f4e5a] tracking-tight"><span class="text-[#2d7f6a]"><i class="fa-solid fa-arrow-trend-up"></i> JOB</span>LYNX</h1>
        </div>
        <div class="flex items-center gap-8 font-semibold text-sm text-[#1f4e5a]">
            <a href="{{ url('dashboard') }}" class="hover:text-[#2d7f6a] transition">Dashboard</a>
            <a href="{{ url('pasang-lowongan') }}" class="hover:text-[#2d7f6a] transition">Pasang Loker</a>
            <div class="flex items-center gap-4 border-l border-gray-200 pl-4">
                <span class="font-bold text-[#1a4450]">{{ explode(' ', $nama_user)[0] }}</span>
                <a href="javascript:void(0)" onclick="konfirmasiLogout()" class="bg-red-50 text-red-500 px-4 py-2 rounded-xl text-xs font-bold hover:bg-red-500 hover:text-white transition-all shadow-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-6 pt-12">
        <div class="mb-10 text-center md:text-left">
            <h2 class="text-4xl font-extrabold text-[#1a4450] mb-2">Profil Instansi & HRD</h2>
            <p class="text-gray-500 font-medium">Kelola informasi perusahaan Anda untuk membangun branding yang kuat.</p>
        </div>

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class='bg-[#dcfce7] text-[#2d7f6a] p-4 rounded-2xl mb-6 text-sm font-bold flex items-center gap-2 border border-[#2d7f6a]/20 shadow-sm'>
                {!! session('success') !!}
            </div>
        @endif

        @if(session('success_foto'))
            <div class='bg-green-100 text-[#1f5c4d] p-4 rounded-2xl mb-6 text-sm font-bold flex items-center gap-2 border border-green-200 shadow-sm'>
                {!! session('success_foto') !!}
            </div>
        @endif

        {{-- Notifikasi Error Validasi --}}
        @if ($errors->any())
            <div class='bg-red-100 text-red-700 p-4 rounded-2xl mb-6 text-sm font-bold flex items-center gap-2 border border-red-200 shadow-sm'>
                <i class='fa-solid fa-triangle-exclamation'></i> Gagal! {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('profil.admin.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 text-center sticky top-32">
                    <div class="relative inline-block mb-4">
                        <div class="w-28 h-28 mx-auto rounded-full overflow-hidden bg-[#dcfce7] border-4 border-white shadow-md flex items-center justify-center">
                            @if(!empty($user->foto_profil))
                                <img src="{{ asset('uploads/' . $user->foto_profil) }}?t={{ time() }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-4xl font-black text-[#2d7f6a]">{{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}</span>
                            @endif
                        </div>
                        <label class="absolute bottom-0 right-0 bg-[#2d7f6a] hover:bg-[#1f5c4d] text-white w-8 h-8 rounded-full border-2 border-white flex items-center justify-center cursor-pointer hover:scale-110 transition shadow-sm">
                            <i class="fa-solid fa-camera text-[10px]"></i>
                            <input type="file" name="foto_profil" class="hidden" accept="image/*" onchange="this.form.submit()">
                        </label>
                    </div>
                    
                    <h2 class="text-xl font-bold text-[#1a4450]">{{ $user->nama_lengkap }}</h2>
                    <p class="text-xs text-[#2d7f6a] font-black uppercase tracking-widest mt-1 mb-4">HR Administrator</p>
                    
                    @if(!empty($user->foto_profil))
                        <button type="submit" name="hapus_foto" class="text-red-400 text-[10px] font-bold uppercase hover:text-red-600 hover:underline mb-4 transition" onclick="return confirm('Hapus foto profil?')">Hapus Foto</button>
                    @endif

                    <div class="text-left space-y-3 pt-4 border-t border-gray-50">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <i class="fa-solid fa-envelope text-gray-400 text-xs"></i>
                            <span class="text-xs font-semibold text-gray-600 truncate">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-[#1a4450] p-8 rounded-[2rem] text-white shadow-xl relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 opacity-10">
                        <i class="fa-solid fa-chart-simple text-9xl"></i>
                    </div>
                    <h3 class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-6 flex items-center gap-2 relative z-10">
                        <i class="fa-solid fa-chart-pie"></i> Ringkasan Aktivitas
                    </h3>
                    <div class="space-y-5 relative z-10 font-bold">
                        <div class="flex justify-between items-center">
                            <span class="text-sm opacity-80 font-normal">Loker Aktif</span>
                            <span class="text-lg">{{ $total_loker }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm opacity-80 font-normal">Total Pelamar</span>
                            <span class="text-lg">{{ $total_pelamar }}</span>
                        </div>
                        <div class="flex justify-between items-center text-orange-400">
                            <span class="text-sm">Perlu Review</span>
                            <span class="text-xl">{{ $perlu_review }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-[#2d7f6a]/5 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <h3 class="text-2xl font-bold text-[#1a4450] mb-8 flex items-center gap-3 relative z-10">
                        <i class="fa-solid fa-sliders text-[#2d7f6a]"></i> Pengaturan Akun & Instansi
                    </h3>
                    
                    <div class="space-y-7 relative z-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-widest mb-2.5">Nama HRD / Admin</label>
                                <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-semibold text-[#1a4450]">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-widest mb-2.5">Nama Perusahaan</label>
                                <input type="text" name="nama_perusahaan" value="{{ $user->nama_perusahaan }}" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-semibold text-[#1a4450]" placeholder="Contoh: PT Teknologi Bangsa">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-widest mb-2.5">Website Resmi Perusahaan</label>
                            <div class="relative">
                                <i class="fa-solid fa-link absolute left-5 top-1/2 -translate-y-1/2 text-[#2d7f6a]"></i>
                                <input type="url" name="website" value="{{ $user->website_perusahaan }}" class="w-full pl-12 pr-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-semibold text-[#1a4450]" placeholder="https://perusahaan.com">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-widest mb-2.5">Tentang Perusahaan (Profil)</label>
                            <textarea name="bio_perusahaan" rows="6" class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-medium text-[#1a4450] leading-relaxed" placeholder="Ceritakan visi, misi, dan budaya perusahaanmu di sini...">{{ $user->bio_perusahaan }}</textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" name="update_profil_admin" class="w-full bg-[#1a4450] text-white font-extrabold py-5 rounded-[1.5rem] transition-all shadow-xl hover:bg-[#2d7f6a] hover:shadow-2xl hover:-translate-y-1 flex items-center justify-center gap-3 text-lg tracking-wide">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <script>
        function konfirmasiLogout() {
            if (confirm('Apakah Anda yakin ingin keluar dari JOBLYNX?')) { window.location.href = '{{ url("logout") }}'; }
        }
    </script>
</body>
</html>