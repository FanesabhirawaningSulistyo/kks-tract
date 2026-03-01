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
            $table->unsignedBigInteger('id_tim'); // anggota tim yang mengerjakan

            $table->string('judul_tugas', 150);
            $table->text('deskripsi_tugas')->nullable();

            $table->enum('level', ['mudah', 'medium', 'susah'])->default('mudah');
            $table->integer('weight')->default(1);

            $table->enum('status_progress', [
                'draft',
                'To Do',
                'In Progress',
                'done'
            ])->default('draft');

            $table->enum('status_akhir', [
                'review',
                'revisi',
                'approved'
            ])->nullable();

            
            $table->date('tanggal_mulai')->nullable();
            $table->date('tenggat_waktu')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_projek')
                ->references('id_projek')
                ->on('projek')
                ->onDelete('cascade');

            $table->foreign('id_tim')
                ->references('id_tim')
                ->on('projek_tim')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};