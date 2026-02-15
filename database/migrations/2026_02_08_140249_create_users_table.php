<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id_user'); // Primary Key
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);

            $table->enum('role', ['admin', 'pm', 'karyawan', 'klien'])
                ->default('karyawan');

            // 🔗 Relasi ke job_roles
            $table->unsignedBigInteger('id_job_role')->nullable();

            $table->string('no_hp', 20)->nullable();
            $table->string('foto', 255)->nullable();

            $table->boolean('status')->default(true); // Aktif / nonaktif

            $table->rememberToken();
            $table->timestamps();

            // Foreign Key
            $table->foreign('id_job_role')
                ->references('id_job_role')
                ->on('job_roles')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 100)->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
