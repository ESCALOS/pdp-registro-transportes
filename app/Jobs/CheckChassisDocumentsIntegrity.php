<?php

namespace App\Jobs;

use App\Enums\ChassisStatusEnum;
use App\Models\Chassis;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CheckChassisDocumentsIntegrity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Chassis $chassis
    ) {}

    public function handle(): void
    {
        // NO usar sleep aquí - el delay ya está configurado en el dispatch
        $hasDeletedDocuments = false;

        // Recargar el chassis con sus documentos actuales
        $this->chassis->refresh();
        $this->chassis->load('documents');

        foreach ($this->chassis->documents as $document) {
            // Verificar si el archivo físico existe en el disco 'public'
            if (!Storage::disk('public')->exists($document->path)) {
                $hasDeletedDocuments = true;
                break;
            }
        }

        if ($hasDeletedDocuments) {
            // Marcar como documentos infectados/eliminados
            $this->chassis->update([
                'status' => ChassisStatusEnum::INFECTED_DOCUMENTS
            ]);
        } else {
            // Si todo está bien, cambiar a espera de aprobación
            $this->chassis->update([
                'status' => ChassisStatusEnum::PENDING_APPROVAL
            ]);
        }
    }
}
