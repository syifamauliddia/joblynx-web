<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
 
class LokerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HELPER — ambil data perusahaan milik HR yang sedang login
    |--------------------------------------------------------------------------
    */
    private function getPerusahaan()
    {
        return DB::table('perusahaans')
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->first();
    }
 
    /*
    |--------------------------------------------------------------------------
    | FORM PASANG LOWONGAN
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
 
        if (Auth::user()->role !== 'hr') {
            abort(403, 'Hanya HR yang dapat mengakses halaman ini.');
        }
 
        $user       = Auth::user();
        $perusahaan = $this->getPerusahaan();
        $nama_user  = $user->nama_lengkap ?? $user->email;
 
        return view('pasang_lowongan', compact('nama_user', 'perusahaan'));
    }
 
    /*
    |--------------------------------------------------------------------------
    | SIMPAN LOWONGAN
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'hr') {
            return redirect('/login');
        }
 
        $request->validate([
            'posisi'         => 'required|string|max:255',
            'lokasi'         => 'required|string|max:255',
            'tipe_pekerjaan' => 'required|string|max:100',
            'syarat_skill'   => 'required|string',
            'deskripsi'      => 'required|string',
            'gaji_min'       => 'nullable|numeric|min:0',
            'gaji_max'       => 'nullable|numeric|min:0',
        ], [
            'posisi.required'         => 'Posisi pekerjaan wajib diisi.',
            'lokasi.required'         => 'Lokasi pekerjaan wajib diisi.',
            'tipe_pekerjaan.required' => 'Tipe pekerjaan wajib dipilih.',
            'syarat_skill.required'   => 'Syarat skill wajib diisi.',
            'deskripsi.required'      => 'Deskripsi pekerjaan wajib diisi.',
        ]);
 
        if (
            !empty($request->gaji_min)
            && !empty($request->gaji_max)
            && $request->gaji_min > $request->gaji_max
        ) {
            return back()
                ->withErrors(['gaji_max' => 'Gaji maksimal harus lebih besar dari gaji minimal.'])
                ->withInput();
        }
 
        // FIX: ambil perusahaan di scope store()
        $perusahaan = $this->getPerusahaan();
 
        DB::table('jobs')->insert([
            'perusahaan_id'  => $perusahaan->id ?? Auth::id(), // FIX: pakai id perusahaan
            'posisi'         => trim($request->posisi),
            'lokasi'         => trim($request->lokasi),
            'tipe_pekerjaan' => trim($request->tipe_pekerjaan),
            'gaji_min'       => $request->gaji_min ? (int) $request->gaji_min : 0,
            'gaji_max'       => $request->gaji_max ? (int) $request->gaji_max : 0,
            'syarat_skill'   => trim($request->syarat_skill),
            'deskripsi'      => trim($request->deskripsi),
            'status_loker'   => 'Aktif',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
 
        return redirect('/dashboard')
            ->with('success', 'Lowongan kerja berhasil dipublikasikan!');
    }
 
    /*
    |--------------------------------------------------------------------------
    | FORM EDIT LOWONGAN
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'hr') {
            return redirect('/login');
        }
 
        $perusahaan = $this->getPerusahaan();
 
        if (!$perusahaan) {
            return redirect('/dashboard')
                ->withErrors(['error' => 'Data perusahaan tidak ditemukan.']);
        }
 
        // FIX: cek kepemilikan pakai perusahaan->id bukan Auth::id()
        $data_loker = DB::table('jobs')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan->id)
            ->whereNull('deleted_at')
            ->first();
 
        if (!$data_loker) {
            return redirect('/dashboard')
                ->withErrors(['error' => 'Data lowongan tidak ditemukan.']);
        }
 
        return view('edit_loker', compact('data_loker'));
    }
 
    /*
    |--------------------------------------------------------------------------
    | UPDATE LOWONGAN
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'hr') {
            return redirect('/login');
        }
 
        $request->validate([
            'posisi'         => 'required|string|max:255',
            'lokasi'         => 'required|string|max:255',
            'tipe_pekerjaan' => 'required|string|max:100',
            'syarat_skill'   => 'required|string',
            'deskripsi'      => 'required|string',
            'status_loker'   => 'required|in:Aktif,Tutup',
            'gaji_min'       => 'nullable|numeric|min:0',
            'gaji_max'       => 'nullable|numeric|min:0',
        ], [
            'posisi.required'         => 'Posisi pekerjaan wajib diisi.',
            'lokasi.required'         => 'Lokasi pekerjaan wajib diisi.',
            'tipe_pekerjaan.required' => 'Tipe pekerjaan wajib dipilih.',
            'syarat_skill.required'   => 'Syarat skill wajib diisi.',
            'deskripsi.required'      => 'Deskripsi pekerjaan wajib diisi.',
            'status_loker.required'   => 'Status lowongan wajib dipilih.',
        ]);
 
        if (
            !empty($request->gaji_min)
            && !empty($request->gaji_max)
            && $request->gaji_min > $request->gaji_max
        ) {
            return back()
                ->withErrors(['gaji_max' => 'Gaji maksimal harus lebih besar dari gaji minimal.'])
                ->withInput();
        }
 
        // FIX: ambil perusahaan dulu, lalu cek kepemilikan pakai perusahaan->id
        $perusahaan = $this->getPerusahaan();
 
        if (!$perusahaan) {
            abort(403, 'Data perusahaan tidak ditemukan.');
        }
 
        $check = DB::table('jobs')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan->id)
            ->whereNull('deleted_at')
            ->first();
 
        if (!$check) {
            abort(403, 'Akses ditolak.');
        }
 
        DB::table('jobs')->where('id', $id)->update([
            'posisi'         => trim($request->posisi),
            'lokasi'         => trim($request->lokasi),
            'tipe_pekerjaan' => trim($request->tipe_pekerjaan),
            'gaji_min'       => $request->gaji_min ? (int) $request->gaji_min : 0,
            'gaji_max'       => $request->gaji_max ? (int) $request->gaji_max : 0,
            'syarat_skill'   => trim($request->syarat_skill),
            'deskripsi'      => trim($request->deskripsi),
            'status_loker'   => $request->status_loker,
            'updated_at'     => now(),
        ]);
 
        return redirect('/beranda')
            ->with('success', 'Data lowongan berhasil diperbarui!');
    }
 
    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS LOWONGAN
    |--------------------------------------------------------------------------
    */
    public function toggleStatus(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'hr') {
            return redirect('/login');
        }
 
        // FIX: cek kepemilikan pakai perusahaan->id bukan Auth::id()
        $perusahaan = $this->getPerusahaan();
 
        if (!$perusahaan) {
            abort(403, 'Data perusahaan tidak ditemukan.');
        }
 
        $aksi = $request->query('aksi');
 
        $cek = DB::table('jobs')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan->id)
            ->whereNull('deleted_at')
            ->first();
 
        if (!$cek) {
            abort(403, 'Akses ditolak.');
        }
 
        $status = $aksi === 'tutup' ? 'Tutup' : 'Aktif';
 
        DB::table('jobs')->where('id', $id)->update([
            'status_loker' => $status,
            'updated_at'   => now(),
        ]);
 
        return redirect('/beranda#daftar-lowongan')
            ->with('success', 'Status lowongan berhasil diperbarui.');
    }
 
    /*
    |--------------------------------------------------------------------------
    | HAPUS LOWONGAN
    |--------------------------------------------------------------------------
    */
    public function hapus($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'hr') {
            abort(403, 'Akses ditolak.');
        }
 
        // FIX: cek kepemilikan pakai perusahaan->id bukan Auth::id()
        $perusahaan = $this->getPerusahaan();
 
        if (!$perusahaan) {
            return redirect('/beranda')
                ->withErrors(['error' => 'Data perusahaan tidak ditemukan.']);
        }
 
        $check = DB::table('jobs')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan->id)
            ->whereNull('deleted_at')
            ->first();
 
        if (!$check) {
            return redirect('/beranda')
                ->withErrors(['error' => 'Gagal menghapus lowongan.']);
        }
 
        // Soft delete lamaran terkait
        DB::table('applications')->where('job_id', $id)->update([
            'deleted_at' => now(),
            'updated_at' => now(),
        ]);
 
        // Soft delete lowongan
        DB::table('jobs')->where('id', $id)->update([
            'deleted_at' => now(),
            'updated_at' => now(),
        ]);
 
        return redirect('/beranda')
            ->with('success', 'Lowongan berhasil dihapus!');
    }
    /*
    |--------------------------------------------------------------------------
    | RESTORE LOWONGAN
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'hr') {
            abort(403, 'Akses ditolak.');
        }

        $perusahaan = $this->getPerusahaan();

        if (!$perusahaan) {
            return redirect('/beranda')
                ->withErrors(['error' => 'Data perusahaan tidak ditemukan.']);
        }

        $check = DB::table('jobs')
            ->where('id', $id)
            ->where('perusahaan_id', $perusahaan->id)
            ->first();

        if (!$check) {
            return redirect('/beranda')
                ->withErrors(['error' => 'Gagal memulihkan lowongan.']);
        }

        // 1. Pulihkan lowongan (hapus deleted_at)
        DB::table('jobs')->where('id', $id)->update([
            'deleted_at' => null,
            'status_loker' => 'Aktif', // Otomatis aktif kembali
            'updated_at' => now(),
        ]);

        // 2. Pulihkan juga lamaran yang terkait (karena ikut di-soft delete saat dihapus)
        DB::table('applications')->where('job_id', $id)->update([
            'deleted_at' => null,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Lowongan berhasil dipulihkan!');
    }
}
