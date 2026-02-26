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
            $table->unsignedBigInteger('id_user_perusahaan')->nullable();

            // ✅ Tambahkan 3 kolom ini (sinkronisasi dari users)
            $table->string('nama_perusahaan', 100)->nullable();
            $table->string('email_perusahaan', 100)->nullable();
            $table->string('telepon_perusahaan', 20)->nullable();

            // Data perwakilan (PIC)
            $table->string('nama_perwakilan', 100);
            $table->string('email_perwakilan', 100)->unique();
            $table->string('telepon_perwakilan', 20)->nullable();

            $table->string('logo_perusahaan', 255)->nullable();
            $table->text('alamat_perusahaan');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_user_perusahaan')
                ->references('id_user')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};
