<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run(): void
    {
        DB::table('users')->insert([
            'nama_lengkap' => 'Administrator',
            'email'        => 'admin@joblynx.com',
            'password'     => Hash::make('admin123'),
            'role'         => 'admin',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }
}