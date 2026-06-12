<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - JOBLYNX</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-[#f8fbf9] min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-10 rounded-3xl shadow-xl w-full max-w-md border border-gray-100">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-[#1f4e5a] mb-2">
                <span class="text-[#59a896]">JOB</span>LYNX
            </h1>
            <p id="formSubtitle" class="text-gray-500 text-sm">Buat akun untuk memulai karirmu</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 text-sm p-3 rounded-xl mb-4 border border-red-100">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/register') }}" class="space-y-4" autocomplete="off">
            @csrf

            <div>
                <label class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
                <input type="text" name="nama_lengkap"
                    value="{{ old('nama_lengkap') }}"
                    required
                    autocomplete="off"
                    class="w-full mt-1 px-4 py-3 rounded-xl border bg-gray-50 focus:border-[#59a896] focus:ring-1 focus:ring-[#59a896] outline-none">
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700">Email</label>
                <input type="email" name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="off"
                    class="w-full mt-1 px-4 py-3 rounded-xl border bg-gray-50 focus:border-[#59a896] focus:ring-1 focus:ring-[#59a896] outline-none">
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700">Password</label>
                <div class="relative mt-1">
                    <input type="password" id="password" name="password"
                        required
                        autocomplete="new-password"
                        class="w-full px-4 py-3 pr-12 rounded-xl border bg-gray-50 focus:border-[#59a896] focus:ring-1 focus:ring-[#59a896] outline-none">
                    
                    <button type="button" onclick="toggleVisibility('password', 'eye1', 'eye-slash1')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#59a896] transition-colors focus:outline-none">
                        <svg id="eye1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg id="eye-slash1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                <div class="relative mt-1">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="w-full px-4 py-3 pr-12 rounded-xl border bg-gray-50 focus:border-[#59a896] focus:ring-1 focus:ring-[#59a896] outline-none">
                    
                    <button type="button" onclick="toggleVisibility('password_confirmation', 'eye2', 'eye-slash2')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#59a896] transition-colors focus:outline-none">
                        <svg id="eye2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg id="eye-slash2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700">Daftar Sebagai</label>

                <select name="role" id="role"
                    onchange="togglePerusahaan()"
                    class="w-full mt-1 px-4 py-3 rounded-xl border bg-gray-50 focus:border-[#59a896] focus:ring-1 focus:ring-[#59a896] outline-none">

                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>
                        Pencari Kerja
                    </option>

                    <option value="hr" {{ old('role') == 'hr' ? 'selected' : '' }}>
                        HRD / Perusahaan
                    </option>
                </select>
            </div>

            <div id="perusahaanField" class="hidden">
                <label class="text-sm font-semibold text-gray-700">Nama Perusahaan</label>

                <input type="text" name="nama_perusahaan"
                    value="{{ old('nama_perusahaan') }}"
                    autocomplete="off"
                    class="w-full mt-1 px-4 py-3 rounded-xl border bg-gray-50 focus:border-[#59a896] focus:ring-1 focus:ring-[#59a896] outline-none"
                    placeholder="Masukkan nama perusahaan">
            </div>

            <button type="submit"
                class="w-full bg-[#1a4450] text-white font-bold py-4 rounded-xl hover:bg-[#13323b] transition mt-4">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center mt-6 text-sm text-gray-500">
            Sudah punya akun?
            <a href="{{ url('/login') }}" class="text-[#59a896] font-bold hover:underline">
                Masuk
            </a>
        </p>
    </div>

    <script>
        // Fungsi untuk Show/Hide Password dinamis (bisa untuk multiple input)
        function toggleVisibility(inputId, eyeId, eyeSlashId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            const eyeSlash = document.getElementById(eyeSlashId);

            if (input.type === "password") {
                input.type = "text";
                eye.classList.add("hidden");
                eyeSlash.classList.remove("hidden");
            } else {
                input.type = "password";
                eye.classList.remove("hidden");
                eyeSlash.classList.add("hidden");
            }
        }

        // Fungsi Toggle HRD/Perusahaan
        function togglePerusahaan() {
            let role = document.getElementById('role').value;
            let field = document.getElementById('perusahaanField');
            let subtitle = document.getElementById('formSubtitle');

            if (role === 'hr') {
                field.classList.remove('hidden');
                subtitle.innerText = "Temukan talenta terbaik untuk perusahaan Anda";
            } else {
                field.classList.add('hidden');
                subtitle.innerText = "Buat akun untuk memulai karirmu";
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            togglePerusahaan();
        });
    </script>

</body>
</html>