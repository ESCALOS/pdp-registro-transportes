<?php

namespace App\Observers;

use App\Enums\DriverStatusEnum;
use App\Jobs\CheckDriverDocumentsIntegrity;
use App\Models\Document;
use App\Models\Driver;
use Illuminate\Support\Facades\Storage;

class DocumentObserver
{
    /**
     * Handle the Document "created" event.
     */
    public function created(Document $document): void
    {
        // Si el documento pertenece a un conductor
        if ($document->documentable_type === Driver::class) {
            $driver = $document->documentable;
            
            // Si el conductor está en alguno de estos estados, procesar
            if ($driver && in_array($driver->status, [
                DriverStatusEnum::INACTIVE, 
                DriverStatusEnum::NEEDS_UPDATE,
                DriverStatusEnum::DOCUMENT_REVIEW
            ])) {
                // Asegurar que esté en revisión de documentos
                $driver->update([
                    'status' => DriverStatusEnum::DOCUMENT_REVIEW
                ]);

                // Despachar job para verificar integridad después de 1 minuto
                CheckDriverDocumentsIntegrity::dispatch($driver)->delay(now()->addMinute());
            }
        }
    }

    /**
     * Handle the Document "updated" event.
     */
    public function updated(Document $document): void
    {
        // Solo verificar integridad si el path del archivo cambió
        // No cuando se actualiza status, rejection_reason, etc.
        if ($document->wasChanged('path') && $document->documentable_type === Driver::class) {
            $driver = $document->documentable;
            
            if ($driver && !Storage::disk('public')->exists($document->path)) {
                // Marcar conductor como documentos infectados
                $driver->update([
                    'status' => DriverStatusEnum::INFECTED_DOCUMENTS
                ]);
            }
        }
    }

    /**
     * Handle the Document "deleted" event.
     */
    public function deleted(Document $document): void
    {
        // Si el documento pertenece a un conductor y fue eliminado
        if ($document->documentable_type === Driver::class) {
            $driver = $document->documentable;
            
            if ($driver) {
                // Marcar conductor como documentos infectados
                $driver->update([
                    'status' => DriverStatusEnum::INFECTED_DOCUMENTS
                ]);
            }
        }
    }

    /**
     * Handle the Document "restored" event.
     */
    public function restored(Document $document): void
    {
        // Si se restaura un documento, revisar el estado del conductor
        if ($document->documentable_type === Driver::class) {
            $driver = $document->documentable;
            
            if ($driver && $driver->status === DriverStatusEnum::INFECTED_DOCUMENTS) {
                // Volver a revisión de documentos
                $driver->update([
                    'status' => DriverStatusEnum::DOCUMENT_REVIEW
                ]);

                // Despachar job para verificar integridad
                CheckDriverDocumentsIntegrity::dispatch($driver)->delay(now()->addMinute());
            }
        }
    }
}
