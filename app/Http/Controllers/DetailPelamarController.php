<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DetailPelamarController extends Controller
{
    public function index($id)
    {
        /*
        |--------------------------------------------------------------------------
        | CEK LOGIN & ROLE
        |--------------------------------------------------------------------------
        */
        if (!Auth::check() || Auth::user()->role !== 'hr') {
            return redirect()->route('beranda');
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL PERUSAHAAN_ID YANG BENAR
        |--------------------------------------------------------------------------
        */
        // Kita harus mencari ID perusahaan milik HR yang sedang login
        $perusahaan = DB::table('perusahaans')->where('user_id', Auth::id())->first();
        
        if (!$perusahaan) {
            abort(403, 'Data perusahaan tidak ditemukan.');
        }
        
        $perusahaan_id = $perusahaan->id;

        /*
        |--------------------------------------------------------------------------
        | AMBIL DETAIL PELAMAR
        |--------------------------------------------------------------------------
        */
        $data = DB::table('applications as a')
            ->join('jobs as j', 'a.job_id', '=', 'j.id')
            ->join('pelamars as p', 'a.pelamar_id', '=', 'p.id')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->select(
                'a.id as application_id',
                'a.status',
                'a.pesan',
                'a.tanggal_lamar',
                'j.id as job_id',
                'j.posisi',
                'j.lokasi',
                'j.tipe_pekerjaan',
                'u.id as user_id',
                'u.nama_lengkap',
                'u.email',
                'u.foto_profil',
                'p.skills',
                'p.no_hp',
                'p.pendidikan',
                'p.pengalaman',
                'p.cv_file',
                'p.alamat'
            )
            ->where('a.id', $id)
            ->where('j.perusahaan_id', $perusahaan_id) // Sekarang $perusahaan_id sudah benar
            ->whereNull('a.deleted_at')
            ->whereNull('j.deleted_at')
            ->first();
            

        /*
        |--------------------------------------------------------------------------
        | VALIDASI DATA
        |--------------------------------------------------------------------------
        */
        if (!$data) {
            abort(403, 'Akses ditolak atau data lamaran tidak ditemukan.');
        }

        /*
        |--------------------------------------------------------------------------
        | FORMAT SKILLS
        |--------------------------------------------------------------------------
        */
        $skills_arr = [];
        if (!empty($data->skills)) {
            $skills = explode(',', $data->skills);
            foreach ($skills as $skill) {
                $parts = explode(':', $skill);
                if (count($parts) == 2) {
                    $skill_name = trim($parts[0]);
                    $percentage = (int) trim($parts[1]);
                    $skills_arr[$skill_name] = $percentage;
                }
            }
        }

        return view('detail_pelamar', compact('data', 'skills_arr'));
    }
}