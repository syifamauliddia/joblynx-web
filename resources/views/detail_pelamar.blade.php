<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Review Pelamar - {{ $data->nama_lengkap }}</title>
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

<body class="bg-[#f0f4f3] p-6 md:p-12 pb-20">
    <div class="max-w-6xl mx-auto">

        <a href="{{ url('dashboard') }}"
            class="inline-block mb-6 text-gray-500 hover:text-[#59a896] font-bold text-sm transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>

        <div class="flex flex-col md:flex-row gap-8">
            <div class="md:w-1/3 space-y-6">
                <div class="bg-white p-8 rounded-3xl shadow-sm text-center border border-gray-100">
                    <img src="{{ asset('uploads/' . (!empty($data->foto_profil) ? $data->foto_profil : 'default.png')) }}"
                        class="w-24 h-24 mx-auto rounded-full object-cover border-4 border-[#eefcf5] mb-4 shadow-md">
                    <h2 class="text-xl font-bold text-[#1a4450]">{{ $data->nama_lengkap }}</h2>
                    <p class="text-xs text-gray-400 mb-6">{{ $data->email }}</p>

                    <div class="space-y-3 text-left">
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Pendidikan</p>
                            <p class="text-sm font-semibold">{{ !empty($data->pendidikan) ? $data->pendidikan : '-' }}
                            </p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Telepon</p>
                            <p class="text-sm font-semibold">{{ !empty($data->no_hp) ? $data->no_hp : '-' }}</p>
                        </div>

                        @if(!empty($data->cv_file))
                            <div class="p-3 bg-green-50 rounded-xl border border-green-200">
                                
                                <p class="text-[10px] text-green-600 font-bold uppercase mb-2">
                                    Curriculum Vitae
                                </p>

                                <a href="{{ asset('uploads/' . $data->cv_file) }}"
                                target="_blank"
                                class="flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white py-2 rounded-xl text-sm font-bold transition">
                                    
                                    <i class="fa-solid fa-file-pdf"></i>
                                    Lihat CV
                                </a>
                            </div>
                            @endif
                    </div>
                </div>

                <div class="bg-[#1a4450] p-6 rounded-3xl text-white shadow-xl">
                    <h3 class="font-bold mb-4 text-sm"><i class="fa-solid fa-gavel mr-2 text-[#59a896]"></i> Berikan
                        Keputusan</h3>
                    <form action="{{ route('update.status') }}" method="POST" class="space-y-3">
                        @csrf
                        {{-- Pastikan ini aplikasi_id yang kita ambil dari select controller --}}
                        <input type="hidden" name="app_id" value="{{ $data->application_id }}">

                        <select name="new_status"
                            class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:bg-white focus:text-[#1a4450] cursor-pointer transition-all">
                            <option value="Diproses" {{ $data->status == 'Diproses' ? 'selected' : '' }}>Review (Proses)
                            </option>
                            <option value="Interview" {{ $data->status == 'Interview' ? 'selected' : '' }}>Undang
                                Interview</option>
                            <option value="Diterima" {{ $data->status == 'Diterima' ? 'selected' : '' }}>Terima Pelamar
                            </option>
                            <option value="Ditolak" {{ $data->status == 'Ditolak' ? 'selected' : '' }}>Tolak Pelamar
                            </option>
                        </select>

                        <button type="submit"
                            class="w-full bg-[#59a896] hover:bg-white hover:text-[#59a896] py-3 rounded-xl font-bold transition shadow-lg">
                            Update Status & Kirim Notif
                        </button>
                    </form>
                </div>
            </div>

            <div class="md:w-2/3 space-y-6">
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-[#1a4450] mb-6 border-b pb-4">Resume Digital & Skills</h3>

                    <div class="mb-8">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-3 tracking-wider">Ringkasan Pengalaman
                        </p>
                        <p class="text-sm text-gray-600 leading-relaxed text-justify">{!! !empty($data->pengalaman) ? nl2br(e($data->pengalaman)) : 'Pelamar belum mengisi ringkasan pengalaman.' !!}</p>
                    </div>

                    <p class="text-xs font-bold text-gray-400 uppercase mb-4 tracking-wider">Penguasaan Skill</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($skills_arr as $name => $val)
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div class="flex justify-between text-xs font-bold mb-2 text-gray-700">
                                    <span>{{ $name }}</span><span>{{ $val }}%</span></div>
                                <div class="w-full bg-gray-200 h-1.5 rounded-full">
                                    <div class="bg-[#59a896] h-1.5 rounded-full" style="width: {{ $val }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-400 uppercase mb-4 tracking-wider">Pesan Motivasi (Cover
                        Letter)</h3>
                    <div
                        class="p-6 bg-[#f8fbf9] rounded-2xl border-l-4 border-[#59a896] italic text-gray-700 text-sm leading-relaxed shadow-inner">
                        "{{ !empty($data->pesan) ? $data->pesan : 'Tidak ada pesan khusus dari pelamar.' }}"
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
