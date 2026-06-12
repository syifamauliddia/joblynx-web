<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Atur Keahlian - JOBLYNX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        input[type=range] { accent-color: #59a896; }
    </style>
</head>
<body class="bg-[#f8fbf9] min-h-screen py-12 px-4">
    
    <div class="max-w-3xl mx-auto bg-white p-8 md:p-12 rounded-[2.5rem] shadow-xl border border-gray-100 relative">
        <a href="{{ url('beranda') }}" class="absolute top-8 left-8 text-gray-400 hover:text-[#59a896] transition-all">
            <i class="fa-solid fa-circle-chevron-left text-3xl"></i>
        </a>

        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-[#1a4450] mb-2">Profil Keahlian</h2>
            <p class="text-gray-500 text-sm">Sesuaikan kemahiranmu agar sistem dapat mencarikan pekerjaan yang pas.</p>
        </div>

        {{-- PERBAIKAN: Menggunakan route() agar URL sesuai dengan web.php --}}
        <form method="POST" action="{{ route('simpan.skill') }}" class="space-y-10">
            @csrf
            
            <div>
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-list-check"></i> Skill Umum
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($all_default_skills as $skill)
                        @php
                            $is_checked = in_array($skill, $saved_skills_array);
                            $current_val = $is_checked ? $saved_percentages[$skill] : 50;
                            $safe_id = preg_replace('/[^a-zA-Z0-9]/', '', $skill);
                        @endphp
                        <div class="bg-white border-2 border-gray-50 p-4 rounded-2xl hover:border-[#59a896]/30 transition-all group">
                            <label class="flex items-center space-x-3 cursor-pointer mb-2">
                                <input type="checkbox" name="skills[]" value="{{ $skill }}" id="chk-{{ $safe_id }}"
                                    {{ $is_checked ? 'checked' : '' }} onchange="toggleSlider('{{ $safe_id }}')"
                                    class="w-5 h-5 text-[#59a896] rounded focus:ring-[#59a896]">
                                <span class="text-gray-700 font-bold">{{ $skill }}</span>
                            </label>
                            <div id="div-{{ $safe_id }}" class="{{ $is_checked ? '' : 'hidden' }} pl-8 mt-3">
                                <input type="range" name="persen[{{ $skill }}]" min="10" max="100" value="{{ $current_val }}" 
                                    oninput="document.getElementById('val-{{ $safe_id }}').innerText = this.value" class="w-full h-1.5 bg-gray-100 rounded-lg appearance-none cursor-pointer">
                                <div class="text-[10px] text-gray-400 mt-2 font-bold">KEMAHIRAN: <span id="val-{{ $safe_id }}" class="text-[#59a896]">{{ $current_val }}</span>%</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-[#f0f9f7] p-8 rounded-[2rem] border-2 border-dashed border-[#59a896]/20">
                <h3 class="text-sm font-black text-[#59a896] uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-star"></i> Skill Spesifik Lainnya
                </h3>
                <div id="custom-skill-container" class="space-y-4">
                    @foreach($custom_skills_data as $index => $c_skill)
                        @php $c_id = 'old-'.$index; @endphp
                        <div class="flex flex-col md:flex-row gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100 items-center" id="row-{{ $c_id }}">
                            <div class="flex-1 w-full">
                                <input type="text" name="custom_skill_name[]" value="{{ $c_skill['name'] }}" placeholder="Nama Skill" class="w-full px-4 py-2 rounded-xl border border-gray-100 text-sm font-bold outline-none focus:border-[#59a896]" required>
                            </div>
                            <div class="flex items-center gap-4 w-full md:w-48">
                                <input type="range" name="custom_skill_val[]" min="10" max="100" value="{{ $c_skill['val'] }}" oninput="this.nextElementSibling.innerText = this.value + '%'" class="flex-1 cursor-pointer">
                                <span class="text-xs font-black text-[#59a896] w-10">{{ $c_skill['val'] }}%</span>
                            </div>
                            <button type="button" onclick="document.getElementById('row-{{ $c_id }}').remove()" class="text-red-300 hover:text-red-500 transition-colors">
                                <i class="fa-solid fa-circle-xmark text-xl"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="tambahBarisSkill()" class="mt-6 w-full py-3 rounded-xl border-2 border-[#59a896] border-dashed text-[#59a896] font-bold text-sm hover:bg-[#59a896] hover:text-white transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus-circle"></i> Tambah Skill Custom
                </button>
            </div>

            <button type="submit" class="w-full bg-[#1a4450] text-white font-bold py-5 rounded-2xl transition-all shadow-lg hover:bg-[#59a896] hover:-translate-y-1 transform">
                Simpan Profil Keahlian
            </button>
        </form>
    </div>

    <script>
        function toggleSlider(id) {
            const checkbox = document.getElementById('chk-' + id);
            const sliderDiv = document.getElementById('div-' + id);
            sliderDiv.classList.toggle('hidden', !checkbox.checked);
        }

        function tambahBarisSkill() {
            const container = document.getElementById('custom-skill-container');
            const rowId = 'new-' + Date.now();
            const html = `
                <div class="flex flex-col md:flex-row gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100 items-center" id="row-${rowId}">
                    <div class="flex-1 w-full text-sm">
                        <input type="text" name="custom_skill_name[]" placeholder="Contoh: Laravel, English, N3" class="w-full px-4 py-2 rounded-xl border border-gray-100 font-bold outline-none focus:border-[#59a896]" required>
                    </div>
                    <div class="flex items-center gap-4 w-full md:w-48">
                        <input type="range" name="custom_skill_val[]" min="10" max="100" value="50" oninput="this.nextElementSibling.innerText = this.value + '%'" class="flex-1 cursor-pointer">
                        <span class="text-xs font-black text-[#59a896] w-10">50%</span>
                    </div>
                    <button type="button" onclick="document.getElementById('row-${rowId}').remove()" class="text-red-300 hover:text-red-500"><i class="fa-solid fa-circle-xmark text-xl"></i></button>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
        }
    </script>
</body>
</html>