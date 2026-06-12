<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelamar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pelamars';

    protected $fillable = [

        'user_id',
        'pendidikan',
        'pengalaman',
        'no_hp',
        'alamat',
        'skills',
        'cv_file'

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
}