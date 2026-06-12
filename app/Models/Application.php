<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'applications';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'job_id',
        'pelamar_id',
        'pesan',
        'status',
        'tanggal_lamar'

    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'tanggal_lamar' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI JOB
    |--------------------------------------------------------------------------
    */

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI PELAMAR
    |--------------------------------------------------------------------------
    */

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class);
    }
}