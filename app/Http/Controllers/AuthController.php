<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |-----------------------------------------
    | LOGIN FORM
    |-----------------------------------------
    */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return view('login');
    }

    /*
    |-----------------------------------------
    | LOGIN PROCESS
    |-----------------------------------------
    */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'loginError' => 'Email atau password salah.'
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return $this->redirectByRole(Auth::user()->role);
    }

    /*
    |-----------------------------------------
    | LOGOUT
    |-----------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /*
    |-----------------------------------------
    | REGISTER FORM
    |-----------------------------------------
    */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return view('register');
    }

    /*
    |-----------------------------------------
    | REGISTER PROCESS (FIXED MULTI ROLE)
    |-----------------------------------------
    */
    public function register(Request $request)
    {
        /*
        |-----------------------------------------
        | VALIDASI DASAR
        |-----------------------------------------
        */
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email',
            'password'     => 'required|string|min:6|confirmed',
            'role'         => 'required|in:user,hr',
        ]);

        /*
        |-----------------------------------------
        | VALIDASI KHUSUS HR
        |-----------------------------------------
        */
        if ($request->role === 'hr') {
            $request->validate([
                'nama_perusahaan' => 'required|string|max:255',
            ]);
        }

        DB::beginTransaction();

        try {

            /*
            |-----------------------------------------
            | INSERT USER
            |-----------------------------------------
            */
            $userId = DB::table('users')->insertGetId([
                'nama_lengkap' => $request->nama_lengkap,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'role'         => $request->role,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            /*
            |-----------------------------------------
            | JIKA USER = PELAMAR
            |-----------------------------------------
            */
            if ($request->role === 'user') {
                DB::table('pelamars')->insert([
                    'user_id'    => $userId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            /*
            |-----------------------------------------
            | JIKA USER = HR
            |-----------------------------------------
            */
            if ($request->role === 'hr') {
                DB::table('perusahaans')->insert([
                    'user_id'          => $userId,
                    'nama_perusahaan'  => $request->nama_perusahaan,
                    'bio_perusahaan'   => null,
                    'website_perusahaan' => null,
                    'logo_perusahaan'  => null,
                    'status'           => 'Aktif',
                    'created_at'       => now(),
                    'updated_at'       => now()
                ]);
            }

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Pendaftaran berhasil.');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->withErrors([
                'registerError' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /*
    |-----------------------------------------
    | REDIRECT ROLE
    |-----------------------------------------
    */
    private function redirectByRole($role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'hr'    => redirect()->route('dashboard'),
            'user'  => redirect()->route('beranda'),
            default => redirect()->route('login')
        };
    }
}