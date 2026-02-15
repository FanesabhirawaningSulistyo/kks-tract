<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_tugas', function (Blueprint $table) {
            $table->bigIncrements('id_report');
            $table->unsignedBigInteger('id_tugas');
            $table->unsignedBigInteger('id_user');
            $table->string('file', 255);
            $table->text('catatan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();

            // Relasi ke tugas
            $table->foreign('id_tugas')
                ->references('id_tugas')
                ->on('tugas')
                ->onDelete('cascade');

            // Relasi ke users (pegawai yg upload)
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_tugas');
    }
};
