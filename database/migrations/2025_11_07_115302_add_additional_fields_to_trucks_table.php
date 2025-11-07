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
        Schema::table('trucks', function (Blueprint $table) {
            $table->string('nationality')->nullable()->after('license_plate')->comment('Nacionalidad del vehículo');
            $table->boolean('is_internal')->default(false)->after('nationality')->comment('Si es interno');
            $table->string('truck_type')->nullable()->after('is_internal')->comment('Tipo: T3, T-Especial, etc.');
            $table->boolean('has_bonus')->default(false)->after('truck_type')->comment('Si tiene bonificación');
            $table->decimal('tare', 10, 2)->nullable()->after('has_bonus')->comment('Tara del vehículo en toneladas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropColumn([
                'nationality',
                'is_internal',
                'truck_type',
                'has_bonus',
                'tare'
            ]);
        });
    }
};
