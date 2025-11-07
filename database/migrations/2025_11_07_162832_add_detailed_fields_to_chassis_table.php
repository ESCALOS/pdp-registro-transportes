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
        Schema::table('chassis', function (Blueprint $table) {
            $table->string('vehicle_type')->nullable()->after('license_plate');
            $table->integer('axle_count')->nullable()->after('vehicle_type');
            $table->boolean('has_bonus')->default(false)->after('axle_count');
            $table->decimal('tare', 8, 2)->nullable()->after('has_bonus');
            $table->decimal('safe_weight', 8, 2)->nullable()->after('tare');
            $table->decimal('height', 8, 2)->nullable()->after('safe_weight');
            $table->decimal('length', 8, 2)->nullable()->after('height');
            $table->decimal('width', 8, 2)->nullable()->after('length');
            $table->boolean('is_insulated')->default(false)->after('width');
            $table->string('material')->nullable()->after('is_insulated');
            $table->boolean('accepts_20ft')->default(false)->after('material');
            $table->boolean('accepts_40ft')->default(false)->after('accepts_20ft');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chassis', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_type',
                'axle_count',
                'has_bonus',
                'tare',
                'safe_weight',
                'height',
                'length',
                'width',
                'is_insulated',
                'material',
                'accepts_20ft',
                'accepts_40ft',
            ]);
        });
    }
};
