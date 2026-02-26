<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas_foto', function (Blueprint $table) {
            $table->bigIncrements('id_tugas_foto');
            $table->unsignedBigInteger('id_tugas');

            $table->string('nama_file');

            $table->enum('tipe', [
                'brief',   // contoh desain dari PM
                'hasil'        // hasil kerja karyawan
            ])->default('hasil');

            $table->timestamp('dibuat_pada')->useCurrent();

            $table->timestamp('diperbarui_pada')->useCurrent();


            $table->foreign('id_tugas')
                ->references('id_tugas')
                ->on('tugas')
                ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_foto');
    }
};
