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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('email'); // Adiciona 'role' após o campo 'email'
            $table->text('bio')->nullable()->after('role'); // Adiciona 'bio' após o campo 'role'
            $table->string('profession')->nullable()->after('bio'); // Adiciona 'profession' após o campo 'bio'
            $table->string('profileImage')->nullable()->after('profession'); // Adiciona 'profile_image' após o campo 'profession'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'bio', 'profession', 'profileImage']);
        });
    }
};
