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
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('email')->nullable()->after('lastname');
            $table->string('phone')->nullable()->after('email');
            $table->string('appeal_token', 64)->nullable()->unique()->after('status');
            $table->timestamp('appeal_token_expires_at')->nullable()->after('appeal_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['email', 'phone', 'appeal_token', 'appeal_token_expires_at']);
        });
    }
};
