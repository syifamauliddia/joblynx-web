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
                            <p class="text-sm font-semibold">{{ !empty($data->pendidikan) ? $data->pendidikan : '-' }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Telepon</p>
                            <p class="text-sm font-semibold">{{ !empty($data->no_hp) ? $data->no_hp : '-' }}</p>
                        </div>

                        @if (!empty($data->cv_file))
                            <div class="p-3 bg-green-50 rounded-xl border border-green-200">
                                <p class="text-[10px] text-green-600 font-bold uppercase mb-2">Curriculum Vitae</p>
                                <a href="{{ asset('uploads/' . $data->cv_file) }}" target="_blank"
                                    class="flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white py-2 rounded-xl text-sm font-bold transition">
                                    <i class="fa-solid fa-file-pdf"></i> Lihat CV
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- PANEL KEPUTUSAN — tombol per status, Interview & Diterima buka modal --}}
                <div class="bg-[#1a4450] p-6 rounded-3xl text-white shadow-xl">
                    <h3 class="font-bold mb-4 text-sm">
                        <i class="fa-solid fa-gavel mr-2 text-[#59a896]"></i> Berikan Keputusan
                    </h3>

                    {{-- Form tersembunyi, disubmit via JS --}}
                    <form id="formDetailStatus" action="{{ route('update.status') }}" method="POST">
                        @csrf
                        <input type="hidden" name="app_id" value="{{ $data->application_id }}">
                        <input type="hidden" name="new_status" id="detailNewStatus">
                        <input type="hidden" name="lokasi_interview" id="detailLokasi">
                        <input type="hidden" name="tanggal_interview" id="detailTanggal">
                        <input type="hidden" name="jam_interview" id="detailJam">
                        <input type="hidden" name="pesan_tambahan" id="detailPesan">
                    </form>

                    <div class="space-y-2">
                        <button onclick="triggerDetailStatus('Diproses')"
                            class="w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition
                                {{ $data->status == 'Diproses' ? 'bg-yellow-400 text-[#1a4450]' : 'bg-white/10 hover:bg-yellow-400 hover:text-[#1a4450]' }}">
                            <i class="fa-solid fa-spinner mr-2"></i> Review (Proses)
                        </button>
                        <button onclick="triggerDetailStatus('Interview')"
                            class="w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition
                                {{ $data->status == 'Interview' ? 'bg-blue-400 text-white' : 'bg-white/10 hover:bg-blue-400 hover:text-white' }}">
                            <i class="fa-solid fa-calendar-check mr-2"></i> Undang Interview
                        </button>
                        <button onclick="triggerDetailStatus('Diterima')"
                            class="w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition
                                {{ $data->status == 'Diterima' ? 'bg-[#59a896] text-white' : 'bg-white/10 hover:bg-[#59a896] hover:text-white' }}">
                            <i class="fa-solid fa-check-circle mr-2"></i> Terima Pelamar
                        </button>
                        <button onclick="triggerDetailStatus('Ditolak')"
                            class="w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition
                                {{ $data->status == 'Ditolak' ? 'bg-red-500 text-white' : 'bg-white/10 hover:bg-red-500 hover:text-white' }}">
                            <i class="fa-solid fa-circle-xmark mr-2"></i> Tolak Pelamar
                        </button>
                    </div>

                    {{-- Label status aktif saat ini --}}
                    <div class="mt-4 pt-4 border-t border-white/10 text-center">
                        <p class="text-[10px] text-white/50 uppercase tracking-wider font-bold mb-1">Status Saat Ini</p>
                        <span class="text-sm font-black text-[#59a896]">{{ $data->status }}</span>
                    </div>
                </div>
            </div>

            <div class="md:w-2/3 space-y-6">
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-[#1a4450] mb-6 border-b pb-4">Resume Digital & Skills</h3>

                    <div class="mb-8">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-3 tracking-wider">Ringkasan Pengalaman</p>
                        <p class="text-sm text-gray-600 leading-relaxed text-justify">
                            {!! !empty($data->pengalaman) ? nl2br(e($data->pengalaman)) : 'Pelamar belum mengisi ringkasan pengalaman.' !!}
                        </p>
                    </div>

                    <p class="text-xs font-bold text-gray-400 uppercase mb-4 tracking-wider">Penguasaan Skill</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($skills_arr as $name => $val)
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div class="flex justify-between text-xs font-bold mb-2 text-gray-700">
                                    <span>{{ $name }}</span><span>{{ $val }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 h-1.5 rounded-full">
                                    <div class="bg-[#59a896] h-1.5 rounded-full" style="width: {{ $val }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-400 uppercase mb-4 tracking-wider">Pesan Motivasi (Cover Letter)</h3>
                    <div class="p-6 bg-[#f8fbf9] rounded-2xl border-l-4 border-[#59a896] italic text-gray-700 text-sm leading-relaxed shadow-inner">
                        "{{ !empty($data->pesan) ? $data->pesan : 'Tidak ada pesan khusus dari pelamar.' }}"
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL POPUP CUSTOM PESAN - DETAIL PELAMAR ===== --}}
    <div id="modalDetail" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 p-8">
            <div class="flex items-center gap-3 mb-6">
                <div id="detailModalIcon" class="w-10 h-10 rounded-full flex items-center justify-center text-white text-lg"></div>
                <h3 id="detailModalTitle" class="text-lg font-extrabold text-[#1a4450]"></h3>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">📍 Lokasi</label>
                    <input type="text" id="inputLokasi"
                        class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#59a896] transition"
                        placeholder="Contoh: Kantor Pusat, Jl. Sudirman No.10">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">📅 Tanggal</label>
                        <input type="date" id="inputTanggal"
                            class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#59a896] transition">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">🕐 Jam</label>
                        <input type="time" id="inputJam"
                            class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#59a896] transition">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">📝 Pesan Tambahan</label>
                    <textarea id="inputPesan" rows="3"
                        class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#59a896] transition resize-none"
                        placeholder="Tulis instruksi tambahan..."></textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button onclick="tutupModalDetail()"
                    class="flex-1 border border-gray-200 text-gray-500 hover:bg-gray-50 py-2.5 rounded-xl font-bold text-sm transition">
                    Batal
                </button>
                <button id="detailSubmitBtn" onclick="submitDetailStatus()"
                    class="flex-1 bg-[#59a896] hover:bg-[#2d7f6a] text-white py-2.5 rounded-xl font-bold text-sm transition shadow-lg">
                    Kirim Notifikasi
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentDetailStatus = '';

        function triggerDetailStatus(status) {
            currentDetailStatus = status;

            if (status === 'Interview' || status === 'Diterima') {
                const icon = document.getElementById('detailModalIcon');
                const title = document.getElementById('detailModalTitle');
                const btn = document.getElementById('detailSubmitBtn');

                document.getElementById('inputLokasi').value = '';
                document.getElementById('inputTanggal').value = '';
                document.getElementById('inputJam').value = '';
                document.getElementById('inputPesan').value = '';

                if (status === 'Interview') {
                    icon.className = 'w-10 h-10 rounded-full flex items-center justify-center text-white text-lg bg-blue-500';
                    icon.innerHTML = '<i class="fa-solid fa-calendar-check"></i>';
                    title.textContent = 'Undang Interview';
                    document.getElementById('inputLokasi').placeholder = 'Contoh: Kantor Pusat, Jl. Sudirman No.10';
                    document.getElementById('inputPesan').placeholder = 'Contoh: Harap membawa dokumen asli dan berpakaian rapi.';
                    btn.className = 'flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2.5 rounded-xl font-bold text-sm transition shadow-lg';
                } else {
                    icon.className = 'w-10 h-10 rounded-full flex items-center justify-center text-white text-lg bg-[#59a896]';
                    icon.innerHTML = '<i class="fa-solid fa-check-circle"></i>';
                    title.textContent = 'Terima Pelamar';
                    document.getElementById('inputLokasi').placeholder = 'Contoh: HRD Lt.2, Gedung A';
                    document.getElementById('inputPesan').placeholder = 'Contoh: Harap konfirmasi kehadiran maksimal H-1.';
                    btn.className = 'flex-1 bg-[#59a896] hover:bg-[#2d7f6a] text-white py-2.5 rounded-xl font-bold text-sm transition shadow-lg';
                }

                const modal = document.getElementById('modalDetail');
                modal.classList.remove('hidden');
                modal.classList.add('flex');

            } else {
                // Diproses / Ditolak langsung submit tanpa modal
                document.getElementById('detailNewStatus').value = status;
                document.getElementById('formDetailStatus').submit();
            }
        }

        function submitDetailStatus() {
            document.getElementById('detailNewStatus').value = currentDetailStatus;
            document.getElementById('detailLokasi').value = document.getElementById('inputLokasi').value;
            document.getElementById('detailTanggal').value = document.getElementById('inputTanggal').value;
            document.getElementById('detailJam').value = document.getElementById('inputJam').value;
            document.getElementById('detailPesan').value = document.getElementById('inputPesan').value;
            document.getElementById('formDetailStatus').submit();
        }

        function tutupModalDetail() {
            const modal = document.getElementById('modalDetail');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('modalDetail').addEventListener('click', function(e) {
            if (e.target === this) tutupModalDetail();
        });
    </script>
</body>

</html>