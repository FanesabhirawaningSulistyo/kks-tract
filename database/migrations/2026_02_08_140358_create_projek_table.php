<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projek', function (Blueprint $table) {
            $table->bigIncrements('id_projek');
            $table->unsignedBigInteger('id_perusahaan');
            $table->string('nama_projek', 150);
            $table->string('kategori', 100);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_pesan');
            $table->enum('status', ['pending', 'disetujui', 'berjalan', 'selesai', 'batal'])->default('pending');

            // 💰 Tambahan
            $table->decimal('nominal_projek', 15, 2)->default(0.00);   // total nilai proyek
            $table->decimal('sisa_tanggungan', 15, 2)->default(0.00); // sisa pembayaran
            $table->string('dokumen_perjanjian', 255)->nullable(); // dokumen perjanjian

            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->unsignedBigInteger('dibuat_oleh');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

            // Relasi ke tabel perusahaan
            $table->foreign('id_perusahaan')
                ->references('id_perusahaan')
                ->on('perusahaan')
                ->onDelete('cascade');

            // Relasi ke tabel users (asumsi id_user ada di tabel users)
            $table->foreign('dibuat_oleh')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projek');
    }
};
