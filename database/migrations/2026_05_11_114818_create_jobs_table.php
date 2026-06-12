<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('perusahaan_id');

            $table->string('posisi');

            $table->string('lokasi');

            $table->enum('tipe_pekerjaan', [
                'Full Time',
                'Part Time',
                'Internship',
                'Contract'
            ]);

            $table->integer('gaji_min')->default(0);

            $table->integer('gaji_max')->default(0);

            $table->text('syarat_skill');

            $table->longText('deskripsi');

            $table->enum('status_loker', [
                'Aktif',
                'Tutup'
            ])->default('Aktif');

            $table->timestamps();

            // SOFT DELETE
            $table->softDeletes();

            $table->foreign('perusahaan_id')
                ->references('id')
                ->on('perusahaans')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};