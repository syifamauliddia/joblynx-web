<?php

namespace App\Http\Controllers;

use App\Models\ExportLog;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Exports\UsersExport;
use App\Exports\PerusahaanExport;

class AdminExportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | EXPORT USERS – EXCEL
    | Query params: ?role=user|hr  &  ?search=kata_kunci
    |--------------------------------------------------------------------------
    */
    public function exportUsersExcel(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses ditolak.');
        Carbon::setLocale('id');

        ExportLog::create([
            'user_id'    => Auth::id(),
            'jenis_data' => 'users',
            'format'     => 'excel',
        ]);

        $suffix   = $this->usersSuffix($request);
        $filename = 'users' . $suffix . '_' . date('Ymd') . '.xlsx';

        return Excel::download(new UsersExport($request), $filename);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT USERS – PDF
    | Query params: ?role=user|hr  &  ?search=kata_kunci
    |--------------------------------------------------------------------------
    */
    public function exportUsersPdf(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses ditolak.');
        Carbon::setLocale('id');

        ExportLog::create([
            'user_id'    => Auth::id(),
            'jenis_data' => 'users',
            'format'     => 'pdf',
        ]);

        $search = $request->search;
        $role   = $request->role;

        $users = User::whereIn('role', ['user', 'hr'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('email', 'like', "%$search%")
                        ->orWhere('nama_lengkap', 'like', "%$search%");
                });
            })
            ->when($role, function ($q) use ($role) {
                $q->where('role', $role);
            })
            ->latest()
            ->get();

        $exportMeta = [
            'filter_role'   => $role   ? strtoupper($role) : 'Semua Role',
            'filter_search' => $search ?: '-',
             'tanggal'       => Carbon::now()->translatedFormat('d F Y, H:i') . ' WIB',
        ];

        $suffix   = $this->usersSuffix($request);
        $filename = 'users' . $suffix . '_' . date('Ymd') . '.pdf';

        $pdf = Pdf::loadView('admin.exports.users_pdf', compact('users', 'exportMeta'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT PERUSAHAAN – EXCEL
    | Query params: ?status=Aktif|Nonaktif  &  ?search=kata_kunci
    |--------------------------------------------------------------------------
    */
    public function exportPerusahaanExcel(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses ditolak.');
        Carbon::setLocale('id');

        ExportLog::create([
            'user_id'    => Auth::id(),
            'jenis_data' => 'perusahaan',
            'format'     => 'excel',
        ]);

        $suffix   = $this->perusahaanSuffix($request);
        $filename = 'perusahaan' . $suffix . '_' . date('Ymd') . '.xlsx';

        return Excel::download(new PerusahaanExport($request), $filename);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT PERUSAHAAN – PDF
    | Query params: ?status=Aktif|Nonaktif  &  ?search=kata_kunci
    |--------------------------------------------------------------------------
    */
    public function exportPerusahaanPdf(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses ditolak.');
        Carbon::setLocale('id');

        ExportLog::create([
            'user_id'    => Auth::id(),
            'jenis_data' => 'perusahaan',
            'format'     => 'pdf',
        ]);

        $search = $request->search;
        $status = $request->status;

        $perusahaan = Perusahaan::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%$search%");
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->get();

        $exportMeta = [
            'filter_status' => $status ?: 'Semua Status',
            'filter_search' => $search ?: '-',
            'tanggal'       => Carbon::now()->translatedFormat('d F Y, H:i') . ' WIB',
        ];

        $suffix   = $this->perusahaanSuffix($request);
        $filename = 'perusahaan' . $suffix . '_' . date('Ymd') . '.pdf';

        $pdf = Pdf::loadView('admin.exports.perusahaan_pdf', compact('perusahaan', 'exportMeta'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVATE HELPERS – nama file deskriptif
    |--------------------------------------------------------------------------
    */
    private function usersSuffix(Request $request): string
    {
        $parts = [];
        if ($request->role)   $parts[] = $request->role;
        if ($request->search) $parts[] = 'search';
        return $parts ? '_' . implode('_', $parts) : '_semua';
    }

    private function perusahaanSuffix(Request $request): string
    {
        $parts = [];
        if ($request->status) $parts[] = strtolower($request->status);
        if ($request->search)  $parts[] = 'search';
        return $parts ? '_' . implode('_', $parts) : '_semua';
    }
}
