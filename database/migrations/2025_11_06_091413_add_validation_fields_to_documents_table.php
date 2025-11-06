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
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('documents', 'validated_by')) {
                $table->foreignId('validated_by')->nullable()->constrained('users')->after('status');
            }
            if (!Schema::hasColumn('documents', 'validated_date')) {
                $table->timestamp('validated_date')->nullable()->after('validated_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'validated_by')) {
                $table->dropForeign(['validated_by']);
            }
            $table->dropColumn(['rejection_reason', 'validated_by', 'validated_date']);
        });
    }
};
