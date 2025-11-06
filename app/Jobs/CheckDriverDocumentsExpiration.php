<?php

namespace App\Jobs;

use App\Enums\DriverStatusEnum;
use App\Models\Driver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class CheckDriverDocumentsExpiration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Obtener conductores activos
        $drivers = Driver::where('status', DriverStatusEnum::ACTIVE)->get();

        foreach ($drivers as $driver) {
            $hasExpiredDocuments = false;

            foreach ($driver->documents as $document) {
                // Verificar si el documento tiene fecha de vencimiento y ya venció
                if ($document->expiration_date && Carbon::parse($document->expiration_date)->isPast()) {
                    $hasExpiredDocuments = true;
                    break;
                }
            }

            if ($hasExpiredDocuments) {
                // Cambiar estado a necesita actualización e inactivo
                $driver->update([
                    'status' => DriverStatusEnum::NEEDS_UPDATE
                ]);
            }
        }
    }
}
