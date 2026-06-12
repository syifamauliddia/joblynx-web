<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Masuk - JOBLYNX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#f8fbf9] h-screen flex items-center justify-center">
    <div class="bg-white p-10 rounded-3xl shadow-xl w-full max-w-md border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-[#1f4e5a] tracking-tight mb-2"><span class="text-[#59a896]">JOB</span>LYNX</h1>
            <p class="text-gray-500">Selamat datang kembali!</p>
        </div>

        {{-- Menampilkan Error Validasi atau Gagal Login dari Laravel --}}
        @if ($errors->has('loginError'))
            <div class="bg-red-50 text-red-500 text-sm font-semibold p-3 rounded-lg mb-4 text-center border border-red-100">
                {{ $errors->first('loginError') }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}" autocomplete="off" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                {{-- old('email') berfungsi menahan inputan email agar tidak hilang jika password salah --}}
                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="off" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-[#59a896] focus:ring-1 focus:ring-[#59a896] bg-gray-50">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required autocomplete="new-password" class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-200 focus:outline-none focus:border-[#59a896] focus:ring-1 focus:ring-[#59a896] bg-gray-50">
                    
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#59a896] transition-colors focus:outline-none">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg id="eye-slash-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-[#59a896] text-white font-bold py-3 rounded-xl hover:bg-[#468a7a] transition mt-6">Masuk</button>
        </form>
        
        <p class="text-center mt-6 text-sm text-gray-500">Belum punya akun? <a href="{{ url('/register') }}" class="text-[#1a4450] font-bold hover:underline">Daftar sekarang</a></p>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eye-icon");
            const eyeSlashIcon = document.getElementById("eye-slash-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.add("hidden");
                eyeSlashIcon.classList.remove("hidden");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("hidden");
                eyeSlashIcon.classList.add("hidden");
            }
        }
    </script>
</body>
</html>