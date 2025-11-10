<?php

namespace App\Livewire\Forms;

use App\Enums\{ChassisStatusEnum, DocumentTypeEnum};
use App\Models\{Chassis, Document};
use Illuminate\Support\Facades\{DB, Storage};
use Livewire\Form;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ChassisForm extends Form
{
    public string $license_plate = '';
    public string $vehicle_type = '';
    public ?int $axle_count = null;
    public bool $has_bonus = false;
    public ?string $tare = '';
    public ?string $safe_weight = '';
    public ?string $height = '';
    public ?string $length = '';
    public ?string $width = '';
    public bool $is_insulated = false;
    public string $material = '';
    public bool $accepts_20ft = false;
    public bool $accepts_40ft = false;
    
    // Documents
    public $documents = [];
    public $document_dates = [];

    public function rules()
    {
        return [
            'license_plate' => [
                'required',
                'string',
                'max:10',
                function ($attribute, $value, $fail) {
                    $exists = Chassis::where('company_id', auth()->user()->company_id)
                        ->where('license_plate', $value)
                        ->exists();
                    
                    if ($exists) {
                        $fail('Ya existe un chassis registrado con esta placa en esta empresa.');
                    }
                }
            ],
            'vehicle_type' => 'required|string|max:100',
            'axle_count' => 'nullable|integer|min:1|max:10',
            'has_bonus' => 'boolean',
            'tare' => 'nullable|numeric|min:0',
            'safe_weight' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'is_insulated' => 'boolean',
            'material' => 'nullable|string|max:100',
            'accepts_20ft' => 'boolean',
            'accepts_40ft' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'license_plate.required' => 'La placa es obligatoria.',
            'license_plate.max' => 'La placa no puede tener más de 10 caracteres.',
            'vehicle_type.required' => 'El tipo de vehículo es obligatorio.',
            'vehicle_type.max' => 'El tipo de vehículo no puede tener más de 100 caracteres.',
            'axle_count.integer' => 'El número de ejes debe ser un número entero.',
            'axle_count.min' => 'El número de ejes debe ser al menos 1.',
            'axle_count.max' => 'El número de ejes no puede ser mayor a 10.',
            'tare.numeric' => 'La tara debe ser un número.',
            'tare.min' => 'La tara debe ser mayor o igual a 0.',
            'safe_weight.numeric' => 'El peso seguro debe ser un número.',
            'safe_weight.min' => 'El peso seguro debe ser mayor o igual a 0.',
            'height.numeric' => 'La altura debe ser un número.',
            'height.min' => 'La altura debe ser mayor o igual a 0.',
            'length.numeric' => 'El largo debe ser un número.',
            'length.min' => 'El largo debe ser mayor o igual a 0.',
            'width.numeric' => 'El ancho debe ser un número.',
            'width.min' => 'El ancho debe ser mayor o igual a 0.',
            'material.max' => 'El material no puede tener más de 100 caracteres.',
        ];
    }

    private function getRequiredDocuments(): array
    {
        return ['habilitacion_mtc', 'bonificacion', 'revision_tecnica'];
    }

    public function validateDocuments(): array
    {
        $errors = [];
        $requiredDocs = $this->getRequiredDocuments();
        
        foreach ($requiredDocs as $docKey) {
            // Validar que el documento esté adjunto
            if (empty($this->documents[$docKey])) {
                $errors["documents.{$docKey}"] = $this->getDocumentLabel($docKey) . ' es obligatorio.';
            }
            
            // Validar que tenga fecha de vencimiento
            if (empty($this->document_dates[$docKey])) {
                $errors["document_dates.{$docKey}"] = 'La fecha de vencimiento de ' . $this->getDocumentLabel($docKey) . ' es obligatoria.';
            }
        }
        
        return $errors;
    }

    private function getDocumentLabel(string $key): string
    {
        return match($key) {
            'habilitacion_mtc' => 'Habilitación MTC',
            'bonificacion' => 'Bonificación',
            'revision_tecnica' => 'Revisión Técnica',
            default => $key,
        };
    }

    public function save(): Chassis
    {
        // Validar datos básicos
        $this->validate();
        
        // Validar documentos obligatorios
        $documentErrors = $this->validateDocuments();
        if (!empty($documentErrors)) {
            foreach ($documentErrors as $key => $message) {
                // Agregar prefijo 'form.' para que coincida con la vista Livewire
                $this->addError('form.' . $key, $message);
            }
            throw new \Exception('Por favor, complete todos los documentos obligatorios y sus fechas de vencimiento.');
        }
        
        return DB::transaction(function () {
            // Create chassis
            $chassis = Chassis::create([
                'license_plate' => strtoupper($this->license_plate),
                'vehicle_type' => $this->vehicle_type,
                'axle_count' => $this->axle_count,
                'has_bonus' => $this->has_bonus,
                'tare' => $this->tare ? (float) $this->tare : null,
                'safe_weight' => $this->safe_weight ? (float) $this->safe_weight : null,
                'height' => $this->height ? (float) $this->height : null,
                'length' => $this->length ? (float) $this->length : null,
                'width' => $this->width ? (float) $this->width : null,
                'is_insulated' => $this->is_insulated,
                'material' => $this->material,
                'accepts_20ft' => $this->accepts_20ft,
                'accepts_40ft' => $this->accepts_40ft,
                'company_id' => auth()->user()->company_id,
                'status' => ChassisStatusEnum::PENDING_APPROVAL->value,
            ]);

            // Save documents
            foreach ($this->documents as $type => $file) {
                if ($file) {
                    $this->saveDocument($chassis, $type, $file, $this->document_dates[$type] ?? null);
                }
            }

            return $chassis;
        });
    }

    private function saveDocument(Chassis $chassis, string $type, TemporaryUploadedFile $file, ?string $expirationDate)
    {
        $documentTypeEnum = $this->mapDocumentType($type);
        
        if (!$documentTypeEnum) {
            return;
        }

        $filename = $chassis->license_plate . '_' . $type . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('chassis-documents/' . $chassis->license_plate, $filename, 'public');

        Document::create([
            'documentable_type' => Chassis::class,
            'documentable_id' => $chassis->id,
            'type' => $documentTypeEnum->value,
            'path' => $path,
            'submitted_date' => now(),
            'expiration_date' => $expirationDate,
            'status' => 1,
        ]);
    }

    private function mapDocumentType(string $key): ?DocumentTypeEnum
    {
        return match($key) {
            'habilitacion_mtc' => DocumentTypeEnum::CHASSIS_HABILITACION_MTC,
            'bonificacion' => DocumentTypeEnum::CHASSIS_BONIFICACION,
            'revision_tecnica' => DocumentTypeEnum::CHASSIS_REVISION_TECNICA,
            default => null,
        };
    }
}
