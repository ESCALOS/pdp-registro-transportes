<?php

namespace Database\Seeders;

use App\Enums\DocumentStatusEnum;
use App\Enums\DocumentTypeEnum;
use App\Enums\DriverStatusEnum;
use App\Models\Driver;
use App\Models\Document;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $driverDocumentTypes = [
            DocumentTypeEnum::DNI->value,
            DocumentTypeEnum::LICENCIA_A1->value,
            DocumentTypeEnum::LICENCIA_A3->value,
            DocumentTypeEnum::CURSO_PBIP->value,
            DocumentTypeEnum::CURSO_SEGURIDAD_PORTUARIA->value,
            DocumentTypeEnum::CURSO_MERCANCIAS->value,
            DocumentTypeEnum::SCTR->value,
            DocumentTypeEnum::INDUCCION_SEGURIDAD->value,
            DocumentTypeEnum::DECLARACION_JURADA->value,
        ];

        // Estado 1: Inactivo - sin documentos
        $driver1 = Driver::factory()->create([
            'company_id' => 1,
            'status' => 1,
        ]);

        // Estado 2: Activo - todos los documentos aprobados
        $driver2 = Driver::factory()->create([
            'company_id' => 1,
            'status' => 2,
        ]);
        foreach ($driverDocumentTypes as $docType) {
            Document::create([
                'documentable_type' => Driver::class,
                'documentable_id' => $driver2->id,
                'type' => $docType,
                'path' => 'documents/drivers/' . fake()->uuid() . '.pdf',
                'submitted_date' => now()->subDays(rand(30, 60)),
                'expiration_date' => now()->addMonths(rand(6, 12)),
                'status' => DocumentStatusEnum::APPROVED,
                'validated_by' => 1,
                'validated_date' => now()->subDays(rand(20, 50)),
            ]);
        }

        // Estado 3: Necesita Actualización - algunos documentos vencidos
        $driver3 = Driver::factory()->create([
            'company_id' => 1,
            'status' => 3,
        ]);
        foreach ($driverDocumentTypes as $index => $docType) {
            Document::create([
                'documentable_type' => Driver::class,
                'documentable_id' => $driver3->id,
                'type' => $docType,
                'path' => 'documents/drivers/' . fake()->uuid() . '.pdf',
                'submitted_date' => now()->subDays(rand(100, 200)),
                'expiration_date' => $index % 3 === 0 ? now()->subDays(rand(1, 30)) : now()->addMonths(rand(6, 12)),
                'status' => $index % 3 === 0 ? DocumentStatusEnum::NEEDS_UPDATE : DocumentStatusEnum::APPROVED,
                'validated_by' => 1,
                'validated_date' => now()->subDays(rand(90, 180)),
            ]);
        }

        // Estado 4: Espera de aprobación - todos los documentos pendientes
        $driver4 = Driver::factory()->create([
            'company_id' => 1,
            'status' => 4,
        ]);
        foreach ($driverDocumentTypes as $docType) {
            Document::create([
                'documentable_type' => Driver::class,
                'documentable_id' => $driver4->id,
                'type' => $docType,
                'path' => 'documents/drivers/' . fake()->uuid() . '.pdf',
                'submitted_date' => now()->subDays(rand(1, 10)),
                'expiration_date' => now()->addMonths(rand(6, 12)),
                'status' => DocumentStatusEnum::PENDING,
            ]);
        }

        // Estado 5: Revisión Documentos - algunos documentos rechazados
        $driver5 = Driver::factory()->create([
            'company_id' => 1,
            'status' => 5,
        ]);
        foreach ($driverDocumentTypes as $index => $docType) {
            Document::create([
                'documentable_type' => Driver::class,
                'documentable_id' => $driver5->id,
                'type' => $docType,
                'path' => 'documents/drivers/' . fake()->uuid() . '.pdf',
                'submitted_date' => now()->subDays(rand(10, 20)),
                'expiration_date' => now()->addMonths(rand(6, 12)),
                'status' => $index % 2 === 0 ? DocumentStatusEnum::REJECTED : DocumentStatusEnum::APPROVED,
                'rejection_reason' => $index % 2 === 0 ? 'Documento ilegible o incompleto' : null,
                'validated_by' => 1,
                'validated_date' => now()->subDays(rand(5, 15)),
            ]);
        }

        // Estado 6: Documentos Infectados - algunos documentos con malware
        $driver6 = Driver::factory()->create([
            'company_id' => 1,
            'status' => 6,
        ]);
        foreach ($driverDocumentTypes as $index => $docType) {
            Document::create([
                'documentable_type' => Driver::class,
                'documentable_id' => $driver6->id,
                'type' => $docType,
                'path' => 'documents/drivers/' . fake()->uuid() . '.pdf',
                'submitted_date' => now()->subDays(rand(1, 5)),
                'expiration_date' => now()->addMonths(rand(6, 12)),
                'status' => $index === 0 || $index === 1 ? DocumentStatusEnum::INFECTED_DOCUMENTS : DocumentStatusEnum::PENDING,
            ]);
        }

        // Drivers adicionales con diferentes estados
        $driver7 = Driver::factory()->create([
            'company_id' => 1,
            'status' => 2,
        ]);
        foreach (fake()->randomElements($driverDocumentTypes, rand(5, 9)) as $docType) {
            Document::create([
                'documentable_type' => Driver::class,
                'documentable_id' => $driver7->id,
                'type' => $docType,
                'path' => 'documents/drivers/' . fake()->uuid() . '.pdf',
                'submitted_date' => now()->subDays(rand(30, 60)),
                'expiration_date' => now()->addMonths(rand(6, 12)),
                'status' => DocumentStatusEnum::APPROVED,
                'validated_by' => 1,
                'validated_date' => now()->subDays(rand(20, 50)),
            ]);
        }

        $driver8 = Driver::factory()->create([
            'company_id' => 1,
            'status' => 4,
        ]);
        foreach (fake()->randomElements($driverDocumentTypes, rand(4, 7)) as $docType) {
            Document::create([
                'documentable_type' => Driver::class,
                'documentable_id' => $driver8->id,
                'type' => $docType,
                'path' => 'documents/drivers/' . fake()->uuid() . '.pdf',
                'submitted_date' => now()->subDays(rand(1, 10)),
                'expiration_date' => now()->addMonths(rand(6, 12)),
                'status' => DocumentStatusEnum::PENDING,
            ]);
        }
    }
}
