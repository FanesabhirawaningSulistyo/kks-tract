<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendapatan', function (Blueprint $table) {
            $table->bigIncrements('id_pendapatan');
            $table->unsignedBigInteger('id_projek');
            $table->decimal('nominal_projek', 15, 2);
            $table->date('tanggal_pembayaran');
            $table->text('catatan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

            // Relasi ke tabel projek
            $table->foreign('id_projek')
                ->references('id_projek')
                ->on('projek')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendapatan');
    }
};
