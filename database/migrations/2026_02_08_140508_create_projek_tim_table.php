<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projek_tim', function (Blueprint $table) {
            $table->bigIncrements('id_tim');
            $table->unsignedBigInteger('id_projek');
            $table->unsignedBigInteger('id_user');
            $table->string('job_role_projek', 100)->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();

            // Relasi ke projek
            $table->foreign('id_projek')
                ->references('id_projek')
                ->on('projek')
                ->onDelete('cascade');

            // Relasi ke users
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projek_tim');
    }
};
