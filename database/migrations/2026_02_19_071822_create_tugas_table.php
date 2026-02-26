<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->bigIncrements('id_tugas');
            $table->unsignedBigInteger('id_projek');
            $table->string('judul_tugas', 150);
            $table->text('deskripsi_tugas')->nullable();
            $table->enum('level', ['mudah', 'medium', 'susah'])->default('mudah');
            $table->integer('weight')->default(1);
            $table->unsignedBigInteger('penanggung_jawab');
            $table->enum('status', ['draft', 'publis', 'progres', 'done'])->default('draft');
            $table->date('tenggat_waktu')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();

            // Relasi ke projek
            $table->foreign('id_projek')
                ->references('id_projek')
                ->on('projek')
                ->onDelete('cascade');

            // Relasi ke users (pegawai yg ngerjakan)
            $table->foreign('penanggung_jawab')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
