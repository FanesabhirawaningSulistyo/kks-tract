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
            $table->unsignedBigInteger('dibuat_oleh'); // user pembuat projek

            $table->string('nama_projek', 150);
            $table->unsignedBigInteger('id_kategori_projek')->nullable();
            $table->text('deskripsi')->nullable();

            $table->enum('status', [
                'pending',
                'in_progress',
                'selesai',
                'aktif'
            ])->default('pending');

            $table->decimal('nominal_projek', 15, 2)->default(0.00);
            $table->decimal('sisa_tanggungan', 15, 2)->default(0.00);

            $table->string('dokumen_perjanjian', 255)->nullable();

            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();

            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            // FK ke perusahaan
            $table->foreign('id_perusahaan')
                ->references('id_perusahaan')
                ->on('perusahaan')
                ->onDelete('cascade');

            // FK ke kategori_projek
            $table->foreign('id_kategori_projek')
                ->references('id_kategori_projek')
                ->on('kategori_projek')
                ->onDelete('set null')
                ->onUpdate('cascade');

            // FK ke users (pembuat projek)
            $table->foreign('dibuat_oleh')
                ->references('id_user')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projek');
    }
};
