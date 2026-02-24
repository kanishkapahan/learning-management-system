<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('phone')->nullable()->after('email');
            $table->string('status')->default('active')->after('remember_token');
            $table->string('timezone')->default('Asia/Colombo')->after('status');
            $table->string('avatar_path')->nullable()->after('timezone');
            $table->timestamp('last_login_at')->nullable()->after('avatar_path');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex(['status']);
            $table->dropColumn(['phone', 'status', 'timezone', 'avatar_path', 'last_login_at']);
        });
    }
};
