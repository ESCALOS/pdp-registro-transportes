<?php

namespace App\Jobs;

use App\Enums\DriverStatusEnum;
use App\Models\Driver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CheckDriverDocumentsIntegrity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Driver $driver
    ) {}

    public function handle(): void
    {
        // NO usar sleep aquí - el delay ya está configurado en el dispatch
        $hasDeletedDocuments = false;

        // Recargar el conductor con sus documentos actuales
        $this->driver->refresh();
        $this->driver->load('documents');

        foreach ($this->driver->documents as $document) {
            // Verificar si el archivo físico existe en el disco 'public'
            if (!Storage::disk('public')->exists($document->path)) {
                $hasDeletedDocuments = true;
                break;
            }
        }

        if ($hasDeletedDocuments) {
            // Marcar como documentos infectados/eliminados
            $this->driver->update([
                'status' => DriverStatusEnum::INFECTED_DOCUMENTS
            ]);
        } else {
            // Si todo está bien, cambiar a espera de aprobación
            $this->driver->update([
                'status' => DriverStatusEnum::PENDING_APPROVAL
            ]);
        }
    }
}
