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

        $pelamar_id = DB::table('pelamars')->where('user_id', Auth::id())->value('id');

        $application = DB::table('applications')
            ->where('id', $id)
            ->where('pelamar_id', $pelamar_id)
            ->first();

        if (!$application) {
            return back()->withErrors(['error' => 'Data lamaran tidak ditemukan.']);
        }

        if ($application->status !== 'Dikirim') {
            return back()->withErrors(['error' => 'Lamaran tidak bisa dibatalkan karena sudah diproses.']);
        }

        DB::table('applications')->where('id', $id)->update(['status' => 'Dibatalkan', 'updated_at' => now()]);

        $job = DB::table('jobs')->where('id', $application->job_id)->first();

        DB::table('notifications')->insert([
            'user_id'    => $job->perusahaan_id,
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
        if (Auth::user()->role !== 'user') {
            return back()->withErrors(['error' => 'Admin atau HRD tidak dapat melamar pekerjaan.']);
        }

        $request->validate(['job_id' => 'required|exists:jobs,id', 'pesan' => 'nullable|string|max:1000']);

        $pelamar = DB::table('pelamars')->where('user_id', Auth::id())->first();

        if (!$pelamar || empty($pelamar->skills) || empty($pelamar->cv_file)) {
            return redirect()->route('profil')->withErrors(['error' => 'Lengkapi profil dan CV terlebih dahulu.']);
        }

        $job = DB::table('jobs as j')
            ->leftJoin('perusahaans as p', 'j.perusahaan_id', '=', 'p.id')
            ->select('j.posisi', 'p.user_id as hr_user_id')
            ->where('j.id', $request->job_id)
            ->first();

        $existing = DB::table('applications')
            ->where('pelamar_id', $pelamar->id)
            ->where('job_id', $request->job_id)
            ->first();

        if ($existing) {
            if ($existing->status !== 'Dibatalkan') {
                return back()->withErrors(['error' => 'Kamu sudah melamar pekerjaan ini.']);
            }

            DB::table('applications')->where('id', $existing->id)->update([
                'status' => 'Dikirim', 'tanggal_lamar' => now(), 'updated_at' => now()
            ]);

            $pesan_hr = Auth::user()->nama_lengkap . " melamar kembali posisi {$job->posisi}.";
        } else {
            DB::table('applications')->insert([
                'pelamar_id'    => $pelamar->id,
                'job_id'        => $request->job_id,
                'pesan'         => $request->pesan,
                'status'        => 'Dikirim',
                'tanggal_lamar' => now(),
                'created_at'    => now(),
                'updated_at'    => now()
            ]);

            $pesan_hr = Auth::user()->nama_lengkap . " melamar posisi {$job->posisi}.";
        }

        if ($job && $job->hr_user_id) {
            DB::table('notifications')->insert([
                'user_id'    => $job->hr_user_id,
                'pesan'      => $pesan_hr,
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
            'app_id'            => 'required|exists:applications,id',
            'new_status'        => 'required|in:Diproses,Interview,Diterima,Ditolak',
            'lokasi_interview'  => 'nullable|string|max:255',
            'tanggal_interview' => 'nullable|string|max:100',
            'jam_interview'     => 'nullable|string|max:50',
            'pesan_tambahan'    => 'nullable|string|max:1000',
        ]);

        $data = DB::table('applications as a')
            ->join('jobs as j', 'a.job_id', '=', 'j.id')
            ->join('pelamars as pl', 'a.pelamar_id', '=', 'pl.id')
            ->join('users as u', 'pl.user_id', '=', 'u.id')
            ->leftJoin('perusahaans as p', 'j.perusahaan_id', '=', 'p.id')
            ->select(
                'a.id',
                'a.status',
                'j.posisi',
                'p.nama_perusahaan',
                'pl.user_id as pelamar_user_id',
                'u.nama_lengkap'
            )
            ->where('a.id', $request->app_id)
            ->where('j.perusahaan_id', DB::table('perusahaans')->where('user_id', Auth::id())->value('id'))
            ->first();

        if (!$data) {
            return back()->withErrors(['error' => 'Data lamaran tidak ditemukan.']);
        }

        DB::table('applications')->where('id', $request->app_id)->update([
            'status'     => $request->new_status,
            'updated_at' => now()
        ]);

        // Buat pesan_custom hanya untuk Interview dan Diterima
        $pesan_custom = null;

        if ($request->new_status === 'Interview') {
            $pesan_custom = "Kamu diundang Interview untuk posisi {$data->posisi} di {$data->nama_perusahaan}.\n";
            if ($request->lokasi_interview)  $pesan_custom .= "📍 Lokasi: {$request->lokasi_interview}\n";
            if ($request->tanggal_interview) $pesan_custom .= "📅 Tanggal: {$request->tanggal_interview}\n";
            if ($request->jam_interview)     $pesan_custom .= "🕐 Jam: {$request->jam_interview}\n";
            if ($request->pesan_tambahan)    $pesan_custom .= "📝 Catatan: {$request->pesan_tambahan}";
        }

        if ($request->new_status === 'Diterima') {
            $pesan_custom = "Selamat! Kamu diterima untuk posisi {$data->posisi} di {$data->nama_perusahaan}.\n";
            if ($request->lokasi_interview)  $pesan_custom .= "📍 Lokasi Onboarding: {$request->lokasi_interview}\n";
            if ($request->tanggal_interview) $pesan_custom .= "📅 Tanggal Mulai/Lapor: {$request->tanggal_interview}\n";
            if ($request->jam_interview)     $pesan_custom .= "🕐 Jam: {$request->jam_interview}\n";
            if ($request->pesan_tambahan)    $pesan_custom .= "📝 Catatan: {$request->pesan_tambahan}";
        }

        // Notifikasi ke pelamar
        DB::table('notifications')->insert([
            'user_id'      => $data->pelamar_user_id,
            'pesan'        => "Status lamaranmu untuk posisi {$data->posisi} diperbarui menjadi {$request->new_status}.",
            'pesan_custom' => $pesan_custom,
            'is_read'      => 0,
            'created_at'   => now(),
            'updated_at'   => now()
        ]);

        // Notifikasi ke admin
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