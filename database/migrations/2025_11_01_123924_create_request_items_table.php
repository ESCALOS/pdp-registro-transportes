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
        Schema::create('request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained()->onDelete('cascade');
            $table->string('itemable_type')->comment('Driver, Truck, Chassis');
            $table->unsignedBigInteger('itemable_id');
            $table->integer('status')->default(1)->comment('1: Pendiente, 2: Aprobado, 3: Rechazado');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['itemable_type', 'itemable_id']);
            $table->unique(['request_id', 'itemable_type', 'itemable_id']);
            $table->index(['request_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_items');
    }
};
