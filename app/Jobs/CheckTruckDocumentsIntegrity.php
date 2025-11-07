<?php

namespace App\Jobs;

use App\Enums\TruckStatusEnum;
use App\Models\Truck;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CheckTruckDocumentsIntegrity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Truck $truck
    ) {}

    public function handle(): void
    {
        // NO usar sleep aquí - el delay ya está configurado en el dispatch
        $hasDeletedDocuments = false;

        // Recargar el truck con sus documentos actuales
        $this->truck->refresh();
        $this->truck->load('documents');

        foreach ($this->truck->documents as $document) {
            // Verificar si el archivo físico existe en el disco 'public'
            if (!Storage::disk('public')->exists($document->path)) {
                $hasDeletedDocuments = true;
                break;
            }
        }

        if ($hasDeletedDocuments) {
            // Marcar como documentos infectados/eliminados
            $this->truck->update([
                'status' => TruckStatusEnum::INFECTED_DOCUMENTS
            ]);
        } else {
            // Si todo está bien, cambiar a espera de aprobación
            $this->truck->update([
                'status' => TruckStatusEnum::PENDING_APPROVAL
            ]);
        }
    }
}
