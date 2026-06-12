<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('pelamar_id');

            $table->unsignedBigInteger('job_id');

            $table->enum('status', [
                'Dikirim',
                'Diproses',
                'Interview',
                'Diterima',
                'Ditolak',
                'Dibatalkan'
            ])->default('Dikirim');

            $table->timestamp('tanggal_lamar')
                ->useCurrent();

            $table->timestamps();

            // SOFT DELETE
            $table->softDeletes();

            $table->foreign('pelamar_id')
                ->references('id')
                ->on('pelamars')
                ->onDelete('cascade');

            $table->foreign('job_id')
                ->references('id')
                ->on('jobs')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};