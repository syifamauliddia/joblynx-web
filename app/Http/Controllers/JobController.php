<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    /**
     * AMBIL PERUSAHAAN SESUAI USER LOGIN HR
     */
    private function getPerusahaan()
    {
        return DB::table('perusahaans')
            ->where('user_id', Auth::id())
            ->first();
    }

    /**
     * LIST LOWONGAN
     */
    public function index()
    {
        $jobs = Job::query()
            ->leftJoin('perusahaans', 'jobs.perusahaan_id', '=', 'perusahaans.id')
            ->select(
                'jobs.*',
                'perusahaans.nama_perusahaan',
                'perusahaans.logo_perusahaan'
            )
            ->latest('jobs.created_at')
            ->get();

        return view('jobs.index', compact('jobs'));
    }

    /**
     * FORM CREATE
     */
    public function create()
    {
        if (!Auth::check() || Auth::user()->role !== 'hr') {
            abort(403);
        }

        // 🔥 kirim perusahaan ke view
        $perusahaan = $this->getPerusahaan();

        return view('jobs.create', compact('perusahaan'));
    }

    /**
     * STORE LOWONGAN
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Auth::check() || Auth::user()->role !== 'hr') {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'posisi' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tipe_pekerjaan' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'syarat_skill' => 'required|string',
            'gaji_min' => 'nullable|numeric|min:0',
            'gaji_max' => 'nullable|numeric|min:0',
        ]);

        // 🔥 AMBIL PERUSAHAAN HR LOGIN
        $perusahaan = $this->getPerusahaan();

        if (!$perusahaan) {
            return back()->withErrors([
                'error' => 'Perusahaan belum terdaftar di akun ini.'
            ]);
        }

        // VALIDASI GAJI
        if (
            !empty($validated['gaji_min']) &&
            !empty($validated['gaji_max']) &&
            $validated['gaji_min'] > $validated['gaji_max']
        ) {
            return back()->withErrors([
                'gaji_max' => 'Gaji maksimum harus lebih besar dari minimum.'
            ]);
        }

        // 🔥 FIX FK ERROR DI SINI
        Job::create([
            'perusahaan_id' => $perusahaan->id, // ✔️ WAJIB BENAR
            'posisi' => $validated['posisi'],
            'lokasi' => $validated['lokasi'],
            'tipe_pekerjaan' => $validated['tipe_pekerjaan'],
            'deskripsi' => $validated['deskripsi'],
            'syarat_skill' => $validated['syarat_skill'],
            'gaji_min' => $validated['gaji_min'] ?? 0,
            'gaji_max' => $validated['gaji_max'] ?? 0,
            'status_loker' => 'Aktif',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Lowongan berhasil dibuat.');
    }

    /**
     * EDIT
     */
    public function edit(int $id)
    {
        $perusahaan = $this->getPerusahaan();

        $data_loker = Job::where('id', $id)
            ->where('perusahaan_id', $perusahaan->id ?? 0)
            ->first();

        return view('jobs.edit', compact('data_loker'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $perusahaan = $this->getPerusahaan();

        $job = Job::where('id', $id)
            ->where('perusahaan_id', $perusahaan->id ?? 0)
            ->first();

        if (!$job) abort(404);

        $job->update($request->all());

        return back()->with('success', 'Updated');
    }

    /**
     * DELETE
     */
    public function destroy(int $id): RedirectResponse
    {
        $perusahaan = $this->getPerusahaan();

        $job = Job::where('id', $id)
            ->where('perusahaan_id', $perusahaan->id ?? 0)
            ->first();

        if (!$job) abort(404);

        $job->delete();

        return back()->with('success', 'Deleted');
    }
}