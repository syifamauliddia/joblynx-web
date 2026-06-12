<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Models\Pelamar;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | DASHBOARD
    |----------------------------------------------------------------------
    */
    public function index()
    {
        $total_user = User::where('role', 'user')->count();
        $total_hr = User::where('role', 'hr')->count();
        $total_jobs = Job::count();
        $total_applications = Application::count();

        $status = [
            'Dikirim'    => Application::where('status', 'Dikirim')->count(),
            'Diproses'   => Application::where('status', 'Diproses')->count(),
            'Interview'  => Application::where('status', 'Interview')->count(),
            'Diterima'   => Application::where('status', 'Diterima')->count(),
            'Ditolak'    => Application::where('status', 'Ditolak')->count(),
            'Dibatalkan' => Application::where('status', 'Dibatalkan')->count(),
        ];


        $activity_lamaran = DB::table('applications as a')
            ->join('pelamars as p', 'a.pelamar_id', '=', 'p.id')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->join('jobs as j', 'a.job_id', '=', 'j.id')
            ->leftJoin('perusahaans as pr', 'j.perusahaan_id', '=', 'pr.id')
            ->select(
                DB::raw("COALESCE(u.nama_lengkap, u.email) as nama_lengkap"),
                'j.posisi',
                DB::raw("COALESCE(pr.nama_perusahaan, 'Perusahaan') as nama_perusahaan"),
                'a.tanggal_lamar as created_at',
                DB::raw("'lamaran' as tipe")
            )
            ->orderBy('a.tanggal_lamar', 'desc')
            ->limit(5)
            ->get();

        $activity_jobs = DB::table('jobs as j')
            ->leftJoin('perusahaans as pr', 'j.perusahaan_id', '=', 'pr.id')
            ->select(
                DB::raw("'HRD' as nama_lengkap"),
                'j.posisi',
                DB::raw("COALESCE(pr.nama_perusahaan, 'Perusahaan') as nama_perusahaan"),
                'j.created_at',
                DB::raw("'posting' as tipe")
            )
            ->orderBy('j.created_at', 'desc')
            ->limit(5)
            ->get();

        $latest_activity = $activity_lamaran
            ->merge($activity_jobs)
            ->sortByDesc('created_at')
            ->values();

        $users = User::where('role', 'user')
    ->latest()
    ->limit(8)
    ->get();

        // TAMBAHAN NOTIFIKASI
        $user_id = auth()->id();
        $unread_count = DB::table('notifications')
            ->where('user_id', $user_id)
            ->where('is_read', 0)
            ->count();

        $notif_result = DB::table('notifications')
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'total_user',
            'total_hr',
            'total_jobs',
            'total_applications',
            'status',
            'latest_activity',
            'users',
            'unread_count',
            'notif_result'
        ));
    }

    /*
    |----------------------------------------------------------------------
    | USERS
    |----------------------------------------------------------------------
    */
    public function users(Request $request)
    {
        $search = $request->search;

        $users = User::where('role', 'user')
            ->when($search, function ($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                  ->orWhere('nama_lengkap', 'like', "%$search%");
            })
            ->latest()
            ->paginate(10);

        $user_id = auth()->id();
        $unread_count = DB::table('notifications')->where('user_id', $user_id)->where('is_read', 0)->count();
        $notif_result = DB::table('notifications')->where('user_id', $user_id)->orderBy('created_at', 'desc')->limit(10)->get();
        return view('admin.users', compact('users', 'search', 'unread_count', 'notif_result'));    
    }

    /*
    |----------------------------------------------------------------------
    | JOBS (FIX ERROR ROUTE)
    |----------------------------------------------------------------------
    */
    public function jobs()
    {
        $jobs = DB::table('jobs as j')
    ->join('perusahaans as p', 'j.perusahaan_id', '=', 'p.id')
    ->join('users as u', 'p.user_id', '=', 'u.id')
    ->select('j.*', 'p.nama_perusahaan', 'u.nama_lengkap as nama_hr')
    ->get();
    
        $user_id = auth()->id();
        $unread_count = DB::table('notifications')->where('user_id', $user_id)->where('is_read', 0)->count();
        $notif_result = DB::table('notifications')->where('user_id', $user_id)->orderBy('created_at', 'desc')->limit(10)->get();
        return view('admin.jobs', [
            'jobs' => $jobs ?? collect(),
            'unread_count' => $unread_count,
            'notif_result' => $notif_result,
        ]);
    }

    /*
    |----------------------------------------------------------------------
    | PERUSAHAAN (FIX COUNT NULL ERROR)
    |----------------------------------------------------------------------
    */
    public function perusahaan()
    {
        $perusahaan = Perusahaan::latest()->get();

        $user_id = auth()->id();
        $unread_count = DB::table('notifications')->where('user_id', $user_id)->where('is_read', 0)->count();
        $notif_result = DB::table('notifications')->where('user_id', $user_id)->orderBy('created_at', 'desc')->limit(10)->get();
        return view('admin.perusahaan', [
        'perusahaan' => $perusahaan ?? collect(),
        'unread_count' => $unread_count,
        'notif_result' => $notif_result,
        ]);
    }

    /*
    |----------------------------------------------------------------------
    | TOGGLE PERUSAHAAN STATUS
    |----------------------------------------------------------------------
    */
    public function togglePerusahaanStatus($id)
    {
        $data = Perusahaan::find($id);

        if (!$data) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        // Toggle status perusahaan: Aktif ↔ Nonaktif
        $data->status = $data->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $data->save();

        // Cascade ke jobs: Nonaktif → 'Tutup' | Aktif → 'Aktif'
        $jobStatus = $data->status === 'Aktif' ? 'Aktif' : 'Tutup';

        DB::table('jobs')
            ->where('perusahaan_id', $id) // $id = perusahaans.id ✅
            ->update(['status_loker' => $jobStatus]);

        return back()->with('success', 'Status perusahaan dan semua lowongannya berhasil diperbarui.');
    }

    /*
    |----------------------------------------------------------------------
    | DELETE PERUSAHAAN
    |----------------------------------------------------------------------
    */
    public function deletePerusahaan($id)
    {
        Perusahaan::where('id', $id)->delete();
        return back();
    }

    

    /*
    |----------------------------------------------------------------------
    | APPLICATIONS
    |----------------------------------------------------------------------
    */
    public function applications(Request $request)
    {
        $applications = DB::table('applications as a')
            ->join('pelamars as p', 'a.pelamar_id', '=', 'p.id')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->leftJoin('jobs as j', 'a.job_id', '=', 'j.id')
            ->select(
                'a.*',
                DB::raw("COALESCE(u.nama_lengkap, u.email) as nama_lengkap"),
                'u.email',
                DB::raw("COALESCE(j.posisi, 'Posisi Dihapus') as posisi")
            )
            ->orderBy('a.tanggal_lamar', 'desc')
            ->paginate(10);

            $user_id = auth()->id();
            $unread_count = DB::table('notifications')->where('user_id', $user_id)->where('is_read', 0)->count();
            $notif_result = DB::table('notifications')->where('user_id', $user_id)->orderBy('created_at', 'desc')->limit(10)->get();
            return view('admin.applications', compact('applications', 'unread_count', 'notif_result'));    }

    /*
    |----------------------------------------------------------------------
    | DETAIL APPLICATION
    |----------------------------------------------------------------------
    */
    public function detailApplication($id)
    {
        $application = DB::table('applications as a')
            ->join('pelamars as p', 'a.pelamar_id', '=', 'p.id')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->join('jobs as j', 'a.job_id', '=', 'j.id')
            ->select(
                'a.*',
                DB::raw("COALESCE(u.nama_lengkap, u.email) as nama_lengkap"),
                'u.email',
                'j.posisi'
            )
            ->where('a.id', $id)
            ->first();

        if (!$application) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        return view('admin.detail_application', compact('application'));
    }

    /*
    |----------------------------------------------------------------------
    | UPDATE PASSWORD ADMIN
    |----------------------------------------------------------------------
    */
    public function updateAdminPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6'
        ]);

        $user = User::find($id);

        if (!$user || $user->role !== 'admin') {
            return back()->with('error', 'Invalid request');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diupdate');
    }
}

