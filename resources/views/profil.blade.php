<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Profil - JOBLYNX</title>
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

<script>
function previewCV(input){

    const preview = document.getElementById('cvPreview');
    const cvName = document.getElementById('cvName');

    if(input.files.length > 0){

        cvName.textContent = input.files[0].name;

        preview.classList.remove('hidden');
    }
}
</script>

<body class="bg-[#f8fbf9] min-h-screen text-gray-800 pb-20">

    <nav
        class="bg-white/90 backdrop-blur-md border-b border-gray-100 sticky top-0 z-[100] px-16 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-2">
            <h1 class="text-2xl font-extrabold text-[#1f4e5a] tracking-tight"><span class="text-[#2d7f6a]"><i
                        class="fa-solid fa-arrow-trend-up"></i> JOB</span>LYNX</h1>
        </div>
        <div class="flex items-center gap-8 font-semibold text-sm">
            <a href="{{ url('beranda') }}" class="hover:text-[#2d7f6a] transition">Beranda</a>
            <a href="{{ url('dashboard') }}" class="hover:text-[#2d7f6a] transition">Dashboard</a>
            <div class="flex items-center gap-4 border-l border-gray-200 pl-4">
                <a href="{{ url('profil') }}" class="flex items-center gap-2 group">
                    <div
                        class="w-9 h-9 bg-[#dcfce7] text-[#2d7f6a] rounded-full flex items-center justify-center font-bold border border-[#2d7f6a]/30 overflow-hidden shadow-sm group-hover:ring-2 group-hover:ring-[#2d7f6a] transition-all">
                        @if (!empty($user->foto_profil))
                            <img src="{{ asset('uploads/' . $user->foto_profil) }}?t={{ time() }}" alt="Profil"
                                class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($nama_user ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                    <span class="text-gray-600 text-sm hidden lg:inline">Halo, <span
                            class="font-bold text-[#1a4450]">{{ explode(' ', $nama_user ?? 'User')[0] }}</span>!</span>
                </a>
                <a href="{{ url('logout') }}"
                    class="ml-2 bg-red-50 text-red-500 px-4 py-2 rounded-xl text-xs font-bold shadow-sm hover:bg-red-500 hover:text-white transition-all">Logout</a>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 pt-7">
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-3xl font-extrabold text-[#1a4450] tracking-tight">
                    {{ $role == 'hr' ? 'Profil Perusahaan' : 'Profil & CV Digital' }}</h2>
                <p class="text-gray-500 mt-1 font-medium italic">
                    {{ $role == 'hr' ? 'Kelola data instansi rekruter Anda.' : 'Lengkapi data dan unggah CV PDF terbaikmu.' }}
                </p>
            </div>
            @if ($role == 'user')
                <div class="text-center bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <div
                        class="w-10 h-10 rounded-full border-2 flex items-center justify-center font-bold text-xs {{ $skor == 100 ? 'border-green-400 text-green-500 bg-green-50' : 'border-orange-400 text-orange-500 bg-orange-50' }}">
                        {{ $skor }}%
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">Lengkap</p>
                </div>
            @endif
        </div>

        {{-- Menampilkan Pesan Sukses / Error --}}
        @if (session('success'))
            <div
                class='bg-[#dcfce7] text-[#2d7f6a] p-4 rounded-2xl mb-6 text-sm font-bold border border-[#2d7f6a]/20 shadow-sm'>
                {!! session('success') !!}
            </div>
        @endif

        @if ($errors->any())
            <div class='bg-red-100 text-red-700 p-4 rounded-2xl mb-6 text-sm font-bold border border-red-200 shadow-sm'>
                <i class='fa-solid fa-triangle-exclamation'></i> Gagal menyimpan: {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ url('profil') }}" method="POST" enctype="multipart/form-data"
            class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @csrf
            <div class="md:col-span-1">
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 text-center sticky top-32">
                    <div class="relative inline-block mb-6">
                        <div
                            class="w-32 h-32 mx-auto rounded-full overflow-hidden bg-[#dcfce7] border-4 border-white shadow-lg flex items-center justify-center">
                            @if (!empty($user->foto_profil))
                                <img src="{{ asset('uploads/' . $user->foto_profil) }}?t={{ time() }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span
                                    class="text-4xl font-black text-[#2d7f6a]">{{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}</span>
                            @endif
                        </div>
                        <label
                            class="absolute bottom-0 right-0 bg-[#2d7f6a] text-white w-9 h-9 rounded-full border-2 border-white flex items-center justify-center cursor-pointer hover:scale-110 transition shadow-md">
                            <i class="fa-solid fa-camera text-xs"></i>
                            <input type="file" name="foto_profil" class="hidden" accept="image/*"
                                onchange="this.form.submit()">
                        </label>
                    </div>
                    <h2 class="text-xl font-bold text-[#1a4450]">{{ $user->nama_lengkap }}</h2>
                    <p class="text-xs text-[#2d7f6a] font-black uppercase tracking-widest mt-1 mb-4">
                        {{ $role == 'hr' ? 'HR Administrator' : 'Job Seeker' }}</p>

                    @if (!empty($user->foto_profil))
                        <button type="submit" name="hapus_foto"
                            class="text-red-400 text-[10px] font-bold uppercase hover:underline"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus foto profil ini?')">Hapus
                            Foto</button>
                    @endif

                    <div class="text-left space-y-3 pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <i class="fa-solid fa-envelope text-gray-400 text-xs"></i>
                            <span class="text-xs font-semibold text-gray-600 truncate">{{ $user->email }}</span>
                        </div>
                        @if ($role == 'user' && !empty($profile->cv_file))
                            <a href="{{ asset('uploads/' . $profile->cv_file) }}" target="_blank"
                                class="flex items-center gap-3 p-3 bg-[#dcfce7] text-[#2d7f6a] rounded-xl hover:bg-[#2d7f6a] hover:text-white transition-all">
                                <i class="fa-solid fa-file-pdf text-xs"></i>
                                <span class="text-xs font-bold uppercase tracking-wider">Lihat CV Saya</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-6">
                <div
                    class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-gray-100 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-48 h-48 bg-[#2d7f6a]/5 rounded-full blur-3xl pointer-events-none">
                    </div>

                    <div class="space-y-6 relative z-10">
                        <div>
                            <label
                                class="block text-xs font-medium text-gray-400 uppercase tracking-[0.15em] mb-2.5">Nama
                                Lengkap</label>
                            <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}" required
                                class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-semibold text-[#1a4450]">
                        </div>

                        {{-- ================= HR SECTION ================= --}}
                        @if ($role == 'hr')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-400 uppercase tracking-[0.15em] mb-2.5">
                                        Nama Perusahaan
                                    </label>
                                    <input type="text" name="nama_perusahaan"
                                        value="{{ $profile->nama_perusahaan ?? '' }}"
                                        class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-semibold text-[#1a4450]">
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-400 uppercase tracking-[0.15em] mb-2.5">
                                        Website Resmi
                                    </label>
                                    <input type="url" name="website_perusahaan"
                                        value="{{ $profile->website_perusahaan ?? '' }}"
                                        class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-semibold text-[#1a4450]">
                                </div>
                            </div>

                            <div class="mt-6">
                                <label
                                    class="block text-xs font-medium text-gray-400 uppercase tracking-[0.15em] mb-2.5">
                                    Bio Perusahaan
                                </label>

                                <textarea name="bio_perusahaan" rows="5" class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100">{{ $profile->bio_perusahaan ?? '' }}</textarea>
                            </div>
                        @endif


                        {{-- ================= USER SECTION (INI YANG KAMU HILANGKAN) ================= --}}
                        @if ($role == 'user')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase mb-2.5">
                                        No HP / WhatsApp
                                    </label>
                                    <input type="text" name="no_hp" value="{{ $profile->no_hp ?? '' }}"
                                        class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase mb-2.5">
                                        Pendidikan
                                    </label>
                                    <input type="text" name="pendidikan" value="{{ $profile->pendidikan ?? '' }}"
                                        class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border">
                                </div>
                            </div>

                            <div class="mt-6">
                                <label class="block text-xs font-medium text-gray-400 uppercase mb-2.5">
                                    Alamat
                                </label>

                                <textarea name="alamat" rows="3"
                                    class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none transition"
                                    placeholder="Masukkan alamat lengkap">{{ $profile->alamat ?? '' }}</textarea>
                            </div>

                            <div class="mt-6">
                                <label class="block text-xs font-medium text-gray-400 uppercase mb-2.5">
                                    Pengalaman
                                </label>
                                <textarea name="pengalaman" rows="4" class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border">{{ $profile->pengalaman ?? '' }}</textarea>
                            </div>

                            <div class="mt-6">
    <label class="block text-xs font-medium text-gray-400 uppercase mb-3">
        CV Digital
    </label>

    {{-- CV SUDAH ADA --}}
    @if(!empty($profile->cv_file))

    <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-2xl">

        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                <i class="fa-solid fa-file-pdf text-red-500"></i>
            </div>

            <div>
                <p class="text-sm font-semibold text-[#1a4450]">
                    {{ basename($profile->cv_file) }}
                </p>
                <p class="text-xs text-green-600">
                    CV berhasil diunggah
                </p>
            </div>
        </div>

        <button
            type="submit"
            name="hapus_cv"
            onclick="return confirm('Yakin ingin menghapus CV ini?')"
            class="text-red-500 hover:text-red-700 text-sm font-semibold">
            Hapus
        </button>

    </div>

    {{-- BELUM ADA CV --}}
    @else

    <div class="border border-dashed border-gray-300 rounded-2xl p-4 bg-gray-50">

        <div class="flex items-center justify-between flex-wrap gap-3">

            <div class="flex items-center gap-3">
                <i class="fa-solid fa-file-pdf text-red-500 text-xl"></i>

                <div>
                    <p class="text-sm font-semibold text-gray-700">
                        Belum ada CV
                    </p>

                    <p class="text-xs text-gray-500">
                        PDF maksimal 5 MB
                    </p>
                </div>
            </div>

            <label class="cursor-pointer bg-[#1a4450] text-white px-4 py-2 rounded-xl text-sm hover:bg-[#2d7f6a] transition">

                Pilih File

                <input
                    type="file"
                    name="cv_file"
                    accept=".pdf"
                    class="hidden"
                    onchange="previewCV(this)">
            </label>

        </div>

        {{-- Preview file sebelum simpan --}}
        <div id="cvPreview" class="hidden mt-3 p-3 bg-white border rounded-xl">

            <div class="flex items-center gap-2">
                <i class="fa-solid fa-file-pdf text-red-500"></i>

                <span id="cvName" class="text-sm font-medium text-gray-700"></span>
            </div>

        </div>

    </div>

    @endif
</div>
                        @endif

                        <div class="pt-4">
                            <button type="submit" name="simpan_profil"
                                class="w-full bg-[#1a4450] text-white py-5 rounded-[1.5rem] font-extrabold shadow-xl hover:bg-[#2d7f6a] hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 flex items-center justify-center gap-4 text-lg">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
