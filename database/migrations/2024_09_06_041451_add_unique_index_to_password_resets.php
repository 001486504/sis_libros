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
        Schema::table('password_resets', function (Blueprint $table) {
            // Agregar índice único al campo 'token' si no existe
            if (!Schema::hasColumn('password_resets', 'token')) {
                $table->string('token')->unique();
            }

            // Agregar columna 'email' con índice si no existe
            if (!Schema::hasColumn('password_resets', 'email')) {
                $table->string('email')->index();
            }

            // Agregar columna 'created_at' si no existe
            if (!Schema::hasColumn('password_resets', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_resets', function (Blueprint $table) {
            // Eliminar índice único del campo 'token'
            if (Schema::hasColumn('password_resets', 'token')) {
                $table->dropUnique(['token']);
            }

            // Eliminar la columna 'email' si existe
            if (Schema::hasColumn('password_resets', 'email')) {
                $table->dropColumn('email');
            }

            // Eliminar columna 'created_at' si existe
            if (Schema::hasColumn('password_resets', 'created_at')) {
                $table->dropColumn('created_at');
            }
    });
    }
};
