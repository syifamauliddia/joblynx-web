<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'jobs';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'perusahaan_id',
        'posisi',
        'lokasi',
        'tipe_pekerjaan',
        'gaji_min',
        'gaji_max',
        'syarat_skill',
        'deskripsi',
        'status_loker'
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'gaji_min' => 'integer',
        'gaji_max' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI PERUSAHAAN
    |--------------------------------------------------------------------------
    */

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI USER (PEMILIK PERUSAHAAN)
    |--------------------------------------------------------------------------
    | Menambahkan relasi ke User agar Admin bisa memanggil with('user')
    */

    public function user()
    {
        // Lowongan -> Perusahaan -> User
        return $this->hasOneThrough(
            User::class,     // Model akhir (User)
            Perusahaan::class, // Model perantara (Perusahaan)
            'id',            // Foreign key di tabel Perusahaan
            'id',            // Foreign key di tabel User
            'perusahaan_id', // Local key di tabel Jobs
            'user_id'        // Local key di tabel Perusahaan
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}