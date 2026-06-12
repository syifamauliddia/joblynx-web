<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN PROFILE HR / PERUSAHAAN
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI LOGIN & ROLE HR
        |--------------------------------------------------------------------------
        */
        if (
            !Auth::check()
            || Auth::user()->role !== 'hr'
        ) {
            return redirect()->route('login');
        }

        $userId = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA USER + PERUSAHAAN
        |--------------------------------------------------------------------------
        |
        | NORMALISASI:
        | users.nama_lengkap = nama lengkap utama
        |
        */
        $user = DB::table('users')

            ->leftJoin(
                'perusahaans',
                'users.id',
                '=',
                'perusahaans.user_id'
            )

            ->where(
                'users.id',
                $userId
            )

            ->select(

                'users.id',

                'users.nama_lengkap',

                'users.email',

                'users.role',

                'users.foto_profil',

                'perusahaans.nama_perusahaan',

                'perusahaans.website_perusahaan',

                'perusahaans.bio_perusahaan',

                'perusahaans.logo_perusahaan',

                'perusahaans.status'
            )

            ->first();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI USER
        |--------------------------------------------------------------------------
        */
        if (!$user) {

            Auth::logout();

            return redirect()

                ->route('login')

                ->withErrors([

                    'loginError'
                        => 'User tidak ditemukan.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | NAMA USER
        |--------------------------------------------------------------------------
        */
        $nama_user =
            $user->nama_lengkap
            ?? $user->email;

        /*
        |--------------------------------------------------------------------------
        | TOTAL LOWONGAN
        |--------------------------------------------------------------------------
        */
        $total_loker = DB::table('jobs')

            ->where(
                'perusahaan_id',
                $userId
            )

            ->whereNull('deleted_at')

            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL PELAMAR
        |--------------------------------------------------------------------------
        */
        $total_pelamar = DB::table('applications')

            ->join(
                'jobs',
                'applications.job_id',
                '=',
                'jobs.id'
            )

            ->where(
                'jobs.perusahaan_id',
                $userId
            )

            ->whereNull('applications.deleted_at')

            ->whereNull('jobs.deleted_at')

            ->count();

        /*
        |--------------------------------------------------------------------------
        | PERLU REVIEW
        |--------------------------------------------------------------------------
        */
        $perlu_review = DB::table('applications')

            ->join(
                'jobs',
                'applications.job_id',
                '=',
                'jobs.id'
            )

            ->where(
                'jobs.perusahaan_id',
                $userId
            )

            ->where(
                'applications.status',
                'Dikirim'
            )

            ->whereNull('applications.deleted_at')

            ->whereNull('jobs.deleted_at')

            ->count();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */
        return view(
            'profil_admin',
            compact(
                'user',
                'nama_user',
                'total_loker',
                'total_pelamar',
                'perlu_review'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PROFILE HR / PERUSAHAAN
    |--------------------------------------------------------------------------
    */
    public function update(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI LOGIN & ROLE HR
        |--------------------------------------------------------------------------
        */
        if (
            !Auth::check()
            || Auth::user()->role !== 'hr'
        ) {
            return redirect()->route('login');
        }

        $userId = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA USER
        |--------------------------------------------------------------------------
        */
        $user = DB::table('users')

            ->where(
                'id',
                $userId
            )

            ->first();

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA PERUSAHAAN
        |--------------------------------------------------------------------------
        */
        $perusahaan = DB::table('perusahaans')

            ->where(
                'user_id',
                $userId
            )

            ->first();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI DATA
        |--------------------------------------------------------------------------
        */
        if (!$user) {

            return back()->withErrors([

                'error'
                    => 'User tidak ditemukan.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | HAPUS FOTO PROFILE
        |--------------------------------------------------------------------------
        */
        if ($request->has('hapus_foto')) {

            /*
            |--------------------------------------------------------------------------
            | HAPUS FOTO USER
            |--------------------------------------------------------------------------
            */
            if (!empty($user->foto_profil)) {

                $fotoPath = public_path(
                    'uploads/' .
                    $user->foto_profil
                );

                if (File::exists($fotoPath)) {

                    File::delete($fotoPath);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | HAPUS LOGO PERUSAHAAN
            |--------------------------------------------------------------------------
            */
            if (
                $perusahaan
                && !empty($perusahaan->logo_perusahaan)
            ) {

                $logoPath = public_path(
                    'uploads/' .
                    $perusahaan->logo_perusahaan
                );

                if (File::exists($logoPath)) {

                    File::delete($logoPath);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE USERS
            |--------------------------------------------------------------------------
            */
            DB::table('users')

                ->where(
                    'id',
                    $userId
                )

                ->update([

                    'foto_profil' => null,

                    'updated_at' => now()
                ]);

            /*
            |--------------------------------------------------------------------------
            | UPDATE PERUSAHAANS
            |--------------------------------------------------------------------------
            */
            if ($perusahaan) {

                DB::table('perusahaans')

                    ->where(
                        'user_id',
                        $userId
                    )

                    ->update([

                        'logo_perusahaan' => null,

                        'updated_at' => now()
                    ]);
            }

            return back()->with(

                'success_foto',

                'Foto profil berhasil dihapus.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI INPUT
        |--------------------------------------------------------------------------
        */
        $request->validate([

            'name'
                => 'required|string|max:255',

            'nama_perusahaan'
                => 'required|string|max:255',

            'website'
                => 'nullable|url|max:255',

            'bio_perusahaan'
                => 'nullable|string',

            'foto_profil'
                => 'nullable|image|mimes:jpg,jpeg,png|max:2048'

        ], [

            'name.required'
                => 'Nama lengkap wajib diisi.',

            'nama_perusahaan.required'
                => 'Nama perusahaan wajib diisi.',

            'website.url'
                => 'Format website tidak valid.',

            'foto_profil.image'
                => 'File harus berupa gambar.',

            'foto_profil.mimes'
                => 'Format gambar harus jpg, jpeg, atau png.',

            'foto_profil.max'
                => 'Ukuran gambar maksimal 2MB.'
        ]);

        /*
        |--------------------------------------------------------------------------
        | DEFAULT FOTO
        |--------------------------------------------------------------------------
        */
        $namaFotoBaru =
            $user->foto_profil;

        $namaLogoBaru =
            $perusahaan->logo_perusahaan
            ?? null;

        /*
        |--------------------------------------------------------------------------
        | UPLOAD FOTO
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('foto_profil')) {

            $file =
                $request->file('foto_profil');

            $namaFile =
                'hr_' .
                $userId .
                '_' .
                time() .
                '.' .
                $file->getClientOriginalExtension();

            /*
            |--------------------------------------------------------------------------
            | UPLOAD FILE
            |--------------------------------------------------------------------------
            */
            $file->move(

                public_path('uploads'),

                $namaFile
            );

            /*
            |--------------------------------------------------------------------------
            | HAPUS FOTO LAMA
            |--------------------------------------------------------------------------
            */
            if (!empty($user->foto_profil)) {

                $oldFoto = public_path(
                    'uploads/' .
                    $user->foto_profil
                );

                if (File::exists($oldFoto)) {

                    File::delete($oldFoto);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | HAPUS LOGO LAMA
            |--------------------------------------------------------------------------
            */
            if (
                $perusahaan
                && !empty($perusahaan->logo_perusahaan)
            ) {

                $oldLogo = public_path(
                    'uploads/' .
                    $perusahaan->logo_perusahaan
                );

                if (File::exists($oldLogo)) {

                    File::delete($oldLogo);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SET FOTO BARU
            |--------------------------------------------------------------------------
            */
            $namaFotoBaru = $namaFile;

            $namaLogoBaru = $namaFile;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE USERS
        |--------------------------------------------------------------------------
        |
        | NORMALISASI:
        | name ada di tabel users
        |
        */
        DB::table('users')

            ->where(
                'id',
                $userId
            )

            ->update([

                'name'
                    => $request->name,

                'foto_profil'
                    => $namaFotoBaru,

                'updated_at'
                    => now()
            ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE / INSERT PERUSAHAAN
        |--------------------------------------------------------------------------
        */
        DB::table('perusahaans')

            ->updateOrInsert(

                [
                    'user_id'
                        => $userId
                ],

                [

                    'nama_perusahaan'
                        => $request->nama_perusahaan,

                    'website_perusahaan'
                        => $request->website,

                    'bio_perusahaan'
                        => $request->bio_perusahaan,

                    'logo_perusahaan'
                        => $namaLogoBaru,

                    'updated_at'
                        => now(),

                    'created_at'
                        => now()
                ]
            );

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */
        return back()->with(

            'success',

            'Profil perusahaan berhasil diperbarui.'
        );
    }
}