<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $user_id = $user->id;
        $role = $user->role;

        // =========================
        // GLOBAL VARIABLES (FIX ERROR BLADE)
        // =========================
        $is_logged_in = true;

        $nama_user = $user->nama_lengkap ?? $user->email;
        $nav_foto = $user->foto_profil ?? '';

        // FIX: default biar tidak error di blade
        $unread_count = DB::table('notifications')
            ->where('user_id', $user_id)
            ->where('is_read', 0)
            ->count();

        $notif_result = DB::table('notifications')
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ADMIN REDIRECT
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        /*
        |-----------------------------------------
        | HR DATA
        |-----------------------------------------
        */
        $nama_perusahaan = '';
        $perusahaan_id = null;

        if ($role === 'hr') {
            $perusahaan = DB::table('perusahaans')
                ->where('user_id', $user_id)
                ->first();

            if ($perusahaan) {
                $perusahaan_id = $perusahaan->id ?? null;

                // FIX ERROR: pastikan kolom ada
                $nama_perusahaan = $perusahaan->nama_perusahaan ?? 'Perusahaan';
                $nav_foto = $perusahaan->logo_perusahaan ?? $nav_foto;
            }
        }

        /*
        |-----------------------------------------
        | USER DASHBOARD
        |-----------------------------------------
        */
        if ($role === 'user') {

            $pelamar_id = DB::table('pelamars')
                ->where('user_id', $user_id)
                ->value('id');

            $display_stats = [
                'Dikirim' => 0,
                'Diproses' => 0,
                'Interview' => 0,
                'Diterima' => 0,
                'Ditolak' => 0,
                'Dibatalkan' => 0
            ];

            $stats = DB::table('applications')
                ->select('status', DB::raw('COUNT(*) as total'))
                ->where('pelamar_id', $pelamar_id)
                ->groupBy('status')
                ->get();

            foreach ($stats as $row) {
                if (isset($display_stats[$row->status])) {
                    $display_stats[$row->status] = $row->total;
                }
            }

            $q_history = DB::table('applications as a')
    ->join('jobs as j', 'a.job_id', '=', 'j.id')
    ->leftJoin('perusahaans as p', 'j.perusahaan_id', '=', 'p.id')
    ->leftJoin('users as u', 'p.user_id', '=', 'u.id') // <-- JOIN KE TABEL USERS
    ->select(
        'a.id as app_id',
        'a.status',
        'a.tanggal_lamar',
        'j.posisi',
        'p.nama_perusahaan as perusahaan',
        'u.email as hr_email' // <-- PASTI ADA INI
    )
    ->where('a.pelamar_id', $pelamar_id)
    ->orderBy('a.created_at', 'desc')
    ->get();

            return view('dashboard', compact(
                'role',
                'nama_user',
                'nav_foto',
                'display_stats',
                'q_history',
                'is_logged_in',
                'unread_count',
                'notif_result'
            ));
        }

        /*
        |-----------------------------------------
        | HR DASHBOARD
        |-----------------------------------------
        */
        if ($role === 'hr') {

            $search_p = trim($request->input('search_p', ''));
            $filter_status = trim($request->input('filter_status', ''));

            $display_stats = [
                'Dikirim' => 0,
                'Diproses' => 0,
                'Interview' => 0,
                'Diterima' => 0,
                'Ditolak' => 0
            ];

            $stats_hr = DB::table('applications as a')
                ->join('jobs as j', 'a.job_id', '=', 'j.id')
                ->leftJoin('perusahaans as p', 'j.perusahaan_id', '=', 'p.id')
                ->select('a.status', DB::raw('COUNT(*) as total'))
                ->where('p.user_id', $user_id)
                ->groupBy('a.status')
                ->get();

            foreach ($stats_hr as $row) {
                if (isset($display_stats[$row->status])) {
                    $display_stats[$row->status] = $row->total;
                }
            }

            $query = DB::table('applications as a')
                ->join('jobs as j', 'a.job_id', '=', 'j.id')
                ->leftJoin('perusahaans as p', 'j.perusahaan_id', '=', 'p.id')
                ->join('pelamars as pl', 'a.pelamar_id', '=', 'pl.id')
                ->join('users as u', 'pl.user_id', '=', 'u.id')
                ->select(
                    'a.id as app_id',
                    'a.status',
                    'a.tanggal_lamar',
                    'j.id as job_id',
                    'j.posisi',
                    'j.status_loker',
                    'u.nama_lengkap',
                    'u.email as user_email',
                    'u.foto_profil',
                    'pl.cv_file',
                    'pl.skills',
                    'pl.pendidikan',
                    'pl.pengalaman',
                    'pl.no_hp'
                )
                ->where('p.user_id', $user_id)
                ->whereNull('j.deleted_at');

            if (!empty($search_p)) {
                $query->where('u.nama_lengkap', 'LIKE', "%$search_p%");
            }

            if (!empty($filter_status)) {
                $query->where('a.status', $filter_status);
            }

            $q_history = $query->orderBy('a.created_at', 'desc')->get();

            return view('dashboard', compact(
                'role',
                'nama_user',
                'nama_perusahaan',
                'nav_foto',
                'display_stats',
                'q_history',
                'search_p',
                'filter_status',
                'is_logged_in',
                'unread_count',
                'notif_result'
            ));
        }

        Auth::logout();
        return redirect()->route('login');
    }
    public function readAll(Request $request)
{
    $user_id = Auth::id();

    // Ubah semua notifikasi user ini menjadi sudah dibaca
    DB::table('notifications')
        ->where('user_id', $user_id)
        ->where('is_read', 0)
        ->update(['is_read' => 1]);

    return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
}
}