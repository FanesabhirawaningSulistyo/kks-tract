<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->bigIncrements('id_perusahaan');

            // Relasi ke user
            $table->unsignedBigInteger('id_user_perusahaan')->nullable();

            // Data perwakilan perusahaan (person in charge)
            $table->string('nama_perwakilan', 100);
            $table->string('email_perwakilan', 100)->unique();
            $table->string('telepon_perwakilan', 20)->nullable();

            // Data perusahaan
            $table->string('logo_perusahaan', 255)->nullable();
            $table->text('alamat_perusahaan');

            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

            // PENTING: Ubah onDelete menjadi SET NULL agar bisa hapus user dulu
            $table->foreign('id_user_perusahaan')
                ->references('id_user')
                ->on('users')
                ->onDelete('set null')  // SET NULL biar bisa hapus user dulu
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};
