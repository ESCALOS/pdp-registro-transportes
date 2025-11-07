<?php

namespace App\Livewire\Forms;

use App\Enums\{DriverStatusEnum, DocumentTypeEnum};
use App\Models\{Driver, Document};
use Illuminate\Support\Facades\{Auth, DB, Log, Storage};
use Livewire\Form;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DriverForm extends Form
{
    public string $document_number = '';
    public string $name = '';
    public string $lastname = '';
    public string $license_number = '';

    // Documents
    public $documents = [];
    public $document_dates = [];

    public function initializeDocuments()
    {
        $this->documents = [
            'dni' => null,
            'licencia' => null,
            'pbip' => null,
            'seg_portuaria' => null,
            'merc_peligrosas' => null,
            'sctr' => null,
            'induc' => null,
            'decla' => null,
        ];

        $this->document_dates = [
            'dni' => '',
            'licencia' => '',
            'pbip' => '',
            'seg_portuaria' => '',
            'merc_peligrosas' => '',
            'sctr' => '',
            'induc' => '',
            'decla' => '',
        ];
    }

    public function resetForm()
    {
        $this->reset();
        $this->initializeDocuments();
        $this->resetValidation();
    }

    public function rules()
    {
        return [
            'document_number' => [
                'required',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    $exists = Driver::where('company_id', Auth::user()->company_id)
                        ->where('document_number', $value)
                        ->exists();

                    if ($exists) {
                        $fail('Ya existe un conductor registrado con este número de documento en esta empresa.');
                    }
                }
            ],
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'license_number' => 'required|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'document_number.required' => 'El DNI es obligatorio.',
            'document_number.max' => 'El DNI no puede tener más de 20 caracteres.',
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'lastname.required' => 'Los apellidos son obligatorios.',
            'lastname.max' => 'Los apellidos no pueden tener más de 255 caracteres.',
            'license_number.required' => 'El número de licencia es obligatorio.',
            'license_number.max' => 'El número de licencia no puede tener más de 50 caracteres.',
        ];
    }

    public function checkDuplicate()
    {
        Log::info('checkDuplicate called with: ' . $this->document_number);

        $this->resetErrorBag('form.document_number');

        if (strlen($this->document_number) >= 8) {
            $exists = Driver::where('company_id', Auth::user()->company_id)
                ->where('document_number', $this->document_number)
                ->exists();

            Log::info('Driver exists: ' . ($exists ? 'YES' : 'NO'));

            if ($exists) {
                $this->addError('form.document_number', 'Ya existe un conductor registrado con este número de documento en esta empresa.');
            }
        }
    }

    public function save(): Driver
    {
        return DB::transaction(function () {
            // Create driver
            $driver = Driver::create([
                'document_number' => $this->document_number,
                'name' => $this->name,
                'lastname' => $this->lastname,
                'license_number' => $this->license_number,
                'company_id' => Auth::user()->company_id,
                'document_type' => 1, // DNI
                'status' => DriverStatusEnum::DOCUMENT_REVIEW->value,
            ]);

            // Save documents
            foreach ($this->documents as $type => $file) {
                if ($file) {
                    $this->saveDocument($driver, $type, $file, $this->document_dates[$type] ?? null);
                }
            }

            return $driver;
        });
    }

    private function saveDocument(Driver $driver, string $type, TemporaryUploadedFile $file, ?string $expirationDate)
    {
        $documentTypeEnum = $this->mapDocumentType($type);

        if (!$documentTypeEnum) {
            return;
        }

        $filename = $driver->document_number . '_' . $type . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('driver-documents/' . $driver->document_number, $filename, 'public');

        Document::create([
            'documentable_type' => Driver::class,
            'documentable_id' => $driver->id,
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
            'dni' => DocumentTypeEnum::DNI,
            'licencia' => DocumentTypeEnum::LICENCIA_A3,
            'pbip' => DocumentTypeEnum::CURSO_PBIP,
            'seg_portuaria' => DocumentTypeEnum::CURSO_SEGURIDAD_PORTUARIA,
            'merc_peligrosas' => DocumentTypeEnum::CURSO_MERCANCIAS,
            'sctr' => DocumentTypeEnum::SCTR,
            'induc' => DocumentTypeEnum::INDUCCION_SEGURIDAD,
            'decla' => DocumentTypeEnum::DECLARACION_JURADA,
            default => null,
        };
    }
}
