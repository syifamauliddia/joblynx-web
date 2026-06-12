<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SkillController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN SKILL
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'user') {
            return redirect('beranda')
                ->with('error', 'Hanya pencari kerja yang dapat mengakses halaman skill.');
        }

        $userId = Auth::id();

        $user = DB::table('users')->where('id', $userId)->first();

        $pelamar = DB::table('pelamars')->where('user_id', $userId)->first();

        $nama_user = $user->nama_lengkap ?? $user->email ?? '';

        $userSkills = $pelamar->skills ?? '';

        $saved_skills_array = [];
        $saved_percentages = [];
        $custom_skills_data = [];

        $all_default_skills = [
            'UI/UX',
            'HTML',
            'CSS',
            'JavaScript',
            'React',
            'Laravel',
            'PHP',
            'MySQL',
            'Python',
            'Data Analysis'
        ];

        /*
        |--------------------------------------------------------------------------
        | PARSE SKILL STRING
        |--------------------------------------------------------------------------
        */
        if (!empty($userSkills)) {

            $pairs = explode(',', $userSkills);

            foreach ($pairs as $pair) {

                $parts = explode(':', $pair);

                if (count($parts) === 2) {

                    $name = trim($parts[0]);
                    $val = (int) trim($parts[1]);

                    if (in_array($name, $all_default_skills)) {

                        $saved_skills_array[] = $name;
                        $saved_percentages[$name] = $val;

                    } else {

                        $custom_skills_data[] = [
                            'name' => $name,
                            'val' => $val
                        ];
                    }
                }
            }
        }

        return view('skill', compact(
            'nama_user',
            'all_default_skills',
            'saved_skills_array',
            'saved_percentages',
            'custom_skills_data'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN SKILL
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'user') {
            return redirect('beranda')
                ->with('error', 'Akses ditolak.');
        }

        $userId = Auth::id();

        $request->validate([
            'skills' => 'nullable|array',
            'custom_skill_name' => 'nullable|array',
            'custom_skill_val' => 'nullable|array'
        ]);

        $final_data = [];

        /*
        |--------------------------------------------------------------------------
        | DEFAULT SKILL
        |--------------------------------------------------------------------------
        */
        if (!empty($request->skills)) {

            $persen = $request->input('persen', []);

            foreach ($request->skills as $skill) {

                $lvl = isset($persen[$skill])
                    ? (int) $persen[$skill]
                    : 50;

                $lvl = max(0, min(100, $lvl));

                $final_data[] = trim($skill) . ':' . $lvl;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CUSTOM SKILL
        |--------------------------------------------------------------------------
        */
        $custom_names = $request->custom_skill_name ?? [];
        $custom_vals = $request->custom_skill_val ?? [];

        for ($i = 0; $i < count($custom_names); $i++) {

            $c_name = trim(str_replace([':', ','], '', $custom_names[$i] ?? ''));

            if (!empty($c_name)) {

                $val = isset($custom_vals[$i])
                    ? (int) $custom_vals[$i]
                    : 50;

                $val = max(0, min(100, $val));

                $final_data[] = $c_name . ':' . $val;
            }
        }

        $final_data = array_unique($final_data);

        $skills_string = implode(',', $final_data);

        $pelamar = DB::table('pelamars')->where('user_id', $userId)->first();

        if ($pelamar) {

            DB::table('pelamars')
                ->where('user_id', $userId)
                ->update([
                    'skills' => $skills_string,
                    'updated_at' => now()
                ]);

        } else {

            DB::table('pelamars')
                ->insert([
                    'user_id' => $userId,
                    'skills' => $skills_string,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Skill berhasil disimpan!');
    }
}