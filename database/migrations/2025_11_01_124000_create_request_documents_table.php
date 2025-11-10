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
        Schema::create('request_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_item_id')->constrained()->onDelete('cascade');
            $table->string('type')->comment('Tipo de documento según el itemable_type');
            $table->string('path');
            $table->date('submitted_date')->comment('Fecha de subida del documento');
            $table->date('expiration_date')->comment('Fecha de vencimiento del documento');
            $table->integer('status')->default(1)->comment('1: Pendiente, 2: Aprobado, 3: Rechazado');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['request_item_id', 'type']);
            $table->index('expiration_date');
            $table->index(['request_item_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_documents');
    }
};
