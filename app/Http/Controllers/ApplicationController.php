<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | BATALKAN LAMARAN
    |--------------------------------------------------------------------------
    */
    public function batalLamar($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'user') {
            return redirect()->route('login');
        }

        // Cari pelamar_id berdasarkan user yang sedang login
        $pelamar_id = DB::table('pelamars')->where('user_id', Auth::id())->value('id');

        $application = DB::table('applications')
            ->where('id', $id)
            ->where('pelamar_id', $pelamar_id) // Ganti user_id jadi pelamar_id
            ->first();

        if (!$application) {
            return back()->withErrors(['error' => 'Data lamaran tidak ditemukan.']);
        }

        if ($application->status !== 'Dikirim') {
            return back()->withErrors(['error' => 'Lamaran tidak bisa dibatalkan karena sudah diproses.']);
        }

        DB::table('applications')->where('id', $id)->update(['status' => 'Dibatalkan', 'updated_at' => now()]);

        $job = DB::table('jobs')->where('id', $application->job_id)->first();
        
        // Notif ke perusahaan
        DB::table('notifications')->insert([
            'user_id'    => $job->perusahaan_id, // ini adalah user_id pemilik perusahaan
            'pesan'      => "Pelamar " . Auth::user()->nama_lengkap . " membatalkan lamaran untuk posisi {$job->posisi}.",
            'is_read'    => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('dashboard')->with('success', 'Lamaran berhasil dibatalkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | KIRIM LAMARAN
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        // Proteksi agar Admin/HR tidak bisa melamar
        if (Auth::user()->role !== 'user') {
            return back()->withErrors(['error' => 'Admin atau HRD tidak dapat melamar pekerjaan.']);
        }

        $request->validate(['job_id' => 'required|exists:jobs,id', 'pesan' => 'nullable|string|max:1000']);

        $pelamar = DB::table('pelamars')->where('user_id', Auth::id())->first();

        if (!$pelamar || empty($pelamar->skills) || empty($pelamar->cv_file)) {
            return redirect()->route('profil')->withErrors(['error' => 'Lengkapi profil dan CV terlebih dahulu.']);
        }

        // CEK APAKAH SUDAH PERNAH MELAMAR (Gunakan pelamar_id)
        $existing = DB::table('applications')
            ->where('pelamar_id', $pelamar->id)
            ->where('job_id', $request->job_id)
            ->first();

        if ($existing) {
            if ($existing->status !== 'Dibatalkan') {
                return back()->withErrors(['error' => 'Kamu sudah melamar pekerjaan ini.']);
            }
            // Update jika status Dibatalkan
            DB::table('applications')->where('id', $existing->id)->update([
                'status' => 'Dikirim', 'tanggal_lamar' => now(), 'updated_at' => now()
            ]);

            // NOTIFIKASI KE HR (re-apply)
            $job = DB::table('jobs as j')
                ->leftJoin('perusahaans as p', 'j.perusahaan_id', '=', 'p.id')
                ->select('j.posisi', 'p.user_id as hr_user_id')
                ->where('j.id', $request->job_id)
                ->first();

            if ($job && $job->hr_user_id) {
                DB::table('notifications')->insert([
                    'user_id'    => $job->hr_user_id,
                    'pesan'      => Auth::user()->nama_lengkap . " melamar kembali posisi {$job->posisi}.",
                    'is_read'    => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } else {
            // Insert baru
            DB::table('applications')->insert([
                'pelamar_id'    => $pelamar->id,
                'job_id'        => $request->job_id,
                'pesan'         => $request->pesan,
                'status'        => 'Dikirim',
                'tanggal_lamar' => now(),
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
        }

        // NOTIFIKASI KE HR
        $job = DB::table('jobs as j')
            ->leftJoin('perusahaans as p', 'j.perusahaan_id', '=', 'p.id')
            ->select('j.posisi', 'p.user_id as hr_user_id')
            ->where('j.id', $request->job_id)
            ->first();

        if ($job && $job->hr_user_id) {
            DB::table('notifications')->insert([
                'user_id'    => $job->hr_user_id,
                'pesan'      => Auth::user()->nama_lengkap . " melamar posisi {$job->posisi}.",
                'is_read'    => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Lamaran berhasil dikirim.');
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS LAMARAN OLEH HR
    |--------------------------------------------------------------------------
    */
    public function updateStatus(Request $request)
{
    if (!Auth::check() || Auth::user()->role !== 'hr') {
        return redirect()->route('login');
    }

    $request->validate([
        'app_id' => 'required|exists:applications,id',
        'new_status' => 'required|in:Diproses,Interview,Diterima,Ditolak',
    ]);

    // 1. Ambil data dengan JOIN ke pelamars, BUKAN user_id di aplikasi
    $data = DB::table('applications as a')
        ->join('jobs as j', 'a.job_id', '=', 'j.id')
        ->join('pelamars as pl', 'a.pelamar_id', '=', 'pl.id') // JOIN ke pelamars
        ->join('users as u', 'pl.user_id', '=', 'u.id')        // Baru JOIN ke users
        ->leftJoin('perusahaans as p', 'j.perusahaan_id', '=', 'p.id')
        ->select(
            'a.id',
            'a.status',
            'j.posisi',
            'p.nama_perusahaan',
            'pl.user_id as pelamar_user_id', // Ambil user_id pelamar
            'u.nama_lengkap'
        )
        ->where('a.id', $request->app_id)
        ->where('j.perusahaan_id', DB::table('perusahaans')->where('user_id', Auth::id())->value('id'))
        ->first();

    if (!$data) {
        return back()->withErrors(['error' => 'Data lamaran tidak ditemukan.']);
    }

    // 2. Update Status
    DB::table('applications')->where('id', $request->app_id)->update([
        'status' => $request->new_status,
        'updated_at' => now()
    ]);

    // 3. Notifikasi ke user pelamar
DB::table('notifications')->insert([
    'user_id'    => $data->pelamar_user_id,
    'pesan'      => "Status lamaranmu untuk posisi {$data->posisi} diperbarui menjadi {$request->new_status}.",
    'is_read'    => 0,
    'created_at' => now(),
    'updated_at' => now()
]);

// 4. Notifikasi ke admin
$admin = DB::table('users')->where('role', 'admin')->first();
if ($admin) {
    DB::table('notifications')->insert([
        'user_id'    => $admin->id,
        'pesan'      => "HR {$data->nama_perusahaan} mengubah status lamaran {$data->nama_lengkap} untuk posisi {$data->posisi} menjadi {$request->new_status}.",
        'is_read'    => 0,
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

return redirect()->route('dashboard')->with('success', 'Status lamaran berhasil diperbarui.');
}
}