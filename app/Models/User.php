<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'users';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'nama_lengkap',
        'email',
        'password',
        'role',
        'foto_profil'

    ];

    /*
    |--------------------------------------------------------------------------
    | HIDDEN
    |--------------------------------------------------------------------------
    */

    protected $hidden = [

        'password',
        'remember_token',

    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'email_verified_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI PELAMAR
    |--------------------------------------------------------------------------
    */

    public function pelamar()
    {
        return $this->hasOne(Pelamar::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI PERUSAHAAN
    |--------------------------------------------------------------------------
    */

    public function perusahaan()
    {
        return $this->hasOne(Perusahaan::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI APPLICATION
    |--------------------------------------------------------------------------
    */

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}