<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_projek', function (Blueprint $table) {

            $table->id('id_pembayaran');

            $table->string('kode_pembayaran')->unique();

            $table->unsignedBigInteger('id_projek');
            $table->unsignedBigInteger('id_petugas');
            $table->unsignedBigInteger('id_metode_pembayaran');

            $table->decimal('jumlah_bayar', 15, 2);

            $table->date('tanggal_bayar');

            $table->string('bukti_bayar')->nullable();

            $table->enum('status', ['draft', 'valid', 'batal'])
                ->default('valid');

            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            /*
            RELASI
            */

            $table->foreign('id_projek')
                ->references('id_projek')
                ->on('projek')
                ->cascadeOnDelete();

            $table->foreign('id_petugas')
                ->references('id_user')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('id_metode_pembayaran')
                ->references('id_metode_pembayaran')
                ->on('metode_pembayaran')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_projek');
    }
};
