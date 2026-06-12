<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $authUser = Auth::user();
        $userId = $authUser->id;
        $role = $authUser->role;

        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user) {
            Auth::logout();
            return redirect('/login');
        }

        /*
        |-----------------------------------------
        | DEFAULT SAFE PROFILE (ANTI ERROR)
        |-----------------------------------------
        */
        $profile = (object)[
            // USER
            'no_hp' => '',
            'alamat' => '',
            'pendidikan' => '',
            'pengalaman' => '',
            'skills' => '',
            'cv_file' => '',

            // HR
            'nama_perusahaan' => '',
            'bio_perusahaan' => '',
            'website_perusahaan' => '',
            'logo_perusahaan' => ''
        ];

        /*
        |-----------------------------------------
        | AMBIL DATA SESUAI ROLE
        |-----------------------------------------
        */
        if ($role === 'user') {
            $dbProfile = DB::table('pelamars')
                ->where('user_id', $userId)
                ->first();
        }

        if ($role === 'hr') {
            $dbProfile = DB::table('perusahaans')
                ->where('user_id', $userId)
                ->first();
        }

        if (!empty($dbProfile)) {
            $profile = (object) array_merge((array) $profile, (array) $dbProfile);
        }

        /*
        |-----------------------------------------
        | SKOR USER
        |-----------------------------------------
        */
        $skor = 0;

        if ($role === 'user') {
            if (!empty($user->nama_lengkap)) $skor += 20;
            if (!empty($user->foto_profil)) $skor += 20;
            if (!empty($profile->no_hp)) $skor += 20;
            if (!empty($profile->skills)) $skor += 20;
            if (!empty($profile->cv_file)) $skor += 20;
        }

        return view('profil', [
            'user' => $user,
            'profile' => $profile,
            'role' => $role,
            'nama_user' => $user->nama_lengkap ?? $user->email,
            'skor' => $skor
        ]);
    }

    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $authUser = Auth::user();
        $userId = $authUser->id;
        $role = $authUser->role;

        $user = DB::table('users')->where('id', $userId)->first();

        $profile = ($role === 'hr')
            ? DB::table('perusahaans')->where('user_id', $userId)->first()
            : DB::table('pelamars')->where('user_id', $userId)->first();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cv_file' => 'nullable|mimes:pdf|max:5120'
        ]);

        $foto = $user->foto_profil ?? null;
$cv = $profile->cv_file ?? null;

/*
|-----------------------------------------
| HAPUS FOTO PROFIL
|-----------------------------------------
*/
if ($request->has('hapus_foto')) {
    if (!empty($user->foto_profil)) {
        $old = public_path('uploads/' . $user->foto_profil);
        if (File::exists($old)) File::delete($old);
    }
    DB::table('users')->where('id', $userId)->update([
        'foto_profil' => null,
        'updated_at' => now()
    ]);
    return back()->with('success', 'Foto profil berhasil dihapus.');
}

/*
|-----------------------------------------
| HAPUS CV
|-----------------------------------------
*/
if ($request->has('hapus_cv') && $role === 'user') {

    if (!empty($profile->cv_file)) {

        $oldCv = public_path('uploads/' . $profile->cv_file);

        if (File::exists($oldCv)) {
            File::delete($oldCv);
        }

        DB::table('pelamars')
            ->where('user_id', $userId)
            ->update([
                'cv_file' => null,
                'updated_at' => now()
            ]);
    }

    return back()->with('success', 'CV berhasil dihapus.');
}

/*
|-----------------------------------------
| FOTO PROFIL
|-----------------------------------------
*/
if ($request->hasFile('foto_profil')) {

            if (!empty($user->foto_profil)) {
                $old = public_path('uploads/' . $user->foto_profil);
                if (File::exists($old)) File::delete($old);
            }

            $file = $request->file('foto_profil');
            $foto = 'profil_' . $userId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $foto);
        }

        /*
        |-----------------------------------------
        | CV (USER ONLY)
        |-----------------------------------------
        */
        if ($role === 'user' && $request->hasFile('cv_file')) {

            if (!empty($profile->cv_file)) {
                $oldCv = public_path('uploads/' . $profile->cv_file);
                if (File::exists($oldCv)) File::delete($oldCv);
            }

            $file = $request->file('cv_file');
            $cv = 'cv_' . $userId . '_' . time() . '.pdf';
            $file->move(public_path('uploads'), $cv);
        }

        /*
        |-----------------------------------------
        | UPDATE USERS
        |-----------------------------------------
        */
        DB::table('users')->where('id', $userId)->update([
            'nama_lengkap' => $request->nama_lengkap,
            'foto_profil' => $foto,
            'updated_at' => now()
        ]);

        /*
        |-----------------------------------------
        | HR UPDATE (FIX FIELD NAME)
        |-----------------------------------------
        */
        if ($role === 'hr') {

            DB::table('perusahaans')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'nama_perusahaan' => $request->nama_perusahaan ?? '',
                    'bio_perusahaan' => $request->bio_perusahaan ?? '',
                    'website_perusahaan' => $request->website_perusahaan ?? '',
                    'updated_at' => now(),
                    'created_at' => now()
                ]
            );
        }

        /*
        |-----------------------------------------
        | USER UPDATE
        |-----------------------------------------
        */
        else {

            DB::table('pelamars')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'no_hp' => $request->no_hp ?? '',
                    'alamat' => $request->alamat ?? '',
                    'pendidikan' => $request->pendidikan ?? '',
                    'pengalaman' => $request->pengalaman ?? '',
                    'skills' => $request->skills ?? '',
                    'cv_file' => $cv,
                    'updated_at' => now(),
                    'created_at' => now()
                ]
            );
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}