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
        ];
    }

    public function messages()
    {
        return [
            'license_plate.required' => 'La placa es obligatoria.',
            'license_plate.max' => 'La placa no puede tener más de 10 caracteres.',
        ];
    }

    public function save(): Chassis
    {
        return DB::transaction(function () {
            // Create chassis
            $chassis = Chassis::create([
                'license_plate' => strtoupper($this->license_plate),
                'company_id' => auth()->user()->company_id,
                'status' => ChassisStatusEnum::INACTIVE->value,
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
