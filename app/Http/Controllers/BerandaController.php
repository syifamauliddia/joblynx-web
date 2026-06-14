<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | CEK LOGIN
        |--------------------------------------------------------------------------
        */
        $is_logged_in = Auth::check();
        $user = $is_logged_in ? Auth::user() : null;

        /*
        |--------------------------------------------------------------------------
        | JIKA ADMIN → DASHBOARD ADMIN
        |--------------------------------------------------------------------------
        */
        if ($is_logged_in && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $user_id = $is_logged_in ? $user->id : null;
        $role = $is_logged_in ? $user->role : '';

        $nama_user = $user->nama_lengkap ?? '';
        $nav_foto = $user->foto_profil ?? '';

        /*
        |--------------------------------------------------------------------------
        | AMBIL PELAMAR (PENTING UNTUK FIX ERROR)
        |--------------------------------------------------------------------------
        */
        $pelamar = null;
        $pelamar_id = null;

        if ($is_logged_in && $role === 'user') {
            $pelamar = DB::table('pelamars')
                ->where('user_id', $user_id)
                ->first();

            $pelamar_id = $pelamar->id ?? null;
        }

        /*
        |--------------------------------------------------------------------------
        | DATA HR PERUSAHAAN
        |--------------------------------------------------------------------------
        */
        $nama_perusahaan = '';
        $perusahaan_hr_id = null;

        if ($is_logged_in && $role === 'hr') {
            $perusahaan = DB::table('perusahaans')
                ->where('user_id', $user_id)
                ->whereNull('deleted_at')
                ->first();

            if ($perusahaan) {
                $perusahaan_hr_id = $perusahaan->id;
                $nama_perusahaan = $perusahaan->nama_perusahaan ?? '';

                if (!empty($perusahaan->logo_perusahaan)) {
                    $nav_foto = $perusahaan->logo_perusahaan;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI
        |--------------------------------------------------------------------------
        */
        $unread_count = 0;
        $notif_result = collect();

        if ($is_logged_in) {
            $unread_count = DB::table('notifications')
                ->where('user_id', $user_id)
                ->where('is_read', 0)
                ->count();

            $notif_result = DB::table('notifications')
                ->where('user_id', $user_id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | JOBS YANG SUDAH DILAMAR (FIX UTAMA DI SINI)
        |--------------------------------------------------------------------------
        */
        $applied_jobs = [];

        if ($is_logged_in && $role === 'user' && $pelamar_id) {
            $applied_jobs = DB::table('applications')
                ->where('pelamar_id', $pelamar_id)
                ->where('status', '!=', 'Dibatalkan')
                ->pluck('job_id')
                ->toArray();
        }

        /*
        |--------------------------------------------------------------------------
        | SKILLS USER
        |--------------------------------------------------------------------------
        */
        $user_skills_array = [];
        $user_skills_raw = [];

        if ($is_logged_in && $role === 'user' && $pelamar) {

            $skills = $pelamar->skills;

            if (!empty($skills)) {
                $pairs = explode(',', $skills);

                foreach ($pairs as $pair) {
                    $parts = explode(':', $pair);

                    if (count($parts) === 2) {
                        $skill_name = trim($parts[0]);
                        $percentage = (int) trim($parts[1]);

                        $user_skills_raw[$skill_name] = $percentage;

                        $user_skills_array[] = strtolower(
                            str_replace(' ', '', $skill_name)
                        );
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER JOB
        |--------------------------------------------------------------------------
        */
        $keyword = trim($request->get('keyword', ''));
        $lokasi = trim($request->get('lokasi', ''));
        $tipe = trim($request->get('tipe', ''));
        $gaji = trim($request->get('gaji', ''));

        $query = DB::table('jobs')
            ->leftJoin('perusahaans', 'jobs.perusahaan_id', '=', 'perusahaans.id') 
            ->select(
                'jobs.*',
                'perusahaans.nama_perusahaan',
                'perusahaans.logo_perusahaan',
                'perusahaans.bio_perusahaan'
            )
            ->whereNull('jobs.deleted_at');

        if ($role === 'hr') {
            $query->where('jobs.perusahaan_id', $perusahaan_hr_id);
        } else {
            $query->where('jobs.status_loker', 'Aktif');
        }

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('jobs.posisi', 'LIKE', "%{$keyword}%")
                  ->orWhere('perusahaans.nama_perusahaan', 'LIKE', "%{$keyword}%")
                  ->orWhere('jobs.syarat_skill', 'LIKE', "%{$keyword}%")
                  ->orWhere('jobs.tipe_pekerjaan', 'LIKE', "%{$keyword}%");
            });
        }

        if (!empty($lokasi) && $lokasi !== 'Semua') {
            $query->where('jobs.lokasi', 'LIKE', "%{$lokasi}%");
        }

        if (!empty($tipe) && $tipe !== 'Semua') {
            $query->where('jobs.tipe_pekerjaan', $tipe);
        }

        if (!empty($gaji)) {
            $query->where('jobs.gaji_max', '>=', (int)$gaji);
        }

        $result = $query->orderBy('jobs.created_at', 'desc')->get();

        /*
        |--------------------------------------------------------------------------
        | HR STATISTIK
        |--------------------------------------------------------------------------
        */
        $total_loker = 0;
        $total_pelamar = 0;
        $pelamar_baru = 0;

        if ($role === 'hr') {
            $total_loker = DB::table('jobs')
                ->where('perusahaan_id', $perusahaan_hr_id)
                ->whereNull('deleted_at')
                ->count();

            $total_pelamar = DB::table('applications')
            ->join('jobs', 'applications.job_id', '=', 'jobs.id')
            ->where('jobs.perusahaan_id', $perusahaan_hr_id)
            ->whereNull('jobs.deleted_at')
            ->count();

        $pelamar_baru = DB::table('applications')
            ->join('jobs', 'applications.job_id', '=', 'jobs.id')
            ->where('jobs.perusahaan_id', $perusahaan_hr_id)
            ->whereNull('jobs.deleted_at')
            ->where('applications.status', 'Dikirim')
            ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | PERSENTASE PROFIL
        |--------------------------------------------------------------------------
        */
        $persen_profil = 0;

        if ($is_logged_in && $role === 'user' && $pelamar) {

            if (!empty($user->nama_lengkap)) $persen_profil += 25;
            if (!empty($user->email)) $persen_profil += 25;
            if (!empty($pelamar->skills)) $persen_profil += 25;
            if (!empty($pelamar->cv_file)) $persen_profil += 25;
        }

        return view('beranda', compact(
            'is_logged_in',
            'user_id',
            'role',
            'nama_user',
            'nama_perusahaan',
            'nav_foto',
            'unread_count',
            'notif_result',
            'applied_jobs',
            'user_skills_raw',
            'user_skills_array',
            'keyword',
            'lokasi',
            'tipe',
            'gaji',
            'result',
            'total_loker',
            'total_pelamar',
            'pelamar_baru',
            'perusahaan_hr_id',
            'persen_profil'
        ));
    }
}