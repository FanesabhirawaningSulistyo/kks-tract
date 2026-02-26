<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_roles', function (Blueprint $table) {
            $table->bigIncrements('id_job_role'); // Primary Key
            $table->string('nama_job_role', 100)->unique(); // Nama posisi (Web Developer, SEO, dll)
            $table->text('deskripsi')->nullable(); // Deskripsi tugas
            $table->boolean('status')->default(true); // Aktif / nonaktif

            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_roles');
    }
};
