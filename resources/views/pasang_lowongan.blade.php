<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasang Lowongan - JOBLYNX</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>

<body class="bg-[#f8fbf9] min-h-screen pb-20">

{{-- NAVBAR (SAMA DENGAN PROFIL) --}}
<nav class="bg-white/90 backdrop-blur-md border-b border-gray-100 sticky top-0 z-[100] px-8 py-4 flex justify-between items-center shadow-sm">

    <h1 class="text-2xl font-extrabold text-[#1f4e5a]">
        <span class="text-[#2d7f6a]">
            <i class="fa-solid fa-arrow-trend-up"></i> JOB
        </span>LYNX
    </h1>

    <div class="flex items-center gap-8 font-semibold text-sm text-[#1f4e5a]">

        <a href="{{ route('beranda') }}" class="hover:text-[#2d7f6a] transition">Beranda</a>
        <a href="{{ route('dashboard') }}" class="hover:text-[#2d7f6a] transition">Dashboard</a>

        <div class="flex items-center gap-4 border-l border-gray-200 pl-4">
            <span class="font-bold text-[#1a4450]">
                {{ explode(' ', $nama_user ?? 'User')[0] }}
            </span>

            <a href="{{ route('logout') }}"
               class="bg-red-50 text-red-500 px-4 py-2 rounded-xl text-xs font-bold hover:bg-red-500 hover:text-white transition">
                Logout
            </a>
        </div>

    </div>

</nav>

{{-- CONTAINER --}}
<div class="max-w-5xl mx-auto px-6 pt-12">

    {{-- HEADER --}}
    <div class="mb-10 text-center md:text-left">
        <h2 class="text-4xl font-extrabold text-[#1a4450] mb-2">
            Buat Lowongan Baru
        </h2>
        <p class="text-gray-500 font-medium">
            Loker akan otomatis dipublish sesuai perusahaan kamu
        </p>
    </div>

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-2xl mb-6 font-bold border border-red-200 shadow-sm">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- FORM CARD (SAMA STYLE PROFIL) --}}
    <form method="POST"
          action="{{ route('store.loker') }}"
          class="bg-white p-10 md:p-12 rounded-[2.5rem] shadow-sm border border-gray-30 relative overflow-hidden">

        @csrf

        <div class="absolute top-0 right-0 w-48 h-48 bg-[#2d7f6a]/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-6">

            {{-- PERUSAHAAN --}}
            <div>
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-widest mb-2.5">
                    Perusahaan
                </label>

                <input type="text"
                       value="{{ $perusahaan->nama_perusahaan ?? 'Data perusahaan belum tersedia' }}"
                       readonly
                       class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-50 text-gray-500 font-bold">
            </div>

            {{-- POSISI + LOKASI --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-xs font-medium text-gray-400 uppercase mb-2.5">
                        Posisi
                    </label>

                    <input type="text"
                           name="posisi"
                           value="{{ old('posisi') }}"
                           required
                           class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none font-semibold text-[#1a4450]">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 uppercase mb-2.5">
                        Lokasi
                    </label>

                    <input type="text"
                           name="lokasi"
                           value="{{ old('lokasi') }}"
                           required
                           class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none font-semibold text-[#1a4450]">
                </div>

            </div>

            {{-- TIPE --}}
            <div>
                <label class="block text-xs font-medium text-gray-400 uppercase mb-2.5">
                    Tipe Pekerjaan
                </label>

                <select name="tipe_pekerjaan"
                        class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none font-semibold text-[#1a4450]">

                    <option>Full Time</option>
                    <option>Part Time</option>
                    <option>Internship</option>
                    <option>Contract</option>

                </select>
            </div>

            {{-- GAJI --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <input type="number"
                       name="gaji_min"
                       placeholder="Gaji Minimum"
                       class="px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100">

                <input type="number"
                       name="gaji_max"
                       placeholder="Gaji Maksimum"
                       class="px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100">

            </div>

            {{-- SKILL --}}
            <div>
                <label class="block text-xs font-medium text-gray-400 uppercase mb-2.5">
                    Skill
                </label>

                <input type="text"
                       name="syarat_skill"
                       value="{{ old('syarat_skill') }}"
                       required
                       placeholder="Laravel, Figma, PHP"
                       class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none font-semibold">
            </div>

            {{-- DESKRIPSI --}}
            <div>
                <label class="block text-xs font-medium text-gray-400 uppercase mb-2.5">
                    Deskripsi
                </label>

                <textarea name="deskripsi"
                          rows="6"
                          required
                          class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none font-medium leading-relaxed">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- BUTTON --}}
            <button type="submit"
                    class="w-full bg-[#1a4450] text-white py-5 rounded-[1.5rem] font-extrabold shadow-xl hover:bg-[#2d7f6a] hover:shadow-2xl hover:-translate-y-1 transition-all flex items-center justify-center gap-3 text-lg">

                <i class="fa-solid fa-paper-plane"></i>
                Publish Lowongan

            </button>

        </div>
    </form>

</div>

</body>
</html>