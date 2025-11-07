<?php

namespace App\Livewire\Forms;

use App\Enums\{TruckStatusEnum, DocumentTypeEnum};
use App\Models\{Truck, Document};
use Illuminate\Support\Facades\{DB, Storage};
use Livewire\Form;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TruckForm extends Form
{
    public string $license_plate = '';
    public string $nationality = '';
    public bool $is_internal = false;
    public string $truck_type = '';
    public bool $has_bonus = false;
    public ?string $tare = '';
    
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
                    $exists = Truck::where('company_id', auth()->user()->company_id)
                        ->where('license_plate', $value)
                        ->exists();
                    
                    if ($exists) {
                        $fail('Ya existe un vehículo registrado con esta placa en esta empresa.');
                    }
                }
            ],
            'nationality' => 'required|string|max:50',
            'is_internal' => 'boolean',
            'truck_type' => 'required|string|max:50',
            'has_bonus' => 'boolean',
            'tare' => 'nullable|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'license_plate.required' => 'La placa es obligatoria.',
            'license_plate.max' => 'La placa no puede tener más de 10 caracteres.',
            'nationality.required' => 'La nacionalidad es obligatoria.',
            'truck_type.required' => 'El tipo de camión es obligatorio.',
            'tare.numeric' => 'La tara debe ser un número.',
            'tare.min' => 'La tara debe ser mayor o igual a 0.',
        ];
    }

    public function save(): Truck
    {
        return DB::transaction(function () {
            // Create truck
            $truck = Truck::create([
                'license_plate' => strtoupper($this->license_plate),
                'nationality' => $this->nationality,
                'is_internal' => $this->is_internal,
                'truck_type' => $this->truck_type,
                'has_bonus' => $this->has_bonus,
                'tare' => $this->tare ? (float) $this->tare : null,
                'company_id' => auth()->user()->company_id,
                'status' => TruckStatusEnum::PENDING_APPROVAL->value,
            ]);

            // Save documents
            foreach ($this->documents as $type => $file) {
                if ($file) {
                    $this->saveDocument($truck, $type, $file, $this->document_dates[$type] ?? null);
                }
            }

            return $truck;
        });
    }

    private function saveDocument(Truck $truck, string $type, TemporaryUploadedFile $file, ?string $expirationDate)
    {
        $documentTypeEnum = $this->mapDocumentType($type);
        
        if (!$documentTypeEnum) {
            return;
        }

        $filename = $truck->license_plate . '_' . $type . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('truck-documents/' . $truck->license_plate, $filename, 'public');

        Document::create([
            'documentable_type' => Truck::class,
            'documentable_id' => $truck->id,
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
            'tarjeta_propiedad' => DocumentTypeEnum::TARJETA_PROPIEDAD,
            'soat' => DocumentTypeEnum::SOAT,
            'poliza_seguro' => DocumentTypeEnum::POLIZA_SEGURO,
            'bonificacion' => DocumentTypeEnum::BONIFICACION,
            'habilitacion_mtc' => DocumentTypeEnum::HABILITACION_MTC,
            'revision_tecnica' => DocumentTypeEnum::REVISION_TECNICA,
            default => null,
        };
    }
}
