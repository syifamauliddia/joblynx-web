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
        $total_notifications = \App\Models\NotificationBroadcast::count();
        $notif_draft         = \App\Models\NotificationBroadcast::where('status', 'draft')->count();
        $notif_published     = \App\Models\NotificationBroadcast::where('status', 'published')->count();
        $total_export_today  = \App\Models\ExportLog::whereDate('created_at', today())->count();

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
        $total_notifications = \App\Models\NotificationBroadcast::count();
        $notif_draft         = \App\Models\NotificationBroadcast::where('status', 'draft')->count();
        $notif_published     = \App\Models\NotificationBroadcast::where('status', 'published')->count();
        $total_export_today  = \App\Models\ExportLog::whereDate('created_at', today())->count();

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
            'notif_result',
            'total_notifications',
            'notif_draft',
            'notif_published',
            'total_export_today'
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
        $role   = $request->role;

        $users = User::whereIn('role', ['user', 'hr'])

            // Search
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('id', $search)
                        ->orWhere('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })

            // Filter Role
            ->when($role, function ($q) use ($role) {
                $q->where('role', $role);
            })

            ->latest()
            ->paginate(10);

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

        return view('admin.users', compact(
            'users',
            'search',
            'role',
            'unread_count',
            'notif_result'
        ));
    }
    /*
    |----------------------------------------------------------------------
    | JOBS (FIX ERROR ROUTE)
    |----------------------------------------------------------------------
    */
    public function jobs(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $jobs = DB::table('jobs as j')
            ->join('perusahaans as p', 'j.perusahaan_id', '=', 'p.id')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->select(
                'j.*',
                'p.nama_perusahaan',
                'u.nama_lengkap as nama_hr'
            )
            ->whereNull('j.deleted_at')

            // Search
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('j.id', $search)
                        ->orWhere('j.posisi', 'like', "%{$search}%")
                        ->orWhere('j.lokasi', 'like', "%{$search}%")
                        ->orWhere('p.nama_perusahaan', 'like', "%{$search}%")
                        ->orWhere('u.nama_lengkap', 'like', "%{$search}%");
                });
            })

            // Filter Status
            ->when($status, function ($q) use ($status) {
                $q->where('j.status_loker', $status);
            })

            ->get();

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

        return view('admin.jobs', [
            'jobs' => $jobs,
            'search' => $search,
            'status' => $status,
            'unread_count' => $unread_count,
            'notif_result' => $notif_result,
        ]);
    }

    /*
    |----------------------------------------------------------------------
    | PERUSAHAAN (FIX COUNT NULL ERROR)
    |----------------------------------------------------------------------
    */
    public function perusahaan(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $perusahaan = DB::table('perusahaans as p')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->select(
                'p.*',
                'u.nama_lengkap as nama_hr',
                'u.email as email_hr'
            )
            ->whereNull('p.deleted_at')

            // SEARCH
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('p.id', $search)
                        ->orWhere('p.nama_perusahaan', 'like', "%{$search}%")
                        ->orWhere('u.nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('u.email', 'like', "%{$search}%");
                });
            })

            // FILTER STATUS
            ->when($status, function ($q) use ($status) {
                $q->where('p.status', $status);
            })

            ->latest('p.created_at')
            ->paginate(10);

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

        return view('admin.perusahaan', compact(
            'perusahaan',
            'search',
            'status',
            'unread_count',
            'notif_result'
        ));
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
            ->where('perusahaan_id', $id) 
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
        DB::table('jobs')
        ->where('perusahaan_id', $id)
        ->update(['status_loker' => 'Tutup']);
        
        Perusahaan::where('id', $id)->delete();
        return back();
    }

    /*
    |----------------------------------------------------------------------
    | DELETE USER (Soft Delete)
    |----------------------------------------------------------------------
    */
    public function deleteUser($id)
    {
        User::where('id', $id)->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    /*
    |----------------------------------------------------------------------
    | TRASH USERS
    |----------------------------------------------------------------------
    */
    public function trashUsers()
    {
        $users = User::onlyTrashed()->where('role', 'user')->latest()->paginate(10);

        $user_id = auth()->id();
        $unread_count = DB::table('notifications')->where('user_id', $user_id)->where('is_read', 0)->count();
        $notif_result = DB::table('notifications')->where('user_id', $user_id)->orderBy('created_at', 'desc')->limit(10)->get();

        return view('admin.trash_users', compact('users', 'unread_count', 'notif_result'));
    }

    /*
    |----------------------------------------------------------------------
    | RESTORE USER
    |----------------------------------------------------------------------
    */
    public function restoreUser($id)
    {
        User::onlyTrashed()->where('id', $id)->restore();
        return back()->with('success', 'User berhasil dipulihkan.');
    }

    /*
    |----------------------------------------------------------------------
    | TOGGLE JOB STATUS
    |----------------------------------------------------------------------
    */
    public function toggleJobStatus($id)
    {
        $job = Job::find($id);

        if (!$job) {
            return back()->with('error', 'Lowongan tidak ditemukan.');
        }

        $job->status_loker = $job->status_loker === 'Aktif' ? 'Tutup' : 'Aktif';
        $job->save();

        return back()->with('success', 'Status lowongan berhasil diperbarui.');
    }

    /*
    |----------------------------------------------------------------------
    | DELETE JOB (Soft Delete)
    |----------------------------------------------------------------------
    */
    public function deleteJob($id)
    {
        Job::where('id', $id)->delete();
        return back()->with('success', 'Lowongan berhasil dihapus.');
    }

    /*
    |----------------------------------------------------------------------
    | TRASH JOBS
    |----------------------------------------------------------------------
    */
    public function trashJobs()
    {
        $jobs = Job::onlyTrashed()->latest()->paginate(10);

        $user_id = auth()->id();
        $unread_count = DB::table('notifications')->where('user_id', $user_id)->where('is_read', 0)->count();
        $notif_result = DB::table('notifications')->where('user_id', $user_id)->orderBy('created_at', 'desc')->limit(10)->get();

        return view('admin.trash_jobs', compact('jobs', 'unread_count', 'notif_result'));
    }

    /*
    |----------------------------------------------------------------------
    | RESTORE JOB
    |----------------------------------------------------------------------
    */
    public function restoreJob($id)
    {
        Job::onlyTrashed()->where('id', $id)->restore();
        return back()->with('success', 'Lowongan berhasil dipulihkan.');
    }

    /*
    |----------------------------------------------------------------------
    | DELETE APPLICATION (Soft Delete)
    |----------------------------------------------------------------------
    */
    public function deleteApplication($id)
    {
        Application::where('id', $id)->delete();
        return back()->with('success', 'Lamaran berhasil dihapus.');
    }

    /*
    |----------------------------------------------------------------------
    | TRASH APPLICATIONS
    |----------------------------------------------------------------------
    */
    public function trashApplications()
    {
        $applications = Application::onlyTrashed()->latest()->paginate(10);

        $user_id = auth()->id();
        $unread_count = DB::table('notifications')->where('user_id', $user_id)->where('is_read', 0)->count();
        $notif_result = DB::table('notifications')->where('user_id', $user_id)->orderBy('created_at', 'desc')->limit(10)->get();

        return view('admin.trash_applications', compact('applications', 'unread_count', 'notif_result'));
    }

    /*
    |----------------------------------------------------------------------
    | RESTORE APPLICATION
    |----------------------------------------------------------------------
    */
    public function restoreApplication($id)
    {
        Application::onlyTrashed()->where('id', $id)->restore();
        return back()->with('success', 'Lamaran berhasil dipulihkan.');
    }

    /*
    |----------------------------------------------------------------------
    | APPLICATIONS
    |----------------------------------------------------------------------
    */
    public function applications(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

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
            ->whereNull('a.deleted_at')
            ->whereNull('u.deleted_at')

            // Search
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('a.id', $search)
                        ->orWhere('u.nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('u.email', 'like', "%{$search}%")
                        ->orWhere('j.posisi', 'like', "%{$search}%");
                });
            })

            // Filter Status
            ->when($status, function ($q) use ($status) {
                $q->where('a.status', $status);
            })

            ->orderBy('a.tanggal_lamar', 'desc')
            ->paginate(10);

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

        return view('admin.applications', compact(
            'applications',
            'search',
            'status',
            'unread_count',
            'notif_result'
        ));
    }

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

