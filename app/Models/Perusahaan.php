<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perusahaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'perusahaans';

    protected $fillable = [

        'user_id',
        'nama_perusahaan',
        'bio_perusahaan',
        'website_perusahaan',
        'logo_perusahaan'

    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI USER
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI JOBS
    |--------------------------------------------------------------------------
    */

    public function jobs()
    {
        return $this->hasMany(Job::class, 'perusahaan_id');
    }
}