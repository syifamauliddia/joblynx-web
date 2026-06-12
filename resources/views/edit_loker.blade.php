<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Lowongan - JOBLYNX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#f8fbf9] min-h-screen text-gray-800 pb-20">

    <nav class="bg-white/90 backdrop-blur-md border-b border-gray-100 sticky top-0 z-[100] px-16 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-2">
            <h1 class="text-2xl font-extrabold text-[#1f4e5a] tracking-tight"><span class="text-[#2d7f6a]"><i class="fa-solid fa-arrow-trend-up"></i> JOB</span>LYNX</h1>
        </div>
        <div class="flex items-center gap-8 font-semibold text-sm">
            <a href="{{ route('dashboard') }}" class="hover:text-[#2d7f6a] transition text-gray-500">Kembali ke Dashboard</a>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-8 pt-12">
        <div class="mb-10 text-center">
            <h2 class="text-4xl font-extrabold text-[#1a4450] mb-3">Edit Lowongan</h2>
            <p class="text-gray-500 italic font-medium">Perbarui informasi lowongan kerja Anda di sini.</p>
        </div>

        {{-- Menampilkan Error Validasi Laravel --}}
        @if ($errors->any())
            <div class='bg-red-100 text-red-700 p-4 rounded-2xl mb-6 text-sm font-bold border border-red-200 shadow-sm'>
                <i class='fa-solid fa-triangle-exclamation'></i> Gagal! Mohon periksa kembali inputan Anda.
            </div>
        @endif

        {{-- Form diarahkan ke Route Name 'update.loker' --}}
        <form method="POST" action="{{ route('update.loker', $data_loker->id) }}" class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100 relative">
            @csrf
            {{-- Laravel membutuhkan Spoofing Method PUT karena browser hanya mendukung GET/POST --}}
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-widest mb-2.5">Posisi Pekerjaan</label>
                    <input type="text" name="posisi" value="{{ old('posisi', $data_loker->posisi) }}" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-semibold text-[#1a4450]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-widest mb-2.5">Status Lowongan</label>
                    <select name="status_loker" class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-semibold cursor-pointer text-[#1a4450]">
                        <option value="Aktif" {{ $data_loker->status_loker == 'Aktif' ? 'selected' : '' }}>Aktif (Muncul di Beranda)</option>
                        <option value="Tutup" {{ $data_loker->status_loker == 'Tutup' ? 'selected' : '' }}>Tutup (Sembunyikan)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-widest mb-2.5">Lokasi Kerja</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $data_loker->lokasi) }}" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-semibold text-[#1a4450]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-widest mb-2.5">Tipe Pekerjaan</label>
                    <select name="tipe_pekerjaan" class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-semibold text-[#1a4450]">
                        @php $types = ['Full Time', 'Part Time', 'Internship', 'Contract']; @endphp
                        @foreach($types as $t)
                            <option value="{{ $t }}" {{ $data_loker->tipe_pekerjaan == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 p-6 bg-[#f8fbf9] rounded-3xl border border-[#2d7f6a]/10">
                <div>
                    <label class="block text-xs font-medium text-[#1a4450] uppercase tracking-widest mb-2.5">Gaji Minimal</label>
                    <input type="number" name="gaji_min" value="{{ old('gaji_min', $data_loker->gaji_min) }}" class="w-full px-5 py-3 rounded-2xl bg-white border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#1a4450] uppercase tracking-widest mb-2.5">Gaji Maksimal</label>
                    <input type="number" name="gaji_max" value="{{ old('gaji_max', $data_loker->gaji_max) }}" class="w-full px-5 py-3 rounded-2xl bg-white border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-semibold">
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-widest mb-2.5">Syarat Keahlian</label>
                <input type="text" name="syarat_skill" value="{{ old('syarat_skill', $data_loker->syarat_skill) }}" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-semibold">
            </div>

            <div class="mb-10">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-widest mb-2.5">Deskripsi Pekerjaan</label>
                <textarea name="deskripsi" rows="6" required class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:border-[#2d7f6a] outline-none transition font-medium leading-relaxed">{{ old('deskripsi', $data_loker->deskripsi) }}</textarea>
            </div>

            <div class="mt-10">
                <button type="submit" class="w-full bg-[#1a4450] text-white py-5 rounded-[1.5rem] font-extrabold shadow-xl hover:bg-[#2d7f6a] transition-all flex items-center justify-center gap-4 text-xl">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Lowongan
                </button>
            </div>
        </form>
    </div>
</body>
</html>