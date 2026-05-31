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
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

    
            $table->unique(['id_projek', 'id_user']);

            // FK ke projek
            $table->foreign('id_projek')
                ->references('id_projek')
                ->on('projek')
                ->onDelete('cascade');

            // FK ke users
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
