<?php

namespace App\Observers;

use App\Enums\ChassisStatusEnum;
use App\Enums\DriverStatusEnum;
use App\Enums\TruckStatusEnum;
use App\Jobs\CheckChassisDocumentsIntegrity;
use App\Jobs\CheckDriverDocumentsIntegrity;
use App\Jobs\CheckTruckDocumentsIntegrity;
use App\Models\Chassis;
use App\Models\Document;
use App\Models\Driver;
use App\Models\Truck;
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

        // Si el documento pertenece a un truck
        if ($document->documentable_type === Truck::class) {
            $truck = $document->documentable;
            
            // Si el truck está en alguno de estos estados, procesar
            if ($truck && in_array($truck->status, [
                TruckStatusEnum::INACTIVE, 
                TruckStatusEnum::NEEDS_UPDATE,
                TruckStatusEnum::DOCUMENT_REVIEW
            ])) {
                // Asegurar que esté en revisión de documentos
                $truck->update([
                    'status' => TruckStatusEnum::DOCUMENT_REVIEW
                ]);

                // Despachar job para verificar integridad después de 1 minuto
                CheckTruckDocumentsIntegrity::dispatch($truck)->delay(now()->addMinute());
            }
        }

        // Si el documento pertenece a un chassis
        if ($document->documentable_type === Chassis::class) {
            $chassis = $document->documentable;
            
            // Si el chassis está en alguno de estos estados, procesar
            if ($chassis && in_array($chassis->status, [
                ChassisStatusEnum::INACTIVE, 
                ChassisStatusEnum::NEEDS_UPDATE,
                ChassisStatusEnum::DOCUMENT_REVIEW
            ])) {
                // Asegurar que esté en revisión de documentos
                $chassis->update([
                    'status' => ChassisStatusEnum::DOCUMENT_REVIEW
                ]);

                // Despachar job para verificar integridad después de 1 minuto
                CheckChassisDocumentsIntegrity::dispatch($chassis)->delay(now()->addMinute());
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

        if ($document->wasChanged('path') && $document->documentable_type === Truck::class) {
            $truck = $document->documentable;
            
            if ($truck && !Storage::disk('public')->exists($document->path)) {
                // Marcar truck como documentos infectados
                $truck->update([
                    'status' => TruckStatusEnum::INFECTED_DOCUMENTS
                ]);
            }
        }

        if ($document->wasChanged('path') && $document->documentable_type === Chassis::class) {
            $chassis = $document->documentable;
            
            if ($chassis && !Storage::disk('public')->exists($document->path)) {
                // Marcar chassis como documentos infectados
                $chassis->update([
                    'status' => ChassisStatusEnum::INFECTED_DOCUMENTS
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

        // Si el documento pertenece a un truck y fue eliminado
        if ($document->documentable_type === Truck::class) {
            $truck = $document->documentable;
            
            if ($truck) {
                // Marcar truck como documentos infectados
                $truck->update([
                    'status' => TruckStatusEnum::INFECTED_DOCUMENTS
                ]);
            }
        }

        // Si el documento pertenece a un chassis y fue eliminado
        if ($document->documentable_type === Chassis::class) {
            $chassis = $document->documentable;
            
            if ($chassis) {
                // Marcar chassis como documentos infectados
                $chassis->update([
                    'status' => ChassisStatusEnum::INFECTED_DOCUMENTS
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

        // Si se restaura un documento de truck
        if ($document->documentable_type === Truck::class) {
            $truck = $document->documentable;
            
            if ($truck && $truck->status === TruckStatusEnum::INFECTED_DOCUMENTS) {
                // Volver a revisión de documentos
                $truck->update([
                    'status' => TruckStatusEnum::DOCUMENT_REVIEW
                ]);

                // Despachar job para verificar integridad
                CheckTruckDocumentsIntegrity::dispatch($truck)->delay(now()->addMinute());
            }
        }

        // Si se restaura un documento de chassis
        if ($document->documentable_type === Chassis::class) {
            $chassis = $document->documentable;
            
            if ($chassis && $chassis->status === ChassisStatusEnum::INFECTED_DOCUMENTS) {
                // Volver a revisión de documentos
                $chassis->update([
                    'status' => ChassisStatusEnum::DOCUMENT_REVIEW
                ]);

                // Despachar job para verificar integridad
                CheckChassisDocumentsIntegrity::dispatch($chassis)->delay(now()->addMinute());
            }
        }
    }
}
